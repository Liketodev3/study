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
        $viewProfile = true;
        if (false == $teacherProfileProgress['isProfileCompleted']) {
            $viewProfile = false;
        }
        /* ] */
     
        $userId = UserAuthentication::getLoggedUserId();
        $userObj = new User($userId);
        $userDetails = $userObj->getDashboardData($this->siteLangId, true);
        $durationArr = Statistics::getDurationTypesArr($this->siteLangId);
        $statistics  = new Statistics($userId);
        $earningData = $statistics->getEarning(Statistics::TYPE_ALL);

        $frmSrch = $this->getSearchForm();
        $frmSrch->fill(['status' => ScheduledLesson::STATUS_UPCOMING, 'show_group_classes' => ApplicationConstants::YES]);
       $reportSearchForm =  $this->reportSearchForm($this->siteLangId);
    //    $reportSearchForm->fill(['report_type' => [Statistics::REPORT_EARNING, Statistics::REPORT_SOLD_LESSONS]]);

        $this->set('frmSrch', $frmSrch);
        $this->set('durationArr', $durationArr);
        $this->set('earningData', $earningData);
        $this->set('teacherProfileProgress',  $teacherProfileProgress);
        $this->set('userDetails', $userDetails);
        $this->set('viewProfile', $viewProfile);
        $this->set('reportSearchForm', $reportSearchForm);
        $this->set('userTotalWalletBalance', User::getUserBalance($userId, false));

        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addJs('js/fateventcalendar.js');
        if ($currentLangCode = strtolower(Language::getLangCode($this->siteLangId))) {
            if (file_exists(CONF_THEME_PATH . "js/locales/$currentLangCode.js")) {
                $this->_template->addJs("js/locales/$currentLangCode.js");
            }
        }
        $this->_template->addJs('js/jquery.countdownTimer.min.js');

        $this->_template->render();
    }

    private function getSettingsForm($data)
    {
        $db = FatApp::getDb();
        $srch = new TeachingLanguageSearch($this->siteLangId);
        $srch->addMultiplefields(['tlanguagelang_tlanguage_id', 'tlanguage_name', 'tlanguage_id']);
        $srch->addChecks();
        $rs = $srch->getResultSet();
        $teachLangs = $db->fetchAll($rs, 'tlanguage_id');
        $frm = new Form('frmSettings');
        $freeTrialPackage = LessonPackage::getFreeTrialPackage();
        if (!empty($freeTrialPackage) && $freeTrialPackage['lpackage_active'] == applicationConstants::YES) {
            $frm->addCheckBox(Label::getLabel('LBL_Enable_Trial_Lesson'), 'us_is_trial_lesson_enabled', applicationConstants::YES, [], true, applicationConstants::NO);
        }
        $lessonNotificationArr = User::getLessonNotificationArr($this->siteLangId);
        $lesson_durations = explode(',', FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_STRING, 60));
        $durationFlds = [];
        $frm->addHtml(Label::getLabel('LBL_Lesson_Durations'), 'lesson_duration_head', '');
        foreach ($lesson_durations as $lesson_duration) {
            $durationFlds[$lesson_duration] = $frm->addCheckBox(sprintf(Label::getLabel('LBL_%d_minutes'), $lesson_duration), 'duration[' . $lesson_duration . ']', $lesson_duration);
        }
        $uTeachLangs = array_unique(array_column($data, 'utl_slanguage_id'));
        foreach ($lesson_durations as $lesson_duration) {
            foreach ($uTeachLangs as $uTeachLang) {
                if (!isset($teachLangs[$uTeachLang]))
                    continue;
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
                if (!isset($teachLangs[$uTeachLang]))
                    continue;
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
        $srch->addMultiplefields(['tlanguagelang_tlanguage_id', 'tlanguage_name']);
        $srch->addChecks();
        $rs = $srch->getResultSet();
        $teachLangs = FatApp::getDb()->fetchAll($rs, 'tlanguagelang_tlanguage_id');
        $this->set('teachLangs', $teachLangs);
        $this->set('tprices', $data);
        $this->_template->render(false, false);
    }

    private function formatTeachLangData($data): array
    {
        if (empty($data)) {
            return [];
        }
        $formattedData = ['duration' => []];
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
        if (!empty($post['utl_single_lesson_amount'])) {
            foreach ($post['utl_single_lesson_amount'] as $tlang => $priceAr) {
                foreach ($priceAr as $slot => $single_lesson_price) {
                    $bulk_lesson_price = $post['utl_bulk_lesson_amount'][$tlang][$slot];
                    if (!in_array($slot, $post['duration'])) {
                        $single_lesson_price = 0;
                        $bulk_lesson_price = 0;
                    }
                    $utl_data = [
                        'utl_single_lesson_amount' => $single_lesson_price,
                        'utl_bulk_lesson_amount' => $bulk_lesson_price,
                        'utl_slanguage_id' => $tlang,
                        'utl_booking_slot' => $slot
                    ];
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
        /* Update Teach Lang Minimum & Maximum Prices */
        (new TeacherStat($userId))->setTeachLangPrices();
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function teacherLanguagesForm()
    {
        $speakLangs = SpokenLanguage::getAllLangs($this->siteLangId, true);
        $frm = $this->getTeacherLanguagesForm($this->siteLangId, $speakLangs);
        $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
       
        $this->set('frm', $frm);
        $this->set('speakLangs', $speakLangs);
        $this->set('profArr', $profArr);
        $this->_template->render(false, false);
    }

    public function getTeacherProfileProgress()
    {
        $teacherProfileProgress = User::getTeacherProfileProgress();
        $this->set('teacherProfileProgress', $teacherProfileProgress);
        $msg =  '';
        if ($teacherProfileProgress['isProfileCompleted'] == false) {
            $msg = Label::getLabel('LBL_Please_Complete_Profile_to_be_visible_on_teachers_listing_page');
        }
        FatUtility::dieJsonSuccess(['msg' => $msg, 'teacherProfileProgress' => $teacherProfileProgress]);
    }

    private function getTeacherLanguagesForm(int $langId,  array $spokenLangs = [])
    {
        $frm = new Form('frmTeacherLanguages');
        $userId = UserAuthentication::getLoggedUserId();
        $db = FatApp::getDb();
        /*         * *** Get Teaching Languages ***** */
        $teacherTeachLangArr = TeachingLanguage::getAllLangs($langId, true);
        /*         * ******* */
        /*         * *** Get Spoken Languages ***** */
        $langArr = $spokenLangs ?: SpokenLanguage::getAllLangs($langId, true);
        /* ] */
        $profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
        $userToTeachLangSrch = new SearchBase('tbl_user_teach_languages');
        $userToTeachLangSrch->addMultiplefields(['utl_slanguage_id']);
        $userToTeachLangSrch->addCondition('utl_us_user_id', '=', $userId);
        $userToTeachLangSrch->addGroupBy('utl_slanguage_id');
        $userToTeachLangRs = $userToTeachLangSrch->getResultSet();
        $userToTeachLangRows = $db->fetchAll($userToTeachLangRs, 'utl_slanguage_id');
        
        $userToLangSrch = new SearchBase('tbl_user_to_spoken_languages');
        $userToLangSrch->addMultiplefields(['utsl_slanguage_id', 'utsl_proficiency']);
        $userToLangSrch->addCondition('utsl_user_id', '=', $userId);
        $userToLangRs = $userToLangSrch->getResultSet();
        $spokenLangRows = $db->fetchAllAssoc($userToLangRs);
        $frm->addCheckBoxes(Label::getLabel('LBL_Language_To_Teach'), 'teach_lang_id', $teacherTeachLangArr, array_keys($userToTeachLangRows))->requirements()->setRequired();

        foreach ($langArr as $key => $lang) {

            $speekLangField = $frm->addCheckBox(Label::getLabel('LBL_Language_I_Speak'), 'utsl_slanguage_id['.$key.']', $key, ['class' => 'utsl_slanguage_id'], false, '0'); 
            $proficiencyField = $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utsl_proficiency['.$key.']', $profArr, '', ['class' => 'utsl_proficiency select__dropdown'], Label::getLabel("LBL_I_don't_speak_this_language")); 
            if(array_key_exists($key, $spokenLangRows)){
                $proficiencyField->value = $spokenLangRows[$key];
                $speekLangField->checked = true;    
                $speekLangField->value = $key;           
            }
            
            $proficiencyField->requirements()->setRequired();
            $speekLangField->requirements()->addOnChangerequirementUpdate(0,'gt', $proficiencyField->getName(),  $proficiencyField->requirements());

            $proficiencyField->requirements()->setRequired(false);
            $speekLangField->requirements()->addOnChangerequirementUpdate(0,'le', $proficiencyField->getName(),  $proficiencyField->requirements());
            
            $speekLangField->requirements()->setRequired();
            $proficiencyField->requirements()->addOnChangerequirementUpdate(0, 'gt', $proficiencyField->getName(), $speekLangField->requirements());

            $speekLangField->requirements()->setRequired(false);
            $proficiencyField->requirements()->addOnChangerequirementUpdate(0, 'le', $proficiencyField->getName(), $speekLangField->requirements());
       
        }
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE_CHANGES'));
        $frm->addButton('', 'next_btn', Label::getLabel('LBL_Next'));
        $frm->addButton('', 'back_btn', Label::getLabel('LBL_Back'));
        return $frm;
    }

    public function deleteLanguageRow($id = 0)
    {
        $id = FatUtility::int($id);
        $teacherId = UserAuthentication::getLoggedUserId();
        $db = FatApp::getDb();
        if (!$db->deleteRecords(UserToLanguage::DB_TBL, ['smt' => 'utsl_user_id = ? and utsl_slanguage_id = ?', 'vals' => [$teacherId, $id]])) {
            Message::addErrorMessage(Label::getLabel($db->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        /* Update Teacher's teach language stat */
        (new TeacherStat($teacherId))->setSpeakLang();
        $this->set('msg', Label::getLabel('MSG_Language_Removed_Successfuly!'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteTeachLanguageRow($id = 0)
    {
        $userId = UserAuthentication::getLoggedUserId();
        $db = FatApp::getDb();
        if (!$db->deleteRecords('tbl_user_teach_languages', ['smt' => 'utl_us_user_id = ? and utl_slanguage_id = ?', 'vals' => [$userId, FatUtility::int($id)]])) {
            Message::addErrorMessage(Label::getLabel($db->getError()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        /* Update Teach Lang Minimum & Maximum Prices */
        (new TeacherStat($userId))->setTeachLangPrices();
        $this->set('msg', Label::getLabel('MSG_Language_Removed_Successfuly!'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setupTeacherLanguages()
    {
        $frm = $this->getTeacherLanguagesForm($this->siteLangId, []);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        
        if(empty(FatApp::getPostedData('utsl_slanguage_id'))){
            FatUtility::dieJsonError(Label::getLabel('Lbl_Speak_Language_is_Requried'));
        }

        $teacherId = UserAuthentication::getLoggedUserId();
        $db = FatApp::getDb();
        $db->startTransaction();
        $query = 'DELETE  FROM ' . UserToLanguage::DB_TBL_TEACH . ' WHERE utl_us_user_id = '. $teacherId;
        if(!empty($post['teach_lang_id'])){
                $langIds = implode(",", $post['teach_lang_id']);
                $query .= ' and utl_slanguage_id NOT IN (' . $langIds . ')';
        }
       $db->query($query);
       if ($db->getError()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($db->getError());
           return false;
       }

       
        foreach ($post['teach_lang_id'] as $tlang) {
            $lesson_durations = explode(',', FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_STRING, 60));
            foreach ($lesson_durations as $lesson_duration) {
                $insertArr = [
                    'utl_slanguage_id' => $tlang,
                    'utl_us_user_id' => UserAuthentication::getLoggedUserId(),
                    'utl_booking_slot' => $lesson_duration
                ];
                if (!$db->insertFromArray(UserToLanguage::DB_TBL_TEACH, $insertArr, false, [], $insertArr)) {
                    $db->rollbackTransaction();
                    FatUtility::dieJsonError($db->getError());
                }
            }
        }

        $query = 'DELETE  FROM ' . UserToLanguage::DB_TBL . ' WHERE utsl_user_id = '. $teacherId;
        if(!empty($post['utsl_slanguage_id'])){
                $langIds = implode(",", $post['utsl_slanguage_id']);
                $query .= ' and utsl_slanguage_id NOT IN (' . $langIds . ')';
        }

       $db->query($query);
       if ($db->getError()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($db->getError());
           return false;
       }
    
        foreach ($post['utsl_slanguage_id'] as $key => $lang) {
            $insertArr = ['utsl_slanguage_id' => $lang, 'utsl_proficiency' => $post['utsl_proficiency'][$key], 'utsl_user_id' => $teacherId];
            if (!$db->insertFromArray(UserToLanguage::DB_TBL, $insertArr, false, [], $insertArr)) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($db->getError());
            }
        }

        
        /* Update Teacher's teach language stat */
        (new TeacherStat($teacherId))->setSpeakLang();
        $db->commitTransaction();
        $this->set('msg', Label::getLabel('MSG_Setup_successful'));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function deleteUserSpeakOrTeachLang($table, $field, $userId, $langs)
    {
        $query = 'DELETE  FROM ' . $table;
        if($table ==  UserToLanguage::DB_TBL){
            $query .= ' utsl_user_id = '.$userId;
        }
    }

    public function teacherQualificationForm($uqualification_id = 0)
    {
        $uqualification_id = FatUtility::int($uqualification_id);
        $experienceFrm = $this->getTeacherQualificationForm(true);
        if ($uqualification_id > 0) {
            $srch = new UserQualificationSearch();
            $srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
            $srch->addCondition('uqualification_id', '=', $uqualification_id);
            $rs = $srch->getResultSet();
            $row = FatApp::getDb()->fetch($rs);
            $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, UserAuthentication::getLoggedUserId(), $uqualification_id);
            $certificateRequried = false; //(empty($file_row)) ? true : false;
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
        /* Update Teacher's Qualification stat */
        (new TeacherStat(UserAuthentication::getLoggedUserId()))->setQualification();
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
        $teacherId = UserAuthentication::getLoggedUserId();
        $srch = new UserQualificationSearch();
        $srch->addCondition('uqualification_user_id', '=', $teacherId);
        $srch->addCondition('uqualification_id', '=', $uqualification_id);
        $srch->addMultiplefields(['uqualification_id']);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
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
        /* Update Teacher's Qualification stat */
        (new TeacherStat($teacherId))->setQualification();
        $this->set('msg', Label::getLabel('MSG_Qualification_Removed_Successfuly'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function teacherQualification()
    {
        $srch = new UserQualificationSearch();
        $srch->addMultiplefields(['uqualification_id', 'afile_name', 'uqualification_title', 'uqualification_institute_name', 'uqualification_institute_address', 'uqualification_description', 'uqualification_start_year', 'uqualification_end_year']);
        $srch->joinTable(AttachedFile::DB_TBL, 'Left Outer Join', 'uqualification_id=afile_record_subid');
        $srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('uqualification_active', '=', 1);
        $qualificationData = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set('qualificationData', $qualificationData);
        $this->_template->render(false, false);
    }

    public function teacherPreferencesForm()
    {
        $frm = $this->getTeacherPreferencesForm();
        $db = FatApp::getDb();
        $teacherPreferenceSrch = new UserToPreferenceSearch();
        $teacherPreferenceSrch->joinToPreference();
        $teacherPreferenceSrch->addMultiplefields(['utpref_preference_id', 'preference_type']);
        $teacherPreferenceSrch->addCondition('utpref_user_id', '=', UserAuthentication::getLoggedUserId());
        $rs = $teacherPreferenceSrch->getResultSet();
        $teacherPrefArr = $db->fetchAll($rs);
        $userToLanguage = UserToLanguage::getUserTeachlanguages(UserAuthentication::getLoggedUserId(), true);
        $userToLanguage->doNotCalculateRecords();
        $userToLanguage->doNotLimitRecords();
        $userToLanguage->addMultipleFields(['GROUP_CONCAT(DISTINCT IFNULL(tlanguage_name, tlanguage_identifier)) as teachLang']);
        $resultSet = $userToLanguage->getResultSet();
        $teachLangs = FatApp::getDb()->fetch($resultSet);
        $teacherTeachLang = (!empty($teachLangs['teachLang'])) ? $teachLangs['teachLang'] : '';
        $arrOptions = [];
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
        if (false === $post) {
            FatUtility::dieWithError(current($frm->getValidationErrors()));
        }
        
        $db = FatApp::getDb();
        $userId = UserAuthentication::getLoggedUserId();
        $deleteRecords = $db->deleteRecords(Preference::DB_TBL_USER_PREF, ['smt' => 'utpref_user_id = ?', 'vals' => [$userId]]);
        if (!$deleteRecords) {
            FatUtility::dieWithError($db->getError());
        }
        unset($post['teach_lang']);
        $preference = 0;
        foreach ($post as $key => $val) {
            if (empty($val)) {
                continue;
            }
            foreach ($val as $innerVal) {
                if (!$db->insertFromArray(Preference::DB_TBL_USER_PREF, ['utpref_preference_id' => $innerVal, 'utpref_user_id' => $userId])) {
                    FatUtility::dieWithError($db->getError());
                }
                $preference = 1;
            }
        }
        //
        /* Update Teacher's Preferences */
        (new TeacherStat($userId))->setPreference($preference);
     
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Preferences_updated_successfully!'));
    }

    private function getTeacherPreferencesForm()
    {
        $frm = new Form('teacherPreferencesFrm');
        /* [ */
        $userSettingSrch = new UserSettingSearch();
        $userSettingSrch->joinLanguageTable($this->siteLangId);
        $userSettingSrch->addCondition('us_user_id', '=', UserAuthentication::getLoggedUserId());
        $userSettingSrch->addMultiplefields(['slanguage_code', 'IFNULL(slanguage_name, slanguage_identifier) as slanguage_name']);
        $userSettingRs = $userSettingSrch->getResultSet();
        $teacherTeachLangArr = FatApp::getDb()->fetch($userSettingRs);
        /* ] */
        $preferencesArr = Preference::getPreferencesArr($this->siteLangId);
        $titleArr = Preference::getPreferenceTypeArr($this->siteLangId);
      
        $frm->addTextArea(Label::getLabel("LBL_Language_that_I'm_teaching"), 'teach_lang', '', ['disabled' => 'disabled']);
        foreach ($preferencesArr as $key => $val) {
            if ($key == Preference::TYPE_ACCENTS && $teacherTeachLangArr['slanguage_code'] != "EN") {
                //continue;
            }
            $optionsArr = [];
            foreach ($val as $innerVal) {
                $optionsArr[$innerVal['preference_id']] = $innerVal['preference_title'];
            }
            if (isset($titleArr[$key])) {
                $frm->addCheckBoxes($titleArr[$key], 'pref_' . $key, $optionsArr, '', ['class' => 'list-onethird list-onethird--bg']);
            }
        }
        $frm->addButton('', 'btn_back', Label::getLabel('LBL_Back'));
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Save_Changes'));
        $frm->addButton('', 'btn_next', Label::getLabel('LBL_next'));
        return $frm;
    }

    public function availability()
    {
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $this->set('cssClassArr', $cssClassNamesArr);
        $userId = UserAuthentication::getLoggedUserId();
        $this->set('userId', $userId);
        $currentLangCode = strtolower(Language::getLangCode($this->siteLangId));
        $this->set('currentLangCode', $currentLangCode);
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addJs('js/fateventcalendar.js');
        $this->_template->render();
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
        $userId = UserAuthentication::getLoggedUserId();
        $tGAvail = new TeacherGeneralAvailability();
        if (!$tGAvail->addTeacherGeneralAvailability($post, $userId)) {
            FatUtility::dieWithError($tGAvail->getError());
        }
        /* Update Teacher's General Availability Stat */
        $available  = json_decode($post['data'] ?? '') ? 1 : 0;
        (new TeacherStat($userId))->setGavailability($available);
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
        $frm->addButton('', 'btn_back', Label::getLabel('LBL_Back'));
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
        $frm->addButton('', 'btn_back', Label::getLabel('LBL_Back'));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE'));
        // $frm->addButton('', 'btn_next', Label::getLabel('LBL_Next'));
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
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $langId), 'keyword', '', ['placeholder' => Label::getLabel('LBL_Keyword', $langId)]);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $langId), 'status', [-2 => Label::getLabel('LBL_Does_Not_Matter', $langId)] + Order::getPaymentStatusArr($langId), '', ['placeholder' => 'Select Status'], '');
        $frm->addDateField(Label::getLabel('LBL_Date_From', $langId), 'date_from', '', ['placeholder' => '', 'readonly' => 'readonly']);
        $frm->addDateField(Label::getLabel('LBL_Date_To', $langId), 'date_to', '', ['placeholder' => '', 'readonly' => 'readonly']);
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Submit', $langId), ['class' => 'btn btn--primary']);
        $fld_cancel = $frm->addResetButton("", "btn_clear", Label::getLabel('LBL_Clear', $langId), ['onclick' => 'clearSearch();', 'class' => 'btn--clear']);
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
