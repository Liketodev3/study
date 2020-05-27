<?php
class CheckoutController extends LoggedUserController{
	private $cartObj;

	public function __construct($action) {
		parent::__construct($action);
		$this->cartObj = new Cart( UserAuthentication::getLoggedUserId() );
	}

	public function index(){
		$criteria = array( 'isUserLogged' => true, 'hasItems' =>  true );
		if( !$this->isEligibleForNextStep( $criteria ) ){
			if( !Message::getErrorCount() ){
				Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
			}
			FatApp::redirectUser( CommonHelper::generateUrl() );
		}

		/* [ */
		$srch = LessonPackage::getSearchObject( $this->siteLangId );
		$srch->addCondition( 'lpackage_is_free_trial', '=', 0 );
		$srch->addMultipleFields(array(
			'lpackage_id',
			'IFNULL(lpackage_title, lpackage_identifier) as lpackage_title',
			'lpackage_lessons'
        ));
		$rs = $srch->getResultSet();
		$lessonPackages = FatApp::getDb()->fetchAll($rs);
		/* ] */

		$cartData = $this->cartObj->getCart( $this->siteLangId );
        /* Languages [ */
        $userSrchObj = new UserSearch();
        $tLangsrch = $userSrchObj->getMyTeachLangQry();
        $tLangsrch->addCondition('utl_us_user_id','=',$cartData['user_id']);
		$rs = $tLangsrch->getResultSet();
		$tLangs = FatApp::getDb()->fetch($rs);
        $teachLanguages = TeachingLanguage::getAllLangs($this->siteLangId,true);
		$tlangArr =array();
        if(!empty($tLangs['utl_slanguage_ids'])){
            $tLangidsArr = explode(',',$tLangs['utl_slanguage_ids']);
            $tlangArr = array_intersect_key($teachLanguages, array_flip($tLangidsArr));
        }

        /* ] */
		$confirmForm = $this->getConfirmFormWithNoAmount( $this->siteLangId );
		if( $cartData['orderNetAmount'] <= 0 ) {
			//$confirmForm->addFormTagAttribute('action', CommonHelper::generateUrl('ConfirmPay','Charge', array($order_id)) );
			/* $confirmForm->setFormTagAttribute('onsubmit', 'confirmOrderWithoutPayment(this); return(false);'); */
			$confirmForm->addSubmitButton( '', 'btn_submit', Label::getLabel('LBL_Confirm_Order') );
		}

		$this->set('teachLanguages', $tlangArr);

		$this->set('lessonPackages',$lessonPackages);
		$this->set('tLangs',$tLangs['utl_slanguage_ids']);
		$this->set('cartData', $cartData );
		$this->set('confirmForm', $confirmForm );
        $this->_template->render();
	}

	public function listFinancialSummary(){
		$json = array();
		$cart = $this->cartObj->getCart( $this->siteLangId );
		$userWalletBalance = User::getUserBalance( UserAuthentication::getLoggedUserId() );
		$this->set('userWalletBalance', $userWalletBalance );
		$this->set('cart', $cart );
		$json['html'] = $this->_template->render( false, false, 'checkout/list-financial-summary.php', true, false);
		$json['couponApplied'] = isset($cart['cartDiscounts']['coupon_discount_total'])?$cart['cartDiscounts']['coupon_discount_total']:0;
		FatUtility::dieJsonSuccess($json);
	}

	public function getCouponForm(){
		$loggedUserId = UserAuthentication::getLoggedUserId();
		$orderId = isset($_SESSION['order_id'])?$_SESSION['order_id']:'';
		$cartSummary = $this->cartObj->getCart( $this->siteLangId );
		$couponsList = DiscountCoupons::getValidCoupons( $loggedUserId, $this->siteLangId,'',$orderId );
		$this->set( 'couponsList', $couponsList );
		$this->set('cartSummary', $cartSummary );
		$PromoCouponsFrm = $this->getPromoCouponsForm($this->siteLangId);
		$this->set('PromoCouponsFrm', $PromoCouponsFrm );
		$this->_template->render(false, false);
	}

	private function getPromoCouponsForm($langId){
		$langId = FatUtility::int($langId);
		$frm = new Form('frmPromoCoupons');
		$fld = $frm->addTextBox(Label::getLabel('LBL_Coupon_code',$langId),'coupon_code','',array('placeholder'=>Label::getLabel('LBL_Enter_Your_code',$langId)));
		$fld->requirements()->setRequired();
		$frm->addSubmitButton('', 'btn_submit',Label::getLabel('LBL_Apply',$langId));
		return $frm;
	}

