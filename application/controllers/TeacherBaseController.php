<?php
class TeacherBaseController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
        if (true !== User::canAccessTeacherDashboard()) {
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest', 'form'));
        }
        User::setDashboardActiveTab(User::USER_TEACHER_DASHBOARD);
    }

    protected function getSearchForm()
    {
        $frm = new Form('frmSrch');
        $frm->addTextBox(Label::getLabel('LBL_Search_By_Keyword'), 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_By_Keyword')));
        $frm->addSelectBox(Label::getLabel('LBL_Status'), 'status', ScheduledLesson::getStatusArr()+array(ScheduledLesson::STATUS_ISSUE_REPORTED=>Label::getLabel('LBL_Issue_Reported')), ScheduledLesson::STATUS_UPCOMING, array(), Label::getLabel('LBL_All'))->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $btnSubmit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search'));
        $btnReset = $frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_Reset'));
        $btnSubmit->attachField($btnReset);
        return $frm;
    }
}
