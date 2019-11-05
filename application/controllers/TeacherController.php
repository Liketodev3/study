<?php
class TeacherController extends TeacherBaseController {
	
	public function __construct($action) {
		parent::__construct($action);
	}
	
	public function index() {
		/* Validate Teacher has filled complete profile[ */
		if (true != User::isTeacherProfileCompleted()) {
			Message::addInfo(Label::getLabel('LBL_Please_Complete_Profile_to_be_visible_on_teachers_listing_page'));
            $this->set('viewProfile', false);
			FatApp::redirectUser(CommonHelper::generateUrl('account', 'profileInfo'));
		} else {
			$this->set('viewProfile',true);
        }
		/* ] */
		$this->_template->addCss('css/custom-full-calendar.css');
		$this->_template->addJs('js/moment.min.js');
		$this->_template->addJs('js/fullcalendar.min.js');
		$this->_template->addCss('css/fullcalendar.min.css');		
		$userObj = new User(UserAuthentication::getLoggedUserId());
		$userDetails = $userObj->getDashboardData(CommonHelper::getLangId(), true);
		$durationArr = Statistics::getDurationTypesArr(CommonHelper::getLangId());
		$frmSrch = $this->getSearchForm();
		$this->set('frmSrch', $frmSrch);		
		$this->set('durationArr', $durationArr);
		$this->set('userDetails', $userDetails);
		$this->_template->render();
	}
	
	private function getSettingsForm($data) {
        $db = FatApp::getDb();
		$srch = new TeachingLanguageSearch($this->siteLangId);
		$srch->addMultiplefields(array('tlanguagelang_tlanguage_id', 'tlanguage_name'));
		$srch->addChecks();
		$rs=$srch->getResultSet();
		$teachLangs = $db->fetchAll($rs, 'tlanguagelang_tlanguage_id');
		$frm = new Form('frmSettings');
		$frm->addCheckBox(Label::getLabel('LBL_Enable_Trial_Lesson'), 'us_is_trial_lesson_enabled', 1);
		$lessonNotificationArr = User::getLessonNotificationArr($this->siteLangId);
		//$frm->addSelectBox(Label::getLabel('LBL_How_much_notice_do_you_require_before_lessons?'), 'us_notice_number',$lessonNotificationArr,'',array())->requirements()->setRequired();

        foreach($data as $us_data) {
            if (empty($us_data['utl_slanguage_id'])) {
                continue;
            }
			if (isset( $teachLangs[$us_data['utl_slanguage_id']])) {
				$fld = $frm->addRequiredField(Label::getLabel('M_Single_Lesson_Rate').' ['. $teachLangs[$us_data['utl_slanguage_id']]['tlanguage_name'] .']', 'us_single_lesson_amount['.$us_data['utl_slanguage_id'].']', '');
				$fld->requirements()->setRange(1, 99999);
				$fld = $frm->addRequiredField(Label::getLabel('M_Bulk_Lesson_Rate').' ['. $teachLangs[$us_data['utl_slanguage_id']]['tlanguage_name'] .']', 'us_bulk_lesson_amount['.$us_data['utl_slanguage_id'].']', '');
				$fld->requirements()->setRange(1, 99999);
			}
        }
		//$frm->addTextBox(Label::getLabel('M_Introduction_Video_Link'),'us_video_link','');
		$frm->addSubmitButton('&nbsp;', 'submit', Label::getLabel('LBL_SAVE_CHANGES'));	
		return $frm;
	}
	
	public function settingsInfoForm() {
		$data = UserSetting::getUserSettings(UserAuthentication::getLoggedUserId());        
		$frm = $this->getSettingsForm($data);
        if ($data) {
            $filledData = array();
            foreach ($data as $utlData) {
                $filledData['us_single_lesson_amount'][$utlData['utl_slanguage_id']] = $utlData['utl_single_lesson_amount'];
                $filledData['us_bulk_lesson_amount'][$utlData['utl_slanguage_id']] = $utlData['utl_bulk_lesson_amount'];
            }
            $filledData['us_is_trial_lesson_enabled'] = current($data)['us_is_trial_lesson_enabled'];
            $frm->fill($filledData);
        }
		$this->set('frm', $frm);		
		$this->_template->render(false, false);
	}
	
