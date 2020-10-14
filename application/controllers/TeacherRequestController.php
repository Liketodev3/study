<?php
class TeacherRequestController extends MyAppController {
	private $userId;
	public function __construct($action) {
		parent::__construct($action);
		//echo UserAuthentication::getGuestTeacherUserId(); die;
		if (UserAuthentication::getLoggedUserId(true)) {
			$this->userId = UserAuthentication::getLoggedUserId();
		} elseif (UserAuthentication::getGuestTeacherUserId()) {
			$this->userId = UserAuthentication::getGuestTeacherUserId();
		} else {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access'));
			CommonHelper::redirectUserReferer();
		}
	}

	public function index() {
		$srch = new TeacherRequestSearch();
		$srch->joinUsers();
		$srch->addCondition('utrequest_user_id', '=', $this->userId);
		$srch->addMultiplefields(array(
			'utrequest_attempts',
			'utrequest_id',
			'utrequest_status',
			'concat(user_first_name, " ", user_last_name) as user_name',
			'utrequest_reference'
		));
		$srch->addOrder('utrequest_id','desc');
		$rs = $srch->getResultSet();
		$teacherRequestRow = FatApp::getDb()->fetch($rs);
		// print_r($teacherRequestRow);
		// die;
		if (!$teacherRequestRow) {
			FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest', 'form'));
		}
		$this->set('teacherRequestRow', $teacherRequestRow);
		$this->_template->render();
	}

	public function form() {
		$teacherRequestRow = TeacherRequest::getData($this->userId);
		if (false !== $teacherRequestRow && $teacherRequestRow['utrequest_status'] == TeacherRequest::STATUS_APPROVED) {
			if (true !== User::canAccessTeacherDashboard()) {
				die("Please contact web master, Though your teacher request is approved, But Still not marked as teacher.");
			}
			FatApp::redirectUser(CommonHelper::generateUrl('Teacher'));
		}

		if (false !== $teacherRequestRow && $teacherRequestRow['utrequest_status'] == TeacherRequest::STATUS_PENDING) {
			FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
		}
		/* check if maximum attempts reached [ */
		if (true === TeacherRequest::isMadeMaximumAttempts($this->userId)) {
			Message::addErrorMessage(Label::getLabel('MSG_You_already_consumed_max_attempts'));
			FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
		}
		/* ] */
		$frm = $this->getForm();
		$userDetails = User::getAttributesById($this->userId,array('user_first_name','user_last_name'));

		if ($userDetails) {
			$assignArray = array();
			$assignArray['utrvalue_user_first_name'] = $userDetails['user_first_name'];
			$assignArray['utrvalue_user_last_name'] = $userDetails['user_last_name'];
			$frm->fill($assignArray);
		}
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$post = $frm->getFormDataFromArray(FatApp::getPostedData());
			$frm->fill($post);
		}
        
        /* [ */
        $cPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_TERMS_AND_CONDITIONS_PAGE', FatUtility::VAR_INT, 0));
        $cpage = FatApp::getDb()->fetch($cPageSrch->getResultSet());
        if (!empty($cpage) && is_array($cpage)) {
            $termsAndConditionsLinkHref = CommonHelper::generateUrl('Cms', 'view', array($cpage['cpage_id']));
        } else {
            $termsAndConditionsLinkHref = 'javascript:void(0)';
        }
        $this->set('termsAndConditionsLinkHref', $termsAndConditionsLinkHref);
        /* ] */
        
