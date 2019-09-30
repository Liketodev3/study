<?php 
class IssueReportOptionsController extends AdminBaseController
{
    public function __construct($action)
    {
		parent::__construct($action);
        $this->objPrivilege->canViewPreferences();
    }
    
	public function index()
    {
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditPreferences($this->admin_id, true);
		$frmSearch = $this->getSearchForm();
		$this->set('frmSearch', $frmSearch);
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }
    
	public function search()
    {
		$data       = FatApp::getPostedData();
		$searchForm = $this->getSearchForm();
		$post       = $searchForm->getFormDataFromArray($data);
		
		$issoptobj = new IssueReportOptions( $this->adminLangId );
        $srch = $issoptobj->getPreferencesArr($this->adminLangId, false);
        /* $srch->addMultipleFields(array(
            'tlanguage_id',
            'tlanguage_code',
            'tlanguage_identifier',
            'tlanguage_active',
            'tlanguage_name',
        )); */
		
		/* if (!empty($post['keyword'])) {
            $srch->addCondition('tlanguage_identifier', 'like', '%' . $post['keyword'] . '%');
        } */
		
        //$srch->addOrder('tlanguage_active', 'desc');
		
		$rs      = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
		
		echo "<pre>"; print_r( $records  ); echo "</pre>"; exit;
		
        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit = $this->objPrivilege->canEditPreferences($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false);
    }
    
