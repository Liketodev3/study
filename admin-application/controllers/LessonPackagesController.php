<?php
class LessonPackagesController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewLessonPackages();
    }

    public function index()
    {
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditLessonPackages($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }

    public function search()
    {
        $srch = LessonPackage::getSearchObject($this->adminLangId, false);
        $srch->addMultipleFields(array(
            'lpackage_id',
            'lpackage_active',
            'lpackage_identifier',
            'lpackage_title',
            'lpackage_lessons',
            'lpackage_is_free_trial'
        ));
        $srch->addOrder('lpackage_active', 'desc');
        $srch->addOrder('lpackage_is_free_trial', 'desc');
        $srch->addOrder('lpackage_added_on', 'desc');
        $rs      = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $canEdit = $this->objPrivilege->canEditLessonPackages(AdminAuthentication::getLoggedAdminId(), true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->set('recordCount', $srch->recordCount());
        //$this->set( 'adminLangId', $this->adminLangId );
        $this->_template->render(false, false);
    }

    public function form($lPackageId)
    {
        $lPackageId = FatUtility::int($lPackageId);
        $frm           = $this->getForm($lPackageId);
        if (0 < $lPackageId) {
            $data = LessonPackage::getAttributesById($lPackageId, array(
                'lpackage_id',
                'lpackage_identifier',
                'lpackage_lessons',
                'lpackage_active',
                'lpackage_is_free_trial'
            ));
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $data['lpackage_lessons'] = $data['lpackage_lessons'] * 1;
            $frm->fill($data);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('lPackageId', $lPackageId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditLessonPackages();
        $frm  = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $lPackageId = $post['lpackage_id'];
        unset($post['lpackage_id']);
        if ($lPackageId == 0) {
            $post['lpackage_added_on'] = date('Y-m-d H:i:s');
        }

        /* add check that Free Trial can only be one time in system[ */
        $srch = LessonPackage::getSearchObject(0, false);
        $srch->addCondition('lpackage_is_free_trial', '=', 1);
        $srch->setPageSize(1);
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields(array('lpackage_is_free_trial'));
        if ($lPackageId > 0) {
            $srch->addCondition('lpackage_id', '!=', $lPackageId);
        }
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if ($row && $post['lpackage_is_free_trial'] == 1) {
            Message::addErrorMessage("Free Trial Package can only be one time.");
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */

        $record = new LessonPackage($lPackageId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        if ($lPackageId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = LessonPackage::getAttributesByLangId($langId, $lPackageId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $lPackageId = $record->getMainTableRecordId();
            $newTabLangId  = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('lPackageId', $lPackageId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($lPackageId = 0, $lang_id = 0)
    {
        $lPackageId = FatUtility::int($lPackageId);
        $lang_id       = FatUtility::int($lang_id);
        if ($lPackageId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm  = $this->getLangForm($lPackageId, $lang_id);
        $langData = LessonPackage::getAttributesByLangId($lang_id, $lPackageId);
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('lPackageId', $lPackageId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditLessonPackages();
        $post          = FatApp::getPostedData();
        $lPackageId = $post['lpackage_id'];
        $lang_id       = $post['lang_id'];
        if ($lPackageId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm  = $this->getLangForm($lPackageId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
        unset($post['lpackage_id']);
        unset($post['lang_id']);
        $data = array(
            'lpackagelang_lang_id' => $lang_id,
            'lpackagelang_lpackage_id' => $lPackageId,
            'lpackage_title' => $post['lpackage_title'],
           // 'lpackage_text' => $post['lpackage_text']
        );
        $obj  = new LessonPackage($lPackageId);
        if (!$obj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages    = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = LessonPackage::getAttributesByLangId($langId, $lPackageId)) {
                $newTabLangId = $langId;
                break;
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('lPackageId', $lPackageId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditLessonPackages();
        $lPackageId = FatApp::getPostedData('lPackageId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (0 >= $lPackageId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $data = LessonPackage::getAttributesById($lPackageId, array(
            'lpackage_id',
            'lpackage_active'
        ));
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        //$status = ($data['lpackage_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;
        $obj    = new LessonPackage($lPackageId);
        if (!$obj->changeStatus($status)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_update_record);
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditLessonPackages();
        $lpackage_id = FatApp::getPostedData('lPackageId', FatUtility::VAR_INT, 0);
        if ($lpackage_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $LessonPackageObj = new LessonPackage($lpackage_id);
        if (!$LessonPackageObj->canRecordMarkDelete($lpackage_id)) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $LessonPackageObj->assignValues(array(
            LessonPackage::tblFld('deleted') => 1
        ));
        if (!$LessonPackageObj->save()) {
            Message::addErrorMessage($LessonPackageObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function getForm($lPackageId = 0)
    {
        $lPackageId = FatUtility::int($lPackageId);
        $frm           = new Form('frmLessonPackage');
        $frm->addHiddenField('', 'lpackage_id', $lPackageId);
        $frm->addRequiredField(Label::getLabel('LBL_Lesson_Package_Identifier', $this->adminLangId), 'lpackage_identifier');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Package_Lessons', $this->adminLangId), 'lpackage_lessons');
        $fld->htmlAfterField = '<small>' . Label::getLabel('LBL_Note:_Enter_Number_of_lessons_in_a_package') . '</small>';

        $yesNoArr = applicationConstants::getYesNoArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Free_Trial'), 'lpackage_is_free_trial', $yesNoArr, applicationConstants::NO, array(), '');

        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'lpackage_active', $activeInactiveArr, '', array(), '');

        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getLangForm($lPackageId = 0, $lang_id = 0)
    {
        $frm = new Form('frmLessonPackageLang');
        $frm->addHiddenField('', 'lpackage_id', $lPackageId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_Lesson_Package_Title', $this->adminLangId), 'lpackage_title');
        // $frm->addTextarea(Label::getLabel('LBL_lpackage_Text', $this->adminLangId), 'lpackage_text');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
