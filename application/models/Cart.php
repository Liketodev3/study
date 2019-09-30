<?php
class Cart extends FatModel {
	private $cartData = array();
	private $cart_user_id;
	CONST DB_TBL = 'tbl_user_cart';	
	const TYPE_TEACHER_BOOK = 1;
	const TYPE_GIFTCARD = 2;
	
	public function __construct( $user_id = 0 ) {
		parent::__construct();
		$user_id = FatUtility::int($user_id);
		if( $user_id < 1 ){
			$user_id = UserAuthentication::getLoggedUserId();
		}
		
		$this->cart_user_id = $user_id;
		
		$srch = new SearchBase('tbl_user_cart');
		$srch->addCondition('usercart_user_id', '=', $this->cart_user_id );
		$srch->addCondition('usercart_type', '=', CART::TYPE_TEACHER_BOOK );
		$rs = $srch->getResultSet();
		if( $row = FatApp::getDb()->fetch($rs) ){
			$this->SYSTEM_ARR['cart'] = unserialize( $row["usercart_details"] );
			if( isset($this->SYSTEM_ARR['cart']['shopping_cart']) ){
				$this->SYSTEM_ARR['shopping_cart'] = $this->SYSTEM_ARR['cart']['shopping_cart'];
				unset($this->SYSTEM_ARR['cart']['shopping_cart']);
			}
		}

		if ( !isset( $this->SYSTEM_ARR['cart'] ) || !is_array( $this->SYSTEM_ARR['cart'] ) ) {
			$this->SYSTEM_ARR['cart'] = array();
		}
		
		if( !isset($this->SYSTEM_ARR['shopping_cart']) || !is_array($this->SYSTEM_ARR['shopping_cart']) ){
			$this->SYSTEM_ARR['shopping_cart'] = array();
		}
	}
	
