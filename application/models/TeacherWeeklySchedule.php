<?php
class TeacherWeeklySchedule extends MyAppModel{
	const DB_TBL = 'tbl_teachers_weekly_schedule';
	const DB_TBL_PREFIX = 'twsch_';	
	const UNAVAILABLE = 0;
	const AVAILABLE = 1;
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
		$this->db = FatApp::getDb();
	}
	
	public static function getWeeklySchCssClsNameArr( ){
		return array(
			static::UNAVAILABLE	=>	'slot_unavailable',	
			static::AVAILABLE	=>	'slot_available',
		);
	}
	
	public static function getWeeklyScheduleJsonArr($userId,$start,$end){
		$userId = FatUtility::int($userId);
		if( $userId < 1 ){
			trigger_error(Label::getLabel('LBL_Invalid_Request'), E_USER_ERROR);
		}		
		
		$srch = new TeacherWeeklyScheduleSearch();
		$srch->addMultipleFields(
			array(
				'twsch_id',
				'twsch_user_id',
				'twsch_date',
				'twsch_start_time',
				'twsch_end_time',
				'twsch_is_available'
			));
		$srch->addCondition( 'twsch_user_id',' = ', $userId );
		
		$date = $end;
		$newdate = strtotime ( '-1 day' , strtotime ( $date ) ) ;
		$newEndDate = date ( 'Y-m-d' , $newdate );

		$srch->addCondition( 'twsch_date','between',array($start,$newEndDate ));
		$srch->addCondition('twsch_date','>=',date('Y-m-d'));
		$rs = $srch->getResultSet();
		return FatApp::getDb()->fetchAll($rs);
	}
	
	public function deleteTeacherWeeklySchedule($userId,$startTime,$endTime,$date,$day,$id){
		$userId = FatUtility::int($userId);
		if( $userId < 1 ){
			$this->error =  Label::getLabel('LBL_Invalid_Request');
			return false;
		}
		$db = FatApp::getDb();
		$srch = new TeacherWeeklyScheduleSearch();
		$srch->addMultipleFields( array('twsch_user_id','twsch_is_available') );
		$srch->addCondition('twsch_user_id','=',$userId);
		$srch->addCondition('mysql_func_DATE(twsch_date)','=',$date, 'AND',true );
		$srch->addCondition('twsch_start_time','=',$startTime);
		$srch->addCondition('twsch_end_time','=',$endTime);
		$rs = $srch->getResultSet();
		$weeklySchCount = $rs->totalRecords();
        $weeklyDate = FatApp::getDb()->fetch($rs);
		
		$gaSrch = new TeacherGeneralAvailabilitySearch();
		$gaSrch->addMultipleFields( array('tgavl_user_id') );
		$gaSrch->addCondition('tgavl_user_id','=',$userId);
		$gaSrch->addCondition('tgavl_day','=',$day);
		$gaSrch->addCondition('tgavl_start_time','=',$startTime );
		$gaSrch->addCondition('tgavl_end_time','=',$endTime );
		$gaRs = $gaSrch->getResultSet();
		$gaCount = $gaRs->totalRecords();
		
		if( $weeklySchCount > 0 && $gaCount > 0 ){
            $weeklyDateAvailability = ($weeklyDate['twsch_is_available']==TeacherWeeklySchedule::UNAVAILABLE)?TeacherWeeklySchedule::AVAILABLE:TeacherWeeklySchedule::UNAVAILABLE;
			$db->updateFromArray(TeacherWeeklySchedule::DB_TBL,array('twsch_is_available'=>$weeklyDateAvailability),array('smt'=>'twsch_date = ? and twsch_start_time = ? and twsch_end_time = ? and twsch_user_id = ?','vals'=>array($date,$startTime,$endTime,$userId)));
			return true;
		}
		
		$deleteRecords = $db->deleteRecords(TeacherWeeklySchedule::DB_TBL,array('smt'=>'twsch_user_id = ? and twsch_id = ?','vals'=>array($userId,$id)));
		if( $db->getError() ){
			$this->error = $db->getError();
			return false;
		}
		return true;
	}
	
	public function addTeacherWeeklySchedule($post,$userId){
		if ( empty($post) ) {
			$this->error =  Label::getLabel('LBL_Invalid_Request');	
			return false;
		}
		$userId = FatUtility::int($userId);
		if( $userId < 1 ){
			$this->error =  Label::getLabel('LBL_Invalid_Request');
			return false;
		}
		$postJson = json_decode($post['data']);
		$db = FatApp::getDb();
        $postJsonArr = array();        
        foreach($postJson as $k=>$postObj){
        /*[ Clubbing the continuous timeslots */            
            if($k>0 AND ($postJson[$k-1]->date == $postObj->date) AND ($postJson[$k-1]->start == $postObj->start)){
                $postJsonArr[count($postJsonArr)-1]->end = ($postObj->end > $postJsonArr[count($postJsonArr)-1]->end)?$postObj->end:$postJsonArr[count($postJsonArr)-1]->end;
                continue;
            }
        /* ] */            
            $postJsonArr[] = $postObj;
        }        

		foreach( $postJsonArr as $val ){
			if(preg_match('/_fc/',$val->_id) || $val->action == "fromGeneralAvailability"){
				if( strtotime($val->date) >= strtotime(date('Y-m-d')) && $val->start != '00:00:00' )
				{
					$insertArr = array('twsch_user_id'=>$userId,'twsch_start_time'=>$val->start,'twsch_end_time'=>$val->end,"twsch_is_available"=>$val->classtype,'twsch_date'=>$val->date);
					if(!$db->insertFromArray(TeacherWeeklySchedule::DB_TBL,$insertArr)){
						$this->error = $db->getError();
						return false;
					}
				}				
			} else {
				$updateArr = array('twsch_start_time'=>$val->start,'twsch_end_time'=>$val->end,"twsch_is_available"=>$val->classtype,'twsch_date'=>$val->date);
				$updateWhereArr = array('smt'=>'twsch_id = ? and twsch_user_id = ?','vals'=>array($val->_id,$userId));
				if(!$db->updateFromArray(TeacherWeeklySchedule::DB_TBL,$updateArr,$updateWhereArr)){
					$this->error = $db->getError();
					return false;
				}
			}
		}
		return true;
	}
	
	public function checkCalendarTimeSlotAvailability($userId,$startTime,$endTime,$date,$day){
		$userId = FatUtility::int($userId);
		if( $userId < 1 ){
			$this->error =  Label::getLabel('LBL_Invalid_Request');
			return false;
		}
		if($startTime>$endTime){
			return 0;
		}

/*        if(strtotime($date.' '.$startTime)<strtotime(date('Y-m-d h:i:s'))){
            return 0
        }*/
        
        
		$srch = new ScheduledLessonSearch();
		$srch->joinTeacher();
		$srch->joinTeacherSettings();
		$srch->addMultipleFields(
			array(
			'slns.slesson_date',
			'slns.slesson_start_time',
			'slns.slesson_end_time'
			));
		$srch->addCondition( 'slns.slesson_teacher_id',' = ', $userId );
		$srch->addCondition( 'slns.slesson_date',' = ', $date );
		$cnd = $srch->addCondition( 'slns.slesson_start_time',' >= ', $startTime,'AND' );
        $cnd->attachCondition('slns.slesson_start_time','<=',$endTime,'AND');

		$cnd1 = $cnd->attachCondition( 'slns.slesson_end_time',' >= ', $startTime,'OR' );
        $cnd1->attachCondition('slns.slesson_end_time','<=',$endTime,'AND');        
		$srch->addCondition( 'slns.slesson_status',' = ', ScheduledLesson::STATUS_SCHEDULED );
		$res = $srch->getResultSet();
		$resC = $res->totalRecords();        
        if($resC > 0){
            return 0;
        }
        
        
		$db = FatApp::getDb();
		$gaSrch = new TeacherGeneralAvailabilitySearch();
		$gaSrch->addCondition('tgavl_user_id','=',$userId);
		$gaSrch->addCondition('tgavl_day','=',$day);
		//$cnd = $gaSrch->addCondition('tgavl_start_time','between',array($startTime,$endTime),'AND');
		//$cnd->attachCondition('tgavl_end_time','between',array($startTime,$endTime),'or');
		
		$cnd = $gaSrch->addCondition('tgavl_start_time','<=',$startTime);
		$cnd->attachCondition('tgavl_end_time','>',$startTime,'AND');		

		$cnd = $gaSrch->addCondition('tgavl_end_time','>=',$endTime);
		$cnd->attachCondition('tgavl_start_time','<',$endTime,'AND');
		
		//$cnd->attachCondition('tgavl_end_time','>=',$endTime,'and');
		//$cnd->attachCondition('tgavl_start_time','<=',$startTime,'and');
		$gaRs = $gaSrch->getResultSet();
		//echo $gaSrch->getQuery(); die;
		$gaCount = $gaRs->totalRecords();
		
		$tWsrchC = new TeacherWeeklyScheduleSearch();
		$tWsrchC->addCondition('twsch_user_id','=',$userId);
		$tWsrchC->addCondition('twsch_date','=',$date);
		$tWRsC = $tWsrchC->getResultSet();
		$tWcountC = $tWRsC->totalRecords();
		
		$tWsrch = clone $tWsrchC;
		$tWsrch->addCondition('twsch_start_time','<=',$startTime);
		$tWsrch->addCondition('twsch_end_time','>=',$endTime);
		$tWRs = $tWsrch->getResultSet();
		$tWcount = $tWRs->totalRecords();
		$tWRows = $db->fetch($tWRs);        
		
		if($tWcount > 0 || $tWcountC > 0)
		{
			if($tWRows['twsch_is_available'] == static::AVAILABLE)
			{
				return 1;
			}
			return 0;
		}
		if($gaCount > 0)
		{
			return 1;
		}
		return 0;
	}
	
	public static function isSlotAvailable( $teacherId, $startDateTime, $endDateTime ){
		$teacherId = FatUtility::int( $teacherId );
		if( $teacherId < 1 ){
			trigger_error ( Label::getLabel( "LBL_Invalid_Teacher_Id_Passed" ), E_USER_ERROR );
		}
		
		$startDateTime = date('Y-m-d H:i:s', strtotime($startDateTime) );
		if( !FatDate::validateDateString($startDateTime) || $startDateTime == "1970-01-01 05:30:00" || $startDateTime == "0000-00-00 00:00:00" ){
			trigger_error( Label::getLabel('LBL_Invalid_Date_selected'), E_USER_ERROR);
		}
		
		$endDateTime = date('Y-m-d H:i:s', strtotime($endDateTime) );
		if( !FatDate::validateDateString($endDateTime) || $endDateTime == "1970-01-01 05:30:00" || $endDateTime == "0000-00-00 00:00:00" ){
			trigger_error( Label::getLabel('LBL_Invalid_Date_selected'), E_USER_ERROR);
		}
		
		$selectedDateWeekRangeArr = CommonHelper::getWeekRangeByDate( $startDateTime );
		
		/* [ */
		$weeklySchSrchObj = new TeacherWeeklyScheduleSearch();
		$weeklySchSrchObj->addCondition( 'twsch_user_id', '=', $teacherId );
		/* ] */
		
		/* [ */
		$weeklySchSrch = clone $weeklySchSrchObj;
		$weeklySchSrch->addCondition( 'twsch_start_time', '<=', $startDateTime );
		$weeklySchSrch->addCondition( 'twsch_end_time', '>=', $startDateTime );
		$weeklySchSrch->setPageSize(1);
		$weeklySchSrch->addMultipleFields( array('twsch_is_available') );
		$weeklySchRs = $weeklySchSrch->getResultSet();
		$weeklySchSelectedSlotRow = FatApp::getDb()->fetch( $weeklySchRs );
		/* ] */
		
		/* [ */
		$weeklySchDataAddedSrch = clone $weeklySchSrchObj;
		$weeklySchDataAddedSrch->addCondition('mysql_func_DATE(twsch_start_time)', '>=', $selectedDateWeekRangeArr['start'], 'AND', true );
		$weeklySchDataAddedSrch->addCondition('mysql_func_DATE(twsch_end_time)', '<=', $selectedDateWeekRangeArr['end'], 'AND', true );
		$weeklySchDataAddedRs = $weeklySchDataAddedSrch->getResultSet();
		$isWeeklySchDataAdded = $weeklySchDataAddedRs->totalRecords();
		/* ] */
		
		if( $isWeeklySchDataAdded > 0 ){
			if( !$weeklySchSelectedSlotRow ){
				return false;
			}
			if( $weeklySchSelectedSlotRow['twsch_is_available'] == 1 ){
				return true;
			}
			return false;
		}
		
		/* Now, start checking in general Availablity[ */
		$gaSrch = new TeacherGeneralAvailabilitySearch();
		$gaSrch->addCondition('tgavl_user_id','=',$teacherId);
		$gaSrch->addCondition('tgavl_day','=', date('w', strtotime($startDateTime)) );
		
		$weeklySchSrch->addCondition( 'mysql_func_TIME(tgavl_start_time)', '<=', date('H:i:s', strtotime($startDateTime)), 'AND', true );
		$weeklySchSrch->addCondition( 'mysql_func_TIME(tgavl_end_time)', '>=', date('H:i:s', strtotime($startDateTime) ),  'AND', true );
		
		$gaRs = $gaSrch->getResultSet();
		$gaRow = FatApp::getDb()->fetch($gaRs);
		if( !$gaRow ){
			return false;
		}
		return true;
		/* ] */
	}
}