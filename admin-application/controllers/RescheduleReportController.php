<?php
class RescheduleReportController extends AdminBaseController
{
    private $canView;
    
    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId(); // should check if repeated
        $this->canView = $this->objPrivilege->canViewRescheduleReport($this->admin_id, true);
        $this->set("canView", $this->canView);
    }

    public function index($orderDate = '')
    {
        $this->objPrivilege->canViewRescheduleReport();
        $frmSearch = $this->getSearchForm($orderDate);
        $this->set('frmSearch', $frmSearch);
        $this->set('orderDate', $orderDate);
        $this->_template->render();
    }

    public function search()
    {
        $this->objPrivilege->canViewRescheduleReport();
        $db = FatApp::getDb();
        $srchFrm = $this->getSearchForm();
        $post = $srchFrm->getFormDataFromArray(FatApp::getPostedData());
        
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : intval($post['page']);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 1);
        $srch = new ScheduledLessonSearch(false, false);
        $srch->joinUserLessonData();
        $srch->addMultipleFields(array('user_id, CONCAT(user_first_name, " ", user_last_name, " ( ", credential_email, " )") as user_name', 'credential_email', 'user_is_teacher', 'user_is_learner'));
        
        if (isset($post['op_teacher_id']) and $post['op_teacher_id'] > 0) {
            $srch->addCondition('lesstslog_updated_by_user_id', '=', $post['op_teacher_id']);
        }

        if (isset($post['op_learner_id']) and $post['op_learner_id'] > 0) {
            $srch->addCondition('lesstslog_updated_by_user_id', '=', $post['op_learner_id']);
        }

        $reschedule_from = FatApp::getPostedData('reschedule_from', FatUtility::VAR_DATE, '');
        if (!empty($reschedule_from)) {
            $srch->addCondition('lesstslog_added_on', '>=', $reschedule_from . ' 00:00:00');
        }

        $reschedule_to = FatApp::getPostedData('reschedule_to', FatUtility::VAR_DATE, '');
        if (!empty($reschedule_to)) {
            $srch->addCondition('lesstslog_added_on', '<=', $reschedule_to . ' 23:59:59');
        }
        $rescheduled_no = FatApp::getPostedData('rescheduled_no', FatUtility::VAR_INT, 0);
        if ($rescheduled_no > 0) {
            $srch->addHaving('teacherRescheduledLessons', '=', $rescheduled_no, 'AND');
        }

        $cancelled_no = FatApp::getPostedData('cancelled_no', FatUtility::VAR_INT, 0);
        if ($cancelled_no > 0) {
            $srch->addHaving('teacherCancelledLessons', '=', $cancelled_no, 'AND');
        }
        
        $srch->setPageSize($pagesize);
        $srch->setPageNumber($page);
        $srch->addOrder('teacherRescheduledLessons', 'DESC');
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $teachersList = $db->fetchAll($rs);
        $totalRecords = $srch->recordCount();
        
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
        $frm          = new Form('frmRescheduledReportSearch');
        $arr_options  = array(
            '-1' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + applicationConstants::getYesNoArr($this->adminLangId);
        $arr_options1  = array(
            '-2' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + Order::getPaymentStatusArr($this->adminLangId);
        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher', '', array(
            'id' => 'teacher',
            'autocomplete' => 'off'
        ));

        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner', '', array(
            'id' => 'learner',
            'autocomplete' => 'off'
        ));

        $frm->addDateField(Label::getLabel('LBL_Date_From', $this->adminLangId), 'reschedule_from', '', array(
            'readonly' => 'readonly'
        ));
        $frm->addDateField(Label::getLabel('LBL_Date_To', $this->adminLangId), 'reschedule_to', '', array(
            'readonly' => 'readonly'
        ));

        $rescheduleFld = $frm->addTextBox('', 'rescheduled_no', '', array('placeholder' => 'Rescheduled Times' ));
        $rescheduleFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $rescheduleFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));

        $cancelledFld = $frm->addTextBox('', 'cancelled_no', '', array('placeholder' => 'Cancelled Times' ));
        $cancelledFld->requirements()->setRegularExpressionToValidate("^[1-9]\d*$");
        $cancelledFld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_Number_Greater_Than_0'));

        $frm->addHiddenField('', 'page', 1);
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
        $this->objPrivilege->canViewRescheduleReport();
        $pagesize   = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 1);
        $searchForm = $this->getSearchForm();
        $data       = FatApp::getPostedData();
        $page       = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray(FatApp::getPostedData());
        $parameters = FatApp::getParameters();
        //echo "<pre>";print_r($post);die;
        
        $db = FatApp::getDb();
        $srch = LessonStatusLog::getSearchObject();
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'lsl.lesstslog_updated_by_user_id = u.user_id', 'u');
        $srch->joinTable(ScheduledLesson::DB_TBL, 'LEFT JOIN', 'lsl.lesstslog_slesson_id  = sl.slesson_id', 'sl');
        $srch->joinTable(ScheduledLessonDetails::DB_TBL, 'LEFT JOIN', 'sld.sldetail_slesson_id = sl.slesson_id', 'sld');
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'sl.slesson_teacher_id = tu.user_id', 'tu');
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'sld. sldetail_learner_id = su.user_id', 'su');
        
        $srch->addMultipleFields(array(
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
        
        
        if ((isset($parameters[0]) && $parameters[0] > 0)) {
            $lesreschlog_reschedule_by = FatUtility::int($parameters[0]);
            $srch->addCondition('lesstslog_updated_by_user_id', '=', $lesreschlog_reschedule_by); //need to make it dynamic
        }

        if (isset($post['report_user_id']) && $post['report_user_id'] > 0) {
            $lesreschlog_reschedule_by = FatUtility::int($post['report_user_id']);
            $srch->addCondition('lesstslog_updated_by_user_id', '=', $lesreschlog_reschedule_by); //need to make it dynamic
        }

        $reschedule_from = FatApp::getPostedData('reschedule_from', FatUtility::VAR_DATE, '');
        if (!empty($reschedule_from)) {
            $srch->addCondition('lesstslog_added_on', '>=', $reschedule_from . ' 00:00:00');
        }

        $reschedule_to = FatApp::getPostedData('reschedule_to', FatUtility::VAR_DATE, '');
        if (!empty($reschedule_to)) {
            $srch->addCondition('lesstslog_added_on', '<=', $reschedule_to . ' 23:59:59');
        }

        $rescheduled_no = FatApp::getPostedData('rescheduled_no', FatUtility::VAR_INT, 0);
        if ($rescheduled_no > 0) {
            $srch->addHaving('mysql_func_count(lesstslog_id)', '>=', $rescheduled_no, 'AND', true);
        }

        if (isset($parameters[1]) && $parameters[1] == LessonStatusLog::CANCELLED_REPORT) {
            $reportType = FatUtility::int($parameters[1]);
            $srch->addCondition('lesstslog_current_status', '=', ScheduledLesson::STATUS_CANCELLED);
        }

        if (isset($parameters[1]) && $parameters[1] == LessonStatusLog::NOT_CANCELLED_REPORT) {
            $reportType = FatUtility::int($parameters[1]);
            $srch->addCondition('lesstslog_current_status', '!=', ScheduledLesson::STATUS_CANCELLED); 
        }

        if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::CANCELLED_REPORT) {
            $reportType = FatUtility::int($post['report_type']);
            $srch->addCondition('lesstslog_current_status', '=', ScheduledLesson::STATUS_CANCELLED);
        }

        if (isset($post['report_type']) && $post['report_type'] == LessonStatusLog::NOT_CANCELLED_REPORT) {
            $reportType = FatUtility::int($post['report_type']);
            $srch->addCondition('lesstslog_current_status', '!=', ScheduledLesson::STATUS_CANCELLED);
        }

        //$srch->doNotCalculateRecords();
        //$srch->doNotLimitRecords();
        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $rs = $srch->getResultSet();
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        //echo "<pre>";print_r($srch->getQuery());die;goToNextPage
        $rs = $srch->getResultSet();
        $lessons = $db->fetchAll($rs);
        //echo $page;
        
        $statusArr = ScheduledLesson::getStatusArr();
        $this->set('lessons', $lessons);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
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
        $this->objPrivilege->canViewRescheduleReport();
        $searchForm = $this->getSearchForm();
        $post = $searchForm->getFormDataFromArray(FatApp::getPostedData());
        $parameters = FatApp::getParameters();
        
        $db = FatApp::getDb();
        $srch = LessonStatusLog::getSearchObject();
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'lsl.lesstslog_updated_by_user_id = u.user_id', 'u');
        $srch->joinTable(ScheduledLesson::DB_TBL, 'LEFT JOIN', 'lsl.lesstslog_slesson_id  = sl.slesson_id', 'sl');
        $srch->joinTable(ScheduledLessonDetails::DB_TBL, 'LEFT JOIN', 'sld.sldetail_slesson_id = sl.slesson_id', 'sld');
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'sl.slesson_teacher_id = tu.user_id', 'tu');
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'sld. sldetail_learner_id = su.user_id', 'su');
        
        $srch->addMultipleFields(array(
                'u.user_id,
                slesson_status,
                slesson_id,
                slesson_date,
                sldetail_order_id,
                CONCAT(tu.user_first_name, " ", tu.user_last_name) as ExpertName,
                CONCAT(u.user_first_name, " ", u.user_last_name) as RescheduledBy, 
                CONCAT(su.user_first_name, " ", su.user_last_name) as StudentName,
                lesstslog_current_status,
                date(lesstslog_prev_start_date,,lesstslog_prev_start_time) as StartTime,
                lesstslog_prev_start_time,
                lesstslog_prev_end_date,
                lesstslog_prev_end_time, 
                lesstslog_comment,
                lesstslog_added_on,
                lesstslog_id
                '));
        
        
        if (isset($parameters[0]) && $parameters[0] > 0) {
            $lesreschlog_reschedule_by = FatUtility::int($parameters[0]);
            $srch->addCondition('lesstslog_updated_by_user_id', '=', $lesreschlog_reschedule_by); //need to make it dynamic
        }

        $reschedule_from = FatApp::getPostedData('reschedule_from', FatUtility::VAR_DATE, '');
        if (!empty($reschedule_from)) {
            $srch->addCondition('lesstslog_added_on', '>=', $reschedule_from . ' 00:00:00');
        }

        $reschedule_to = FatApp::getPostedData('reschedule_to', FatUtility::VAR_DATE, '');
        if (!empty($reschedule_to)) {
            $srch->addCondition('lesstslog_added_on', '<=', $reschedule_to . ' 23:59:59');
        }

        $rescheduled_no = FatApp::getPostedData('rescheduled_no', FatUtility::VAR_INT, 0);
        if ($rescheduled_no > 0) {
            $srch->addHaving('mysql_func_count(lesstslog_id)', '>=', $rescheduled_no, 'AND', true);
        }

        $reportName = '';
        $extraLabelArr = array(
            Label::getLabel('Date of Rescheduled/Cancellation', $this->adminLangId),
            Label::getLabel('Rescheduled/Cancelled by', $this->adminLangId),
            Label::getLabel('Reason of Rescheduled/Cancellation', $this->adminLangId)
        );
        if (isset($parameters[1]) && $parameters[1] == LessonStatusLog::CANCELLED_REPORT) {
            $reportType = FatUtility::int($parameters[1]);
            $reportName = 'Cancelled_';
            $extraLabelArr = array(
                Label::getLabel('Date of Cancellation', $this->adminLangId),
                Label::getLabel('Cancelled by', $this->adminLangId),
                Label::getLabel('Reason of Cancellation', $this->adminLangId)
            );
            $srch->addCondition('lesstslog_current_status', '=', ScheduledLesson::STATUS_CANCELLED);
        }

        if (isset($parameters[1]) && $parameters[1] == LessonStatusLog::NOT_CANCELLED_REPORT) {
            $reportType = FatUtility::int($parameters[1]);
            $reportName = 'Rescheduled_';
            $extraLabelArr = array(
                Label::getLabel('Date of Rescheduled', $this->adminLangId),
                Label::getLabel('Rescheduled by', $this->adminLangId),
                Label::getLabel('Reason of Reschedule', $this->adminLangId)
            );
            $srch->addCondition('lesstslog_current_status', '!=', ScheduledLesson::STATUS_CANCELLED); 
        }

        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        
        $sheetData = array();
        $arr = array(
                    Label::getLabel('LBL_Sr_No', $this->adminLangId),
                    Label::getLabel('Expert Name', $this->adminLangId),
                    Label::getLabel('Student Name', $this->adminLangId),
                    Label::getLabel('Order ID', $this->adminLangId),
                    Label::getLabel('Lesson ID', $this->adminLangId),
                    Label::getLabel('Previous Scheduled Start Date', $this->adminLangId),
                    Label::getLabel('Previous Scheduled Start Time', $this->adminLangId),
                    Label::getLabel('Previous Scheduled End Date', $this->adminLangId),
                    Label::getLabel('Previous Scheduled End Time', $this->adminLangId),
                    Label::getLabel('Lesson Status', $this->adminLangId),
                    Label::getLabel('Action Type', $this->adminLangId)
                );
        
                $arr = array_merge($arr, $extraLabelArr);
                array_push($sheetData, $arr);
        $statusArr = ScheduledLesson::getStatusArr();

        $count = 1;
        while ($row = $db->fetch($rs)) {
            $arr = array(
                    $count,
                    $row['ExpertName'],
                    $row['StudentName'],
                    $row['sldetail_order_id'],
                    $row['slesson_id'],
                    $row['lesstslog_prev_start_date'],
                    $row['lesstslog_prev_start_time'],
                    $row['lesstslog_prev_end_date'],
                    $row['lesstslog_prev_end_time'],
                    $statusArr[$row['slesson_status']],
                    $row['lesstslog_current_status'],
                    $row['lesstslog_added_on'],
                    $row['RescheduledBy'],
                    $row['lesstslog_comment'],
                );
            array_push($sheetData, $arr);
            $count++;
        }
        
        CommonHelper::convertToCsv($sheetData, $reportName.'Report_'.date("d-M-Y").'.csv', ',');
        exit;
    }
}
