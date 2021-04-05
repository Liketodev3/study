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
        $canEdit = $this->objPrivilege->canEditLessonPackages($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }

    public function search()
    {
        $srch = LessonPackage::getSearchObject($this->adminLangId, false);
        $srch->addMultipleFields([
            'lpackage_id',
            'lpackage_active',
            'lpackage_identifier',
            'lpackage_title',
            'lpackage_lessons',
            'lpackage_is_free_trial'
        ]);
        $srch->addOrder('lpackage_active', 'desc');
        $srch->addOrder('lpackage_is_free_trial', 'desc');
        $srch->addOrder('lpackage_added_on', 'desc');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $canEdit = $this->objPrivilege->canEditLessonPackages(AdminAuthentication::getLoggedAdminId(), true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false);
    }

    public function form($lPackageId)
    {
        $lPackageId = FatUtility::int($lPackageId);
        $isFreeTrial = applicationConstants::NO;
        $lessonPackageData = [];
        if (0 < $lPackageId) {
            $lessonPackageData = LessonPackage::getAttributesById($lPackageId, [
                        'lpackage_id',
                        'lpackage_identifier',
                        'lpackage_lessons',
                        'lpackage_active',
                        'lpackage_is_free_trial'
            ]);
            if ($lessonPackageData === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $isFreeTrial = $lessonPackageData['lpackage_is_free_trial'];
            $lessonPackageData['lpackage_lessons'] = $lessonPackageData['lpackage_lessons'] * 1;
        }
        $frm = $this->getForm($lPackageId, $isFreeTrial);
        $frm->fill($lessonPackageData);
        $this->set('languages', Language::getAllNames());
        $this->set('lPackageId', $lPackageId);
        $this->set('isFreeTrial', $isFreeTrial);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditLessonPackages();
        $lPackageId = FatApp::getPostedData('lpackage_id', FatUtility::VAR_INT, 0);
        $isFreeTrial = applicationConstants::NO;
        if ($lPackageId > 0) {
            $srch = LessonPackage::getSearchObject(0, false);
            $srch->setPageSize(1);
            $srch->doNotCalculateRecords();
            $srch->addMultipleFields(['lpackage_is_free_trial', 'lpackage_id']);
            $srch->addCondition('lpackage_id', '=', $lPackageId);
            $rs = $srch->getResultSet();
            $lessonPackageData = FatApp::getDb()->fetch($rs);
            if (empty($lessonPackageData)) {
                Message::addErrorMessage(Label::getLabel('LBL_INVALID_REQUEST'));
                FatUtility::dieJsonError(Message::getHtml());
            }
            $isFreeTrial = $lessonPackageData['lpackage_is_free_trial'];
        }
        $form = $this->getForm($lPackageId, $isFreeTrial);
        $post = $form->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($form->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        unset($post['lpackage_id'], $post['lpackage_is_free_trial']);
        if ($isFreeTrial == applicationConstants::YES) {
            unset($post['lpackage_lessons']);
        }
        if ($lPackageId == 0) {
            $post['lpackage_added_on'] = date('Y-m-d H:i:s');
        }
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
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('lPackageId', $lPackageId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($lPackageId = 0, $lang_id = 0)
    {
        $lPackageId = FatUtility::int($lPackageId);
        $lang_id = FatUtility::int($lang_id);
        if ($lPackageId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm = $this->getLangForm($lPackageId, $lang_id);
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
        $post = FatApp::getPostedData();
        $lPackageId = $post['lpackage_id'];
        $lang_id = $post['lang_id'];
        if ($lPackageId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getLangForm($lPackageId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
        unset($post['lpackage_id']);
        unset($post['lang_id']);
        $data = [
            'lpackagelang_lang_id' => $lang_id,
            'lpackagelang_lpackage_id' => $lPackageId,
            'lpackage_title' => $post['lpackage_title'],
        ];
        $obj = new LessonPackage($lPackageId);
        if (!$obj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages = Language::getAllNames();
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
        $data = LessonPackage::getAttributesById($lPackageId, ['lpackage_id', 'lpackage_active']);
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $obj = new LessonPackage($lPackageId);
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
        $LessonPackageObj->assignValues([LessonPackage::tblFld('deleted') => 1]);
        if (!$LessonPackageObj->save()) {
            Message::addErrorMessage($LessonPackageObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function getForm($lPackageId = 0, int $isFreeTrial = applicationConstants::NO)
    {
        $lPackageId = FatUtility::int($lPackageId);
        $frm = new Form('frmLessonPackage');
        $frm->addHiddenField('', 'lpackage_id', $lPackageId);
        $frm->addRequiredField(Label::getLabel('LBL_Lesson_Package_Identifier', $this->adminLangId), 'lpackage_identifier');
        $fld = $frm->addFloatField(Label::getLabel('LBL_Package_Lessons', $this->adminLangId), 'lpackage_lessons');
        $fld->requirements()->setRequired(true);
        if ($isFreeTrial == applicationConstants::NO) {
            $fld->requirements()->setFloatPositive();
            $fld->requirements()->setRange(1, 9999);
        }
        $fld->htmlAfterField = '<small>' . Label::getLabel('LBL_Note:_Enter_Number_of_lessons_in_a_package') . '</small>';
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'lpackage_active', $activeInactiveArr, '', [], '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getLangForm($lPackageId = 0, $lang_id = 0)
    {
        $frm = new Form('frmLessonPackageLang');
        $frm->addHiddenField('', 'lpackage_id', $lPackageId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_Lesson_Package_Title', $this->adminLangId), 'lpackage_title');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

}
