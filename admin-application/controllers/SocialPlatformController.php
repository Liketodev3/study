<?php
class SocialPlatformController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewSocialPlatforms();
    }
    public function index()
    {
        $this->_template->render();
    }
    public function search()
    {
        $srch = SocialPlatform::getSearchObject($this->adminLangId, false);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('splatform_user_id', '=', 0);
        $rs = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditSocialPlatforms($adminId, true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->_template->render(false, false);
    }
    public function form($splatform_id = 0)
    {
        $splatform_id = FatUtility::int($splatform_id);
        $frm = $this->getForm();
        if (0 < $splatform_id) {
            $data = SocialPlatform::getAttributesById($splatform_id);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('splatform_id', $splatform_id);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
    public function setup()
    {
        $this->objPrivilege->canEditSocialPlatforms();
        $frm  = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $splatform_id = $post['splatform_id'];
        unset($post['splatform_id']);
        $data_to_be_save = $post;
        $recordObj = new SocialPlatform($splatform_id);
        $recordObj->assignValues($data_to_be_save, true);
        if (!$recordObj->save()) {
            Message::addErrorMessage($recordObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $splatform_id = $recordObj->getMainTableRecordId();
        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = SocialPlatform::getAttributesByLangId($langId, $splatform_id)) {
                $newTabLangId = $langId;
                break;
            }
        }
        if ($newTabLangId == 0 && !$this->isMediaUploaded($splatform_id)) {
            $this->set('openMediaForm', true);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('splatformId', $splatform_id);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }
    public function langForm($splatform_id = 0, $lang_id = 0)
    {
        $splatform_id = FatUtility::int($splatform_id);
        $lang_id = FatUtility::int($lang_id);
        if ($splatform_id == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm  = $this->getLangForm($splatform_id, $lang_id);
        $langData = SocialPlatform::getAttributesByLangId($lang_id, $splatform_id);
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('splatform_id', $splatform_id);
        $this->set('splatform_lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }
    public function langSetup()
    {
        $this->objPrivilege->canEditSocialPlatforms();
        $post = FatApp::getPostedData();
        $splatform_id = FatUtility::int($post['splatform_id']);
        $lang_id = $post['lang_id'];
        if ($splatform_id == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getLangForm($splatform_id, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['splatform_id']);
        unset($post['lang_id']);
        $data_to_update = array(
            'splatformlang_splatform_id' => $splatform_id,
            'splatformlang_lang_id' => $lang_id,
            'splatform_title' => $post['splatform_title']
        );
        $socialObj = new SocialPlatform($splatform_id);
        if (!$socialObj->updateLangData($lang_id, $data_to_update)) {
            Message::addErrorMessage($socialObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = SocialPlatform::getAttributesByLangId($langId, $splatform_id)) {
                $newTabLangId = $langId;
                break;
            }
        }
        if ($newTabLangId == 0 && !$this->isMediaUploaded($splatform_id)) {
            $this->set('openMediaForm', true);
        }
        $this->set('msg', Label::getLabel('LBL_Social_Platform_Setup_Successful', $this->adminLangId));
        $this->set('splatformId', $splatform_id);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }
    public function mediaForm($splatform_id)
    {
        $splatform_id = FatUtility::int($splatform_id);
        $splatformDetail = SocialPlatform::getAttributesById($splatform_id);
        if (false == $splatformDetail) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getMediaForm($splatform_id);
        if (!false == $splatformDetail) {
            $img = AttachedFile::getAttachment(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatform_id);
            $this->set('img', $img);
        }
        $this->set('splatform_id', $splatform_id);
        $this->set('frm', $frm);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    }
    public function setUpImage($splatform_id)
    {
        $splatform_id = FatUtility::int($splatform_id);
        if (!$splatform_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $post = FatApp::getPostedData();
        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Label::getLabel('LBL_Please_select_a_file', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $fileHandlerObj = new AttachedFile();
        $fileHandlerObj->deleteFile(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatform_id);
        if (!$res = $fileHandlerObj->saveAttachment($_FILES['file']['tmp_name'], AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatform_id, 0, $_FILES['file']['name'], -1)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('file', $_FILES['file']['name']);
        $this->set('splatform_id', $splatform_id);
        $this->set('msg', $_FILES['file']['name'] . ' ' . Label::getLabel('LBL_Uploaded_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
    public function removeImage($splatform_id)
    {
        $splatform_id = FatUtility::int($splatform_id);
        if (!$splatform_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $fileHandlerObj = new AttachedFile();
        if (!$fileHandlerObj->deleteFile(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatform_id)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('LBL_Deleted_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
    public function deleteRecord()
    {
        $this->objPrivilege->canEditSocialPlatforms();
        $reasonId = FatApp::getPostedData('splatformId', FatUtility::VAR_INT, 0);
        if ($reasonId < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $obj = new SocialPlatform($reasonId);
        if (!$obj->deleteRecord(true)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }
    public function changeStatus()
    {
        $this->objPrivilege->canEditSocialPlatforms();
        $splatformId = FatApp::getPostedData('splatformId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (0 >= $splatformId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $data = SocialPlatform::getAttributesById($splatformId, array(
            'splatform_id',
            'splatform_active'
        ));
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        //$status = ( $data['splatform_active'] == applicationConstants::ACTIVE ) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;
        $socialPlatObj = new SocialPlatform($splatformId);
        if (!$socialPlatObj->changeStatus($status)) {
            Message::addErrorMessage($socialPlatObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_update_record);
    }
    private function isMediaUploaded($splatformId)
    {
        if ($attachment = AttachedFile::getAttachment(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatformId, 0)) {
            return true;
        }
        return false;
    }
    private function getForm()
    {
        $frm = new Form('frmSocialPlatform');
        $frm->addHiddenField('', 'splatform_id', 0);
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Identifier', $this->adminLangId), 'splatform_identifier');
        $fld->setUnique('tbl_navigations', 'splatform_identifier', 'splatform_id', 'splatform_id', 'splatform_id');
        $frm->addRequiredField(Label::getLabel('LBL_URL', $this->adminLangId), 'splatform_url');
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'splatform_active', $activeInactiveArr, '', array(), '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    private function getLangForm($splatform_id = 0, $lang_id = 0)
    {
        $frm = new Form('frmSocialPlatformLang');
        $frm->addHiddenField('', 'splatform_id', $splatform_id);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_Title', $this->adminLangId), 'splatform_title');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Update', $this->adminLangId));
        return $frm;
    }
    private function getMediaForm($splatform_id = 0)
    {
        $frm = new Form('frmSocialPlatformMedia');
        $frm->addHiddenField('', 'splatform_id', $splatform_id);
        $fld = $frm->addButton(Label::getLabel('LBL_Icon_Image', $this->adminLangId), 'image', Label::getLabel('LBL_Upload_File', $this->adminLangId), array(
            'class' => 'File-Js',
            'id' => 'image',
            'data-splatform_id' => $splatform_id
        ));
        return $frm;
    }
    public function SocialPlatformImage($splatform_id, $sizeType = '')
    {
        $default_image = 'brand_deafult_image.jpg';
        $splatform_id  = FatUtility::int($splatform_id);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatform_id);
        $image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 200;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
                break;
            default:
                $w = 30;
                $h = 30;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
                break;
        }
    }
}
