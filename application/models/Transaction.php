<?php
class Transaction extends MyAppModel{
	const DB_TBL = 'tbl_user_transactions';
	const DB_TBL_PREFIX = 'utxn_';
	
	const STATUS_PENDING = 0;
	const STATUS_COMPLETED = 1;

	const WITHDRAWL_STATUS_PENDING = 0;	
	const WITHDRAWL_STATUS_COMPLETED = 1;	
	const WITHDRAWL_STATUS_APPROVED = 2;	
	const WITHDRAWL_STATUS_DECLINED = 3;	
	
	const TYPE_LESSON_BOOKING = 1;
	const TYPE_GIFTCARD_REDEEM_TO_WALLET = 2;	
	const TYPE_LOADED_MONEY_TO_WALLET = 3;	
	const TYPE_MONEY_WITHDRAWN = 4;
	const TYPE_ISSUE_REFUND = 5;
	
	const CREDIT_TYPE = 1;	
	const DEBIT_TYPE = 2;		

	protected $userId;
	
	public function __construct($uid,$utxnId = 0) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $utxnId );
		$uid = FatUtility::int($uid);		
		$this->userId = $uid;
	}
	
	public static function getSearchObject( $doNotCalculateRecords = true, $doNotLimitRecords = true ) {
		$srch = new SearchBase(static::DB_TBL, 'utxn');
		if( true === $doNotCalculateRecords ){
			$srch->doNotCalculateRecords();
		}
		if( true === $doNotLimitRecords ){
			$srch->doNotLimitRecords();
		}
		return $srch;
	}
	
	public static function getStatusArr( $langId = 0 ){
		$langId = FatUtility::int($langId);
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();	
		}
		return array(
			static::STATUS_PENDING => Label::getLabel('LBL_Pending',$langId),
			static::STATUS_COMPLETED => Label::getLabel('LBL_Completed',$langId)					
		);
	}

	public static function getWithdrawlStatusArr($langId){
		$langId = FatUtility::int($langId);
		if($langId == 0){ 
			trigger_error(Label::getLabel('MSG_Language_Id_not_specified.',$this->commonLangId), E_USER_ERROR);				
		}
		$arr=array(
			static::WITHDRAWL_STATUS_PENDING => Label::getLabel('LBL_Withdrawal_Request_Pending',$langId),
			static::WITHDRAWL_STATUS_COMPLETED => Label::getLabel('LBL_Withdrawal_Request_Completed',$langId),						
			static::WITHDRAWL_STATUS_APPROVED => Label::getLabel('LBL_Withdrawal_Request_Approved',$langId),						
			static::WITHDRAWL_STATUS_DECLINED => Label::getLabel('LBL_Withdrawal_Request_Declined',$langId)						
		);
		return $arr;
	}	
	
	public function save(){
		$this->setFldValue( 'utxn_date', date('Y-m-d H:i:s') );
		return parent::save();
	}
	
	public static function formatTransactionCommentByOrderId( $orderId, $langId = 0 ){
		$formattedOrderValue = " #".$orderId;
		$langId = FatUtility::int( $langId );
		
		/* $srch = new OrderSearch();
		$srch->addCondition('order_id','=',$orderId);
		$srch->addMultipleFields( array('order_id') );
		$rs = $srch->getResultSet();
		$orderInfo = FatApp::getDb()->fetch($rs); */
		
		//CommonHelper::printArray($orderInfo); die;
		$str = Label::getLabel( 'LBL_ORDER_PLACED_{order-id}', $langId );
		return str_replace( '{order-id}', $formattedOrderValue, $str );
	}

	public static function getCreditDebitTypeArr($langId){
		$langId = FatUtility::int($langId);
		if($langId == 0){ 
			trigger_error(Label::getLabel('MSG_Language_Id_not_specified.',$this->commonLangId), E_USER_ERROR);				
		}
		
		$arr=array(
			static::CREDIT_TYPE => Label::getLabel('LBL_Credit',$langId),
			static::DEBIT_TYPE => Label::getLabel('LBL_Debit',$langId)					
		);
		return $arr;
	}	
	
	public function addTransaction($data){
		if($this->userId < 1){
			trigger_error(Label::getLabel('MSG_INVALID_REQUEST',$this->commonLangId),E_USER_ERROR) ;
			return false;
		}
		$data['utxn_date'] = date('Y-m-d H:i:s');
		$this->assignValues($data);
		if (!$this->save()) {
			return false;		
		} 
		return $this->getMainTableRecordId();
	}	
	
	public function getTransactionSummary(){
		$srch = static::getSearchObject();		
		if($this->userId > 0){
			$srch->addCondition('utxn.utxn_user_id','=',$this->userId);
		}
		if($this->mainTableRecordId > 0){
			$srch->addCondition('utxn.utxn_id','=',$mainTableRecordId);
		}
		
		$srch->addMultipleFields(array('IFNULL(SUM(utxn.utxn_credit),0) AS total_earned','IFNULL(SUM(utxn.utxn_debit),0) AS total_used'));
		$srch->doNotCalculateRecords();
		$srch->doNotlimitRecords();
		$srch->addCondition('utxn_status', '=', applicationConstants::ACTIVE);
		$rs = $srch->getResultSet();
		if(!$rs){
            trigger_error($srch->getError(), E_USER_ERROR);
		}		
		return $row = FatApp::getDb()->fetch($rs);
	}

	public function getAttributesWithUserInfo($userId = 0,$attr = null){
		$userId = FatUtility::int($userId);
		$srch = static::getSearchObject();
		$srch->joinTable(User::DB_TBL,'LEFT OUTER JOIN','u.user_id = utxn.utxn_user_id','u');
		$srch->joinTable(User::DB_TBL_CRED,'LEFT OUTER JOIN','c.credential_user_id = u.user_id','c');
		
		if ( null != $attr ) {
			if (is_array($attr)) {
				$srch->addMultipleFields($attr);
			}elseif (is_string($attr)) {
				$srch->addFld($attr);
			}
		}
		
		if( $this->mainTableRecordId > 0 ){
			$srch->addCondition('utxn.utxn_id','=',$this->mainTableRecordId);
		}
		
		if( $userId > 0 ){
			$srch->addCondition('utxn.utxn_user_id','=',$userId);
		}
		
		$rs = $srch->getResultSet();
		
		if( $this->mainTableRecordId > 0 ){		
			$row = FatApp::getDb()->fetch($rs);
		}else{
			$row = FatApp::getDb()->fetchAll($rs,'utxn_id');
		}
		
		if(!empty($row)){
			return $row;
		}
		
		return array();
	}

	static function formatTransactionNumber($txnId){
		$newValue = str_pad($txnId,7,'0',STR_PAD_LEFT);
		$newValue = "TN"."-".$newValue;
		return $newValue;
	}
	
	static function formatTransactionComments($txnComments){
		$strComments = $txnComments;
		$strComments = preg_replace('/<\/?a[^>]*>/','',$strComments);
		return $strComments;
	}	

	public function getAttributesBywithdrawlId($withdrawalId,$attr = null){
		$withdrawalId = FatUtility::int($withdrawalId);
		if(1 > $withdrawalId){
			trigger_error(Label::getLabel('MSG_INVALID_REQUEST',$this->commonLangId),E_USER_ERROR) ;
			return false;
		}
		
		$srch = static::getSearchObject();
		if ( null != $attr ) {
			if (is_array($attr)) {
				$srch->addMultipleFields($attr);
			}elseif (is_string($attr)) {
				$srch->addFld($attr);
			}
		}
		
		$srch->addCondition('utxn.utxn_withdrawal_id','=',$withdrawalId);
		
		$rs = $srch->getResultSet();
		$row = FatApp::getDb()->fetch($rs);
	
		if(!empty($row)){
			return $row;
		}
		
		return false;
	}    
	
}