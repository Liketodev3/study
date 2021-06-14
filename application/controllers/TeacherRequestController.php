<?php

class TeacherRequestController extends MyAppController
{
    private $userId;
    public function __construct($action)
    {
        parent::__construct($action);
        if (UserAuthentication::getLoggedUserId(true)) {
            $this->userId = UserAuthentication::getLoggedUserId();
        } elseif (UserAuthentication::getGuestTeacherUserId()) {
            $this->userId = UserAuthentication::getGuestTeacherUserId();
        } else {
            $this->userId = 0;
        }
    }
    public function index()
    {
        $applyTeachFrm = $this->getApplyTeachFrm($this->siteLangId);
        $sectionAfterBanner = ExtraPage::getBlockContent(ExtraPage::BLOCK_APPLY_TO_TEACH_BENEFITS_SECTION, $this->siteLangId);
        $featursSection = ExtraPage::getBlockContent(ExtraPage::BLOCK_APPLY_TO_TEACH_FEATURES_SECTION, $this->siteLangId);
        $becometutorSection = ExtraPage::getBlockContent(ExtraPage::BLOCK_APPLY_TO_TEACH_BECOME_A_TUTOR_SECTION, $this->siteLangId);
        $staticBannerSection = ExtraPage::getBlockContent(ExtraPage::BLOCK_APPLY_TO_TEACH_STATIC_BANNER, $this->siteLangId);

        $this->set('faqs', $this->getApplyToTeachFaqs());
        $this->set('sectionAfterBanner', $sectionAfterBanner);
        $this->set('featuresSection', $featursSection);
        $this->set('becometutorSection', $becometutorSection);
        $this->set('staticBannerSection', $staticBannerSection);
        $this->set('applyTeachFrm', $applyTeachFrm);
        $this->_template->render();
    }

    public function form()
    {
        $this->_template->addJs('js/jquery.form.js');
        $this->_template->addJs('js/cropper.js');
        $this->_template->addJs('js/jquery.inputmask.bundle.js');
        $this->_template->addJs('js/intlTelInput.js');
        $this->_template->addCss('css/intlTelInput.css');

        if (!$this->userId) {
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
        }

        $user = new User($this->userId);
        if (!$user->loadFromDb()) {
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
        }
        $spokenLangs = SpokenLanguage::getAllLangs($this->siteLangId, true);
        $frm = $this->getForm($this->siteLangId, $spokenLangs);
        $userDetails = $user->getFlds();
        if ($userDetails) {
            $assignArray = [];
            $assignArray['utrvalue_user_first_name'] = $userDetails['user_first_name'];
            $assignArray['utrvalue_user_last_name'] = $userDetails['user_last_name'];
            $frm->fill($assignArray);
        }
        $this->set('frm', $frm);
        $this->set('speakLangs', $spokenLangs);
        $this->set('exculdeMainHeaderDiv', false);
        $this->set('userDetails', $userDetails);
        $this->set('profileImgFrm', $this->getProfileImageForm());

        $this->_template->render(true, false);
    }

    public function setUpTeacherApproval()
    {

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

        $teacherRequest = $this->verifyTeacherRequestRow();
        if(in_array($teacherRequest['utrequest_id'],['']))
        /* ] */
        /* Validation[ */
        $frm = $this->getForm($this->siteLangId);
        $post = FatApp::getPostedData();

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
        $srch->addMultiplefields(['uqualification_id', 'uqualification_user_id']);
        $rs = $srch->getResultSet();
        if (empty($rs->totalRecords())) {
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError(Label::getLabel('MSG_please_upload_Your_Resume'));
            }
            Message::addErrorMessage(Label::getLabel('MSG_please_upload_Your_Resume'));
            $this->form();
            return;
        }

