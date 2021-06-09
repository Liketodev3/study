<?php

class AccountController extends LoggedUserController
{

    public function __construct($action)
    {
        parent::__construct($action);
        $this->_template->addJs('js/jquery-confirm.min.js');
    }

    public function index()
    {
        switch (User::getDashboardActiveTab()) {
            case User::USER_LEARNER_DASHBOARD:
                FatApp::redirectUser(CommonHelper::generateUrl('learner'));
                break;
            case User::USER_TEACHER_DASHBOARD:
                FatApp::redirectUser(CommonHelper::generateUrl('teacher'));
                break;
            default:
                FatApp::redirectUser(CommonHelper::generateUrl('learner'));
                break;
        }
    }

    public function changePassword()
    {
        $this->_template->render();
    }

    public function changePasswordForm()
    {
        $frm = $this->getChangePasswordForm();
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function changeEmailForm()
    {
        $emailChangeReqObj = new UserEmailChangeRequest();
        $userPendingRequest = $emailChangeReqObj->checkPendingRequestForUser(UserAuthentication::getLoggedUserId());
        $frm = $this->getChangeEmailForm();
        $this->set('frm', $frm);
        $this->set('userPendingRequest', $userPendingRequest);
        $this->_template->render(false, false);
    }

    public function setUpPassword()
    {
        $pwdFrm = $this->getChangePasswordForm();
        $post = $pwdFrm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($pwdFrm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
        if ($post['new_password'] != $post['conf_new_password']) {
            Message::addErrorMessage(Label::getLabel('MSG_New_Password_Confirm_Password_does_not_match'));
            FatUtility::dieWithError(Message::getHtml());
        }
        if (true !== CommonHelper::validatePassword($post['new_password'])) {
            Message::addErrorMessage(
                    Label::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC')
            );
            FatUtility::dieWithError(Message::getHtml());
        }
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $srch = $userObj->getUserSearchObj(['user_id', 'credential_password']);
        $rs = $srch->getResultSet();
        $userRow = FatApp::getDb()->fetch($rs, 'user_id');
        if (false == $userRow) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_REQUEST'));
            FatUtility::dieWithError(Message::getHtml());
        }
        if ($userRow['credential_password'] != UserAuthentication::encryptPassword($post['current_password'])) {
            Message::addErrorMessage(Label::getLabel('MSG_YOUR_CURRENT_PASSWORD_MIS_MATCHED'));
            FatUtility::dieWithError(Message::getHtml());
        }
        if (!$userObj->setLoginPassword($post['new_password'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Password_could_not_be_set') . $userObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Password_changed_successfully'));
        $this->_template->render(false, false, 'json-success.php');
    }

    // @fix
    public function setUpEmail()
    {
        $EmailFrm = $this->getChangeEmailForm();
        $post = $EmailFrm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($EmailFrm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $srch = $userObj->getUserSearchObj(['user_id', 'user_first_name', 'user_last_name', 'credential_password']);
        $rs = $srch->getResultSet();
        $userRow = FatApp::getDb()->fetch($rs, 'user_id');
        $userData = [
            'user_email' => $post['new_email'],
            'user_first_name' => $userRow['user_first_name'],
            'user_last_name' => $userRow['user_last_name']
        ];
        if ($userRow['credential_password'] != UserAuthentication::encryptPassword($post['current_password'])) {
            Message::addErrorMessage(Label::getLabel('MSG_YOUR_CURRENT_PASSWORD_MIS_MATCHED'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $_token = $userObj->prepareUserVerificationCode();
        $error = '';
        if (!$this->sendEmailChangeVerificationLink($_token, $userData, $error)) {
            Message::addErrorMessage(Label::getLabel('MSG_Unable_to_process_your_requset') . ' : ' . $error);
            FatUtility::dieWithError(Message::getHtml());
        }
        $emailChangeReqObj = new UserEmailChangeRequest();
        $emailChangeReqObj->deleteOldLinkforUser(UserAuthentication::getLoggedUserId());
        $postData = [
            'uecreq_user_id' => UserAuthentication::getLoggedUserId(),
            'uecreq_email' => $post['new_email'],
            'uecreq_token' => $_token,
            'uecreq_status' => 0,
            'uecreq_created' => date('Y-m-d H:i:s'),
            'uecreq_updated' => date('Y-m-d H:i:s'),
            'uecreq_expire' => date('Y-m-d H:i:s', strtotime('+ 24 hours', strtotime(date('Y-m-d H:i:s'))))
        ];
        $emailChangeReqObj->assignValues($postData);
        if (!$emailChangeReqObj->save()) {
            Message::addErrorMessage(Label::getLabel('MSG_Unable_to_process_your_requset') . $emailChangeReqObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Please_verify_your_email'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function removeProfileImage()
    {
        $userId = UserAuthentication::getLoggedUserId();
        if (1 > $userId) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_REQUEST_ID'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $fileHandlerObj = new AttachedFile();
        if (!$fileHandlerObj->deleteFile(AttachedFile::FILETYPE_USER_PROFILE_IMAGE, $userId)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        if (!$fileHandlerObj->deleteFile(AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE, $userId)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        if (CONF_USE_FAT_CACHE) {
            FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'ORIGINAL'), CONF_WEBROOT_FRONTEND));
            FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'MEDIUM'), CONF_WEBROOT_FRONTEND));
            FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'SMALL'), CONF_WEBROOT_FRONTEND));
            FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'EXTRASMALL'), CONF_WEBROOT_FRONTEND));
            FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId), CONF_WEBROOT_FRONTEND));
        }
        $this->set('msg', Label::getLabel('MSG_File_deleted_successfully'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function profileInfo()
    {
        $this->_template->addJs('js/jquery.form.js');
        $this->_template->addJs('js/jquery.inputmask.bundle.js');
        $this->_template->addJs('js/cropper.js');
        $this->_template->addJs('js/intlTelInput.js');
        $this->_template->addCss('css/intlTelInput.css');

        if ($currentLangCode = strtolower(Language::getLangCode($this->siteLangId))) {
            if (file_exists(CONF_THEME_PATH . "js/locales/$currentLangCode.js")) {
                $this->_template->addJs("js/locales/$currentLangCode.js");
            }
        }
        $this->set('userIsTeacher', User::isTeacher());
        $this->_template->render();
    }

    public function profileInfoForm()
    {
        //$profileImgFrm = $this->getProfileImageForm();
        $stateId = 0;
        $userRow = User::getAttributesById(UserAuthentication::getLoggedUserId(), [
                    'user_id',
                    'user_url_name',
                    'user_first_name',
                    'user_last_name',
                    'user_gender',
                    'user_phone',
                    'user_phone_code',
                    'user_country_id',
                    'user_is_teacher',
                    'user_timezone',
                    'user_profile_info'
        ]);
        $userRow['user_phone'] = ($userRow['user_phone'] == 0) ? '' : $userRow['user_phone'];
        // $profileFrm = $this->getProfileInfoForm($userRow['user_is_teacher']);
        $isTeacherDashboardActive = (User::getDashboardActiveTab() == User::USER_TEACHER_DASHBOARD);
        $profileFrm = $this->getProfileInfoForm($isTeacherDashboardActive);
        $userSettings = UserSetting::getUserSettings(UserAuthentication::getLoggedUserId());
        if ($userRow['user_is_teacher']) {
            $userRow['us_video_link'] = $userSettings['us_video_link'] ?? '';
            $userRow['us_is_trial_lesson_enabled'] = $userSettings['us_is_trial_lesson_enabled'] ?? '';
            $userRow['us_booking_before'] = $userSettings['us_booking_before'] ?? ''; //== code added on 23-08-2019
            $userRow['us_google_access_token'] = $userSettings['us_google_access_token'] ?? '';
            $userRow['us_google_access_token_expiry'] = $userSettings['us_google_access_token_expiry'] ?? '';
        }
        $userRow['user_phone'] = $userRow['user_phone_code'] . $userRow['user_phone'];
        $userRow['us_site_lang'] = $userSettings['us_site_lang'] ?? '';
        $profileFrm->fill($userRow);
        $this->set('isProfilePicUploaded', User::isProfilePicUploaded());
        $this->set('userRow', $userRow);
        $this->set('profileFrm', $profileFrm);
        //$this->set('profileImgFrm', $profileImgFrm);
        $this->set('languages', Language::getAllNames(false));
        $this->_template->render(false, false);
    }

    public function userLangForm($lang_id = 0)
    {
        $lang_id = FatUtility::int($lang_id);
        if ($lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm = $this->getUserLangForm($lang_id);
        $srch = new SearchBase(User::DB_TBL_LANG);
        $srch->addMultipleFields(['userlang_lang_id', 'userlang_user_profile_Info']);
        $srch->addCondition('userlang_lang_id', '=', $lang_id);
        $srch->addCondition('userlang_user_id', '=', UserAuthentication::getLoggedUserId());
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        $langData = FatApp::getDb()->fetch($rs);
        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames(false));
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    private function getUserLangForm($lang_id = 0)
    {
        $frm = new Form('frmUserLang');
        $frm->addHiddenField('', 'userlang_lang_id', $lang_id);
        $fld = $frm->addTextArea(Label::getLabel('LBL_Biography', $lang_id), 'userlang_user_profile_Info');
        $fld->requirements()->setLength(1, 500);
        $fld->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $lang_id));
        $frm->addButton('', 'btn_next', Label::getLabel('LBL_Next', $lang_id));
        return $frm;
    }

    public function setUpProfileLangInfo()
    {
        $post = FatApp::getPostedData();
        $frm = $this->getUserLangForm($post['userlang_lang_id']);
        $post = $frm->getFormDataFromArray($post);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }
        $data = [
            'userlang_user_id' => UserAuthentication::getLoggedUserId(),
            'userlang_lang_id' => $post['userlang_lang_id'],
            'userlang_user_profile_Info' => $post['userlang_user_profile_Info']
        ];
        $userObj = new User(UserAuthentication::getLoggedUserId());
        if (!$userObj->updateLangData($post['userlang_lang_id'], $data)) {
            Message::addErrorMessage($userObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = User::getAttributesByLangId($langId, UserAuthentication::getLoggedUserId())) {
                $newTabLangId = $langId;
                break;
            }
        }
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setUpProfileImage()
    {
        $userId = UserAuthentication::getLoggedUserId();
        // $isTeacher = User::getAttributesById($userId, 'user_is_teacher');
        $isTeacherDashboardActive = (User::getDashboardActiveTab() == User::USER_TEACHER_DASHBOARD);
        $profileImgFrm = $this->getProfileImageForm($isTeacherDashboardActive);

        $post = FatApp::getPostedData();
        $post = $profileImgFrm->getFormDataFromArray($post);
        if (false === $post) {
            Message::addErrorMessage(current($profileImgFrm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }

        if ($post['action'] == "demo_avatar" && !empty($_FILES['user_profile_image']['tmp_name'])) {
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
            if (CONF_USE_FAT_CACHE) {
                FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'ORIGINAL'), CONF_WEBROOT_FRONTEND));
                FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'MEDIUM'), CONF_WEBROOT_FRONTEND));
                FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId, 'SMALL'), CONF_WEBROOT_FRONTEND));
                FatCache::delete(CommonHelper::generateUrl('Image', 'user', array($userId), CONF_WEBROOT_FRONTEND));
            }
            $this->set('file', CommonHelper::generateFullUrl('Image', 'user', [$userId, 'ORIGINAL', 0], CONF_WEBROOT_FRONTEND) . '?' . time());
        }
        if ($post['action'] == "avatar" && !empty($_FILES['user_profile_image']['tmp_name'])) {
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
            $this->set('file', CommonHelper::generateFullUrl('Image', 'user', [$userId, 'MEDIUM', true], CONF_WEBROOT_FRONTEND) . '?' . time());
        }
        if ($isTeacherDashboardActive) {
            $userSettings = new UserSetting($userId);
            if (!$userSettings->saveData(['us_video_link' => $post['us_video_link']])) {
                FatUtility::dieJsonError($userSettings->getError());
            }
        }

        $this->set('msg', Label::getLabel('MSG_Data_uploaded_successfully'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setUpProfileInfo()
    {
        // $isTeacher = User::getAttributesById(UserAuthentication::getLoggedUserId(), 'user_is_teacher');
        // $profileFrm = $this->getProfileInfoForm($userRow['user_is_teacher']);
        $isTeacherDashboardActive = (User::getDashboardActiveTab() == User::USER_TEACHER_DASHBOARD);
        $frm = $this->getProfileInfoForm($isTeacherDashboardActive);
        $post = FatApp::getPostedData();
        $post = $frm->getFormDataFromArray($post);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $record = new TableRecord(UserSetting::DB_TBL);
        $record->setFlds(['us_site_lang' => $post['us_site_lang'], 'us_user_id' => UserAuthentication::getLoggedUserId()]);

        if ($isTeacherDashboardActive) {
            // $bookingDurationOptions = array(0, 12, 24);
            // if (!in_array($post['us_booking_before'], $bookingDurationOptions)) {
            //     Message::addErrorMessage(Label::getLabel('Lbl_invalid_Selection_of Booking_Field'));
            //     FatUtility::dieWithError(Message::getHtml());
            // } //  code added on 23-08-2019
            $record->assignValues(['us_booking_before' => $post['us_booking_before'], 'us_is_trial_lesson_enabled' => $post['us_is_trial_lesson_enabled']]); //  code added on 23-08-2019
        }
        $user_settings = UserSetting::getUserSettings(UserAuthentication::getLoggedUserId());
        if ($post['us_site_lang'] != $user_settings['us_site_lang']) {
            CommonHelper::setDefaultSiteLangCookie($post['us_site_lang']);
        }

        if (!$record->addNew([], $record->getFlds())) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($record->getError());
        }
        $user = new User(UserAuthentication::getLoggedUserId());
        $user->assignValues($post);
        if (!$user->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($user->getError());
        }
        $uaObj = new UserAuthentication();
        $uaObj->updateSessionData($post);
        $db->commitTransaction();
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getProfileInfoForm($teacher = false)
    {
        $frm = new Form('frmProfileInfo');
        $frm->addHTML('', 'personal_information', '');
        if ($teacher) {
            $frm->addHiddenField('', 'user_id', 'user_id');
            $fldUname = $frm->addTextBox(Label::getLabel('LBL_Username'), 'user_url_name');
            $fldUname->setUnique('tbl_users', 'user_url_name', 'user_id', 'user_id', 'user_id');
            $fldUname->requirements()->setRequired();
            $fldUname->requirements()->setRegularExpressionToValidate('^[A-Za-z0-9-_]{3,35}$');
            $fldUname->requirements()->setCustomErrorMessage(Label::getLabel('LBL_Invalid_Username', $this->siteLangId));
        }

        $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'user_first_name');
        $frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'user_last_name');

        $frm->addRadioButtons(Label::getLabel('LBL_Gender'), 'user_gender', User::getGenderArr());
        $fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone'), 'user_phone');
        $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
        $fldPhn->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PHONE_NO_VALIDATION_MSG'));
        $frm->addHiddenField('', 'user_phone_code');

        $countryObj = new Country();
        $countriesArr = $countryObj->getCountriesArr($this->siteLangId);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Country'), 'user_country_id', $countriesArr, FatApp::getConfig('CONF_COUNTRY', FatUtility::VAR_INT, 0), [], Label::getLabel('LBL_Select'));
        $fld->requirement->setRequired(true);
        $timezonesArr = MyDate::timeZoneListing();
        $fld2 = $frm->addSelectBox(Label::getLabel('LBL_TimeZone'), 'user_timezone', $timezonesArr, FatApp::getConfig('CONF_COUNTRY', FatUtility::VAR_INT, 0), [], Label::getLabel('LBL_Select'));
        $fld2->requirement->setRequired(true);
        if ($teacher) { //== check if user is teacher
            $bookingOptionArr = array(0 => Label::getLabel('LBL_Immediate'), 12 => Label::getLabel('LBL_12_Hours'), 24 => Label::getLabel('LBL_24_Hours'));
            $fld3 = $frm->addSelectBox(Label::getLabel('LBL_Booking_Before'), 'us_booking_before', $bookingOptionArr, 'us_booking_before', [], Label::getLabel('LBL_Select'));
            $fld3->requirement->setRequired(true);

            $isFreeTrialActive = FatApp::getConfig('CONF_ENABLE_FREE_TRIAL', FatUtility::VAR_INT, 0);
            if ($isFreeTrialActive == applicationConstants::YES) {
                $frm->addCheckBox(Label::getLabel('LBL_Enable_Trial_Lesson'), 'us_is_trial_lesson_enabled', applicationConstants::YES, [], true, applicationConstants::NO);
            }
        }
        $frm->addSelectBox(Label::getLabel('LBL_Site_Language'), 'us_site_lang', Language::getAllNames(), '', [], Label::getLabel('LBL_Select'));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        $frm->addButton('', 'btn_next', Label::getLabel('LBL_Next'));
        return $frm;
    }

    public function profileImageForm()
    {
        $userId = UserAuthentication::getLoggedUserId();
        // $isTeacher = User::getAttributesById($userId, 'user_is_teacher');
        $userSettings = UserSetting::getUserSettings($userId);

        $isTeacherDashboardActive = (User::getDashboardActiveTab() == User::USER_TEACHER_DASHBOARD);

        $profileImgFrm = $this->getProfileImageForm($isTeacherDashboardActive);

        $profileImgFrm->fill(['us_video_link' => $userSettings['us_video_link'] ?? '']);
        $userFirstName = UserAuthentication::getLoggedUserAttribute('user_first_name');
        $isProfilePicUploaded = User::isProfilePicUploaded($userId);
        $this->set('profileImgFrm', $profileImgFrm);
        $this->set('isProfilePicUploaded', $isProfilePicUploaded);
        $this->set('userId', $userId);
        $this->set('userFirstName', $userFirstName);

        $this->_template->render(false, false);
    }

    private function getProfileImageForm($teacher = false)
    {
        $frm = new Form('frmProfile', ['id' => 'frmProfile']);
        $frm->addFileUpload(Label::getLabel('LBL_Profile_Picture'), 'user_profile_image', ['onchange' => 'popupImage(this)', 'accept' => 'image/*']);
        $frm->addHiddenField('', 'update_profile_img', Label::getLabel('LBL_Update_Profile_Picture'), ['id' => 'update_profile_img']);
        $frm->addHiddenField('', 'rotate_left', Label::getLabel('LBL_Rotate_Left'), ['id' => 'rotate_left']);
        $frm->addHiddenField('', 'rotate_right', Label::getLabel('LBL_Rotate_Right'), ['id' => 'rotate_right']);
        $frm->addHiddenField('', 'remove_profile_img', 0, ['id' => 'remove_profile_img']);
        $frm->addHiddenField('', 'action', 'avatar', ['id' => 'avatar-action']);
        $frm->addHiddenField('', 'img_data', '', ['id' => 'img_data']);
        if ($teacher) {
            $frm->addTextBox(Label::getLabel('M_Introduction_Video_Link'), 'us_video_link', '');
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        $frm->addButton('', 'btn_next', Label::getLabel('LBL_Next'));
        return $frm;
    }

    private function getChangePasswordForm()
    {
        $frm = new Form('changePwdFrm');
        $curPwd = $frm->addPasswordField(Label::getLabel('LBL_CURRENT_PASSWORD'), 'current_password');
        $curPwd->requirements()->setRequired();
        $newPwd = $frm->addPasswordField(Label::getLabel('LBL_NEW_PASSWORD'), 'new_password');
        $newPwd->requirements()->setRequired();
        $newPwd->requirements()->setRegularExpressionToValidate(applicationConstants::PASSWORD_REGEX);
        $newPwd->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Valid_password'));
        $conNewPwd = $frm->addPasswordField(Label::getLabel('LBL_CONFIRM_NEW_PASSWORD'), 'conf_new_password');
        $conNewPwdReq = $conNewPwd->requirements();
        $conNewPwdReq->setRequired();
        $conNewPwdReq->setCompareWith('new_password', 'eq');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        $frm->addSubmitButton('', 'btn_next', Label::getLabel('LBL_next'));
        return $frm;
    }

    private function sendEmailChangeVerificationLink($_token, $data, &$error)
    {
        $link = CommonHelper::generateFullUrl('GuestUser', 'verifyEmail', [$_token]);
        $data = [
            'user_first_name' => $data['user_first_name'],
            'user_last_name' => $data['user_last_name'],
            'user_email' => $data['user_email'],
            'link' => $link,
        ];
        $email = new EmailHandler();
        if (true !== $email->sendEmailChangeVerificationLink($this->siteLangId, $data)) {
            $error = $email->getError();
            return false;
        }
        return true;
    }

    public function GoogleCalendarAuthorize()
    {

        require_once CONF_INSTALLATION_PATH . 'library/third-party/GoogleAPI/vendor/autoload.php'; // include the required calss files for google login
        $client = new Google_Client();
        $client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $this->siteLangId)); // Set your applicatio name
        $client->setScopes(['email', 'profile', 'https://www.googleapis.com/auth/calendar', 'https://www.googleapis.com/auth/calendar.events']); // set scope during user login
        $client->setClientId(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_ID")); // paste the client id which you get from google API Console
        $client->setClientSecret(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_SECRET")); // set the client secret
        $currentPageUri = CommonHelper::generateFullUrl('Account', 'GoogleCalendarAuthorize', [], '', false);
        $client->setRedirectUri($currentPageUri);
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");
        $client->setDeveloperKey(FatApp::getConfig("CONF_GOOGLEPLUS_DEVELOPER_KEY")); // Developer key
        $oauth2 = new Google_Service_Oauth2($client); // Call the OAuth2 class for get email address
        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']); // Authenticate
            $_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
            FatApp::redirectUser($currentPageUri);
        }
        if (isset($_SESSION['access_token'])) {
            $client->setAccessToken($_SESSION['access_token']);
        }
        if (!$client->getAccessToken()) {
            $authUrl = $client->createAuthUrl();
            FatApp::redirectUser($authUrl);
        }
        $data = [
            'us_google_access_token' => $client->getRefreshToken(),
            'us_google_access_token_expiry' => date('Y-m-d H:i:s', strtotime('+60 days'))
        ];
        $usrStngObj = new UserSetting(UserAuthentication::getLoggedUserId());
        $usrStngObj->saveData($data);
        unset($_SESSION['access_token']);
        FatApp::redirectUser(CommonHelper::generateUrl('Account', 'ProfileInfo'));
    }

}
