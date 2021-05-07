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
        $reportIssue = new ReportedIssue(0, $userId);
        if (!$lesson = $reportIssue->getLessonToReport($sldetailId)) {
            FatUtility::dieJsonError($reportIssue->getError());
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
        $userId = UserAuthentication::getLoggedUserId();
        $sldetailId = FatUtility::int($post['repiss_slesson_id']);
        $reportIssue = new ReportedIssue(0, $userId);
        if (!$lesson = $reportIssue->getLessonToReport($sldetailId)) {
            FatUtility::dieJsonError($reportIssue->getError());
        }
        if (!$reportIssue->setupIssue($sldetailId, $post['repiss_title'], $post['repiss_comment'])) {
            FatUtility::dieJsonError($reportIssue->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    public function resolve()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $userId = UserAuthentication::getLoggedUserId();
        $sldetailId = FatUtility::int($post['repiss_slesson_id']);
        $reportIssue = new ReportedIssue(0, $userId);
        if (!$lesson = $reportIssue->getLessonToReport($sldetailId)) {
            FatUtility::dieJsonError($reportIssue->getError());
        }
        if (!$reportIssue->setupIssue($sldetailId, $post['repiss_title'], $post['repiss_comment'])) {
            FatUtility::dieJsonError($reportIssue->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    public function detail($issueId)
    {
        $issueId = FatUtility::int($issueId);
        $issue = ReportedIssue::getIssueById($issueId);
        if (empty($issue)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $this->set('issue', $issue);
        $this->set('userTimezone', MyDate::getUserTimeZone());
        $this->_template->render(false, false);
    }

    private function getForm()
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
