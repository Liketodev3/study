<?php
class ScheduledLesson extends MyAppModel
{
    const DB_TBL = 'tbl_scheduled_lessons';
    const DB_TBL_PREFIX = 'slesson_';

    const STATUS_SCHEDULED = 1;
    const STATUS_NEED_SCHEDULING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_UPCOMING = 6;
    const STATUS_ISSUE_REPORTED = 7;
    const STATUS_RESCHEDULED = 8;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getStatusArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        return array(
            static::STATUS_SCHEDULED	=>	Label::getLabel('LBL_Scheduled', $langId),
            static::STATUS_NEED_SCHEDULING	=>	Label::getLabel('LBL_Need_to_be_scheduled', $langId),
            static::STATUS_COMPLETED	=>	Label::getLabel('LBL_Completed', $langId),
            static::STATUS_CANCELLED	=>	Label::getLabel('LBL_Cancelled', $langId),
            static::STATUS_UPCOMING	=>	Label::getLabel('LBL_Upcoming', $langId),
            static::STATUS_ISSUE_REPORTED	=>	Label::getLabel('LBL_Issue_Reported_Status', $langId),
            static::STATUS_RESCHEDULED	=>	Label::getLabel('LBL_Rescheduled', $langId),
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


    public function save()
    {
        if ($this->getMainTableRecordId() == 0) {
            $this->setFldValue('slesson_added_on', date('Y-m-d H:i:s'));
        }

        return parent::save();
    }

    public function payTeacherCommission()
    {
        $srch = new ScheduledLessonSearch();
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->addCondition('slns.slesson_id', ' = ', $this->getMainTableRecordId());
        $srch->addCondition('slns.slesson_is_teacher_paid', ' = ', 0);
        $srch->addCondition('op.op_lpackage_is_free_trial', ' = ', 0);
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        if ($data) {
            $tObj = new Transaction($data['slesson_teacher_id']);
            $data = array(
                'utxn_user_id' => $data['slesson_teacher_id'],
                'utxn_date' => date('Y-m-d H:i:s'),
                'utxn_comments' => sprintf(Label::getLabel('LBL_LessonId:_%s_Payment_Received', CommonHelper::getLangId()), $this->getMainTableRecordId()),
                'utxn_status' => Transaction::STATUS_COMPLETED,
                'utxn_type' => Transaction::TYPE_LOADED_MONEY_TO_WALLET,
                'utxn_credit' => $data['op_commission_charged'],
                'utxn_slesson_id' => $data['slesson_id'],
            );

            if (!$tObj->addTransaction($data)) {
                trigger_error($tObj->getError(), E_USER_ERROR);
            }
            return true;
        } else {
            return false;
        }
    }

    public function holdPayment($user_id, $lesson_id)
    {
        $db = FatApp::getDb();
        if (!$db->updateFromArray(Transaction::DB_TBL, array('utxn_status'=>Transaction::STATUS_PENDING), array('smt'=>'utxn_user_id = ? and utxn_slesson_id = ?','vals'=>array( $user_id, $lesson_id )))) {
            return false;
        }
        return true;
    }

    public function changeLessonStatus($lesson_id, $status)
    {
        $lesson_id = FatUtility::int($lesson_id);
        $status = FatUtility::int($status);
        $db = FatApp::getDb();
        if (!$db->updateFromArray(self::DB_TBL, array('slesson_status'=>$status ), array('smt'=>'slesson_id = ?','vals'=>array( $lesson_id )))) {
            return false;
        }
        return true;
    }

    public function cancelLessonByTeacher($reason='')
    {
        $lessonDetailRows = ScheduledLessonDetails::getScheduledRecordsByLessionId($this->getMainTableRecordId());

        /* update status for every learner and refund [ */
		foreach($lessonDetailRows as $lessonDetailRow){
            // CommonHelper::printArray($lessonDetailRow);die;
			$sLessonDetailObj = new ScheduledLessonDetails($lessonDetailRow['sldetail_id']);

            if (!$sLessonDetailObj->refundToLearner()) {
                $this->error = $sLessonDetailObj->getError();
                return false;
            }

			if (!$sLessonDetailObj->changeStatus(ScheduledLesson::STATUS_CANCELLED)) {
				$this->error = $sLessonDetailObj->getError();
                return false;
			}

            $start_date = $lessonDetailRow['slesson_date'];
            $start_time = $lessonDetailRow['slesson_start_time'];
            $end_time = $lessonDetailRow['slesson_end_time'];

            $user_timezone = $lessonDetailRow['learnerTz'];

            if($start_time){
                $start_time = $start_date.' '.$start_time;
                $end_time = $start_date.' '.$end_time;
                $start_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $start_time, true, $user_timezone);
                $start_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $start_time, true, $user_timezone);
                $end_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $end_time, true, $user_timezone);
            }

