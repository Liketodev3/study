<?php
class Cart extends FatModel
{
    private $cartData = array();
    private $cart_user_id;
    const DB_TBL = 'tbl_user_cart';
    const TYPE_TEACHER_BOOK = 1;
    const TYPE_GIFTCARD = 2;

    public function __construct($user_id = 0)
    {
        parent::__construct();
        $user_id = FatUtility::int($user_id);
        if ($user_id < 1) {
            $user_id = UserAuthentication::getLoggedUserId();
        }
        $this->cart_user_id = $user_id;
        $srch = new SearchBase('tbl_user_cart');
        $srch->addCondition('usercart_user_id', '=', $this->cart_user_id);
        $srch->addCondition('usercart_type', '=', CART::TYPE_TEACHER_BOOK);
        $rs = $srch->getResultSet();
        if ($row = FatApp::getDb()->fetch($rs)) {
            $this->SYSTEM_ARR['cart'] = unserialize($row["usercart_details"]);
            if (isset($this->SYSTEM_ARR['cart']['shopping_cart'])) {
                $this->SYSTEM_ARR['shopping_cart'] = $this->SYSTEM_ARR['cart']['shopping_cart'];
                unset($this->SYSTEM_ARR['cart']['shopping_cart']);
            }
        }
        if (!isset($this->SYSTEM_ARR['cart']) || !is_array($this->SYSTEM_ARR['cart'])) {
            $this->SYSTEM_ARR['cart'] = array();
        }
        if (!isset($this->SYSTEM_ARR['shopping_cart']) || !is_array($this->SYSTEM_ARR['shopping_cart'])) {
            $this->SYSTEM_ARR['shopping_cart'] = array();
        }
    }