	/*public function setUpSettings(){
		
		$frm = $this->getSettingsForm();		
		$post = $frm->getFormDataFromArray(FatApp::getPostedData());
		
		if (false === $post) {
			Message::addErrorMessage(current($frm->getValidationErrors()));
			FatUtility::dieJsonError( Message::getHtml() );	
		}
		
		$userObj = new UserSetting( UserAuthentication::getLoggedUserId() );
		unset( $post['submit'] );
		if (!$userObj->saveData($post)) {
			Message::addErrorMessage(Label::getLabel($userObj->getError()));
			FatUtility::dieJsonError( Message::getHtml() );
		}
		
		$this->set('msg', Label::getLabel('MSG_Setup_successful'));	
		$this->_template->render(false, false, 'json-success.php');
	}*/
	public function setUpSettings() {
		$post = FatApp::getPostedData();
		if (false === $post) {
			Message::addErrorMessage(current($frm->getValidationErrors()));
			FatUtility::dieJsonError(Message::getHtml());	
		}

        if (isset($post['us_single_lesson_amount'])) {
            foreach ($post['us_single_lesson_amount'] as $k=>$tlang) {
                $record = new TableRecord('tbl_user_teach_languages');
                $record->assignValues(array('utl_single_lesson_amount' => $tlang, 'utl_bulk_lesson_amount' => $post['us_bulk_lesson_amount'][$k]));
                if (!$record->update(array('smt'=>'utl_us_user_id=? and utl_slanguage_id=?', 'vals'=>array(UserAuthentication::getLoggedUserId(),$k)))) {
                    $this->error = $record->getError();
                    return false;
                } 
            }
        }
		$userObj = new UserSetting(UserAuthentication::getLoggedUserId());
        $isFreeTrial['us_is_trial_lesson_enabled'] = isset($post['us_is_trial_lesson_enabled'])?$post['us_is_trial_lesson_enabled']:0;
		if (!$userObj->saveData($isFreeTrial)) {
			Message::addErrorMessage(Label::getLabel($userObj->getError()));
			FatUtility::dieJsonError(Message::getHtml());
		}
		$this->set('msg', Label::getLabel('MSG_Setup_successful'));	
		$this->_template->render(false, false, 'json-success.php');
	}
	
	public function teacherLanguagesForm() {
		$frm = $this->getTeacherLanguagesForm();
		$this->set('frm', $frm);		
		$this->_template->render(false, false);
	}
	