		$websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_'.$this->siteLangId, FatUtility::VAR_STRING, '');
		$this->set('websiteName', $websiteName);
		$this->set('frm', $frm);
		$this->_template->render(true, true, 'teacher-request/form.php');
	}

	public function setUpTeacherApproval() {
		/* check if maximum attempts reached [ */
		if (true === TeacherRequest::isMadeMaximumAttempts($this->userId)) {
			$msg1 = Label::getLabel('MSG_You_already_consumed_max_attempts');
			$msg2 = Label::getLabel('MSG_Visit_previous_request_page_{teacher-request-url}');
			$msg2 = str_replace("{teacher-request-url}", "<a href='" . CommonHelper::generateUrl('TeacherRequest') . "'>" . Label::getLabel('LBL_Click') . "</a>", $msg2);
			if (FatUtility::isAjaxCall()) {
				FatUtility::dieWithError($msg1 . ' ' . $msg2);
			}
			Message::addErrorMessage($msg1 . ' ' . $msg2);
			$this->form();
			return;
		}
		/* ] */
		/* Validation[ */
		$frm = $this->getForm(false);
		$post = $frm->getFormDataFromArray(FatApp::getPostedData());

		if (false === $post) {
			if (FatUtility::isAjaxCall()) {
				FatUtility::dieWithError(current($frm->getValidationErrors()));
			}
			Message::addErrorMessage(current($frm->getValidationErrors()));
			$this->form();
			return;
		}
		/* ] */
		$srch = new UserQualificationSearch();
		$srch->addCondition('uqualification_user_id', '=', $this->userId);
		$srch->addMultiplefields(['uqualification_id','uqualification_user_id']);
		$rs = $srch->getResultSet();
		if(empty($rs->totalRecords())) {
			if (FatUtility::isAjaxCall()) {
				FatUtility::dieWithError(Label::getLabel('MSG_please_upload_Your_Resume'));
			}
			Message::addErrorMessage(Label::getLabel('MSG_please_upload_Your_Resume'));
			$this->form();
			return;
		}
		/* file handling[ */
		if (empty($_FILES['user_profile_pic']['tmp_name']) || !is_uploaded_file($_FILES['user_profile_pic']['tmp_name'])) {
			if (FatUtility::isAjaxCall()) {
				FatUtility::dieWithError(Label::getLabel('MSG_Please_select_a_Profile_Pic'));
			}
			Message::addErrorMessage(Label::getLabel('MSG_Please_select_a_Profile_Pic'));
			$this->form();
			return;
		}

		$userId = $this->userId;
		$fileHandlerObj = new AttachedFile();
		/* Profile Pic[ */
		if (!$fileHandlerObj->saveImage($_FILES['user_profile_pic']['tmp_name'], $fileHandlerObj::FILETYPE_TEACHER_APPROVAL_USER_PROFILE_IMAGE, $userId, 0, $_FILES['user_profile_pic']['name'], -1, true)
		) {
			if (FatUtility::isAjaxCall()) {
				FatUtility::dieWithError($fileHandlerObj->getError());
			}
			Message::addErrorMessage($fileHandlerObj->getError());
			$this->form();
			return;
		}
		/* ] */
		/* Proof File[ */
		if (!empty($_FILES['user_photo_id']['tmp_name'])) {
			$fileHandlerObj = new AttachedFile();
			if (!$fileHandlerObj->saveDoc($_FILES['user_photo_id']['tmp_name'], $fileHandlerObj::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF, $userId, 0, $_FILES['user_photo_id']['name'], -1, true)) {
				if (FatUtility::isAjaxCall()) {
					FatUtility::dieWithError($fileHandlerObj->getError());
				}
				Message::addErrorMessage($fileHandlerObj->getError());
				$this->form();
				return;
			}
		}
		/* ] */
		/* ] */
		$teacherRequest = new TeacherRequest();
		if (true !== $teacherRequest->saveData($post, $this->userId)) {
			if (FatUtility::isAjaxCall()) {
				FatUtility::dieWithError($teacherRequest->getError());
			}
			Message::addErrorMessage($teacherRequest->getError());
			$this->form();
			return;
		}

		$successMsg =  Label::getLabel('MSG_Teacher_Approval_Request_Setup_Successful');
		if (FatUtility::isAjaxCall()) {
			$this->set('redirectUrl', CommonHelper::generateUrl('TeacherRequest'));
			$this->set('msg', $successMsg);
			$this->_template->render(false, false, 'json-success.php');
		}
		Message::addMessage($successMsg);
		FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
	}

	public function searchTeacherQualification() {
		$srch = new UserQualificationSearch();
		$srch->addCondition('uqualification_user_id', '=', $this->userId);
		$srch->addMultiplefields(
			array(
				'uqualification_id',
				'uqualification_title',
				'uqualification_experience_type',
				'uqualification_start_year',
				'uqualification_end_year',
				'uqualification_institute_address',
				'uqualification_institute_name',
			));
		$rs = $srch->getResultSet();
		$rows = FatApp::getDb()->fetchAll($rs);
		$this->set("userId", $this->userId);
		$this->set("rows", $rows);
		$this->_template->render(false, false);
	}

	public function teacherQualificationForm() {
		$uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
		$frm = $this->getTeacherQualificationForm(true);
		if ($uqualification_id > 0) {
			$srch = new UserQualificationSearch();
			$srch->addCondition('uqualification_user_id', '=', $this->userId);
			$srch->addCondition('uqualification_id', '=', $uqualification_id);
			$rs = $srch->getResultSet();
			$row = FatApp::getDb()->fetch($rs);
			$frm->fill($row);
			$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $this->userId , $uqualification_id);
			$field = $frm->getField('certificate');
			$certificateRequried =  (empty($file_row)) ? true : false;
			$field->requirements()->setRequired($certificateRequried);
		}
		$this->set('frm', $frm);
		$this->_template->render(false, false);
	}

	public function setUpTeacherQualification() {
		$frm = $this->getTeacherQualificationForm();
		$post = $frm->getFormDataFromArray(FatApp::getPostedData());
		if (false === $post) {
			Message::addErrorMessage(current($frm->getValidationErrors()));
			FatUtility::dieJsonError(Message::getHtml());
		}

		$uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
		$qualification  = new UserQualification($uqualification_id);
		$post['uqualification_user_id'] = $this->userId;
        $db = FatApp::getDb();
        $db->startTransaction();
		$qualification->assignValues($post);
		if (true !== $qualification->save()) {
       		$db->rollbackTransaction();
			Message::addErrorMessage($qualification->getError());
			FatUtility::dieJsonError(Message::getHtml());
		}

		/* file handling[ */
		/* $file_row = [];
		if($uqualification_id > 0) {
			$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $this->userId ,$uqualification_id);
		}
		if(empty($file_row) && empty($_FILES['certificate']['tmp_name'])) {
			$db->rollbackTransaction();
			Message::addErrorMessage(Label::getLabel('MSG_Please_upload_certificate'));
			FatUtility::dieJsonError(Message::getHtml());
		} */

		if (!empty($_FILES['certificate']['tmp_name'])) {
			if (!is_uploaded_file($_FILES['certificate']['tmp_name'])) {
           		$db->rollbackTransaction();
				Message::addErrorMessage(Label::getLabel('LBL_Please_select_a_file'));
				FatUtility::dieJsonError(Message::getHtml());
			}

			$uqualification_id = $qualification->getMainTableRecordId();
			$fileHandlerObj = new AttachedFile();
			$res = $fileHandlerObj->saveDoc($_FILES['certificate']['tmp_name'], AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $post['uqualification_user_id'], $uqualification_id, $_FILES['certificate']['name'], -1, $unique_record = true,0,$_FILES['certificate']['type']);
			if (!$res) {
                $db->rollbackTransaction();
				Message::addErrorMessage($fileHandlerObj->getError());
				FatUtility::dieJsonError(Message::getHtml());
			}
		}
		$db->commitTransaction();
		$this->set('msg', Label::getLabel('MSG_Qualification_Setup_Successful'));
		$this->_template->render(false, false, 'json-success.php');
	}

	public function deleteTeacherQualification() {
		$uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
		if ($uqualification_id < 1) {
			Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
			FatUtility::dieWithError(Message::getHtml());
		}

		/* [ */
		$srch = new UserQualificationSearch();
		$srch->addCondition('uqualification_user_id', '=', $this->userId);
		$srch->addCondition('uqualification_id', '=', $uqualification_id);
		$srch->addMultiplefields(array('uqualification_id'));
		$rs = $srch->getResultSet();
		$row = FatApp::getDb()->fetch($rs);
		if (false == $row) {
			Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
			FatUtility::dieWithError(Message::getHtml());
		}
		/* ] */
		$userQualification = new UserQualification($uqualification_id);
		if (true !== $userQualification->deleteRecord()) {
			Message::addErrorMessage($userQualification->getError());
			FatUtility::dieWithError(Message::getHtml());
		}
		$this->set('msg', Label::getLabel('MSG_Qualification_Removed_Successfuly'));
		$this->_template->render(false, false, 'json-success.php');
	}

	public function downloadResume($subRecordId) {
		$subRecordId = FatUtility::int($subRecordId);
		if ($subRecordId < 1) {
			Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
			FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest', 'form'));
		}
		$fileRow = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $this->userId, $subRecordId);

		if (!$fileRow || $fileRow['afile_physical_path'] == "") {
			Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
			FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest', 'form'));
		}
		AttachedFile::downloadFile($fileRow['afile_name'], $fileRow['afile_physical_path']);
	}

	private function getForm($profilePic = true) {
		$langId = $this->siteLangId;
		/* [ */
		$spokenLanguagesArr = SpokenLanguage::getAllLangs($langId, true);
		$teachingLanguagesArr = TeachingLanguage::getAllLangs($langId, true);
		/* ] */
		$frm = new Form('frmTeacherApprovalForm');
		$frm->addHtml('', 'general_fields_heading', '');
		$fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'utrvalue_user_first_name');
        $fld->requirements()->setCharOnly();
		$fld = $frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'utrvalue_user_last_name');
        $fld->requirements()->setCharOnly();
		$fld = $frm->addRadioButtons(Label::getLabel('LBL_Gender'), 'utrvalue_user_gender', User::getGenderArr($langId), User::GENDER_MALE);
		$fld->requirements()->setRequired();
		$fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone_Number'), 'utrvalue_user_phone');
        $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
        if ($profilePic) {
            $fld = $frm->addFileUpload(Label::getLabel('LBL_Profile_Picture'), 'user_profile_pic');
            $fld->requirements()->setRequired();
            $fld->htmlAfterField = "<small>".Label::getLabel('LBL_User_Profile_Image_Dimension')."</small>";
        }
		$fld = $frm->addFileUpload(Label::getLabel('LBL_Photo_Id'), 'user_photo_id');
        $fld->htmlAfterField = "<small>".Label::getLabel('LBL_Photo_Id_Type')."</small>";
		$frm->addHtml('', 'introduction_fields_heading', '');
		$frm->addTextBox(Label::getLabel('LBL_Video_Youtube_Link'), 'utrvalue_user_video_link');
		$frm->addHtml('', 'about_me_fields_heading', '');
		$fld = $frm->addTextArea(Label::getLabel('LBL_Write_about_yourself_and_your_qualifications'), 'utrvalue_user_profile_info');
        $fld->requirements()->setLength(1, 500);
		$frm->addHtml('', 'language_fields_heading', '');
		$fld = $frm->addSelectBox(Label::getLabel('LBL_What_Language_Do_You_Want_To_Teach?'), 'utrvalue_user_teach_slanguage_id[]', $teachingLanguagesArr);
		$fld->requirements()->setRequired();
		$fld = $frm->addHtml('', 'add_anotherteach_language', '');
		$fld = $frm->addSelectBox(Label::getLabel('LBL_Languages_You_Speak'), 'utrvalue_user_language_speak[]', $spokenLanguagesArr);
		$fld->requirements()->setRequired();
		$fldProficiency = $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utrvalue_user_language_speak_proficiency[]', SpokenLanguage::getProficiencyArr($langId));
		$fldProficiency->requirements()->setRequired();
		$fld = $frm->addHtml('', 'add_anotherspoken_language', '');
		/* Resume[ */
		$frm->addHtml('', 'resume_fields_heading', '');
		$frm->addHtml('', 'resume_listing_html', '');
		/* ] */
		$fld = $frm->addCheckBox(Label::getLabel('LBL_Accept_Teacher_Approval_Terms_&_condition'), 'terms', 1);
		$fld->requirements()->setRequired();
		$frm->addSubmitButton('', 'btn_submit',Label::getLabel('LBL_Save_Changes'));
		return $frm;
	}
}