    public function add($teacher_id, $lpackageId, $languageId, $startDateTime = '', $endDateTime = '', $grpcls_id = 0)
    {
        $this->SYSTEM_ARR['cart'] = array();
        $grpcls_id = FatUtility::int($grpcls_id);
        $teacher_id = FatUtility::int($teacher_id);
        $lpackageId = FatUtility::int($lpackageId);
        $languageId = FatUtility::int($languageId);
        if ($teacher_id < 1 || ($lpackageId < 1 && $grpcls_id<1)) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        /* validate teacher[ */
        $srch = new UserSearch();
        $srch->setTeacherDefinedCriteria();
        $srch->addFld('us_is_trial_lesson_enabled');
        $srch->addCondition('user_id', '=', $teacher_id);
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        $userRow = FatApp::getDb()->fetch($rs);
        if (!$userRow) {
            $this->error = Label::getLabel('LBL_Teacher_not_found');
            return false;
        }

        $freePackages = LessonPackage::getFreeTrialPackage();
        if ($freePackages and $freePackages['lpackage_id'] == $lpackageId) {
            if (1 != $userRow['us_is_trial_lesson_enabled']) {
                $this->error = Label::getLabel('MSG_Trial_Lessons_are_disabled_by_teacher');
                return false;
            }
        }
        /* ] */
        /* validate that free trial package cannot be added again per teacher[ */
        if($lpackageId>0){
            $lPackageRow = LessonPackage::getAttributesById($lpackageId, array('lpackage_id', 'lpackage_is_free_trial'));
            if ($lpackageId !== $lPackageRow['lpackage_id']) {
                $this->error = Label::getLabel('LBL_Invalid_Request');
                return false;
            }

            if (1 === $lPackageRow['lpackage_is_free_trial']) {
                /* validate if teacher has enabled free trial or not[ */
                /* ] */
                if (LessonPackage::isAlreadyPurchasedFreeTrial($this->cart_user_id, $teacher_id)) {
                    $this->error = Label::getLabel('LBL_You_already_purchased_free_trial_for_this_teacher');
                    return false;
                }
                if ($startDateTime == '' || $endDateTime == '') {
                    $this->error = Label::getLabel('LBL_Lesson_Schedule_time_is_required');
                    return false;
                }
            }
        }
        /* ] */

        /* validate group class id */
        if($grpcls_id>0){
			$classDetails = TeacherGroupClasses::getAttributesById($grpcls_id, array('grpcls_id', 'grpcls_teacher_id', 'grpcls_start_datetime', 'grpcls_end_datetime', 'grpcls_max_learner', 'grpcls_status'));
			if ($grpcls_id !== $classDetails['grpcls_id']) {
				$this->error = Label::getLabel('LBL_Invalid_Request');
				return false;
			}

            if ($classDetails['grpcls_status'] != TeacherGroupClasses::STATUS_ACTIVE) {
				$this->error = Label::getLabel('LBL_Class_Not_active');
				return false;
			}

            //60 mins booking gap
            $time_to_book = FatApp::getConfig('CONF_CLASS_BOOKING_GAP', FatUtility::VAR_INT, 60);
            $validDate = date('Y-m-d H:i:s', strtotime('+'.$time_to_book. ' minutes', strtotime(date('Y-m-d H:i:s'))));
            $difference = strtotime($classDetails['grpcls_start_datetime']) - strtotime($validDate); //== Difference should be always greaten then 0

            if ($difference < 1) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Booking_Close_For_This_Class'));
				return false;
            }

            if($this->cart_user_id==$classDetails['grpcls_teacher_id']){
                $this->error = Label::getLabel('LBL_Can_not_join_own_classes');
				return false;
            }

            $isBooked = TeacherGroupClassesSearch::isClassBookedByUser($grpcls_id, $this->cart_user_id);
            if($isBooked){
                $this->error = Label::getLabel('LBL_You_already_booked_this_class');
				return false;
            }

            $bookedSeatsCount = TeacherGroupClassesSearch::totalSeatsBooked($grpcls_id);
            if($classDetails['grpcls_max_learner']>0 && $bookedSeatsCount>=$classDetails['grpcls_max_learner']){
                $this->error = Label::getLabel('LBL_Class_Full');
				return false;
            }
		}
        $key = $teacher_id.'_'.$grpcls_id;
        $key = base64_encode(serialize($key));
        $this->SYSTEM_ARR['cart'][$key] = array(
            'teacher_id' => $teacher_id,
            'grpcls_id'  => $grpcls_id,
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
            'lpackageId' => $lpackageId,
            'languageId' => $languageId,
        );
        $this->updateUserCart();
        return true;
    }
    public function cartData($langId)
    {
        $key = key($this->SYSTEM_ARR['cart']);
        $cartData = $this->SYSTEM_ARR['cart'][$key];
        $languageId = $cartData['languageId'];
        $keyDecoded = unserialize(base64_decode($key));
        list($teacher_id,$grpcls_id) = explode('_', $keyDecoded);
        $teacherSrch = new UserSearch();
        $teacherSrch->setTeacherDefinedCriteria(false);
        $teacherSrch->joinUserCountry($langId);
        $teacherSrch->joinUserState($langId);
        $teacherSrch->addCondition('user_id', '=', $teacher_id);
        //$teacherSrch->addCondition( 'utl_slanguage_id', '=', 1 );
        $teacherSrch->setPageSize(1);
        $teacherSrch->joinTable("tbl_user_teach_languages", 'INNER JOIN', 'utl_us_user_id = '. $teacher_id .' AND utl_slanguage_id = '. $languageId, 'utl');
        /* find, if have added any offer price is locked with this teacher[ */
        $teacherSrch->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top_teacher_id = user_id AND top_learner_id = '.$this->cart_user_id, 'top');
        /* ] */
        $teacherSrch->addMultipleFields(array(
                'user_id',
                'user_first_name',
                'user_last_name',
                'user_country_id',
                'us_teach_slanguage_id',
                'top_teacher_id',
                'IFNULL(top_single_lesson_price,0) as topSingleLessonPrice',
                'IFNULL(top_bulk_lesson_price,0) as topBulkLessonPrice',
                'utl.*'

            ));
        if ($langId > 0) {
            $teacherSrch->addMultipleFields(array(
                    'IFNULL(country_name, country_code) as user_country_name',
                    'IFNULL(state_name, state_identifier) as user_state_name'
                ));
        }
        //echo $teacherSrch->getQuery(); die;
        $rs = $teacherSrch->getResultSet();
        $teacher = FatApp::getDb()->fetch($rs);
        // print_r($teacher); die;
        if (!$teacher) {
            $this->removeCartKey($key);
        }
        $lPackageId = $cartData['lpackageId'];
        $grpcls_id = $cartData['grpcls_id'];
        if($lPackageId > 0){
            $srch = LessonPackage::getSearchObject($langId);
            $srch->addCondition('lpackage_id', '=', $lPackageId);
            $srch->addMultipleFields(array('lpackage_id', 'lpackage_lessons', 'lpackage_is_free_trial', 'lpackage_identifier as lpackage_title'));
            if ($langId > 0) {
                $srch->addMultipleFields(array('IFNULL(lpackage_title, lpackage_identifier) as lpackage_title'));
            }
            $rs = $srch->getResultSet();
            $lessonPackageRow = FatApp::getDb()->fetch($rs);
            if ($lessonPackageRow['lpackage_is_free_trial'] == 1) {
                $itemPrice = 0;
            } else {

                if (!empty($teacher['top_teacher_id']) && isset($teacher['topSingleLessonPrice']) && isset($teacher['topBulkLessonPrice'])) {
                    $teacher['utl_bulk_lesson_amount'] = $teacher['topBulkLessonPrice'];
                    $teacher['utl_single_lesson_amount'] = $teacher['topSingleLessonPrice'];
                }
                $itemPrice = (($lessonPackageRow['lpackage_lessons'] > 1) ? $teacher['utl_bulk_lesson_amount'] : $teacher['utl_single_lesson_amount']);
            }
            $totalPrice = $itemPrice * $lessonPackageRow['lpackage_lessons'];
        }elseif($grpcls_id>0){
			$classDetails = TeacherGroupClasses::getAttributesById($grpcls_id, array('grpcls_id', 'grpcls_title', 'grpcls_entry_fee'));
			$itemPrice = $classDetails['grpcls_entry_fee'];
			$totalPrice = $itemPrice;
		}else{
            $this->error = Label::getLabel('LBL_Invalid_Request');
			return false;
        }
        $this->cartData = $teacher;
        $this->cartData['key'] = $key;
        $this->cartData['grpcls_id'] = $grpcls_id;
        $this->cartData['lpackage_id'] = $lPackageId;
        $this->cartData['languageId'] = $languageId;
        $this->cartData['lpackage_is_free_trial'] = $lPackageId>0 ? $lessonPackageRow['lpackage_is_free_trial'] : 0;
        $this->cartData['lpackage_lessons'] = $lPackageId>0 ? $lessonPackageRow['lpackage_lessons'] * 1 : 0;
        $this->cartData['startDateTime'] = $cartData['startDateTime'];
        $this->cartData['endDateTime'] = $cartData['endDateTime'];
        $this->cartData['itemName'] = $lPackageId>0 ? $lessonPackageRow['lpackage_title'] : $classDetails['grpcls_title'];
        $this->cartData['itemPrice'] = $itemPrice;
        $this->cartData['total'] = $totalPrice;
        //print_r($this->cartData); die;
        return $this->cartData;
    }
    public function getCart($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if (!$this->cartData) {
            /* cart Summary[ */
            $this->cartData = $this->cartData($langId);
            $userWalletBalance = User::getUserBalance($this->cart_user_id);
            $cartTotal = $this->cartData['total'];
            $cartTaxTotal = 0;
            $cartDiscounts = self::getCouponDiscounts($langId);
            //$cartDiscounts = 0;
            $totalSiteCommission = 0;
            $totalDiscountAmount = (isset($cartDiscounts['coupon_discount_total'])) ? $cartDiscounts['coupon_discount_total'] : 0;
            $orderNetAmount = ($cartTotal + $cartTaxTotal)  - $totalDiscountAmount;
            $walletAmountCharge = ($this->isCartUserWalletSelected()) ? min($orderNetAmount, $userWalletBalance) : 0;
            $orderPaymentGatewayCharges = $orderNetAmount - $walletAmountCharge;
            $summaryArr = array(
                'cartTotal' => $cartTotal,
                'cartTaxTotal' => $cartTaxTotal,
                'cartDiscounts' => $cartDiscounts,
                'cartWalletSelected' => $this->isCartUserWalletSelected(),
                'siteCommission' => $totalSiteCommission,
                'orderNetAmount' => $orderNetAmount,
                'walletAmountCharge' => $walletAmountCharge,
                'orderPaymentGatewayCharges' => $orderPaymentGatewayCharges,
            );
            $this->cartData = $this->cartData + $summaryArr;
            /* ] */
        }
        return $this->cartData;
    }

    public function updateCartWalletOption($val)
    {
        $this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet'] = $val;
        $this->updateUserCart();
        return true;
    }

    public function isCartUserWalletSelected()
    {
        return (isset($this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet']) && intval($this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet'])==1) ? 1 : 0;
    }

    public function removeCartKey($key)
    {
        unset($this->cartData[$key]);
        unset($this->SYSTEM_ARR['cart'][$key]);
        $this->updateUserCart();
        return true;
    }

    public function updateUserCart()
    {
        if (isset($this->cart_user_id)) {
            $record = new TableRecord('tbl_user_cart');
            $cart_arr = $this->SYSTEM_ARR['cart'];
            if (isset($this->SYSTEM_ARR['shopping_cart']) && is_array($this->SYSTEM_ARR['shopping_cart']) && (!empty($this->SYSTEM_ARR['shopping_cart']))) {
                $cart_arr["shopping_cart"] = $this->SYSTEM_ARR['shopping_cart'];
            }
            $cart_arr = serialize($cart_arr);
            $record->assignValues(
                array(
                    "usercart_user_id" => $this->cart_user_id,
                    "usercart_type" => CART::TYPE_TEACHER_BOOK,
                    "usercart_details" => $cart_arr,
                    "usercart_added_date" => date('Y-m-d H:i:s')
                )
            );
            if (!$record->addNew(array(), array('usercart_details' => $cart_arr, "usercart_added_date" => date('Y-m-d H:i:s')))) {
                Message::addErrorMessage($record->getError());
                throw new Exception('');
            }
        }
    }

    public function getCartUserId()
    {
        return $this->cart_user_id;
    }

    public function hasItems()
    {
        return count($this->SYSTEM_ARR['cart']);
    }

    public function clear()
    {
        $this->cartData = array();
        $this->SYSTEM_ARR['cart'] = array();
        $this->SYSTEM_ARR['shopping_cart'] = array();
        unset($_SESSION['shopping_cart']["order_id"]);
        //unset($_SESSION['wallet_recharge_cart']["order_id"]);
    }

    public function updateCartDiscountCoupon($val)
    {
        $this->SYSTEM_ARR['shopping_cart']['discount_coupon'] = $val;
        $this->updateUserCart();
        return true;
    }

    public function removeUsedRewardPoints()
    {
        if (isset($this->SYSTEM_ARR['shopping_cart']) && array_key_exists('reward_points', $this->SYSTEM_ARR['shopping_cart'])) {
            unset($this->SYSTEM_ARR['shopping_cart']['reward_points']);
            $this->updateUserCart();
        }
        return true;
    }

    public function getCouponDiscounts($langId = 0)
    {
        $couponObj = new DiscountCoupons();
        if (!self::getCartDiscountCoupon()) {
            return false;
        }
        $couponInfo = $couponObj->getValidCoupons($this->cart_user_id, $langId, self::getCartDiscountCoupon());
        $cartSubTotal = self::getSubTotal($langId);
        $couponData = array();
        if ($couponInfo) {
            $discountTotal = 0;
            if ($couponInfo['coupon_discount_in_percent'] == applicationConstants::FLAT) {
                $couponInfo['coupon_discount_value'] = min($couponInfo['coupon_discount_value'], $cartSubTotal);
            }
            if ($discountTotal > $couponInfo['coupon_max_discount_value'] && $couponInfo['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) {
                $discountTotal = $couponInfo['coupon_max_discount_value'];
            }
            /*]*/
            $labelArr = array(
                'coupon_label' => $couponInfo["coupon_title"],
                'coupon_id' => $couponInfo["coupon_id"],
                'coupon_discount_in_percent' => $couponInfo["coupon_discount_in_percent"],
                'max_discount_value' => $couponInfo["coupon_max_discount_value"]
            );
            if ($couponInfo['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) {
                /*if ( $cartSubTotal > $couponInfo['coupon_max_discount_value'] ) {
                    $cartSubTotal = $couponInfo['coupon_max_discount_value'];
                }*/
                $cartSubTotal = $cartSubTotal * $couponInfo['coupon_discount_value'] / 100 ;
            } elseif ($couponInfo['coupon_discount_in_percent'] == applicationConstants::FLAT) {
                if ($cartSubTotal > $couponInfo["coupon_discount_value"]) {
                    $cartSubTotal = $couponInfo["coupon_discount_value"];
                }
            }
            $couponData = array(
                'coupon_discount_type' => $couponInfo["coupon_type"],
                'coupon_code' => $couponInfo["coupon_code"],
                'coupon_discount_value' => $couponInfo["coupon_discount_value"],
                'coupon_discount_total' => $cartSubTotal,
                'coupon_info' => json_encode($labelArr),
            );
        }
        if (empty($couponData)) {
            return false;
        }
        return $couponData;
    }

    public function getSubTotal($langId)
    {
        if (!$this->cartData) {
            return 0;
        }
        $cartTotal = $this->cartData($langId);
        return $cartTotal['total'];
    }

    public function getCartDiscountCoupon()
    {
        return isset($this->SYSTEM_ARR['shopping_cart']['discount_coupon']) ? $this->SYSTEM_ARR['shopping_cart']['discount_coupon'] : '';
    }

    public function removeCartDiscountCoupon()
    {
        $couponCode = array_key_exists('discount_coupon', $this->SYSTEM_ARR['shopping_cart']) ? $this->SYSTEM_ARR['shopping_cart']['discount_coupon'] : '';
        unset($this->SYSTEM_ARR['shopping_cart']['discount_coupon']);
        /* Removing from temp hold[ */
        if ($couponCode != '') {
            $loggedUserId = $this->cart_user_id;
            $srch = DiscountCoupons::getSearchObject(0, false, false);
            $srch->addCondition('coupon_code', '=', $couponCode);
            $srch->setPageSize(1);
            $srch->addMultipleFields(array('coupon_id'));
            $rs = $srch->getResultSet();
            $couponRow = FatApp::getDb()->fetch($rs);
            if ($couponRow && $loggedUserId) {
                FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD, array('smt' => 'couponhold_coupon_id = ? AND couponhold_user_id = ?', 'vals'=> array($couponRow['coupon_id'], $loggedUserId)));
            }
        }
        $orderId = isset($_SESSION['order_id'])?$_SESSION['order_id']:'';
        if ($orderId != '') {
            FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD_PENDING_ORDER, array('smt' => 'ochold_order_id = ?', 'vals' => array($orderId)));
        }
        /* ] */
        $this->updateUserCart();
        return true;
    }
}
