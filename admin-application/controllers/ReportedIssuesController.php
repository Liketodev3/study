<?php

class ReportedIssuesController extends AdminBaseController
{

    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewIssuesReported();
    }

    public function index()
    {
        $this->set('frm', $this->getSearchForm());
        $this->_template->render();
    }

    public function escalated()
    {
        $frm = $this->getSearchForm();
        $frm->fill(['repiss_status' => ReportedIssue::STATUS_ESCLATED]);
        $this->set('frm', $frm);
        $this->_template->addJs('reported-issues/page-js/index.js');
        $this->_template->render();
    }

    public function search()
    {
        $frm = $this->getSearchForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $srch = ReportedIssue::getSearchObject();
        $srch->addMultipleFields(['repiss.repiss_id', 'repiss.repiss_title', 'repiss.repiss_slesson_id',
            'repiss.repiss_reported_on', 'repiss.repiss_reported_by', 'repiss.repiss_reported_by_type',
            'repiss.repiss_status', 'repiss.repiss_comment', 'repiss.repiss_updated_on', 'sldetail.sldetail_order_id',
            'CONCAT(user.user_first_name, " ", user.user_last_name) AS reporter_username']);
        if ($post['repiss_status'] > 0) {
            $srch->addCondition('repiss.repiss_status', '=', $post['repiss_status']);
        }
        if (!empty($post['sldetail_order_id'])) {
            $srch->addCondition('sldetail.sldetail_order_id', 'LIKE', '%' . $post['sldetail_order_id'] . '%');
        }
        if ($post['repiss_slesson_id'] != '') {
            $srch->addCondition('repiss.repiss_slesson_id', '=', $post['repiss_slesson_id']);
        }
        if ($post['sldetail_learner_id'] > 0) {
            $srch->addCondition('sldetail.sldetail_learner_id', '=', $post['sldetail_learner_id']);
        }
        if ($post['slesson_teacher_id'] > 0) {
            $srch->addCondition('slesson.slesson_teacher_id', '=', $post['slesson_teacher_id']);
        }
        $srch->setPageNumber($post['page']);
        $srch->setPageSize($post['pageSize']);
        $srch->addGroupBy('repiss.repiss_id');
        $srch->addOrder('repiss.repiss_id', 'DESC');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set('post', $post);
        $this->set("records", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false, null, false, false);
    }

    public function view($issueId)
    {
        $issue = ReportedIssue::getIssueById($issueId);
        if (empty($issue)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $this->set("issue", $issue);
        $this->_template->render(false, false);
    }

    public function actionForm($issueId)
    {
        $issue = ReportedIssue::getIssueById($issueId);
        if (empty($issue)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $logs = ReportedIssue::getIssueLogsById($issueId);
        $lastLog = end($logs);
        $frm = $this->getActionForm();
        $frm->fill([
            'reislo_repiss_id' => $issue['repiss_id'],
            'reislo_action' => $lastLog['reislo_action'] ?? ''
        ]);
        $this->set('frm', $frm);
        $this->set('logs', $logs);
        $this->set("issue", $issue);
        $this->set('statusArr', ReportedIssue::getStatusArr());
        $this->set('actionArr', ReportedIssue::getActionsArr());
        $this->_template->render(false, false);
    }

    public function setupAction()
    {
        $frm = $this->getActionForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $reportedIssue = new ReportedIssue($post['reislo_repiss_id'], $this->admin_id, ReportedIssue::USER_TYPE_SUPPORT);
        if (!$reportedIssue->setupIssueAction($post['reislo_action'], $post['reislo_comment'])) {
            FatUtility::dieJsonError($reportedIssue->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    public function transaction($slessonId, $issueId)
    {
        $slessonId = FatUtility::int($slessonId);
        if (1 > $slessonId) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $canEdit = $this->objPrivilege->canEditIssuesReported($this->admin_id, true);
        $post = FatApp::getPostedData();
        $srch = Transaction::getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'utxn.utxn_user_id = u.user_id', 'u');
        $srch->addCondition('utxn.utxn_type', '=', Transaction::TYPE_ISSUE_REFUND);
        $srch->addCondition('utxn.utxn_slesson_id', '=', $slessonId);
        $srch->addMultipleFields(['utxn.*', 'CONCAT(u.user_first_name, " " , u.user_last_name) AS username']);
        $srch->addOrder('utxn_id', 'DESC');
        $srch->addGroupBy('utxn.utxn_id');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("arr_listing", $records);
        $this->set('postedData', $post);
        $this->set('slessonId', $slessonId);
        $this->set('issueId', $issueId);
        $this->set('statusArr', Transaction::getStatusArr($this->adminLangId));
        $this->set("canEdit", $canEdit);
        $this->_template->render(false, false);
    }

    public function addLessonTransaction($lessonId, $issueId)
    {
        $this->objPrivilege->canEditIssuesReported($this->admin_id, true);
        $lessonId = FatUtility::int($lessonId);
        $issueId = FatUtility::int($issueId);
        if (1 > $lessonId || 1 > $issueId) {
            FatUtility::dieWithError($this->str_invalid_request_id);
        }
        $frm = $this->addLessonTransactionForm($this->adminLangId);
        $issRepObj = new IssuesReported($issueId);
        $srch = $issRepObj->getIssueDetails();
        $srch->addMultipleFields(["i.*", 'u.user_id', 'CONCAT(u.user_first_name, " " , u.user_last_name) AS reporter_username']);
        $issueDetail = FatApp::getDb()->fetch($srch->getResultSet());
        $frm->fill(['user_id' => $issueDetail['user_id'], 'slesson_id' => $lessonId, 'issue_id' => $issueId]);
        $reporterName = $issueDetail['reporter_username'];
        $this->set('reporterName', $reporterName);
        $this->set('lessonId', $lessonId);
        $this->set('issueId', $issueId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setupLessonTransaction()
    {
        $this->objPrivilege->canEditIssuesReported($this->admin_id, true);
        $frm = $this->addLessonTransactionForm($this->adminLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userId = FatUtility::int($post['user_id']);
        if (1 > $userId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $tObj = new Transaction($userId);
        $data = [
            'utxn_user_id' => $userId,
            'utxn_date' => date('Y-m-d H:i:s'),
            'utxn_comments' => $post['description'],
            'utxn_status' => Transaction::STATUS_COMPLETED,
            'utxn_type' => Transaction::TYPE_ISSUE_REFUND,
            'utxn_slesson_id' => $post['slesson_id']
        ];
        if ($post['type'] == Transaction::CREDIT_TYPE) {
            $data['utxn_credit'] = $post['amount'];
        }
        if ($post['type'] == Transaction::DEBIT_TYPE) {
            $data['utxn_debit'] = $post['amount'];
        }
        if (!$tObj->addTransaction($data)) {
            Message::addErrorMessage($tObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        /* send email to user[ */
        $emailNotificationObj = new EmailHandler();
        $emailNotificationObj->sendTxnNotification($tObj->getMainTableRecordId(), $this->adminLangId);
        /* ] */
        $this->set('slessonId', $post['slesson_id']);
        $this->set('issueId', $post['issue_id']);
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function addLessonTransactionForm($langId)
    {
        $frm = new Form('frmUserTransaction');
        $frm->addHiddenField('', 'user_id');
        $frm->addHiddenField('', 'slesson_id');
        $frm->addHiddenField('', 'issue_id');
        $typeArr = Transaction::getCreditDebitTypeArr($langId);
        $frm->addSelectBox(Label::getLabel('LBL_Type', $this->adminLangId), 'type', $typeArr)->requirements()->setRequired(true);
        $frm->addRequiredField(Label::getLabel('LBL_Amount', $this->adminLangId), 'amount')->requirements()->setFloatPositive();
        $frm->addTextArea(Label::getLabel('LBL_Description', $this->adminLangId), 'description')->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    public function updateStatus()
    {
        if (!$this->objPrivilege->canEditIssuesReported($this->admin_id, true)) {
            FatUtility::dieJsonError($this->unAuthorizeAccess);
        }
        $data = FatApp::getPostedData();
        if (IssuesReported::getIssueStatus($data['issue_id']) == IssuesReported::STATUS_RESOLVED) {
            FatUtility::dieJsonError(Label::getLabel("LBL_Status_Already_Resolved", CommonHelper::getLangId()));
        }
        $assignValues = ['repiss_status' => $data['issue_status'], 'repiss_updated_on' => date('Y-m-d H:i:s')];
        if (!FatApp::getDb()->updateFromArray(IssuesReported::DB_TBL, $assignValues, ['smt' => 'repiss_id = ?', 'vals' => [$data['issue_id']]])) {
            FatUtility::dieJsonError(Label::getLabel("LBL_SYSTEM_ERROR", CommonHelper::getLangId()));
        }
        $this->set('msg', 'Updated Successfully.');
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher');
        $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner');
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'repiss_status', ReportedIssue::getStatusArr());
        $frm->addTextBox(Label::getLabel('LBL_Order_Id', $this->adminLangId), 'sldetail_order_id');
        $frm->addTextBox(Label::getLabel('LBL_Lesson_Id', $this->adminLangId), 'repiss_slesson_id');
        $frm->addHiddenField('', 'pageSize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'));
        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'slesson_teacher_id', 0);
        $frm->addHiddenField('', 'sldetail_learner_id', 0);
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    private function getActionForm()
    {
        $frm = new Form('actionFrm');
        $repissId = $frm->addHiddenField('', 'reislo_repiss_id');
        $repissId->requirements()->setRequired();
        $repissId->requirements()->setIntPositive();
        $frm->addSelectBox(Label::getLabel('LBL_TAKE_ACTION', $this->adminLangId), 'reislo_action', ReportedIssue::getActionsArr())->requirements()->setRequired();
        $frm->addTextArea(Label::getLabel('LBL_ADMIN_COMMENT'), 'reislo_comment', '');
        $frm->addCheckBox(Label::getLabel('LBL_MARK_IT_CLOSE'), 'reislo_closed', 1);
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Save'));
        return $frm;
    }

}
