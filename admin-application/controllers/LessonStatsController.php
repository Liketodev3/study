<?php
class LessonStatsController extends AdminBaseController
{
    private $canView;
    
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewLessonStatsReport($this->admin_id);
        $this->set("canView", $this->canView);
    }

    public function index()
    {
        $frmSearch = $this->getSearchForm();
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }

    public function search()
    {
        $db = FatApp::getDb();
        $srchFrm = $this->getSearchForm();
        $post = $srchFrm->getFormDataFromArray(FatApp::getPostedData());
        
        if (false === $post) {
            FatUtility::dieWithError($srchFrm->getValidationErrors()); // need to discuss
        }
        
        $srch = new ScheduledLessonSearch(false, false);
        $srch->joinUserLessonData();
        $srch->addMultipleFields(array('user_id, CONCAT(user_first_name, " ", user_last_name, " ( ", credential_email, " )") as user_name', 
        'credential_email', 'user_is_teacher', 'user_is_learner'));
        
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $page = $post['page'];
        
        if ($post) {
            
            if ($post['op_teacher_id'] > 0) {
                $srch->addCondition('lesstslog_updated_by_user_id', '=', $post['op_teacher_id']);
            }
            
            if ($post['op_learner_id'] > 0) {
                $srch->addCondition('lesstslog_updated_by_user_id', '=', $post['op_learner_id']);
            }
            
            if (!empty($post['reschedule_from'])) {
                $srch->addCondition('lesstslog_added_on', '>=', $post['reschedule_from'] . ' 00:00:00');
            }

            if (!empty($post['reschedule_to'])) {
                $srch->addCondition('lesstslog_added_on', '<=', $post['reschedule_to'] . ' 23:59:59');
            }

            if ($post['upcoming_no'] > 0) {
                $srch->addHaving('upcomingLessons', '<=', $post['upcoming_no'], 'AND');
            }

            if ($post['completed_no'] > 0) {
                $srch->addHaving('completedLessons', '<=', $post['completed_no'], 'AND');
            }
            
            if ($post['rescheduled_no'] > 0) {
                $srch->addHaving('rescheduledLessons', '<=', $post['rescheduled_no'], 'AND');
            }

            if ($post['cancelled_no'] > 0) {
                $srch->addHaving('cancelledLessons', '<=', $post['cancelled_no'], 'AND');
            }

            if (!empty($post['reschedule_from']) && !empty($post['reschedule_to'])) {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00" 
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as cancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00" 
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as rescheduledLessons');
                    
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_COMPLETED .'" 
                    AND `slesson_added_on` >= "'.$post['reschedule_from'] .' 00:00:00" 
                    AND `slesson_added_on` <= "'.$post['reschedule_to'] .' 23:59:59" ) as completedLessons');
                
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_SCHEDULED .'" AND CONCAT(slesson_date, " ", slesson_start_time) >= now()
                    AND `slesson_added_on` >= "'.$post['reschedule_from'] .' 00:00:00"
                    AND `slesson_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as upcomingLessons');

            } else if (!empty($post['reschedule_from'])) {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00") as cancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00") as rescheduledLessons');
                    
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_COMPLETED .'" 
                    AND `slesson_added_on` >= "'.$post['reschedule_from'] .' 00:00:00" ) as completedLessons');
                
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_SCHEDULED .'" AND CONCAT(slesson_date, " ", slesson_start_time) >= now()
                    AND `slesson_added_on` >= "'.$post['reschedule_from'] .' 00:00:00") as upcomingLessons');

            } else if (!empty($post['reschedule_to'])) {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as cancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'"
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as rescheduledLessons');

                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_COMPLETED .'" 
                    AND `slesson_added_on` <= "'.$post['reschedule_to'] .' 23:59:59" ) as completedLessons');
                
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_SCHEDULED .'" AND CONCAT(slesson_date, " ", slesson_start_time) >= now()
                    AND `slesson_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as upcomingLessons');
            } else {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" ) as cancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'" ) as rescheduledLessons');
                
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_COMPLETED .'" ) as completedLessons');
                
                $srch->addFld('(select COUNT(*) from '. ScheduledLesson::DB_TBL . ' LEFT JOIN '. ScheduledLessonDetails::DB_TBL . ' 
                    ON slesson_id = sldetail_slesson_id WHERE (slesson_teacher_id = user_id OR sldetail_learner_id = user_id ) 
                    AND slesson_status = "'.ScheduledLesson::STATUS_SCHEDULED .'" AND CONCAT(slesson_date, " ", slesson_start_time) >= now()) 
                    as upcomingLessons');
            }
        }
        
        $srch->setPageSize($pagesize);
        $srch->setPageNumber($page);
        $srch->addOrder('rescheduledLessons', 'DESC');
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $teachersList = $db->fetchAll($rs);
        
        $this->set("arr_listing", $teachersList);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    private function getSearchForm()
    {
        $frm = new Form('frmRescheduledReportSearch');
        $keyword = $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher', '', array(
            'id' => 'teacher',
            'autocomplete' => 'off'
        ));

        $keyword = $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner', '', array(
            'id' => 'learner',
            'autocomplete' => 'off'
        ));

        $frm->addDateField(Label::getLabel('LBL_Date_From', $this->adminLangId), 'reschedule_from', '', array(
            'readonly' => 'readonly'
        ));
        $frm->addDateField(Label::getLabel('LBL_Date_To', $this->adminLangId), 'reschedule_to', '', array(
            'readonly' => 'readonly'
        ));
        
        $rescheduleFld = $frm->addTextBox(Label::getLabel('LBL_Upcoming_Lessons_Count', $this->adminLangId), 'upcoming_no', '', array());
        $rescheduleFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $rescheduleFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));

        $cancelledFld = $frm->addTextBox(Label::getLabel('LBL_Completed_Lessons_Count', $this->adminLangId), 'completed_no', '', array());
        $cancelledFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $cancelledFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));

        $rescheduleFld = $frm->addTextBox(Label::getLabel('LBL_Rescheduled_Lessons_Count', $this->adminLangId), 'rescheduled_no', '', array());
        $rescheduleFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $rescheduleFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));

        $cancelledFld = $frm->addTextBox(Label::getLabel('LBL_Cancelled_Lessons_Count', $this->adminLangId), 'cancelled_no', '', array());
        $cancelledFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $cancelledFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));


        $frm->addHiddenField('', 'page', 1)->requirements()->setIntPositive();
        $frm->addHiddenField('', 'op_learner_id', '');
        $frm->addHiddenField('', 'op_teacher_id', '');
        $frm->addHiddenField('', 'report_type', '');
        $frm->addHiddenField('', 'report_user_id', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function viewUpcomingAndCompletedReport()
    { 
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        
        $post = $searchForm->getFormDataFromArray(FatApp::getPostedData());
        
        if (false === $post) {
            FatUtility::dieWithError($srchFrm->getValidationErrors()); // need to discuss
        }

        $db = FatApp::getDb();
        $scheduledLessonSearch = new ScheduledLessonSearch(true, true);
        $scheduledLessonSearch->joinTable(User::DB_TBL, 'LEFT JOIN', 'su.user_id = sld.sldetail_learner_id', 'su');
        $scheduledLessonSearch->joinTable(User::DB_TBL, 'LEFT JOIN', 'tu.user_id = slesson_teacher_id', 'tu');
        
        if ( $post ) {
            if ($post['report_user_id'] > 0) {
                $userCondition = $scheduledLessonSearch->addCondition('slesson_teacher_id', '=', $post['report_user_id']);
                $userCondition->attachCondition('sldetail_learner_id', '=', $post['report_user_id'], 'OR');
            }
    
            if (!empty($post['reschedule_from'])) {
                $scheduledLessonSearch->addCondition('slesson_added_on', '>=', $post['reschedule_from'] . ' 00:00:00');
            }
    
            if (!empty($post['reschedule_to'])) {
                $scheduledLessonSearch->addCondition('slesson_added_on', '<=', $post['reschedule_to'] . ' 23:59:59');
            }
    
            if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::UPCOMING_REPORT) {
                $scheduledLessonSearch->addCondition('slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
                $scheduledLessonSearch->addCondition('mysql_func_CONCAT(slns.slesson_date, " ", slns.slesson_start_time )', '>=', date('Y-m-d H:i:s'), 'AND', true);
            }
    
            if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::COMPLETED_REPORT) {
                $scheduledLessonSearch->addCondition('slesson_status', '=', ScheduledLesson::STATUS_COMPLETED);
            }
    
        }

        $scheduledLessonSearch->addMultipleFields(array(
                'su.user_id as StudentId,
                tu.user_id as TeacherID,
                slesson_status,
                slesson_id,
                slesson_date,
                sldetail_order_id,
                CONCAT(tu.user_first_name, " ", tu.user_last_name) as ExpertName,
                CONCAT(su.user_first_name, " ", su.user_last_name) as StudentName,
                CONCAT(slesson_date," ",slesson_start_time) as StartTime,
                CONCAT(slesson_end_date," ",slesson_end_time) as EndTime,
                slesson_comments,
                slesson_added_on
                '));
        
            // CONCAT(u.user_first_name, " ", u.user_last_name) as RescheduledBy, 
            
        
        //$srch->doNotCalculateRecords();
        //$srch->doNotLimitRecords();
        $page = $post['page'];
        // /echo $scheduledLessonSearch->getQuery();die;
        $scheduledLessonSearch->setPageNumber($page);
        $scheduledLessonSearch->setPageSize($pagesize);
        
        $rs = $scheduledLessonSearch->getResultSet();
        $lessons = $db->fetchAll($rs);
        
        $this->set('lessons', $lessons);
        $this->set('pageCount', $scheduledLessonSearch->pages());
        $this->set('recordCount', $scheduledLessonSearch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('report_type', $post['report_type']);
        $this->set('report_user_id', $post['report_user_id']);
        $this->set('postedData', $post);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render(false, false);
    }

    public function upcomingAndCompletedReportExport()
    { 
        $searchForm = $this->getSearchForm();
        $post = $searchForm->getFormDataFromArray(FatApp::getPostedData());
        
        if (false === $post) {
            FatUtility::dieWithError($srchFrm->getValidationErrors()); // need to discuss
        }

        $db = FatApp::getDb();
        $scheduledLessonSearch = new ScheduledLessonSearch(true, true);
        $scheduledLessonSearch->joinTable(User::DB_TBL, 'LEFT JOIN', 'su.user_id = sld.sldetail_learner_id', 'su');
        $scheduledLessonSearch->joinTable(User::DB_TBL, 'LEFT JOIN', 'tu.user_id = slesson_teacher_id', 'tu');
        
        $scheduledLessonSearch->addMultipleFields(array(
            'su.user_id as StudentId,
            tu.user_id as TeacherID,
            slesson_status,
            slesson_id,
            slesson_date,
            sldetail_order_id,
            CONCAT(tu.user_first_name, " ", tu.user_last_name) as ExpertName,
            CONCAT(su.user_first_name, " ", su.user_last_name) as StudentName,
            CONCAT(slesson_date," ",slesson_start_time) as StartTime,
            CONCAT(slesson_end_date," ",slesson_end_time) as EndTime,
            slesson_comments,
            slesson_added_on
            '));
        
        $reportName = '';
        $extraLabelArr = array();
        
        if ( $post ) {
            if ($post['report_user_id'] > 0) {
                $userCondition = $scheduledLessonSearch->addCondition('slesson_teacher_id', '=', $post['report_user_id']);
                $userCondition->attachCondition('sldetail_learner_id', '=', $post['report_user_id'], 'OR');
            }
    
            if (!empty($post['reschedule_from'])) {
                $scheduledLessonSearch->addCondition('slesson_added_on', '>=', $post['reschedule_from'] . ' 00:00:00');
            }
    
            if (!empty($post['reschedule_to'])) {
                $scheduledLessonSearch->addCondition('slesson_added_on', '<=', $post['reschedule_to'] . ' 23:59:59');
            }
    
            if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::UPCOMING_REPORT) {
                $reportName = 'Upcoming_';
                $scheduledLessonSearch->addCondition('slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
                $scheduledLessonSearch->addCondition('mysql_func_CONCAT(slns.slesson_date, " ", slns.slesson_start_time )', '>=', date('Y-m-d H:i:s'), 'AND', true);
            }
    
            if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::COMPLETED_REPORT) {
                $reportName = 'Completed_';
                $scheduledLessonSearch->addCondition('slesson_status', '=', ScheduledLesson::STATUS_COMPLETED);
            }
        }
        
        $scheduledLessonSearch->doNotCalculateRecords();
        $scheduledLessonSearch->doNotLimitRecords();
        
        $rs = $scheduledLessonSearch->getResultSet();
        
        $csvColumns = array();
        $arr = array(
                    Label::getLabel('LBL_Sr_No', $this->adminLangId),
                    Label::getLabel('LBL_Expert_Name', $this->adminLangId),
                    Label::getLabel('LBL_Student_Name', $this->adminLangId),
                    Label::getLabel('LBL_Order_ID', $this->adminLangId),
                    Label::getLabel('LBL_Lesson_ID', $this->adminLangId),
                    Label::getLabel('LBL_Start_Time', $this->adminLangId),
                    Label::getLabel('LBL_End_Time', $this->adminLangId),
                    Label::getLabel('LBL_Lesson_Status', $this->adminLangId),
                    Label::getLabel('LBL_Comments', $this->adminLangId)
                );
        
        $arr = array_merge($arr, $extraLabelArr);
        array_push($csvColumns, $arr);
        $statusArr = ScheduledLesson::getStatusArr();
        
        $rowKeys = array( 'ExpertName', 'StudentName', 'sldetail_order_id', 'slesson_id',
            'StartTime', 'EndTime', 'slesson_status', 'slesson_added_on', 
            'slesson_comments'
        );

        $placeholders = array( 'slesson_status' );
        
        CommonHelper::exportCsv($rs, $csvColumns[0], $rowKeys, $statusArr, $placeholders, $reportName.'Stats_'.date("d-M-Y").'.csv');       
        
        exit;
    }

    public function viewReport()
    { 
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        
        $post = $searchForm->getFormDataFromArray(FatApp::getPostedData());
        
        if (false === $post) {
            FatUtility::dieWithError($srchFrm->getValidationErrors()); // need to discuss
        }

        $db = FatApp::getDb();
        $lessonStatsSearch = new LessonStatsSearch();
        $lessonStatsSearch->joinDetails();
       
        if ( $post ) {
            if ($post['report_user_id'] > 0) {
                $lessonStatsSearch->addCondition('lesstslog_updated_by_user_id', '=', $post['report_user_id']);
            }
    
            if (!empty($post['reschedule_from'])) {
                $lessonStatsSearch->addCondition('lesstslog_added_on', '>=', $post['reschedule_from'] . ' 00:00:00');
            }
    
            if (!empty($post['reschedule_to'])) {
                $lessonStatsSearch->addCondition('lesstslog_added_on', '<=', $post['reschedule_to'] . ' 23:59:59');
            }
    
            if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::CANCELLED_REPORT) {
                $lessonStatsSearch->addCondition('lesstslog_current_status', '=', ScheduledLesson::STATUS_CANCELLED);
            }
    
            if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::NOT_CANCELLED_REPORT) {
                $lessonStatsSearch->addCondition('lesstslog_current_status', '!=', ScheduledLesson::STATUS_CANCELLED);
            }
    
        }

        $lessonStatsSearch->addMultipleFields(array(
                'lesstslog_updated_by_user_id,
                slesson_status,
                slesson_id,
                slesson_date,
                sldetail_order_id,
                CONCAT(tu.user_first_name, " ", tu.user_last_name) as ExpertName,
                (CASE
                    WHEN lesstslog_updated_by_user_id = sl.slesson_teacher_id THEN CONCAT(tu.user_first_name, " ", tu.user_last_name)
                    ELSE CONCAT(su.user_first_name, " ", su.user_last_name)
                END ) as RescheduledBy,
                CONCAT(su.user_first_name, " ", su.user_last_name) as StudentName,
                lesstslog_current_status,
                CONCAT(lesstslog_prev_start_date," ",lesstslog_prev_start_time) as StartTime,
                CONCAT(lesstslog_prev_end_date," ",lesstslog_prev_end_time) as EndTime,
                lesstslog_comment,
                lesstslog_added_on,
                lesstslog_id      
                '));
        
            // CONCAT(u.user_first_name, " ", u.user_last_name) as RescheduledBy, 
            
        
        //$srch->doNotCalculateRecords();
        //$srch->doNotLimitRecords();
        $page = $post['page'];
        
        $lessonStatsSearch->setPageNumber($page);
        $lessonStatsSearch->setPageSize($pagesize);
        
        $rs = $lessonStatsSearch->getResultSet();
        $lessons = $db->fetchAll($rs);
        
        $this->set('lessons', $lessons);
        $this->set('pageCount', $lessonStatsSearch->pages());
        $this->set('recordCount', $lessonStatsSearch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('report_type', $post['report_type']);
        $this->set('report_user_id', $post['report_user_id']);
        $this->set('postedData', $post);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render(false, false);
    }

    public function export()
    { 
        $searchForm = $this->getSearchForm();
        $post = $searchForm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            FatUtility::dieWithError($srchFrm->getValidationErrors()); // need to discuss
        }
        
        $db = FatApp::getDb();
        $lessonStatsSearch = new LessonStatsSearch();
        $lessonStatsSearch->joinDetails();
        
        $lessonStatsSearch->addMultipleFields(array(
                'u.user_id,
                slesson_status,
                slesson_id,
                slesson_date,
                sldetail_order_id,
                CONCAT(tu.user_first_name, " ", tu.user_last_name) as ExpertName,
                CONCAT(u.user_first_name, " ", u.user_last_name) as RescheduledBy, 
                CONCAT(su.user_first_name, " ", su.user_last_name) as StudentName,
                lesstslog_current_status,
                CONCAT(lesstslog_prev_start_date," ",lesstslog_prev_start_time) as StartTime,
                CONCAT(lesstslog_prev_end_date," ",lesstslog_prev_end_time) as EndTime,
                lesstslog_comment,
                lesstslog_added_on,
                lesstslog_id
                '));
        
        if ( $post ) {
            if ($post['report_user_id'] > 0) {
                $lessonStatsSearch->addCondition('lesstslog_updated_by_user_id', '=', $post['report_user_id']);
            }
     
            if (!empty($post['reschedule_from'])) {
                $lessonStatsSearch->addCondition('lesstslog_added_on', '>=', $post['reschedule_from'] . ' 00:00:00');
            }
     
            if (!empty($post['reschedule_to'])) {
                $lessonStatsSearch->addCondition('lesstslog_added_on', '<=', $post['reschedule_to'] . ' 23:59:59');
            }
        }
        
        $reportName = '';
        $extraLabelArr = array(
            Label::getLabel('LBL_Date_Of_Rescheduled/Cancellation', $this->adminLangId),
            Label::getLabel('LBL_Rescheduled/Cancelled_By', $this->adminLangId),
            Label::getLabel('LBL_Reason_Of_Rescheduled/Cancellation', $this->adminLangId)
        );
        if (!empty($post['report_type']) && ($post['report_type'] == LessonStatusLog::CANCELLED_REPORT)) {
            $reportName = 'Cancelled_';
            $extraLabelArr = array(
                Label::getLabel('LBL_Date_Of_Cancellation', $this->adminLangId),
                Label::getLabel('LBL_Cancelled_By', $this->adminLangId),
                Label::getLabel('LBL_Reason_Of_Cancellation', $this->adminLangId)
            );
            $lessonStatsSearch->addCondition('lesstslog_current_status', '=', ScheduledLesson::STATUS_CANCELLED);
        }

        if (!empty($post['report_type']) && ($post['report_type'] == LessonStatusLog::NOT_CANCELLED_REPORT)) {
            $reportName = 'Rescheduled_';
            $extraLabelArr = array(
                Label::getLabel('LBL_Date_Of_Rescheduled', $this->adminLangId),
                Label::getLabel('LBL_Rescheduled_By', $this->adminLangId),
                Label::getLabel('LBL_Reason_Of_Reschedule', $this->adminLangId)
            );
            $lessonStatsSearch->addCondition('lesstslog_current_status', '!=', ScheduledLesson::STATUS_CANCELLED); 
        }

        $lessonStatsSearch->doNotCalculateRecords();
        $lessonStatsSearch->doNotLimitRecords();
        
        $rs = $lessonStatsSearch->getResultSet();
        
        $csvColumns = array();
        $arr = array(
                    Label::getLabel('LBL_Sr_No', $this->adminLangId),
                    Label::getLabel('LBL_Expert_Name', $this->adminLangId),
                    Label::getLabel('LBL_Student_Name', $this->adminLangId),
                    Label::getLabel('LBL_Order_ID', $this->adminLangId),
                    Label::getLabel('LBL_Lesson_ID', $this->adminLangId),
                    Label::getLabel('LBL_Start_Time', $this->adminLangId),
                    Label::getLabel('LBL_End_Time', $this->adminLangId),
                    Label::getLabel('LBL_Lesson_Status', $this->adminLangId),
                    Label::getLabel('LBL_Action_Performed', $this->adminLangId)
                );
        
        $arr = array_merge($arr, $extraLabelArr);
        array_push($csvColumns, $arr);
        $statusArr = ScheduledLesson::getStatusArr();
        
        $rowKeys = array( 'ExpertName', 'StudentName', 'sldetail_order_id', 'slesson_id',
            'StartTime', 'EndTime', 'slesson_status', 'lesstslog_current_status',
            'lesstslog_added_on', 'RescheduledBy', 'lesstslog_comment'
        );

        $placeholders = array( 'slesson_status', 'lesstslog_current_status' );
        
        CommonHelper::exportCsv($rs, $csvColumns[0], $rowKeys, $statusArr, $placeholders, $reportName.'Stats_'.date("d-M-Y").'.csv');       
        
        // $count = 1;
        // while ($row = $db->fetch($rs)) {
        //     $arr = array(
        //             $count,
        //             $row['ExpertName'],
        //             $row['StudentName'],
        //             $row['sldetail_order_id'],
        //             $row['slesson_id'],
        //             $row['StartTime'],
        //             $row['EndTime'],
        //             $statusArr[$row['slesson_status']],
        //             $row['lesstslog_current_status'],
        //             $row['lesstslog_added_on'],
        //             $row['RescheduledBy'],
        //             $row['lesstslog_comment'],
        //         );
        //     array_push($sheetData, $arr);
        //     $count++;
        // }
        
        // CommonHelper::convertToCsv($sheetData, $reportName.'Stats_'.date("d-M-Y").'.csv', ',');
        exit;
    }
}

