<?php
class TeachersController extends MyAppController
{

	public function index($teachLangId = 0)
	{

		$keyword = FatApp::getPostedData('language');
		$frmSrch = $this->getTeacherSearchForm($teachLangId, $keyword);
		$this->set('frmTeacherSrch', $frmSrch);
		$daysArr = array(
			0 => Label::getLabel('LBL_Sunday'),
			1 => Label::getLabel('LBL_Monday'),
			2 => Label::getLabel('LBL_Tuesday'),
			3 => Label::getLabel('LBL_Wednesday'),
			4 => Label::getLabel('LBL_Thursday'),
			5 => Label::getLabel('LBL_Friday'),
			6 => Label::getLabel('LBL_Saturday')
		);
		$timeSlotArr = TeacherGeneralAvailability::timeSlotArr();
		$this->set('keywordlanguage', $keyword);
		$this->set('daysArr', $daysArr);
		$this->set('timeSlotArr', $timeSlotArr);
		$this->_template->addJs('js/enscroll-0.6.2.min.js');
		$this->_template->addJs('js/moment.min.js');
		$this->_template->addJs('js/fullcalendar.min.js');
		$this->_template->addCss('css/fullcalendar.min.css');
		$this->_template->addCss('css/custom-full-calendar.css');
		$this->_template->addJs('js/ion.rangeSlider.js');
		$this->_template->addCss('css/ion.rangeSlider.css');
		$this->_template->addCss('css/ion.rangeSlider.skinHTML5.css');
		$this->_template->render();
	}

	public function teachersList()
	{
		$frmSrch = $this->getTeacherSearchForm();
		$post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
		if (false === $post) {
			Message::addErrorMessage($frmSrch->getValidationErrors());
			FatUtility::dieWithError(Message::getHtml());
		}
		$page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
		if ($page < 2) {
			$page = 1;
		}
		$pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
		/* $json['status'] = true;
		$json['msg'] = ''; */

		$srch = new stdClass();
		$this->searchTeachers($srch);
		$srch->joinUserLang($this->siteLangId);
		$srch->joinTeacherLessonData(0, false, false);
		$srch->joinRatingReview();
		$srch->addMultipleFields(array(
			// 'ulg.*', 
			'IFNULL(userlang_user_profile_Info, user_profile_info) as user_profile_info', 
			'utls.minPrice'
			// 'utls.*'
		));
		$srch->setPageSize($pageSize);
		$srch->setPageNumber($page);
		$srch->removGroupBy('sl.slesson_teacher_id');
		if (UserAuthentication::isUserLogged()) {
			$srch->addCondition('user_id', '!=', UserAuthentication::getLoggedUserId());
		}
		// echo $srch->getQuery();die;
		$rs = $srch->getResultSet();
	
		$db = FatApp::getDb();
		$teachersList = $db->fetchAll($rs);
        // CommonHelper::printArray($teachersList);die;
		$totalRecords = $srch->recordCount();
		$pagingArr = array(
			'pageCount' => $srch->pages(),
			'page' => $page,
			'pageSize' => $pageSize,
			'recordCount' => $totalRecords,
		);

		$this->set('teachers', $teachersList);
		$post['page'] = $page;
		$this->set('postedData', $post);
		$this->set('pagingArr', $pagingArr);
		$html = $this->_template->render(false, false, 'teachers/teachers-list.php', true);
		$this->set('html', $html);
		$startRecord = ($page - 1) * $pageSize + 1;
		if ($totalRecords < 1) {
			$startRecord = 0;
		}
		$endRecord = $page * $pageSize;
		if ($totalRecords < $endRecord) {
			$endRecord = $totalRecords;
		}
		$this->set('startRecord', $startRecord);
		$this->set('endRecord', $endRecord);
		$this->set('totalRecords', $totalRecords);
		$this->set('msg', Label::getLabel('LBL_Request_Processing..'));
		/* $json['startRecord'] = $startRecord ;
		$json['endRecord'] = $endRecord;
		$json['totalRecords'] = $totalRecords; */
		//FatUtility::dieJsonSuccess($json);
		$this->_template->render(false, false, 'json-success.php', false, false);
	}

