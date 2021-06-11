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
        $frm->fill(['repiss_sldetail_id' => $sldetailId]);
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
        $sldetailId = FatUtility::int($post['repiss_sldetail_id']);
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
        $issue = ReportedIssue::getIssueById(FatUtility::int($issueId));
        if (empty($issue)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $userId = UserAuthentication::getLoggedUserId();
        $logs = ReportedIssue::getIssueLogsById($issueId);
        $log = end($logs);
        $esclateHours = FatApp::getConfig('CONF_ESCLATE_ISSUE_HOURS_AFTER_RESOLUTION');
        $esclateDate = strtotime($issue['repiss_updated_on'] . " +" . $esclateHours . " hour");
        $canEsclate = false;
        if (($esclateDate > strtotime(date('Y-m-d H:i:s')) && ($issue['repiss_status'] == ReportedIssue::STATUS_RESOLVED) &&
                (($log['reislo_added_by_type'] ?? 0) == ReportedIssue::USER_TYPE_TEACHER) && (($log['reislo_added_by'] ?? 0) != $userId))) {
            $canEsclate = true;
        }
        $this->set('logs', $logs);
        $this->set('issue', $issue);
        $this->set('canEsclate', $canEsclate);
        $this->set('userTimezone', MyDate::getUserTimeZone());
        $this->set('actionArr', ReportedIssue::getActionsArr());
        $this->_template->render(false, false);
    }

    private function getForm()
    {
        $frm = new Form('reportIssueFrm');
        $options = IssueReportOptions::getOptionsArray($this->siteLangId, ReportedIssue::USER_TYPE_LEANER);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Subject'), 'repiss_title', $options);
        $fld->requirements()->setRequired(true);
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'repiss_comment', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField(Label::getLabel('LBL_slesson_id'), 'repiss_sldetail_id');
        $fld->requirements()->setRequired(true);
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SUBMIT'));
        return $frm;
    }

    public function resolveForm($issueId)
    {
        $issue = ReportedIssue::getIssueById($issueId);
        $userId = UserAuthentication::getLoggedUserId();
        if (empty($issue) || $userId != $issue['slesson_teacher_id']) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if ($issue['repiss_status'] > ReportedIssue::STATUS_PROGRESS) {
            FatUtility::dieJsonError(Label::getLabel('LBL_RESOLUTION_PROVIDED_ALREADY'));
        }
        $frm = $this->getResolveForm();
        $frm->fill(['reislo_repiss_id' => $issue['repiss_id']]);
        $this->set('frm', $frm);
        $this->set("issue", $issue);
        $this->set('statusArr', ReportedIssue::getStatusArr());
        $this->_template->render(false, false);
    }

    public function resolveSetup()
    {
        $frm = $this->getResolveForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $userId = UserAuthentication::getLoggedUserId();
        $issue = ReportedIssue::getIssueById($post['reislo_repiss_id']);
        if (empty($issue) || $userId != $issue['slesson_teacher_id']) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if ($issue['repiss_status'] > ReportedIssue::STATUS_PROGRESS) {
            FatUtility::dieJsonError(Label::getLabel('LBL_RESOLUTION_PROVIDED_ALREADY'));
        }
        $reportedIssue = new ReportedIssue($post['reislo_repiss_id'], $userId, ReportedIssue::USER_TYPE_TEACHER);
        if (!$reportedIssue->setupIssueAction($post['reislo_action'], $post['reislo_comment'], false)) {
            FatUtility::dieJsonError($reportedIssue->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    private function getResolveForm()
    {
        $frm = new Form('actionFrm');
        $repissId = $frm->addHiddenField('', 'reislo_repiss_id');
        $repissId->requirements()->setRequired();
        $repissId->requirements()->setIntPositive();
        $options = ReportedIssue::getActionsArr();
        unset($options[ReportedIssue::ACTION_ESCLATE_TO_ADMIN]);
        $frm->addSelectBox(Label::getLabel('LBL_TAKE_ACTION'), 'reislo_action', $options)
                ->requirements()->setRequired(true);
        $frm->addTextArea(Label::getLabel('LBL_YOUR_COMMENT'), 'reislo_comment', '')
                ->requirements()->setRequired(true);
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Submit'));
        return $frm;
    }

    public function esclateForm($issueId)
    {
        $issue = ReportedIssue::getIssueById($issueId);
        $userId = UserAuthentication::getLoggedUserId();
        if (empty($issue) || $userId != $issue['sldetail_learner_id'] ||
                $issue['repiss_status'] != ReportedIssue::STATUS_RESOLVED) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $logs = ReportedIssue::getIssueLogsById($issue['repiss_id']);
        $log = end($logs);
        if ($log['reislo_added_by'] == $userId || $issue['repiss_status'] != ReportedIssue::STATUS_RESOLVED) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $escalateHour = FatApp::getConfig('CONF_ESCLATE_ISSUE_HOURS_AFTER_RESOLUTION');
        $escalateDate = strtotime($issue['repiss_updated_on'] . " +" . $escalateHour . " hour");
        if ($escalateDate <= strtotime(date('Y-m-d H:i:s'))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_ISSUE_ESCALATION_TIME_HAS_PASSED'));
        }
        $frm = $this->getEsclateForm();
        $frm->fill(['reislo_repiss_id' => $issue['repiss_id']]);
        $this->set('frm', $frm);
        $this->set("issue", $issue);
        $this->set('statusArr', ReportedIssue::getStatusArr());
        $this->_template->render(false,
                false);
    }

    public function esclateSetup()
    {
        $frm = $this->getEsclateForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $userId = UserAuthentication::getLoggedUserId();
        $issue = ReportedIssue::getIssueById($post['reislo_repiss_id']);
        if (empty($issue) || $userId != $issue['sldetail_learner_id'] || $issue ['repiss_status'] != ReportedIssue::STATUS_RESOLVED) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $logs = ReportedIssue::getIssueLogsById($issue['repiss_id']);
        $log = end($logs);
        if ($log['reislo_added_by'] == $userId || $issue['repiss_status'] != ReportedIssue::STATUS_RESOLVED) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $escalateHour = FatApp::getConfig('CONF_ESCLATE_ISSUE_HOURS_AFTER_RESOLUTION');
        $escalateDate = strtotime($issue['repiss_updated_on'] . " +" . $escalateHour . " hour");
        if ($escalateDate <= strtotime(date('Y-m-d H:i:s'))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_ISSUE_ESCALATION_TIME_HAS_PASSED'));
        }
        $reportedIssue = new ReportedIssue($post['reislo_repiss_id'], $userId, ReportedIssue::USER_TYPE_LEARNER);
        if (!$reportedIssue->setupIssueAction(ReportedIssue:: ACTION_ESCLATE_TO_ADMIN, $post['reislo_comment'], false)) {
            FatUtility::dieJsonError($reportedIssue->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    private function getEsclateForm()
    {
        $frm = new Form('actionFrm');
        $repissId = $frm->addHiddenField('', 'reislo_repiss_id');
        $repissId->requirements()->setRequired();
        $repissId->requirements()->setIntPositive();
        $frm->addTextArea(Label::getLabel('LBL_YOUR_COMMENT'), 'reislo_comment', '')->requirements()->setRequired();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Submit'));

        return $frm;
    }

}