	public function paymentSummary(){
		$criteria = array( 'isUserLogged' => true, 'hasItems' =>  true );
		if( !$this->isEligibleForNextStep( $criteria ) ){
			if( Message::getErrorCount() ){
				$errMsg = Message::getHtml();
			} else {
				Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
				$errMsg = Message::getHtml();
			}
			if( FatUtility::isAjaxCall() == true) {
				$json['errorMsg'] = $errMsg;
				$json['redirectUrl'] = CommonHelper::generateUrl('Checkout');
				FatUtility::dieJsonError($json);
			}
			FatUtility::dieWithError( $errMsg );
		}

		$userId = UserAuthentication::getLoggedUserId();
		$userWalletBalance = User::getUserBalance( $userId );
		$cartData = $this->cartObj->getCart($this->siteLangId);
		$WalletPaymentForm = $this->getWalletPaymentForm( );

		if( (FatUtility::convertToType( $userWalletBalance,FatUtility::VAR_FLOAT) >= FatUtility::convertToType($cartData['orderNetAmount'], FatUtility::VAR_FLOAT) ) && $cartData['cartWalletSelected'] ){
			$WalletPaymentForm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');
			$WalletPaymentForm->addSubmitButton( '', 'btn_submit', Label::getLabel('LBL_Pay_Now') );
		}

		/* Payment Methods[ */
		$pmSrch = PaymentMethods::getSearchObject( $this->siteLangId );
		$pmSrch->doNotCalculateRecords();
		$pmSrch->doNotLimitRecords();
		$pmSrch->addMultipleFields(
			array(
				'pmethod_id',
				'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name',
				'pmethod_code',
				'pmethod_description'
				));

		$pmRs = $pmSrch->getResultSet();
		$paymentMethods = FatApp::getDb()->fetchAll($pmRs);
		/* ] */
		$confirmForm = $this->getConfirmFormWithNoAmount( $this->siteLangId );
		if( $cartData['orderPaymentGatewayCharges'] <= 0 ) {
			//$confirmForm->addFormTagAttribute('action', CommonHelper::generateUrl('ConfirmPay','Charge', array($order_id)) );
			/* $confirmForm->setFormTagAttribute('onsubmit', 'confirmOrderWithoutPayment(this); return(false);'); */
			$confirmForm->addSubmitButton( '', 'btn_submit', Label::getLabel('LBL_Confirm_Order') );
		}


		$this->set( 'confirmForm', $confirmForm );
		$this->set( 'paymentMethods', $paymentMethods );
		$this->set( 'userWalletBalance', $userWalletBalance );
		$this->set('cartData', $cartData);
		$this->set('WalletPaymentForm', $WalletPaymentForm );
		$this->_template->render( false, false );
	}

	public function walletSelection(){
		$payFromWallet = FatApp::getPostedData('payFromWallet', FatUtility::VAR_INT, 0);
		$this->cartObj->updateCartWalletOption( $payFromWallet );
		$this->_template->render(false, false, 'json-success.php');
	}

	public function paymentTab($pmethod_id, $order_id = ''){
		$pmethodId = FatUtility::int( $pmethod_id );
		if( !$pmethodId ){
			FatUtility::dieWithError( Label::getLabel("MSG_Invalid_Request!", $this->siteLangId) );
		}

		if( !UserAuthentication::isUserLogged() ){
			FatUtility::dieWithError( Label::getLabel('MSG_Your_Session_seems_to_be_expired.', $this->siteLangId) );
		}
		$orderInfo = [];
		$netAmmount =0;
		if(!empty($order_id)) {
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
			$netAmmount =  $orderInfo['order_net_amount'];
		}

		/* [ */
		$pmSrch = PaymentMethods::getSearchObject( $this->siteLangId );
		$pmSrch->doNotCalculateRecords();
		$pmSrch->doNotLimitRecords();
		$pmSrch->addMultipleFields(array('pmethod_id', 'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name', 'pmethod_code', 'pmethod_description'));
		$pmSrch->addCondition('pmethod_id','=',$pmethodId);
		$pmRs = $pmSrch->getResultSet();
		$paymentMethod = FatApp::getDb()->fetch($pmRs);
		if( !$paymentMethod ){
			FatUtility::dieWithError( Label::getLabel("MSG_Selected_Payment_method_not_found!", $this->siteLangId) );
		}
		$this->set('paymentMethod', $paymentMethod);
		/* ] */

		/* [ */
		$frm = $this->getPaymentTabForm( $this->siteLangId );
		if(!empty($order_id)) {
			$frm->fill(array('order_id' => $order_id,'order_type' => $orderInfo['order_type']));
		}
		/* $controller = $paymentMethod['pmethod_code'].'Pay';
		$frm->setFormTagAttribute( 'action', CommonHelper::generateUrl( $controller, 'charge', array( $orderInfo['order_id']) ) ); */
		$frm->fill(array( 'pmethod_id' => $pmethodId ) );
		$this->set( 'frm', $frm );
		/* ] */
		$cartData = [];
		if(empty($order_id)) {
			$cartData = $this->cartObj->getCart($this->siteLangId);
			$netAmmount = $cartData['orderPaymentGatewayCharges'];
			$this->set('cartData', $cartData);
		}
		$this->set('netAmmount', $netAmmount);
		$this->_template->render( false, false, '', false, false );
	}