        /* file handling[ */
        if (!User::isProfilePicUploaded($this->userId)) {
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError(Label::getLabel('MSG_Please_select_a_Profile_Pic'));
            }
            Message::addErrorMessage(Label::getLabel('MSG_Please_select_a_Profile_Pic'));
            $this->form();
            return;
        }

        $userId = $this->userId;
        $fileHandlerObj = new AttachedFile();
        /* ] */
        if (!empty($_FILES['user_photo_id']['name']) && !empty($_FILES['user_photo_id']['error'])) {
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieJsonError($attachedFile->getErrMsgByErrCode($_FILES['user_photo_id']['error']));
            }
            Message::addErrorMessage($attachedFile->getErrMsgByErrCode($_FILES['user_photo_id']['error']));
            $this->form();
            return;
        }

        /* Proof File[ */
        if (!empty($_FILES['user_photo_id']['tmp_name'])) {
            $attachedFile = new AttachedFile();
            if (!$attachedFile->saveDoc($_FILES['user_photo_id']['tmp_name'], $attachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF, $userId, 0, $_FILES['user_photo_id']['name'], -1, true)) {
                if (FatUtility::isAjaxCall()) {
                    FatUtility::dieWithError($attachedFile->getError());
                }
                Message::addErrorMessage($attachedFile->getError());
                $this->form();
                return;
            }
        }
        /* ] */
        /* ] */
        $languageRow = Language::getAttributesById($this->siteLangId);
        $post['utrequest_language_id'] = $languageRow['language_id'];
        $post['utrequest_language_code'] = $languageRow['language_code'];
        $teacherRequest = new TeacherRequest();
        if (true !== $teacherRequest->saveData($post, $this->userId)) {
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError($teacherRequest->getError());
            }
            Message::addErrorMessage($teacherRequest->getError());
            $this->form();
            return;
        }

        if ($teacherRequest->getMainTableRecordId()) {
            $emailHandler = new EmailHandler($this->siteLangId);
            $emailHandler->sendTeacherRequestEmailToAdmin($teacherRequest->getMainTableRecordId());
        }

        $successMsg = Label::getLabel('MSG_Teacher_Approval_Request_Setup_Successful');
        if (FatUtility::isAjaxCall()) {
            $this->set('redirectUrl', CommonHelper::generateUrl('TeacherRequest'));
            $this->set('msg', $successMsg);
            $this->_template->render(false, false, 'json-success.php');
        }
        Message::addMessage($successMsg);
        FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
    }

    public function searchTeacherQualification()
    {
        $srch = new UserQualificationSearch();
        $srch->addCondition('uqualification_user_id', '=', $this->userId);
        $srch->addMultiplefields([

            'uqualification_id',
            'uqualification_title',
            'uqualification_experience_type',
            'uqualification_start_year',
            'uqualification_end_year',
            'uqualification_institute_address',
            'uqualification_institute_name',
        ]);

        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $this->set("userId", $this->userId);
        $this->set("rows", $rows);
        $this->_template->render(false, false);
    }

    public function teacherQualificationForm()
    {
        $uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
        $frm = $this->getTeacherQualificationForm(true);
        if ($uqualification_id > 0) {
            $srch = new UserQualificationSearch();
            $srch->addCondition('uqualification_user_id', '=', $this->userId);
            $srch->addCondition('uqualification_id', '=', $uqualification_id);
            $rs = $srch->getResultSet();
            $row = FatApp::getDb()->fetch($rs);
            $frm->fill($row);
            $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $this->userId, $uqualification_id);
            $field = $frm->getField('certificate');
            $certificateRequried = false; //(empty($file_row)) ? true : false;
            $field->requirements()->setRequired($certificateRequried);
        }
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setUpTeacherQualification()
    {
        $frm = $this->getTeacherQualificationForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
        $qualification = new UserQualification($uqualification_id);

        $post['uqualification_user_id'] = $this->userId;
        $db = FatApp::getDb();
        $db->startTransaction();
        $qualification->assignValues($post);
        if (true !== $qualification->save()) {
            $db->rollbackTransaction();
            Message::addErrorMessage($qualification->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $attachedFile = new AttachedFile();
        if (!empty($_FILES['certificate']['name']) && !empty($_FILES['certificate']['error'])) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($attachedFile->getErrMsgByErrCode($_FILES['certificate']['error']));
        }

        if (!empty($_FILES['certificate']['tmp_name'])) {
            if (!is_uploaded_file($_FILES['certificate']['tmp_name'])) {
                $db->rollbackTransaction();
                Message::addErrorMessage(Label::getLabel('LBL_Please_select_a_file'));
                FatUtility::dieJsonError(Message::getHtml());
            }

            $uqualification_id = $qualification->getMainTableRecordId();

            $res = $attachedFile->saveDoc($_FILES['certificate']['tmp_name'], AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $post['uqualification_user_id'], $uqualification_id, $_FILES['certificate']['name'], -1, $unique_record = true, 0, $_FILES['certificate']['type']);
            if (!$res) {
                $db->rollbackTransaction();
                Message::addErrorMessage($attachedFile->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
        }
        $db->commitTransaction();
        $this->set('msg', Label::getLabel('MSG_Qualification_Setup_Successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteTeacherQualification()
    {
        $uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
        if ($uqualification_id < 1) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        /* [ */
        $srch = new UserQualificationSearch();
        $srch->addCondition('uqualification_user_id', '=', $this->userId);
        $srch->addCondition('uqualification_id', '=', $uqualification_id);
        $srch->addMultiplefields(['uqualification_id']);
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

    public function downloadResume($subRecordId)
    {
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

    public function setUpProfileImage()
    {
        $userId = $this->userId;
        $post = FatApp::getPostedData();
        if (empty($post)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request_Or_File_not_supported'));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if ($post['action'] == "demo_avatar") {
            if (!is_uploaded_file($_FILES['user_profile_image']['tmp_name'])) {
                $msgLblKey = CommonHelper::getFileUploadErrorLblKeyFromCode($_FILES['user_profile_image']['error']);
                Message::addErrorMessage(Label::getLabel($msgLblKey));
                FatUtility::dieJsonError(Message::getHtml());
            }
            $fileHandlerObj = new AttachedFile();
            if (!$res = $fileHandlerObj->saveImage($_FILES['user_profile_image']['tmp_name'], AttachedFile::FILETYPE_USER_PROFILE_IMAGE, $userId, 0, $_FILES['user_profile_image']['name'], -1, $unique_record = true)) {
                Message::addErrorMessage($fileHandlerObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
            $this->set('file', CommonHelper::generateFullUrl('Image', 'user', array($userId, 'ORIGINAL', 0)) . '?' . time());
        }

        if ($post['action'] == "avatar") {
            if (!is_uploaded_file($_FILES['user_profile_image']['tmp_name'])) {
                $msgLblKey = CommonHelper::getFileUploadErrorLblKeyFromCode($_FILES['user_profile_image']['error']);
                Message::addErrorMessage(Label::getLabel($msgLblKey));
                FatUtility::dieJsonError(Message::getHtml());
            }
            $fileHandlerObj = new AttachedFile();
            if (!$res = $fileHandlerObj->saveImage($_FILES['user_profile_image']['tmp_name'], AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE, $userId, 0, $_FILES['user_profile_image']['name'], -1, $unique_record = true)) {
                Message::addErrorMessage($fileHandlerObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
            $data = json_decode(stripslashes($post['img_data']));
            CommonHelper::crop($data, CONF_UPLOADS_PATH . $res);
            $this->set('file', CommonHelper::generateFullUrl('Image', 'user', array($userId, 'MEDIUM', true)) . '?' . time());
        }

        $this->set('msg', Label::getLabel('MSG_File_uploaded_successfully'));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getForm(int $langId, $spokenLangs = null)
    {
        $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
        /* [ */
        $spokenLanguagesArr = SpokenLanguage::getAllLangs($langId, true);
        $teachingLanguagesArr = TeachingLanguage::getAllLangs($langId, true);
        /* ] */
        $frm = new Form('frmTeacherApprovalForm');
        $frm->addHtml('', 'general_fields_heading', '');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'utrvalue_user_first_name');

        $fld = $frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'utrvalue_user_last_name');

        $fld = $frm->addRadioButtons(Label::getLabel('LBL_Gender'), 'utrvalue_user_gender', User::getGenderArr($langId), User::GENDER_MALE);
        $fld->requirements()->setRequired();
        $fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone_Number'), 'utrvalue_user_phone');
        $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
        $fldPhn->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PHONE_NO_VALIDATION_MSG'));
        $frm->addHiddenField('', 'utrvalue_user_phone_code');
        $fld = $frm->addFileUpload(Label::getLabel('LBL_Photo_Id'), 'user_photo_id');
        $fld->htmlAfterField = "<small>" . Label::getLabel('LBL_Photo_Id_Type') . "</small>";
        $headfld = $frm->addHtml('', 'about_me_fields_heading', '');
        $frm->addFileUpload(Label::getLabel('LBL_profile_photo'), 'user_profile_pic');
        $bioFld = $frm->addHtml('', 'bio', '');
        $headfld->attachField($bioFld);

        $fld = $frm->addTextArea(Label::getLabel('LBL_Biography', $langId), 'utrvalue_user_profile_info');
        $fld->requirements()->setLength(1, 500);
        $headfld->attachField($fld);
        $frm->addHiddenField('', 'utrvalue_user_phone_code');
        $brFld = $frm->addHtml('', 'br', '<br><br>');
        $headfld->attachField($brFld);

        $fld = $frm->addHtml('', 'introduction_fields_heading', '');
        $headfld->attachField($fld);

        $bioFld = $frm->addHtml('', 'youtube_head', '');
        $headfld->attachField($bioFld);

        $fld = $frm->addTextBox(Label::getLabel('LBL_Introduction_video', $langId), 'utrvalue_user_video_link');
        $headfld->attachField($fld);

        $frm->addHtml('', 'profile_pic_preview', '');

        $frm->addHtml('', 'brFld', '<br>');

        $frm->addHtml('', 'language_fields_heading', '');
        $frm->addCheckBoxes(Label::getLabel('LBL_Language_To_Teach'), 'utrvalue_user_teach_slanguage_id[]', $teachingLanguagesArr)->requirements()->setRequired();
        $langArr = $spokenLangs ?: SpokenLanguage::getAllLangs($langId, true);
        foreach ($langArr as $key => $lang) {
            $speekLangField = $frm->addCheckBox(Label::getLabel('LBL_Language_I_Speak'), 'utrvalue_user_language_speak[' . $key . ']', $key, ['class' => 'utsl_slanguage_id'], false, '0');
            $proficiencyField = $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utrvalue_user_language_speak_proficiency[' . $key . ']', $profArr, '', ['class' => 'utsl_proficiency select__dropdown'], Label::getLabel("LBL_I_don't_speak_this_language"));

            $proficiencyField->requirements()->setRequired();
            $speekLangField->requirements()->addOnChangerequirementUpdate(0, 'gt', $proficiencyField->getName(), $proficiencyField->requirements());

            $proficiencyField->requirements()->setRequired(false);
            $speekLangField->requirements()->addOnChangerequirementUpdate(0, 'le', $proficiencyField->getName(), $proficiencyField->requirements());

            $speekLangField->requirements()->setRequired();
            $proficiencyField->requirements()->addOnChangerequirementUpdate(0, 'gt', $proficiencyField->getName(), $speekLangField->requirements());

            $speekLangField->requirements()->setRequired(false);
            $proficiencyField->requirements()->addOnChangerequirementUpdate(0, 'le', $proficiencyField->getName(), $speekLangField->requirements());
        }
        /* Resume[ */
        $frm->addHtml('', 'resume_fields_heading', '');
        $frm->addHtml('', 'resume_listing_html', '');
        /* ] */
        $fld = $frm->addCheckBox(Label::getLabel('LBL_Accept_Teacher_Approval_Terms_&_condition'), 'terms', 1);
        $fld->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }
    public function logoutGuestUser()
    {
        UserAuthentication::logoutGuestTeacher();
        FatApp::redirectUser(CommonHelper::generateUrl());
    }

    private function getProfileImageForm()
    {
        $frm = new Form('frmProfileImage', ['id' => 'frmProfileImage']);
        $frm->addFileUpload(Label::getLabel('LBL_Profile_Picture'), 'user_profile_image', ['onchange' => 'popupImage(this)', 'accept' => 'image/*']);
        $frm->addHiddenField('', 'update_profile_img', Label::getLabel('LBL_Update_Profile_Picture'), ['id' => 'update_profile_img']);
        $frm->addHiddenField('', 'rotate_left', Label::getLabel('LBL_Rotate_Left'), ['id' => 'rotate_left']);
        $frm->addHiddenField('', 'rotate_right', Label::getLabel('LBL_Rotate_Right'), ['id' => 'rotate_right']);
        $frm->addHiddenField('', 'remove_profile_img', 0, ['id' => 'remove_profile_img']);
        $frm->addHiddenField('', 'action', 'avatar', ['id' => 'avatar-action']);
        $frm->addHiddenField('', 'img_data', '', ['id' => 'img_data']);
        return $frm;
    }

    private function getApplyTeachFrm(int $langId)
    {
        $frm = new Form('frmApplyTeachFrm');
        $frm->addHiddenField('', 'user_id', 0);
        $fld = $frm->addEmailField(Label::getLabel('LBL_Email_ID', $langId), 'user_email', '', ['autocomplete' => 'off']);
        $fld->setUnique('tbl_user_credentials', 'credential_email', 'credential_user_id', 'user_id', 'user_id');
        $fld = $frm->addPasswordField(Label::getLabel('LBL_Password', $langId), 'user_password');
        $fld->requirements()->setRequired();
        $fld->setRequiredStarPosition(Form::FORM_REQUIRED_STAR_POSITION_NONE);
        $fld->requirements()->setRegularExpressionToValidate(applicationConstants::PASSWORD_REGEX);
        $fld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_8_Digit_AlphaNumeric_Password', $langId));
        $frm->addHiddenField('', 'user_preferred_dashboard', User::USER_TEACHER_DASHBOARD);
        $fld = $frm->addCheckBox(Label::getLabel('LBL_I_accept_to_the', $langId), 'agree', 1);
        $fld->requirements()->setRequired();
        $fld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Terms_and_Condition_and_Privacy_Policy_are_mandatory.', $langId));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Register', $langId));
        return $frm;
    }

    private function getApplyToTeachFaqs()
    {
        $srch = Faq::getSearchObject($this->siteLangId, false);
        $srch->addMultipleFields(['faq_identifier', 'faq_id', 'faq_category', 'faq_active', 'faq_title', 'faq_description']);
        $srch->addCondition('faq_category', '=', Faq::CATEGORY_APPLY_TO_TEACH);
        $srch->addOrder('faq_active', 'desc');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());

        return  $records;
    }

    private function verifyTeacherRequestRow(){
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
		$srch->addOrder('utrequest_id', 'desc');
		$rs = $srch->getResultSet();
		$teacherRequestRow = FatApp::getDb()->fetch($rs);

        return $teacherRequestRow;
    }
}
