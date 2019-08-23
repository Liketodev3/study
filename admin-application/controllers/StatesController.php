<?php
class StatesController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewStates();
    }
    public function index()
    {
        $search  = $this->getSearchForm();
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditStates($adminId, true);
        $this->set("canEdit", $canEdit);
        $this->set("search", $search);
        $this->_template->addJs('js/import-export.js');
        $this->_template->render();
    }
    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
        $countryObj   = new Country();
        $countriesArr = $countryObj->getCountriesArr($this->adminLangId, true);
        $frm->addSelectBox(Label::getLabel('LBL_Country', $this->adminLangId), 'country', $countriesArr);
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
    public function search()
    {
        $pagesize         = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm       = $this->getSearchForm();
        $data             = FatApp::getPostedData();
        $page             = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post             = $searchForm->getFormDataFromArray($data);
        $srch             = State::getSearchObject(false, $this->adminLangId);
        $countrySearchObj = Country::getSearchObject(false, $this->adminLangId);
        $countrySearchObj->doNotCalculateRecords();
        $countrySearchObj->doNotLimitRecords();
        $countriesDbView = $countrySearchObj->getQuery();
        $srch->joinTable("($countriesDbView)", 'LEFT OUTER JOIN', 'st.' . State::DB_TBL_PREFIX . 'country_id = c.' . Country::tblFld('id'), 'c');
        $srch->addMultipleFields(array(
            'st.*',
            'st_l.state_name',
            'c.country_name'
        ));
        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('st.state_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('st_l.state_name', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        if (!empty($post['country'])) {
            $condition = $srch->addCondition('st.state_country_id', '=', $post['country']);
        }
        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs      = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditStates($adminId, true);
        $this->set("canEdit", $canEdit);
        $this->set('activeInactiveArr', applicationConstants::getActiveInactiveArr($this->adminLangId));
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }
    public function form($stateId)
    {
        $this->objPrivilege->canEditStates();
        $stateId = FatUtility::int($stateId);
        $frm     = $this->getForm($stateId);
        if (0 < $stateId) {
            $data = State::getAttributesById($stateId, array(
                'state_id',
                'state_code',
                'state_country_id',
                'state_identifier',
                'state_active'
            ));
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('state_id', $stateId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
    public function setup()
    {
        $this->objPrivilege->canEditStates();
        $frm  = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $stateId = $post['state_id'];
        unset($post['state_id']);
        $record = new State($stateId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        if ($stateId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = State::getAttributesByLangId($langId, $stateId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $stateId      = $record->getMainTableRecordId();
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('stateId', $stateId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }
    public function langForm($stateId = 0, $lang_id = 0)
    {
        $stateId = FatUtility::int($stateId);
        $lang_id = FatUtility::int($lang_id);
        if ($stateId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm  = $this->getLangForm($stateId, $lang_id);
        $langData = State::getAttributesByLangId($lang_id, $stateId);
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('stateId', $stateId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }
    public function langSetup()
    {
        $this->objPrivilege->canEditStates();
        $post    = FatApp::getPostedData();
        $stateId = $post['state_id'];
        $lang_id = $post['lang_id'];
        if ($stateId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm  = $this->getLangForm($stateId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['state_id']);
        unset($post['lang_id']);
        $data     = array(
            'statelang_lang_id' => $lang_id,
            'statelang_state_id' => $stateId,
            'state_name' => $post['state_name']
        );
        $stateObj = new State($stateId);
        if (!$stateObj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($stateObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages    = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = State::getAttributesByLangId($langId, $stateId)) {
                $newTabLangId = $langId;
                break;
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('stateId', $stateId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }
    private function getForm($stateId = 0)
    {
        $stateId = FatUtility::int($stateId);
        $frm     = new Form('frmState');
        $frm->addHiddenField('', 'state_id', $stateId);
        $frm->addRequiredField(Label::getLabel('LBL_State_Identifier', $this->adminLangId), 'state_identifier');
        $frm->addRequiredField(Label::getLabel('LBL_State_Code', $this->adminLangId), 'state_code');
        $countryObj   = new Country();
        $countriesArr = $countryObj->getCountriesArr($this->adminLangId, true);
        $frm->addSelectBox(Label::getLabel('LBL_Country', $this->adminLangId), 'state_country_id', $countriesArr, '', array(), '');
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'state_active', $activeInactiveArr, '', array(), '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    private function getLangForm($stateId = 0, $lang_id = 0)
    {
        $frm = new Form('frmStateLang');
        $frm->addHiddenField('', 'state_id', $stateId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_State_Name', $this->adminLangId), 'state_name');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    public function changeStatus()
    {
        $this->objPrivilege->canEditStates();
        $stateId = FatApp::getPostedData('stateId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (0 >= $stateId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $data = State::getAttributesById($stateId, array(
            'state_id',
            'state_active'
        ));
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        //$status   = ($data['state_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;
        $stateObj = new State($stateId);
        if (!$stateObj->changeStatus($status)) {
            Message::addErrorMessage($stateObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_update_record);
    }
}