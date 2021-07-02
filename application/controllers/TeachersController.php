<?php

class TeachersController extends MyAppController
{

    public function index($teachLangId = 0)
    {
        if (empty($teachLangId)) {
            $teachLangId = FatApp::getPostedData('teachLangId', FatUtility::VAR_INT, 0);
        }

        $frmSrch = $this->getTeacherSearchForm($teachLangId);
        $this->set('frmTeacherSrch', $frmSrch);
        $daysArr = applicationConstants::getWeekDays();
        $timeSlotArr = TeacherGeneralAvailability::timeSlotArr();
        $this->set('daysArr', $daysArr);
        $this->set('timeSlotArr', $timeSlotArr);
        $this->set('siteLangId', CommonHelper::getLangId());
        $this->_template->addJs('js/enscroll-0.6.2.min.js');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addJs('js/fateventcalendar.js');
        $this->_template->addJs('js/ion.rangeSlider.js');
        if ($currentLangCode = strtolower(Language::getLangCode($this->siteLangId))) {
            if (file_exists(CONF_THEME_PATH . "js/locales/$currentLangCode.js")) {
                $this->_template->addJs("js/locales/$currentLangCode.js");
            }
        }
        $this->_template->render();
    }

    public function teachersList()
    {
        $post = FatApp::getPostedData();
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $pageSize = FatApp::getPostedData('pageSize', FatUtility::VAR_INT, 12);
        $sortOrder = FatApp::getPostedData('sortOrder', FatUtility::VAR_STRING, '');
        $userId = UserAuthentication::isUserLogged() ? UserAuthentication::getLoggedUserId() : 0;
        $langId = CommonHelper::getLangId();
        $_SESSION['search_filters'] = $post;
        $srch = new TeacherSearch($langId);
        $srch->addSearchListingFields();
        $srch->applyPrimaryConditions($userId);
        $srch->applySearchConditions($post);
        $srch->applyOrderBy($sortOrder);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $rawData = FatApp::getDb()->fetchAll($srch->getResultSet());
        $records = $srch->formatTeacherSearchData($rawData, $userId);
        $recordCount = $srch->getRecordCount();
        $startRecord = ($recordCount > 0) ? (($page - 1) * $pageSize + 1) : 0;
        $endRecord = ($recordCount < $page * $pageSize) ? $recordCount : $page * $pageSize;
        $recordCountTxt = ($recordCount > SEARCH_MAX_COUNT) ? $recordCount . '+' : $recordCount;
        $showing = 'Showing ' . $startRecord . ' - ' . $endRecord . ' Of ' . $recordCountTxt . ' ' . Label::getLabel('lbl_teachers');
        $this->set('showing', $showing);
        $this->set('teachers', $records);
        $this->set('postedData', $post);
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('recordCount', $recordCount);
        $this->set('pageCount', ceil($recordCount / $pageSize));
        $this->set('slots', TeacherGeneralAvailability::timeSlotArr());
        $this->_template->render(false, false);
    }

    public function spokenLanguagesAutoCompleteJson()
    {

        $srch = new SpokenLanguageSearch($this->siteLangId);
        $srch->addChecks();

        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        if (!empty($keyword)) {
            $cnd = $srch->addCondition('slanguage_identifier', 'like', '%' . $keyword . '%');
            $cnd->attachCondition('slanguage_name', 'like', '%' . $keyword . '%', 'OR');
        }

        $languages = FatApp::getDb()->fetchAll($srch->getResultSet(), 'slanguage_id');
        $json = array();
        foreach ($languages as $key => $language) {

            $json[] = ['id' => $key, 'name' => $language['slanguage_name'],];
        }
        die(json_encode($json));
    }

    public function teachLanguagesAutoCompleteJson()
    {

        $srch = new TeachingLanguageSearch($this->siteLangId);
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        $srch->addOrder('tlanguage_display_order');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addMultipleFields(['tlanguage_id', 'IFNULL(tlanguage_name,tlanguage_identifier)as tlanguage_name']);
        if (!empty($keyword)) {
            $conditaion = $srch->addCondition('tlanguage_name', 'like', '%' . $keyword . '%');
            $conditaion->attachCondition('tlanguage_identifier', 'like', '%' . $keyword . '%');
        }
        $rs = $srch->getResultSet();
        $languages = FatApp::getDb()->fetchAll($rs, 'tlanguage_id');
        $json = [];
        foreach ($languages as $key => $language) {

            $json[] = ['id' => $key, 'name' => $language['tlanguage_name'],];
        }
        die(json_encode($json));
    }

