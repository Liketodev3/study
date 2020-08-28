<?php
class LessonspaceController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function launch(int $lessonId = 0, int $isTeacher = 0)
    {
        $lessonData =  $this->getLessonDeatils( $lessonId, $isTeacher );

        if(empty($lessonData)){
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST!'));
        }

        $lessonData['isTeacher'] =  ($isTeacher == applicationConstants::YES);

        $lessonMeetingDetail =  new LessonMeetingDetail(0, $lessonId, UserAuthentication::getLoggedUserId());

        if($lessonData['isUserJoinLesson'] > 0) {

            $lessonUrl =  $lessonMeetingDetail->getUserLessonUrl();

            if(empty($lessonUrl)){
                FatUtility::dieJsonError($lessonMeetingDetail->getError());
            }

            $jsonArray = [
                'msg' => Label::getLabel('LBL_Lesson_URL'),
                'url' =>  $lessonUrl
            ];

            FatUtility::dieJsonSuccess($jsonArray);
        }

        $lessonFormatData = $this->formatLessonData($lessonData);
        $lessonspace =  new Lessonspace();
        $lessonData = $lessonspace->launch($lessonFormatData);
        
        if($lessonspace->isError()) {
            FatUtility::dieJsonError($lessonspace->getError());
        }

        $db =  FatApp::getDb();
        $db->startTransaction();
       
        if(!$lessonMeetingDetail->addDeatils( LessonMeetingDetail::URL_KEY, $lessonData['client_url'])) {
             FatUtility::dieJsonError( $lessonMeetingDetail->getError() );
        }

        if(!$lessonMeetingDetail->addDeatils( 'LEESON_ROOM_ID', $lessonData['room_id'])) {

            $db->rollbackTransaction();
            FatUtility::dieJsonError( $lessonMeetingDetail->getError() );

        }

        $db->commitTransaction();

        $jsonArray = [
            'msg' => Label::getLabel('LBL_Lesson_URL'),
            'url' =>  $lessonData['client_url']
        ];

        FatUtility::dieJsonSuccess($jsonArray);

    }

    private function formatLessonData(array $lessonData) : array
    {
     
       $lessonspaceData = array();
       $userTimezone = $lessonData['userTimeZone'];
       $systemTimeZone = MyDate::getTimeZone();
       
       $getTimeZoneOffset =  MyDate::getOffset($userTimezone);

       $startTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $lessonData['slesson_date'].' '.$lessonData['slesson_start_time'], true, $userTimezone);
       $endTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $lessonData['slesson_end_date'].' '.$lessonData['slesson_end_time'], true, $userTimezone);
       
       $unixStartTime = strtotime($startTime);
       $unixeEndTime = strtotime($endTime);
       $startTime = date('Y-m-d',$unixStartTime).'T'.date('H:i:s',$unixStartTime).$getTimeZoneOffset;
       $endTime = date('Y-m-d',$unixeEndTime).'T'.date('H:i:s',$unixeEndTime).$getTimeZoneOffset;
        return  [
            "id" => Lessonspace::LESSON_ID_PREFIX.$lessonData['slesson_id'],
            "user" => [
                'name' => $lessonData['userName'],
                'leader' => $lessonData['isTeacher'],
            ],
            'timeouts' => [
                "not_before" => $startTime,
                "not_after" => $endTime,
            ],
            "features" => [
                'invite' => false,
                'endSession' => false
            ]
           
        ];
    }

    private function getLessonDeatils(int $lessonId = 0, int $isTeacher = 0) : array
    {
        $scheduledLessonSearch= new ScheduledLessonSearch(false);
        $scheduledLessonSearch->joinOrder();
        $scheduledLessonSearch->joinOrderProducts();
        $scheduledLessonSearch->joinTeacher();
        $scheduledLessonSearch->joinLearner();
        $scheduledLessonSearch->addMultipleFields(['slesson_id','slesson_date','slesson_end_date','slesson_start_time','slesson_end_time']);
        $scheduledLessonSearch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $scheduledLessonSearch->addCondition('slesson_id', '=', $lessonId);
        if($isTeacher == applicationConstants::YES) {
            $scheduledLessonSearch->addMultipleFields([
                                                        'IF(slesson_teacher_join_time > 0, 1, 0) as isUserJoinLesson', 
                                                        'ut.user_timezone as userTimeZone',
                                                        'CONCAT(ut.user_first_name," ",ut.user_last_name) as userName',
                                                    ]);
            $scheduledLessonSearch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
            $scheduledLessonSearch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        }else{
            $scheduledLessonSearch->addMultipleFields([
                                                        'IF(sldetail_learner_join_time > 0, 1, 0) as isUserJoinLesson', 
                                                        'ul.user_timezone as userTimeZone',
                                                        'CONCAT(ut.user_first_name," ",ut.user_last_name) as userName',
                                                    ]);
            $scheduledLessonSearch->addCondition('sld.sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
            $scheduledLessonSearch->addCondition('slns.sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        }
        $resultSet =  $scheduledLessonSearch->getResultSet();
        $lessonData = FatApp::getDb()->fetch($resultSet);
        
        if(empty($lessonData)) {
           return array();
        }
        return  $lessonData;
    }

    
}
