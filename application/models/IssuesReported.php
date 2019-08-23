<?php
class IssuesReported extends MyAppModel{
	const DB_TBL = 'tbl_issues_reported';
	const DB_TBL_PREFIX = 'issrep_';
	
	const STATUS_OPEN = 0;
	const STATUS_PROGRESS = 1;
	const STATUS_RESOLVED = 2;
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}

	public static function getSearchObject( ) {
		$srch = new SearchBase(static::DB_TBL, 'i');
		$srch->joinTable( ScheduledLesson::DB_TBL, 'INNER JOIN', 'i.issrep_slesson_id = sl.slesson_id', 'sl' );
		$srch->joinTable( Order::DB_TBL, 'INNER JOIN', 'o.order_id = sl.slesson_order_id', 'o' );
		$srch->joinTable( User::DB_TBL, 'INNER JOIN', 'CASE WHEN i.issrep_reported_by = '.USER::USER_TYPE_LEANER.' THEN sl.slesson_learner_id  ELSE sl.slesson_teacher_id END = u.user_id', 'u' );
		return $srch;
	}

	public function getIssueDetails(){
		$srch = static::getSearchObject();
		$srch->addCondition('issrep_id', '=', $this->mainTableRecordId);		
		return $srch;
	}
	
	public static function getStatusArr( $langId = 0, $filter = false ){
		$langId = FatUtility::int($langId);
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();
		}
		if(!$filter){
			return array(
				static::STATUS_OPEN	=>	Label::getLabel('LBL_Open', $langId),	
				static::STATUS_PROGRESS	=>	Label::getLabel('LBL_In_Progress', $langId),
				static::STATUS_RESOLVED	=>	Label::getLabel('LBL_Resolved', $langId),			
			);
		}
		else{
			return array(
				static::STATUS_RESOLVED	=>	Label::getLabel('LBL_Resolved', $langId),			
			);			
		}
	}	
	
    static function getIssueStatus($issueId) {
		$status = IssuesReported::getAttributesById($issueId,array('issrep_status'));
		return $status['issrep_status'];
    }
	
	public static function getCallHistory($uid){
		
		$data_string = array('UID'=>$uid);
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,'https://api.cometondemand.net/api/v2/getCallHistory');
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
			"api-key: ".FatApp::getConfig('CONF_COMET_CHAT_API_KEY')
		));
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$json = curl_exec($curl_handle);
		curl_close($curl_handle);
		$callHistory = json_decode($json);
		if(isset($callHistory->success->data)){
			return $callHistory->success->data;
		}else{
			return array();
		}
	}
	
	public static function getIssueStatusByLessonId($lessonId,$reportedBy){
		$srch = static::getSearchObject();
		$srch->addCondition('issrep_slesson_id', '=', $lessonId);		
		$srch->addCondition('issrep_reported_by', '=', $reportedBy);		
		return $srch;		
	}
	
	public static function isAlreadyReported($lessonId,$userType){
		$srch = new SearchBase(static::DB_TBL);
		$srch->addCondition( 'issrep_slesson_id',' = ', $lessonId );
		$srch->addCondition( 'issrep_reported_by',' = ', $userType );
		$rs = $srch->getResultSet();
		$issueRow = FatApp::getDb()->fetch($rs);
		if( $issueRow ){
			return true;
		}		
		return false;
	}
	
}