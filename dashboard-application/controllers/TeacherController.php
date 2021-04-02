<?php
class TeacherController extends TeacherBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $teacherProfileProgress = User::getTeacherProfileProgress();
        /* Validate Teacher has filled complete profile[ */
        $link = " <a href='" . CommonHelper::generateUrl('account', 'profileInfo') . "'>" . Label::getLabel('LBL_Click_Here') . "</a>";
        if (false == $teacherProfileProgress['isProfileCompleted']) {
            Message::addInfo(sprintf(Label::getLabel('LBL_Please_Complete_Profile_to_be_visible_on_teachers_listing_page_%s'), $link));
            $this->set('viewProfile', false);
            // FatApp::redirectUser(CommonHelper::generateUrl('account', 'profileInfo'));
        } else {
            /* $token = current(UserSetting::getUserSettings(UserAuthentication::getLoggedUserId()))['us_google_access_token'];
            if(!$token || SocialMedia::isGoogleAccessTokenExpired($token)){
                Message::addInfo(sprintf(Label::getLabel('LBL_Please_Authenticate_google_to_be_able_to_post_on_google_calendar_%s'), $link));
            } */
            $this->set('viewProfile', true);
        }



        /* ] */
        //$this->_template->addCss('css/custom-full-calendar.css');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addJs('js/fateventcalendar.js');
        if ($currentLangCode = strtolower(Language::getLangCode($this->siteLangId))) {
            if (file_exists(CONF_THEME_PATH . "js/locales/$currentLangCode.js")) {
                $this->_template->addJs("js/locales/$currentLangCode.js");
            }
        }
        //$this->_template->addCss('css/fullcalendar.min.css');
        $this->_template->addJs('js/jquery.countdownTimer.min.js');
        //$this->_template->addCss('css/jquery.countdownTimer.css');
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $userDetails = $userObj->getDashboardData(CommonHelper::getLangId(), true);
        $durationArr = Statistics::getDurationTypesArr(CommonHelper::getLangId());
        $frmSrch = $this->getSearchForm();
        $frmSrch->fill(['status' => ScheduledLesson::STATUS_UPCOMING, 'show_group_classes' => ApplicationConstants::YES]);
        $this->set('frmSrch', $frmSrch);
        $this->set('durationArr', $durationArr);
        $this->set('userDetails', $userDetails);
        $this->_template->render();
    }

    private function getSettingsForm($data)
    {
        $db = FatApp::getDb();
        $srch = new TeachingLanguageSearch($this->siteLangId);
        $srch->addMultiplefields(array('tlanguagelang_tlanguage_id', 'tlanguage_name', 'tlanguage_id'));
        $srch->addChecks();
        $rs = $srch->getResultSet();
        $teachLangs = $db->fetchAll($rs, 'tlanguage_id');
        $frm = new Form('frmSettings');
        $freeTrialPackage = LessonPackage::getFreeTrialPackage();
        if (!empty($freeTrialPackage) && $freeTrialPackage['lpackage_active'] == applicationConstants::YES) {
            $frm->addCheckBox(Label::getLabel('LBL_Enable_Trial_Lesson'), 'us_is_trial_lesson_enabled', applicationConstants::YES, [], true, applicationConstants::NO);
        }

        $lessonNotificationArr = User::getLessonNotificationArr($this->siteLangId);
        //$frm->addSelectBox(Label::getLabel('LBL_How_much_notice_do_you_require_before_lessons?'), 'us_notice_number',$lessonNotificationArr,'',array())->requirements()->setRequired();

        $lesson_durations = explode(',', FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_STRING, 60));

        $durationFlds = array();
        $frm->addHtml(Label::getLabel('LBL_Lesson_Durations'), 'lesson_duration_head', '');
        foreach ($lesson_durations as $lesson_duration) {
            $durationFlds[$lesson_duration] = $frm->addCheckBox(sprintf(Label::getLabel('LBL_%d_minutes'), $lesson_duration), 'duration[' . $lesson_duration . ']', $lesson_duration);
        }

        $uTeachLangs = array_unique(array_column($data, 'utl_slanguage_id'));

        // $fldGrp = $frm->addFieldGroup();

        foreach ($lesson_durations as $lesson_duration) {
            foreach ($uTeachLangs as $uTeachLang) {
                if (!isset($teachLangs[$uTeachLang])) continue;

                $single_lesson_name = 'utl_single_lesson_amount[' . $uTeachLang . '][' . $lesson_duration . ']';

                $fld = $frm->addFloatField(sprintf(Label::getLabel('LBL_Single_Lesson_Rate(%d_min)'), $lesson_duration) . ' [' . $teachLangs[$uTeachLang]['tlanguage_name'] . ']', $single_lesson_name, '0.00');
                $fld->requirements()->setRange(1, 99999);


                // onchange update requirements for single lesson price
                $single_lesson_fld_req = new FormFieldRequirement($single_lesson_name, Label::getLabel('LBL_Single_Lesson_Rate'));
                $single_lesson_fld_req->setRequired(true);
                $single_lesson_fld_req->setRange(1, 99999);
                $durationFlds[$lesson_duration]->requirements()->addOnChangerequirementUpdate($lesson_duration, 'eq', $single_lesson_name, $single_lesson_fld_req);

                $single_lesson_fld_req2 = clone $single_lesson_fld_req;
                $single_lesson_fld_req2->setRequired(true);
                $single_lesson_fld_req2->setRange(0, 0);
                $durationFlds[$lesson_duration]->requirements()->addOnChangerequirementUpdate("", 'eq', $single_lesson_name, $single_lesson_fld_req2);
            }
        }

        foreach ($lesson_durations as $lesson_duration) {
            foreach ($uTeachLangs as $uTeachLang) {
                if (!isset($teachLangs[$uTeachLang])) continue;
                $bulk_lesson_name = 'utl_bulk_lesson_amount[' . $uTeachLang . '][' . $lesson_duration . ']';
                $fld = $frm->addFloatField(sprintf(Label::getLabel('LBL_Bulk_Lesson_Rate(%d_min)'), $lesson_duration) . ' [' . $teachLangs[$uTeachLang]['tlanguage_name'] . ']', $bulk_lesson_name, '0.00');
                $fld->requirements()->setRange(1, 99999);

                // onchange update requirements for bulk lesson price
                $bulk_lesson_fld_req = new FormFieldRequirement($bulk_lesson_name, Label::getLabel('LBL_Bulk_Lesson_Rate'));
                $bulk_lesson_fld_req->setRequired(true);
                $bulk_lesson_fld_req->setRange(1, 99999);
                $durationFlds[$lesson_duration]->requirements()->addOnChangerequirementUpdate($lesson_duration, 'eq', $bulk_lesson_name, $bulk_lesson_fld_req);

                $bulk_lesson_fld_req2 = clone $bulk_lesson_fld_req;
                $bulk_lesson_fld_req2->setRequired(true);
                $bulk_lesson_fld_req2->setRange(0, 0);
                $durationFlds[$lesson_duration]->requirements()->addOnChangerequirementUpdate("", 'eq', $bulk_lesson_name, $bulk_lesson_fld_req2);
            }
        }
        //$frm->addTextBox(Label::getLabel('M_Introduction_Video_Link'),'us_video_link','');
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE_CHANGES'));
        return $frm;
    }

    public function settingsInfoForm()
    {
        $data = UserSetting::getUserSettings(UserAuthentication::getLoggedUserId());
        $frm = $this->getSettingsForm($data);
        $frm->fill($this->formatTeachLangData($data));
        $this->set('frm', $frm);

        $srch = new TeachingLanguageSearch($this->siteLangId);
        $srch->addMultiplefields(array('tlanguagelang_tlanguage_id', 'tlanguage_name'));
        $srch->addChecks();
        $rs = $srch->getResultSet();
        $teachLangs = FatApp::getDb()->fetchAll($rs, 'tlanguagelang_tlanguage_id');
        $this->set('teachLangs', $teachLangs);

        $this->set('tprices', $data);
        $this->_template->render(false, false);
    }

    private function formatTeachLangData($data): array
    {
        if (empty($data)) return [];
        $formattedData = array('duration' => array());
        $lesson_durations = explode(',', FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_STRING, 60));
        foreach ($data as $utlData) {
            if ($utlData['utl_single_lesson_amount'] > 0) {
                $formattedData['duration'][$utlData['utl_booking_slot']] = $utlData['utl_booking_slot'];
            }

            $formattedData['utl_single_lesson_amount'][$utlData['utl_slanguage_id']][$utlData['utl_booking_slot']] = $utlData['utl_single_lesson_amount'];
            $formattedData['utl_bulk_lesson_amount'][$utlData['utl_slanguage_id']][$utlData['utl_booking_slot']] = $utlData['utl_bulk_lesson_amount'];

            foreach ($lesson_durations as $lesson_duration) {
                if (!in_array($lesson_duration, array_column($data, 'utl_booking_slot'))) {
                    $formattedData['utl_single_lesson_amount'][$utlData['utl_slanguage_id']][$lesson_duration] = '0.00';
                    $formattedData['utl_bulk_lesson_amount'][$utlData['utl_slanguage_id']][$lesson_duration] = '0.00';
                }
            }
        }
        $formattedData['us_is_trial_lesson_enabled'] = current($data)['us_is_trial_lesson_enabled'];
        return $formattedData;
    }

    public function setUpSettings()
    {
        $userSettingData = UserSetting::getUserSettings(UserAuthentication::getLoggedUserId());
        $form = $this->getSettingsForm($userSettingData);
        $post = FatApp::getPostedData();
        $data = $form->getFormDataFromArray(FatApp::getPostedData());
        if (false === $data) {
            Message::addErrorMessage(current($form->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $userId = UserAuthentication::getLoggedUserId();
        $userToLanguage = new UserToLanguage($userId);

        // CommonHelper::printArray($post);die;
        if (!empty($post['utl_single_lesson_amount'])) {
            foreach ($post['utl_single_lesson_amount'] as $tlang => $priceAr) {
                foreach ($priceAr as $slot => $single_lesson_price) {
                    $bulk_lesson_price = $post['utl_bulk_lesson_amount'][$tlang][$slot];
                    if (!in_array($slot, $post['duration'])) {
                        $single_lesson_price = 0;
                        $bulk_lesson_price = 0;
                    }
                    $utl_data = array(
                        'utl_single_lesson_amount'  => $single_lesson_price,
                        'utl_bulk_lesson_amount'    => $bulk_lesson_price,
                        'utl_slanguage_id'          => $tlang,
                        'utl_booking_slot'          => $slot
                    );
                    if (!$userToLanguage->saveTeachLang($utl_data)) {
                        Message::addErrorMessage($userToLanguage->getError());
                        FatUtility::dieJsonError(Message::getHtml());
                    }
                }
            }
        }
        $userObj = new UserSetting(UserAuthentication::getLoggedUserId());
        $isFreeTrial['us_is_trial_lesson_enabled'] = isset($post['us_is_trial_lesson_enabled']) ? $post['us_is_trial_lesson_enabled'] : 0;
        if (!$userObj->saveData($isFreeTrial)) {
            Message::addErrorMessage(Label::getLabel($userObj->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function teacherLanguagesForm()
    {
        $frm = $this->getTeacherLanguagesForm();
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function getTeacherProfileProgress()
    {
        $teacherProfileProgress = User::getTeacherProfileProgress();
        $this->set('teacherProfileProgress', $teacherProfileProgress);
        if ($teacherProfileProgress['isProfileCompleted'] == false) {

            $this->set('msg', Label::getLabel('LBL_Please_Complete_Profile_to_be_visible_on_teachers_listing_page'));
        }
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getTeacherLanguagesForm()
    {
        $frm = new Form('frmTeacherLanguages');
        $userId = UserAuthentication::getLoggedUserId();
        $db = FatApp::getDb();

        /***** Get Teaching Languages ******/
        $teacherTeachLangArr = TeachingLanguage::getAllLangs($this->siteLangId, true);
        /**********/

        /***** Get Spoken Languages ******/
        $langArr = SpokenLanguage::getAllLangs($this->siteLangId, true);
        /* ] */

        $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);

        $userToTeachLangSrch = new SearchBase('tbl_user_teach_languages');
        $userToTeachLangSrch->addMultiplefields(array('utl_slanguage_id'));
        $userToTeachLangSrch->addCondition('utl_us_user_id', '=', $userId);
        $userToTeachLangSrch->addGroupBy('utl_slanguage_id');
        $userToTeachLangRs = $userToTeachLangSrch->getResultSet();
        $userToTeachLangRows = $db->fetchAll($userToTeachLangRs);
        // prx($userToTeachLangRows);
        $userToLangSrch = new SearchBase('tbl_user_to_spoken_languages');
        $userToLangSrch->addMultiplefields(array('utsl_slanguage_id', 'utsl_proficiency'));
        $userToLangSrch->addCondition('utsl_user_id', '=', $userId);
        $userToLangRs = $userToLangSrch->getResultSet();
        $userToLangRows = $db->fetchAll($userToLangRs);

        $frm->addHtml('', 'add_more_lang', '');
        $userTeachingLang = array();
        foreach ($userToTeachLangRows as $userToTeachLangRow) {
            if (isset($teacherTeachLangArr[$userToTeachLangRow['utl_slanguage_id']])) {
                $userTeachingLang[] = $userToTeachLangRow['utl_slanguage_id'];
            }
        }

        if (empty($userTeachingLang)) {
            $frm->addSelectBox(Label::getLabel('LBL_Language_To_Teach'), 'teach_lang_id[]', $teacherTeachLangArr, array(), array(), Label::getLabel('LBL_Select'))->requirements()->setRequired();
        }
        foreach ($userToTeachLangRows as $userToTeachLangRow) {
            if (isset($teacherTeachLangArr[$userToTeachLangRow['utl_slanguage_id']])) {
                $fld1 = $frm->addSelectBox(Label::getLabel('LBL_Language_I_Teach'), 'teach_lang_id[]', $teacherTeachLangArr, array($userToTeachLangRow['utl_slanguage_id']), array(), Label::getLabel('LBL_Select'))->requirements()->setRequired();
                $fld1->developerTags['col'] = 10;
                $fld = $frm->addHtml('', 'add_minus_teach_button', '<label class="field_label -display-block"></label><a class="inline-action teachLang inline-action--minus" onclick="deleteTeachLanguageRow(' . $userToTeachLangRow['utl_slanguage_id'] . ');" href="javascript:void(0);">' . Label::getLabel('LBL_REMOVE') . '</a>');
                $fld->developerTags['col'] = 2;
            }
        }
        $frm->addHtml('', 'add_more_div_a_tag', '');
        $userSpokenLang = array();
        foreach ($userToLangRows as $userToLangRow) {
            if (isset($langArr[$userToLangRow['utsl_slanguage_id']])) {
                $userSpokenLang[] = $userToLangRow['utsl_slanguage_id'];
            }
        }

        if (empty($userSpokenLang)) {
            $frm->addSelectBox(Label::getLabel('LBL_Language_I_Speak'), 'utsl_slanguage_id[]', $langArr, array(), array('class' => 'utsl_slanguage_id'), Label::getLabel('LBL_Select'))->requirements()->setRequired();
            $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utsl_proficiency[]', $profArr, array(), array('class' => 'utsl_proficiency'), Label::getLabel('LBL_Select'))->requirements()->setRequired();
        }

        foreach ($userToLangRows as $userToLangRow) {
            if (isset($langArr[$userToLangRow['utsl_slanguage_id']])) {
                $fld1 = $frm->addSelectBox(Label::getLabel('LBL_Language_I_Speak'), 'utsl_slanguage_id[]', $langArr, array($userToLangRow['utsl_slanguage_id']), array('class' => 'utsl_slanguage_id'), Label::getLabel('LBL_Select'))->requirements()->setRequired();
                $fld1->developerTags['col'] = 5;
                $fld1 = $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utsl_proficiency[]', $profArr, array($userToLangRow['utsl_proficiency']), array('class' => 'utsl_proficiency'), Label::getLabel('LBL_Select'))->requirements()->setRequired();
                $fld1->developerTags['col'] = 5;
                $fld = $frm->addHtml('', 'add_minus_button', '<label class="field_label -display-block"></label><a class="inline-action spokenLang inline-action--minus" onclick="deleteLanguageRow(' . $userToLangRow['utsl_slanguage_id'] . ');" href="javascript:void(0);">' . Label::getLabel('LBL_REMOVE') . '</a>');
                //$fld->developerTags['col']=2;
            }
        }
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE_CHANGES'));
        $frm->addHtml('', 'add_more_div', '');
        return $frm;
    }

    public function deleteLanguageRow($id = 0)
    {
        $id = FatUtility::int($id);
        $db = FatApp::getDb();
        if (!$db->deleteRecords(UserToLanguage::DB_TBL, array('smt' => 'utsl_user_id = ? and utsl_slanguage_id = ?', 'vals' => array(UserAuthentication::getLoggedUserId(), $id)))) {
            Message::addErrorMessage(Label::getLabel($db->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Language_Removed_Successfuly!'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteTeachLanguageRow($id = 0)
    {
        $id = FatUtility::int($id);
        $db = FatApp::getDb();
        if (!$db->deleteRecords('tbl_user_teach_languages', array('smt' => 'utl_us_user_id = ? and utl_slanguage_id = ?', 'vals' => array(UserAuthentication::getLoggedUserId(), $id)))) {
            Message::addErrorMessage(Label::getLabel($db->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Language_Removed_Successfuly!'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setupTeacherLanguages()
    {
        $frm = $this->getTeacherLanguagesForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $db = FatApp::getDb();
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }

        foreach ($post['teach_lang_id'] as $tlang) {
            $lesson_durations = explode(',', FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_STRING, 60));
            foreach ($lesson_durations as $lesson_duration) {
                $insertArr = array(
                    'utl_slanguage_id' => $tlang,
                    'utl_us_user_id' => UserAuthentication::getLoggedUserId(),
                    'utl_booking_slot' => $lesson_duration
                );
                if (!$db->insertFromArray(UserToLanguage::DB_TBL_TEACH, $insertArr, false, array(), $insertArr)) {
                    $db->rollbackTransaction();
                    Message::addErrorMessage(Label::getLabel($db->getError()));
                    FatUtility::dieWithError(Message::getHtml());
                }
            }
        }
        $i = 0;
        foreach ($post['utsl_slanguage_id'] as $lang) {
            $insertArr = array('utsl_slanguage_id' => $lang, 'utsl_proficiency' => $post['utsl_proficiency'][$i], 'utsl_user_id' => UserAuthentication::getLoggedUserId());
            if (!$db->insertFromArray(UserToLanguage::DB_TBL, $insertArr, false, array(), $insertArr)) {
                $db->rollbackTransaction();
                Message::addErrorMessage(Label::getLabel($db->getError()));
                FatUtility::dieWithError(Message::getHtml());
            }
            $i++;
        }
        $db->commitTransaction();
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function teacherQualificationForm($uqualification_id = 0)
    {
        $uqualification_id =  FatUtility::int($uqualification_id);
        $experienceFrm = $this->getTeacherQualificationForm(true);
        if ($uqualification_id > 0) {
            $srch = new UserQualificationSearch();
            $srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
            $srch->addCondition('uqualification_id', '=', $uqualification_id);
            $rs = $srch->getResultSet();
            $row = FatApp::getDb()->fetch($rs);
            $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, UserAuthentication::getLoggedUserId(), $uqualification_id);
            $certificateRequried =  false; //(empty($file_row)) ? true : false;
            $field = $experienceFrm->getField('certificate');
            $field->requirements()->setRequired($certificateRequried);
            $experienceFrm->fill($row);
        }
        $this->set('experienceFrm', $experienceFrm);
        $this->_template->render(false, false);
    }

    public function setUpTeacherQualification()
    {
        $frm = $this->getTeacherQualificationForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $uqualification_id = FatApp::getPostedData('uqualification_id', FatUtility::VAR_INT, 0);
        $qualification = new UserQualification($uqualification_id);
        $post['uqualification_active'] = 1;
        $post['uqualification_user_id'] = UserAuthentication::getLoggedUserId();
        $qualification->assignValues($post);
        if (true !== $qualification->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($qualification->getError());
        }

        /* $file_row = [];
		if($uqualification_id > 0) {
			$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, UserAuthentication::getLoggedUserId() ,$uqualification_id);
		}

		if(empty($file_row) && empty($_FILES['certificate']['tmp_name'])) {
			$db->rollbackTransaction();
			Message::addErrorMessage(Label::getLabel('MSG_Please_upload_certificate'));
			FatUtility::dieJsonError(Message::getHtml());
		} */

        if (!empty($_FILES['certificate']['tmp_name'])) {
            if (!is_uploaded_file($_FILES['certificate']['tmp_name'])) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError(Label::getLabel('LBL_Please_select_a_file'));
            }
            $uqualification_id = $qualification->getMainTableRecordId();
            $fileHandlerObj = new AttachedFile();
            $res = $fileHandlerObj->saveDoc($_FILES['certificate']['tmp_name'], AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $post['uqualification_user_id'], $uqualification_id, $_FILES['certificate']['name'], -1, $unique_record = true, 0, $_FILES['certificate']['type']);
            if (!$res) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($fileHandlerObj->getError());
            }
        }
        $db->commitTransaction();
        $this->set('msg', Label::getLabel('MSG_Qualification_Setup_Successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteTeacherQualification($uqualification_id = 0)
    {
        $uqualification_id = FatUtility::int($uqualification_id);
        if ($uqualification_id < 1) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* [ */
        $srch = new UserQualificationSearch();
        $srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
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

    public function teacherQualification()
    {
        $srch = new UserQualificationSearch();
        $srch->addMultiplefields(array('uqualification_id', 'afile_name', 'uqualification_title', 'uqualification_institute_name', 'uqualification_institute_address', 'uqualification_description', 'uqualification_start_year', 'uqualification_end_year'));
        $srch->joinTable(AttachedFile::DB_TBL, 'Left Outer Join', 'uqualification_id=afile_record_subid');
        $srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('uqualification_active', '=', 1);
        $rs = $srch->getResultSet();
        $qualificationData = FatApp::getDb()->fetchAll($rs);
        $this->set('qualificationData', $qualificationData);
        $this->_template->render(false, false);
    }

    public function teacherPreferencesForm()
    {
        $frm = $this->getTeacherPreferencesForm();
        $db = FatApp::getDb();
        $teacherPreferenceSrch = new UserToPreferenceSearch();
        $teacherPreferenceSrch->joinToPreference();
        $teacherPreferenceSrch->addMultiplefields(array('utpref_preference_id', 'preference_type'));
        $teacherPreferenceSrch->addCondition('utpref_user_id', '=', UserAuthentication::getLoggedUserId());
        $rs = $teacherPreferenceSrch->getResultSet();
        $teacherPrefArr = $db->fetchAll($rs);
        /*$userSettingSrch = new UserSettingSearch();
        $userSettingSrch->joinLanguageTable( $this->siteLangId );
        $userSettingSrch->addCondition('us_user_id','=',UserAuthentication::getLoggedUserId());
        $userSettingSrch->addMultiplefields(
            array(
                'slanguage_name',
                'us_teach_slanguage_id'
                )
        );

        $userSettingRs = $userSettingSrch->getResultSet();
        $teacherTeachLangArr = $db->fetch($userSettingRs);*/
        $userToLanguage = UserToLanguage::getUserTeachlanguages(UserAuthentication::getLoggedUserId(), true);
        $userToLanguage->doNotCalculateRecords();
        $userToLanguage->doNotLimitRecords();
        $userToLanguage->addMultipleFields(['GROUP_CONCAT(DISTINCT IFNULL(tlanguage_name, tlanguage_identifier)) as teachLang']);
        $resultSet = $userToLanguage->getResultSet();
        $teachLangs = FatApp::getDb()->fetch($resultSet);
        $teacherTeachLang = (!empty($teachLangs['teachLang'])) ? $teachLangs['teachLang'] :  '';
        $arrOptions = array();
        foreach ($teacherPrefArr as $val) {
            $arrOptions['pref_' . $val['preference_type']][] = $val['utpref_preference_id'];
        }
        $frm->fill($arrOptions);
        $this->set('teachLang', $teacherTeachLang);
        $this->set('teacherPreferencesFrm', $frm);
        $this->_template->render(false, false);
    }

    public function setupTeacherPreferences()
    {
        $frm = $this->getTeacherPreferencesForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $db = FatApp::getDb();
        if (false === $post) {
            FatUtility::dieWithError(current($frm->getValidationErrors()));
        }
        $titleArr = Preference::getPreferenceTypeArr($this->siteLangId);
        $deleteRecords = $db->deleteRecords(Preference::DB_TBL_USER_PREF, array('smt' => 'utpref_user_id = ?', 'vals' => array(UserAuthentication::getLoggedUserId())));
        if (!$deleteRecords) {
            FatUtility::dieWithError($db->getError());
        }
        unset($post['teach_lang']);
        foreach ($post as  $key => $val) {
            if (empty($val)) {
                continue;
            }
            foreach ($val as $innerVal) {
                if (!$db->insertFromArray(Preference::DB_TBL_USER_PREF, array('utpref_preference_id' => $innerVal, 'utpref_user_id' => UserAuthentication::getLoggedUserId()))) {
                    FatUtility::dieWithError($db->getError());
                }
            }
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Preferences_updated_successfully!'));
    }

    private function getTeacherPreferencesForm()
    {
        $frm = new Form('teacherPreferencesFrm');
        /* [ */
        $userSettingSrch = new UserSettingSearch();
        $userSettingSrch->joinLanguageTable($this->siteLangId);
        $userSettingSrch->addCondition('us_user_id', '=', UserAuthentication::getLoggedUserId());
        $userSettingSrch->addMultiplefields(
            array(
                'slanguage_code',
                'IFNULL(slanguage_name, slanguage_identifier) as slanguage_name'
            )
        );

        $userSettingRs = $userSettingSrch->getResultSet();
        $teacherTeachLangArr = FatApp::getDb()->fetch($userSettingRs);
        /* ] */
        $preferencesArr = Preference::getPreferencesArr($this->siteLangId);
        $titleArr = Preference::getPreferenceTypeArr($this->siteLangId);
        $frm->addTextArea(Label::getLabel("LBL_Language_that_I'm_teaching"), 'teach_lang', '', array('disabled' => 'disabled'));
        foreach ($preferencesArr as  $key => $val) {
            if ($key == Preference::TYPE_ACCENTS && $teacherTeachLangArr['slanguage_code'] != "EN") {
                //continue;
            }

            $optionsArr = array();
            foreach ($val as $innerVal) {
                $optionsArr[$innerVal['preference_id']] = $innerVal['preference_title'];
            }
            if (isset($titleArr[$key])) {
                $frm->addCheckBoxes($titleArr[$key], 'pref_' . $key, $optionsArr, '', array('class' => 'list-onethird list-onethird--bg'));
            }
        }
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }

    public function teacherGeneralAvailability()
    {
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $this->set('cssClassArr', $cssClassNamesArr);
        $userId = UserAuthentication::getLoggedUserId();
        $this->set('userId', $userId);
        $currentLangCode = strtolower(Language::getLangCode($this->siteLangId));
        $this->set('currentLangCode', $currentLangCode);
        $this->_template->render(false, false);
    }

    public function teacherWeeklySchedule()
    {
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $this->set('cssClassArr', $cssClassNamesArr);
        $userId = UserAuthentication::getLoggedUserId();
        $this->set('userId', $userId);
        $currentLangCode = strtolower(Language::getLangCode($this->siteLangId));
        $this->set('currentLangCode', $currentLangCode);
        $this->_template->render(false, false);
    }

    public function deleteTeacherGeneralAvailability($tgavl_id = 0)
    {
        $tgavl_id = FatUtility::int($tgavl_id);
        $tGAvail = new TeacherGeneralAvailability();
        if (!$tGAvail->deleteTeacherGeneralAvailability($tgavl_id, UserAuthentication::getLoggedUserId())) {
            FatUtility::dieWithError($tGAvail->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Availability_deleted_successfully!'));
    }

    public function deleteTeacherWeeklySchedule()
    {
        $post = FatApp::getPostedData();
        if (false === $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $postJson = json_decode($post['data']);
        $userId = UserAuthentication::getLoggedUserId();
        $tWsch = new TeacherWeeklySchedule();
        if (!$tWsch->deleteTeacherWeeklySchedule($userId, $postJson->start, $postJson->end, $postJson->date, $postJson->day, $postJson->_id)) {
            FatUtility::dieWithError($tWsch->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Availability_deleted_successfully!'));
    }

    public function setupTeacherGeneralAvailability()
    {
        $post = FatApp::getPostedData();
        if (false === $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $tGAvail = new TeacherGeneralAvailability();
        if (!$tGAvail->addTeacherGeneralAvailability($post, UserAuthentication::getLoggedUserId())) {
            FatUtility::dieWithError($tGAvail->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Availability_updated_successfully!'));
    }

    public function setupTeacherWeeklySchedule()
    {
        $post = FatApp::getPostedData();
        if (false === $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $userId = UserAuthentication::getLoggedUserId();
        $tWsch = new TeacherWeeklySchedule();
        if (!$tWsch->addTeacherWeeklySchedule($post, $userId)) {
            FatUtility::dieWithError($tWsch->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Availability_updated_successfully!'));
    }

    public function qualificationFile($userId = 0, $subRecordId = 0, $sizeType = '', $cropedImage = false)
    {
        $userId = UserAuthentication::getLoggedUserId();
        $recordId = FatUtility::int($userId);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $recordId, $subRecordId);
        $image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h);
                break;
            default:
                AttachedFile::displayOriginalImage($image_name);
                break;
        }
    }

    public function paypalEmailAddressForm()
    {
        $frm = $this->getPaypalEmailAddressForm();
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $data = $userObj->getUserPaypalInfo();
        $frm->fill($data);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setUpPaypalInfo()
    {
        $frm = $this->getPaypalEmailAddressForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userObj = new User(UserAuthentication::getLoggedUserId());
        if (!$userObj->updatePaypalInfo($post)) {
            Message::addErrorMessage(Label::getLabel($userObj->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getPaypalEmailAddressForm()
    {
        $frm = new Form('frmBankInfo');
        $frm->addEmailField(Label::getLabel('M_Paypal_Email_Address'), 'ub_paypal_email_address');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        return $frm;
    }

    public function bankInfoForm()
    {
        $frm = $this->getBankInfoForm();
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $data = $userObj->getUserBankInfo();
        $frm->fill($data);
        $this->set('frm', $frm);
        $this->set('activePaypalPayout', PaypalPayout::isMethodActive());

        $this->_template->render(false, false);
    }

    private function getBankInfoForm()
    {
        $frm = new Form('frmBankInfo');
        $frm->addRequiredField(Label::getLabel('M_Bank_Name'), 'ub_bank_name', '');
        $frm->addRequiredField(Label::getLabel('M_Beneficiary/Account_Holder_Name'), 'ub_account_holder_name', '');
        $frm->addRequiredField(Label::getLabel('M_Bank_Account_Number'), 'ub_account_number', '');
        $frm->addRequiredField(Label::getLabel('M_IFSC_Code/Swift_Code'), 'ub_ifsc_swift_code', '');
        $frm->addTextArea(Label::getLabel('M_Bank_Address'), 'ub_bank_address', '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        return $frm;
    }

    public function setUpBankInfo()
    {
        $frm = $this->getBankInfoForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userObj = new User(UserAuthentication::getLoggedUserId());
        if (!$userObj->updateBankInfo($post)) {
            Message::addErrorMessage(Label::getLabel($userObj->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function message($userId = 0)
    {
        $userId = FatUtility::int($userId);
        $userObj = new User($userId);
        $userDetails = $userObj->getUserInfo(null, true, true);
        if (!$userDetails || $userId == UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        $teacherObj = new User(UserAuthentication::getLoggedUserId());
        $teacherDetails = $teacherObj->getUserInfo(null, true, true);
        $this->set('teacherDetails', $teacherDetails);
        $this->set('userDetails', $userDetails);
        $this->_template->render();
    }

    public function orders()
    {
        $frmOrderSrch = $this->getOrderSearchForm($this->siteLangId);
        $this->set('frmOrderSrch', $frmOrderSrch);
        $this->_template->render();
    }

    private function getOrderSearchForm($langId)
    {
        $frm = new Form('frmOrderSrch');
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $langId), 'keyword', '', array('placeholder' => Label::getLabel('LBL_Keyword', $langId)));
        $frm->addSelectBox(Label::getLabel('LBL_Status', $langId), 'status', array(-2 => Label::getLabel('LBL_Does_Not_Matter', $langId)) + Order::getPaymentStatusArr($langId), '', array('placeholder' => 'Select Status'), '');
        $frm->addDateField(Label::getLabel('LBL_Date_From', $langId), 'date_from', '', array('placeholder' => '', 'readonly' => 'readonly'));
        $frm->addDateField(Label::getLabel('LBL_Date_To', $langId), 'date_to', '', array('placeholder' => '', 'readonly' => 'readonly'));
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Submit', $langId), array('class' => 'btn btn--primary'));
        $fld_cancel = $frm->addResetButton("", "btn_clear", Label::getLabel('LBL_Clear', $langId), array('onclick' => 'clearSearch();', 'class' => 'btn--clear'));
        $fld_submit->attachField($fld_cancel);
        $frm->addHiddenField('', 'page', 1);
        return $frm;
    }

    public function getOrders()
    {
        $frm = $this->getOrderSearchForm($this->siteLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $ordersData = Order::getOrders($post, User::USER_TYPE_TEACHER, UserAuthentication::getLoggedUserId());
        $statusArr = Order::getPaymentStatusArr($this->siteLangId);
        $this->set('statusArr', $statusArr);
        $this->set('ordersData', $ordersData);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }
}
