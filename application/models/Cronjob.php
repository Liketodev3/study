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
		
		$srch = new ScheduledLessonSearch(false);
		//$srch->addGroupBy('slesson_teacher_id');
		$srch->joinOrder();
		$srch->joinOrderProducts();
		$srch->joinTeacher();
		$srch->joinTeacherCredentials();
		$srch->joinLearner();
		$srch->joinLearnerCredentials();
		$srch->addCondition( 'order_is_paid',' = ', Order::ORDER_IS_PAID );
		//$srch->addOrder('slesson_date','DESC');
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
			//'IFNULL(teachercountry_lang.country_name, teachercountry.country_code) as teacherCountryName',
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

		//$srch->addCondition('slns.slesson_date', '=', date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))) );
		$srch->addCondition('slns.slesson_reminder_one', '=', 0 );
		
		$rs = $srch->getResultSet();
		$lessons = FatApp::getDb()->fetchAll($rs);
		
		
		if (empty($lessons)) {
            return Label::getLabel('MSG_No_Record_Found',  $langId );
        } 
		
		$teacherLessonArr = array();
		$lernerLessonArr = array();
		
		foreach ( $lessons as $lesson ) {
			$key = $lesson['teacherId'];
			$key1 = $lesson['learnerId'];
			$teacherLessonArr[$key][] = $lesson;
			$lernerLessonArr[$key1][] = $lesson;
		}
		
		$emailNotificationObj = new EmailHandler();
		$templeteLerner = 'lesson_one_day_reminder_learner';
		$templeteTeacher = 'lesson_one_day_reminder_teacher';
		
        foreach ( $teacherLessonArr as $lessons ) {
			$lessonsData = '';
			$data = array();
			$lessonsData = ' <table width="100%" cellspacing="0" cellpadding="5" border="1"   align="center">
				<thead>
					<tr><th colspan="4">'. date('d F, Y', strtotime($lessons[0]['slesson_date'])) .'</th></tr>
				</thead>
				<tbody>
					<tr>
						<th>
							Lerner
						</th>
						<th>
							Start
						</th>
						<th>
							End 
						</th>
						<th>
							 
						</th>
					</tr>';
			foreach ( $lessons as $lesson ) {
				$teacherLink = CommonHelper::generateFullUrl('teacherScheduledLessons', 'view', array( $lesson['slesson_id'] ));
				$lesson_start_time = date('h:i A', strtotime( $lesson['slesson_start_time']));
				$lesson_end_time = date('h:i A', strtotime( $lesson['slesson_end_time']));
				$lesson_start_date = date('d F, Y', strtotime($lesson['slesson_date']));
				$lesson_end_date = date('d F, Y', strtotime($lesson['slesson_end_date']));
				
				$lessonsData .='<tr>
						<td>'. $lesson['learnerFullName'] .'</td>
						<td>'. $lesson_start_time .'</td>
						<td>'. $lesson_end_time .'</td>
						<td><a href="'. $teacherLink .'" style="background:#e84c3d; color:#fff; text-decoration:none;font-size:16px; font-weight:500;padding:10px 30px;display:inline-block;border-radius:3px;">View</a></td>
					</tr>';
			}
			$lessonsData .= '</tbody></tabale>';
			$data = array('user_email' => $lessons[0]['teacherEmail'],
						  'user_first_name' => $lessons[0]['teacherFname'],
						  'user_last_name' => $lessons[0]['teacherLname'],
						  'user_full_name' => $lessons[0]['teacherFullName'],
						  'lessons_details' => $lessonsData
					);
			$emailNotificationObj->sendLessonReminderMail( $templeteTeacher, $langId, $data );
		}
        return Label::getLabel('MSG_Success', $langId );
    }
}