<?php

class CheckoutController extends LoggedUserController
{

    private $cartObj;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->cartObj = new Cart(UserAuthentication::getLoggedUserId());
    }

    public function index()
    {
        $cartData = $this->cartObj->getCart($this->siteLangId);
        $criteria = ['isUserLogged' => true, 'hasItems' => true];
        if (!$this->isEligibleForNextStep($criteria)) {
            if (!Message::getErrorCount()) {
                Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
            }
            FatApp::redirectUser(CommonHelper::generateUrl());
        }
        if (0 >= $cartData['grpcls_id']) {
            $lessonPackages = LessonPackage::getPackagesWithoutTrial($this->siteLangId);
            if (empty($lessonPackages)) {
                Message::addErrorMessage(Label::getLabel('MSG_PLEASE_CONCAT_WITH_ADMIN_NO_LESSON_PACKAGE_ACTIVE'));
                FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
            }
            $this->set('lessonPackages', $lessonPackages);
            $lessonPackageIds = array_column($lessonPackages, 'lpackage_id', 'lpackage_id');
            if (!array_key_exists($cartData['lpackage_id'], $lessonPackageIds)) {
                $cartData['lpackage_id'] = $lessonPackages[0]['lpackage_id'];
                $cart = new Cart();
                $cart->updateLessonPackageId($cartData['lpackage_id']);
            }
        }
        $userToLanguage = new UserToLanguage($cartData['user_id']);
        $tLangSettings = $userToLanguage->getTeachingSettings($this->siteLangId);
        $tlangArr = [];
        foreach ($tLangSettings as $tLangSetting) {
            $tlangArr[$tLangSetting['tlanguage_id']] = $tLangSetting['tlanguage_name'];
        }
        $this->set('teachLanguages', $tlangArr);
        $confirmForm = $this->getConfirmFormWithNoAmount($this->siteLangId);
        if ($cartData['orderNetAmount'] <= 0) {
            $confirmForm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Confirm_Order'));
        }
        $this->set('confirmForm', $confirmForm);
        $bookingDurations = array_unique(array_column($tLangSettings, 'utl_booking_slot'));
        sort($bookingDurations);
        $this->set('bookingDurations', $bookingDurations);
        $this->set('cartData', $cartData);
        $this->_template->render();
    }

    public function listFinancialSummary()
    {
        $json = [];
        $cart = $this->cartObj->getCart($this->siteLangId);
        $userWalletBalance = User::getUserBalance(UserAuthentication::getLoggedUserId());
        $this->set('userWalletBalance', $userWalletBalance);
        $this->set('cart', $cart);
        $json['html'] = $this->_template->render(false, false, 'checkout/list-financial-summary.php', true, false);
        $json['couponApplied'] = isset($cart['cartDiscounts']['coupon_discount_total']) ? $cart['cartDiscounts']['coupon_discount_total'] : 0;
        FatUtility::dieJsonSuccess($json);
    }

    public function getCouponForm()
    {
        $loggedUserId = UserAuthentication::getLoggedUserId();
        $orderId = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : '';
        $cartSummary = $this->cartObj->getCart($this->siteLangId);
        $couponsList = DiscountCoupons::getValidCoupons($loggedUserId, $this->siteLangId, '', $orderId);
        $this->set('couponsList', $couponsList);
        $this->set('cartSummary', $cartSummary);
        $PromoCouponsFrm = $this->getPromoCouponsForm($this->siteLangId);
        $this->set('PromoCouponsFrm', $PromoCouponsFrm);
        $this->_template->render(false, false);
    }

    private function getPromoCouponsForm($langId)
    {
        $langId = FatUtility::int($langId);
        $frm = new Form('frmPromoCoupons');
        $fld = $frm->addTextBox(Label::getLabel('LBL_Coupon_code', $langId), 'coupon_code', '', ['placeholder' => Label::getLabel('LBL_Enter_Your_code', $langId)]);
        $fld->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Apply', $langId));
        return $frm;
    }

    public function paymentSummary()
    {
        $criteria = ['isUserLogged' => true, 'hasItems' => true];
        if (!$this->isEligibleForNextStep($criteria)) {
            if (Message::getErrorCount()) {
                $errMsg = Message::getHtml();
            } else {
                Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
                $errMsg = Message::getHtml();
            }
            if (FatUtility::isAjaxCall() == true) {
                $json['errorMsg'] = $errMsg;
                $json['redirectUrl'] = CommonHelper::generateUrl('Checkout');
                FatUtility::dieJsonError($json);
            }
            FatUtility::dieWithError($errMsg);
        }
        $userId = UserAuthentication::getLoggedUserId();
        $userWalletBalance = User::getUserBalance($userId);
        $cartData = $this->cartObj->getCart($this->siteLangId);
        $WalletPaymentForm = $this->getWalletPaymentForm();
        if ((FatUtility::convertToType($userWalletBalance, FatUtility::VAR_FLOAT) >= FatUtility::convertToType($cartData['orderNetAmount'], FatUtility::VAR_FLOAT)) && $cartData['cartWalletSelected']) {
            $WalletPaymentForm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');
            $WalletPaymentForm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Pay_Now'));
        }
        /* Payment Methods[ */
        $pmSrch = PaymentMethods::getSearchObject($this->siteLangId);
        $pmSrch->doNotCalculateRecords();
        $pmSrch->doNotLimitRecords();
        $pmSrch->addMultipleFields([
            'pmethod_id',
            'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name',
            'pmethod_code',
            'pmethod_description'
        ]);
        $pmSrch->addCondition('pmethod_type', '=', PaymentMethods::TYPE_PAYMENT_METHOD);
        $pmRs = $pmSrch->getResultSet();
        $paymentMethods = FatApp::getDb()->fetchAll($pmRs);
        /* ] */
        $confirmForm = $this->getConfirmFormWithNoAmount($this->siteLangId);
        if ($cartData['orderPaymentGatewayCharges'] <= 0) {
            $confirmForm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Confirm_Order'));
        }
        $this->set('confirmForm', $confirmForm);
        $this->set('paymentMethods', $paymentMethods);
        $this->set('userWalletBalance', $userWalletBalance);
        $this->set('cartData', $cartData);
        $this->set('WalletPaymentForm', $WalletPaymentForm);
        $this->_template->render(false, false);
    }

    public function walletSelection()
    {
        $payFromWallet = FatApp::getPostedData('payFromWallet', FatUtility::VAR_INT, 0);
        $this->cartObj->updateCartWalletOption($payFromWallet);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function paymentTab($pmethod_id, $order_id = '')
    {
        $pmethodId = FatUtility::int($pmethod_id);
        if (!$pmethodId) {
            FatUtility::dieWithError(Label::getLabel("MSG_Invalid_Request!", $this->siteLangId));
        }
        if (!UserAuthentication::isUserLogged()) {
            FatUtility::dieWithError(Label::getLabel('MSG_Your_Session_seems_to_be_expired.', $this->siteLangId));
        }
        $orderInfo = [];
        $netAmmount = 0;
        if (!empty($order_id)) {
            $srch = Order::getSearchObject();
            $srch->doNotCalculateRecords();
            $srch->doNotLimitRecords();
            $srch->addCondition('order_id', '=', $order_id);
            $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PENDING);
            $rs = $srch->getResultSet();
            $orderInfo = FatApp::getDb()->fetch($rs);
            if (!$orderInfo) {
                FatUtility::dieWithError(Label::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId));
            }
            $netAmmount = $orderInfo['order_net_amount'];
        }
        /* [ */
        $pmSrch = PaymentMethods::getSearchObject($this->siteLangId);
        $pmSrch->doNotCalculateRecords();
        $pmSrch->doNotLimitRecords();
        $pmSrch->addMultipleFields(['pmethod_id', 'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name', 'pmethod_code', 'pmethod_description']);
        $pmSrch->addCondition('pmethod_id', '=', $pmethodId);
        $pmSrch->addCondition('pmethod_type', '=', PaymentMethods::TYPE_PAYMENT_METHOD);
        $pmRs = $pmSrch->getResultSet();
        $paymentMethod = FatApp::getDb()->fetch($pmRs);
        if (!$paymentMethod) {
            FatUtility::dieWithError(Label::getLabel("MSG_Selected_Payment_method_not_found!", $this->siteLangId));
        }
        $this->set('paymentMethod', $paymentMethod);
        /* ] */
        /* [ */
        $frm = $this->getPaymentTabForm($this->siteLangId);
        if (!empty($order_id)) {
            $frm->fill(['order_id' => $order_id, 'order_type' => $orderInfo['order_type']]);
        }
        $frm->fill(['pmethod_id' => $pmethodId]);
        $this->set('frm', $frm);
        /* ] */
        $cartData = [];
        if (empty($order_id)) {
            $cartData = $this->cartObj->getCart($this->siteLangId);
            $netAmmount = $cartData['orderPaymentGatewayCharges'];
            $this->set('cartData', $cartData);
        }
        $this->set('netAmmount', $netAmmount);
        $this->_template->render(false, false, '', false, false);
    }

    public function confirmOrder()
    {
        $order_type = FatApp::getPostedData('order_type', FatUtility::VAR_INT, 0);
        $pmethodId = FatApp::getPostedData('pmethod_id', FatUtility::VAR_INT, 0);
        $order_id = FatApp::getPostedData("order_id", FatUtility::VAR_STRING, "");
        /* [ */
        if ($pmethodId > 0) {
            $pmSrch = PaymentMethods::getSearchObject($this->siteLangId);
            $pmSrch->doNotCalculateRecords();
            $pmSrch->doNotLimitRecords();
            $pmSrch->addMultipleFields(['pmethod_id', 'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name', 'pmethod_code', 'pmethod_description']);
            $pmSrch->addCondition('pmethod_id', '=', $pmethodId);
            $pmSrch->addCondition('pmethod_type', '=', PaymentMethods::TYPE_PAYMENT_METHOD);
            $pmRs = $pmSrch->getResultSet();
            $paymentMethod = FatApp::getDb()->fetch($pmRs);
            if (!$paymentMethod) {
                Message::addErrorMessage(Label::getLabel("MSG_Selected_Payment_method_not_found!"));
                FatUtility::dieWithError(Message::getHtml());
            }
        }
        /* ] */
        /* Loading Money to wallet[ */
        if ($order_type == Order::TYPE_WALLET_RECHARGE || $order_type == Order::TYPE_GIFTCARD) {
            $criteria = ['isUserLogged' => true];
            if (!$this->isEligibleForNextStep($criteria)) {
                if (Message::getErrorCount()) {
                    $errMsg = Message::getHtml();
                } else {
                    Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
                    $errMsg = Message::getHtml();
                }
                FatUtility::dieWithError($errMsg);
            }
            $user_id = UserAuthentication::getLoggedUserId();
            if ($order_id == '') {
                Message::addErrorMessage(Label::getLabel("MSG_INVALID_Request"));
                FatUtility::dieWithError(Message::getHtml());
            }
            $orderObj = new Order();
            $srch = Order::getSearchObject();
            $srch->doNotCalculateRecords();
            $srch->doNotLimitRecords();
            $srch->addCondition('order_id', '=', $order_id);
            $srch->addCondition('order_user_id', '=', $user_id);
            $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PENDING);
            $srch->addCondition('order_type', '=', $order_type);
            $rs = $srch->getResultSet();
            $orderInfo = FatApp::getDb()->fetch($rs);
            if (!$orderInfo) {
                Message::addErrorMessage(Label::getLabel("MSG_INVALID_ORDER_PAID_CANCELLED"));
                FatUtility::dieWithError(Message::getHtml());
            }
            $orderObj->updateOrderInfo($order_id, ['order_pmethod_id' => $pmethodId]);
            $controller = $paymentMethod['pmethod_code'] . 'Pay';
            $redirectUrl = CommonHelper::generateUrl($controller, 'charge', [$order_id]);
            $this->set('msg', Label::getLabel('LBL_Processing...'));
            $this->set('redirectUrl', $redirectUrl);
            $this->_template->render(false, false, 'json-success.php');
        }
        $cartData = $this->cartObj->getCart($this->siteLangId);
        $criteria = ['isUserLogged' => true, 'hasItems' => true,];
        if (!$this->isEligibleForNextStep($criteria)) {
            if (Message::getErrorCount()) {
                $errMsg = Message::getHtml();
            } else {
                Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
                $errMsg = Message::getHtml();
            }
            if (FatUtility::isAjaxCall() == true) {
                $json['msg'] = $errMsg;
                $json['redirectUrl'] = CommonHelper::generateUrl('Checkout');
                FatUtility::dieJsonError($json);
            }
            FatUtility::dieWithError($errMsg);
        }
        if ($cartData['orderPaymentGatewayCharges'] == 0 && $pmethodId) {
            Message::addErrorMessage(Label::getLabel('MSG_Amount_for_payment_gateway_must_be_greater_than_zero.'));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* addOrder[ */
        $order_id = isset($_SESSION['shopping_cart']["order_id"]) ? $_SESSION['shopping_cart']["order_id"] : false;
        $orderNetAmount = $cartData["orderNetAmount"];
        $walletAmountCharge = $cartData["walletAmountCharge"];
        $coupon_discount_total = $cartData['cartDiscounts']['coupon_discount_total'];
        $orderData = [
            'order_id' => $order_id,
            'order_type' => Order::TYPE_LESSON_BOOKING,
            'order_user_id' => $this->cartObj->getCartUserId(),
            'order_is_paid' => Order::ORDER_IS_PENDING,
            'order_net_amount' => $orderNetAmount,
            'order_is_wallet_selected' => $cartData['cartWalletSelected'],
            'order_wallet_amount_charge' => $walletAmountCharge,
            'order_currency_id' => CommonHelper::getCurrencyId(),
            'order_currency_code' => CommonHelper::getCurrencyCode(),
            'order_currency_value' => CommonHelper::getCurrencyValue(),
            'order_pmethod_id' => $pmethodId,
            'order_discount_coupon_code' => $cartData['cartDiscounts']['coupon_code'],
            'order_discount_total' => $coupon_discount_total,
            'order_discount_info' => $cartData['cartDiscounts']['coupon_info'],
        ];
        $languageRow = Language::getAttributesById($this->siteLangId);
        $orderData['order_language_id'] = $languageRow['language_id'];
        $orderData['order_language_code'] = $languageRow['language_code'];
        /* [ */
        $op_lesson_duration = $cartData['lessonDuration']; //FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_INT, 60);
        $cartData['op_commission_charged'] = 0;
        $cartData['op_commission_percentage'] = 0;
        if ($cartData['lpackage_is_free_trial']) {
            $op_lesson_duration = FatApp::getConfig('conf_trial_lesson_duration', FatUtility::VAR_INT, 30);
        } else {
            $commissionDetails = Commission::getTeacherCommission($cartData['user_id'], $cartData['grpcls_id']);
            if ($commissionDetails) {
                $cartData['op_commission_percentage'] = $commissionDetails['commsetting_fees'];
                $teacherCommission = ((100 - $commissionDetails['commsetting_fees']) * $cartData['itemPrice']) / 100;
            } else {
                $teacherCommission = $cartData['itemPrice'];
            }
            $teacherCommission = $teacherCommission;
            $cartData['op_commission_charged'] = $teacherCommission;
        }
        $products[$cartData['lpackage_id']] = [
            'op_grpcls_id' => $cartData['grpcls_id'],
            'op_lpackage_id' => $cartData['lpackage_id'],
            'op_lpackage_lessons' => $cartData['lpackage_lessons'],
            'op_lpackage_is_free_trial' => $cartData['lpackage_is_free_trial'],
            'op_lesson_duration' => $op_lesson_duration,
            'op_teacher_id' => $cartData['user_id'],
            'op_qty' => $cartData['grpcls_id'] == 0 ? $cartData['lpackage_lessons'] : 1,
            'op_commission_charged' => $cartData['op_commission_charged'],
            'op_commission_percentage' => $cartData['op_commission_percentage'],
            'op_unit_price' => $cartData['itemPrice'],
            'op_orderstatus_id' => FatApp::getConfig("CONF_DEFAULT_ORDER_STATUS"),
            'op_slanguage_id' => $cartData['languageId'],
        ];
        $productsLangData = [];
        $allLanguages = Language::getAllNames();
        foreach ($allLanguages as $lang_id => $language_name) {
            $langSpecificLPackageRow = LessonPackage::getAttributesByLangId($lang_id, $cartData['lpackage_id']);
            if (!$langSpecificLPackageRow) {
                continue;
            }
            $productsLangData[$lang_id] = ['oplang_lang_id' => $lang_id, 'op_lpackage_title' => $langSpecificLPackageRow['lpackage_title']];
        }
        $products[$cartData['lpackage_id']]['productsLangData'] = $productsLangData;
        $orderData['products'] = $products;
        /* ] */
        $order = new Order();
        if (!$order->addUpdate($orderData)) {
            Message::addErrorMessage($order->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */
        $redirectUrl = '';
        if (0 >= $orderNetAmount) {
            $redirectUrl = CommonHelper::generateUrl('FreePay', 'Charge', [$order->getOrderId()], CONF_WEBROOT_FRONT_URL);
            $this->set('msg', Label::getLabel('LBL_Processing...', $this->siteLangId));
            $this->set('redirectUrl', $redirectUrl);
            $this->_template->render(false, false, 'json-success.php');
        }
        $userId = UserAuthentication::getLoggedUserId();
        $userWalletBalance = User::getUserBalance($userId);
        if ($orderNetAmount > 0 && $cartData['cartWalletSelected'] && ($userWalletBalance >= $orderNetAmount) && !$pmethodId) {
            $redirectUrl = CommonHelper::generateUrl('WalletPay', 'Charge', [$order->getOrderId()], CONF_WEBROOT_FRONTEND);
            $this->set('msg', Label::getLabel('LBL_Processing...', $this->siteLangId));
            $this->set('redirectUrl', $redirectUrl);
            $this->_template->render(false, false, 'json-success.php');
        }
        if ($pmethodId > 0) {
            $controller = $paymentMethod['pmethod_code'] . 'Pay';
            $redirectUrl = CommonHelper::generateUrl($controller, 'charge', [$order->getOrderId()], CONF_WEBROOT_FRONTEND);
            $this->set('msg', Label::getLabel('LBL_Processing...', $this->siteLangId));
            $this->set('redirectUrl', $redirectUrl);
            $this->cartObj->clear();
            $this->cartObj->updateUserCart();
            $this->_template->render(false, false, 'json-success.php');
        }
        Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
        FatUtility::dieWithError(Message::getHtml());
    }

    private function getPaymentTabForm($langId = 0)
    {
        $frm = new Form('frmPaymentTabForm');
        $frm->setFormTagAttribute('id', 'frmPaymentTabForm');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Confirm_Payment', $langId));
        $frm->addHiddenField('', 'order_type');
        $frm->addHiddenField('', 'order_id');
        $frm->addHiddenField('', 'pmethod_id');
        return $frm;
    }

    private function getWalletPaymentForm()
    {
        $frm = new Form('frmWalletPayment');
        return $frm;
    }

    private function isEligibleForNextStep(&$criteria = [])
    {
        if (empty($criteria)) {
            return true;
        }
        foreach ($criteria as $key => $val) {
            switch ($key) {
                case 'isUserLogged':
                    if (!UserAuthentication::isUserLogged()) {
                        $key = false;
                        Message::addErrorMessage(Label::getLabel('MSG_Your_Session_seems_to_be_expired.'));
                        return false;
                    }
                    break;
                case 'hasItems':
                    if (!$this->cartObj->hasItems()) {
                        $key = false;
                        Message::addErrorMessage(Label::getLabel('MSG_Teacher_booking_selection_is_not_yet_been_selected,_Please_try_selecting_the_appropriate_teacher_and_start_booking_lesson.'));
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    private function getConfirmFormWithNoAmount()
    {
        $frm = new Form('frmConfirmForm');
        $frm->addFormTagAttribute('onSubmit', 'return confirmOrder(this);');
        return $frm;
    }

    public function getLanguagePackages()
    {
        $criteria = ['isUserLogged' => true, 'hasItems' => true];
        if (!$this->isEligibleForNextStep($criteria)) {
            if (Message::getErrorCount()) {
                $errMsg = Message::getHtml();
            } else {
                Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
                $errMsg = Message::getHtml();
            }
            FatUtility::dieWithError($errMsg);
        }
        $post = FatApp::getPostedData();
        if (false == $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $cartData = $this->cartObj->getCart($this->siteLangId);
        // if buying group class, then show no packages
        if ($cartData['grpcls_id'] > 0) {
            die('');
        }
        $teacherOfferClassObj = new TeacherOfferPrice();
        $srchdata = $teacherOfferClassObj->getOffer(UserAuthentication::getLoggedUserId(), $post['teacher_id'], $cartData['lessonDuration']);
        $srchdata->doNotCalculateRecords();
        $srchdata->setPageSize(1);
        $rs = $srchdata->getResultSet();
        $teacherOffer = FatApp::getDb()->fetch($rs);
        $lessonPackages = LessonPackage::getPackagesWithoutTrial($this->siteLangId);
        if (empty($lessonPackages)) {
            $errMsg = Message::addErrorMessage(Label::getLabel('MSG_PLEASE_CONCAT_WITH_ADMIN_NO_LESSON_PACKAGE_ACTIVE'));
            FatUtility::dieWithError($errMsg);
        }
        $lessonPackageIds = array_column($lessonPackages, 'lpackage_id', 'lpackage_id');
        if (!array_key_exists($cartData['lpackage_id'], $lessonPackageIds)) {
            $cartData['lpackage_id'] = $lessonPackages[0]['lpackage_id'];
            $cart = new Cart();
            $cart->updateLessonPackageId($cartData['lpackage_id']);
        }
        $data = UserSetting::getUserSettings($post['teacher_id'], $post['languageId'], $post['lessonDuration']);
        $this->set('cartData', $cartData);
        $this->set('teacherOffer', $teacherOffer);
        $this->set('languageId', $post['languageId']);
        $this->set('lessonPackages', $lessonPackages);
        $this->set('selectedLang', current($data));
        $this->_template->render(false, false);
    }

    public function getBookingDurations()
    {
        $cartData = $this->cartObj->getCart($this->siteLangId);
        $criteria = ['isUserLogged' => true, 'hasItems' => true];
        if (!$this->isEligibleForNextStep($criteria)) {
            if (Message::getErrorCount()) {
                $errMsg = Message::getHtml();
            } else {
                Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
                $errMsg = Message::getHtml();
            }
            FatUtility::dieWithError($errMsg);
        }
        if ($cartData['grpcls_id'] > 0) {
            die('');
        }
        $teacher_id = $cartData['user_id'];
        $confPaidLessonDuration = CommonHelper::getPaidLessonDurations();
        $userSrch = new UserSearch();
        $userTeachLangSrch = $userSrch->getMyTeachLangQry();
        $userTeachLangSrch->addCondition('utl_user_id', '=', $teacher_id);
        $userTeachLangSrch->addCondition('utl_booking_slot', 'IN', $confPaidLessonDuration);
        $rs = $userTeachLangSrch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (empty($row)) {
            Message::addErrorMessage(Label::getLabel('MSG_TEACHER_HAS_NOT_ANY_SLOT_DURATION'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $bookingDurations = array_unique(explode(',', $row['ustelgpr_slots']));
        $lessonPackages = LessonPackage::getPackagesWithoutTrial($this->siteLangId);
        if (empty($lessonPackages)) {
            Message::addErrorMessage(Label::getLabel('MSG_PLEASE_CONCAT_WITH_ADMIN_NO_LESSON_PACKAGE_ACTIVE'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $lessonPackageIds = array_column($lessonPackages, 'lpackage_id', 'lpackage_id');
        if (!array_key_exists($cartData['lpackage_id'], $lessonPackageIds)) {
            $cartData['lpackage_id'] = $lessonPackages[0]['lpackage_id'];
            $cart = new Cart();
            $cart->updateLessonPackageId($cartData['lpackage_id']);
        }
        $this->set('cartData', $cartData);
        $this->set('bookingDurations', $bookingDurations);
        $this->_template->render(false, false);
    }
}