            /* send an email to learner[ */
            $vars = array(
                '{learner_name}'    => $lessonDetailRow['learnerFullName'],
                '{teacher_name}'    => $lessonDetailRow['teacherFullName'],
                '{lesson_name}'     => $lessonDetailRow['teacherTeachLanguageName'],
                '{teacher_comment}' => $reason,
                '{lesson_date}'     => FatDate::format($start_date),
                '{lesson_start_time}' => $start_time,
                '{lesson_end_time}' => $end_time,
            );

            if (!EmailHandler::sendMailTpl($lessonDetailRow['learnerEmailId'], 'teacher_cancelled_email', CommonHelper::getLangId(), $vars)) {
                $this->error = Label::getLabel('LBL_Mail_not_sent!');
                return false;
            }
		}
        return true;
    }

    public function rescheduleLessonByTeacher($reason='')
    {
        $lessonDetailRows = ScheduledLessonDetails::getScheduledRecordsByLessionId($this->getMainTableRecordId());

        /* update status for every learner [ */
		foreach($lessonDetailRows as $lessonDetailRow)
        {
            $sLessonDetailObj = new ScheduledLessonDetails($lessonDetailRow['sldetail_id']);
            $sLessonDetailObj->assignValues(array(
                'sldetail_learner_status' =>	ScheduledLesson::STATUS_NEED_SCHEDULING,
                'sldetail_learner_join_time' =>	'',
            ));
            if (!$sLessonDetailObj->save()) {
                $this->error = $sLessonDetailObj->getError();
                return false;
            }

            $start_date = $lessonDetailRow['slesson_date'];
            $start_time = $lessonDetailRow['slesson_start_time'];
            $end_time = $lessonDetailRow['slesson_end_time'];

            $user_timezone = $lessonDetailRow['learnerTz'];

            if($start_time){
                $start_time = $start_date.' '.$start_time;
                $end_time = $start_date.' '.$end_time;
                $start_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $start_time, true, $user_timezone);
                $start_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $start_time, true, $user_timezone);
                $end_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $end_time, true, $user_timezone);
            }

            /* send email to learner[ */
            $vars = array(
                '{learner_name}'    => $lessonDetailRow['learnerFullName'],
                '{teacher_name}'    => $lessonDetailRow['teacherFullName'],
                '{lesson_name}'     => $lessonDetailRow['teacherTeachLanguageName'],
                '{teacher_comment}' => $reason,
                '{lesson_date}'     => FatDate::format($start_date),
                '{lesson_start_time}' => $start_time,
                '{lesson_end_time}' => $end_time,
                '{action}' => Label::getLabel('LBL_Rescheduled'),
            );

            if (!EmailHandler::sendMailTpl($lessonDetailRow['learnerEmailId'], 'teacher_reschedule_email', CommonHelper::getLangId(), $vars)) {
                $this->error = Label::getLabel('LBL_Mail_not_sent!');
                return false;
            }
            /* ] */
        }
        return true;
    }
}
