<?php

class Cart extends FatModel
{

    private $cartData = [];
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
            $this->SYSTEM_ARR['cart'] = [];
        }
        if (!isset($this->SYSTEM_ARR['shopping_cart']) || !is_array($this->SYSTEM_ARR['shopping_cart'])) {
            $this->SYSTEM_ARR['shopping_cart'] = [];
        }
    }

    
    public function add(int $teacherId, int $languageId, int $lessonQty, int $grpclsId = 0, int $lessonDuration = 0, int $isFreeTrial = 0, $startDateTime = '', $endDateTime = '')
    {
    
        $this->SYSTEM_ARR['cart'] = [];
        if ($teacherId < 1 || ( $isFreeTrial == applicationConstants::NO && $lessonQty < 1 && $grpclsId < 1)) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }

        $db = FatApp::getDb();
        /* validate teacher[ */
        $teacherSearch = new TeacherSearch(CommonHelper::getLangId());
        $teacherSearch->applyPrimaryConditions();
        $teacherSearch->joinSettingTabel();
        $teacherSearch->addCondition('user_id', '=', $teacherId);
        $teacherSearch->setPageSize(1);
        $teacherSearch->addMultipleFields(['user_id','us_is_trial_lesson_enabled']);
        $userRow = $db->fetch($teacherSearch->getResultSet());
    
        if (!$userRow) {
            $this->error = Label::getLabel('LBL_Teacher_not_found');
            return false;
        }

        if ($lessonQty > 0 || $isFreeTrial == applicationConstants::YES) {

            $freeTrialConfiguration = FatApp::getConfig('CONF_ENABLE_FREE_TRIAL', FatUtility::VAR_INT, 0);
            if($isFreeTrial == applicationConstants::YES){
                
                if($freeTrialConfiguration == applicationConstants::NO || $userRow['us_is_trial_lesson_enabled'] == applicationConstants::NO){
                    FatUtility::dieJsonError(Label::getLabel('LBL_FREE_TRIAL_NOT_ENABLE'));
                }
                if (OrderProduct::isAlreadyPurchasedFreeTrial($this->cart_user_id, $teacherId)) {
                    $this->error = Label::getLabel('LBL_You_already_purchased_free_trial_for_this_teacher');
                    return false;
                }
                if (empty($startDateTime) || empty($endDateTime)) {
                    $this->error = Label::getLabel('LBL_Lesson_Schedule_time_is_required');
                    return false;
                }

            } else {

                $userToLanguage = new UserToLanguage($teacherId);
                $userTeachLangs = $userToLanguage->getTeacherPricesForLearner(CommonHelper::getLangId(), $this->cart_user_id, $lessonDuration);
                
                if (empty($userTeachLangs)) {
                    $this->error = sprintf(Label::getLabel('LBL_ADMIN/TEACHER_DISABLED_THE_REQUESTED_TIME_DURATION'), $lessonDuration);
                    return false;
                }

                if (empty($lessonDuration)) {
                    $lessonDuration = $userTeachLangs[0]['utl_booking_slot'];
                }

                if (empty($languageId)) {
                    $languageId = $userTeachLangs[0]['tlanguage_id'];
                }

            }
        }
        /* ] */
        /* validate group class id */
        if ($grpclsId > 0) {
            $classDetails = TeacherGroupClasses::getAttributesById($grpclsId, ['grpcls_id', 'grpcls_teacherId', 'grpcls_start_datetime', 'grpcls_end_datetime', 'grpcls_max_learner', 'grpcls_status']);
            if ($grpclsId !== $classDetails['grpcls_id']) {
                $this->error = Label::getLabel('LBL_Invalid_Request');
                return false;
            }
            if ($classDetails['grpcls_status'] != TeacherGroupClasses::STATUS_ACTIVE) {
                $this->error = Label::getLabel('LBL_Class_Not_active');
                return false;
            }
            //60 mins booking gap
            $time_to_book = FatApp::getConfig('CONF_CLASS_BOOKING_GAP', FatUtility::VAR_INT, 60);
            $validDate = date('Y-m-d H:i:s', strtotime('+' . $time_to_book . ' minutes', strtotime(date('Y-m-d H:i:s'))));
            $difference = strtotime($classDetails['grpcls_start_datetime']) - strtotime($validDate); //== Difference should be always greaten then 0
            if ($difference < 1) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Booking_Close_For_This_Class'));
                return false;
            }
            if ($this->cart_user_id == $classDetails['grpcls_teacherId']) {
                $this->error = Label::getLabel('LBL_Can_not_join_own_classes');
                return false;
            }
            $isBooked = TeacherGroupClassesSearch::isClassBookedByUser($grpclsId, $this->cart_user_id);
            if ($isBooked) {
                $this->error = Label::getLabel('LBL_You_already_booked_this_class');
                return false;
            }
            $bookedSeatsCount = TeacherGroupClassesSearch::totalSeatsBooked($grpclsId);
            if ($classDetails['grpcls_max_learner'] > 0 && $bookedSeatsCount >= $classDetails['grpcls_max_learner']) {
                $this->error = Label::getLabel('LBL_Class_Full');
                return false;
            }
            $isSlotBooked = ScheduledLessonSearch::isSlotBooked($this->cart_user_id, $classDetails['grpcls_start_datetime'], $classDetails['grpcls_end_datetime']);
            if ($isSlotBooked) {
                $this->error = Label::getLabel('LBL_YOU_ALREADY_BOOKED_A_CLASS_BETWEEN_THIS_TIME_RANGE');
                return false;
            }
            $groupClassTiming = TeacherGroupClassesSearch::checkGroupClassTiming([$this->cart_user_id], $classDetails['grpcls_start_datetime'], $classDetails['grpcls_end_datetime']);
            $groupClassTiming->addCondition('grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
            $groupClassTiming->setPageSize(1);
            $groupClassTiming->getResultSet();
            if ($groupClassTiming->recordCount() > 0) {
                $this->error = Label::getLabel('LBL_YOU_ALREDY_HAVE_A_GROUP_CLASS_BETWEEN_THIS_TIME_RANGE');
                return false;
            }
        }
        $key = $teacherId . '_' . $grpclsId;
        $key = base64_encode(serialize($key));
        $this->SYSTEM_ARR['cart'][$key] = [
            'teacherId' => $teacherId,
            'grpclsId' => $grpclsId,
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
            'isFreeTrial' => $isFreeTrial,
            'lessonQty' => $lessonQty,
            'languageId' => $languageId,
            'lessonDuration' => $lessonDuration,
        ];
        $this->updateUserCart();
        return true;
    }

    public function cartData($langId)
    {
        $key = key($this->SYSTEM_ARR['cart']);
        if (empty($key)) {
            $this->error = Label::getLabel('LBL_SOMETHING_WENT_WORNG');
            return false;
        }
        $cartData = $this->SYSTEM_ARR['cart'][$key];
        $languageId = $cartData['languageId'];
        $lessonDuration = $cartData['lessonDuration'];
        $lessonQty = $cartData['lessonQty'];
        $grpclsId = $cartData['grpclsId'];
        $isFreeTrial = $cartData['isFreeTrial'];
        $keyDecoded = unserialize(base64_decode($key));
        list($teacherId, $grpclsId) = explode('_', $keyDecoded);
    
        $teacherSearch = new TeacherSearch($langId);
        $teacherSearch->applyPrimaryConditions();
        $teacherSearch->joinSettingTabel();
        $teacherSearch->addCondition('user_id', '=', $teacherId);
        $teacherSearch->setPageSize(1);
        $teacherSearch->addMultipleFields(['user_id','us_is_trial_lesson_enabled', 'us_booking_before']);
        if($grpclsId > 0){
            $teacherSearch->joinTable(TeacherGroupClasses::DB_TBL, 'INNER JOIN', 'grpcls.grpcls_teacher_id = teacher.user_id', 'grpcls');
            $teacherSearch->addCondition('grpcls.grpcls_status' , '=', TeacherGroupClasses::STATUS_ACTIVE);
        }elseif (!$isFreeTrial && $lessonQty > 0) {
            $teacherSearch->joinTable(UserTeachLanguage::DB_TBL, 'INNER JOIN', 'utl.utl_user_id = tlanguage_id = utl_tlanguage_id', 'utl');
            $teacherSearch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage_id = utl_tlanguage_id', 'tl');
            $teacherSearch->joinTable(TeachLangPrice::DB_TBL, 'INNER JOIN', 'prislab.prislab_id = ustelgpr.ustelgpr_prislab_id', 'prislab');
            $teacherSearch->joinTable(PriceSlab::DB_TBL, 'INNER JOIN', 'prislab.prislab_id = ustelgpr.ustelgpr_prislab_id', 'prislab');
            $teacherSearch->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top.top_teacher_id = utl.utl_user_id and top.top_learner_id = '.$this->cart_user_id.' and top.top_lesson_duration = ustelgpr.ustelgpr_slot', 'top');
        }

        // $teacherSrch = new UserSearch();
        // $teacherSrch->setTeacherDefinedCriteria(false);
        // $teacherSrch->joinUserCountry($langId);
        // $teacherSrch->joinUserState($langId);
        // $teacherSrch->addCondition('user_id', '=', $teacher_id);
        // $teacherSrch->setPageSize(1);
        $cnd = $cnd2 = '';
        $slotDurationActive = true;

        if ($grpclsId == 0 && ) {
            $cnd = ' AND utl_booking_slot=' . $lessonDuration . ' AND utl_price > 0 ';
            $cnd2 = ' AND top_lesson_duration=' . $lessonDuration;
            $slotDurationActive = (in_array($lessonDuration, CommonHelper::getPaidLessonDurations()));
        }

        $teacherSrch->joinTable("tbl_user_teach_languages", 'INNER JOIN', 'utl_user_id = ' . $teacher_id . ' AND utl_tlanguage_id = ' . $languageId . $cnd, 'utl');
        /* find, if have added any offer price is locked with this teacher[ */
        $teacherSrch->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top_teacher_id = user_id AND top_learner_id = ' . $this->cart_user_id . $cnd2, 'top');
        /* ] */
        $teacherSrch->addMultipleFields([
            'user_id',
            'user_first_name',
            'user_last_name',
            'user_country_id',
            'us_teach_slanguage_id',
            'top_teacher_id',
            'IFNULL(top_single_lesson_price,0) as topSingleLessonPrice',
            'IFNULL(top_bulk_lesson_price,0) as topBulkLessonPrice',
            'utl.*'
        ]);
        if ($langId > 0) {
            $teacherSrch->addMultipleFields([
                'IFNULL(country_name, country_code) as user_country_name',
                'IFNULL(state_name, state_identifier) as user_state_name'
            ]);
        }
        $rs = $teacherSrch->getResultSet();
        $teacher = FatApp::getDb()->fetch($rs);
        if (empty($teacher) || !$slotDurationActive) {
            $this->removeCartKey($key);
            return false;
        }
        if ($lPackageId > 0) {
            $srch = LessonPackage::getSearchObject($langId);
            $srch->addCondition('lpackage_id', '=', $lPackageId);
            $srch->addMultipleFields(['lpackage_id', 'lpackage_lessons', 'lpackage_is_free_trial', 'lpackage_identifier as lpackage_title']);
            if ($langId > 0) {
                $srch->addMultipleFields(['IFNULL(lpackage_title, lpackage_identifier) as lpackage_title']);
            }
            $srch->doNotCalculateRecords();
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
        } elseif ($grpcls_id > 0) {
            $classDetails = TeacherGroupClasses::getAttributesById($grpcls_id, ['grpcls_id', 'grpcls_title', 'grpcls_entry_fee']);
            $itemPrice = $classDetails['grpcls_entry_fee'];
            $totalPrice = $itemPrice;
        } else {
            $this->removeCartKey($key);
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        $this->cartData = $teacher;
        $this->cartData['key'] = $key;
        $this->cartData['grpcls_id'] = $grpcls_id;
        $this->cartData['lpackage_id'] = $lPackageId;
        $this->cartData['languageId'] = $languageId;
        $this->cartData['lessonDuration'] = $lessonDuration;
        $this->cartData['lpackage_is_free_trial'] = $lPackageId > 0 ? $lessonPackageRow['lpackage_is_free_trial'] : 0;
        $this->cartData['lpackage_lessons'] = $lPackageId > 0 ? $lessonPackageRow['lpackage_lessons'] * 1 : 0;
        $this->cartData['startDateTime'] = $cartData['startDateTime'];
        $this->cartData['endDateTime'] = $cartData['endDateTime'];
        $this->cartData['itemName'] = $lPackageId > 0 ? $lessonPackageRow['lpackage_title'] : $classDetails['grpcls_title'];
        $this->cartData['itemPrice'] = $itemPrice;
        $this->cartData['total'] = $totalPrice;
        return $this->cartData;
    }

    public function getCart($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if (!$this->cartData) {
            /* cart Summary[ */
            $this->cartData = $this->cartData($langId);
            if (empty($this->cartData)) {
                return [];
            }
            $userWalletBalance = User::getUserBalance($this->cart_user_id);
            $cartTotal = $this->cartData['total'];
            $cartTaxTotal = 0;
            $cartDiscounts = $this->getCouponDiscounts($langId);
            $totalSiteCommission = 0;
            $totalDiscountAmount = (isset($cartDiscounts['coupon_discount_total'])) ? $cartDiscounts['coupon_discount_total'] : 0;
            $orderNetAmount = ($cartTotal + $cartTaxTotal) - $totalDiscountAmount;
            $walletAmountCharge = ($this->isCartUserWalletSelected()) ? min($orderNetAmount, $userWalletBalance) : 0;
            $orderPaymentGatewayCharges = $orderNetAmount - $walletAmountCharge;
            $summaryArr = [
                'cartTotal' => $cartTotal,
                'cartTaxTotal' => $cartTaxTotal,
                'cartDiscounts' => $cartDiscounts,
                'cartWalletSelected' => $this->isCartUserWalletSelected(),
                'siteCommission' => $totalSiteCommission,
                'orderNetAmount' => $orderNetAmount,
                'walletAmountCharge' => $walletAmountCharge,
                'orderPaymentGatewayCharges' => $orderPaymentGatewayCharges,
            ];
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

    public function updateLessonPackageId(int $lessonPackageId)
    {
        $this->SYSTEM_ARR['shopping_cart']['lpackage_id'] = $lessonPackageId;
        $this->updateUserCart();
        return true;
    }

    public function isCartUserWalletSelected()
    {
        return (isset($this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet']) && intval($this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet']) == 1) ? 1 : 0;
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
            $record->assignValues([
                "usercart_user_id" => $this->cart_user_id,
                "usercart_type" => CART::TYPE_TEACHER_BOOK,
                "usercart_details" => $cart_arr,
                "usercart_added_date" => date('Y-m-d H:i:s')
            ]);
            if (!$record->addNew([], ['usercart_details' => $cart_arr, "usercart_added_date" => date('Y-m-d H:i:s')])) {
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
        $this->cartData = [];
        $this->SYSTEM_ARR['cart'] = [];
        $this->SYSTEM_ARR['shopping_cart'] = [];
        unset($_SESSION['shopping_cart']["order_id"]);
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
        if (!$this->getCartDiscountCoupon()) {
            return false;
        }
        $couponInfo = $couponObj->getValidCoupons($this->cart_user_id, $langId, $this->getCartDiscountCoupon());
        $cartSubTotal = $this->getSubTotal($langId);
        $couponData = [];
        if ($couponInfo) {
            $discountTotal = 0;
            if ($couponInfo['coupon_discount_in_percent'] == applicationConstants::FLAT) {
                $couponInfo['coupon_discount_value'] = min($couponInfo['coupon_discount_value'], $cartSubTotal);
            }
            if ($discountTotal > $couponInfo['coupon_max_discount_value'] && $couponInfo['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) {
                $discountTotal = $couponInfo['coupon_max_discount_value'];
            }
            /* ] */
            $labelArr = [
                'coupon_label' => $couponInfo["coupon_title"],
                'coupon_id' => $couponInfo["coupon_id"],
                'coupon_discount_in_percent' => $couponInfo["coupon_discount_in_percent"],
                'max_discount_value' => $couponInfo["coupon_max_discount_value"]
            ];
            if ($couponInfo['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) {
                $cartSubTotal = $cartSubTotal * $couponInfo['coupon_discount_value'] / 100;
            } elseif ($couponInfo['coupon_discount_in_percent'] == applicationConstants::FLAT) {
                if ($cartSubTotal > $couponInfo["coupon_discount_value"]) {
                    $cartSubTotal = $couponInfo["coupon_discount_value"];
                }
            }
            $couponData = [
                'coupon_discount_type' => $couponInfo["coupon_type"],
                'coupon_code' => $couponInfo["coupon_code"],
                'coupon_discount_value' => $couponInfo["coupon_discount_value"],
                'coupon_discount_total' => $cartSubTotal,
                'coupon_info' => json_encode($labelArr),
            ];
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
            $srch->addMultipleFields(['coupon_id']);
            $rs = $srch->getResultSet();
            $couponRow = FatApp::getDb()->fetch($rs);
            if ($couponRow && $loggedUserId) {
                FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD, ['smt' => 'couponhold_coupon_id = ? AND couponhold_user_id = ?', 'vals' => [$couponRow['coupon_id'], $loggedUserId]]);
            }
        }
        $orderId = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : '';
        if ($orderId != '') {
            FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD_PENDING_ORDER, ['smt' => 'ochold_order_id = ?', 'vals' => [$orderId]]);
        }
        /* ] */
        $this->updateUserCart();
        return true;
    }

    private function getUserTeachLangData($teacherId, $languageId, $lessonQty, $slot)
    {
       $userTeachLanguage = new UserTeachLanguage($teacherId);
       $getUserTeachLanguage = $userTeachLanguage->getUserTeachlanguages(0, true);
       $getUserTeachLanguage->joinTable(PriceSlab::DB_TBL, 'INNER JOIN', 'prislab.prislab_id = ustelgpr.ustelgpr_prislab_id', 'prislab');
       $getUserTeachLanguage->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top.top_teacher_id = utl.utl_user_id and top.top_learner_id = '.$this->cart_user_id.' and top.top_lesson_duration = ustelgpr.ustelgpr_slot', 'top');
       $getUserTeachLanguage->addCondition('utl.utl_tlanguage_id', '=', $languageId);
       $getUserTeachLanguage->addCondition('prislab.prislab_max', '>=', $lessonQty);
       $getUserTeachLanguage->addCondition('prislab.prislab_min', '<=', $lessonQty);
       $getUserTeachLanguage->addCondition('ustelgpr.ustelgpr_slot', '=', $slot);
       $getUserTeachLanguage->setPageSize(1);
       $getUserTeachLanguage->doNotCalculateRecords();
       $userTeachlanguages = FatApp::getDb()->fetch($getUserTeachLanguage->getResultSet());
       if(empty($userTeachlanguages)){
           return [];
       }
       return $userTeachlanguages;
    }

}