	public function spokenLanguagesAutoCompleteJson()
	{
		$pageSize = 20;
		$post = FatApp::getPostedData();
		$srch = new SpokenLanguageSearch($this->siteLangId);
        $srch->addChecks();
        // $srch->setPageSize($pageSize);
		$keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        if (!empty($keyword)) {
            $cnd = $srch->addCondition('slanguage_identifier', 'like', '%' . $keyword . '%');
            $cnd->attachCondition('slanguage_name', 'like', '%' . $keyword . '%', 'OR');
		}
		$rs = $srch->getResultSet();
        $languages = FatApp::getDb()->fetchAll($rs, 'slanguage_id');
        $json  = array();
        foreach ($languages as $key => $language) {
            $json[] = array(
                'id' => $key,
                'name' => $language['slanguage_name'],
            );
        }
        die(json_encode($json));
	}

	public function teachLanguagesAutoCompleteJson()
	{
		$pageSize = 20;
		$post = FatApp::getPostedData();
		$srch = new TeachingLanguageSearch($this->siteLangId);
        // $srch->setPageSize($pageSize);
		$keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
		$srch->addOrder('tlanguage_display_order');
		$rs = $srch->getResultSet();
		$languages = FatApp::getDb()->fetchAll($rs, 'tlanguage_id');
		$json = array();
		foreach ($languages as $key => $language) {
			$json[] = array(
				'id' => $key,
				'name' => $language['tlanguage_name'],
			);
		}
		die(json_encode($json));
	}

