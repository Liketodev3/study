<?php
class PreferencesController extends AdminBaseController
{
    public function __construct($action)
    {
        $ajaxCallArray = array(
            'deleteRecord',
            'form',
            'langForm',
            'search',
            'setup',
            'langSetup'
        );
        if (!FatUtility::isAjaxCall() && in_array($action, $ajaxCallArray)) {
            die($this->str_invalid_Action);
        }
        parent::__construct($action);
        $this->objPrivilege->canViewPreferences();
    }
    public function index($type = 0)
    {
        $adminId = AdminAuthentication::getLoggedAdminId();
        $frmSearch = $this->getSearchForm($type);
        $canEdit = $this->objPrivilege->canEditPreferences($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->set("type", $type);
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }

    public function search()
    {
        // $pagesize   = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data       = FatApp::getPostedData();
        // $page       = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post       = $searchForm->getFormDataFromArray($data);
        $srch       = new PreferenceSearch($this->adminLangId);
        if (!empty($post['keyword'])) {
            $srch->addCondition('preference_identifier', 'like', '%' . $post['keyword'] . '%');
        }
        if ($post['type'] > 0) {
            $srch->addCondition('preference_type', '=', $post['type']);
        }
        $srch->addOrder('preference_display_order', 'asc');
        $srch->doNotLimitRecords();
        // $page = (empty($page) || $page <= 0) ? 1 : $page;
        // $page = FatUtility::int($page);
        // $srch->setPageNumber($page);
        // $srch->setPageSize($pagesize);
        $rs      = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }

        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditPreferences($adminId, true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        // $this->set('pageCount', $srch->pages());
        // $this->set('recordCount', $srch->recordCount());
        // $this->set('page', $page);
        $this->set("type", $post['type']);
        // $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    private function getSearchForm($type = 0)
    {
        $frm        = new Form('frmPreferenceSearch');
        $f1         = $frm->addTextBox(Label::getLabel('LBL_Preference_Identifier', $this->adminLangId), 'keyword', '');
        $f1         = $frm->addHiddenField('', 'type', $type);
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function form($preferenceId, $type = 0)
    {
        $preferenceId = FatUtility::int($preferenceId);
        $frm = $this->getForm($preferenceId);
        if (0 < $preferenceId) {
            $data = Preference::getAttributesById($preferenceId, array(
                'preference_id',
                'preference_identifier',
            ));
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }
        $fldType = $frm->getField('type');
        $fldType->value = $type;
        $this->set('languages', Language::getAllNames());
        $this->set('preference_id', $preferenceId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
    public function setup()
    {
        $this->objPrivilege->canEditPreferences();
        $frm  = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $preferenceId = $post['preference_id'];
        if (!empty($post['type'])) {
            $post['preference_type'] = $post['type'];
        }
        unset($post['preference_id']);
        $record = new Preference($preferenceId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        if ($preferenceId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = Preference::getAttributesByLangId($langId, $preferenceId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $preferenceId = $record->getMainTableRecordId();
            $newTabLangId  = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('preferenceId', $preferenceId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($preferenceId = 0, $lang_id = 0)
    {
        $preferenceId = FatUtility::int($preferenceId);
        $lang_id       = FatUtility::int($lang_id);
        if ($preferenceId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm  = $this->getLangForm($preferenceId, $lang_id);
        $langData = Preference::getAttributesByLangId($lang_id, $preferenceId);
        $langData['preferencelang_title'] = $langData['preference_title'];
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('preferenceId', $preferenceId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }
    public function langSetup()
    {
        $this->objPrivilege->canEditPreferences();
        $post          = FatApp::getPostedData();
        $preferenceId = $post['preferencelang_preference_id'];
        $lang_id       = $post['lang_id'];
        if ($preferenceId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm  = $this->getLangForm($preferenceId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['preferencelang_preference_id']);
        unset($post['lang_id']);
        $data = array(
            'preferencelang_lang_id' => $lang_id,
            'preferencelang_preference_id' => $preferenceId,
            'preference_title' => $post['preferencelang_title'],
        );
        $obj  = new Preference($preferenceId);
        if (!$obj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages    = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = Preference::getAttributesByLangId($langId, $preferenceId)) {
                $newTabLangId = $langId;
                break;
            }
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('preferenceId', $preferenceId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditPreferences();
        $preference_id = FatApp::getPostedData('preferenceId', FatUtility::VAR_INT, 0);
        if ($preference_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $deleteRecord = new Preference();
        if (!$deleteRecord->deletePreference($preference_id)) {
            FatUtility::dieWithError($deleteRecord->getError());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }




    private function getForm($preferenceId = 0)
    {
        $preferenceId = FatUtility::int($preferenceId);
        $frm           = new Form('frmPreference');
        $frm->addHiddenField('', 'preference_id', $preferenceId);
        $frm->addHiddenField('', 'type', '');
        $frm->addRequiredField(Label::getLabel('LBL_preference_Identifier', $this->adminLangId), 'preference_identifier');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    private function getLangForm($preferenceId = 0, $lang_id = 0)
    {
        $frm = new Form('frmPreferenceLang');
        $frm->addHiddenField('', 'preferencelang_preference_id', $preferenceId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_preference_Title', $this->adminLangId), 'preferencelang_title');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    public function updateOrder()
    {
        $post = FatApp::getPostedData();
        if (!empty($post)) {
            $prefObj = new Preference();
            if (!$prefObj->updateOrder($post['preferences'])) {
                Message::addErrorMessage($prefObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }

            $this->set('msg', Label::getLabel('LBL_Order_Updated_Successfully', $this->adminLangId));
            $this->_template->render(false, false, 'json-success.php');
        }
    }
}
