<?php
class CourseCategoriesController extends AdminBaseController
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
        $this->objPrivilege->canViewCourseCategory();
    }

    public function index()
    {
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditCourseCategory($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }

    public function search()
    {
        $srch = CourseCategory::getSearchObject($this->adminLangId, false);
        $srch->addMultipleFields(array(
            't.ccategory_id',
            't.ccategory_active',
            't.ccategory_identifier',
            't_l.ccategory_title',
        ));
        $srch->addOrder('ccategory_active', 'desc');
        $rs      = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditCourseCategory($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false);
    }

    public function form($cCategoryId)
    {
        $cCategoryId = FatUtility::int($cCategoryId);
        $frm           = $this->getForm($cCategoryId);
        if (0 < $cCategoryId) {
            $data = CourseCategory::getAttributesById($cCategoryId, array(
                'ccategory_id',
                'ccategory_identifier',
                'ccategory_active',

            ));
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('cCategoryId', $cCategoryId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditCourseCategory();
        $frm  = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $cCategoryId = $post['ccategory_id'];
        unset($post['ccategory_id']);
        if ($cCategoryId == 0) {
            $post['ccategory_added_on'] = date('Y-m-d H:i:s');
        }
        $record = new CourseCategory($cCategoryId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        if ($cCategoryId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = CourseCategory::getAttributesByLangId($langId, $cCategoryId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $cCategoryId = $record->getMainTableRecordId();
            $newTabLangId  = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('cCategoryId', $cCategoryId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($cCategoryId = 0, $lang_id = 0)
    {
        $cCategoryId = FatUtility::int($cCategoryId);
        $lang_id       = FatUtility::int($lang_id);
        if ($cCategoryId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm  = $this->getLangForm($cCategoryId, $lang_id);
        $langData = CourseCategory::getAttributesByLangId($lang_id, $cCategoryId);
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('cCategoryId', $cCategoryId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditCourseCategory();
        $post          = FatApp::getPostedData();
        $cCategoryId = $post['ccategory_id'];
        $lang_id       = $post['lang_id'];
        if ($cCategoryId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm  = $this->getLangForm($cCategoryId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        unset($post['ccategory_id']);
        unset($post['lang_id']);
        $data = array(
            'ccategorylang_lang_id' => $lang_id,
            'ccategorylang_ccategory_id' => $cCategoryId,
            'ccategory_title' => $post['ccategory_title'],
        );
        $obj  = new CourseCategory($cCategoryId);
        if (!$obj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages    = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = CourseCategory::getAttributesByLangId($langId, $cCategoryId)) {
                $newTabLangId = $langId;
                break;
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('cCategoryId', $cCategoryId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditCourseCategory();
        $cCategoryId = FatApp::getPostedData('cCategoryId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (0 >= $cCategoryId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $data = CourseCategory::getAttributesById($cCategoryId, array(
            'ccategory_id',
            'ccategory_active'
        ));
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $obj    = new CourseCategory($cCategoryId);
        if (!$obj->changeStatus($status)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_update_record);
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditCourseCategory();
        $ccategory_id = FatApp::getPostedData('cCategoryId', FatUtility::VAR_INT, 0);
        if ($ccategory_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $CourseCategoryObj = new CourseCategory($ccategory_id);
        if (!$CourseCategoryObj->canRecordMarkDelete($ccategory_id)) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $CourseCategoryObj->assignValues(array(
            CourseCategory::tblFld('deleted') => 1
        ));
        if (!$CourseCategoryObj->save()) {
            Message::addErrorMessage($CourseCategoryObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function getForm($cCategoryId = 0)
    {
        $cCategoryId = FatUtility::int($cCategoryId);
        $frm           = new Form('frmCourseCategory');
        $frm->addHiddenField('', 'ccategory_id', $cCategoryId);
        $frm->addRequiredField(Label::getLabel('LBL_Course_Category_Identifier', $this->adminLangId), 'ccategory_identifier');
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'ccategory_active', $activeInactiveArr, '', array(), '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getLangForm($cCategoryId = 0, $lang_id = 0)
    {
        $frm = new Form('frmCourseCategoryLang');
        $frm->addHiddenField('', 'ccategory_id', $cCategoryId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_Course_Category_Title', $this->adminLangId), 'ccategory_title');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