    public function view($userName)
    {
        $srchTeacher = new UserSearch();
        $srchTeacher->addMultipleFields(['user_id']);
        $srchTeacher->addCondition('user_url_name', '=', $userName);
        $srchTeacher->setPageSize(1);
        $rsTeacher = $srchTeacher->getResultSet();
        $teacherData = FatApp::getDb()->fetch($rsTeacher);
        if (empty($teacherData)) {
            FatUtility::exitWithErrorCode(404);
        }
        $teacherId = $teacherData['user_id'];
        $teacherId = FatUtility::int($teacherId);
        /* preferences/skills[ */
        $prefSrch = new UserToPreferenceSearch();
        $prefSrch->joinToPreference($this->siteLangId);
        $prefSrch->addCondition('utpref_user_id', '=', $teacherId);
        $prefSrch->addOrder('preference_type');
        $prefSrch->addGroupBy('preference_type');
        $prefSrch->addMultipleFields(['preference_type', 'GROUP_CONCAT(IFNULL(preference_title, preference_identifier)) as preference_titles']);
        $prefRs = $prefSrch->getResultSet();
        $teacherPreferences = FatApp::getDb()->fetchAll($prefRs);
        $loggedUserId = UserAuthentication::getLoggedUserId(true);
        /* ] */
        $srch = new UserSearch();
        $srch->doNotCalculateRecords();
        $srch->setTeacherDefinedCriteria(true);
        $srch->joinUserLang($this->siteLangId);
        $srch->joinTeacherLessonData();
        $srch->joinUserSpokenLanguages($this->siteLangId);
        $srch->joinUserCountry($this->siteLangId);
        $srch->joinUserState($this->siteLangId);
        if (UserAuthentication::isUserLogged()) {
            $srch->joinFavouriteTeachers($loggedUserId);
            $srch->addFld('uft_id');
        } else {
            $srch->addFld('0 as uft_id');
        }
        $srch->setPageSize(1);
        $srch->addCondition('user_id', '=', $teacherId);
        $srch->addMultipleFields([
            'user_id',
            'user_first_name',
            'user_last_name',
            'CONCAT(user_first_name," ", user_last_name) as user_full_name',
            'user_country_id',
            'IFNULL(country_name, country_code) as user_country_name',
            'IFNULL(state_name, state_identifier) as user_state_name',
            // 'IFNULL(tlanguage_name, tlanguage_identifier) as teachlanguage_name',						   
            'us_video_link',
            'us_is_trial_lesson_enabled',
            'minPrice',
            'maxPrice',
            'minSlab',
            'maxSlab',
            'slot',
            'IFNULL(userlang_user_profile_Info, user_profile_info) as user_profile_info',
            'utl_tlanguage_ids',
            'ustelgpr_slots'
        ]);

        $rs = $srch->getResultSet();
        $teacher = FatApp::getDb()->fetch($rs);
        if (empty($teacher)) {
            FatUtility::exitWithErrorCode(404);
        }
        /* [ */
        $freeTrialConfiguration = FatApp::getConfig('CONF_ENABLE_FREE_TRIAL', FatUtility::VAR_INT, 0);
        $teacher['isFreeTrialEnabled'] = ($freeTrialConfiguration == applicationConstants::YES && $teacher['us_is_trial_lesson_enabled'] == applicationConstants::YES);
        /* ] */
        /* Languages and prices [ */
        $userTeachLanguage = new UserTeachLanguage($teacherId);
        $getUserTeachLanguages = $userTeachLanguage->getUserTeachlanguages($this->siteLangId, true);
        $getUserTeachLanguages->doNotCalculateRecords();
        $getUserTeachLanguages->addMultipleFields([
            'IFNULL(tlanguage_name, tlanguage_identifier) as teachLangName',
            'utl_id',
            'utl_tlanguage_id',
            'ustelgpr_slot',
            'ustelgpr_price',
            'ustelgpr_min_slab',
            'ustelgpr_max_slab',
            'ustelgpr_price'
        ]);
        if (UserAuthentication::isUserLogged()) {
            $getUserTeachLanguages->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top.top_teacher_id = utl.utl_user_id and top.top_learner_id = ' . $loggedUserId . ' and top.top_lesson_duration = ustelgpr.ustelgpr_slot', 'top');
            $getUserTeachLanguages->addMultipleFields([
                'IFNULL(top_percentage,0) as top_percentage',
                // 'top_lesson_duration'
            ]);
        } else {
            $getUserTeachLanguages->addFld('0 as top_percentage');
        }
        $getUserTeachLanguages->addCondition('ustelgpr_price', '>', 0);
        $getUserTeachLanguages->addCondition('ustelgpr_min_slab', '>', 0);
        $userTeachlanguages = FatApp::getDb()->fetchAll($getUserTeachLanguages->getResultSet());
        // prx($userTeachlanguages);
        $tlangArr = array_column($userTeachlanguages, 'teachLangName', 'utl_tlanguage_id');
        $this->set('userTeachLangs', $userTeachlanguages);
        $teacher['teachLanguages'] = $tlangArr;
        /* ] */
        $teacher['isAlreadyPurchasedFreeTrial'] = false;
        if (UserAuthentication::isUserLogged()) {
            $teacher['isAlreadyPurchasedFreeTrial'] = OrderProduct::isAlreadyPurchasedFreeTrial($loggedUserId, $teacherId);
        }

        $teacherLessonReviewObj = new TeacherLessonReviewSearch();
        $teacherLessonReviewObj->joinTeacher();
        $teacherLessonReviewObj->joinLearner();
        $teacherLessonReviewObj->joinTeacherLessonRating();
        $teacherLessonReviewObj->joinScheduledLesson();
        $teacherLessonReviewObj->joinScheduleLessonDetails();
        $teacherLessonReviewObj->doNotCalculateRecords();
        $teacherLessonReviewObj->doNotLimitRecords();
        $teacherLessonReviewObj->addCondition('tlr.tlreview_status', '=', TeacherLessonReview::STATUS_APPROVED);
        $teacherLessonReviewObj->addCondition('tlreview_teacher_user_id', '=', $teacherId);
        $teacherLessonReviewObj->addMultipleFields(["ROUND(AVG(tlrating_rating),2) as prod_rating", "count(DISTINCT tlreview_id) as totReviews"]);
        $teacherLessonReviewObj->addMultipleFields(['COUNT(DISTINCT tlreview_postedby_user_id) as totStudents']);
        $reviews = FatApp::getDb()->fetch($teacherLessonReviewObj->getResultSet());
        $this->set('reviews', $reviews);

        $grpClassSrch = TeacherGroupClassesSearch::getSearchObj($this->siteLangId);
        $grpClassSrch->addCondition('grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
        $grpClassSrch->addCondition('grpcls_teacher_id', '=', $teacherId);
        $grpClassSrch->addCondition('grpcls_start_datetime', '>', date('Y-m-d H:i:s'));
        $grpClassSrch->setPageSize(5);
        $grpClassSrch->addOrder('grpcls_start_datetime', 'Asc');
        $rs = $grpClassSrch->getResultSet();
        $classesList = FatApp::getDb()->fetchAll($rs);
        $this->set('groupClasses', $classesList);
        $frmReviewSearch = $this->getTeacherReviewSearchForm(FatApp::getConfig('CONF_FRONTEND_PAGESIZE'));
        $frmReviewSearch->fill(['tlreview_teacher_user_id' => $teacherId]);
        $this->set('frmReviewSearch', $frmReviewSearch);
        $teacher['proficiencyArr'] = SpokenLanguage::getProficiencyArr(CommonHelper::getLangId());
        $teacher['preferences'] = $teacherPreferences;
        $this->set('preferencesTypeArr', Preference::getPreferenceTypeArr());
        $this->set('teacher', $teacher);
        $this->set('loggedUserId', UserAuthentication::getLoggedUserId(true));
        $this->set('sortArr', TeacherLessonReview::getReviewSortArr($this->siteLangId));
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addJs('js/fateventcalendar.js');
        if ($currentLangCode = strtolower(Language::getLangCode($this->siteLangId))) {
            if (file_exists(CONF_THEME_PATH . "js/locales/$currentLangCode.js")) {
                $this->_template->addJs("js/locales/$currentLangCode.js");
            }
        }
        $this->_template->addJs('js/enscroll-0.6.2.min.js');
        $this->_template->render();
    }

    private function getTeacherReviewSearchForm($pageSize = 10)
    {
        $frm = new Form('frmReviewSearch');
        $frm->addHiddenField('', 'tlreview_teacher_user_id');
        $frm->addHiddenField('', 'teach_lang_name');
        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'pageSize', $pageSize);
        return $frm;
    }

