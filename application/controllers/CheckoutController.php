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
        if (0 >= $cartData['grpclsId']) {
            $getUserTeachLanguages = $this->getTeacherTeachLanguages($cartData['teacherId']);
            $userTeachLanguages = FatApp::getDb()->fetchAll($getUserTeachLanguages->getResultSet());
            if(empty($userTeachLanguages)){
                Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
                FatApp::redirectUser(CommonHelper::generateUrl());
            }
            $this->set('userTeachLanguages', $userTeachLanguages);
        }

        $confirmForm = $this->getConfirmFormWithNoAmount($this->siteLangId);
        if ($cartData['orderNetAmount'] <= 0) {
            $confirmForm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Confirm_Order'));
        }
        $cartForm = $this->addToCartForm();
        $this->set('confirmForm', $confirmForm);
        $this->set('cartData', $cartData);
        $this->set('cartForm', $cartForm);
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

        $criteria = ['isUserLogged' => true, 'hasItems' => true];
        if (!$this->isEligibleForNextStep($criteria)) {
            $errMsg = Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.');
            if (Message::getErrorCount()) {
                $errMsg = Message::getHtml();
            }
            $json['msg'] = $errMsg;
            $json['redirectUrl'] = CommonHelper::generateUrl('Checkout');
            FatUtility::dieJsonError($json);
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
        if ($cartData['isFreeTrial']) {
            $op_lesson_duration = FatApp::getConfig('conf_trial_lesson_duration', FatUtility::VAR_INT, 30);
        } else {
            $commissionDetails = Commission::getTeacherCommission($cartData['teacherId'], $cartData['grpclsId']);
            if ($commissionDetails) {
                $cartData['op_commission_percentage'] = $commissionDetails['commsetting_fees'];
                $teacherCommission = ((100 - $commissionDetails['commsetting_fees']) * $cartData['itemPrice']) / 100;
            } else {
                $teacherCommission = $cartData['itemPrice'];
            }
            $teacherCommission = $teacherCommission;
            $cartData['op_commission_charged'] = $teacherCommission;
        }

        $products = [
            'op_grpcls_id' => $cartData['grpclsId'],
            'op_lpackage_is_free_trial' => $cartData['isFreeTrial'],
            'op_lesson_duration' => $op_lesson_duration,
            'op_teacher_id' => $cartData['user_id'],
            'op_qty' => $cartData['grpclsId'] == 0 ? $cartData['lessonQty'] : 1,
            'op_commission_charged' => $cartData['op_commission_charged'],
            'op_commission_percentage' => $cartData['op_commission_percentage'],
            'op_unit_price' => $cartData['itemPrice'],
            'op_tlanguage_id' => $cartData['languageId'],
        ];
        $productsLangData = [];
        // $allLanguages = Language::getAllNames();
        // foreach ($allLanguages as $lang_id => $language_name) {
        //     $langSpecificLPackageRow = LessonPackage::getAttributesByLangId($lang_id, $cartData['lpackage_id']);
        //     if (!$langSpecificLPackageRow) {
        //         continue;
        //     }
        //     $productsLangData[$lang_id] = ['oplang_lang_id' => $lang_id, 'op_lpackage_title' => $langSpecificLPackageRow['lpackage_title']];
        // }
        $products['productsLangData'] = $productsLangData;
        $orderData['products'][] = $products;
        /* ] */
        
        $order = new Order();
        if (!$order->addUpdate($orderData)) {
            Message::addErrorMessage($order->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */
        $redirectUrl = '';
        $msg = Label::getLabel('LBL_Processing...', $this->siteLangId);
        if (0 >= $orderNetAmount) {
            $redirectUrl = CommonHelper::generateUrl('FreePay', 'Charge', [$order->getOrderId()], CONF_WEBROOT_FRONT_URL);
            $this->set('redirectUrl', $redirectUrl);
            FatUtility::dieJsonSuccess(['redirectUrl' => $redirectUrl, 'msg' => $msg]);
        }
        $userId = UserAuthentication::getLoggedUserId();
        $userWalletBalance = User::getUserBalance($userId);
        if ($orderNetAmount > 0 && $cartData['cartWalletSelected'] && ($userWalletBalance >= $orderNetAmount) && !$pmethodId) {
            $redirectUrl = CommonHelper::generateUrl('WalletPay', 'Charge', [$order->getOrderId()], CONF_WEBROOT_FRONTEND);
            FatUtility::dieJsonSuccess(['redirectUrl' => $redirectUrl, 'msg' => $msg]);
        }
        if ($pmethodId > 0) {
            $controller = $paymentMethod['pmethod_code'] . 'Pay';
            $redirectUrl = CommonHelper::generateUrl($controller, 'charge', [$order->getOrderId()], CONF_WEBROOT_FRONTEND);
            $this->cartObj->clear();
            $this->cartObj->updateUserCart();
            FatUtility::dieJsonSuccess(['redirectUrl' => $redirectUrl, 'msg' => $msg]);
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

    public function getTeacherPriceSlabs()
    {
        $pricSlabForm = $this->pricSlabForm();
        $post =  $pricSlabForm->getFormDataFromArray(FatApp::getPostedData());

        if($post == false){
            FatUtility::dieJsonError(current($pricSlabForm->getValidationErrors()));
        }
       
        $cartData = $this->cartObj->getCart($this->siteLangId);
        $criteria = ['isUserLogged' => true, 'hasItems' => true];
        if (!$this->isEligibleForNextStep($criteria)) {
            $errMsg = Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.');
            if (Message::getErrorCount()) {
                $errMsg = Message::getHtml();
            } 
            FatUtility::dieWithError($errMsg);
        }

        $getUserTeachLanguages = $this->getTeacherTeachLanguages($cartData['teacherId']);
        $getUserTeachLanguages->addCondition('utl_tlanguage_id', '=', $post['languageId']);
        $getUserTeachLanguages->addCondition('ustelgpr_slot', '=' , $post['lessonDuration']);
        $getUserTeachLanguages->addFld('IF(prislab_max >= '.$cartData['lessonQty'].' and prislab_min <= '.$cartData['lessonQty'].', 1,0) as isSlapCollapse');
        $slabs = FatApp::getDb()->fetchAll($getUserTeachLanguages->getResultSet(), 'ustelgpr_prislab_id');
        if(empty($slabs)){
            FatUtility::dieJsonError(Label::getLabel('LBL_SLAB_NOT_AVAILABLE'));
        }
        $this->set('slabs', $slabs);
        $this->set('cartData', $cartData);
        $this->_template->render(false, false);
    }

    private function getTeacherTeachLanguages(int $teacherId): SearchBase
    {
        $loggedUserId = UserAuthentication::getLoggedUserId();
        $userTeachLanguage = new UserTeachLanguage($teacherId);
        $getUserTeachLanguages = $userTeachLanguage->getUserTeachlanguages($this->siteLangId, true);
        $getUserTeachLanguages->joinTable(PriceSlab::DB_TBL, 'INNER JOIN', 'prislab.prislab_id = ustelgpr.ustelgpr_prislab_id', 'prislab');
        $getUserTeachLanguages->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top.top_teacher_id = utl.utl_user_id and top.top_learner_id = ' . $loggedUserId . ' and top.top_lesson_duration = ustelgpr.ustelgpr_slot', 'top');
        $getUserTeachLanguages->doNotCalculateRecords();
        $getUserTeachLanguages->addMultipleFields([
            'IFNULL(tlanguage_name, tlanguage_identifier) as teachLangName',
            'utl_id',
            'utl_tlanguage_id',
            'ustelgpr_prislab_id',
            'ustelgpr_slot',
            'ustelgpr_price',
            'prislab_min',
            'prislab_max',
            'ustelgpr_price',
            'IFNULL(top_percentage,0) as top_percentage',
        ]);
        return $getUserTeachLanguages;
    }

    private function pricSlabForm() : Form
    {
        $form = new Form('pricSlabsForm');
        $languageId = $form->addIntegerField(Label::getLabel('Lbl_Teach_language'),  'languageId');
        $languageId->requirements()->setRequired();
        $languageId->requirements()->setRange(1,99999999);
        $durations = CommonHelper::getPaidLessonDurations();
        $lessonDuration = $form->addSelectBox(Label::getLabel('Lbl_Duration'),  'lessonDuration', array_flip($durations));
        $lessonDuration->requirements()->setRequired(true);
        return $form;
    }

}