	private function getTeacherLanguagesForm() {
		$frm = new Form('frmTeacherLanguages');
		$userId = UserAuthentication::getLoggedUserId();
		$db = FatApp::getDb();
		$srch = new SpokenLanguageSearch($this->siteLangId);
		$srch->addMultiplefields(array('slanguagelang_slanguage_id', 'slanguage_name'));
		$srch->addChecks();
		$rs=$srch->getResultSet();
		$rows = $db->fetchAll($rs);
		/*  
		$userSettingSrch = new UserSettingSearch();
		$userSettingSrch->joinLanguageTable( $this->siteLangId );
		$userSettingSrch->addCondition( 'us_user_id', '=', UserAuthentication::getLoggedUserId() );
		$userSettingSrch->addMultiplefields(array('slanguage_name','us_teach_slanguage_id'));
		$userSettingRs=$userSettingSrch->getResultSet();
		$teacherTeachLangArr = $db->fetch($userSettingRs);
		  */
        
        /* [ For Multiple Teaching Languages */
		/*$srch = new SpokenLanguageSearch( $this->siteLangId );
        $srch->addChecks();
        $rs    = $srch->getResultSet();        
        $languages = FatApp::getDb()->fetchAll( $rs, 'slanguage_id' ); */
		
		
		/***** Get Teaching Languages ******/
		$tlSrch = new TeachingLanguageSearch($this->siteLangId);
		$tlSrch->addMultiplefields(array('tlanguagelang_tlanguage_id', 'tlanguage_name'));
		$tlRs=$tlSrch->getResultSet();
        /* [ For Multiple Teaching Languages */
        $tlSrch->addChecks();
        $tlRs = $tlSrch->getResultSet();        
        $teachLanguages = FatApp::getDb()->fetchAll($tlRs, 'tlanguage_id');
		/**********/
        $teacherTeachLangArr = array();
        foreach ($teachLanguages as $key => $language) {
            $teacherTeachLangArr[$key] = $language['tlanguage_name'];
        }        
        /* ] */
		$profArr = SpokenLanguage::getProficiencyArr($this->siteLangId);
		$langArr = array();
		foreach ($rows as $row) {
				$langArr[$row['slanguagelang_slanguage_id']] = $row['slanguage_name'];
		}
		$userToTeachLangSrch = new SearchBase('tbl_user_teach_languages');
		$userToTeachLangSrch->addMultiplefields(array('utl_slanguage_id'));
		$userToTeachLangSrch->addCondition('utl_us_user_id', '=', $userId);
		$userToTeachLangRs=$userToTeachLangSrch->getResultSet();
		$userToTeachLangRows = $db->fetchAll($userToTeachLangRs);
		$userToLangSrch = new SearchBase('tbl_user_to_spoken_languages');
		$userToLangSrch->addMultiplefields(array('utsl_slanguage_id','utsl_proficiency'));
		$userToLangSrch->addCondition('utsl_user_id', '=', $userId);
		$userToLangRs=$userToLangSrch->getResultSet();
		$userToLangRows = $db->fetchAll($userToLangRs);
		
		$frm->addHtml('', 'add_more_lang', ''); 
			$userTeachingLang = array();
			foreach($userToTeachLangRows as $userToTeachLangRow) {
				if (isset($teacherTeachLangArr[$userToTeachLangRow['utl_slanguage_id']])) {
					$userTeachingLang[] = $userToTeachLangRow['utl_slanguage_id'];
				}
			}	
        if (empty($userTeachingLang)) {
            $frm->addSelectBox(Label::getLabel('LBL_Language_To_Teach'), 'teach_lang_id[]', $teacherTeachLangArr, array(),array())->requirements()->setRequired();
        }
		foreach ($userToTeachLangRows as $userToTeachLangRow) {
			if (isset($teacherTeachLangArr[$userToTeachLangRow['utl_slanguage_id']])) {
				$fld1 = $frm->addSelectBox(Label::getLabel('LBL_Language_I_Teach'), 'teach_lang_id[]', $teacherTeachLangArr, array($userToTeachLangRow['utl_slanguage_id']), array())->requirements()->setRequired();
				$fld1->developerTags['col'] = 10;
				$fld = $frm->addHtml('', 'add_minus_teach_button', '<label class="field_label -display-block"></label><a class="inline-action teachLang inline-action--minus" onclick="deleteTeachLanguageRow('.$userToTeachLangRow['utl_slanguage_id'].');" href="javascript:void(0);">'.Label::getLabel('LBL_REMOVE').'</a>');
				$fld->developerTags['col'] = 2;  
			}
        }            
        $frm->addHtml('', 'add_more_div_a_tag', '');   
		$userSpokenLang = array();
		foreach($userToLangRows as $userToLangRow) {
			if(isset($langArr[$userToLangRow['utsl_slanguage_id']])) {
				$userSpokenLang[] = $userToLangRow['utsl_slanguage_id'];
			}
		}
			
        if (empty($userSpokenLang)) {            
            $frm->addSelectBox(Label::getLabel('LBL_Language_I_Speak'), 'utsl_slanguage_id[]', $langArr, array(), array('class' => 'utsl_slanguage_id'))->requirements()->setRequired();
            $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utsl_proficiency[]', $profArr, array(), array('class' => 'utsl_proficiency'))->requirements()->setRequired();
		}
		
		foreach ($userToLangRows as $userToLangRow) {
			if (isset($langArr[$userToLangRow['utsl_slanguage_id']])) {
				$fld1 = $frm->addSelectBox(Label::getLabel('LBL_Language_I_Speak'), 'utsl_slanguage_id[]', $langArr, array($userToLangRow['utsl_slanguage_id']), array('class' => 'utsl_slanguage_id'))->requirements()->setRequired();
				$fld1->developerTags['col'] = 5;            
				$fld1 = $frm->addSelectBox(Label::getLabel('LBL_Language_Proficiency'), 'utsl_proficiency[]', $profArr, array($userToLangRow['utsl_proficiency']), array('class' => 'utsl_proficiency'))->requirements()->setRequired();
				$fld1->developerTags['col'] = 5;                        
				$fld = $frm->addHtml('', 'add_minus_button', '<label class="field_label -display-block"></label><a class="inline-action spokenLang inline-action--minus" onclick="deleteLanguageRow('.$userToLangRow['utsl_slanguage_id'].');" href="javascript:void(0);">'.Label::getLabel('LBL_REMOVE').'</a>');
				//$fld->developerTags['col']=2;
			}
		}
		$frm->addSubmitButton('&nbsp;', 'submit', Label::getLabel('LBL_SAVE_CHANGES'));
		$frm->addHtml('', 'add_more_div', '');
		return $frm;
	}
	