	public function confirmOrder(){
		$order_type = FatApp::getPostedData('order_type', FatUtility::VAR_INT, 0);
        $pmethodId = FatApp::getPostedData('pmethod_id', FatUtility::VAR_INT, 0);
        $order_id = FatApp::getPostedData("order_id", FatUtility::VAR_STRING, "");
		/* [ */
		if( $pmethodId > 0 ){
			$pmSrch = PaymentMethods::getSearchObject( $this->siteLangId );
			$pmSrch->doNotCalculateRecords();
			$pmSrch->doNotLimitRecords();
			$pmSrch->addMultipleFields(array('pmethod_id', 'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name', 'pmethod_code', 'pmethod_description'));
			$pmSrch->addCondition('pmethod_id','=',$pmethodId);
			$pmRs = $pmSrch->getResultSet();
			$paymentMethod = FatApp::getDb()->fetch($pmRs);
			if( !$paymentMethod ){
				Message::addErrorMessage( Label::getLabel("MSG_Selected_Payment_method_not_found!") );
				FatUtility::dieWithError( Message::getHtml() );
			}
		}
		/* ] */
		/* Loading Money to wallet[ */
		if ($order_type == Order::TYPE_WALLET_RECHARGE || $order_type == Order::TYPE_GIFTCARD) {
			$criteria = array( 'isUserLogged' => true );
			if (!$this->isEligibleForNextStep($criteria)) {
				if (Message::getErrorCount()) {
					$errMsg = Message::getHtml();
				}else {
					Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
					$errMsg = Message::getHtml();
				}
				FatUtility::dieWithError($error);
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

			$orderObj->updateOrderInfo($order_id, array('order_pmethod_id' => $pmethodId));
			$controller = $paymentMethod['pmethod_code'].'Pay';
			$redirectUrl = CommonHelper::generateUrl($controller, 'charge', array($order_id));
			$this->set('msg', Label::getLabel('LBL_Processing...'));
			$this->set('redirectUrl', $redirectUrl);
			$this->_template->render(false, false, 'json-success.php');
		}

		$cartData = $this->cartObj->getCart( $this->siteLangId );
		$criteria = array(
			'isUserLogged' => true,
			'hasItems' =>  true,
		);
		if( !$this->isEligibleForNextStep( $criteria ) ){
		if( Message::getErrorCount() ){
			$errMsg = Message::getHtml();
		} else {
			Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
			$errMsg = Message::getHtml();
		}
			FatUtility::dieWithError( $errMsg );
		}
		/* if( $cartData['cartWalletSelected'] && $cartData['orderPaymentGatewayCharges'] == 0 ){
			Message::addErrorMessage( Label::getLabel('MSG_Try_to_pay_using_wallet_balance_as_amount_for_payment_gateway_is_not_enough.') );
			FatUtility::dieWithError( Message::getHtml() );
		} */

		if( $cartData['orderPaymentGatewayCharges'] == 0 && $pmethodId ){
			Message::addErrorMessage( Label::getLabel('MSG_Amount_for_payment_gateway_must_be_greater_than_zero.' ) );
			FatUtility::dieWithError( Message::getHtml() );
		}

		/* addOrder[ */
		$order_id = isset($_SESSION['shopping_cart']["order_id"]) ? $_SESSION['shopping_cart']["order_id"] : false;
		$orderNetAmount = $cartData["orderNetAmount"] * CommonHelper::getCurrencyValue();
		$walletAmountCharge = $cartData["walletAmountCharge"] * CommonHelper::getCurrencyValue();
		$coupon_discount_total = $cartData['cartDiscounts']['coupon_discount_total'] * CommonHelper::getCurrencyValue();

		$orderData = array(
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
		);

		$languageRow = Language::getAttributesById($this->siteLangId);
		$orderData['order_language_id'] =  $languageRow['language_id'];
		$orderData['order_language_code'] =  $languageRow['language_code'];

		/* [ */
		$op_lesson_duration = FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_INT, 60);

		$cartData['op_commission_charged'] = 0;
		$cartData['op_commission_percentage'] = 0;

		if( $cartData['lpackage_is_free_trial'] ){
			$op_lesson_duration = FatApp::getConfig( 'conf_trial_lesson_duration', FatUtility::VAR_INT, 30 );
		}else{
			$commissionDetails = Commission::getTeacherCommission($cartData['user_id']);
			if ($commissionDetails) {
				$cartData['op_commission_percentage'] = $commissionDetails['commsetting_fees'];
                $teacherCommission = ( (100 - $commissionDetails['commsetting_fees']) * $cartData['itemPrice'] ) / 100;
			}
            else {
                $teacherCommission = $cartData['itemPrice'];
            }
			$teacherCommission = $teacherCommission * CommonHelper::getCurrencyValue();

            /*$maxCommission = FatApp::getConfig('CONF_MAX_COMMISSION', FatUtility::VAR_INT, 0);
            if($maxCommission > 0 AND $maxCommission <= $teacherCommission){
                $teacherCommission = $maxCommission;
            }*/
            $cartData['op_commission_charged'] = $teacherCommission;
		}

		$products[$cartData['lpackage_id']] = array(
			'op_lpackage_id' => $cartData['lpackage_id'],
			'op_lpackage_lessons' => $cartData['lpackage_lessons'],
			'op_lpackage_is_free_trial' => $cartData['lpackage_is_free_trial'],
			'op_lesson_duration' => $op_lesson_duration,
			'op_teacher_id' => $cartData['user_id'],
			//'op_qty' => 1,
			'op_qty'	=>	$cartData['lpackage_lessons'],
			'op_commission_charged' => $cartData['op_commission_charged'],
			'op_commission_percentage' => $cartData['op_commission_percentage'],
			'op_unit_price' => $cartData['itemPrice'],
			'op_orderstatus_id' => FatApp::getConfig("CONF_DEFAULT_ORDER_STATUS"),
			'op_slanguage_id' => $cartData['languageId'],
		);

		$productsLangData = array();
		$allLanguages = Language::getAllNames();
		foreach ( $allLanguages as $lang_id => $language_name ) {
			$langSpecificLPackageRow = LessonPackage::getAttributesByLangId( $lang_id, $cartData['lpackage_id'] );
			if( !$langSpecificLPackageRow ){
				continue;
			}

			$productsLangData[$lang_id]	= array(
				'oplang_lang_id' => $lang_id,
				'op_lpackage_title' => $langSpecificLPackageRow['lpackage_title']
			);
		}

		$products[$cartData['lpackage_id']]['productsLangData'] = $productsLangData;
		$orderData['products'] = $products;
		/* ] */

		$order = new Order( );
		if( !$order->addUpdate( $orderData ) ){
			Message::addErrorMessage( $order->getError() );
			FatUtility::dieWithError( Message::getHtml() );
		}
		/* ] */

		$redirectUrl = '';
		/* if( $cartData['lpackage_is_free_trial'] == 1 && $cartData['orderNetAmount'] <= 0 ){ */
		if( 0 >= $orderNetAmount ){
			$redirectUrl = CommonHelper::generateUrl('FreePay', 'Charge', array($order->getOrderId()));
			$this->set( 'msg', Label::getLabel('LBL_Processing...', $this->siteLangId) );
			$this->set( 'redirectUrl', $redirectUrl );
			$this->_template->render(false, false, 'json-success.php');
		}

		$userId = UserAuthentication::getLoggedUserId();
		$userWalletBalance = User::getUserBalance($userId);
		$userWalletBalance = $userWalletBalance * CommonHelper::getCurrencyValue();

		if ( $orderNetAmount > 0 && $cartData['cartWalletSelected'] && ($userWalletBalance >= $orderNetAmount) && !$pmethodId ) {
			$redirectUrl = CommonHelper::generateUrl('WalletPay','Charge', array($order->getOrderId()) );
			$this->set('msg', Label::getLabel('LBL_Processing...', $this->siteLangId));
			$this->set('redirectUrl', $redirectUrl);
			$this->_template->render(false, false, 'json-success.php');
		}

		if ($pmethodId > 0) {
			$controller = $paymentMethod['pmethod_code'].'Pay';
			$redirectUrl = CommonHelper::generateUrl($controller, 'charge', array($order->getOrderId()));
			$this->set('msg', Label::getLabel('LBL_Processing...', $this->siteLangId));
			$this->set('redirectUrl', $redirectUrl);

			$this->cartObj->clear();
			$this->cartObj->updateUserCart();

			$this->_template->render(false, false, 'json-success.php');
		}

		Message::addErrorMessage( Label::getLabel('LBL_Invalid_Request') );
		FatUtility::dieWithError( Message::getHtml() );
		//$this->cartObj->clear();
		//$this->cartObj->updateUserCart();
		//$order->updateOrderInfo($order_id, array('order_pmethod_id' => $pmethod_id) );
	}