	public function form($optId)
    {
        $optId = FatUtility::int($optId);
        $frm           = $this->getForm($optId);
        if (0 < $optId) {
            $data = IssueReportOptions::getAttributesById($optId, array(
                'tissueopt_id',
                'tissueopt_identifier',
                'tissueopt_display_order'
            ));
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('optId', $optId);
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
        $optId = $post['tissueopt_id'];
        unset($post['tissueopt_id']);
       
        $record = new IssueReportOptions($optId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        if ($optId > 0) {
            $languages = Language::getAllNames();
			foreach ($languages as $langId => $langName) {
                if (!$row = IssueReportOptions::getAttributesByLangId($langId, $optId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $optId = $record->getMainTableRecordId();
            $newTabLangId  = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('optId', $optId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }
    
	public function langForm($optId = 0, $lang_id = 0)
    {
        $optId = FatUtility::int($optId);
        $lang_id       = FatUtility::int($lang_id);
        if ($optId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm  = $this->getLangForm($optId, $lang_id);
        $langData = IssueReportOptions::getAttributesByLangId($lang_id, $optId);
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('optId', $optId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }
    
	public function langSetup()
    {
        $this->objPrivilege->canEditPreferences();
        $post          = FatApp::getPostedData();
        $optId = $post['tissueopt_id'];
        $lang_id       = $post['lang_id'];
        if ($optId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm  = $this->getLangForm($optId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['tissueopt_id']);
        unset($post['lang_id']);
        $data = array(
            'otplang_lang_id' => $lang_id,
            'otplang_title' => $post['otplang_title']
        );
        $obj  = new IssueReportOptions($optId);
        if (!$obj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages    = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = IssueReportOptions::getAttributesByLangId($langId, $tLangId)) {
                $newTabLangId = $langId;
                break;
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('tLangId', $tLangId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }
    
	public function changeStatus()
    {
        $this->objPrivilege->canEditPreferences();
        $sLangId = FatApp::getPostedData('tLangId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (0 >= $sLangId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $data = TeachingLanguage::getAttributesById($sLangId, array(
            'tlanguage_id',
            'tlanguage_active'
        ));
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        //$status = ($data['lpackage_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;
        $obj    = new TeachingLanguage($sLangId);
        if (!$obj->changeStatus($status)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_update_record);
    }
    
	public function deleteRecord()
    {
        $this->objPrivilege->canEditPreferences();
        $tlanguage_id = FatApp::getPostedData('tLangId', FatUtility::VAR_INT, 0);
        if ($tlanguage_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $LessonPackageObj = new TeachingLanguage($tlanguage_id);
        if (!$LessonPackageObj->deleteRecord($tlanguage_id)) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }
	
	private function getForm( $sLangId = 0 )
    {
        $sLangId = FatUtility::int($sLangId);
        $frm           = new Form('frmIssueReoprtOption');
        $frm->addHiddenField('', 'tissueopt_id', $sLangId);
        $frm->addRequiredField(Label::getLabel('LBL_Option_Identifier', $this->adminLangId), 'tissueopt_identifier');
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'tissueopt_active', $activeInactiveArr, '', array(), '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    
	private function getLangForm($optId = 0, $lang_id = 0)
    {
        $frm = new Form('frmIssueReoprtOption');
        $frm->addHiddenField('', 'optId', $optId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $frm->addRequiredField(Label::getLabel('LBL_Option_Identifier', $this->adminLangId), 'otplang_title');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    public function mediaForm($tLangId)
    {
        $tLangId = FatUtility::int($tLangId);
        if (1 > $tLangId) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $sLangDetail = TeachingLanguage::getAttributesById($tLangId);
        if (!false == $sLangDetail && ($sLangDetail['tlanguage_active'] != applicationConstants::ACTIVE)) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_request_Or_Inactive_Record', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $mediaFrm = $this->getMediaForm($tLangId);
        if (!false == $sLangDetail) {
            $slangImage = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_TEACHING_LANGUAGES, $tLangId, 0, -1);
            $this->set('tlangImage', $slangImage);
        }
        $this->set('mediaFrm', $mediaFrm);
        $this->set('languages', Language::getAllNames());
        $this->set('tLangId', $tLangId);
        $this->_template->render(false, false);
    }

    private function getMediaForm($tlanguage_id)
    {
        $frm = new Form('frmTeachingLanguageMedia');
        $frm->addHiddenField('', 'tlanguage_id', $tlanguage_id);
        $fld = $frm->addButton(Label::getLabel('LBL_Language_Image', $this->adminLangId), 'tlanguage_image', Label::getLabel('LBL_Upload_File', $this->adminLangId), array(
            'class' => 'tlanguageFile-Js',
            'id' => 'tlanguage_image',
            'data-tlanguage_id' => $tlanguage_id
        ));
        $fld = $frm->addButton(Label::getLabel('LBL_Language_Flag_Image', $this->adminLangId), 'tlanguage_flag_image', Label::getLabel('LBL_Upload_File', $this->adminLangId), array(
            'class' => 'tlanguageFlagFile-Js',
            'id' => 'tlanguage_flag_image',
            'data-tlanguage_id' => $tlanguage_id
        ));        
        return $frm;
    }
    
    public function images($tlanguage_id, $lang_id = 0, $screen = 0)
    {
        $tlanguage_id = FatUtility::int($tlanguage_id);
        if (1 > $tlanguage_id) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $sLanguageDetail = TeachingLanguage::getAttributesById($tlanguage_id);
        if (!false == $sLanguageDetail && ($sLanguageDetail['tlanguage_active'] != applicationConstants::ACTIVE)) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_request_Or_Inactive_Record', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $imgType = AttachedFile::FILETYPE_TEACHING_LANGUAGES;

        if (!false == $sLanguageDetail) {
            $languageImgArr = AttachedFile::getMultipleAttachments($imgType, $tlanguage_id, 0, $lang_id, false, $screen);
            $this->set('images', $languageImgArr);
        }
        $admin_id = AdminAuthentication::getLoggedAdminId();
        $canEdit  = $this->objPrivilege->canEditPreferences($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->set('languages', Language::getAllNames());
        $this->set('tlanguage_id', $tlanguage_id);
        $this->_template->render(false, false);
    }
    
    public function flagImages($tlanguage_id, $lang_id = 0, $screen = 0)
    {
        $tlanguage_id = FatUtility::int($tlanguage_id);
        if (1 > $tlanguage_id) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $sLanguageDetail = TeachingLanguage::getAttributesById($tlanguage_id);
        if (!false == $sLanguageDetail && ($sLanguageDetail['tlanguage_active'] != applicationConstants::ACTIVE)) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_request_Or_Inactive_Record', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $imgType = AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES;

        if (!false == $sLanguageDetail) {
            $languageImgArr = AttachedFile::getMultipleAttachments($imgType, $tlanguage_id, 0, $lang_id, false, $screen);
            $this->set('images', $languageImgArr);
        }
        $admin_id = AdminAuthentication::getLoggedAdminId();
        $canEdit  = $this->objPrivilege->canEditPreferences($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->set('languages', Language::getAllNames());
        $this->set('tlanguage_id', $tlanguage_id);
        $this->_template->render(false, false);
    }
    
    public function upload($tlanguage_id)
    {
        $this->objPrivilege->canEditPreferences();
        $tlanguage_id = FatUtility::int($tlanguage_id);
        if (1 > $tlanguage_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $post = FatApp::getPostedData();

        if (empty($post)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request_Or_File_not_supported', $this->adminLangId));
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request_Or_File_not_supported', $this->adminLangId));
        }
        $lang_id       = FatUtility::int($post['lang_id']);
        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Please_Select_A_File', $this->adminLangId));
            FatUtility::dieJsonError(Label::getLabel('MSG_Please_Select_A_File', $this->adminLangId));
        }

        $imgType = AttachedFile::FILETYPE_TEACHING_LANGUAGES;

        $fileHandlerObj = new AttachedFile();
        $fileHandlerObj->deleteFile($imgType, $tlanguage_id);
        if (!$res = $fileHandlerObj->saveAttachment($_FILES['file']['tmp_name'], $imgType, $tlanguage_id, 0, $_FILES['file']['name'], -1, true, $lang_id)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError($fileHandlerObj->getError());
        }
        $this->set('tlanguage_id', $tlanguage_id);
        $this->set('file', $_FILES['file']['name']);
        $this->set('msg', $_FILES['file']['name'] . Label::getLabel('MSG_File_uploaded_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function flagUpload($tlanguage_id)
    {
        $this->objPrivilege->canEditPreferences();
        $tlanguage_id = FatUtility::int($tlanguage_id);
        if (1 > $tlanguage_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $post = FatApp::getPostedData();

        if (empty($post)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request_Or_File_not_supported', $this->adminLangId));
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request_Or_File_not_supported', $this->adminLangId));
        }
        $lang_id       = FatUtility::int($post['lang_id']);
        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Please_Select_A_File', $this->adminLangId));
            FatUtility::dieJsonError(Label::getLabel('MSG_Please_Select_A_File', $this->adminLangId));
        }

        $imgType = AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES;

        $fileHandlerObj = new AttachedFile();
        $fileHandlerObj->deleteFile($imgType, $tlanguage_id);
        if (!$res = $fileHandlerObj->saveAttachment($_FILES['file']['tmp_name'], $imgType, $tlanguage_id, 0, $_FILES['file']['name'], -1, true, $lang_id)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError($fileHandlerObj->getError());
        }
        $this->set('tlanguage_id', $tlanguage_id);
        $this->set('file', $_FILES['file']['name']);
        $this->set('msg', $_FILES['file']['name'] . Label::getLabel('MSG_File_uploaded_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
    public function removeImage($tlanguage_id)
    {
        $tlanguage_id = FatUtility::int($tlanguage_id);
        if (1 > $tlanguage_id) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $fileHandlerObj = new AttachedFile();

        $imgType = AttachedFile::FILETYPE_TEACHING_LANGUAGES;

        if (!$fileHandlerObj->deleteFile($imgType, $tlanguage_id, 0, 0)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Deleted_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function removeFlagImage($tlanguage_id)
    {
        $tlanguage_id = FatUtility::int($tlanguage_id);
        if (1 > $tlanguage_id) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $fileHandlerObj = new AttachedFile();

        $imgType = AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES;

        if (!$fileHandlerObj->deleteFile($imgType, $tlanguage_id, 0, 0)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Deleted_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }    

    public function Thumb($bannerId, $imgType, $langId = 0, $screen = 0)
    {
        $this->showBanner($bannerId, $imgType ,$langId, 100, 100, $screen);
    }
    public function showBanner($bannerId, $imgType, $langId, $w = '200', $h = '200', $screen = 0)
    {
        $bannerId   = FatUtility::int($bannerId);
        $langId     = FatUtility::int($langId);

        $fileRow    = AttachedFile::getAttachment($imgType, $bannerId, 0, $langId, true, $screen);
        $image_name = isset($fileRow['afile_physical_path']) ? $fileRow['afile_physical_path'] : '';

        AttachedFile::displayImage($image_name, $w, $h, '', '', ImageResize::IMG_RESIZE_EXTRA_ADDSPACE, false, true);
    } 

	public function updateOrder() {
		$post = FatApp::getPostedData();
		
		if (!empty($post)) {
			$teachLangObj = new TeachingLanguage();
			if(!$teachLangObj->updateOrder($post['teachingLangages'])){
				Message::addErrorMessage($teachLangObj->getError());
				FatUtility::dieJsonError( Message::getHtml() );
			}
			
			$this->set('msg', Label::getLabel('LBL_Order_Updated_Successfully',$this->adminLangId));
			$this->_template->render(false, false, 'json-success.php');
        } 
	}
	
	private function getSearchForm() {
        $frm = new Form('frmTeachingLanguageSearch');
        $f1 = $frm->addTextBox(Label::getLabel('LBL_Language_Identifier', $this->adminLangId), 'keyword', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
	
    
 }