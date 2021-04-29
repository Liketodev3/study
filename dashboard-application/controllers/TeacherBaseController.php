<?php

class TeacherBaseController extends LoggedUserController
{

    public function __construct($action)
    {
        parent::__construct($action);
        if (!User::canAccessTeacherDashboard()) {
            if (FatUtility::isAjaxCall()) {
                Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS'));
                FatUtility::dieWithError(Message::getHtml());
            }
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest', 'form'));
        }
        User::setDashboardActiveTab(User::USER_TEACHER_DASHBOARD);
    }

    protected function getSearchForm()
    {
        $frm = new Form('frmSrch');
        $frm->addTextBox(Label::getLabel('LBL_Search_By_Keyword'), 'keyword', '', ['placeholder' => Label::getLabel('LBL_Search_By_Keyword')]);
        $frm->addSelectBox(Label::getLabel('LBL_Status'), 'status', ScheduledLesson::getStatusArr() + [ScheduledLesson::STATUS_ISSUE_REPORTED => Label::getLabel('LBL_Issue_Reported')], '', [], Label::getLabel('LBL_All'))->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $frm->addHiddenField('', 'show_group_classes', ApplicationConstants::NO);
        $btnSubmit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search'));
        $btnReset = $frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_Reset'));
        // $btnSubmit->attachField($btnReset);
        return $frm;
    }

    protected function reportSearchForm($langId)
    {
        $durationArray = Statistics::getDurationTypesArr($langId);
        $reportType =  [Statistics::REPORT_EARNING => Statistics::REPORT_EARNING, Statistics::REPORT_SOLD_LESSONS => Statistics::REPORT_SOLD_LESSONS];
        $frm = new Form('reportSearchForm');
        $field = $frm->addSelectBox(Label::getLabel('LBL_Earing_Duration'), 'earing_duration', $durationArray, Statistics::TYPE_TODAY);
        $field->requirements()->setInt();
        $field->requirements()->setRequired(true);
        $field = $frm->addSelectBox(Label::getLabel('LBL_Lesson_Sold_Duration'), 'lesson_duration', $durationArray, Statistics::TYPE_TODAY);
        $field->requirements()->setInt();
        $field->requirements()->setRequired(true);
        $field = $frm->addSelectBox('', 'report_type[]', $reportType, $reportType, ['multiple' => 'multiple']);
        $field->requirements()->setRequired(true);
        return $frm;
    }

}
