<?php
class TeacherIssueReportedController extends TeacherBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $this->_template->addJs('js/teacherLessonCommon.js');
        $frmSrch = $this->getSearchForm();
        $this->set('frmSrch', $frmSrch);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render();
    }

    public function search()
    {
        $frmSrch = $this->getSearchForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        $referer = CommonHelper::redirectUserReferer(true);
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }
        $srch = IssuesReported::getSearchObject();
        $srch->addCondition('slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addMultipleFields(array(
            'i.*',
            'user_first_name',
            'slesson_id',
            'sldetail_learner_id',
            'slesson_date',
            'slesson_start_time',
            'user_timezone',
            'slesson_status',
            'slesson_slanguage_id',
            'op_lesson_duration',
            'op_lpackage_is_free_trial',
        ));
        $srch->addOrder('issrep_status', 'ASC');
        $srch->addOrder('issrep_id', 'DESC');
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $rs = $srch->getResultSet();
        $issuesReported = FatApp::getDb()->fetchAll($rs);
        $this->set('issuesReported', $issuesReported);
        /* [ */
        $totalRecords = $srch->recordCount();
        $pagingArr = array(
            'pageCount' => $srch->pages(),
            'page' => $page,
            'pageSize' => $pageSize,
            'recordCount' => $totalRecords,
        );
        $this->set('postedData', $post);
        $this->set('pagingArr', $pagingArr);
        $startRecord = ($page - 1) * $pageSize + 1;
        $endRecord = $page * $pageSize;
        if ($totalRecords < $endRecord) {
            $endRecord = $totalRecords;
        }
        $this->set('startRecord', $startRecord);
        $this->set('endRecord', $endRecord);
        $this->set('totalRecords', $totalRecords);
        $this->set('referer', $referer);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->set('issues_options', IssueReportOptions::getOptionsArray($this->siteLangId));
        /* ] */
        $this->_template->render(false, false);
    }

    public function issueDetails($issueLessonId)
    {
        $issueLessonId = FatUtility::int($issueLessonId);
        $srch = IssuesReported::getSearchObject();
        $srch->addCondition('issrep_slesson_id', '=', $issueLessonId);
        $srch->addMultipleFields(array(
            'i.*',
            'user_first_name',
            'slesson_id',
            'slesson_learner_id',
            'slesson_date',
            'slesson_start_time',
            'user_timezone',
            'slesson_status',
            'slesson_slanguage_id',
            'op_lesson_duration',
            'op_lpackage_is_free_trial',
        ));
        $srch->addOrder('issrep_id', 'ASC');
        $rs = $srch->getResultSet();
        $issuesReportedDetails = FatApp::getDb()->fetchAll($rs);
        $this->set('issueDeatils', $issuesReportedDetails);
        $this->set('issues_options', IssueReportOptions::getOptionsArray($this->siteLangId));
        $this->set('resolve_type_options', IssuesReported::RESOLVE_TYPE);
        $this->_template->render(false, false);
    }

    public function resolveIssue($issueId, $slesson_id)
    {
        $issueId = FatUtility::int($issueId);
        $frm = $this->getIssueReportedFrm();
        $frm->fill(array('issue_id' => $issueId, 'slesson_id' => $slesson_id));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
    public static function getIssueDetails($issueId)
    {
        $srch = IssuesReported::getSearchObject();
        $srch->addCondition('issrep_id', '=', $issueId);
        $srch->addMultipleFields(array(
            'i.*'
        ));
        $rs = $srch->getResultSet();
        $issuesReportedDetails = FatApp::getDb()->fetch($rs);
        return $issuesReportedDetails;
    }

    public function issueResolveStepTwo($issueId, $slesson_id)
    {
        $issueId = FatUtility::int($issueId);
        $issuesReportedDetails = self::getIssueDetails($issueId);
        $frm = $this->getIssueReportedFrmStepTwo();
        $frm->fill(array('issue_id' => $issueId, 'slesson_id' => $slesson_id));
        $this->set('frm', $frm);
        $this->set('issueDeatils', $issuesReportedDetails);
        $this->set('issues_options', IssueReportOptions::getOptionsArray($this->siteLangId));
        $this->_template->render(false, false);
    }

    public function issueResolveSetup()
    {
        $frm = $this->getIssueReportedFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $_reason_ids = $post['issues_to_report'];
        if (empty($_reason_ids)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Please_Choose_Issue_to_Report'));
        }
        $lessonId = $post['slesson_id'];
        /* [ check If Already resolved */
        if (IssuesReported::isAlreadyResolved($post['issue_id'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Issue_Already_Resolved_/_inprogress'));
        }
        /* ] */
        $reportedArr = array();
        $reportedArr['issrep_status'] = IssuesReported::STATUS_PROGRESS;
        $reportedArr['issrep_resolve_comments'] = $post['issue_reported_msg'];
        $reportedArr['issrep_issues_resolve'] = implode(',', $_reason_ids);
        $record = new IssuesReported($post['issue_id']);
        $record->assignValues($reportedArr);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError($record->getError());
        }
        $reason_html = '';
        $issues_options = IssueReportOptions::getOptionsArray($this->siteLangId);
        foreach ($_reason_ids as $_id) {
            $reason_html .= $issues_options[$_id].'<br />';
        }
        //Message::addMessage(Label::getLabel( 'LBL_Lesson_Issue_Updated_Successfully!', $this->siteLangId ));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Issue_Reported_Successfully!'));
    }

    public function issueResolveSetupStepTwo()
    {
        $frm = $this->getIssueReportedFrmStepTwo();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lessonId = $post['slesson_id'];
        $issueId = $post['issue_id'];
        $issue_resolve_type = $post['issue_resolve_type'];
        $issuesReportedDetails = self::getIssueDetails($issueId);
        $lesson_status = ScheduledLesson::STATUS_NEED_SCHEDULING;
        $paymentStatus = Transaction::STATUS_DECLINED;
        $_refund_percentage = 0;
        $refundAmount = 0;
        $refundAmountTeacher = 0;
        $lessonAmount = 0;
        $transactionDetails = Transaction::transactionDetailsWithLesson($lessonId);
         if(!empty($transactionDetails['order_total']) && !empty($transactionDetails['total_lessons'])) {
             $lessonAmount = $transactionDetails['order_total'] / $transactionDetails['total_lessons'] ;
         }
        $lessonAmountTeacher = $transactionDetails['utxn_credit'];
        $lerner_id = $transactionDetails['slesson_learner_id'];
        $teacherPayment = $lessonAmount;
        $transactionComment = $transactionDetails['utxn_comments'];
        switch ($issue_resolve_type) {
            case 1: // Reset Lesson to: Unscheduled
                $lesson_status = ScheduledLesson::STATUS_NEED_SCHEDULING;
                $paymentStatus = Transaction::STATUS_COMPLETED;
                $transactionComment = sprintf(Label::getLabel('LBL_LessonId:_%s_Payment_Received', $this->siteLangId), $lessonId);

            break;

            case 2: // Mark Lesson as: Completed
                $lesson_status = ScheduledLesson::STATUS_COMPLETED;
                $paymentStatus = Transaction::STATUS_COMPLETED;
                $transactionComment = sprintf(Label::getLabel('LBL_LessonId:_%s_Payment_Received', $this->siteLangId), $lessonId);
            break;

            case 3: // Mark Lesson as: Completed and issue a student a 50% refund
                $lesson_status = ScheduledLesson::STATUS_COMPLETED;
                $paymentStatus = Transaction::STATUS_COMPLETED;
                $_refund_percentage = 50;
                $transactionComment = sprintf(Label::getLabel('LBL_LessonId:_%s_Payment_Received_(_%s&percnt;_Refunded_)', $this->siteLangId), $lessonId, 50);
            break;

            case 4: // Mark Lesson as: Completed and issue a student a 100% refund
                $lesson_status = ScheduledLesson::STATUS_COMPLETED;
                $paymentStatus = Transaction::STATUS_COMPLETED;
                $_refund_percentage = 100;
                $transactionComment = sprintf(Label::getLabel('LBL_LessonId:_%s_Payment_Received_(_%s&percnt;_Refunded_)', $this->siteLangId), $lessonId, 100);
            break;
        }

        if ($_refund_percentage > 0) {
            $tObj = new Transaction($lerner_id);
            $data = array(
                'utxn_user_id' => $lerner_id,
                'utxn_date' => date('Y-m-d H:i:s'),
                'utxn_comments' => $transactionComment,
                'utxn_status' => Transaction::STATUS_COMPLETED,
                'utxn_type' => Transaction::TYPE_ISSUE_REFUND,
                'utxn_slesson_id' => $lessonId
            );

            $refundAmount = $lessonAmount *  $_refund_percentage / 100;
            $data['utxn_credit'] = $refundAmount;
            $refundAmountTeacher = $lessonAmountTeacher * $_refund_percentage / 100;
            if (!$tObj->addTransaction($data)) {
                Message::addErrorMessage($tObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
        }

        $tObjTeach = new Transaction(UserAuthentication::getLoggedUserId(), $transactionDetails['utxn_id']);
        $teachData = array(
            'utxn_user_id' => UserAuthentication::getLoggedUserId(),
            'utxn_date' => date('Y-m-d H:i:s'),
            'utxn_comments' => $transactionComment,
            'utxn_status' => $paymentStatus,
            'utxn_slesson_id' => $lessonId,
            'utxn_debit' => $refundAmountTeacher
        );

        if (!$tObjTeach->addTransaction($teachData)) {
            Message::addErrorMessage($tObjTeach->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $reportedArr = array();
        $reportedArr['issrep_status'] = IssuesReported::STATUS_RESOLVED;
        $reportedArr['issrep_issues_resolve_type'] = $post['issue_resolve_type'];
        $reportedArr['issrep_updated_on'] = date('Y-m-d H:i:s');
        $record = new IssuesReported($issueId);
        $record->assignValues($reportedArr);

        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError($record->getError());
        }

        $sLessonObj = new ScheduledLesson($lessonId);
        $lessonData = array();

        if ($issue_resolve_type == 1) {
            $lessonData['slesson_date'] = '';
            $lessonData['slesson_end_date'] = '';
            $lessonData['slesson_start_time'] = '';
            $lessonData['slesson_end_time'] = '';
            $lessonData['slesson_teacher_join_time'] = '';
            $lessonData['slesson_learner_join_time'] = '';
            $lessonData['slesson_teacher_end_time'] = '';
            $lessonData['slesson_learner_end_time'] = '';
            $lessonData['slesson_ended_by'] = 0;
            $lessonData['slesson_ended_on'] = '';
            $lessonData['slesson_reminder_one'] = 0;
            $lessonData['slesson_reminder_two'] = 0;
        }
        $lessonData['slesson_status'] = $lesson_status;
        $sLessonObj->assignValues($lessonData);
        if (!$sLessonObj->save()) {
            Message::addErrorMessage($sLessonObj->getError());
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        //$sLessonObj->changeLessonStatus( $lessonId, $lesson_status );
        $reason_html = '';
        $teacherReasonHtml = '';
        $_reason_ids = $issuesReportedDetails['issrep_issues_to_report'];
        $_reason_ids = explode(',', $_reason_ids);
        $teaher_reason_ids = $issuesReportedDetails['issrep_issues_resolve'];
        $teaher_reason_ids = explode(',', $teaher_reason_ids);
        $issues_options = IssueReportOptions::getOptionsArray($this->siteLangId);
        foreach ($_reason_ids as $_id) {
            $reason_html .= $issues_options[$_id].'<br />';
        }
        foreach ($teaher_reason_ids as $_trId) {
            $teacherReasonHtml .= $issues_options[$_trId].'<br />';
        }
        $resolveArray = IssuesReported::RESOLVE_TYPE;
        $tpl = 'teacher_issue_resolved_email';
        $vars = array(
            '{learner_name}' => $transactionDetails['learnerFullName'],
            '{teacher_name}' => $transactionDetails['teacherFullName'],
            '{lesson_name}' => $transactionDetails['teacherTeachLanguageName'],
            '{lesson_issue_reason}' => $reason_html,
            '{teacher_issue_reason}' => $teacherReasonHtml,
            '{learner_comment}' => $issuesReportedDetails['issrep_comment'],
            '{teacher_comment}' => $issuesReportedDetails['issrep_resolve_comments'],
            '{lesson_date}' => $transactionDetails['slesson_date'],
            '{lesson_start_time}' => $transactionDetails['slesson_start_time'],
            '{lesson_end_time}' => $transactionDetails['slesson_end_time'],
            '{issue_resolve_type}' => $resolveArray[$issue_resolve_type]
        );

        if (!EmailHandler::sendMailTpl($transactionDetails['learner_email'], $tpl, $this->siteLangId, $vars)) {
            Message::addErrorMessage(Label::getLabel('LBL_Mail_not_sent!', $this->siteLangId));
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }

        $userNotification = new UserNotifications($lerner_id);
        $userNotification->sendIssueRefundNotification($lessonId, IssuesReported::ISSUE_RESOLVE_NOTIFICATION);
        Message::addMessage(Label::getLabel('LBL_Lesson_Issue_Resolved_Successfully!', $this->siteLangId));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Issue_Resolved_Successfully!'));
    }

    private function getIssueReportedFrm()
    {
        $frm = new Form('issueResolveFrm');
        $arr_options = IssueReportOptions::getOptionsArray($this->siteLangId);
        $fldIssue = $frm->addCheckBoxes(Label::getLabel('LBL_Issue_To_Resolve'), 'issues_to_report', $arr_options, array());
        $fldIssue->requirement->setSelectionRange(1, count($arr_options));
        $fldIssue->requirement->setCustomErrorMessage(Label::getLabel('LBL_Issue_To_Resolve').' '.Label::getLabel('LBL_is_Mandatory'));
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'issue_reported_msg', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'issue_id');
        $fld = $frm->addHiddenField('', 'slesson_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Next'));
        return $frm;
    }

    private function getIssueReportedFrmStepTwo()
    {
        $frm = new Form('issueResolveFrmStepTwo');
        $arr_options = IssuesReported::RESOLVE_TYPE;
        $fldIssue = $frm->addRadioButtons(Label::getLabel('LBL_How_would_you_like_to_resolve_this?'), 'issue_resolve_type', $arr_options);
        $fldIssue->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'issue_id');
        $fld = $frm->addHiddenField('', 'slesson_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Resolve'));
        return $frm;
    }
}