	public function add( $teacher_id, $lpackageId, $languageId , $startDateTime = '', $endDateTime = ''  ){
		$this->SYSTEM_ARR['cart'] = array();
		$teacher_id = FatUtility::int( $teacher_id );
		$lpackageId = FatUtility::int( $lpackageId );
		$languageId = FatUtility::int( $languageId );
		
		if( $teacher_id < 1 || $lpackageId < 1 ){
			$this->error = Label::getLabel('LBL_Invalid_Request');
			return false;
		}
		
		/* validate teacher[ */
		$srch = new UserSearch();
		$srch->setTeacherDefinedCriteria();
		$srch->addFld( 'us_is_trial_lesson_enabled' );
		$srch->addCondition( 'user_id', '=', $teacher_id );
		$srch->setPageSize(1);
		$rs = $srch->getResultSet();
		$userRow = FatApp::getDb()->fetch( $rs );
		if( !$userRow ){
			$this->error = Label::getLabel( 'LBL_Teacher_not_found' );
			return false;
		}
		
        $freePackages = LessonPackage::getFreeTrialPackage();
        if($freePackages AND $freePackages['lpackage_id'] == $lpackageId){
            if( 1 != $userRow['us_is_trial_lesson_enabled'] ){
                $this->error = Label::getLabel( 'MSG_Trial_Lessons_are_disabled_by_teacher' );
                return false;
            }
        }
		/* ] */
		
		/* validate that free trial package cannot be added again per teacher[ */
		$lPackageRow = LessonPackage::getAttributesById( $lpackageId, array( 'lpackage_id', 'lpackage_is_free_trial' ) );
		if( $lpackageId !== $lPackageRow['lpackage_id'] ){
			$this->error = Label::getLabel('LBL_Invalid_Request');
			return false;
		}
		
		if( 1 === $lPackageRow['lpackage_is_free_trial'] ){
			
			/* validate if teacher has enabled free trial or not[ */
			
			/* ] */
			
			if( LessonPackage::isAlreadyPurchasedFreeTrial( $this->cart_user_id, $teacher_id ) ){
				$this->error = Label::getLabel( 'LBL_You_already_purchased_free_trial_for_this_teacher' );
				return false;
			}
			if( $startDateTime == '' || $endDateTime == '' ){
				$this->error = Label::getLabel( 'LBL_Lesson_Schedule_time_is_required' );
				return false;
			}
		}
		/* ] */
		
		$key = $teacher_id;
		$key = base64_encode(serialize($key));
		$this->SYSTEM_ARR['cart'][$key] = array(
			'teacher_id'	=>	$teacher_id,
			'startDateTime'	=>	$startDateTime,
			'endDateTime'	=>	$endDateTime,
			'lpackageId'	=>	$lpackageId,
			'languageId'	=>	$languageId,
		);
		
		$this->updateUserCart();
		return true;
	}
	public function cartData($langId){
			$key = key($this->SYSTEM_ARR['cart']);
			$cartData = $this->SYSTEM_ARR['cart'][$key];
			$languageId = $cartData['languageId'];			
			$keyDecoded = unserialize( base64_decode($key) );
			$teacher_id = $keyDecoded;
			
			$teacherSrch = new UserSearch();
			$teacherSrch->setTeacherDefinedCriteria(false);
			$teacherSrch->joinUserCountry( $langId );
			$teacherSrch->joinUserState( $langId );
			$teacherSrch->addCondition( 'user_id', '=', $teacher_id );
			//$teacherSrch->addCondition( 'utl_slanguage_id', '=', 1 );
			$teacherSrch->setPageSize(1);
            $teacherSrch->joinTable( "tbl_user_teach_languages", 'INNER JOIN', 'utl_us_user_id = '.$teacher_id.' AND utl_slanguage_id = '.$languageId  , 'utl' );                			
			/* find, if have added any offer price is locked with this teacher[ */
			$teacherSrch->joinTable( TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top_teacher_id = user_id AND top_learner_id = '.$this->cart_user_id, 'top' );
			/* ] */
			
			$teacherSrch->addMultipleFields( array(
				'user_id', 
				'user_first_name', 
				'user_last_name',
				'user_country_id',
				'us_single_lesson_amount',
				'us_teach_slanguage_id',
				'us_bulk_lesson_amount',
				'top_single_lesson_price',
				'top_bulk_lesson_price',
                'utl.*'

			) );
			
			if( $langId > 0 ){
				$teacherSrch->addMultipleFields( array(
					'IFNULL(country_name, country_code) as user_country_name',
					'IFNULL(state_name, state_identifier) as user_state_name'
					) );
			}
            //echo $teacherSrch->getQuery(); die;
			$rs = $teacherSrch->getResultSet();
			$teacher = FatApp::getDb()->fetch( $rs );
            //print_r($teacher); die;
			if( !$teacher ){
				$this->removeCartKey( $key );
			}
			
			$lPackageId = $cartData['lpackageId'];

			$srch = LessonPackage::getSearchObject( $langId );
			$srch->addCondition( 'lpackage_id', '=', $lPackageId );
			$srch->addMultipleFields( array('lpackage_id', 'lpackage_lessons', 'lpackage_is_free_trial', 'lpackage_identifier as lpackage_title') );
			
			if( $langId > 0 ){
				$srch->addMultipleFields( array('IFNULL(lpackage_title, lpackage_identifier) as lpackage_title') );
			}
			
			$rs = $srch->getResultSet();
			$lessonPackageRow = FatApp::getDb()->fetch( $rs );
			
			if( $lessonPackageRow['lpackage_is_free_trial'] == 1 ){
				$itemPrice = 0;
			} else {
				
				if( !empty($teacher['top_single_lesson_price']) && !empty( $teacher['top_bulk_lesson_price'] ) ){
					$teacher['utl_bulk_lesson_amount'] = $teacher['top_bulk_lesson_price'];
					$teacher['utl_single_lesson_amount'] = $teacher['top_single_lesson_price'];
				}
				
				$itemPrice = (($lessonPackageRow['lpackage_lessons'] > 1) ? $teacher['utl_bulk_lesson_amount'] : $teacher['utl_single_lesson_amount']);
			}
			
			$totalPrice = $itemPrice * $lessonPackageRow['lpackage_lessons'];
			
			$this->cartData = $teacher;
			$this->cartData['key'] = $key;
			$this->cartData['lpackage_id'] = $lessonPackageRow['lpackage_id'];
			$this->cartData['languageId'] = $languageId;
			$this->cartData['lpackage_is_free_trial'] = $lessonPackageRow['lpackage_is_free_trial'];
			$this->cartData['lpackage_lessons'] = $lessonPackageRow['lpackage_lessons'] * 1;
			$this->cartData['startDateTime'] = $cartData['startDateTime'];
			$this->cartData['endDateTime'] = $cartData['endDateTime'];
			$this->cartData['itemName'] = $lessonPackageRow['lpackage_title'];
			$this->cartData['itemPrice'] = $itemPrice;
			$this->cartData['total'] = $totalPrice;
            //print_r($this->cartData); die;
			return $this->cartData;
	}
	public function getCart( $langId = 0 ){
		$langId = FatUtility::int( $langId );
		if( !$this->cartData ){			
			/* cart Summary[ */
			$this->cartData = $this->cartData($langId);
			$userWalletBalance = User::getUserBalance( $this->cart_user_id );
			$cartTotal = $this->cartData['total'];
			$cartTaxTotal = 0;
			$cartDiscounts = self::getCouponDiscounts($langId);
			//$cartDiscounts = 0;
			$totalSiteCommission = 0;
			$totalDiscountAmount = (isset($cartDiscounts['coupon_discount_total'])) ? $cartDiscounts['coupon_discount_total'] : 0;
			$orderNetAmount = ( $cartTotal + $cartTaxTotal )  - $totalDiscountAmount;
			$walletAmountCharge = ( $this->isCartUserWalletSelected() ) ? min( $orderNetAmount, $userWalletBalance ) : 0;
			$orderPaymentGatewayCharges = $orderNetAmount - $walletAmountCharge;
			
			$summaryArr = array(
				'cartTotal'			=>	$cartTotal,
				'cartTaxTotal'		=>	$cartTaxTotal,
				'cartDiscounts'		=>	$cartDiscounts,
				'cartWalletSelected'=>	$this->isCartUserWalletSelected(),
				'siteCommission' 	=>	$totalSiteCommission,
				'orderNetAmount'	=>	$orderNetAmount,
				'walletAmountCharge'=>	$walletAmountCharge,
				'orderPaymentGatewayCharges' => $orderPaymentGatewayCharges,
			);
			
			$this->cartData = $this->cartData + $summaryArr;
			/* ] */
			
		}
		return $this->cartData;
	}
	
	public function updateCartWalletOption( $val ) {
		$this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet'] = $val;
		$this->updateUserCart();
		return true;
	}
	
	public function isCartUserWalletSelected() {
		return (isset($this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet']) && intval($this->SYSTEM_ARR['shopping_cart']['Pay_from_wallet'])==1) ? 1 : 0;
	}
	
	public function removeCartKey($key) {
		unset($this->cartData[$key]);
		unset($this->SYSTEM_ARR['cart'][$key]);
		$this->updateUserCart();
		return true;
	}
	
	public function updateUserCart() {
		if (isset($this->cart_user_id)) {
			$record = new TableRecord('tbl_user_cart');
			$cart_arr = $this->SYSTEM_ARR['cart'];
			if (isset($this->SYSTEM_ARR['shopping_cart']) && is_array($this->SYSTEM_ARR['shopping_cart']) && (!empty($this->SYSTEM_ARR['shopping_cart']))){
				$cart_arr["shopping_cart"] = $this->SYSTEM_ARR['shopping_cart'];
			}
			$cart_arr = serialize($cart_arr);
			$record->assignValues( 
				array(
					"usercart_user_id" => $this->cart_user_id,
					"usercart_type" =>CART::TYPE_TEACHER_BOOK, 
					"usercart_details" => $cart_arr, 
					"usercart_added_date" => date ( 'Y-m-d H:i:s' ) 
				) 
			);
			if( !$record->addNew( array(), array( 'usercart_details' => $cart_arr, "usercart_added_date" => date ( 'Y-m-d H:i:s' )) ) ){
				Message::addErrorMessage( $record->getError() );
				throw new Exception('');
			}
		}
	}
	
	public function getCartUserId(){
		return $this->cart_user_id;
	}
	
	public function hasItems() {
		return count($this->SYSTEM_ARR['cart']);
	}
	
	public function clear() {
		$this->cartData = array();
		$this->SYSTEM_ARR['cart'] = array();
		$this->SYSTEM_ARR['shopping_cart'] = array();
		unset($_SESSION['shopping_cart']["order_id"]);
		//unset($_SESSION['wallet_recharge_cart']["order_id"]);
	}
	
	public function updateCartDiscountCoupon($val) {
		$this->SYSTEM_ARR['shopping_cart']['discount_coupon'] = $val;
		$this->updateUserCart(); 
		return true;
	}
	
	public function removeUsedRewardPoints(){
		if(isset($this->SYSTEM_ARR['shopping_cart']) && array_key_exists('reward_points',$this->SYSTEM_ARR['shopping_cart'])){
			unset($this->SYSTEM_ARR['shopping_cart']['reward_points']);
			$this->updateUserCart();
		}
		return true;
	}	
	
	public function getCouponDiscounts($langId = 0){ 
		$couponObj = new DiscountCoupons();
		if( !self::getCartDiscountCoupon() ){ return false; }
		//echo $langId; die;
		$couponInfo = $couponObj->getValidCoupons( $this->cart_user_id, $langId, self::getCartDiscountCoupon() );
		//$couponInfo = $couponObj->getCoupon( self::getCartDiscountCoupon(), $this->cart_lang_id );
		//CommonHelper::printArray($couponInfo); die();
		$cartSubTotal = self::getSubTotal($langId);
		
		/* $var = false;
		if(count($var>0)){
			echo 'its false';
		} else { echo 'not false'; }
		die(); */
		$couponData = array();
		if( $couponInfo ){
			$discountTotal = 0;
			//$cartProducts = $this->getProducts( $this->cart_lang_id );
			
			if ( $couponInfo['coupon_discount_in_percent'] == applicationConstants::FLAT ) {
				$couponInfo['coupon_discount_value'] = min($couponInfo['coupon_discount_value'], $cartSubTotal);
			}
			
			
			
			if ($discountTotal > $couponInfo['coupon_max_discount_value'] && $couponInfo['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) {
				$discountTotal = $couponInfo['coupon_max_discount_value'];
			}
			/*]*/
			
			$labelArr = array(
				'coupon_label'=>$couponInfo["coupon_title"],
				'coupon_id'=>$couponInfo["coupon_id"],
				'coupon_discount_in_percent'=>$couponInfo["coupon_discount_in_percent"],
				'max_discount_value' =>$couponInfo["coupon_max_discount_value"] 
			);
			
			if ( $couponInfo['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE ) {
				/*if ( $cartSubTotal > $couponInfo['coupon_max_discount_value'] ) {
					$cartSubTotal = $couponInfo['coupon_max_discount_value'];
				}*/
				$cartSubTotal = $cartSubTotal * $couponInfo['coupon_discount_value'] / 100 ;
			} else if( $couponInfo['coupon_discount_in_percent'] == applicationConstants::FLAT ) {
				if ( $cartSubTotal > $couponInfo["coupon_discount_value"] ) {
				$cartSubTotal = $couponInfo["coupon_discount_value"];
				}
			}
			
			$couponData = array(
				'coupon_discount_type'       => $couponInfo["coupon_type"],
				'coupon_code' => $couponInfo["coupon_code"],
				'coupon_discount_value'      =>$couponInfo["coupon_discount_value"],
				'coupon_discount_total'      =>$cartSubTotal,
				'coupon_info'      => json_encode($labelArr),				
			);
			
		}
		
		if(empty($couponData)){ return false;} 
		return $couponData;
	}	

	public function getSubTotal($langId) {
		if( !$this->cartData ){
			return 0;			
		}
		$cartTotal = $this->cartData($langId);
		return $cartTotal['total'];
	}	
	
	public function getCartDiscountCoupon() {
		return isset($this->SYSTEM_ARR['shopping_cart']['discount_coupon']) ? $this->SYSTEM_ARR['shopping_cart']['discount_coupon'] : '';
	}	

	public function removeCartDiscountCoupon() {
		$couponCode = array_key_exists('discount_coupon', $this->SYSTEM_ARR['shopping_cart']) ? $this->SYSTEM_ARR['shopping_cart']['discount_coupon'] : '';
		unset($this->SYSTEM_ARR['shopping_cart']['discount_coupon']);
		
		/* Removing from temp hold[ */
		if( $couponCode != '' ){
			$loggedUserId = $this->cart_user_id;
			
			$srch = DiscountCoupons::getSearchObject(0, false, false);
			$srch->addCondition( 'coupon_code', '=', $couponCode );
			$srch->setPageSize(1);
			$srch->addMultipleFields( array('coupon_id') );
			$rs = $srch->getResultSet();
			$couponRow = FatApp::getDb()->fetch( $rs );
			
			if( $couponRow && $loggedUserId ){
				FatApp::getDb()->deleteRecords( DiscountCoupons::DB_TBL_COUPON_HOLD, array( 'smt' => 'couponhold_coupon_id = ? AND couponhold_user_id = ?', 'vals'=> array( $couponRow['coupon_id'], $loggedUserId ) ) );
			}
		}
		
		$orderId = isset($_SESSION['order_id'])?$_SESSION['order_id']:'';
		if($orderId != ''){
			FatApp::getDb()->deleteRecords( DiscountCoupons::DB_TBL_COUPON_HOLD_PENDING_ORDER, array( 'smt' => 'ochold_order_id = ?', 'vals'=> array( $orderId ) ) );
		}
		
		/* ] */
		
		$this->updateUserCart();
		return true;
	}	
	
}