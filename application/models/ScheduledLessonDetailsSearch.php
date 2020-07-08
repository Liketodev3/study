<?php
class ScheduledLessonDetailsSearch extends SearchBase
{
    public function __construct($doNotCalculateRecords = true)
    {
        parent::__construct(ScheduledLessonDetails::DB_TBL, 'sld');

        if (true === $doNotCalculateRecords) {
            $this->doNotCalculateRecords();
        }
    }
    
    public function joinScheduledLesson()
    {
        $this->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'sld.sldetail_slesson_id=sl.slesson_id', 'sl');
    }
    
    public function getRefundPercentage($sldetailId):int
    {
        $this->joinScheduledLesson();
        $this->addCondition('sldetail_id', '=', $sldetailId);
        $rs = $this->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        if (empty($data))
        {
            return 0; // if not exist refund nothing, mark charges 100%
        }
        
        $to_time = strtotime($data['slesson_date'].' '.$data['slesson_start_time']);
        $from_time = strtotime(date('Y-m-d H:i:s'));
        $diff = round(($to_time - $from_time) / 3600, 2);

        if($data['slesson_grpcls_id']>0)
        {
            return FatApp::getConfig('CONF_LEARNER_CLASS_REFUND_PERCENTAGE', FatUtility::VAR_INT, 100); // refund charges for class
        }
        if ($diff<24 ) {
            return FatApp::getConfig('CONF_LEARNER_REFUND_PERCENTAGE', FatUtility::VAR_INT, 10);
        }
        
        return 100;// do not charge
    }
}