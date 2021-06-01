<?php

class CartController extends LoggedUserController
{

    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function add()
    {
        $addToCartForm = $this->addToCartForm();
        $post = $addToCartForm->getFormDataFromArray(FatApp::getPostedData());

        if (false == $post) {
            FatUtility::dieJsonError(current($addToCartForm->getValidationErrors()));
        }
     
        /* [ */
        $grpclsId = $post['grpclsId'];
        $lessonQty = $post['lessonQty'];
        $teacherId = $post['teacherId'];
        $isFreeTrial = $post['isFreeTrial'];
        $languageId = $post['languageId'];
        $endDateTime = $post['endDateTime'];
        $startDateTime = $post['startDateTime'];
        $lessonDuration =  FatApp::getPostedData('lessonDuration', FatUtility::VAR_INT, 0);
        
        /* ] */
        $loggedUserId = UserAuthentication::getLoggedUserId();
        if ($teacherId == $loggedUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $freeTrialConfiguration = FatApp::getConfig('CONF_ENABLE_FREE_TRIAL', FatUtility::VAR_INT, 0);
        if($isFreeTrial == applicationConstants::YES && $freeTrialConfiguration == applicationConstants::NO){
            FatUtility::dieJsonError(Label::getLabel('LBL_FREE_TRIAL_NOT_ENABLE'));
        }
        $teacher = $this->checkTeacherIsValid($teacherId);
        /* [ */
        // prx($teacher);
        if (empty($teacher)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */

        if($isFreeTrial == applicationConstants::YES && $teacher['us_is_trial_lesson_enabled'] == applicationConstants::NO){
            FatUtility::dieJsonError(Label::getLabel('LBL_FREE_TRIAL_NOT_ENABLE_BY_TEACHER'));
        }
       
        if ($isFreeTrial == applicationConstants::YES && !empty($startDateTime) && !empty($endDateTime)) {
            $userTimezone = MyDate::getUserTimeZone();
            $systemTimeZone = MyDate::getTimeZone();

            $validDate = strtotime('+' . $teacher['us_booking_before'] . 'hours');

            $startDateTime = MyDate::changeDateTimezone($startDateTime, $userTimezone, $systemTimeZone);
            $endDateTime = MyDate::changeDateTimezone($endDateTime, $userTimezone, $systemTimeZone);

            if($validDate > strtotime($startDateTime)){
                FatUtility::dieJsonError(Label::getLabel('LBL_Booking_Close_For_This_Teacher'));
            }

            $teacherWeeklySchedule = new TeacherWeeklySchedule();
            if (!$teacherWeeklySchedule->checkCalendarTimeSlotAvailability($teacherId, $startDateTime, $endDateTime)) {
                FatUtility::dieJsonError($teacherWeeklySchedule->getError());
            }
        }

        /* add to cart[ */
        $cart = new Cart();
        if (!$cart->add($teacherId, $languageId, $lessonQty, $grpclsId, $lessonDuration, $isFreeTrial, $startDateTime, $endDateTime)) {
            FatUtility::dieJsonError($cart->getError());
        }
        /* ] */
        
        $cartData = $cart->getCart($this->siteLangId);
        if(empty($cartData)){
            FatUtility::dieJsonError($cart->getError());
        }

        $this->set('isFreeLesson', $isFreeTrial);
        $this->set('redirectUrl', CommonHelper::generateUrl('Checkout'));
        $msg = '';
        if (isset($post['checkoutPage'])) {
            $msg = Label::getLabel('LBL_ITEM_ADD_TO_CART.');
        }

        FatUtility::dieJsonSuccess(['isFreeLesson' => $isFreeTrial, 'redirectUrl' => CommonHelper::generateUrl('Checkout'), 'msg' => $msg]);
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

    private function checkTeacherIsValid(int $teacherId) : array
    {
        $teacherSearch = new TeacherSearch($this->siteLangId);
        $teacherSearch->applyPrimaryConditions();
        $teacherSearch->joinSettingTabel();
        $teacherSearch->addCondition('user_id', '=', $teacherId);
        $teacherSearch->setPageSize(1);
        $teacherSearch->addMultipleFields(['user_id','us_is_trial_lesson_enabled', 'us_booking_before']);
        $teacher = FatApp::getDb()->fetch($teacherSearch->getResultSet());
        if(!empty($teacher)){
            return $teacher;
        }
        return [];
    }
}
