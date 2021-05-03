<?php

class ReportIssueController extends LoggedUserController
{

    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function form($sldetailId)
    {
        $sldetailId = FatUtility::int($sldetailId);
        $userId = UserAuthentication::getLoggedUserId();
        $srch = new SearchBase('tbl_scheduled_lesson_details');
        $srch->addCondition('sldetail_learner_id', '=', $userId);
        $srch->addCondition('sldetail_slesson_id', '=', $sldetailId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        if ($srch->recordCount() == 0) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $srch = new SearchBase('tbl_reported_issues');
        $srch->addCondition('repiss_reported_by', '=', $userId);
        $srch->addCondition('repiss_slesson_id', '=', $sldetailId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        if ($srch->recordCount() > 0) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getForm();
        $frm->fill(['repiss_slesson_id' => $sldetailId]);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $sldetailId = $post['repiss_slesson_id'];
        $userId = UserAuthentication::getLoggedUserId();
        $srch = new SearchBase('tbl_scheduled_lesson_details');
        $srch->addCondition('sldetail_learner_id', '=', $userId);
        $srch->addCondition('sldetail_slesson_id', '=', $sldetailId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        if ($srch->recordCount() == 0) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $srch = new SearchBase('tbl_reported_issues');
        $srch->addCondition('repiss_reported_by', '=', $userId);
        $srch->addCondition('repiss_slesson_id', '=', $sldetailId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        if ($srch->recordCount() > 0) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $options = IssueReportOptions::getOptionsArray($this->siteLangId, User::USER_TYPE_LEANER);
        $record = new TableRecord(ReportedIssue::DB_TBL);
        $record->assignValues([
            'repiss_comment' => $post['repiss_comment'],
            'repiss_slesson_id' => $post['repiss_slesson_id'],
            'repiss_title' => $options[$post['repiss_title']],
            'repiss_status' => ReportedIssue::STATUS_PROGRESS,
            'repiss_reported_by_type' => ReportedIssue::USER_TYPE_LEARNER,
            'repiss_reported_on' => date('Y-m-d H:i:s'),
            'repiss_reported_by' => $userId
        ]);
        if (!$record->addNew()) {
            FatUtility::dieJsonError($record->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    public function getForm()
    {
        $frm = new Form('reportIssueFrm');
        $options = IssueReportOptions::getOptionsArray($this->siteLangId, User::USER_TYPE_LEANER);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Subject'), 'repiss_title', $options);
        $fld->requirements()->setRequired(true);
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'repiss_comment', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField(Label::getLabel('LBL_slesson_id'), 'repiss_slesson_id');
        $fld->requirements()->setRequired(true);
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SUBMIT'));
        return $frm;
    }

}