	private function getPaymentTabForm( $langId = 0 ){
		$frm = new Form('frmPaymentTabForm');
		$frm->setFormTagAttribute('id', 'frmPaymentTabForm');
		$frm->addSubmitButton( '', 'btn_submit', Label::getLabel('LBL_Confirm_Payment', $langId) );
		$frm->addHiddenField( '', 'order_type' );
		$frm->addHiddenField( '', 'order_id' );
		$frm->addHiddenField('','pmethod_id');
		return $frm;
	}

	private function getWalletPaymentForm( ){
		$frm = new Form('frmWalletPayment');
		return $frm;
	}

	private function isEligibleForNextStep( &$criteria = array()){
		if( empty( $criteria ) ) return true;


		foreach( $criteria as $key => $val ) {
            switch( $key ) {
				case 'isUserLogged':
					if( !UserAuthentication::isUserLogged() ){
						$key = false;
						Message::addErrorMessage(Label::getLabel('MSG_Your_Session_seems_to_be_expired.'));
						return false;
					}
				break;
				case 'hasItems':
					if( !$this->cartObj->hasItems() ){
						$key = false;
						Message::addErrorMessage(Label::getLabel('MSG_Teacher_booking_selection_is_not_yet_been_selected,_Please_try_selecting_the_appropriate_teacher_and_start_booking_lesson.'));
						return false;
					}
				break;
			}
		}
		return true;
	}