    public function getTeacherReviews()
    {

        $teacherId = FatApp::getPostedData('tlreview_teacher_user_id');
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $orderBy = FatApp::getPostedData('orderBy', FatUtility::VAR_STRING, 'most_recent');
        $page = ($page) ? $page : 1;
        $pageSize = 5;
        $srch = new TeacherLessonReviewSearch();
        // $srch->joinTeacher();
        $srch->joinLearner();
        $srch->joinTeacherLessonRating();
        // $srch->joinScheduledLesson();
        // $srch->joinScheduleLessonDetails();
        // $srch->joinLessonLanguage(CommonHelper::getLangId());
        // $srch->addCondition('tlr.tlreview_teacher_user_id', '=', $teacherId);
        $srch->addCondition('tlr.tlreview_status', '=', TeacherLessonReview::STATUS_APPROVED);
        // $srch->addMultipleFields([
        //     'tlreview_id', "ROUND(AVG(tlrating_rating),2) as prod_rating",
        //     'tlreview_title',
        //     'tlreview_description',
        //     'tlreview_posted_on',
        //     'tlreview_postedby_user_id',
        //     'ul.user_first_name as lname',
        //     'ut.user_first_name as tname',
        //     '(select count(slesson_id) from  ' . ScheduledLesson::DB_TBL . ' where slesson_teacher_id = ut.user_id AND sldetail_learner_id = ul.user_id ) as lessonCount',
        //     'IFNULL(tlanguage_name, tlanguage_identifier) as lessonLanguage'
        // ]);
        // $srch->addGroupBy('tlr.tlreview_id');
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);
        switch ($orderBy) {
            case 'most_helpful':
                $srch->addOrder('helpful', 'desc');
                break;
            case 'date_posted_desc':
                $srch->addOrder('tlr.tlreview_posted_on', 'desc');
                break;
            case 'date_posted_asc':
                $srch->addOrder('tlr.tlreview_posted_on', 'asc');
                break;
            default:
                $srch->addOrder('tlr.tlreview_posted_on', 'desc');
                break;
        }
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        foreach ($records as $key => $record) {
            $records[$key]['img'] = (User::isProfilePicUploaded($record['tlreview_postedby_user_id'])) ? CommonHelper::generateUrl('Image', 'user', array($record['tlreview_postedby_user_id'])) : '';
            $records[$key]['fChar'] = '';
            $records[$key]['tlreview_posted_on'] = FatDate::format($record['tlreview_posted_on']);
            // $records[$key]['lessonCount'] = '(' . $record['lessonCount'] . Label::getLabel('LBL_Lessons', $this->siteLangId) . ')';
            $records[$key]['iconSrc'] = CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating';
            $records[$key]['tlreview_description'] = nl2br($record['tlreview_description']);
        }
        $json['startRecord'] = !empty($records) ? ($page - 1) * $pageSize + 1 : 0;
        $json['msg'] = Label::getLabel('LBL_Request_Processing..');
        $json['records'] = $records;
        $json['displayRecords'] = sprintf(Label::getLabel('LBL_Displaying_Reviews_%d_of_%d', $this->siteLangId), ($page > 1) ? (($page - 1) * $pageSize + count($records)) : count($records), $srch->recordCount());
        $json['loadMoreBtnHtml'] = $this->_template->render(false, false, '_partial/load-more-teacher-reviews-btn.php', true, false);

