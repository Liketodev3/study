<?php
class LessonspaceController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function launch(int $lessonId = 0, int $isTeacher = 0)
    {
        $scheduledLessonSearch= new ScheduledLessonSearch(false);
        $scheduledLessonSearch->joinOrder();
        $scheduledLessonSearch->joinOrderProducts();
        $scheduledLessonSearch->joinTeacher();
        $scheduledLessonSearch->joinLearner();
        $scheduledLessonSearch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        if($isTeacher == applicationConstants::YES) {
            $scheduledLessonSearch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
            $scheduledLessonSearch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        }else{
            $scheduledLessonSearch->addCondition('sld.sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
            $scheduledLessonSearch->addCondition('slns.sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        }
        

    }

    
}
