<?php
class LessonStatusLog extends MyAppModel
{
    const DB_TBL = 'tbl_lesson_status_log';
    const DB_TBL_PREFIX = 'lesstslog_';

    const NOT_CANCELLED_REPORT = 1;
    const CANCELLED_REPORT = 2;
    const BOTH_REPORT = 3;

    private $lessonDetailId;
    private $lessonData;

    public function __construct(int $lessonDetailId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        if ( $lessonDetailId > 0 ) {
            $this->lessonDetailId = $lessonDetailId;
            $scheduledLessonDetailsSearch = new ScheduledLessonDetailsSearch();
            $this->lessonData = $scheduledLessonDetailsSearch->getDetailsById($this->lessonDetailId);
        }
    }
	
	public static function getSearchObject(string $alias = 'lsl' ) : object
    {
        $srch = new SearchBase(static::DB_TBL, $alias);
        return $srch;
    }


    public function save()
    {
        if ($this->getMainTableRecordId() == 0) {
            $this->setFldValue('lesstslog_added_on', date('Y-m-d H:i:s'));
        }
        return parent::save();
    }
    //ScheduledLesson::STATUS_CANCELLED, User::USER_TYPE_LEANER, UserAuthentication::getLoggedUserId(), $post['cancel_lesson_msg'
    
    public function addLog(int $status, int $userType, int $userId, string $comment) :bool
    {
        $scheduledLessonDetails = new ScheduledLessonDetails( $this->lessonDetailId );
        echo "<pre>";print_r($this->lessonData);die;
        // $lessonStatusLogArr = array(
        //     'lesstslog_slesson_id' => $lesson['slesson_id'],
        //     'lesstslog_sldetail_id' => $lesson['sldetail_id'],
        //     'lesstslog_prev_status' => $lesson['slesson_status'],
        //     'lesstslog_current_status' => $currentStatus,
        //     'lesstslog_prev_start_date' => $lesson['slesson_date'],
        //     'lesstslog_prev_end_time' => $lesson['slesson_end_time'],
        //     'lesstslog_prev_start_time' => $lesson['slesson_start_time'],
        //     'lesstslog_prev_end_date' => $lesson['slesson_end_date'],
        //     'lesstslog_updated_by_user_id' => UserAuthentication::getLoggedUserId(),
        //     'lesstslog_updated_by_user_type' => $userType,
        //     'lesstslog_comment' => $comment
        // );

        $lessonStatusLog = new LessonStatusLog();
        $lessonStatusLog->assignValues($lessonStatusLogArr);

    }
	
}
