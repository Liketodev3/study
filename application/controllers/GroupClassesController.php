<?php

class GroupClassesController extends MyAppController
{

    public function index()
    {
        $this->set('frmSrch', $this->getSearchForm());
        $this->_template->addJs('js/jquery.datetimepicker.js');
        $this->_template->render();
    }

    public function search()
    {
        $frm = $this->getSearchForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if ($post === false) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = TeacherGroupClassesSearch::getSearchObj($this->siteLangId);
        if (isset($post['language']) && $post['language'] !== "") {
            $srch->addCondition('grpcls_tlanguage_id', '=', $post['language']);
        }
        if (isset($post['custom_filter'])) {
            switch ($post['custom_filter']) {
                case TeacherGroupClasses::FILTER_UPCOMING:
                    $srch->addCondition('grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
                    $srch->addCondition('grpcls_start_datetime', '>', date('Y-m-d H:i:s'));
                    break;
                case TeacherGroupClasses::FILTER_ONGOING:
                    $srch->addCondition('grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
                    $srch->addCondition('grpcls_start_datetime', '<=', date('Y-m-d H:i:s'));
                    $srch->addCondition('grpcls_end_datetime', '>=', date('Y-m-d H:i:s'));
                    break;
                default:
                    $srch->addCondition('grpcls_end_datetime', '>=', date('Y-m-d H:i:s'));
                    break;
            }
            $frm->fill($post);
        }

        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $rs = $srch->getResultSet();
        $classesList = FatApp::getDb()->fetchAll($rs);
        $pagingArr = [
            'pageCount' => $srch->pages(),
            'page' => $page,
            'pageSize' => $pageSize,
            'recordCount' => $srch->recordCount(),
        ];
        $this->set('classes', $classesList);
        $min_booking_time = FatApp::getConfig('CONF_CLASS_BOOKING_GAP', FatUtility::VAR_INT, 60);
        $this->set('min_booking_time', $min_booking_time);
        $post['page'] = $page;
        $this->set('postedData', $post);
        $this->set('frm', $frm);
        $this->set('pagingArr', $pagingArr);
        $this->_template->render(false, false);
    }

    public function view($grpcls_id)
    {
        $srch = TeacherGroupClassesSearch::getSearchObj($this->siteLangId);
        $srch->addCondition('grpcls_id', '=', $grpcls_id);
        $srch->setPageSize(1);
        $classData = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($classData)) {
            FatUtility::exitWithErrorCode(404);
        }
        $this->set('class', $classData);
        $min_booking_time = FatApp::getConfig('CONF_CLASS_BOOKING_GAP', FatUtility::VAR_INT, 60);
        $this->set('min_booking_time', $min_booking_time);
        $this->_template->render();
    }

    private function getSearchForm()
    {
        $frm = new Form('frmTeacherSrch');
        $frm->addSelectBox('', 'custom_filter', TeacherGroupClasses::getCustomFilterAr(), '', array(), Label::getLabel('LBL_ALL'));
        $frm->addSelectBox('', 'language', TeacherGroupClassesSearch::getTeachLangs($this->siteLangId), '', array(), Label::getLabel('LBL_All_Language'));
        $frm->addTextBox('', 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_Class')));
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'btnSrchSubmit', '');
        return $frm;
    }
}
