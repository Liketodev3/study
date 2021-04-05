<?php

class DiscountCoupons extends MyAppModel
{

    const DB_TBL = 'tbl_coupons';
    const DB_TBL_PREFIX = 'coupon_';
    const DB_TBL_LANG = 'tbl_coupons_lang';
    const DB_TBL_LANG_PREFIX = 'coupon_';
    const DB_TBL_COUPON_HOLD = 'tbl_coupons_hold';
    const DB_TBL_COUPON_HOLD_PENDING_ORDER = 'tbl_coupons_hold_pending_order';
    const DB_TBL_COUPON_HISTORY = 'tbl_coupons_history';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getSearchObject($langId = 0, $active = true, $isDeleted = true)
    {
        $srch = new SearchBase(static::DB_TBL, 'dc');
        if ($langId > 0) {
            $srch->joinTable(static::DB_TBL_LANG, 'LEFT OUTER JOIN', 'couponlang_coupon_id = dc.coupon_id AND couponlang_lang_id = ' . $langId, 'dc_l');
        }
        if ($isDeleted == true) {
            $srch->addCondition('dc.' . static::DB_TBL_PREFIX . 'deleted', '=', applicationConstants::NO);
        }
        if ($active == true) {
            $srch->addCondition('dc.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }
        return $srch;
    }

    public function getCoupon($code, $langId = 0)
    {
        $langId = FatUtility::int($langId);
        if (!$code) {
            return false;
        }
        $status = true;
        $currDate = date('Y-m-d');
        $srch = static::getSearchObject($langId);
        $srch->addMultipleFields(['dc.*', 'IFNULL(dc_l.coupon_title,dc.coupon_identifier) as coupon_title']);
        $srch->addCondition('coupon_code', '=', $code);
        $cnd = $srch->addCondition('coupon_start_date', '=', '0000-00-00', 'AND');
        $cnd->attachCondition('coupon_start_date', '<=', $currDate, 'OR');
        $cnd1 = $srch->addCondition('coupon_end_date', '=', '0000-00-00', 'AND');
        $cnd1->attachCondition('coupon_end_date', '>=', $currDate, 'OR');
        $rs = $srch->getResultSet();
        $couponData = FatApp::getDb()->fetch($rs);
        if ($couponData == false) {
            return false;
        }
        $cartObj = new Cart();
        $cartSubTotal = $cartObj->getSubTotal($langId);
        if ($couponData['coupon_min_order_value'] > $cartSubTotal) {
            $status = false;
        }
        $chistorySrch = CouponHistory::getSearchObject();
        $chistorySrch->addCondition('couponhistory_coupon_id', '=', $couponData['coupon_id']);
        $chistorySrch->addMultipleFields(['count(couponhistory_id) as total']);
        $chistorySrch->doNotLimitRecords();
        $chistorySrch->doNotCalculateRecords();
        $chistoryRs = $chistorySrch->getResultSet();
        $couponHistoryData = FatApp::getDb()->fetch($chistoryRs);
        $interval = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 15 minute'));
        FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD, ['smt' => 'couponhold_added_on < ?', 'vals' => [$interval]]);
        $cHoldSrch = new SearchBase(static::DB_TBL_COUPON_HOLD);
        $cHoldSrch->addCondition('couponhold_coupon_id', '=', $couponData['coupon_id']);
        $cHoldSrch->addCondition('couponhold_added_on', '>=', $interval);
        $cHoldSrch->addCondition('couponhold_user_id', '!=', UserAuthentication::getLoggedUserId());
        $cHoldSrch->addMultipleFields(['count(couponhold_id) as total']);
        $cHoldSrch->doNotLimitRecords();
        $cHoldSrch->doNotCalculateRecords();
        $cHoldRs = $cHoldSrch->getResultSet();
        $couponHoldData = FatApp::getDb()->fetch($cHoldRs);
        $total = $couponHistoryData['total'] + $couponHoldData['total'];
        if ($couponData['coupon_uses_count'] > 0 && $total >= $couponData['coupon_uses_count']) {
            $status = false;
        }
        $userSpecificCoupon = false;
        if (UserAuthentication::isUserLogged()) {
            $userId = UserAuthentication::getLoggedUserId();
            $cUserhistorySrch = CouponHistory::getSearchObject();
            $cUserhistorySrch->addCondition('couponhistory_coupon_id', '=', $couponData['coupon_id']);
            $cUserhistorySrch->addCondition('couponhistory_user_id', '=', $userId);
            $cUserhistorySrch->addMultipleFields(['count(couponhistory_id) as total']);
            $cUserhistorySrch->doNotLimitRecords();
            $cUserhistorySrch->doNotCalculateRecords();
            $cUserhistoryRs = $cUserhistorySrch->getResultSet();
            $couponUserHistoryData = FatApp::getDb()->fetch($cUserhistoryRs);
            if ($couponData['coupon_uses_coustomer'] > 0 && $couponUserHistoryData['total'] >= $couponData['coupon_uses_coustomer']) {
                $status = false;
            }
            $couponUserData = static::getCouponUsers($couponData['coupon_id']);
            if (array_key_exists($userId, $couponUserData)) {
                $userSpecificCoupon = true;
            }
        }
        // Products
        $productData = ['group' => '', 'product' => ''];
        $products = $cartObj->getProducts($langId);
        $prodObj = new Product();
        if ($userSpecificCoupon) {
            foreach ($products as $product) {
                if ($product['is_batch']) {
                    $productData['group'][] = $product['prodgroup_id'];
                } else {
                    $productData['product'][] = $product['product_id'];
                }
            }
        } else {
            $couponProductData = static::getCouponProducts($couponData['coupon_id']);
            $couponCategoryData = static::getCouponCategories($couponData['coupon_id']);
            if (!empty($couponProductData) || !empty($couponCategoryData)) {
                foreach ($products as $product) {
                    if ($product['is_batch']) {
                        $productData['group'][] = [];
                    } else {
                        if (array_key_exists($product['product_id'], $couponProductData)) {
                            $productData['product'][] = $product['product_id'];
                            continue;
                        }
                        $prodCategories = $prodObj->getProductCategories($product['product_id']);
                        if (count($prodCategories > 0) && $prodCategories != false) {
                            foreach ($prodCategories as $category) {
                                if (array_key_exists($category['prodcat_id'], $couponCategoryData)) {
                                    $productData['product'][] = $product['product_id'];
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (empty($productData['product']) && empty($productData['group'])) {
            $status = false;
        }
        if ($status) {
            return array_merge($couponData, ["products" => $productData['product'], 'groups' => $productData['group']]);
        }
    }

    public static function getValidCoupons($userId, $langId, $coupon_code = '', $orderId = '')
    {
        $userId = FatUtility::int($userId);
        $langId = FatUtility::int($langId);
        if ($userId <= 0) {
            trigger_error(Label::getLabel("ERR_User_id_is_mandatory", $langId), E_USER_ERROR);
        }
        if ($langId <= 0) {
            trigger_error(Label::getLabel("ERR_Language_id_is_mandatory", $langId), E_USER_ERROR);
        }
        $currDate = date('Y-m-d');
        $interval = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 15 minute'));
        $cartObj = new Cart();
        $cartSubTotal = $cartObj->cartData($langId);
        $cartSubTotal = $cartSubTotal['total'];
        /* coupon history[ */
        $cHistorySrch = CouponHistory::getSearchObject();
        $cHistorySrch->doNotLimitRecords();
        $cHistorySrch->doNotCalculateRecords();
        $cHistorySrch->addGroupBy('couponhistory_coupon_id');
        $cHistorySrch->addMultipleFields(['count(couponhistory_id) as coupon_used_count', 'couponhistory_coupon_id']);
        /* ] */
        /* coupon User History[ */
        $userCouponHistorySrch = CouponHistory::getSearchObject();
        $userCouponHistorySrch->addCondition('couponhistory_user_id', '=', $userId);
        $userCouponHistorySrch->doNotLimitRecords();
        $userCouponHistorySrch->doNotCalculateRecords();
        /* ] */
        /* coupon temp hold for order[ */
        if ($orderId != '') {
            $pendingOrderHoldSrch = new SearchBase(DiscountCoupons::DB_TBL_COUPON_HOLD_PENDING_ORDER);
            $pendingOrderHoldSrch->addCondition('ochold_order_id', '!=', $orderId);
            $pendingOrderHoldSrch->addMultipleFields(['count(ochold_order_id) as pending_order_hold_count', 'ochold_coupon_id']);
            $pendingOrderHoldSrch->doNotLimitRecords();
            $pendingOrderHoldSrch->addGroupBy('ochold_coupon_id');
            $pendingOrderHoldSrch->doNotCalculateRecords();
        }
        /* ] */
        /* coupon temp hold[ */
        $cHoldSrch = new SearchBase(DiscountCoupons::DB_TBL_COUPON_HOLD);
        $cHoldSrch->addCondition('couponhold_added_on', '>=', $interval);
        $cHoldSrch->addCondition('couponhold_user_id', '!=', $userId);
        $cHoldSrch->addMultipleFields(['couponhold_coupon_id']);
        $cHoldSrch->doNotLimitRecords();
        $cHoldSrch->doNotCalculateRecords();
        /* ] */
        $srch = DiscountCoupons::getSearchObject($langId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->joinTable('(' . $cHistorySrch->getQuery() . ')', 'LEFT OUTER JOIN', 'coupon_history.couponhistory_coupon_id = dc.coupon_id', 'coupon_history');
        $srch->joinTable('(' . $cHoldSrch->getQuery() . ')', 'LEFT OUTER JOIN', 'dc.coupon_id = coupon_hold.couponhold_coupon_id', 'coupon_hold');
        $srch->joinTable('(' . $userCouponHistorySrch->getQuery() . ')', 'LEFT OUTER JOIN', 'dc.coupon_id = user_coupon_history.couponhistory_coupon_id', 'user_coupon_history');
        if ($orderId != '') {
            $srch->joinTable('(' . $pendingOrderHoldSrch->getQuery() . ')', 'LEFT OUTER JOIN', 'dc.coupon_id = ctop.ochold_coupon_id', 'ctop');
        }
        $cnd = $srch->addCondition('coupon_start_date', '=', '0000-00-00', 'AND');
        $cnd->attachCondition('coupon_start_date', '<=', $currDate, 'OR');
        $cnd1 = $srch->addCondition('coupon_end_date', '=', '0000-00-00', 'AND');
        $cnd1->attachCondition('coupon_end_date', '>=', $currDate, 'OR');
        $srch->addCondition('coupon_min_order_value', '<=', $cartSubTotal);
        if ($coupon_code != '') {
            $srch->addCondition('coupon_code', '=', $coupon_code);
        }
        $selectArr = ['dc.*', 'dc_l.coupon_description',
            'IFNULL(dc_l.coupon_title, dc.coupon_identifier) as coupon_title',
            'IFNULL(coupon_history.coupon_used_count, 0) as coupon_used_count',
            'IFNULL(COUNT(coupon_hold.couponhold_coupon_id), 0) as coupon_hold_count',
            'count(user_coupon_history.couponhistory_id) as user_coupon_used_count'];
        if ($orderId != '') {
            $selectArr = array_merge($selectArr, ['IFNULL(ctop.pending_order_hold_count,0) as pending_order_hold_count']);
        }
        $srch->addMultipleFields($selectArr);
        $srch->addGroupBy('dc.coupon_id');
        if ($orderId != '') {
            $srch->addHaving('coupon_uses_count', '>', 'mysql_func_coupon_used_count + coupon_hold_count + pending_order_hold_count', 'AND', true);
            $srch->addHaving('coupon_uses_coustomer', '>', 'mysql_func_user_coupon_used_count', 'AND', true);
        } else {
            $srch->addHaving('coupon_uses_count', '>', 'mysql_func_coupon_used_count + coupon_hold_count', 'AND', true);
            $srch->addHaving('coupon_uses_coustomer', '>', 'mysql_func_user_coupon_used_count', 'AND', true);
        }
        $rs = $srch->getResultSet();
        if ($coupon_code != '') {
            $data = FatApp::getDb()->fetch($rs);
        } else {
            $data = FatApp::getDb()->fetchAll($rs, 'coupon_id');
        }
        return $data;
    }

}
