<?php
class ScheduledLesson extends MyAppModel{
	const DB_TBL = 'tbl_scheduled_lessons';
	const DB_TBL_PREFIX = 'slesson_';
	
	const STATUS_SCHEDULED = 1;
	const STATUS_NEED_SCHEDULING = 2;
	const STATUS_COMPLETED = 3;
	const STATUS_CANCELLED = 4;
	const STATUS_UPCOMING = 6;
    const STATUS_ISSUE_REPORTED = 7;
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}

	public static function getStatusArr( $langId = 0 ){
		$langId = FatUtility::int( $langId );
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();
		}
		return array(
			static::STATUS_SCHEDULED	=>	Label::getLabel('LBL_Scheduled', $langId),
			static::STATUS_NEED_SCHEDULING	=>	Label::getLabel('LBL_Need_to_be_scheduled', $langId),
			static::STATUS_COMPLETED	=>	Label::getLabel('LBL_Completed', $langId),
			static::STATUS_CANCELLED	=>	Label::getLabel('LBL_Cancelled', $langId),
			static::STATUS_UPCOMING	=>	Label::getLabel('LBL_Upcoming', $langId),
			static::STATUS_ISSUE_REPORTED	=>	Label::getLabel('LBL_Issue_Reported', $langId)
		);
	}
	
	/* public static function getLessonsDetailByUserid($userId,$isTeacher = 1,$isLearner = 1){
		$lessonId = FatUtility::int($userId);
		$srch = new ScheduledLessonSearch(false);
		$srch->addMultipleFields(array(
		'count(slns.slesson_id) as totalLessons',
		'COALESCE(SUM(CASE WHEN slns.slesson_status = '.self::STATUS_SCHEDULED.' THEN 1 ELSE 0 END),0) AS scheduledLessons',
		));		
		if($isTeacher == 1)
		{		
			$srch->addCondition( 'slns.slesson_teacher_id',' = ',$userId);
            $srch->addMultipleFields(array('GROUP_CONCAT(slesson_learner_id) as StudentIds'));			
		}
		if($isLearner == 1)
		{		
			$srch->addCondition( 'slns.slesson_learner_id',' = ',$userId);
		}
		$rs = $srch->getResultSet();
		$data = FatApp::getDb()->fetch($rs);	
		return $data;
	} */


	public function save(){
		if( $this->getMainTableRecordId() == 0 ){
			$this->setFldValue('slesson_added_on', date('Y-m-d H:i:s') );
		}

		return parent::save();
	}

	public function payTeacherCommission(){
		$srch = new ScheduledLessonSearch();
		$srch->joinOrder();
		$srch->joinOrderProducts();
		$srch->addCondition( 'slns.slesson_id',' = ',$this->getMainTableRecordId());
		$srch->addCondition( 'slns.slesson_is_teacher_paid',' = ',0);
		$srch->addCondition( 'op.op_lpackage_is_free_trial',' = ',0);                
		$rs = $srch->getResultSet();
		$data = FatApp::getDb()->fetch($rs);
		if($data){
			$tObj = new Transaction($data['slesson_teacher_id']);
			$data = array(
				'utxn_user_id' => $data['slesson_teacher_id'],
				'utxn_date' => date('Y-m-d H:i:s'),
				'utxn_comments' => sprintf(Label::getLabel('LBL_LessonId:_%s_Payment', CommonHelper::getLangId()), $this->getMainTableRecordId()),
				'utxn_status' => Transaction::STATUS_COMPLETED,
				'utxn_type' => Transaction::TYPE_LOADED_MONEY_TO_WALLET,
				'utxn_credit' => $data['op_commission_charged'],
				'utxn_slesson_id' => $data['slesson_id'],
			);

			if (!$tObj->addTransaction($data)) {
					trigger_error($tObj->getError(), E_USER_ERROR);
			}
			return true;
		}
		else{
			return false;
		}
	}
    
    public function refundToLearner($learner=false){
		$srch = new ScheduledLessonSearch();
		$srch->joinOrder();
		$srch->joinOrderProducts();
		$srch->addCondition( 'slns.slesson_id',' = ',$this->getMainTableRecordId());
		$srch->addCondition( 'slns.slesson_is_teacher_paid',' = ',0);
		$srch->addCondition( 'op.op_lpackage_is_free_trial',' = ',0);        
		$rs = $srch->getResultSet();
		$data = FatApp::getDb()->fetch($rs);
		if($data){
            $to_time = strtotime($data['slesson_date'].' '.$data['slesson_start_time']);
            $from_time = strtotime(date('Y-m-d H:i:s'));
            $diff = round(($to_time - $from_time) / 3600,2);

            if($learner AND $diff<24){
                $data['op_unit_price'] = ( FatApp::getConfig('CONF_LEARNER_REFUND_PERCENTAGE', FatUtility::VAR_INT, 10) * $data['op_unit_price'] ) / 100;
            }
			$tObj = new Transaction($data['slesson_learner_id']);
			$data = array(
				'utxn_user_id' => $data['slesson_learner_id'],
				'utxn_date' => date('Y-m-d H:i:s'),
				'utxn_comments' => sprintf(Label::getLabel('LBL_LessonId:_%s_Refund_Payment', CommonHelper::getLangId()), $this->getMainTableRecordId()),
				'utxn_status' => Transaction::STATUS_COMPLETED,
				'utxn_type' => Transaction::TYPE_LOADED_MONEY_TO_WALLET,
				'utxn_credit' => $data['op_unit_price']
			);

			if (!$tObj->addTransaction($data)) {
					trigger_error($tObj->getError(), E_USER_ERROR);
			}
			return true;
		}
		else{
			return true;
		}
    }
	
	public function holdPayment( $user_id, $lesson_id ) {
		$db = FatApp::getDb();
		if( !$db->updateFromArray(Transaction::DB_TBL,array('utxn_status'=>Transaction::STATUS_PENDING), array('smt'=>'utxn_user_id = ? and utxn_slesson_id = ?','vals'=>array( $user_id, $lesson_id ))) ) {
			return false;
		}
		return true;
	}
	
}