	public function deleteLanguageRow($id = 0) {
		$id = FatUtility::int($id);
		$db = FatApp::getDb();
		if (!$db->deleteRecords(UserToLanguage::DB_TBL, array('smt' => 'utsl_user_id = ? and utsl_slanguage_id = ?', 'vals'=> array(UserAuthentication::getLoggedUserId(), $id)))) {
			Message::addErrorMessage(Label::getLabel($db->getError()));
			FatUtility::dieJsonError(Message::getHtml());	
		}
		$this->set('msg', Label::getLabel('MSG_Language_Removed_Successfuly!'));
		$this->_template->render(false, false, 'json-success.php');
	}

	public function deleteTeachLanguageRow($id = 0) {
		$id = FatUtility::int($id);
		$db = FatApp::getDb();
		if (!$db->deleteRecords('tbl_user_teach_languages', array('smt' => 'utl_us_user_id = ? and utl_slanguage_id = ?', 'vals' => array(UserAuthentication::getLoggedUserId(), $id)))) {
			Message::addErrorMessage(Label::getLabel($db->getError()));
			FatUtility::dieJsonError(Message::getHtml());	
		}
		$this->set('msg', Label::getLabel('MSG_Language_Removed_Successfuly!'));
		$this->_template->render(false, false, 'json-success.php');
	}
	
