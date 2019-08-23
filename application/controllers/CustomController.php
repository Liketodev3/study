<?php
class CustomController extends MyAppController {
	
	public function paymentFailed(){
		$textMessage = Label::getLabel('MSG_learner_failure_order_{contact-us-page-url}');
		$contactUsPageUrl = CommonHelper::generateUrl('contact');
		$textMessage = str_replace('{contact-us-page-url}', '<a href="'.$contactUsPageUrl.'">'.Label::getLabel('LBL_Contact_Us').'</a>' , $textMessage);
		
		/* if(FatApp::getConfig('CONF_MAINTAIN_WALLET_ON_PAYMENT_FAILURE',FatUtility::VAR_INT,applicationConstants::NO) && isset( $_SESSION['cart_order_id']) &&  $_SESSION['cart_order_id']>0){
			$cartOrderId = $_SESSION['cart_order_id'];
			$orderObj = new Orders();
			$orderDetail = $orderObj->getOrderById($cartOrderId);

			$cartInfo = unserialize( $orderDetail['order_cart_data'] );
			unset($cartInfo['shopping_cart']);

			FatApp::getDb()->deleteRecords('tbl_user_cart', array('smt'=>'`usercart_user_id`=? and `usercarrt_type`=?', 'vals'=>array(UserAuthentication::getLoggedUserId(),CART::TYPE_PRODUCT)));
			$cartObj = new Cart();
			foreach($cartInfo as $key => $quantity){

				$keyDecoded = unserialize( base64_decode($key) );

				$selprod_id = 0;


				if( strpos($keyDecoded, Cart::CART_KEY_PREFIX_PRODUCT ) !== FALSE ){
					$selprod_id = FatUtility::int(str_replace( Cart::CART_KEY_PREFIX_PRODUCT, '', $keyDecoded ));
				}
				$cartObj->add($selprod_id, $quantity);

			}
			$cartObj->updateUserCart();
		} */
		$this->set('textMessage',$textMessage);
		/* if(CommonHelper::isAppUser()){
			$this->set('exculdeMainHeaderDiv', true);
			$this->_template->render(false,false);
		}else{ */
			$this->_template->render();
		/* } */
	}
	
	public function paymentSuccess($orderId=null){
		
		$textMessage = Label::getLabel('MSG_learner_success_order_{dashboard-url}_{contact-us-page-url}');
		
		$arrReplace = array(
			'{dashboard-url}'	=>	CommonHelper::generateUrl('learner'),
			'{contact-us-page-url}'	=>	CommonHelper::generateUrl('custom','contactUs'),
		);
		
		foreach( $arrReplace as $key => $val ){
			$textMessage = str_replace( $key, $val, $textMessage );
		}
		
		/* Clear cart upon successfull redirection from Payment gateway[ */
		/* if( $_SESSION['cart_user_id'] ){
			$userId = (UserAuthentication::isUserLogged()) ? UserAuthentication::getLoggedUserId() : 0;
			$cartObj = new Cart($userId);
			$cartObj->clear();
			$cartObj->updateUserCart();
			unset($_SESSION['cart_user_id']);
		} */
		/* ] */
        if($orderId){
            $orderObj = new Order();
            $order = $orderObj->getOrderById($orderId);
            if(isset($order['order_type'])){
                $this->set('orderType',$order['order_type']);
            }
        }
		$this->set('textMessage',$textMessage);
		/* if(CommonHelper::isAppUser()){
			$this->set('exculdeMainHeaderDiv', true);
			$this->_template->render(false,false);
		}else{ */
			$this->_template->render();
		/* } */
	}

	public function paymentCancel(){
		FatApp::redirectUser(CommonHelper::generateFullUrl('Custom', 'paymentFailed')); 
		/* echo FatApp::getConfig('CONF_MAINTAIN_WALLET_ON_PAYMENT_CANCEL',FatUtility::VAR_INT,applicationConstants::NO);
		echo $_SESSION['cart_order_id'];
		if(FatApp::getConfig('CONF_MAINTAIN_WALLET_ON_PAYMENT_CANCEL',FatUtility::VAR_INT,applicationConstants::NO)&& isset( $_SESSION['cart_order_id']) &&  $_SESSION['cart_order_id']!=''){

			$cartOrderId = $_SESSION['cart_order_id'];
			$orderObj = new Orders();
			$orderDetail = $orderObj->getOrderById($cartOrderId);

			$cartInfo = unserialize( $orderDetail['order_cart_data'] );
			unset($cartInfo['shopping_cart']);

			FatApp::getDb()->deleteRecords('tbl_user_cart', array('smt'=>'`usercart_user_id`=? and `usercarrt_type`=?', 'vals'=>array(UserAuthentication::getLoggedUserId(),CART::TYPE_PRODUCT)));
			$cartObj = new Cart();
			foreach($cartInfo as $key => $quantity){

				$keyDecoded = unserialize( base64_decode($key) );

				$selprod_id = 0;


				if( strpos($keyDecoded, Cart::CART_KEY_PREFIX_PRODUCT ) !== FALSE ){
					$selprod_id = FatUtility::int(str_replace( Cart::CART_KEY_PREFIX_PRODUCT, '', $keyDecoded ));
				}
				$cartObj->add($selprod_id, $quantity);

			}
			$cartObj->updateUserCart();
		}

		FatApp::redirectUser(CommonHelper::generateFullUrl('Checkout')); */
	}

	
}