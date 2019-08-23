<?php
class TeacherRequest extends MyAppModel{
	
	const DB_TBL = 'tbl_user_teacher_requests';
	const DB_TBL_PREFIX = 'utrequest_';
	
	const STATUS_PENDING = 0;
	const STATUS_APPROVED = 1;
	const STATUS_CANCELLED = 2;
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );		
	}
	
	public static function getStatusArr( $langId = 0 ){
		$langId = FatUtility::int( $langId );
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();
		}
		
		return array(
			static::STATUS_PENDING => Label::getLabel('LBL_Pending', $langId),
			static::STATUS_APPROVED => Label::getLabel('LBL_Approved', $langId),
			static::STATUS_CANCELLED => Label::getLabel('LBL_Cancelled', $langId),
		);
	}
	
	public static function isMadeMaximumAttempts( $userId ){
		
		$userId = FatUtility::int( $userId );
		if( $userId < 1 ){
			trigger_error( "User Id is not passed", E_USER_ERROR );
		}
		$srch = new TeacherRequestSearch();
		$srch->addCondition( 'utrequest_user_id', '=', $userId );
		$srch->addMultiplefields( array( 'utrequest_id', 'utrequest_attempts') );
		$rs = $srch->getResultSet();
		$row = FatApp::getDb()->fetch( $rs );
		
		$maxAttempts = FatApp::getConfig('CONF_MAX_TEACHER_REQUEST_ATTEMPT',FatUtility::VAR_INT,3);
		if( $row && $row['utrequest_attempts'] >= $maxAttempts ){
			return true;
		}
		
		return false;
	}
	
	public static function getData( $userId ){
		$userId = FatUtility::int( $userId );
		if( $userId < 1 ){
			trigger_error( "User Id is not passed", E_USER_ERROR );
		}
		$srch = new TeacherRequestSearch();
		$srch->addCondition( 'utrequest_user_id', '=', $userId );
		$srch->addMultiplefields( array( 'utrequest_attempts', 'utrequest_id', 'utrequest_status') );
		$rs = $srch->getResultSet();
		if( $row = FatApp::getDb()->fetch($rs) ){
			return $row;
		}
		return false;
	}
	
	public function saveData( $post, $userId ){
		$userId = FatUtility::int( $userId );
		if( $userId < 1 ){
			$this->error = Label::getLabel( 'LBL_Invalid_Request' );
			return false;
		}
		
		/* save teacher approval request[ */
		$data = array(
			'utrequest_user_id'	=>	$userId,
			'utrequest_reference'	=>	$userId.'-'.time(),
			'utrequest_date'		=>	date('Y-m-d H:i:s'),
			'utrequest_status'	=>	0,
		);
		
		$this->assignValues( $data );
		$this->setFldValue('utrequest_attempts', 1, true);
		if( true !== $this->addNew( array(), array('utrequest_attempts' => 'mysql_func_utrequest_attempts+1') ) ){
			return false;
		}
		$utrequest_id = $this->getMainTableRecordId();
		/* ] */
		
		/* save teacher approval request values[ */
		FatApp::getDb()->deleteRecords( TeacherRequestValue::DB_TBL, array( 'smt' => 'utrvalue_utrequest_id = ?', 'vals' => array( $utrequest_id ) ) );
		
		$requestValues = $post;
		
		$requestValues['utrvalue_utrequest_id'] = $utrequest_id;
		$requestValues['utrvalue_user_teach_slanguage_id'] = json_encode( $post['utrvalue_user_teach_slanguage_id'] );
		$requestValues['utrvalue_user_language_speak'] = json_encode( $post['utrvalue_user_language_speak'] );
		
		$requestValues['utrvalue_user_language_speak_proficiency'] = json_encode( $post['utrvalue_user_language_speak_proficiency'] );
		
		$teacherRequestValue = new TeacherRequestValue();
		$teacherRequestValue->assignValues( $requestValues );
		if( true !== $teacherRequestValue->save() ){
			$this->error = $teacherRequestValue->getError();
			return false;
		}
		/* ] */
		return true;
	}
}