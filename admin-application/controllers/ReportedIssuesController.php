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
        $this->set('frm', $this->getSearchForm());
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
        $issueId = FatUtility::int($issueId);
        if ($issueId < 1) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $srch = ReportedIssue::getSearchObject();
        $srch->addCondition('repiss.repiss_slesson_id', '=', $issueId);
        $srch->joinTable(UserSetting::DB_TBL, 'INNER JOIN', 'slesson.slesson_teacher_id = us.us_user_id', 'us');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'slesson.slesson_slanguage_id = tlang.tlanguage_id', 'tlang');
        $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT OUTER JOIN', 'sll.tlanguagelang_tlanguage_id = tlang.tlanguage_id AND sll.tlanguagelang_lang_id = ' . $this->adminLangId, 'sll');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'sldetail.sldetail_learner_id = ul.user_id', 'ul');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'slesson.slesson_teacher_id = ut.user_id', 'ut');
        $srch->addMultipleFields(['repiss.repiss_id', 'repiss.repiss_title', 'repiss.repiss_slesson_id',
            'repiss.repiss_reported_on', 'repiss.repiss_reported_by', 'repiss.repiss_reported_by_type',
            'repiss.repiss_status', 'repiss.repiss_comment', 'repiss.repiss_updated_on', "us.*",
            'sldetail.sldetail_order_id', 'sldetail.sldetail_learner_id', 'sldetail.sldetail_learner_join_time',
            'slesson.slesson_teacher_join_time', 'sldetail.sldetail_learner_end_time', 'slesson.slesson_teacher_id',
            'slesson.slesson_teacher_end_time', 'slesson.slesson_ended_by', 'slesson.slesson_ended_on',
            'IFNULL(sll.tlanguage_name, tlang.tlanguage_identifier) as tlanguage_name',
            'CONCAT(user.user_first_name, " ", user.user_last_name) AS reporter_username',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) AS learner_username',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) AS teacher_username',
            'order_net_amount', 'order_discount_total', 'op_qty', 'op_lpackage_is_free_trial', 'op_unit_price',
        ]);
        $srch->addGroupBy('repiss.repiss_id');
        $issueDetail = FatApp::getDb()->fetchAll($srch->getResultSet());
        $callHistory = IssuesReported::getCallHistory($issueDetail[0]['slesson_teacher_id']);
        $issueStatusArr = IssuesReported::getResolveTypeArray($this->adminLangId);
        $this->set("callHistory", $callHistory);
        $this->set("statusArr", $issueStatusArr);
        $this->set("issueDetail", $issueDetail);
        $this->_template->render(false, false);
    }

    public function actionForm($repissId)
    {
        $repissId = FatUtility::int($repissId);
        $issRepObj = new IssuesReported();
        $srch = $issRepObj->getSearchObject();
        $srch->addCondition('repiss_id', '=', $repissId);
        $srch->joinTable(UserSetting::DB_TBL, 'INNER JOIN', 'sl.slesson_teacher_id = us.us_user_id', 'us');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'sl.slesson_slanguage_id = tlang.tlanguage_id', 'tlang');
        if ($this->adminLangId > 0) {
            $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT OUTER JOIN', 'sll.tlanguagelang_tlanguage_id = tlang.tlanguage_id AND sll.tlanguagelang_lang_id = ' . $this->adminLangId, 'sll');
        }
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'sld.sldetail_learner_id = ul.user_id', 'ul');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'sl.slesson_teacher_id = ut.user_id', 'ut');
        $srch->addMultipleFields([
            "i.*",
            "us.*",
            'sld.sldetail_order_id',
            'sl.slesson_teacher_id',
            'sld.sldetail_learner_id',
            'sld.sldetail_learner_join_time',
            'sl.slesson_teacher_join_time',
            'sld.sldetail_learner_end_time',
            'sl.slesson_teacher_end_time',
            'sl.slesson_ended_by',
            'sl.slesson_ended_on',
            'IFNULL(sll.tlanguage_name, tlang.tlanguage_identifier) as tlanguage_name',
            'CONCAT(u.user_first_name, " " , u.user_last_name) AS reporter_username',
            'CONCAT(ul.user_first_name, " " , ul.user_last_name) AS learner_username',
            'CONCAT(ut.user_first_name, " " , ut.user_last_name) AS teacher_username',
            'i.repiss_admin_comments', 'i.repiss_updated_by_admin',
            'order_net_amount',
            'order_discount_total',
            'op_qty',
            'op_lpackage_is_free_trial',
            'op_unit_price',
        ]);
        $srch->addGroupBy('repiss_id');
        $issueDetail = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (empty($issueDetail)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $frm = $this->getActionForm();
        $issue = end($issueDetail);
        $frm->fill($issue);
        $this->set('frm', $frm);
        $this->set('issueDetail', $issueDetail);
        $this->set('statusArr', IssuesReported::getResolveTypeArray($this->adminLangId));
        $this->set('issues_options', IssueReportOptions::getOptionsArray($this->adminLangId));
        $this->_template->render(false, false);
    }

    public function setupAction()
    {
        $frm = $this->getActionForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $record = new IssuesReported($post['repiss_id']);
        $record->setFldValue('repiss_status', $post['repiss_status']);
        $record->setFldValue('repiss_closed', $post['repiss_closed']);
        $record->setFldValue('repiss_admin_comments', $post['repiss_admin_comments']);
        if (!$record->save()) {
            FatUtility::dieJsonError($record->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    private function getActionForm()
    {
        $frm = new Form('actionFrm');
        $repissId = $frm->addHiddenField('', 'repiss_id');
        $repissId->requirements()->setRequired();
        $repissId->requirements()->setIntPositive();
        $arrOptions = IssueReportOptions::getOptionsArray($this->adminLangId);
        $frm->addTextArea(Label::getLabel('LBL_ADMIN_COMMENT'), 'repiss_admin_comments', '');
        $frm->addSelectBox(Label::getLabel('LBL_TAKE_ACTION', $this->adminLangId), 'repiss_status', IssuesReported::getResolveTypeArray())->requirements()->setRequired();
        $frm->addCheckBox(Label::getLabel('LBL_MARK_IT_CLOSED'), 'repiss_closed', 1);
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Save'));
        return $frm;
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
        $frm->addTextBox(Label::getLabel('LBL_Order_Id', $this->adminLangId), 'sldetail_order_id');
        $frm->addHiddenField('', 'pageSize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'));
        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'repiss_status', 0);
        $frm->addHiddenField('', 'slesson_teacher_id', 0);
        $frm->addHiddenField('', 'sldetail_learner_id', 0);
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

}
