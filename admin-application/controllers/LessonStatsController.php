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
            
            if ($post['rescheduled_no'] > 0) {
                $srch->addHaving('teacherRescheduledLessons', '<=', $post['rescheduled_no'], 'AND');
            }

            if ($post['cancelled_no'] > 0) {
                $srch->addHaving('teacherCancelledLessons', '<=', $post['cancelled_no'], 'AND');
            }

            if (!empty($post['reschedule_from']) && !empty($post['reschedule_to'])) {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00" 
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as teacherCancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00" 
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as teacherRescheduledLessons');
            } else if (!empty($post['reschedule_from'])) {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00") as teacherCancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` >= "'.$post['reschedule_from'] .' 00:00:00") as teacherRescheduledLessons');
            } else if (!empty($post['reschedule_to'])) {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" 
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as teacherCancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'"
                    AND `lesstslog_added_on` <= "'.$post['reschedule_to'] .' 23:59:59") as teacherRescheduledLessons');
            } else {
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status = "'.ScheduledLesson::STATUS_CANCELLED .'" ) as teacherCancelledLessons');
                $srch->addFld('(select COUNT(*) from '. LessonStatusLog::DB_TBL . ' WHERE lesstslog_updated_by_user_id = user_id 
                    AND lesstslog_current_status != "'.ScheduledLesson::STATUS_CANCELLED .'" ) as teacherRescheduledLessons');
            }
        }
        
        $srch->setPageSize($pagesize);
        $srch->setPageNumber($page);
        $srch->addOrder('teacherRescheduledLessons', 'DESC');
        
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

        $rescheduleFld = $frm->addTextBox(Label::getLabel('LBL_Rescheduled_Times', $this->adminLangId), 'rescheduled_no', '', array());
        $rescheduleFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $rescheduleFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));

        $cancelledFld = $frm->addTextBox(Label::getLabel('LBL_Cancelled_Times', $this->adminLangId), 'cancelled_no', '', array());
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
        //echo $srch->getQuery();die;
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
        
        
        if (!empty($post['report_user_id'])) {
           $lessonStatsSearch->addCondition('lesstslog_updated_by_user_id', '=', $post['report_user_id']);
        }

        if (!empty($post['reschedule_from'])) {
            $lessonStatsSearch->addCondition('lesstslog_added_on', '>=', $post['reschedule_from'] . ' 00:00:00');
        }

        if (!empty($post['reschedule_to'])) {
            $lessonStatsSearch->addCondition('lesstslog_added_on', '<=', $post['reschedule_to'] . ' 23:59:59');
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

