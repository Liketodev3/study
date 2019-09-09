<?php
class Cronjob extends FatModel
{
    const DB_TBL_CONFIGURATION = 'tbl_configurations';

    public function __construct()
    {
        CommonHelper::initCommonVariables();
    }

    public static function lessonOneDayReminder() {
		
		$langId = CommonHelper::getLangId();
		$srch = $this->getLessonsData();
		$srch->addCondition('slns.slesson_date', '=', date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))) );
		$rs = $srch->getResultSet();
		$lessons = FatApp::getDb()->fetchAll($rs);
		
		if ( empty($lessons )) {
            return Label::getLabel('MSG_No_Record_Found',  $langId );
        } 
		
		if ( $this->prepareDataForReminder( $lessons , 'daily' )) {
			return Label::getLabel('MSG_Success', $langId );
		} else {
			return Label::getLabel('MSG_Error', $langId );
		}
	}
	
	public function lessonHalfHourReminder() {
		$langId = CommonHelper::getLangId();
		$srch = $this->getLessonsData();
		$srch->addCondition('slns.slesson_date', '>=', date('Y-m-d', strtotime('+30 mints', strtotime(date('Y-m-d')))) );
		$srch->addCondition('slns.slesson_date', '<', date('Y-m-d', strtotime('+40 mints', strtotime(date('Y-m-d')))) );
		$rs = $srch->getResultSet();
		$lessons = FatApp::getDb()->fetchAll($rs);
		
		if ( empty($lessons )) {
            return Label::getLabel('MSG_No_Record_Found',  $langId );
        } 
		
		if ( $this->prepareDataForReminder( $lessons , 'daily' )) {
			return Label::getLabel('MSG_Success', $langId );
		} else {
			return Label::getLabel('MSG_Error', $langId );
		}
	}
	
	
	private function prepareDataForReminder( $lessons, $cronType ) {
		$teacherLessonArr = array();
		$lernerLessonArr = array();
		foreach ( $lessons as $lesson ) {
			$key = $lesson['teacherId'];
			$key1 = $lesson['learnerId'];
			$teacherLessonArr[$key][] = $lesson;
			$lernerLessonArr[$key1][] = $lesson;
		}
		$teacherStatus = $this->sendEmailToUsers( $teacherLessonArr, 'teacher', $cronType  );
		$learnerStatus = $this->sendEmailToUsers( $lernerLessonArr, 'learner' , $cronType );
		if (  $cronType == 'daily' ) {
			$_filed_to_update = 'slesson_reminder_one';
		} else {
			$_filed_to_update = 'slesson_reminder_two';
		}
		
		
		if ( !empty( $teacherStatus ) || !empty($learnerStatus) ) {
			$lessonIds = array_merge( $teacherStatus, $learnerStatus);
			$lessonIds = array_unique( $lessonIds );
			$LessonIdsString =  '('. implode(', ', $lessonIds) . ')';
			$db = FatApp::getDb();
			
			if ( $db->query( 'UPDATE `'. ScheduledLesson::DB_TBL .'` SET `'. $_filed_to_update .'` = 1 WHERE `slesson_id` IN '. $LessonIdsString )) {
				//return Label::getLabel('MSG_Success', $langId );
				return true;
			} else {
				return false;
			}
		}
	}
	
	
	private function sendEmailToUsers( $LessonArr, $userType ) {
		if ( $userType == 'teacher' ) {
			$template = 'lesson_one_day_reminder_teacher';
			$controller = 'teacherScheduledLessons';
			$user = 'Learner';
		} else {
			$template = 'lesson_one_day_reminder_learner';
			$controller = 'learnerScheduledLessons';
			$user = 'Teacher';
		}
		$teacherLessonIds = array();
		$emailNotificationObj = new EmailHandler();
		$langId = CommonHelper::getLangId();
		foreach ( $LessonArr as $lessons ) {
			$lessonsData = '';
			$data = array();
			
			$lessonsData = ' <table width="100%" cellspacing="0" cellpadding="5" border="1"   align="center">
				<thead>
					<tr><th colspan="4">'. date('d F, Y', strtotime($lessons[0]['slesson_date'])) .'</th></tr>
				</thead>
				<tbody>
					<tr>
						<th> '. $user .' </th>
						<th> Start </th>
						<th> End  </th>
						<th> </th>
					</tr>';
			foreach ( $lessons as $lesson ) {
				$teacherLessonIds[] = $lesson['slesson_id'];
				$teacherLink = CommonHelper::generateFullUrl($controller, 'view', array( $lesson['slesson_id'] ));
				$lesson_start_time = date('h:i A', strtotime( $lesson['slesson_start_time']));
				$lesson_end_time = date('h:i A', strtotime( $lesson['slesson_end_time']));
				$lesson_start_date = date('d F, Y', strtotime($lesson['slesson_date']));
				$lesson_end_date = date('d F, Y', strtotime($lesson['slesson_end_date']));
				
				if ( $userType == 'learner' ) {
					$lessonsData .='<tr>
						<td>'. $lesson['teacherFullName'] .'</td>
						<td>'. $lesson_start_time .'</td>
						<td>'. $lesson_end_time .'</td>
						<td><a href="'. $teacherLink .'" style="background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;">View</a></td>
					</tr>';
				} else {
					$lessonsData .='<tr>
						<td>'. $lesson['learnerFullName'] .'</td>
						<td>'. $lesson_start_time .'</td>
						<td>'. $lesson_end_time .'</td>
						<td><a href="'. $teacherLink .'" style="background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;">View</a></td>
					</tr>';
				}
				
				
			}
			$lessonsData .= '</tbody></tabale>';
			
			if ( $userType == 'teacher' ) {
				$data = array('user_email' => $lessons[0]['teacherEmail'],
					  'user_first_name' => $lessons[0]['teacherFname'],
					  'user_last_name' => $lessons[0]['teacherLname'],
					  'user_full_name' => $lessons[0]['teacherFullName'],
					  'lessons_details' => $lessonsData
				);
			} else {
				$data = array('user_email' => $lessons[0]['learnerEmail'],
					  'user_first_name' => $lessons[0]['LearnerFname'],
					  'user_last_name' => $lessons[0]['LearnerLname'],
					  'user_full_name' => $lessons[0]['learnerFullName'],
					  'lessons_details' => $lessonsData
				);
			}		
			$emailNotificationObj->sendLessonReminderMail( $template, $langId, $data ); 
			
			
		}
		return $teacherLessonIds;
        //return Label::getLabel('MSG_Success', $langId );
		
	}
	
	private function getLessonsData() {
		$srch = new ScheduledLessonSearch(false);
		$srch->joinOrder();
		$srch->joinOrderProducts();
		$srch->joinTeacher();
		$srch->joinTeacherCredentials();
		$srch->joinLearner();
		$srch->joinLearnerCredentials();
		$srch->addCondition( 'order_is_paid',' = ', Order::ORDER_IS_PAID );
		$srch->addOrder('slesson_status','ASC');
		$srch->addMultipleFields(array(
			'slns.slesson_id',
			'slns.slesson_slanguage_id',
			'slns.slesson_learner_id as learnerId',
			'slns.slesson_teacher_id as teacherId',
			'ut.user_first_name as teacherFname',
			'ut.user_last_name as teacherLname',
			'tcred.credential_email as teacherEmail',
			'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
			'ul.user_first_name as LearnerFname',
			'ul.user_last_name as LearnerLname',
			'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
			'lcred.credential_email as learnerEmail',
			'slns.slesson_date',
			'slns.slesson_end_date',
			'slns.slesson_start_time',
			'slns.slesson_end_time',
			'slns.slesson_status',
			'slns.slesson_is_teacher_paid',			
			'"-" as teacherTeachLanguageName',
			'op_lpackage_is_free_trial as is_trial',
			'op_lesson_duration'
		));
		$srch->addCondition('slns.slesson_reminder_one', '=', 0 );
		$srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED );
		return $srch;
	}
}