	public function setupTeacherLanguages() {
		$frm = $this->getTeacherLanguagesForm();
		$post = $frm->getFormDataFromArray( FatApp::getPostedData() );
		$db = FatApp::getDb();
		if (false === $post) {
			Message::addErrorMessage(current($frm->getValidationErrors()));
			FatUtility::dieWithError(Message::getHtml());	
		}
		//unset($post['teach_lang_id']);
   		$db->startTransaction();        
        if (!$db->deleteRecords('tbl_user_teach_languages', array('smt' => 'utl_us_user_id = ? ', 'vals' => array(UserAuthentication::getLoggedUserId())))) {
       		$db->rollbackTransaction();        		            
            Message::addErrorMessage(Label::getLabel($db->getError()));
            FatUtility::dieJsonError(Message::getHtml());	
        }        
		foreach ($post['teach_lang_id'] as $tlang) {
			$insertArr = array('utl_slanguage_id' => $tlang, 'utl_us_user_id' => UserAuthentication::getLoggedUserId());
			if (!$db->insertFromArray('tbl_user_teach_languages', $insertArr, false, array(), $insertArr)) {
           		$db->rollbackTransaction();        		                
				Message::addErrorMessage(Label::getLabel($db->getError()));
				FatUtility::dieWithError(Message::getHtml());	
			}			
		}      
		$i = 0;
		foreach ($post['utsl_slanguage_id'] as $lang) {
			$insertArr = array('utsl_slanguage_id' => $lang, 'utsl_proficiency' => $post['utsl_proficiency'][$i],'utsl_user_id' => UserAuthentication::getLoggedUserId());
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
	
	public function teacherQualificationForm($uqualification_id = 0) {
		$uqualification_id =  FatUtility::int($uqualification_id);
		$experienceFrm = $this->getTeacherQualificationForm();
		if ( $uqualification_id > 0) {
			$srch = new UserQualificationSearch();
			$srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
			$srch->addCondition('uqualification_id', '=', $uqualification_id);
			$rs = $srch->getResultSet();
			$row = FatApp::getDb()->fetch($rs);
			$experienceFrm->fill($row);
		} 
		$this->set('experienceFrm', $experienceFrm);
		$this->_template->render(false, false);
	}
	
	public function setUpTeacherQualification() {
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
			$res = $fileHandlerObj->saveDoc($_FILES['certificate']['tmp_name'], AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $post['uqualification_user_id'], $uqualification_id, $_FILES['certificate']['name'], -1, $unique_record = true);
			if (!$res) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($fileHandlerObj->getError());
			}
		}
		$db->commitTransaction();		
		$this->set('msg', Label::getLabel('MSG_Qualification_Setup_Successful'));
		$this->_template->render(false, false, 'json-success.php');
	}
	