	public function view($user_name)
	{
		// echo 'view';
		// exit;
		$this->_template->addJs('js/moment.min.js');
		$this->_template->addJs('js/fullcalendar.min.js');
		$this->_template->addCss('css/fullcalendar.min.css');
		$this->_template->addCss('css/custom-full-calendar.css');

		$srchTeacher = new UserSearch();
		$srchTeacher->addMultipleFields(array('user_id'));
		$srchTeacher->addCondition('user_url_name', '=', $user_name);
		$srchTeacher->setPageSize(1);
		$rsTeacher = $srchTeacher->getResultSet();
		$teacherData = FatApp::getDb()->fetch($rsTeacher);
		if (empty($teacherData)) {
			FatUtility::exitWithErrorCode(404);
		}
		$teacher_id = $teacherData['user_id'];
		$teacher_id = FatUtility::int($teacher_id);
		/* preferences/skills[ */
		$prefSrch = new UserToPreferenceSearch();
		$prefSrch->joinToPreference($this->siteLangId);
		$prefSrch->addCondition('utpref_user_id', '=', $teacher_id);
		$prefSrch->addOrder('preference_type');
		$prefSrch->addGroupBy('preference_type');
		$prefSrch->addMultipleFields(array('preference_type', 'GROUP_CONCAT(IFNULL(preference_title, preference_identifier)) as preference_titles'));
		$prefRs = $prefSrch->getResultSet();
		$teacherPreferences = FatApp::getDb()->fetchAll($prefRs);
		/* ] */
		$srch = new UserSearch();
		$srch->setTeacherDefinedCriteria(true);
		$srch->joinUserLang($this->siteLangId);
		$srch->joinTeacherLessonData();
		$srch->joinUserSpokenLanguages($this->siteLangId);
		$srch->joinUserTeachLanguage($this->siteLangId);
		$srch->joinUserCountry($this->siteLangId);
		$srch->joinUserState($this->siteLangId);
		if (UserAuthentication::isUserLogged()) {
			$srch->joinFavouriteTeachers(UserAuthentication::getLoggedUserId());
			$srch->addFld('uft_id');
			/* find, if have added any offer price is locked with this teacher[ */
			// $srch->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'top_teacher_id = user_id AND top_learner_id = '.UserAuthentication::getLoggedUserId(), 'top');
			// $srch->addFld(array('top_single_lesson_price', 'top_bulk_lesson_price'));
			/* ] */
		} else {
			$srch->addFld('0 as uft_id');
		}
		$srch->setPageSize(1);
		$srch->addCondition('user_id', '=', $teacher_id);
		$srch->addMultipleFields(array(
			'user_id',
			'user_first_name',
			'user_last_name',
			'CONCAT(user_first_name," ", user_last_name) as user_full_name',
			'user_country_id',
			/* 'user_timezone', */
			'IFNULL(country_name, country_code) as user_country_name',
			'IFNULL(state_name, state_identifier) as user_state_name',
			'IFNULL(tlanguage_name, tlanguage_identifier) as teachlanguage_name',
			// 'utsl.spoken_language_names',
			// 'utsl.spoken_languages_proficiency',
			'us_video_link',
			'us_is_trial_lesson_enabled',
			'minPrice',
			'maxPrice',
            'IFNULL(userlang_user_profile_Info, user_profile_info) as user_profile_info',
			'utl_slanguage_ids',
            'utl_booking_slots'
		));
		// echo $srch->getQuery();die;
		$rs = $srch->getResultSet();
		$teacher = FatApp::getDb()->fetch($rs);
		if (empty($teacher)) {
			FatUtility::exitWithErrorCode(404);
		}


		/* [ */
		$isFreeTrialEnabled = false;
		$freeTrialPackageRow = LessonPackage::getFreeTrialPackage($this->siteLangId);
		if ($teacher['us_is_trial_lesson_enabled'] == 1 && $freeTrialPackageRow) {
			$isFreeTrialEnabled = true;
			$this->set('freeTrialPackageRow', $freeTrialPackageRow);
		}
		$teacher['isFreeTrialEnabled'] = $isFreeTrialEnabled;
		/* ] */
		/* Languages and prices [ */
		$userToLanguage = new UserToLanguage($teacher_id);
		$userTeachLangs = $userToLanguage->getTeacherPricesForLearner($this->siteLangId, UserAuthentication::getLoggedUserId(true));
		$tlangArr = array();
		foreach($userTeachLangs as $userTeachLang){
			$tlangArr[$userTeachLang['tlanguage_id']] = $userTeachLang['tlanguage_name'];
		}
		// CommonHelper::printArray($userTeachLangs);die;
		$this->set('userTeachLangs', $userTeachLangs);
		/* ] */
		/* [ */
		$srch = LessonPackage::getSearchObject($this->siteLangId);
		$srch->addCondition('lpackage_is_free_trial', '=', 0);
		$srch->addMultipleFields(array(
			'lpackage_id',
			'IFNULL(lpackage_title, lpackage_identifier) as lpackage_title',
			'lpackage_lessons'
		));
		$rs = $srch->getResultSet();
		$lessonPackages = FatApp::getDb()->fetchAll($rs);
		$teacher['lessonPackages'] = $lessonPackages;
		$teacher['teachLanguages'] = $tlangArr;
		/* ] */
		$teacher['isAlreadyPurchasedFreeTrial'] = false;
		if (UserAuthentication::isUserLogged()) {
			$teacher['isAlreadyPurchasedFreeTrial'] = LessonPackage::isAlreadyPurchasedFreeTrial(UserAuthentication::getLoggedUserId(), $teacher_id);
		}
		// var_dump($teacher['isAlreadyPurchasedFreeTrial']);
		// die;
		$teacherLessonReviewObj = new TeacherLessonReviewSearch();
		$teacherLessonReviewObj->joinTeacher();
		$teacherLessonReviewObj->joinLearner();
		$teacherLessonReviewObj->joinTeacherLessonRating();
		$teacherLessonReviewObj->joinScheduledLesson();
		$teacherLessonReviewObj->joinScheduleLessonDetails();
		$teacherLessonReviewObj->doNotCalculateRecords();
		$teacherLessonReviewObj->doNotLimitRecords();
		$teacherLessonReviewObj->addCondition('tlr.tlreview_status', '=', TeacherLessonReview::STATUS_APPROVED);
		$teacherLessonReviewObj->addCondition('tlreview_teacher_user_id', '=', $teacher_id);
		$teacherLessonReviewObj->addMultipleFields(array("ROUND(AVG(tlrating_rating),2) as prod_rating", "count(DISTINCT tlreview_id) as totReviews"));
		$teacherLessonReviewObj->addMultipleFields(array('COUNT(DISTINCT tlreview_postedby_user_id) as totStudents'));
		$reviews = FatApp::getDb()->fetch($teacherLessonReviewObj->getResultSet());
		$this->set('reviews', $reviews);
		$frmReviewSearch = $this->getTeacherReviewSearchForm(FatApp::getConfig('CONF_FRONTEND_PAGESIZE'));
		$frmReviewSearch->fill(array('tlreview_teacher_user_id' => $teacher_id, 'teach_lang_name' => $teacher['teachlanguage_name']));
		$this->set('frmReviewSearch', $frmReviewSearch);
		$teacher['proficiencyArr'] = SpokenLanguage::getProficiencyArr(CommonHelper::getLangId());
		$teacher['preferences'] = $teacherPreferences;
		$teacher['proficiencyArr'] = SpokenLanguage::getProficiencyArr(CommonHelper::getLangId());
		$this->set('teacher', $teacher);
        
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
		$frm->addHiddenField('', 'orderBy', 'most_recent');
		return $frm;
	}

