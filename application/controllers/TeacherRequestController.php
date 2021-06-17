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

    private function getRequest(int $userId)
    {
        $srch = new SearchBase('tbl_user_teacher_requests');
        $srch->addCondition('utrequest_user_id', '=', $userId);
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    public function form()
    {
        $this->set('step', 1);
        $this->set('exculdeMainHeaderDiv', false);
        $this->_template->addJs('js/jquery.form.js');
        $this->_template->addJs('js/cropper.js');
        $this->_template->addJs('js/jquery.inputmask.bundle.js');
        $this->_template->addJs('js/intlTelInput.js');
        $this->_template->addCss('css/intlTelInput.css');
        $this->_template->render(true, false);
    }

    public function formStep1()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getFormStep1($this->siteLangId);
        $request = $this->getRequest($userId);
        if (!empty($request)) {
            $frm->fill($request);
        }
        $this->set('frm', $frm);
        $this->set('request', $request);
        $this->set('user', User::getAttributesById($userId));
        $this->_template->render(false, false);
    }

    public function formStep2()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getFormStep2($this->siteLangId);
        $frm->fill($request);
        $this->set('frm', $frm);
        $this->set('request', $request);
        $this->set('user', User::getAttributesById($userId));
        $this->_template->render(false, false);
    }

    public function formStep3()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $request['utrequest_teach_slanguage_id'] = json_decode($request['utrequest_teach_slanguage_id'], true);
        $request['utrequest_language_speak'] = json_decode($request['utrequest_language_speak'], true);
        $request['utrequest_language_speak_proficiency'] = json_decode($request['utrequest_language_speak_proficiency'], true);
        $spokenLangs = SpokenLanguage::getAllLangs($this->siteLangId, true);
        $frm = $this->getFormStep3($this->siteLangId, $spokenLangs);
        $frm->fill($request);
        $this->set('frm', $frm);
        $this->set('request', $request);
        $this->set('spokenLangs', $spokenLangs);
        $this->set('user', User::getAttributesById($userId));
        $this->_template->render(false, false);
    }

    public function formStep4()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getFormStep4($this->siteLangId);
        $frm->fill(['terms' => applicationConstants::YES]);
        $this->set('frm', $frm);
        $this->set('rows', []);
        $this->set('request', $request);
        $this->set('user', User::getAttributesById($userId));
        $this->_template->render(false, false);
    }

    public function formStep5()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $fileType = AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF;
        $fileRow = AttachedFile::getAttachment($fileType, $userId);
        $this->set('fileRow', $fileRow);
        $this->set('request', $request);
        $this->set('user', User::getAttributesById($userId));
        $this->_template->render(false, false);
    }

    public function setupStep1()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getFormStep1($this->siteLangId);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }

        if (!empty($_FILES['user_photo_id']['tmp_name'] ?? '')) {
            $file = new AttachedFile();
            $photoName = $_FILES['user_photo_id']['name'];
            $photoTmpName = $_FILES['user_photo_id']['tmp_name'];
            $photoType = AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF;
            if (!$file->saveDoc($photoTmpName, $photoType, $userId, 0, $photoName, -1, true)) {
                FatUtility::dieJsonError($file->getError());
            }
        }

        $data = [
            'utrequest_step' => 2,
            'utrequest_user_id' => $userId,
            'utrequest_language_id' => $this->siteLangId,
            'utrequest_reference' => $userId . '-' . time(),
            'utrequest_date' => date('Y-m-d H:i:s'),
            'utrequest_first_name' => $post['utrequest_first_name'],
            'utrequest_last_name' => $post['utrequest_last_name'],
            'utrequest_gender' => $post['utrequest_gender'],
            'utrequest_phone_code' => $post['utrequest_phone_code'],
            'utrequest_phone_number' => $post['utrequest_phone_number'],
        ];
        $request = $this->getRequest($userId);
        if (!empty($request)) {
            $data = [
                'utrequest_id' => $request['utrequest_id'],
                'utrequest_first_name' => $post['utrequest_first_name'],
                'utrequest_last_name' => $post['utrequest_last_name'],
                'utrequest_gender' => $post['utrequest_gender'],
                'utrequest_phone_code' => $post['utrequest_phone_code'],
                'utrequest_phone_number' => $post['utrequest_phone_number'],
            ];
        }
        $record = new TableRecord('tbl_user_teacher_requests');
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_SOMETHING_WENT_WRONG_PLEASE_TRY_AGAIN'));
        }
        FatUtility::dieJsonSuccess(['step' => 2, 'msg' => Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY')]);
    }

    public function setupStep2()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getFormStep2($this->siteLangId);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $record = new TableRecord('tbl_user_teacher_requests');
        $data = [
            'utrequest_step' => 3,
            'utrequest_id' => $request['utrequest_id'],
            'utrequest_video_link' => $post['utrequest_video_link'],
            'utrequest_profile_info' => $post['utrequest_profile_info'],
        ];
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_SOMETHING_WENT_WRONG_PLEASE_TRY_AGAIN'));
        }
        FatUtility::dieJsonSuccess(['step' => 3, 'msg' => Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY')]);
    }

    public function setupStep3()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $spokenLangs = SpokenLanguage::getAllLangs($this->siteLangId, true);
        $frm = $this->getFormStep3($this->siteLangId, $spokenLangs);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $record = new TableRecord('tbl_user_teacher_requests');
        $data = [
            'utrequest_step' => 4,
            'utrequest_id' => $request['utrequest_id'],
            'utrequest_teach_slanguage_id' => json_encode(array_filter(FatUtility::int($post['utrequest_teach_slanguage_id']))),
            'utrequest_language_speak' => json_encode(array_filter(FatUtility::int(array_values($post['utrequest_language_speak'])))),
            'utrequest_language_speak_proficiency' => json_encode(array_filter(FatUtility::int(array_values($post['utrequest_language_speak_proficiency'])))),
        ];
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_SOMETHING_WENT_WRONG_PLEASE_TRY_AGAIN'));
        }
        FatUtility::dieJsonSuccess(['step' => 4, 'msg' => Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY')]);
    }

    public function setupStep4()
    {
        $userId = FatUtility::int($this->userId);
        if ($userId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getFormStep4();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $request = $this->getRequest($userId);
        if (empty($request)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $record = new TableRecord('tbl_user_teacher_requests');
        $record->assignValues(['utrequest_step' => 5]);
        if (!$record->update(['smt' => 'utrequest_id = ?', 'vals' => [$request['utrequest_id']]])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_SOMETHING_WENT_WRONG_PLEASE_TRY_AGAIN'));
        }
        FatUtility::dieJsonSuccess(['step' => 5, 'msg' => Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY')]);
    }

    private function getFormStep1($langId)
    {
        $frm = new Form('frmFormStep1');
        $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'utrequest_first_name')->requirements()->setRequired();
        $frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'utrequest_last_name')->requirements()->setRequired();
        $frm->addRadioButtons(Label::getLabel('LBL_Gender'), 'utrequest_gender', User::getGenderArr($langId), User::GENDER_MALE)->requirements()->setRequired();
        $fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone_Number'), 'utrequest_phone_number');
        $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
        $fldPhn->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PHONE_NO_VALIDATION_MSG'));
        $frm->addHiddenField('', 'utrequest_phone_code');
        $frm->addFileUpload(Label::getLabel('LBL_Photo_Id'), 'user_photo_id');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }

    private function getFormStep2($langId)
    {
        $frm = new Form('frmFormStep2');
        $frm->addTextArea(Label::getLabel('LBL_Biography', $langId), 'utrequest_profile_info')->requirements()->setLength(1, 500);
        $frm->addTextBox(Label::getLabel('LBL_Introduction_video', $langId), 'utrequest_video_link');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }

    private function getFormStep3($langId, $spokenLangs)
    {
        $frm = new Form('frmFormStep3');
        $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
        $teachingLanguagesArr = TeachingLanguage::getAllLangs($langId, true);
        $fld = $frm->addCheckBoxes(Label::getLabel('LBL_Language_To_Teach'), 'utrequest_teach_slanguage_id', $teachingLanguagesArr);
        $fld->requirements()->setSelectionRange(1, count($teachingLanguagesArr));
        $fld->requirements()->setRequired();
        $langArr = $spokenLangs ?: SpokenLanguage::getAllLangs($langId, true);
        foreach ($langArr as $key => $lang) {
            $speekLangField = $frm->addCheckBox(Label::getLabel('LBL_Language_I_Speak'), 'utrequest_language_speak[' . $key . ']', $key, ['class' => 'utsl_slanguage_id'], false, '0');
            $proficiencyField = $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utrequest_language_speak_proficiency[' . $key . ']', $profArr, '', ['class' => 'utsl_proficiency select__dropdown'], Label::getLabel("LBL_I_don't_speak_this_language"));
            $proficiencyField->requirements()->setRequired();
            $speekLangField->requirements()->addOnChangerequirementUpdate(0, 'gt', $proficiencyField->getName(), $proficiencyField->requirements());
            $proficiencyField->requirements()->setRequired(false);
            $speekLangField->requirements()->addOnChangerequirementUpdate(0, 'le', $proficiencyField->getName(), $proficiencyField->requirements());
            $speekLangField->requirements()->setRequired();
            $proficiencyField->requirements()->addOnChangerequirementUpdate(0, 'gt', $proficiencyField->getName(), $speekLangField->requirements());
            $speekLangField->requirements()->setRequired(false);
            $proficiencyField->requirements()->addOnChangerequirementUpdate(0, 'le', $proficiencyField->getName(), $speekLangField->requirements());
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }

    private function getFormStep4()
    {
        $frm = new Form('frmFormStep4');
        $frm->addCheckBox(Label::getLabel('LBL_Accept_Teacher_Approval_Terms_&_condition'), 'terms', 1)->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }

    public function CreateForm()
    {
        $user = new User($this->userId);
        if (!$user->loadFromDb()) {
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherRequest'));
        }
        $spokenLangs = SpokenLanguage::getAllLangs($this->siteLangId, true);
        $formStep = FatApp::getPostedData('step', FatUtility::VAR_INT, 1);
        $frm = $this->getForm($formStep, $this->siteLangId, $spokenLangs);
        $userDetails = $user->getFlds();

        $userQualifications = new UserQualificationSearch();
        $rows = $userQualifications->getUserQualification($this->userId);
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
        $this->set('formStep', $formStep);
        $this->set('rows', $rows);
        $this->_template->render(false, false);
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

        /**
         * need to check if user has/have already pending request
         * system will behave accordingly
         */
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

    private function getForm(int $formstep, int $langId, $spokenLangs = null)
    {
        $frm = new Form('frmTeacherApprovalForm');

        switch ($formstep) {
            case 1:
                $fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'utrvalue_user_first_name');
                $fld = $frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'utrvalue_user_last_name');
                $fld = $frm->addRadioButtons(Label::getLabel('LBL_Gender'), 'utrvalue_user_gender', User::getGenderArr($langId), User::GENDER_MALE);
                $fld->requirements()->setRequired();
                $fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone_Number'), 'utrvalue_user_phone');
                $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
                $fldPhn->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PHONE_NO_VALIDATION_MSG'));
                $frm->addHiddenField('', 'utrvalue_user_phone_code');
                $fld = $frm->addFileUpload(Label::getLabel('LBL_Photo_Id'), 'user_photo_id');
                break;
            case 2:
                $fld = $frm->addTextArea(Label::getLabel('LBL_Biography', $langId), 'utrvalue_user_profile_info');
                $fld->requirements()->setLength(1, 500);
                $fld = $frm->addTextBox(Label::getLabel('LBL_Introduction_video', $langId), 'utrvalue_user_video_link');
                break;
            case 3:
                $frm->jsErrorDivId = 'errorDiv';
                $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
                $teachingLanguagesArr = TeachingLanguage::getAllLangs($langId, true);
                $fld = $frm->addCheckBoxes(Label::getLabel('LBL_Language_To_Teach'), 'utrvalue_user_teach_slanguage_id[]', $teachingLanguagesArr);
                $fld->requirements()->setSelectionRange(1, count($teachingLanguagesArr));
                $fld->requirements()->setRequired();
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
                break;
            case 4:
                $fld = $frm->addCheckBox(Label::getLabel('LBL_Accept_Teacher_Approval_Terms_&_condition'), 'terms', 1);
                $fld->requirements()->setRequired();
                break;
        }
        switch ($formstep) {
            case 1:
                $fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'utrvalue_user_first_name');
                $fld = $frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'utrvalue_user_last_name');
                $fld = $frm->addRadioButtons(Label::getLabel('LBL_Gender'), 'utrvalue_user_gender', User::getGenderArr($langId), User::GENDER_MALE);
                $fld->requirements()->setRequired();
                $fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone_Number'), 'utrvalue_user_phone');
                $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
                $fldPhn->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PHONE_NO_VALIDATION_MSG'));
                $frm->addHiddenField('', 'utrvalue_user_phone_code');
                $fld = $frm->addFileUpload(Label::getLabel('LBL_Photo_Id'), 'user_photo_id');
                break;
            case 2:
                $fld = $frm->addTextArea(Label::getLabel('LBL_Biography', $langId), 'utrvalue_user_profile_info');
                $fld->requirements()->setLength(1, 500);
                $fld = $frm->addTextBox(Label::getLabel('LBL_Introduction_video', $langId), 'utrvalue_user_video_link');
                break;
            case 3:
                $frm->jsErrorDivId = 'errorDiv';
                $frm->jsErrorDisplay = Form::FORM_ERROR_TYPE_SUMMARY;
                $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
                $teachingLanguagesArr = TeachingLanguage::getAllLangs($langId, true);
                $fld = $frm->addCheckBoxes(Label::getLabel('LBL_Language_To_Teach'), 'utrvalue_user_teach_slanguage_id[]', $teachingLanguagesArr);
                $fld->requirements()->setSelectionRange(1, count($teachingLanguagesArr));
                $fld->requirements()->setRequired();
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
                break;
            case 4:
                $fld = $frm->addCheckBox(Label::getLabel('LBL_Accept_Teacher_Approval_Terms_&_condition'), 'terms', 1);
                $fld->requirements()->setRequired();
                break;
        }
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
        $cPPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_PRIVACY_POLICY_PAGE', FatUtility::VAR_INT, 0));
        $cpppage = FatApp::getDb()->fetch($cPPageSrch->getResultSet());
        if (!empty($cpppage) && is_array($cpppage)) {
            $privacyPolicyLinkHref = CommonHelper::generateUrl('Cms', 'view', [$cpppage['cpage_id']]);
        } else {
            $privacyPolicyLinkHref = 'javascript:void(0)';
        }
        $cPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_TERMS_AND_CONDITIONS_PAGE', FatUtility::VAR_INT, 0));
        $cpage = FatApp::getDb()->fetch($cPageSrch->getResultSet());
        if (!empty($cpage) && is_array($cpage)) {
            $termsAndConditionsLinkHref = CommonHelper::generateUrl('Cms', 'view', [$cpage['cpage_id']]);
        } else {
            $termsAndConditionsLinkHref = 'javascript:void(0)';
        }
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
        $cPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_TERMS_AND_CONDITIONS_PAGE', FatUtility::VAR_INT, 0));
        $cpage = FatApp::getDb()->fetch($cPageSrch->getResultSet());
        if (!empty($cpage) && is_array($cpage)) {
            $fld = $frm->addCheckBox(Label::getLabel('LBL_I_accept_to_the', $langId), 'agree', 1);
            $termLink = ' <a target="_blank" class = "-link-underline link-color" href="' . $termsAndConditionsLinkHref . '">' . Label::getLabel('LBL_TERMS_AND_CONDITION') . '</a> and <a href="' . $privacyPolicyLinkHref . '" target="_blank" class = "-link-underline link-color" >' . Label::getLabel('LBL_Privacy_Policy') . '</a>';
            $terms_caption = '<span>' . $termLink . '</span>';
            $frm->getField('agree')->addWrapperAttribute('class', 'terms_wrap');
            $frm->getField('agree')->htmlAfterField = $terms_caption;
        }
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

        return $records;
    }

    private function verifyTeacherRequestRow()
    {
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
