<?php
class LessonStatusLog extends MyAppModel
{
    const DB_TBL = 'tbl_lesson_status_log';
    const DB_TBL_PREFIX = 'lesstslog_';

    const NOT_CANCELLED_REPORT = 1;
    const CANCELLED_REPORT = 2;
    const BOTH_REPORT = 3;

    private  $scheduledLessonId;
    public function __construct(int $id = 0, int $scheduledLessonId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->scheduledLessonId =  FatUtility::int($scheduledLessonId);
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
	
	public static function getLatestLessonStatusLog() : object
    {
		$searchObj = self::getSearchObject('lstslog');
        
		$latestLogSearchObj =  self::getSearchObject('latestStatusLessonLog');
		$latestLogSearchObj->doNotCalculateRecords();
        $latestLogSearchObj->doNotLimitRecords();
		$latestLogSearchObj->addMultipleFields(array('max(latestStatusLessonLog.lesstslog_id) as lessonLatestLogId'));
		$latestLogSearchObj->addGroupBy('latestStatusLessonLog.lesstslog_slesson_id');
       
	   $searchObj->joinTable("(".$latestLogSearchObj->getQuery().")", 'INNER JOIN', 'latestLesStatusLog.lessonLatestLogId = lstslog.lesstslog_id', 'latestLesStatusLog');
	   
	   return $searchObj;
		
    }
	
	
	
}