	public function getTeacherReviews()
	{
		$teacherId = FatApp::getPostedData('tlreview_teacher_user_id');
		$langName = FatApp::getPostedData('teach_lang_name');
		$page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
		$orderBy = FatApp::getPostedData('orderBy', FatUtility::VAR_STRING, 'most_recent');
		$page = ($page) ? $page : 1;
		$pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
		$srch = new TeacherLessonReviewSearch();
		$srch->joinTeacher();
		$srch->joinLearner();
		$srch->joinTeacherLessonRating();
		$srch->joinScheduledLesson();
		$srch->joinScheduleLessonDetails();
		$srch->joinLessonLanguage(CommonHelper::getLangId());

		$srch->addCondition('tlr.tlreview_teacher_user_id', '=', $teacherId);
		$srch->addCondition('tlr.tlreview_status', '=', TeacherLessonReview::STATUS_APPROVED);
		$srch->addMultipleFields(array(
			'tlreview_id', "ROUND(AVG(tlrating_rating),2) as prod_rating",
			'tlreview_title',
			'tlreview_description',
			'tlreview_posted_on',
			'tlreview_postedby_user_id',
			'ul.user_first_name as lname',
			'ut.user_first_name as tname',
			'(select count(slesson_id) from  ' . ScheduledLesson::DB_TBL . ' where slesson_teacher_id = ut.user_id AND sldetail_learner_id = ul.user_id ) as lessonCount',
			'IFNULL(tlanguage_name, tlanguage_identifier) as lessonLanguage'
		));
		$srch->addGroupBy('tlr.tlreview_id');
		$srch->setPageNumber($page);
		$srch->setPageSize($pageSize);
		switch ($orderBy) {
			case 'most_helpful':
				$srch->addOrder('helpful', 'desc');
				break;
			default:
				$srch->addOrder('tlr.tlreview_posted_on', 'desc');
				break;
		}
		$records = FatApp::getDb()->fetchAll($srch->getResultSet());
		$this->set('reviewsList', $records);
		$this->set('page', $page);
		$this->set('langName', $langName);
		$this->set('pageCount', $srch->pages());
		$this->set('postedData', FatApp::getPostedData());
		$json['startRecord'] = !empty($records) ? ($page - 1) * $pageSize + 1 : 0;
		$json['recordsToDisplay'] = count($records);
		$json['totalRecords'] = $srch->recordCount();
		$json['msg'] =  Label::getLabel('LBL_Request_Processing..');
		$json['html'] = $this->_template->render(false, false, '_partial/teacher-reviews-list.php', true, false);
		$json['loadMoreBtnHtml'] = $this->_template->render(false, false, '_partial/load-more-teacher-reviews-btn.php', true, false);
		array_map(function ($val) {
			$val = iconv('UTF-8', 'UTF-8//IGNORE', $val);
		}, $json);
		FatUtility::dieJsonSuccess($json);
	}

