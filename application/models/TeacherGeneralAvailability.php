<?php
class TeacherGeneralAvailability extends MyAppModel{
	const DB_TBL = 'tbl_teachers_general_availability';
	const DB_TBL_PREFIX = 'teacher_avl_';	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}
	
	public static function getGenaralAvailabilityJsonArr($userId){
		$userId = FatUtility::int($userId);
		if( $userId < 1 ){
			trigger_error(Label::getLabel('LBL_Invalid_Request'));
		}
		$srch = new TeacherGeneralAvailabilitySearch();
		$srch->addMultipleFields(array('tgavl_day','tgavl_start_time','tgavl_end_time','tgavl_user_id','tgavl_id'));
		$srch->addCondition( 'tgavl_user_id','=',$userId );
		$rs = $srch->getResultSet();
		$rows = FatApp::getDb()->fetchAll($rs);
		$cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
		$jsonArr = array();
		$i = 7;
		foreach($rows as $row)
		{
			$gendate = new DateTime();
			$gendate->setISODate(2018,2,$row['tgavl_day']);
			$day = $gendate->format('d');
			$dayNum = $day;
			$jsonArr[] = array(
				"title"=>"",
				"endW"=>date('H:i:s',strtotime($row['tgavl_end_time'])),
				"startW"=>date('H:i:s',strtotime($row['tgavl_start_time'])),
				"end"=>"2018-01-".$dayNum." ".date('H:i:s',strtotime($row['tgavl_end_time'])),
				"start"=>"2018-01-".$dayNum." ".date('H:i:s',strtotime($row['tgavl_start_time'])),
				'_id'=>$row['tgavl_id'],
				"classType"=>1,
				"day"=>$row['tgavl_day'],
				'className'=>"slot_available"
			);
			$i++;
		}
		return $jsonArr;
	}
	
	public function deleteTeacherGeneralAvailability( $tgavl_id, $userId ){
		$userId = FatUtility::int($userId);
		$tgavl_id = FatUtility::int($tgavl_id);
		
		if( $userId < 1 || $tgavl_id < 1 ){
			$this->error =  Label::getLabel('LBL_Invalid_Request');
			return false;
		}
		$db = FatApp::getDb();
		
		$weekendDate = date( 'Y-m-d', strtotime( 'next Saturday +1 day' ) );
		//$deleteWeeklyFutrureWeeksRecords = $db->deleteRecords(TeacherWeeklySchedule::DB_TBL,array('smt'=>'twsch_user_id = ? and (twsch_start_time > ?)','vals'=>array($userId,$weekendDate)));
		
		$deleteRecords = $db->deleteRecords(TeacherGeneralAvailability::DB_TBL,array('smt'=>'tgavl_user_id = ? and tgavl_id = ?','vals'=>array($userId,$tgavl_id)));
		if(!$deleteRecords){
			$this->error = $db->getError();
			return false;
		}
		return true;
	}
	
	public function addTeacherGeneralAvailability( $post, $userId ){
		if ( false === $post ) {
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
		$weekendDate = date( 'Y-m-d', strtotime( 'next Saturday +1 day' ) );
		//$deleteWeeklyFutrureWeeksRecords = $db->deleteRecords(TeacherWeeklySchedule::DB_TBL,array('smt'=>'twsch_user_id = ? and (twsch_start_time > ?)','vals'=>array($userId,$weekendDate)));
		
		$deleteRecords = $db->deleteRecords(TeacherGeneralAvailability::DB_TBL,array('smt'=>'tgavl_user_id = ?','vals'=>array($userId)));

        $postJsonArr = array();
        
        $sort = array();
        
        /*[ Sorting the Array By Date and StartTime */

        foreach($postJson as $k=>$v) {
            $sort['day'][$k] = $v->day;
            $sort['start'][$k] = $v->start;
        }
        # sort by event_type desc and then title asc
        array_multisort($sort['day'], SORT_ASC, $sort['start'], SORT_ASC,$postJson);
        /* ] */

        foreach($postJson as $k=>$postObj){
        /*[ Clubbing the continuous timeslots */            
            if($k>0 AND ($postJson[$k-1]->day == $postObj->day) AND ($postJson[$k-1]->endTime == $postObj->startTime)){
                $postJsonArr[count($postJsonArr)-1]->endTime = $postObj->endTime;
                continue;
            }
        /* ] */            
            $postJsonArr[] = $postObj;
        }
		if( $deleteRecords ){
			foreach( $postJsonArr as $val ){
				$insertArr = array('tgavl_day'=>$val->day,'tgavl_user_id'=>$userId,'tgavl_start_time'=>$val->startTime,'tgavl_end_time'=>$val->endTime);
					if(!$db->insertFromArray(TeacherGeneralAvailability::DB_TBL,$insertArr)){
						$this->error = $db->getError();
						return false;
					}
				}
		}
		return true;
	}

	public static function timeSlotArr(){
		
		return array(
			0	=>	'00:00-02:59',	
			1	=>	'03:00-05:59',	
			2	=>	'06:00-08:59',	
			3	=>	'09:00-11:59',
			4	=>	'12:00-14:59',
			5	=>	'15:00-17:59',
			6	=>	'18:00-20:59',
			7	=>	'21:00-23:59',			
		);
	}	
	
}