	private function getConfirmFormWithNoAmount( ){
		$frm = new Form('frmConfirmForm');
		$frm->addFormTagAttribute( 'onSubmit', 'return confirmOrder(this);' );
		return $frm;
	}

    public function getLanguagePackages(){
		$criteria = array( 'isUserLogged' => true, 'hasItems' =>  true );
		if( !$this->isEligibleForNextStep( $criteria ) ){
			if( Message::getErrorCount() ){
				$errMsg = Message::getHtml();
			} else {
				Message::addErrorMessage(Label::getLabel('MSG_Something_went_wrong,_please_try_after_some_time.'));
				$errMsg = Message::getHtml();
			}
			FatUtility::dieWithError( $errMsg );
		}
		$post = FatApp::getPostedData();
		if( false == $post ){
			FatUtility::dieWithError( Label::getLabel('LBL_Invalid_Request') );
		}
		$cartData = $this->cartObj->getCart( $this->siteLangId );
		$srch = LessonPackage::getSearchObject( $this->siteLangId );
		$srch->addCondition( 'lpackage_is_free_trial', '=', 0 );
		$srch->addMultipleFields(array(
			'lpackage_id',
			'IFNULL(lpackage_title, lpackage_identifier) as lpackage_title',
			'lpackage_lessons'
        ));
		$rs = $srch->getResultSet();
		$lessonPackages = FatApp::getDb()->fetchAll($rs);
        $data = UserSetting::getUserSettings( $post['teacher_id'],$post['languageId'] );
        $this->set('cartData',$cartData);
        $this->set('languageId',$post['languageId']);
        $this->set('lessonPackages',$lessonPackages);
        $this->set('selectedLang',current($data));

        $this->_template->render(false,false);
    }
}
