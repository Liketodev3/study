<?php
class LearnerController extends LearnerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        if (true != User::isLearnerProfileCompleted()) {
            Message::addInfo(Label::getLabel('LBL_Please_Update_Your_Timezone'));
            FatApp::redirectUser(CommonHelper::generateUrl('account', 'profileInfo'));
        }

        /* $token = current(UserSetting::getUserSettings(UserAuthentication::getLoggedUserId()))['us_google_access_token'];
        if(!$token || SocialMedia::isGoogleAccessTokenExpired($token)){
            $link = " <a href='".CommonHelper::generateUrl('account', 'profileInfo')."'>".Label::getLabel('LBL_Click_Here')."</a>";
            Message::addInfo(sprintf(Label::getLabel('LBL_Please_Authenticate_google_to_be_able_to_post_on_google_calendar_%s'), $link));
        } */

        $this->_template->addCss('css/custom-full-calendar.css');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addCss('css/fullcalendar.min.css');
        $this->_template->addJs('js/jquery.countdownTimer.min.js');
        $this->_template->addCss('css/jquery.countdownTimer.css');
        if($currentLangCode = strtolower(Language::getLangCode($this->siteLangId))){
            if(file_exists(CONF_THEME_PATH."js/locales/$currentLangCode.js")){
                $this->_template->addJs("js/locales/$currentLangCode.js");
            }
        }
        $frmSrch = $this->getSearchForm();
        $frmSrch->fill(['status'=>ScheduledLesson::STATUS_UPCOMING, 'show_group_classes'=>ApplicationConstants::YES]);
        $this->set('frmSrch', $frmSrch);
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $userDetails = $userObj->getDashboardData(CommonHelper::getLangId());
        $this->set('userDetails', $userDetails);
        $this->_template->render();
    }
    public function message($userId = 0)
    {
        $userId = FatUtility::int($userId);
        $teacherObj = new User($userId);
        $teacherDetails = $teacherObj->getUserInfo(null, true, true);
        if (!$teacherDetails || $userId == UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $userDetails = $userObj->getUserInfo(null, true, true);
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
        $frm->addSelectBox('Status', 'status', array(-2 => Label::getLabel('LBL_Does_Not_Matter', $langId)) + Order::getPaymentStatusArr($langId), '', [], '');
        $frm->addDateField(Label::getLabel('LBL_Date_From', $langId), 'date_from', '', array('readonly' => 'readonly'));
        $frm->addDateField(Label::getLabel('LBL_Date_To', $langId), 'date_to', '', array('readonly' => 'readonly'));
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Submit', $langId), array('class' => 'btn btn--primary'));
        $fld_cancel = $frm->addResetButton("", "btn_clear", Label::getLabel('LBL_Clear', $langId), array('onclick' => 'clearSearch();', 'class' =>'btn--clear'));
        $frm->addHiddenField('', 'page', 1);
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function getOrders()
    {
        $frm = $this->getOrderSearchForm($this->siteLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $ordersData = Order::getOrders($post, User::USER_TYPE_LEANER, UserAuthentication::getLoggedUserId());
        $statusArr = Order::getPaymentStatusArr($this->siteLangId);
        $this->set('statusArr', $statusArr);
        $this->set('ordersData', $ordersData);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    //public function myTeacher($teacherId){
    public function myTeacher($user_name)
    {
        $srchTeacher = new UserSearch();
        $srchTeacher->addMultipleFields(array(
            'user_id'
        ));
        $srchTeacher->addCondition('user_url_name', '=', $user_name);
        $srchTeacher->setPageSize(1);
        $rsTeacher = $srchTeacher->getResultSet();
        $teacherData = FatApp::getDb()->fetch($rsTeacher);
        if (empty($teacherData)) {
            FatUtility::exitWithErrorCode(404);
        }
        $teacherId = $teacherData['user_id'];
        $teacherId = FatUtility::int($teacherId);
        $srch = new UserSearch();
        $srch->setTeacherDefinedCriteria();
        $srch->joinUserLang($this->siteLangId);
        $srch->joinUserSpokenLanguages($this->siteLangId);
        $srch->joinUserTeachLanguage($this->siteLangId);
        $srch->joinUserCountry($this->siteLangId);
        $srch->joinUserState($this->siteLangId);
        $srch->joinFavouriteTeachers(UserAuthentication::getLoggedUserId());
        $srch->joinTeacherLessonData();
        $srch->joinRatingReview();
        $srch->setPageSize(1);
        $srch->addCondition('user_id', '=', $teacherId);
        $srch->addMultipleFields(array(
            'user_id',
            'user_url_name',
            'user_first_name',
            'user_last_name',
            'CONCAT(user_first_name," ", user_last_name) as user_full_name',
            'user_country_id',
            'IFNULL(userlang_user_profile_Info, user_profile_info) as user_profile_info',
            'user_timezone',
            'IFNULL(uft_id, 0) as uft_id',
            'IFNULL(country_name, country_code) as user_country_name',
            'IFNULL(state_name, state_identifier) as user_state_name',
            'IFNULL(slanguage_name, slanguage_identifier) as teachlanguage_name',
            'utsl.spoken_language_names',
            'utsl.spoken_languages_proficiency',
            'us_video_link',
            'us_is_trial_lesson_enabled',
            'utl_slanguage_ids'
        ));
        $rs = $srch->getResultSet();
        $teacher = FatApp::getDb()->fetch($rs);
        if (empty($teacher)) {
            FatUtility::exitWithErrorCode(404);
        }
        $proficiencyArr = SpokenLanguage::getProficiencyArr( CommonHelper::getLangId() );
        $teacher['proficiencyArr'] = $proficiencyArr;
        $this->set('teacher', $teacher);
        $this->_template->render();
    }

    public function toggleTeacherFavorite()
    {
        $post = FatApp::getPostedData();
        $teacherId = FatUtility::int($post['teacher_id']);
        $loggedUserId = UserAuthentication::getLoggedUserId();
        $db = FatApp::getDb();
        $srch = new UserSearch();
        $srch->setTeacherDefinedCriteria();
        $srch->joinUserSpokenLanguages($this->siteLangId);
        $srch->joinUserTeachLanguage($this->siteLangId);
        $srch->joinUserCountry($this->siteLangId);
        $srch->joinUserState($this->siteLangId);
        $srch->setPageSize(1);
        $srch->addCondition('user_id', '=', $teacherId);
        $srch->addMultipleFields(array(
            'user_id',
            'user_first_name',
            'user_last_name',
        ));

        $rs = $srch->getResultSet();
        $teacher = FatApp::getDb()->fetch($rs);
        if (empty($teacher)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $action = 'N'; //nothing happened
        $srch = new UserFavoriteTeacherSearch();
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('uft_user_id', '=', $loggedUserId);
        $srch->addCondition('uft_teacher_id', '=', $teacherId);
        $rs = $srch->getResultSet();
        if (!$row = $db->fetch($rs)) {
            $userObj = new User($loggedUserId);
            if (!$userObj->addUpdateUserFavoriteTeacher($teacherId)) {
                Message::addErrorMessage(Label::getLabel('LBL_Some_problem_occurred,_Please_contact_webmaster', $this->siteLangId));
                FatUtility::dieWithError(Message::getHtml());
            }
            $action = 'A'; //Added to favorite
            $this->set('msg', Label::getLabel('LBL_Teacher_has_been_marked_as_favourite_successfully', $this->siteLangId) );
            //Message::addMessage(Label::getLabel('LBL_Teacher_has_been_marked_as_favourite_successfully', $this->siteLangId));
        } else {
            if (!$db->deleteRecords(User::DB_TBL_TEACHER_FAVORITE, array('smt'=>'uft_user_id = ? AND uft_teacher_id = ?', 'vals'=> array($loggedUserId, $teacherId)))) {
                Message::addErrorMessage(Label::getLabel('LBL_Some_problem_occurred,_Please_contact_webmaster', $this->siteLangId));
                FatUtility::dieWithError(Message::getHtml());
            }
            $action = 'R'; //Removed from favorite
            $this->set('msg', Label::getLabel('LBL_Teacher_has_been_removed_from_favourite_list', $this->siteLangId) );
            //Message::addMessage(Label::getLabel('LBL_Teacher_has_been_removed_from_favourite_list', $this->siteLangId));
        }
        $this->set('action', $action);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function favourites()
    {
        $frmFavSrch = $this->getFavouriteSearchForm($this->siteLangId);
        $this->set('frmFavSrch', $frmFavSrch);
        $this->_template->render();
    }

    private function getFavouriteSearchForm($langId)
    {
        $frm = new Form('frmFavSrch');
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $langId), 'keyword', '', array('placeholder' => Label::getLabel('LBL_Keyword', $langId)));
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Submit', $langId), array('class' => 'btn btn--primary'));
        $fld_cancel = $frm->addResetButton('', "btn_clear", Label::getLabel('LBL_Clear', $langId), array('onclick' => 'clearSearch();', 'class' =>'btn--clear'));
        $fld_submit->attachField($fld_cancel);
        $frm->addHiddenField('', 'page', 1);
        return $frm;
    }

    public function getFavourites()
    {
        $frm = $this->getFavouriteSearchForm($this->siteLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $userObj = new User(UserAuthentication::getLoggedUserId());
        $favouritesData = $userObj->getFavourites($post, $this->siteLangId);
        $countryObj = new Country();
        $countriesArr = $countryObj->getCountriesArr($this->siteLangId);
        $this->set('countriesArr', $countriesArr);
        $this->set('favouritesData', $favouritesData);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }
}