	public function viewCalendar($teacher_id = 0, $languageId = 1)
	{
		$teacher_id = FatUtility::int($teacher_id);
		$languageId = FatUtility::int($languageId);
		if ($teacher_id < 1) {
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$srch = new UserSearch();
		$srch->setTeacherDefinedCriteria();
		$srch->setPageSize(1);
		$srch->addCondition('user_id', '=', $teacher_id);

		$srch->addMultipleFields(array(
			/* 'user_timezone', */
			'user_first_name',
			'CONCAT(user_first_name," ",user_last_name) as user_full_name',
			'user_country_id',
		));
		$rs = $srch->getResultSet();
		$userRow = FatApp::getDb()->fetch($rs);
		if (!$userRow) {
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}

		$allowedActionArr = array(
			'free_trial',
			'paid'
		);
		$postedAction = FatApp::getPostedData('action');
		if (!in_array($postedAction, $allowedActionArr)) {
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$bookingMinutesDuration = FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_INT, 60);
		if ('free_trial' == $postedAction) {
			$bookingMinutesDuration = FatApp::getConfig('conf_trial_lesson_duration', FatUtility::VAR_INT, 30);
			$freeTrialPackageRow = LessonPackage::getFreeTrialPackage();
			$lPackageId = $freeTrialPackageRow['lpackage_id'];
		} else {
			/* [ */
			$srch = LessonPackage::getSearchObject();
			$srch->addCondition('lpackage_is_free_trial', '=', 0);
			$srch->addOrder('lpackage_id', 'ASC');
			$srch->setPageSize(1);
			$srch->addFld('lpackage_id');
			$rs = $srch->getResultSet();
			$lessonPackageRow = FatApp::getDb()->fetch($rs);
			if (!empty($lessonPackageRow)) {
				$lPackageId = $lessonPackageRow['lpackage_id'];
			}
			/* ] */
		}
		if ($lPackageId <= 0) {
			FatUtility::dieWithError(Label::getLabel('LBL_Packages_are_not_configured_by_admin'));
		}
		$mint = floor($bookingMinutesDuration / 60);
		$mint =  ($mint > 9) ?  $mint : '0' . $mint;
		$second = ($bookingMinutesDuration - floor($bookingMinutesDuration / 60) * 60);
		$second =  ($second > 9) ?  $second : '0' . $second;
		$bookingSnapDuration = $mint . ':' . $second . ':00';
		$this->set('bookingMinutesDuration', $bookingMinutesDuration);
		$this->set('bookingSnapDuration', $bookingSnapDuration);
		MyDate::setUserTimeZone();
		$user_timezone = MyDate::getUserTimeZone();
		$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
		$teacherBookingBefore = current(UserSetting::getUserSettings($teacher_id))['us_booking_before'];
		if ('' ==  $teacherBookingBefore) {
			$teacherBookingBefore = 0;
		}
		$this->set('teacherBookingBefore', $teacherBookingBefore);
		$this->set('user_timezone', $user_timezone);
		$this->set('nowDate', $nowDate);
		$cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
		$this->set('lPackageId', $lPackageId);
		$this->set('userRow', $userRow);
		$this->set('action', $postedAction);
		$this->set('teacher_name', $userRow['user_first_name']);
		$this->set('teacher_country_id', $userRow['user_country_id']);
		$this->set('teacher_id', $teacher_id);
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
		$user_timezone = MyDate::getUserTimeZone();
		$startDateTime = MyDate::changeDateTimezone($post['start'], $user_timezone, $systemTimeZone);
		$endDateTime = MyDate::changeDateTimezone($post['end'], $user_timezone, $systemTimeZone);
		$date = MyDate::changeDateTimezone($post['date'] . ' ' . $post['startTime'], $user_timezone, $systemTimeZone);
		$day = MyDate::getDayNumber($startDateTime);
		if (strtotime($startDateTime) < strtotime(date('Y-m-d H:i:s'))) {
			FatUtility::dieJsonSuccess(0);
		}
		$originalDayNumber = $post['day'];
		$tWsch = new TeacherWeeklySchedule();
		$checkAvialSlots = $tWsch->checkCalendarTimeSlotAvailability($userId, $startDateTime, $endDateTime);
		$returnArray = [
			'status' => $checkAvialSlots,
		];
		if (!empty($tWsch->getError())) {
			$returnArray['msg'] = $tWsch->getError();
		}

		FatUtility::dieJsonSuccess($returnArray);
	}

	public function getTeacherGeneralAvailabilityJsonData(int $userId) {
		$post = FatApp::getPostedData();
		if ($userId < 1) {
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$jsonArr = TeacherGeneralAvailability::getGenaralAvailabilityJsonArr($userId, $post);
		echo FatUtility::convertToJson($jsonArr);
	}

	public function getTeacherScheduledLessonData($userId = 0)
	{
		$userId = FatUtility::int($userId);
		if ($userId < 1) {
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$srch = new ScheduledLessonSearch();
		$srch->addGroupBy('slesson_id');
		$srch->joinTeacher();
		$srch->joinTeacherSettings();
		$srch->joinTeacherTeachLanguageView($this->siteLangId);
		$srch->addMultipleFields(array(
			'slns.slesson_date',
			'slns.slesson_start_time',
			'slns.slesson_end_time',
			'slns.slesson_end_date'
			//'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName'
		));
		$srch->addCondition('slns.slesson_teacher_id', '=', $userId);
		$srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
		$rs = $srch->getResultSet();
		$data = FatApp::getDb()->fetchAll($rs);
		$jsonArr = array();
		$user_timezone = MyDate::getUserTimeZone();
		foreach ($data as $data) {
			$slesson_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $data['slesson_date'] . ' ' . $data['slesson_start_time'], true, $user_timezone);

			$slesson_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $data['slesson_end_date'] . ' ' . $data['slesson_end_time'], true, $user_timezone);
			$jsonArr[] = array(
				"title" => $data['teacherTeachLanguageName'],
				"start" => $slesson_start_time,
				"end" => $slesson_end_time,
				"className" => "sch_data",
				"classType" => "0",
			);
		}
		echo FatUtility::convertToJson($jsonArr);
	}

	public function getTeacherWeeklyScheduleJsonData(int $userId) {
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
        
		// $_serchEndDate = date('Y-m-d 00:00:00', strtotime($post['end']));
		$cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
		$jsonArr = array();
		if (!empty($weeklySchRows)) {
			/* code added on 15-07-2019 */
			foreach ($weeklySchRows as $row) {
				$twsch_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['twsch_end_date'] . ' ' . $row['twsch_end_time'], true, $userTimezone);
				$twsch_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['twsch_date'] . ' ' . $row['twsch_start_time'], true, $userTimezone);
				$twsch_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $row['twsch_date'] . ' ' . $row['twsch_start_time'], true, $userTimezone);
				// if ((strtotime($twsch_start_time) >=  strtotime($post['start'])) && (strtotime($twsch_end_time) <= strtotime($_serchEndDate))) {
					$jsonArr[] = array(
				    	"title" => "",
				    	"date" => $twsch_date,
				    	"start" => $twsch_start_time,
				    	"end" => $twsch_end_time,
				    	"weekyear" => $row['twsch_weekyear'],
				    	'_id' => $row['twsch_id'],
				    	'classType' => $row['twsch_is_available'],
				    	'className' => $cssClassNamesArr[$row['twsch_is_available']]
				    );
				// }
			}
		}
        
        $midPoint = (strtotime($startDate) + strtotime($endDate))/2;        
        $twsch_weekyear = date('W-Y', $midPoint);
        if(empty($jsonArr) || end($jsonArr)['weekyear'] != $twsch_weekyear){
            $weekRange = CommonHelper::getWeekRangeByDate(date('Y-m-d', $midPoint));
            $jsonArr2 = TeacherGeneralAvailability::getGenaralAvailabilityJsonArr($userId, array('WeekStart' => $weekRange['start'], 'WeekEnd' => $weekRange['end']));
            // CommonHelper::printArray($jsonArr2);die;
            $jsonArr = array_merge($jsonArr, $jsonArr2);
        }
        
        // CommonHelper::printArray($jsonArr);die;
		echo FatUtility::convertToJson($jsonArr);
	}

	public function qualificationList()
	{
		$json = array();
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
		//$srch->joinTable(AttachedFile::DB_TBL, 'LEFT OUTER JOIN','uqualification_id=afile_record_subid');
		$srch->addMultiplefields(array(
			'uqualification_id',
			'uqualification_experience_type',
			'uqualification_title',
			'uqualification_institute_name',
			'uqualification_institute_address',
			'uqualification_description',
			'uqualification_start_year',
			'uqualification_end_year'
		));
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
		$teachLanguageName = FatApp::getPostedData('teach_lang_keyword');
		$postedData = FatApp::getPostedData();
		$_SESSION['search_filters'] = $postedData;

		$srch = new UserSearch(false);
		$srch->setTeacherDefinedCriteria(false, false);
		$tlangSrch = $srch->getMyTeachLangQry(true, $this->siteLangId, $teachLanguageName);
		$srch->joinTable("(" . $tlangSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utl_us_user_id', 'utls');
		$srch->joinUserSpokenLanguages($this->siteLangId);
		// $srch->joinUserTeachingLanguages($this->siteLangId, $teachLanguageName);
		$srch->joinUserCountry($this->siteLangId);
		// $srch->joinUserSettings();
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
			$srch->addDirectCondition('spoken_language_ids IN ('. $spokenLanguage.')');
		}
		/* ] */
		/* [ */
		$preferenceFilter = FatApp::getPostedData('preferenceFilter', FatUtility::VAR_STRING, NULL);
		if (!empty($preferenceFilter)) {
			if (is_numeric($preferenceFilter)) {
				$srch->addDirectCondition('utpref_preference_id = '. $preferenceFilter);
			} else {
				$preferenceFilterArr = explode(",", $preferenceFilter);
				if (!empty($preferenceFilterArr)) {
					$preferenceFilterArr = FatUtility::int($preferenceFilterArr);
					foreach ($preferenceFilterArr as $preferenceFilter) {
						$srch->addDirectCondition('utpref_preference_id = '. $preferenceFilter);
					}
				}
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
				$srch->addDirectCondition('FIND_IN_SET(' . $langTeach . ', utl_slanguage_ids)');
			}
		}
		/* ] */
		/* Week Day [ */
		$weekDays = FatApp::getPostedData('filterWeekDays', FatUtility::VAR_STRING, array());
		if ($weekDays) {
			$srch->addCondition('ta.tgavl_day', 'IN', $weekDays);
		}
		/* ] */
		/* Time Slot [ */
		$timeSlots = FatApp::getPostedData('filterTimeSlots', FatUtility::VAR_STRING, array());

		$systemTimeZone = MyDate::getTimeZone();
		$user_timezone = MyDate::getUserTimeZone();

		if ($timeSlots) {
			$formatedArr = CommonHelper::formatTimeSlotArr($timeSlots);
			if ($formatedArr) {
				foreach ($formatedArr as $key => $formatedVal) {
					$startTime = date('Y-m-d') . ' ' . $formatedVal['startTime'];
					$endTime = date('Y-m-d') . ' ' . $formatedVal['endTime'];
					$startTime = date('H:i:s', strtotime(MyDate::changeDateTimezone($startTime, $user_timezone, $systemTimeZone)));
					$endTime = date('H:i:s', strtotime(MyDate::changeDateTimezone($endTime, $user_timezone, $systemTimeZone)));
					if ($key == 0) {
						$cnd = $srch->addCondition('tgavl_start_time', '<=', $startTime, 'AND');
						$cnd->attachCondition('tgavl_end_time', '>=', $startTime, 'AND');
					} else {
						$newSrch = $cnd->attachCondition('tgavl_start_time', '<=', $endTime, 'OR');
						$newSrch->attachCondition('tgavl_end_time', '>=', $endTime, 'AND');
					}
				}
			}
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
			$maxPriceRangeInDefaultCurrency =  CommonHelper::getDefaultCurrencyValue($maxPriceRange, false, false);
			$condition = $srch->addCondition('minPrice', '<=', $maxPriceRangeInDefaultCurrency);
			$condition->attachCondition('maxPrice', '>=', $minPriceRangeInDefaultCurrency,'AND');
		}
	
		/* ] */
		/* [ */
		$filterSortBy = FatApp::getPostedData('sortBy', FatUtility::VAR_STRING, 'popularity_desc');
		$filterSortBy = explode('_', $filterSortBy);
		$sortBy = $filterSortBy[0];
		$sortOrder = $filterSortBy[1];
		//$sortOrder = FatApp::getPostedData('sortOrder', FatUtility::VAR_STRING, 'asc');
		if (!in_array($sortOrder, array('asc', 'desc'))) {
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
		$srch->addMultipleFields(array(
			'user_id',
			'user_url_name',
			'user_first_name',
			'user_last_name',
			'user_country_id',
			'country_name as user_country_name',
			'user_profile_info',
			'uqualification_user_id',
			// 'utsl.spoken_language_names',
			// 'utsl.spoken_languages_proficiency',
			'utls.teacherTeachLanguageName',
            'utl_ids',
            // 'utl_slanguage_ids'
		));
	}

	private function getTeacherSearchForm($teachLangId = 0, $techLangKeyword = '')
	{
		$slangId = 0;
		$slangName = '';
		$keyword = '';
		if ($teachLangId) {
			$srch = new TeachingLanguageSearch($this->siteLangId);
			$srch->addCondition('tlanguage_id', '=', $teachLangId);
			$srch->addMultipleFields(array(
				'tlanguage_id',
				'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name'
			));

			$rs = $srch->getResultSet();
			$languages = FatApp::getDb()->fetch($rs);
			if ($languages) {
				$slangId = $languages['tlanguage_id'];
				$slangName = $languages['tlanguage_name'];
			}
		}
		if (isset($_SESSION['search_filters']) && !empty($_SESSION['search_filters'])) {
			if (isset($_SESSION['search_filters']['teach_lang_keyword']) && !empty($_SESSION['search_filters']['teach_lang_keyword'])) {
				$slangName = $_SESSION['search_filters']['teach_lang_keyword'];
				$techLangKeyword = $_SESSION['search_filters']['teach_lang_keyword'];
			}
			if (isset($_SESSION['search_filters']['keyword']) && !empty($_SESSION['search_filters']['keyword'])) {
				$keyword = $_SESSION['search_filters']['keyword'];
			}
		}
		$frm = new Form('frmTeacherSrch');
		$frm->addTextBox('', 'teach_language_name', $slangName, array('placeholder' => Label::getLabel('LBL_Select_a_language')));
		$frm->addHiddenField('', 'teach_language_id', $slangId);
		$frm->addHiddenField('', 'teach_lang_keyword', $techLangKeyword );
		$frm->addTextBox('', 'teach_availability', '', array('placeholder' => Label::getLabel('LBL_Select_date_time')));
		$keyword =  $frm->addTextBox('', 'keyword', $keyword, array('placeholder' => Label::getLabel('LBL_Search_By_Teacher_Name')));
		$keyword->requirements()->setLength(0,15);
		$fld = $frm->addHiddenField('', 'page', 1);
		$fld->requirements()->setIntPositive();
		$frm->addSubmitButton('', 'btnTeacherSrchSubmit', '');
		return $frm;
	}
}
