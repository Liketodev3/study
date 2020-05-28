<?php
class WalletPayController extends LoggedUserController
{
    public function charge($orderId = '')
    {
        $isAjaxCall = FatUtility::isAjaxCall();
        if (!$orderId || ((isset($_SESSION['shopping_cart']["order_id"]) && $orderId != $_SESSION['shopping_cart']["order_id"]))) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
            if ($isAjaxCall) {
                FatUtility::dieWithError(Message::getHtml());
            }
            CommonHelper::redirectUserReferer();
        }
        $userId = UserAuthentication::getLoggedUserId();
        $srch = new OrderSearch();
        $srch->joinOrderProduct();
        $srch->addCondition('order_id', '=', $orderId);
        $srch->addCondition('order_user_id', '=', $userId);
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PENDING);
        $srch->addMultipleFields(array(
            'order_id',
            'order_user_id',
            'order_net_amount',
            'order_wallet_amount_charge'
        ));
        $rs = $srch->getResultSet();
        $orderInfo = FatApp::getDb()->fetch($rs);
        if (!$orderInfo) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
            if ($isAjaxCall) {
                FatUtility::dieWithError(Message::getHtml());
            }
            CommonHelper::redirectUserReferer();
        }
        $orderObj = new Order();
        $orderPaymentFinancials = $orderObj->getOrderPaymentFinancials($orderId);
        if ($orderPaymentFinancials["order_credits_charge"] > 0) {
            $orderPaymentObj = new OrderPayment($orderId);
            $orderPaymentObj->chargeUserWallet($orderPaymentFinancials["order_credits_charge"]);
        }
        $cartObj = new Cart();
        $cartObj->clear();
        $cartObj->updateUserCart();
        if ($isAjaxCall) {
            $this->set('redirectUrl', CommonHelper::generateUrl('Custom', 'paymentSuccess', array($orderId)));
            $this->set('msg', Label::getLabel("MSG_Payment_from_wallet_made_successfully", $this->siteLangId));
            $this->_template->render(false, false, 'json-success.php');
        }
        FatApp::redirectUser(CommonHelper::generateUrl('Custom', 'paymentSuccess', array($orderId)));
    }

    public function recharge($orderId)
    {
        if ($orderId == '' || ((isset($_SESSION['wallet_recharge_cart']) && $orderId != $_SESSION['wallet_recharge_cart']["order_id"]))) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        $loggedUserId = UserAuthentication::getLoggedUserId();
        $orderObj = new Order();
        $srch = Order::getSearchObject();
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('order_id', '=', $orderId);
        $srch->addCondition('order_user_id', '=', $loggedUserId);
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PENDING);
        $srch->addCondition('order_type', '=', Order::TYPE_WALLET_RECHARGE);
        $rs = $srch->getResultSet();
        $orderInfo = FatApp::getDb()->fetch($rs);
        if (!$orderInfo) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
            if ($isAjaxCall) {
                FatUtility::dieWithError(Message::getHtml());
            }
            CommonHelper::redirectUserReferer();
        }
        $this->set('orderInfo', $orderInfo);
        $userObj = new User($loggedUserId);
        $userDetails = $userObj->getUserInfo();
        $pmSrch = PaymentMethods::getSearchObject($this->siteLangId);
        $pmSrch->doNotCalculateRecords();
        $pmSrch->doNotLimitRecords();
        $pmSrch->addMultipleFields(array('pmethod_id', 'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name', 'pmethod_code', 'pmethod_description'));
        $pmRs = $pmSrch->getResultSet();
        $paymentMethods = FatApp::getDb()->fetchAll($pmRs);
        $this->set('userDetails', $userDetails);
        $this->set('orderId', $orderId);
        $this->set('paymentMethods', $paymentMethods);
        $this->_template->render(true, true);
    }
}
