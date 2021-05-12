<?php

class CartController extends MyAppController
{

    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function add()
    {
        if (!UserAuthentication::isUserLogged()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Please_login_to_book'));
        }
        $post = FatApp::getPostedData();
        if (false == $post) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* [ */
        $startDateTime = FatApp::getPostedData('startDateTime', FatUtility::VAR_STRING, '');
        $endDateTime = FatApp::getPostedData('endDateTime', FatUtility::VAR_STRING, '');
        $weekStart = FatApp::getPostedData('weekStart', FatUtility::VAR_STRING, '');
        $weekEnd = FatApp::getPostedData('weekEnd', FatUtility::VAR_STRING, '');
        $lessonDuration = FatApp::getPostedData('lessonDuration', FatUtility::VAR_INT, 0);
        /* ] */
        $grpclsId = FatApp::getPostedData('grpcls_id', FatUtility::VAR_INT, 0);
        $teacher_id = FatApp::getPostedData('teacher_id', FatUtility::VAR_INT, 0);
        $lpackageId = FatApp::getPostedData('lpackageId', FatUtility::VAR_INT, 0);
        $languageId = FatApp::getPostedData('languageId', FatUtility::VAR_INT, 1);
        if ($teacher_id == UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $db = FatApp::getDb();
        /* [ */
        $srch = new UserSearch();
        $srch->setTeacherDefinedCriteria();
        $srch->addCondition('user_id', '=', $teacher_id);
        $srch->setPageSize(1);
        $srch->addMultipleFields(['user_id']);
        $rs = $srch->getResultSet();
        $teacher = $db->fetch($rs);
        if (!$teacher) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        $teacher_id = $teacher['user_id'];
        if ($startDateTime != '' && $endDateTime != '') {
            $user_timezone = MyDate::getUserTimeZone();
            $systemTimeZone = MyDate::getTimeZone();
            $startDateTime = MyDate::changeDateTimezone($startDateTime, $user_timezone, $systemTimeZone);
            $endDateTime = MyDate::changeDateTimezone($endDateTime, $user_timezone, $systemTimeZone);
            $scheduledLessonSearchObj = new ScheduledLessonSearch(false);
            $userIds = [$teacher_id, UserAuthentication::getLoggedUserId()];
            $scheduledLessonSearchObj->checkUserLessonBooking($userIds, $startDateTime, $endDateTime);
            $getResultSet = $scheduledLessonSearchObj->getResultSet();
            $scheduledLessonData = $db->fetch($getResultSet);
            if (!empty($scheduledLessonData)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Requested_Slot_is_not_available'));
            }
            if (!TeacherWeeklySchedule::isSlotAvailable($teacher_id, $startDateTime, $endDateTime, $weekStart)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Requested_Slot_is_not_available'));
            }
        }
        $teacherBookingBefore = UserSetting::getUserSettings($teacher_id)['us_booking_before'];
        if ('' == $teacherBookingBefore) {
            $teacherBookingBefore = 0;
        }
        if ($startDateTime != '' && $endDateTime != '') {
            $validDate = date('Y-m-d H:i:s', strtotime('+' . $teacherBookingBefore . 'hours', strtotime(date('Y-m-d H:i:s'))));
            $selectedDate = $startDateTime;
            $validDateTimeStamp = strtotime($validDate);
            $SelectedDateTimeStamp = strtotime($selectedDate); //== always should be greater then current date
            $difference = $SelectedDateTimeStamp - $validDateTimeStamp; //== Difference should be always greaten then 0
            if ($difference < 1) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Booking_Close_For_This_Teacher'));
            }
        }
        /* add to cart[ */
        $cart = new Cart();
        if (!$cart->add($teacher_id, $lpackageId, $languageId, $startDateTime, $endDateTime, $grpclsId, $lessonDuration)) {
            FatUtility::dieJsonError($cart->getError());
        }
        /* ] */
        $cartData = $cart->getCart($this->siteLangId);
        $freePackage = LessonPackage::getFreeTrialPackage();
        if (!empty($freePackage) && ($freePackage['lpackage_id'] == $lpackageId)) {
            $this->set('isFreeLesson', ($cartData['orderNetAmount'] == 0));
        }
        $this->set('redirectUrl', CommonHelper::generateUrl('Checkout'));
        if (isset($post['checkoutPage'])) {
            $this->set('msg', Label::getLabel('LBL_Package_Selected_Successfully.'));
        }
        $this->_template->render(false, false, 'json-success.php');
    }

    public function applyPromoCode()
    {
        UserAuthentication::checkLogin();
        $post = FatApp::getPostedData();
        $loggedUserId = UserAuthentication::getLoggedUserId();
        if (false == $post) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        if (empty($post['coupon_code'])) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $couponCode = $post['coupon_code'];
        $couponInfo = DiscountCoupons::getValidCoupons($loggedUserId, $this->siteLangId, $couponCode);
        if ($couponInfo == false) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Coupon_Code', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $cartObj = new Cart();
        if (!$cartObj->updateCartDiscountCoupon($couponInfo['coupon_code'])) {
            Message::addErrorMessage(Label::getLabel('LBL_Action_Trying_Perform_Not_Valid', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $holdCouponData = [
            'couponhold_coupon_id' => $couponInfo['coupon_id'],
            'couponhold_user_id' => UserAuthentication::getLoggedUserId(),
            'couponhold_added_on' => date('Y-m-d H:i:s'),
        ];
        if (!FatApp::getDb()->insertFromArray(DiscountCoupons::DB_TBL_COUPON_HOLD, $holdCouponData, true, [], $holdCouponData)) {
            Message::addErrorMessage(Label::getLabel('LBL_Action_Trying_Perform_Not_Valid', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $cartObj->removeUsedRewardPoints();
        $this->set('msg', Label::getLabel("MSG_cart_discount_coupon_applied", $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function addToCartForm(): Form
    {
        $form = new Form('addToCart');
        $teacherIdField = $form->addIntegerField(Label::getLabel('LBL_Teacher_Id'), 'teacher_id');
        $teacherIdField->requirements()->setRequired(true);
        $teacherIdField->requirements()->setRange(1, 99999999999);
        
        $getMinAndMaxSlab = PriceSlab::getMinAndMaxSlab();
        $min = $max = 0;
        if(!empty($getMinAndMaxSlab)){
            $min = $getMinAndMaxSlab['minSlab'];
            $max = $getMinAndMaxSlab['maxSlab'];
        }

       
        $groupClassField = $form->addIntegerField(Label::getLabel('LBL_Group_Class'), 'grpcls_id');
        $groupClassField->requirements()->setRequired(false);
        $groupClassField->requirements()->setRange(1, 9999999);

        $slabIdField = $form->addIntegerField(Label::getLabel('LBL_lesson_qty'), 'lessonQty');
        $slabIdField->requirements()->setRequired(false);
        $slabIdField->requirements()->setRange($min, $max);

        $languageIdField = $form->addIntegerField(Label::getLabel('LBL_language_Id'), 'languageId');
        $languageIdField->requirements()->setRequired(false);
        $languageIdField->requirements()->setRange(1, 9999);
        
        $bookingSlot = applicationConstants::getBookingSlots();
        $lessonDurationField = $form->addRadioButtons(Label::getLabel('LBL_lesson_duration'),'lessonDuration', $bookingSlot[0]);
        $lessonDurationField->requirements()->setRequired(false);

        $groupClassField->requirements()->addOnChangerequirementUpdate(0, 'gt');
       
        
        $freeTrialField = $form->addRadioButtons(Label::getLabel('LBL_Free_trial'), 'is_free_trial', applicationConstants::getYesNoArr($this->siteLangId), applicationConstants::NO);
        $freeTrialField->requirements()->setRequired(true);

        $startDateTimeField = $form->addTextBox(Label::getLabel('LBL_Start_Date_Time'), 'startDateTime');
        $startDateTimeField->requirements()->setRequired(false);

        /* startDateTime requirements */
        $startDateTimeField->requirements()->setRequired(true);
        $freeTrialField->requirements()->addOnChangerequirementUpdate(applicationConstants::YES, 'eq', 'startDateTime',  $startDateTimeField->requirements());
        
        $startDateTimeField->requirements()->setRequired(false);
        $freeTrialField->requirements()->addOnChangerequirementUpdate(applicationConstants::YES, 'ne', 'startDateTime',  $startDateTimeField->requirements());
        /* ] */
        
        $endDateTimeField = $form->addTextBox(Label::getLabel('LBL_End_Date_Time'), 'endDateTime');
        $endDateTimeField->requirements()->setRequired(false);

        /* endDateTime requirements */
        $endDateTimeField->requirements()->setRequired(true);
        $freeTrialField->requirements()->addOnChangerequirementUpdate(applicationConstants::YES, 'eq', 'endDateTime',  $endDateTimeField->requirements());
        
        $endDateTimeField->requirements()->setRequired(false);
        $freeTrialField->requirements()->addOnChangerequirementUpdate(applicationConstants::YES, 'ne', 'endDateTime',  $endDateTimeField->requirements());
        /* ] */

        $weekStartField = $form->addTextBox(Label::getLabel('LBL_week_Start'), 'weekStart');
        $weekStartField->requirements()->setRequired(false);

         /* weekStart requirements */
         $weekStartField->requirements()->setRequired(true);
         $freeTrialField->requirements()->addOnChangerequirementUpdate(applicationConstants::YES, 'eq', 'weekStart',  $weekStartField->requirements());
         
         $weekStartField->requirements()->setRequired(false);
         $freeTrialField->requirements()->addOnChangerequirementUpdate(applicationConstants::YES, 'ne', 'weekStart',  $weekStartField->requirements());
         /* ] */

        return $form;
    }
    

    public function removePromoCode()
    {
        $cartObj = new Cart();
        if (!$cartObj->removeCartDiscountCoupon()) {
            Message::addErrorMessage(Label::getLabel('LBL_Action_Trying_Perform_Not_Valid', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $cartObj->removeUsedRewardPoints();
        $this->set('msg', Label::getLabel("MSG_cart_discount_coupon_removed", $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

}
