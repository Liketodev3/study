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

    public static function getStatusArr()
    {
        return [
            static::STATUS_UPCOMING => Label::getLabel('LBL_Upcoming'),
            static::STATUS_SCHEDULED => Label::getLabel('LBL_Scheduled'),
            static::STATUS_RESCHEDULED => Label::getLabel('LBL_Rescheduled'),
            static::STATUS_NEED_SCHEDULING => Label::getLabel('LBL_Need_to_be_scheduled'),
            static::STATUS_COMPLETED => Label::getLabel('LBL_Completed'),
            static::STATUS_CANCELLED => Label::getLabel('LBL_Cancelled'),
            static::STATUS_ISSUE_REPORTED => Label::getLabel('LBL_Issue_Reported_Status'),
        ];
    }

    public function save()
    {
        if ($this->getMainTableRecordId() == 0) {
            $this->setFldValue('slesson_added_on', date('Y-m-d H:i:s'));
        }
        return parent::save();
    }

    /**
     * @todo Add cancel hours check from configuration
     */
    public function cancelLessonByTeacher($reason = '')
    {
        $lessonDetailRows = ScheduledLessonDetails::getScheduledRecordsByLessionId($this->getMainTableRecordId());
        /* update status for every learner and refund [ */
        foreach ($lessonDetailRows as $lessonDetailRow) {
            $sLessonDetailObj = new ScheduledLessonDetails($lessonDetailRow['sldetail_id']);
            if (!$sLessonDetailObj->refundToLearner()) {
                $this->error = $sLessonDetailObj->getError();
                return false;
            }
            if (!$sLessonDetailObj->changeStatus(ScheduledLesson::STATUS_CANCELLED)) {
                $this->error = $sLessonDetailObj->getError();
                return false;
            }
            // remove from learner google calendar
            $token = UserSetting::getUserSettings($lessonDetailRow['learnerId'])['us_google_access_token'];
            if ($token) {
                $sLessonDetailObj->loadFromDb();
                $oldCalId = $sLessonDetailObj->getFldValue('sldetail_learner_google_calendar_id');
                if ($oldCalId) {
                    SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
                }
                $sLessonDetailObj->setFldValue('sldetail_learner_google_calendar_id', '');
                $sLessonDetailObj->save();
            }
            $start_date = $lessonDetailRow['slesson_date'];
            $start_time = $lessonDetailRow['slesson_start_time'];
            $end_time = $lessonDetailRow['slesson_end_time'];
            $user_timezone = $lessonDetailRow['learnerTz'];
            if ($start_time) {
                $start_time = $start_date . ' ' . $start_time;
                $end_time = $start_date . ' ' . $end_time;
                $start_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $start_time, true, $user_timezone);
                $start_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $start_time, true, $user_timezone);
                $end_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $end_time, true, $user_timezone);
            }

            $userNotification = new UserNotifications($lessonDetailRow['learnerId']);
            $userNotification->cancelLessonNotification($lessonDetailRow['sldetail_id'], $lessonDetailRow['teacherId'], $lessonDetailRow['teacherFullName'], USER::USER_TYPE_LEANER, $reason);
            /* send an email to learner[ */
            $vars = [
                '{lesson_id}' => $lessonDetailRow['slesson_id'],
                '{learner_name}' => $lessonDetailRow['learnerFullName'],
                '{teacher_name}' => $lessonDetailRow['teacherFullName'],
                '{lesson_name}' => ($lessonDetailRow['op_lpackage_is_free_trial'] == applicationConstants::NO) ? $lessonDetailRow['teacherTeachLanguageName'] : Label::getLabel('LBL_Trial'),
                '{teacher_comment}' => $reason,
                '{lesson_date}' => FatDate::format($start_date),
                '{lesson_start_time}' => $start_time,
                '{lesson_end_time}' => $end_time,
                '{lesson_url}' => CommonHelper::generateFullUrl('LearnerScheduledLessons', 'view', [$lessonDetailRow['sldetail_id']]),
            ];
            if (!EmailHandler::sendMailTpl($lessonDetailRow['learnerEmailId'], 'teacher_cancelled_email', CommonHelper::getLangId(), $vars)) {
                $this->error = Label::getLabel('LBL_Mail_not_sent!');
                return false;
            }
        }
        return true;
    }

    public function rescheduleLessonByTeacher($reason = '')
    {
        $lessonDetailRows = ScheduledLessonDetails::getScheduledRecordsByLessionId($this->getMainTableRecordId());
        /* update status for every learner [ */
        foreach ($lessonDetailRows as $lessonDetailRow) {
            $sLessonDetailObj = new ScheduledLessonDetails($lessonDetailRow['sldetail_id']);
            $sLessonDetailObj->assignValues(['sldetail_learner_status' => ScheduledLesson::STATUS_NEED_SCHEDULING, 'sldetail_learner_join_time' => '']);
            if (!$sLessonDetailObj->save()) {
                $this->error = $sLessonDetailObj->getError();
                return false;
            }
            // remove from learner google calendar
            $token = UserSetting::getUserSettings($lessonDetailRow['learnerId'])['us_google_access_token'];
            if ($token) {
                $sLessonDetailObj->loadFromDb();
                $oldCalId = $sLessonDetailObj->getFldValue('sldetail_learner_google_calendar_id');
                if ($oldCalId) {
                    SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
                }
                $sLessonDetailObj->setFldValue('sldetail_learner_google_calendar_id', '');
                $sLessonDetailObj->save();
            }
            $start_date = $lessonDetailRow['slesson_date'];
            $start_time = $lessonDetailRow['slesson_start_time'];
            $end_time = $lessonDetailRow['slesson_end_time'];
            $user_timezone = $lessonDetailRow['learnerTz'];
            if ($start_time) {
                $start_time = $start_date . ' ' . $start_time;
                $end_time = $start_date . ' ' . $end_time;
                $start_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $start_time, true, $user_timezone);
                $start_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $start_time, true, $user_timezone);
                $end_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $end_time, true, $user_timezone);
            }
            /* send email to learner[ */
            $vars = [
                '{lesson_id}' => $lessonDetailRow['slesson_id'],
                '{learner_name}' => $lessonDetailRow['learnerFullName'],
                '{teacher_name}' => $lessonDetailRow['teacherFullName'],
                '{lesson_name}' => ($lessonDetailRow['op_lpackage_is_free_trial'] == applicationConstants::NO) ? $lessonDetailRow['teacherTeachLanguageName'] : Label::getLabel('LBL_Trial'),
                '{teacher_comment}' => $reason,
                '{lesson_date}' => FatDate::format($start_date),
                '{lesson_url}' => CommonHelper::generateFullUrl('LearnerScheduledLessons', 'view', [$lessonDetailRow['sldetail_id']]),
                '{lesson_start_time}' => $start_time,
                '{lesson_end_time}' => $end_time,
                '{action}' => Label::getLabel('LBL_Rescheduled'),
            ];
            if (!EmailHandler::sendMailTpl($lessonDetailRow['learnerEmailId'], 'teacher_reschedule_email', CommonHelper::getLangId(), $vars)) {
                $this->error = Label::getLabel('LBL_Mail_not_sent!');
                return false;
            }
            /* ] */
        }
        return true;
    }

    public function markTeacherJoinTime()
    {
        $this->assignValues(['slesson_teacher_join_time' => date('Y-m-d H:i:s')]);
        return $this->save();
    }

    public function endLesson()
    {
        $lessonId = $this->getMainTableRecordId();
        $this->loadFromDb();
        $lessonRow = $this->getFlds();

        if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_COMPLETED) {
            if ($lessonRow['slesson_ended_by'] ==  User::USER_TYPE_TEACHER) {
                $this->error = Label::getLabel('LBL_You_already_end_lesson!');
                return false;
            }
            $this->assignValues(array('slesson_teacher_end_time' => date('Y-m-d H:i:s')));
            return $this->save();
        }

        $dataUpdateArr = array(
            'slesson_status' => ScheduledLesson::STATUS_COMPLETED,
            'slesson_ended_by' => User::USER_TYPE_TEACHER,
            'slesson_ended_on' => date('Y-m-d H:i:s'),
            'slesson_teacher_end_time' => date('Y-m-d H:i:s'),
        );

        $db = FatApp::getDb();
        $db->startTransaction();
        if ($lessonRow['slesson_is_teacher_paid'] == 0) {
            if ($this->payTeacherCommission()) {
                $userNotification = new UserNotifications($lessonRow['slesson_teacher_id']);
                $userNotification->sendWalletCreditNotification($lessonRow['slesson_id']);
                $dataUpdateArr['slesson_is_teacher_paid'] = 1;
            }
        }

        $this->assignValues($dataUpdateArr);
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        $sLessonDetailSrch = new ScheduledLessonDetailsSearch();
        $sLessonDetailSrch->addCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $sLessonDetailSrch->addMultipleFields(array('DISTINCT sldetail_id'));
        $sLessonDetails = $sLessonDetailSrch->getRecordsByLessonId($lessonRow['slesson_id']);

        foreach ($sLessonDetails as $sLessonDetail) {
            $scheduledLessonDetailObj = new ScheduledLessonDetails($sLessonDetail['sldetail_id']);
            $scheduledLessonDetailObj->assignValues(array('sldetail_learner_status' => ScheduledLesson::STATUS_COMPLETED));
            if (!$scheduledLessonDetailObj->save()) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($scheduledLessonDetailObj->getError());
            }
        }
        $db->commitTransaction();
        return true;
    }
}