	public function deleteTeacherQualification($uqualification_id = 0) {
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
	
	public function teacherQualification() {
		$srch = new UserQualificationSearch();
		$srch->addMultiplefields(array('uqualification_id', 'afile_name', 'uqualification_title', 'uqualification_institute_name', 'uqualification_institute_address', 'uqualification_description','uqualification_start_year', 'uqualification_end_year'));
		$srch->joinTable(AttachedFile::DB_TBL, 'Left Outer Join', 'uqualification_id=afile_record_subid');
		$srch->addCondition('uqualification_user_id', '=', UserAuthentication::getLoggedUserId());
		$srch->addCondition('uqualification_active', '=', 1);
		$rs = $srch->getResultSet();
		$qualificationData = FatApp::getDb()->fetchAll($rs);	
		$this->set('qualificationData', $qualificationData);
		$this->_template->render(false, false);
	}
	
	public function teacherPreferencesForm() {
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
        $userSrchObj = new UserSearch();
        $tLangsrch = $userSrchObj->getMyTeachLangQry();
        $tLangsrch->addCondition('utl_us_user_id', '=', UserAuthentication::getLoggedUserId());
		$rs = $tLangsrch->getResultSet();
		$tLangs = FatApp::getDb()->fetch($rs);
        $teacherTeachLang = CommonHelper::getTeachLangs($tLangs['utl_slanguage_ids']);
		$arrOptions = array();
		foreach ($teacherPrefArr as $val) {
			$arrOptions['pref_'.$val['preference_type']][] = $val['utpref_preference_id'];
		}
		$frm->fill($arrOptions);
		$this->set('teachLang', $teacherTeachLang);
		$this->set('teacherPreferencesFrm', $frm);
		$this->_template->render(false, false);
	}
	
	public function setupTeacherPreferences() {
		$frm = $this->getTeacherPreferencesForm();
		$post = $frm->getFormDataFromArray(FatApp::getPostedData());
		$db = FatApp::getDb();
		if (false === $post) {
			FatUtility::dieWithError(current($frm->getValidationErrors()));	
		}
		$titleArr = Preference::getPreferenceTypeArr($this->siteLangId);
		$deleteRecords = $db->deleteRecords(Preference::DB_TBL_USER_PREF, array('smt' => 'utpref_user_id = ?','vals' => array(UserAuthentication::getLoggedUserId())));
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
	
	private function getTeacherPreferencesForm() {
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
		$frm->addTextArea(Label::getLabel("LBL_Language_that_I'm_teaching"),'teach_lang','',array('disabled'=>'disabled'));
		foreach ($preferencesArr as  $key => $val) {
			if ($key == Preference::TYPE_ACCENTS && $teacherTeachLangArr['slanguage_code'] != "EN") {
				//continue;
			}
			
			$optionsArr = array();
			foreach ($val as $innerVal) {
				$optionsArr[$innerVal['preference_id']] = $innerVal['preference_title'];
			}
			if (isset( $titleArr[$key])) {
				$frm->addCheckBoxes($titleArr[$key], 'pref_'.$key, $optionsArr, '', array('class' => 'list-onethird list-onethird--bg') );
			}
		}
		$frm->addSubmitButton('', 'submit', 'Save Changes');
		return $frm;
	}
	
	public function teacherGeneralAvailability() {
		$cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
		/* $userData = User::getAttributesById( UserAuthentication::getLoggedUserId(), array('user_timezone') );
		$this->set('userData',$userData); */
		$this->set('cssClassArr', $cssClassNamesArr);
		$this->_template->render(false, false);
	}
	
	public function teacherWeeklySchedule() {
		$cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
		/* $userData = User::getAttributesById( UserAuthentication::getLoggedUserId(), array('user_timezone'));
		$this->set('userData',$userData); */
		$this->set('cssClassArr', $cssClassNamesArr);
		$this->_template->render(false, false);
	}
	
	public function getTeacherGeneralAvailabilityJsonData() {
		$userId = UserAuthentication::getLoggedUserId();
		$post = FatApp::getPostedData();
		$jsonArr = TeacherGeneralAvailability::getGenaralAvailabilityJsonArr($userId, $post);
		echo FatUtility::convertToJson($jsonArr);
	}
	
	public function getTeacherGeneralAvailabilityJsonDataForWeekly() {
		$userId = UserAuthentication::getLoggedUserId();
		$post = FatApp::getPostedData();
		$jsonArr = TeacherGeneralAvailability::getGenaralAvailabilityJsonArr($userId, $post);
		echo FatUtility::convertToJson($jsonArr);
	}
	
	public function getTeacherWeeklyScheduleJsonData() {
		$userId = UserAuthentication::getLoggedUserId();
		$post = FatApp::getPostedData();
		if (false === $post) {
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$weeklySchRows = TeacherWeeklySchedule::getWeeklyScheduleJsonArr($userId, $post['start'], $post['end']);
		$_serchEndDate = date('Y-m-d 00:00:00', strtotime($post['end']));
		$cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
		$jsonArr = array();
		if (!empty($weeklySchRows)) {
			$user_timezone = MyDate::getUserTimeZone($userId);
			foreach ($weeklySchRows as $row) {
				$twsch_end_time = MyDate::format(date('Y-m-d H:i:s', strtotime($row['twsch_end_date'].' '. $row['twsch_end_time'])), true, true, $user_timezone);
				
				$twsch_start_time = MyDate::format(date('Y-m-d H:i:s', strtotime($row['twsch_date']. ' ' . $row['twsch_start_time'])), true, true, $user_timezone);
				
				$startDate = date('Y-m-d', strtotime($twsch_start_time));
				$endDate = date('Y-m-d', strtotime($twsch_end_time));
				
				if ((strtotime($twsch_start_time) >=  strtotime($post['start'] .' 00:00:00 ')) && (strtotime($twsch_end_time) <= strtotime($_serchEndDate))) {
					$jsonArr[] = array(
						"title" => "",
						"date" => date('Y-m-d', strtotime($twsch_start_time)),
						"start" => $twsch_start_time,
						"end" => $twsch_end_time,
						'_id' => $row['twsch_id'],
						'classType' => $row['twsch_is_available'],
						'className' => $cssClassNamesArr[$row['twsch_is_available']] 
					);
				}
			}
		}
		echo FatUtility::convertToJson($jsonArr);
	}
	
	public function deleteTeacherGeneralAvailability($tgavl_id = 0) {
		$tgavl_id = FatUtility::int($tgavl_id);
		$tGAvail = new TeacherGeneralAvailability();
		if (!$tGAvail->deleteTeacherGeneralAvailability($tgavl_id, UserAuthentication::getLoggedUserId())) {
			FatUtility::dieWithError($tGAvail->getError());
		}
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Availability_deleted_successfully!'));
	}
	
	public function deleteTeacherWeeklySchedule() {
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
	 
	public function setupTeacherGeneralAvailability() {
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
	
	public function setupTeacherWeeklySchedule() {
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
	
	public function qualificationFile($userId = 0, $subRecordId=0, $sizeType = '', $cropedImage = false) {
		$userId = UserAuthentication::getLoggedUserId();
		$recordId = FatUtility::int($userId);
		$file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_QUALIFICATION_FILE, $recordId, $subRecordId);
		$image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
		switch(strtoupper($sizeType)) {
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
	
	public function paypalEmailAddressForm() {
		$frm = $this->getPaypalEmailAddressForm();
		$userObj = new User(UserAuthentication::getLoggedUserId());			
		$data = $userObj->getUserPaypalInfo();		
		$frm->fill($data);
		$this->set('frm', $frm);
		$this->_template->render(false, false);
	}
	
	public function setUpPaypalInfo() {
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
	
	private function getPaypalEmailAddressForm() {
		$frm = new Form('frmBankInfo');
		$frm->addEmailField(Label::getLabel('M_Paypal_Email_Address'), 'ub_paypal_email_address');
		$frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
		return $frm;
	}
	
	public function bankInfoForm() {
		$frm = $this->getBankInfoForm();
		$userObj = new User(UserAuthentication::getLoggedUserId());			
		$data = $userObj->getUserBankInfo();		
		$frm->fill($data);
		$this->set('frm', $frm);		
		$this->_template->render(false, false);
	}
	
	private function getBankInfoForm() {
		$frm = new Form('frmBankInfo');
		$frm->addRequiredField(Label::getLabel('M_Bank_Name'), 'ub_bank_name', '');
		$frm->addRequiredField(Label::getLabel('M_Beneficiary/Account_Holder_Name'), 'ub_account_holder_name', '');
		$frm->addRequiredField(Label::getLabel('M_Bank_Account_Number'), 'ub_account_number', '');
		$frm->addRequiredField(Label::getLabel('M_IFSC_Code/Swift_Code'), 'ub_ifsc_swift_code', '');
		$frm->addTextArea(Label::getLabel('M_Bank_Address'), 'ub_bank_address', '');
		$frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
		return $frm;
	}
	
	public function setUpBankInfo() {
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
	
	public function message($userId = 0) {
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

	public function orders() {
        $frmOrderSrch = $this->getOrderSearchForm($this->siteLangId);
        $this->set('frmOrderSrch', $frmOrderSrch);		
		$this->_template->render();	
	}
	
    private function getOrderSearchForm($langId) {
        $frm = new Form('frmOrderSrch');
        $frm->addTextBox('Keyword', 'keyword', '', array('placeholder' => Label::getLabel('LBL_Keyword', $langId)));
        $frm->addSelectBox('Status', 'status', array(-2 => Label::getLabel('LBL_Does_Not_Matter', $langId)) + Order::getPaymentStatusArr($langId), '', array('placeholder' => 'Select Status'), '');
        $frm->addDateField(Label::getLabel('LBL_Date_From', $langId), 'date_from', '', array('placeholder' => '', 'readonly' => 'readonly'));
        $frm->addDateField(Label::getLabel('LBL_Date_To', $langId), 'date_to', '', array('placeholder' => '', 'readonly' => 'readonly'));
		$fld_submit = $frm->addSubmitButton('', 'btn_submit', 'Submit', array('class' => 'btn btn--primary'));
        $fld_cancel = $frm->addResetButton("", "btn_clear", "Clear", array('onclick' => 'clearSearch();', 'class' =>'btn--clear'));
		$fld_submit->attachField($fld_cancel);				
        $frm->addHiddenField('', 'page', 1);
        return $frm;
    }	

	public function getOrders() {
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