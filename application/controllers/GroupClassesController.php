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
        $srch->addCondition('grpcls_end_datetime', '>', date('Y-m-d H:i:s'));
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
        $srch->joinTable(Country::DB_TBL, 'LEFT JOIN', 'ut.user_country_id = country.country_id', 'country');
        $srch->joinTable(Country::DB_TBL_LANG, 'LEFT JOIN', 'country.country_id = countryLang.countrylang_country_id and countryLang.countrylang_lang_id = '.$this->siteLangId, 'countryLang');
        $srch->joinTable('tbl_teacher_stats', 'LEFT JOIN', 'testat.testat_user_id = ut.user_id', 'testat');
        
        $srch->addMultipleFields(['IFNULL(country_name, country_code) as country_name', 'testat_reviewes', 'testat_ratings']);
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
        $frm->addSelectBox('', 'language', TeacherGroupClassesSearch::getTeachLangs($this->siteLangId), '', array(), Label::getLabel('LBL_All_Language'));
        $frm->addTextBox('', 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_Class')));
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'btnSrchSubmit', '');
        return $frm;
    }
}