        FatUtility::dieJsonSuccess($json);
    }

    public function viewCalendar($teacherId = 0, $languageId = 1)
    {
        $teacherId = FatUtility::int($teacherId);
        $languageId = FatUtility::int($languageId);
        $user = new User($teacherId);
        $postedAction = FatApp::getPostedData('action');
        $allowedActionArr = ['free_trial', 'paid'];

        if (!in_array($postedAction, $allowedActionArr)) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }

        if (!$user->loadFromDb()) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $userRow = $user->getFlds();
        $bookingMinutesDuration = FatApp::getConfig('CONF_DEFAULT_PAID_LESSON_DURATION', FatUtility::VAR_INT, 60);

        if ('free_trial' == $postedAction) {
            $bookingMinutesDuration = FatApp::getConfig('conf_trial_lesson_duration', FatUtility::VAR_INT, 30);
            $freeTrialEnable = FatApp::getConfig('CONF_ENABLE_FREE_TRIAL', FatUtility::VAR_INT, 0);
            if ($freeTrialEnable == applicationConstants::NO) {
                FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
            }
        }
        $hour = floor($bookingMinutesDuration / 60);
        $hour = ($hour > 9) ? $hour : '0' . $hour;
        $min = ($bookingMinutesDuration - floor($bookingMinutesDuration / 60) * 60);
        $min = ($min > 9) ? $min : '0' . $min;
        $bookingSnapDuration = $hour . ':' . $min;
        $this->set('bookingMinutesDuration', $bookingMinutesDuration);
        $this->set('bookingSnapDuration', $bookingSnapDuration);
        $user_timezone = MyDate::getUserTimeZone();
        $nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
        $teacherBookingBefore = UserSetting::getUserSettings($teacherId)['us_booking_before'];
        if ('' == $teacherBookingBefore) {
            $teacherBookingBefore = 0;
        }
        $this->set('teacherBookingBefore', $teacherBookingBefore);
        $this->set('user_timezone', $user_timezone);
        $this->set('nowDate', $nowDate);
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $currentLangCode = strtolower(Language::getLangCode($this->siteLangId));
        $this->set('currentLangCode', $currentLangCode);
        $this->set('userRow', $userRow);
        $this->set('action', $postedAction);
        $this->set('teacher_name', $userRow['user_first_name']);
        $this->set('teacher_country_id', $userRow['user_country_id']);
        $this->set('teacher_id', $teacherId);
        $this->set('languageId', $languageId);
        $this->set('cssClassArr', $cssClassNamesArr);
        $this->_template->render(false, false);
    }

    public function checkCalendarTimeSlotAvailability($userId = 0)
    {
        $userId = FatUtility::int($userId);
        $post = FatApp::getPostedData();
        if (false === $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        if ($userId < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $systemTimeZone = MyDate::getTimeZone();
        $userTimezone = MyDate::getUserTimeZone();

        $startDateTime = MyDate::changeDateTimezone($post['start'], $userTimezone, $systemTimeZone);
        $endDateTime = MyDate::changeDateTimezone($post['end'], $userTimezone, $systemTimeZone);

        if (strtotime($startDateTime) < strtotime(date('Y-m-d H:i:s'))) {
            FatUtility::dieJsonSuccess(0);
        }

        if (UserAuthentication::isUserLogged()) {
            $loggedUserId = UserAuthentication::getLoggedUserId();
            $checkGroupClassTiming = TeacherGroupClassesSearch::checkGroupClassTiming([$loggedUserId], $startDateTime, $endDateTime);
            $checkGroupClassTiming->setPageSize(1);
            $checkGroupClassTiming->addCondition('grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
            $getResultSet = $checkGroupClassTiming->getResultSet();
            $scheduledLessonData = FatApp::getDb()->fetch($getResultSet);
            if (!empty($scheduledLessonData)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_YOU_ALREDY_HAVE_A_GROUP_CLASS_BETWEEN_THIS_TIME_RANGE'));
            }
        }

        $tWsch = new TeacherWeeklySchedule();
        $checkAvialSlots = $tWsch->checkCalendarTimeSlotAvailability($userId, $startDateTime, $endDateTime);
        $returnArray = [
            'status' => ($checkAvialSlots) ? applicationConstants::YES : applicationConstants::NO,
        ];
        if (!empty($tWsch->getError())) {
            $returnArray['msg'] = $tWsch->getError();
        }
        FatUtility::dieJsonSuccess($returnArray);
    }

    public function getTeacherGeneralAvailabilityJsonData(int $userId)
    {
        $post = FatApp::getPostedData();
        if ($userId < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $userTimezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $startDate = MyDate::changeDateTimezone($post['start'], $userTimezone, $systemTimeZone);
        $endDate = MyDate::changeDateTimezone($post['end'], $userTimezone, $systemTimeZone);
        $midPoint = (strtotime($startDate) + strtotime($endDate)) / 2;
        $weekRange = CommonHelper::getWeekRangeByDate(date('Y-m-d', $midPoint));
        $jsonArr = TeacherGeneralAvailability::getGenaralAvailabilityJsonArr($userId, ['WeekStart' => $weekRange['start'], 'WeekEnd' => $weekRange['end']]);
        echo FatUtility::convertToJson($jsonArr);
    }

    public function getTeacherScheduledLessonData($userId = 0)
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $weekStartDate = Fatapp::getPostedData('start', FatUtility::VAR_STRING, '');
        $weekEndDate = Fatapp::getPostedData('end', FatUtility::VAR_STRING, '');
        $userTimezone = MyDate::getUserTimeZone();
		$systemTimeZone = MyDate::getTimeZone();

        if (empty($weekStartDate) || empty($weekEndDate)) {
            $weekStartAndEndDate = MyDate::getWeekStartAndEndDate(new DateTime());
            $weekStartDate = $weekStartAndEndDate['weekStart'];
            $weekEndDate = $weekStartAndEndDate['weekEnd'];
        } else {
            $weekStartDate = MyDate::changeDateTimezone($weekStartDate, $userTimezone, $systemTimeZone);
            $weekEndDate = MyDate::changeDateTimezone($weekEndDate, $userTimezone, $systemTimeZone);
        }
        $db = FatApp::getDb();
        $srch = new ScheduledLessonSearch();
        $srch->addGroupBy('slesson_id');
        $srch->joinTeacher();
        $srch->joinTeacherSettings();
        $srch->joinTeacherTeachLanguageView($this->siteLangId);
        $srch->addMultipleFields([
            'slns.slesson_date',
            'slns.slesson_date',
            'slns.slesson_start_time',
            'slns.slesson_end_time',
            'slns.slesson_end_date',
            'slns.slesson_grpcls_id',
        ]);

        $userIds = [];
        $userIds[] = $userId;
        if (UserAuthentication::isUserLogged()) {
            $userIds[] = UserAuthentication::getLoggedUserId();
        }
        $condition = $srch->addCondition('slns.slesson_teacher_id', 'IN', $userIds);
        $condition->attachCondition('sldetail_learner_id', 'IN', $userIds);
        $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addCondition('CONCAT(slns.`slesson_date`, " ", slns.`slesson_start_time` )', '< ', $weekEndDate);
        $srch->addCondition('CONCAT(slns.`slesson_end_date`, " ", slns.`slesson_end_time` )', ' > ', $weekStartDate);

        $data = $db->fetchAll($srch->getResultSet());
        $jsonArr = [];
        $groupClassIds = [];

        foreach ($data as $data) {
            $slesson_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $data['slesson_date'] . ' ' . $data['slesson_start_time'], true, $userTimezone);
            $slesson_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $data['slesson_end_date'] . ' ' . $data['slesson_end_time'], true, $userTimezone);
            $jsonArr[] = [
                // "title" => $data['teacherTeachLanguageName'],
                "title" => "",
                "start" => $slesson_start_time,
                "end" => $slesson_end_time,
                "className" => "sch_data booked-slot",
                "classType" => "0",
            ];
            if ($data['slesson_grpcls_id'] > 0) {
                $groupClassIds[] = $data['slesson_grpcls_id'];
            }
        }
        echo FatUtility::convertToJson($jsonArr);
    }

    public function getTeacherWeeklyScheduleJsonData($userId)
    {
        $post = FatApp::getPostedData();
        if (false === $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        if ($userId < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $userTimezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();


        $startDate = MyDate::changeDateTimezone($post['start'], $userTimezone, $systemTimeZone);
        $endDate = MyDate::changeDateTimezone($post['end'], $userTimezone, $systemTimeZone);
        
        $weeklySchRows = TeacherWeeklySchedule::getWeeklyScheduleJsonArr($userId, $startDate, $endDate);
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $teacherBookingBefore = null;
        if (isset($_POST['bookingBefore'])) {
            $teacherBookingBefore = FatUtility::int(FatApp::getPostedData('bookingBefore'));
        }

        $jsonArr = [];
        $validStartDateTime = strtotime("+ " . $teacherBookingBefore . " hours");

        if (!empty($weeklySchRows)) {
            /* code added on 15-07-2019 */
            foreach ($weeklySchRows as $row) {
                $endDateTime = $row['twsch_end_date'] . ' ' . $row['twsch_end_time'];
                $startDateTime = $row['twsch_date'] . ' ' . $row['twsch_start_time'];

                // if ($validStartDateTime > strtotime($endDateTime)) {
                //     continue;
                // }
                
                if ($validStartDateTime > strtotime($startDateTime) &&  strtotime($endDateTime) > $validStartDateTime) {
                    $startDateTime = date('Y-m-d H:i:s', $validStartDateTime);
                }
                $twsch_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $endDateTime, true, $userTimezone);
                $twsch_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $startDateTime, true, $userTimezone);

                $twsch_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $startDateTime, true, $userTimezone);
               
                $midPoint = (strtotime($startDateTime) + strtotime($endDateTime)) / 2;
                
                $dateTime = new dateTime(date('Y-m-d H:i:s', $midPoint));
                $weekRange = MyDate::getWeekStartAndEndDate($dateTime);
                $twschWeekYear = date('W-Y', strtotime($weekRange['weekStart']));

                $jsonArr[] = [
                    "title" => "",
                    "date" => $twsch_date,
                    "start" => $twsch_start_time,
                    "end" => $twsch_end_time,
                    "weekyear" =>  $twschWeekYear,
                    '_id' => $row['twsch_id'],
                    'classType' => $row['twsch_is_available'],
                    'className' => $cssClassNamesArr[$row['twsch_is_available']],
                    'action' => 'fromWeeklySchedule',
                ];
            }
        }
        $midPoint = (strtotime($startDate) + strtotime($endDate)) / 2;
        $dateTime = new dateTime(date('Y-m-d H:i:s', $midPoint));
        $weekRange = MyDate::getWeekStartAndEndDate($dateTime);
        
        $twsch_weekyear = date('W-Y', strtotime($weekRange['weekStart']));

        if ( (empty($jsonArr) || end($jsonArr)['weekyear'] != $twsch_weekyear)) {
            $weekData = ['WeekStart' => $weekRange['weekStart'], 'WeekEnd' => $weekRange['weekEnd']];
            $jsonArr2 = TeacherGeneralAvailability::getGenaralAvailabilityJsonArr($userId, $weekData, $teacherBookingBefore);
            $jsonArr = array_merge($jsonArr, $jsonArr2);
        }


        echo FatUtility::convertToJson($jsonArr);
    }

    public function qualificationList()
    {
        $json = [];
        $post = FatApp::getPostedData();
        if (false === $post) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $user_id = FatApp::getPostedData('user_id', FatUtility::VAR_INT, 0);
        if ($user_id < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $srch = new UserQualificationSearch(false);
        $srch->addCondition('uqualification_user_id', '=', $user_id);
        $srch->addCondition('uqualification_active', '=', 1);
        $srch->addOrder('uqualification_experience_type');
        $srch->addOrder('uqualification_start_year');

        $srch->addMultiplefields([
            'uqualification_id',
            'uqualification_experience_type',
            'uqualification_title',
            'uqualification_institute_name',
            'uqualification_institute_address',
            'uqualification_description',
            'uqualification_start_year',
            'uqualification_end_year'
        ]);
        $rs = $srch->getResultSet();
        $totalRecords = $srch->recordCount();
        $qualifications = FatApp::getDb()->fetchAll($rs);
        $this->set('qualifications', $qualifications);
        $this->set('qualificationTypeArr', UserQualification::getExperienceTypeArr());
        if ($totalRecords > 0) {
            $this->_template->render(false, false, 'teachers/qualification-list.php', false, false);
        } else {
            $this->_template->render(false, false, 'teachers/no-qualification-found.php', false, false);
        }
    }

    private function searchTeachers(&$srch)
    {
        $teachLangId = FatApp::getPostedData('teachLangId', FatUtility::VAR_INT, 0);
        $postedData = FatApp::getPostedData();
        $srch = new UserSearch(false);
        $srch->setTeacherDefinedCriteria(false, false);
        $tlangSrch = $srch->getMyTeachLangQry(true, $this->siteLangId, $teachLangId);
        $tlangSrch->addCondition('utl.utl_booking_slot', 'IN', CommonHelper::getPaidLessonDurations());
        $srch->joinTable("(" . $tlangSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utl_user_id', 'utls');
        $srch->joinUserSpokenLanguages($this->siteLangId);
        $srch->joinUserCountry($this->siteLangId);
        $srch->joinUserAvailibility();
        if (UserAuthentication::isUserLogged()) {
            $srch->joinFavouriteTeachers(UserAuthentication::getLoggedUserId());
            $srch->addFld('uft_id');
        } else {
            $srch->addFld('0 as uft_id');
        }
        /* [ */
        $spokenLanguage = FatApp::getPostedData('spokenLanguage', FatUtility::VAR_STRING, NULL);
        if (!empty($spokenLanguage)) {
            $srch->addDirectCondition('spoken_language_ids IN (' . $spokenLanguage . ')');
        }
        /* ] */
        /* [ */
        $preferenceFilter = FatApp::getPostedData('preferenceFilter', FatUtility::VAR_STRING, NULL);
        if (!empty($preferenceFilter)) {
            if (is_numeric($preferenceFilter)) {
                $srch->addCondition('utpref_preference_id', '=', $preferenceFilter);
            } else {
                $preferenceFilterArr = explode(",", $preferenceFilter);
                $srch->addCondition('utpref_preference_id', 'IN', $preferenceFilterArr);
                $srch->addHaving('mysql_func_COUNT(DISTINCT utpref_preference_id)', '=', count($preferenceFilterArr), 'AND', true);
            }
        }
        /* ] */
        /* from country[ */
        $fromCountry = FatApp::getPostedData('fromCountry', FatUtility::VAR_STRING, NULL);
        if (!empty($fromCountry)) {
            if (is_numeric($fromCountry)) {
                $srch->addCondition('user_country_id', '=', $fromCountry);
            } else {
                $fromCountryArr = explode(",", $fromCountry);
                if (!empty($fromCountryArr)) {
                    $fromCountryArr = FatUtility::int($fromCountryArr);
                    $srch->addCondition('user_country_id', 'IN', $fromCountryArr);
                }
            }
        }
        /* ] */
        /* Language Teach [ */
        $langTeach = FatApp::getPostedData('teach_language_id', FatUtility::VAR_STRING, NULL);
        if ($langTeach > 0) {
            if (is_numeric($langTeach)) {
                //$srch->addCondition( 'us.us_teach_slanguage_id', '=', $langTeach );
                $srch->addDirectCondition('FIND_IN_SET(' . $langTeach . ', utl_tlanguage_ids)');
            }
        }
        /* ] */
        /* Week Day [ */
        $weekDays = FatApp::getPostedData('filterWeekDays', FatUtility::VAR_STRING, array());
        $timeSlots = FatApp::getPostedData('filterTimeSlots', FatUtility::VAR_STRING, array());
        $timeSlotArr = [];
        if (!empty($timeSlots)) {
            $timeSlotArr = CommonHelper::formatTimeSlotArr($timeSlots);
        }
        if (is_array($weekDays) && !empty($weekDays)) {
            $weekDates = MyDate::changeWeekDaysToDate($weekDays, $timeSlotArr);
            $condition = ' ( ';
            foreach ($weekDates as $weekDayKey => $date) {
                $condition .= ($weekDayKey == 0) ? '' : ' OR ';
                $condition .= ' ( CONCAT(`tgavl_date`," ",`tgavl_start_time`) < "' . $date['endDate'] . '" and CONCAT(`tgavl_end_date`," ",`tgavl_end_time`) > "' . $date['startDate'] . '" ) ';
            }
            $condition .= ' ) ';
            $srch->addDirectCondition($condition);
        }
        /* ] */
        /* Time Slot [ */
        if (empty($weekDays) && !empty($timeSlotArr)) {
            $systemTimeZone = MyDate::getTimeZone();
            $user_timezone = MyDate::getUserTimeZone();
            $condition = ' ( ';
            foreach ($timeSlotArr as $key => $formatedVal) {
                $condition .= ($key == 0) ? '' : ' OR ';
                $startTime = date('Y-m-d') . ' ' . $formatedVal['startTime'];
                $endTime = date('Y-m-d') . ' ' . $formatedVal['endTime'];
                $startTime = date('H:i:s', strtotime(MyDate::changeDateTimezone($startTime, $user_timezone, $systemTimeZone)));
                $endTime = date('H:i:s', strtotime(MyDate::changeDateTimezone($endTime, $user_timezone, $systemTimeZone)));
                $condition .= ' ( CONCAT(`tgavl_date`," ",`tgavl_start_time`) <  CONCAT(`tgavl_end_date`," ","' . $endTime . '") and CONCAT(`tgavl_end_date`," ",`tgavl_end_time`) >  CONCAT(`tgavl_date`," ","' . $startTime . '") ) ';
            }
            $condition .= ' ) ';
            $srch->addDirectCondition($condition);
        }
        /* ] */
        /* [ */
        $gender = FatApp::getPostedData('gender', FatUtility::VAR_STRING, NULL);
        if (!empty($gender)) {
            if (is_numeric($gender)) {
                $srch->addCondition('user_gender', '=', $gender);
            } else {
                $genderArr = explode(",", $gender);
                if (!empty($genderArr)) {
                    $genderArr = FatUtility::int($genderArr);
                    $srch->addCondition('user_gender', 'IN', $genderArr);
                }
            }
        }
        /* ] */
        /* price Range[ */
        $minPriceRange = FatApp::getPostedData('minPriceRange', FatUtility::VAR_FLOAT, 0);
        $maxPriceRange = FatApp::getPostedData('maxPriceRange', FatUtility::VAR_FLOAT, 0);
        if (!empty($minPriceRange) && !empty($maxPriceRange)) {
            $minPriceRangeInDefaultCurrency = CommonHelper::getDefaultCurrencyValue($minPriceRange, false, false);
            $maxPriceRangeInDefaultCurrency = CommonHelper::getDefaultCurrencyValue($maxPriceRange, false, false);
            $condition = $srch->addCondition('minPrice', '<=', $maxPriceRangeInDefaultCurrency);
            $condition->attachCondition('maxPrice', '>=', $minPriceRangeInDefaultCurrency, 'AND');
        }
        /* ] */
        /* [ */
        $filterSortBy = FatApp::getPostedData('sortBy', FatUtility::VAR_STRING, 'popularity_desc');
        $filterSortBy = explode('_', $filterSortBy);
        $sortBy = $filterSortBy[0];
        $sortOrder = $filterSortBy[1];

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
        if (!empty($sortBy)) {
            $sortByArr = explode("_", $sortBy);
            $sortBy = isset($sortByArr[0]) ? $sortByArr[0] : $sortBy;
            $sortOrder = isset($sortByArr[1]) ? $sortByArr[1] : $sortOrder;
            switch ($sortBy) {
                case 'price':
                    $srch->addOrder('minPrice', $sortOrder);
                    break;
                case 'popularity':
                    $srch->addOrder('studentIdsCnt', $sortOrder);
                    $srch->addOrder('teacherTotLessons', $sortOrder);
                    $srch->addOrder('totReviews', $sortOrder);
                    $srch->addOrder('teacher_rating', $sortOrder);
                    break;
            }
        }
        /* ] */
        if (isset($postedData['keyword']) && !empty($postedData['keyword'])) {
            $cond = $srch->addCondition('user_first_name', 'LIKE', '%' . $postedData['keyword'] . '%');
            $cond->attachCondition('user_last_name', 'LIKE', '%' . $postedData['keyword'] . '%');
            $cond->attachCondition('mysql_func_CONCAT(user_first_name, " ", user_last_name)', 'LIKE', '%' . $postedData['keyword'] . '%', 'OR', true);
        }
        $srch->addOrder('user_id', 'DESC');
        $srch->addGroupBy('user_id');
        $srch->addMultipleFields([
            'user_id',
            'user_url_name',
            'user_first_name',
            'user_last_name',
            'user_country_id',
            'country_name as user_country_name',
            'user_profile_info',
            'uqualification_user_id',
            'utls.teacherTeachLanguageName',
            'utl_ids',
        ]);
    }

    private function getTeacherSearchForm($teachLangId = 0)
    {
        $teachLangName = '';
        if ($teachLangId) {
            $srch = new TeachingLanguageSearch($this->siteLangId);
            $srch->addCondition('tlanguage_id', '=', $teachLangId);
            $srch->addMultipleFields(['tlanguage_id', 'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name']);
            $srch->doNotCalculateRecords();
            $srch->setPageSize(1);
            $rs = $srch->getResultSet();
            $languages = FatApp::getDb()->fetch($rs);

            if (!empty($languages['tlanguage_id'])) {
                $teachLangId = $languages['tlanguage_id'];
                $teachLangName = $languages['tlanguage_name'];
            }
        }

        $frm = new Form('frmTeacherSrch');
        $frm->addTextBox('', 'teach_language_name', $teachLangName, ['placeholder' => Label::getLabel('LBL_Language')]);
        $frm->addHiddenField('', 'teachLangId', $teachLangId);
        $frm->addTextBox('', 'teach_availability', '', ['placeholder' => Label::getLabel('LBL_Select_date_time')]);
        $keyword = $frm->addTextBox('', 'keyword', '', ['placeholder' => Label::getLabel('LBL_Search_By_Teacher_Name')]);
        $keyword->requirements()->setLength(0, 15);
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'btnTeacherSrchSubmit', '');
        return $frm;
    }
}
