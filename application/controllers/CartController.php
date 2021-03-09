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
        /* ] */

        $grpclsId = FatApp::getPostedData('grpcls_id', FatUtility::VAR_INT, 0);
        $teacher_id = FatApp::getPostedData('teacher_id', FatUtility::VAR_INT, 0);
        $lpackageId = FatApp::getPostedData('lpackageId', FatUtility::VAR_INT, 0);
        $languageId = FatApp::getPostedData('languageId', FatUtility::VAR_INT, 1);

        if ($teacher_id == UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $db =  FatApp::getDb();
        /* [ */
        $srch = new UserSearch();
        $srch->setTeacherDefinedCriteria();
        $srch->addCondition('user_id', '=', $teacher_id);
        $srch->setPageSize(1);
        $srch->addMultipleFields(array('user_id'));
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
            $userIds  = array( $teacher_id, UserAuthentication::getLoggedUserId() );
            $scheduledLessonSearchObj->checkUserLessonBooking($userIds, $startDateTime, $endDateTime);
            $getResultSet = $scheduledLessonSearchObj->getResultSet();
            $scheduledLessonData =$db->fetch($getResultSet);

            if(!empty($scheduledLessonData)){
                FatUtility::dieJsonError(Label::getLabel('LBL_Requested_Slot_is_not_available'));
            }

            if (!TeacherWeeklySchedule::isSlotAvailable($teacher_id, $startDateTime, $endDateTime, $weekStart)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Requested_Slot_is_not_available'));
            }
        }

        $teacherBookingBefore = current(UserSetting::getUserSettings($teacher_id))['us_booking_before'];
        if ('' ==  $teacherBookingBefore) {
            $teacherBookingBefore = 0;
        }

        if ($startDateTime != '' && $endDateTime != '') {
            $validDate = date('Y-m-d H:i:s', strtotime('+'.$teacherBookingBefore. 'hours', strtotime(date('Y-m-d H:i:s'))));
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
    
        if (!$cart->add($teacher_id, $lpackageId, $languageId, $startDateTime, $endDateTime, $grpclsId)) {
            
            FatUtility::dieJsonError($cart->getError());
        }
        /* ] */

        $cartData = $cart->getCart( $this->siteLangId );
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
        /* $couponObj = new DiscountCoupons();
        $couponInfo = $couponObj->getCoupon($couponCode,$this->siteLangId);
        */
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
        $holdCouponData = array(
            'couponhold_coupon_id'=>$couponInfo['coupon_id'],
            'couponhold_user_id'=>UserAuthentication::getLoggedUserId(),
            /* 'couponhold_usercart_id'=>$cartObj->cart_id, */
            'couponhold_added_on'=>date('Y-m-d H:i:s'),
        );

        if (!FatApp::getDb()->insertFromArray(DiscountCoupons::DB_TBL_COUPON_HOLD, $holdCouponData, true, array(), $holdCouponData)) {
            Message::addErrorMessage(Label::getLabel('LBL_Action_Trying_Perform_Not_Valid', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $cartObj->removeUsedRewardPoints();
        $this->set('msg', Label::getLabel("MSG_cart_discount_coupon_applied", $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
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
