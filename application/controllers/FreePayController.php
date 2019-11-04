<?php
class FreePayController extends MyAppController {
	public function charge($orderId = '') {
		$isAjaxCall = FatUtility::isAjaxCall();
		if (!$orderId || ((isset($_SESSION['shopping_cart']["order_id"]) && $orderId != $_SESSION['shopping_cart']["order_id"]))) {
			Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access',$this->siteLangId));
			if ($isAjaxCall) {
				FatUtility::dieWithError(Message::getHtml());
			}
			CommonHelper::redirectUserReferer();
		}
		if (!UserAuthentication::isUserLogged()) {
			Message::addErrorMessage(Label::getLabel('MSG_Your_Session_seems_to_be_expired.', $this->siteLangId));
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
			'order_wallet_amount_charge',
			'op_teacher_id',
			'op_slanguage_id',
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
		
		if ($orderInfo['order_net_amount'] > 0) {
			Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access'));
			if ($isAjaxCall) {
				FatUtility::dieWithError(Message::getHtml());
			}
			CommonHelper::redirectUserReferer();
		}
		$orderObj = new Order();
		$orderPaymentFinancials = $orderObj->getOrderPaymentFinancials($orderId);
		//$orderPaymentGatewayCharge = $orderInfo["order_net_amount"] - $orderInfo["order_wallet_amount_charge"];
		if ($orderPaymentFinancials['order_payment_gateway_charge'] > 0) {
			FatApp::redirectUser(CommonHelper::generateUrl('Custom', 'paymentFailure', array($orderId)));
		}
		$orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
		if (!$orderPaymentObj->chargeFreeOrder()) {
			Message::addErrorMessage($orderPaymentObj->getError());
			if ($isAjaxCall) {
				FatUtility::dieWithError(Message::getHtml());
			}
			CommonHelper::redirectUserReferer();
		}
		
		$cartObj = new Cart();
		$cartData = $cartObj->getCart($this->siteLangId);
		
		/* add schedulaed lessons[ */
		if ($cartData['lpackage_is_free_trial']) { //== only for free trial
		$sLessonArr = array(
			'slesson_order_id' => $orderId,
			'slesson_teacher_id' => $orderInfo['op_teacher_id'],
			'slesson_learner_id' => $orderInfo['order_user_id'],
			'slesson_slanguage_id' => $orderInfo['op_slanguage_id'],
			'slesson_date' => date('Y-m-d', strtotime($cartData['startDateTime'])),
			'slesson_end_date' => date('Y-m-d', strtotime($cartData['endDateTime'])),
			'slesson_start_time' => date('H:i:s', strtotime($cartData['startDateTime'])),
			'slesson_end_time' => date('H:i:s', strtotime($cartData['endDateTime'])),
			'slesson_status' => ScheduledLesson::STATUS_SCHEDULED
		);
		
		$sLessonObj = new ScheduledLesson();
		$sLessonObj->assignValues($sLessonArr);
		if (!$sLessonObj->save()) {
			Message::addErrorMessage($sLessonObj->getError());
			if ($isAjaxCall) {
				FatUtility::dieWithError(Message::getHtml());
			}
			CommonHelper::redirectUserReferer();
		}
		}
		/* ] */
		$cartObj = new Cart();
		$cartObj->clear();
		$cartObj->updateUserCart();
		if ($isAjaxCall) {
			$this->set('redirectUrl', CommonHelper::generateUrl('Custom','paymentSuccess'));
			$this->set('msg', Label::getLabel("MSG_Payment_from_wallet_made_successfully", $this->siteLangId));
			$this->_template->render(false, false, 'json-success.php');
		}
		FatApp::redirectUser(CommonHelper::generateUrl('Custom', 'paymentSuccess'));
	}
}