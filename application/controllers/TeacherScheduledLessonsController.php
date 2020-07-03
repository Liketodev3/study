<?php
class TeacherScheduledLessonsController extends TeacherBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->_template->addJs('js/jquery-confirm.min.js');
        $this->_template->addCss('css/jquery-confirm.min.css');
    }

    public function index()
    {
        $this->_template->addJs('js/teacherLessonCommon.js');
        $this->_template->addCss('css/custom-full-calendar.css');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addCss('css/fullcalendar.min.css');
        $frmSrch = $this->getSearchForm();
        $this->set('frmSrch', $frmSrch);
        $this->_template->render();
    }

    public function search()
    {
        $frmSrch = $this->getSearchForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        $referer = CommonHelper::redirectUserReferer(true);
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }

        $srch = new stdClass();
        $this->searchLessons($srch, $post);
        $srch->joinIssueReported(User::USER_TYPE_LEANER);
        $srch->addFld(
            array(
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            'IFNULL(iss.issrep_id,0) AS issrep_id',
            'CONCAT(slns.slesson_date, " ", slns.slesson_start_time) as startDateTime'
            )
        );
        // $srch->addOrder('slesson_status', 'ASC');
		$srch->addOrder('startDateTime', 'ASC');
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $rs = $srch->getResultSet();
        $lessons = FatApp::getDb()->fetchAll($rs);
        $lessonArr = array();
        $user_timezone = MyDate::getUserTimeZone();
        foreach ($lessons as $lesson) {
            $key = $lesson['slesson_date'];
            if ($lesson['slesson_date'] != '0000-00-00') {
                $key = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $lesson['slesson_date'] .' '. $lesson['slesson_start_time'], true, $user_timezone);
            }
            $lessonArr[$key][] = $lesson;
        }
        /* [ */
        $totalRecords = $srch->recordCount();
        $pagingArr = array(
            'pageCount' => $srch->pages(),
            'page' => $page,
            'pageSize' => $pageSize,
            'recordCount' => $totalRecords,
        );
        $this->set('postedData', $post);
        $this->set('pagingArr', $pagingArr);
        $startRecord = ($page - 1) * $pageSize + 1 ;
        $endRecord = $page * $pageSize;
        if ($totalRecords < $endRecord) {
            $endRecord = $totalRecords;
        }
        $teachLanguages = TeachingLanguage::getAllLangs($this->siteLangId);
        $this->set('teachLanguages', $teachLanguages);
        $this->set('startRecord', $startRecord);
        $this->set('endRecord', $endRecord);
        $this->set('totalRecords', $totalRecords);
        /* ] */
        $this->set('referer', $referer);
        $this->set('lessonArr', $lessonArr);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render(false, false);
    }

    private function searchLessons(&$srch, $post = array())
    {
        $srch = new ScheduledLessonSearch(false);
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->joinTeacher();
        $srch->joinLearner();
        $srch->joinLearnerCountry($this->siteLangId);
        $srch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $srch->joinTeacherSettings();
        //$srch->joinTeacherTeachLanguage( $this->siteLangId );
        $srch->addOrder('slesson_date', 'ASC');
        $srch->addOrder('slesson_status', 'ASC');
        $srch->addMultipleFields(array(
            'slns.slesson_id',
            'slns.slesson_learner_id as learnerId',
            'slns.slesson_teacher_id as teacherId',
            'slns.slesson_slanguage_id',
            'ul.user_first_name as learnerFname',
            'ul.user_last_name as learnerLname',
            'ul.user_url_name as learnerUrlName',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
            /* 'ul.user_timezone as learnerTimeZone', */
            'IFNULL(learnercountry_lang.country_name, learnercountry.country_code) as learnerCountryName',
            'slns.slesson_date',
            'slns.slesson_end_date',
            'slns.slesson_start_time',
            'slns.slesson_end_time',
            'slns.slesson_status',
            'slesson_order_id',
            'slns.slesson_is_teacher_paid',
            //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
            '"-" as teacherTeachLanguageName',
            'op_lpackage_is_free_trial as is_trial',
            'op_lesson_duration'
        ));

        if (isset($post) && !empty($post['keyword'])) {
            $keywordsArr = array_unique(array_filter(explode(' ', $post['keyword'])));
            foreach ($keywordsArr as $keyword) {
                $cnd = $srch->addCondition('ul.user_first_name', 'like', '%'.$keyword.'%');
                $cnd->attachCondition('ul.user_last_name', 'like', '%'.$keyword.'%');
                $cnd->attachCondition('slesson_order_id', 'like', '%'.$keyword.'%');
            }
        }

        if (isset($post) && !empty($post['status'])) {
            if ($post['status'] == ScheduledLesson::STATUS_ISSUE_REPORTED) {
                $srch->addCondition('issrep_id', '>', 0);
            } elseif ($post['status'] == ScheduledLesson::STATUS_UPCOMING) {
                $srch->addCondition('slns.slesson_date', '>=', date('Y-m-d'));
                $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
            } else {
                if ($post['status'] == ScheduledLesson::STATUS_SCHEDULED) {
                    $srch->addCondition('mysql_func_CONCAT(slns.slesson_date, " ", slns.slesson_start_time )', '>=', date('Y-m-d H:i:s'), 'AND', true);
                }
                $srch->addCondition('slns.slesson_status', '=', $post['status']);
            }
        }
    }

    public function view($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id', 'slesson_teacher_id'));
        if (!$lessonRow) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($lessonRow['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherScheduledLessons'));
        }
        $this->_template->addJs('js/teacherLessonCommon.js');
        $this->_template->addCss('css/custom-full-calendar.css');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addCss('css/fullcalendar.min.css');
        $this->_template->addJs('js/jquery.countdownTimer.min.js');
        $this->_template->addCss('css/jquery.countdownTimer.css');
        $this->set('lessonId', $lessonRow['slesson_id']);
        $this->_template->render();
    }

    public function viewLessonDetail($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        if (1 > $lessonId) {
            FatUtility::exitWithErrorCode(404);
        }
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->doNotCalculateRecords();
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->joinTeacherCountry($this->siteLangId);
        $srch->joinIssueReported(User::USER_TYPE_LEANER);
        $srch->addFld(array(
            'slns.slesson_teacher_id as teacherId',
            'ut.user_first_name as teacherFname',
            'ut.user_url_name as teacherUrlName',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
            'IFNULL(teachercountry_lang.country_name, teachercountry.country_code) as teacherCountryName',
            /* 'ut.user_timezone as teacherTimeZone', */
            //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
            '"-" as teacherTeachLanguageName',
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            //'IFNULL(iss.issrep_id,0) AS issrep_id',
            'iss.*'
        ));

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatApp::redirectUser(CommonHelper::generateUrl('TeacherScheduledLessons'));
        }
        /* flashCardSearch Form[ */
        $frmSrchFlashCard = $this->getLessonFlashCardSearchForm();
        $frmSrchFlashCard->fill(array('lesson_id' => $lessonRow['slesson_id']));
        $this->set('frmSrchFlashCard', $frmSrchFlashCard);
        /* ] */
        $this->set('lessonData', $lessonRow);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render(false, false);
    }

    public function searchFlashCards()
    {
        $frmSrch = $this->getLessonFlashCardSearchForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        $myteacher = (isset(FatApp::getPostedData()['teacherId']))?FatApp::getPostedData()['teacherId']:0;
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }

        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $lessonId = $post['lesson_id'];
        $srch = new FlashCardSearch(false);
        $srch->joinSharedFlashCard();
        $srch->joinLesson();
        $srch->joinWordLanguage();
        $srch->joinWordDefinitionLanguage();
        $srch->addCondition('sflashcard_slesson_id', '=', $lessonId);
        $srch->addCondition('sflashcard_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addOrder('flashcard_id', 'DESC');
        $srch->addMultipleFields(array(
            'flashcard_id',
            'flashcard_title',
            'wordLang.slanguage_code as wordLanguageCode',
            'flashcard_pronunciation',
            'flashcard_defination',
            'wordDefLang.slanguage_code as wordDefLanguageCode',
            'sflashcard_slesson_id'
        ));

        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        if (!empty($post['keyword'])) {
            $srch->addCondition('flashcard_title', 'like', '%'.$post['keyword'].'%');
        }
        $rsFlashcard = $srch->getResultSet();
        $flashCards = FatApp::getDb()->fetchAll($rsFlashcard);
        $this->set('flashCards', $flashCards);
        /* [ */
        $totalRecords = $srch->recordCount();
        $pagingArr = array(
            'pageCount' => $srch->pages(),
            'page' => $page,
            'pageSize' => $pageSize,
            'recordCount' => $totalRecords,
        );
        $this->set('postedData', $post);
        $this->set('pagingArr', $pagingArr);
        $startRecord = ($page - 1) * $pageSize + 1;
        $endRecord = $page * $pageSize;
        if ($totalRecords < $endRecord) {
            $endRecord = $totalRecords;
        }
        $this->set('startRecord', $startRecord);
        $this->set('endRecord', $endRecord);
        $this->set('totalRecords', $totalRecords);
        $this->set('myteacher', $myteacher);
        /* ] */
        $this->_template->render(false, false, 'teacher-scheduled-lessons/search-flash-cards.php');
    }

    public function viewCalendar()
    {
        MyDate::setUserTimeZone();
        $user_timezone = MyDate::getUserTimeZone();
        $nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
        $this->set('user_timezone', $user_timezone);
        $this->set('nowDate', $nowDate);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render(false, false);
    }

    public function calendarJsonData()
    {
        $cssClassNamesArr = ScheduledLesson::getStatusArr();
        $srch = new ScheduledLessonSearch();
        $srch->addMultipleFields(
            array(
                'slns.slesson_teacher_id',
                'slns.slesson_learner_id',
                'slns.slesson_date',
                'slns.slesson_end_date',
                'slns.slesson_start_time',
                'slns.slesson_end_time',
                'slns.slesson_status',
                'ul.user_first_name',
                'ul.user_id',
            )
        );
        $srch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->joinLearner();
        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $jsonArr = array();
        if (!empty($rows)) {
            $user_timezone = MyDate::getUserTimeZone();
            foreach ($rows as $k=>$row) {
                $slesson_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_date'], true, $user_timezone);
                $slesson_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_date'] .' '. $row['slesson_start_time'], true, $user_timezone);
                $slesson_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_end_date'] .' '. $row['slesson_end_time'], true, $user_timezone);
                $jsonArr[$k] = array(
                    "title" => $row['user_first_name'],
                    "date" => $slesson_date,
                    "start" => $slesson_start_time,
                    "end" => $slesson_end_time,
                    'lid' => $row['slesson_learner_id'],
                    'liFname' => substr($row['user_first_name'], 0, 1),
                    'classType' => $row['slesson_status'],
                    'className' => $cssClassNamesArr[$row['slesson_status']]
                );
                if (true == User::isProfilePicUploaded($row['user_id'])) {
                    $img = CommonHelper::generateFullUrl('Image', 'User', array($row['user_id']));
                    $jsonArr[$k]['imgTag'] = '<img src="'.$img.'" />';
                } else {
                    $jsonArr[$k]['imgTag'] = '';
                }
            }
        }
        echo FatUtility::convertToJson($jsonArr);
    }

    private function getCancelLessonFrm()
    {
        $frm = new Form('cancelFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'cancel_lesson_msg', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'slesson_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Send'));
        return $frm;
    }

    public function cancelLesson($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonRow['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getCancelLessonFrm();
        $frm->fill(array('slesson_id' => $lessonId ));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function cancelLessonSetup()
    {
        $frm = $this->getCancelLessonFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lessonId = $post['slesson_id'];
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinLearnerCredentials();
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('slns.slesson_id', ' = ', $lessonId);
        $srch->addFld(
            array(
                'lcred.credential_email as learnerEmailId',
                'slesson_status',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName'
            )
        );

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        if($lessonRow['slesson_status'] == ScheduledLesson::STATUS_CANCELLED) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Lesson_Already_Cancelled'));
        }
        /* ] */
        $db = FatApp::getDb();
        $db->startTransaction();
        /* update lesson status[ */
        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->assignValues(array('slesson_status' => ScheduledLesson::STATUS_CANCELLED));
        if (!$sLessonObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        /* ] */
        if (!$sLessonObj->refundToLearner()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        $db->commitTransaction();
        /* send an email to learner[ */
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{teacher_comment}' => $post['cancel_lesson_msg'],
            '{lesson_date}' => FatDate::format($lessonRow['slesson_date']),
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time'],
        );

        if (!EmailHandler::sendMailTpl($lessonRow['learnerEmailId'], 'teacher_cancelled_email', $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        /* ] */
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Cancelled_Successfully!'));
    }

    private function getRescheduleLessonFrm()
    {
        $frm = new Form('rescheduleFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'reschedule_lesson_msg', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'slesson_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Send'));
        return $frm;
    }

    public function requestReschedule($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonRow['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getRescheduleLessonFrm();
        $frm->fill(array('slesson_id' => $lessonId));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function requestRescheduleSetup()
    {
        $frm = $this->getRescheduleLessonFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lessonId = $post['slesson_id'];
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinLearnerCredentials();
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addFld(
            array(
                'lcred.credential_user_id as learnerId',
                'lcred.credential_email as learnerEmailId',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName'
            )
        );
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        /* update lesson status[ */
        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $dataArr = array(
            'slesson_status' => ScheduledLesson::STATUS_NEED_SCHEDULING,
            'slesson_date' => '0000-00-00',
            'slesson_start_time' => '00:00:00',
            'slesson_end_time' => '00:00:00'
        );
        $sLessonObj->assignValues($dataArr);
        if (!$sLessonObj->save()) {
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        /* ] */
        /* send email to learner[ */
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{teacher_comment}' => $post['reschedule_lesson_msg'],
            '{lesson_date}' => FatDate::format($lessonRow['slesson_date']),
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time'],
            '{action}' => Label::getLabel('LBL_Rescheduled'),
        );

        if (!EmailHandler::sendMailTpl($lessonRow['learnerEmailId'], 'teacher_reschedule_email', $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        /* ] */

        $userNotification = new UserNotifications($lessonRow['learnerId']);
        $userNotification->sendSchLessonByTeacherNotification($lessonId, true);
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Re-schedule_request_sent_successfully!'));
    }

    public function viewBookingCalendar($lessonId = 0)
    {
        $lessonId = FatUtility::int($lessonId);
        $teacher_id = UserAuthentication::getLoggedUserId();
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        /* $userRow = User::getAttributesById( $teacher_id, array('user_timezone') );
        $this->set('userRow',$userRow); */
        $this->set('action', FatApp::getPostedData('action'));
        $this->set('teacher_id', $teacher_id);
        $this->set('lessonId', $lessonId);
        $this->set('cssClassArr', $cssClassNamesArr);
        $this->_template->render(false, false);
    }

    public function scheduleLessonSetup()
    {
        FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        $lessonId = FatApp::getPostedData('lessonId', FatUtility::VAR_INT, 0);
        if ($lessonId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $post = FatApp::getPostedData();
        if (empty($post)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinLearnerCredentials();
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addFld(
            array(
                'lcred.credential_user_id as learnerId',
                'lcred.credential_email as learnerEmailId',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName'
            )
        );

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        /* update lesson status[ */
        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $dataArr = array(
            'slesson_status' => ScheduledLesson::STATUS_SCHEDULED,
            'slesson_date' => $post['date'],
            'slesson_start_time' => $post['startTime'],
            'slesson_end_time' => $post['endTime']
        );
        $sLessonObj->assignValues($dataArr);
        if (!$sLessonObj->save()) {
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        /* ] */
        /* send email to learner[ */
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{lesson_date}' => FatDate::format($post['date']),
            '{lesson_start_time}' => $post['startTime'],
            '{lesson_end_time}' => $post['endTime'],
            '{action}' => ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_SCHEDULED],
        );

        if (!EmailHandler::sendMailTpl($lessonRow['learnerEmailId'], 'teacher_scheduled_lesson_email', $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        /* ] */
        $userNotification = new UserNotifications($lessonRow['learnerId']);
        $userNotification->sendSchLessonByTeacherNotification($lessonId);
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Scheduled_Successfully!'));
    }

    private function getIssueReportedFrm()
    {
        $frm = new Form('issueReportedFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'issue_reported_msg', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'slesson_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Send'));
        return $frm;
    }

    public function issueReported($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonRow['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getIssueReportedFrm();
        $frm->fill(array('slesson_id' => $lessonId));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function issueReportedSetup()
    {
        $frm = $this->getIssueReportedFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lessonId = $post['slesson_id'];
        /* [ check If Already reorted */
        if (IssuesReported::isAlreadyReported($lessonId, User::USER_TYPE_TEACHER)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Issue_Already_Reported'));
        }
        /* ] */
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinLearnerCredentials();
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addFld(
            array(
                'lcred.credential_email as learnerEmailId',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName'
            )
        );
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        /* save issue reported[ */
        $reportedArr = array();
        $reportedArr['issrep_comment'] = $post['issue_reported_msg'];
        $reportedArr['issrep_reported_by'] = User::USER_TYPE_TEACHER;
        $reportedArr['issrep_slesson_id'] = $lessonId;
        $record = new IssuesReported();
        $record->assignValues($reportedArr);
        if (!$record->save()) {
            FatUtility::dieJsonError($record->getError());
        }
        /* ] */
        /* send email to learner[ */
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{teacher_comment}' => $post['issue_reported_msg'],
            '{lesson_date}' => FatDate::format($lessonRow['slesson_date']),
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time'],
            '{action}' => Label::getLabel('LBL_Issue_Reported'),
        );

        if (!EmailHandler::sendMailTpl($lessonRow['learnerEmailId'], 'teacher_issue_reported_email', $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        /* ] */
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Issue_Reported_SetUp_Successfully!'));
    }

    public function viewAssignedLessonPlan($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        if ($lessonId < 1) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* validation[ */
        $lessonDetail = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonDetail['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */
        $srch = new LessonPlanSearch(false);
        $srch->joinTable('tbl_scheduled_lessons_to_teachers_lessons_plan', 'INNER JOIN', 'tlpn_id = ltp_tlpn_id');
        $srch->addCondition('tlpn_user_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('ltp_slessonid', '=', $lessonId);
        $srch->addMultipleFields(array(
            'tlpn_id',
            'tlpn_title',
            'tlpn_level',
            'tlpn_user_id',
            'tlpn_tags',
            'tlpn_description',
        ));

        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        $this->set('statusArr', LessonPlan::getDifficultyArr());
        $this->set('data', $row);
        $this->_template->render(false, false);
    }

    public function assignLessonPlanToLessons()
    {
        $lessonId = FatApp::getPostedData('ltp_slessonid', FatUtility::VAR_INT, 0);
        $planId = FatApp::getPostedData('ltp_tlpn_id', FatUtility::VAR_INT, 0);
        if ($lessonId < 1 || $planId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* validation[ */
        $lessonDetail = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonDetail['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Access_Denied'));
        }
        $planDetail = LessonPlan::getAttributesById($planId, array('tlpn_user_id'));
        if ($planDetail['tlpn_user_id'] !=  UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Access_Denied'));
        }
        /* ] */
        $data = array(
            "ltp_slessonid" => $lessonId,
            "ltp_tlpn_id" => $planId,
        );

        if (!FatApp::getDb()->insertFromArray('tbl_scheduled_lessons_to_teachers_lessons_plan', $data, false, array(), $data)) {
            FatUtility::dieJsonError(FatApp::getDb()->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Plan_Assigned_Successfully!'));
    }

    public function removeAssignedLessonPlan()
    {
        $lessonId = FatApp::getPostedData('ltp_slessonid', FatUtility::VAR_INT, 0);
        if ($lessonId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* validation[ */
        $lessonDetail = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonDetail['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Access_Denied'));
        }
        /* ] */
        $data = array(
            'smt' =>'ltp_slessonid = ?',
            'vals'=>array($lessonId)
        );

        if (!FatApp::getDb()->deleteRecords('tbl_scheduled_lessons_to_teachers_lessons_plan', $data)) {
            FatUtility::dieJsonError(FatApp::getDb()->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Plan_Removed_Successfully!'));
    }

    public function changeLessonPlan($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        if ($lessonId < 1) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        /* validation[ */
        $lessonDetail = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonDetail['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */
        $post = FatApp::getPostedData();
        $srch = new LessonPlanSearch(false);
        $srch->addMultipleFields(array(
            'tlpn_id',
            'tlpn_title',
            'tlpn_level',
            'tlpn_user_id',
            'tlpn_tags',
            'tlpn_description',
        ));

        $srchRelLsnToPln = new SearchBase('tbl_scheduled_lessons_to_teachers_lessons_plan');
        $srchRelLsnToPln->addMultipleFields(array('ltp_tlpn_id'));
        $srchRelLsnToPln->addCondition('ltp_slessonid', '=', $lessonId);
        $relRs = $srchRelLsnToPln->getResultSet();
        $relRows = FatApp::getDb()->fetch($relRs);
        $srch->addCondition('tlpn_id', '!=', $relRows['ltp_tlpn_id']);
        $srch->addCondition('tlpn_user_id', '=', UserAuthentication::getLoggedUserId());
        if (!empty($post['keyword'])) {
            $srch->addCondition('tlpn_title', 'like', '%'.$post['keyword'].'%');
        }
        if (!empty($post['status'])) {
            $srch->addCondition('tlpn_level', '=', $post['status']);
        }
        $rs = $srch->getResultSet();
        $count = $srch->recordCount();
        $rows = FatApp::getDb()->fetchAll($rs);
        $this->set('userId', UserAuthentication::getLoggedUserId());
        $this->set('statusArr', LessonPlan::getDifficultyArr());
        $this->set('countData', $count);
        $this->set('lessonsPlanData', $rows);
        $this->set('lessonId', $lessonId);
        $this->_template->render(false, false);
    }

    public function listLessonPlans($lessonId)
    {
        $post = FatApp::getPostedData();
        $lessonId = FatUtility::int($lessonId);
        if ($lessonId < 1) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        /* validation[ */
        $lessonDetail = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        if ($lessonDetail['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */
        $srch = new LessonPlanSearch(false);
        $srch->addCondition('tlpn_user_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addMultipleFields(array(
            'tlpn_id',
            'tlpn_title',
            'tlpn_level',
            'tlpn_user_id',
            'tlpn_tags',
            'tlpn_description',
        ));

        if (!empty($post['keyword'])) {
            $srch->addCondition('tlpn_title', 'like', '%'.$post['keyword'].'%');
        }
        if (!empty($post['status'])) {
            $srch->addCondition('tlpn_level', '=', $post['status']);
        }

        $rs = $srch->getResultSet();
        $count = $srch->recordCount();
        $rows = FatApp::getDb()->fetchAll($rs);
        $this->set('userId', UserAuthentication::getLoggedUserId());
        $this->set('statusArr', LessonPlan::getDifficultyArr());
        $this->set('countData', $count);
        $this->set('lessonsPlanData', $rows);
        $this->set('lessonId', $lessonId);
        $this->_template->render(false, false);
    }

    public function viewFlashCard($flashcardId)
    {
        $flashcardId = FatUtility::int($flashcardId);
        $srch = new FlashCardSearch();
        $srch->joinSharedFlashCard();
        $srch->joinLesson();
        $srch->joinWordLanguage();
        $srch->joinWordDefinitionLanguage();
        $srch->addCondition('sflashcard_flashcard_id', '=', $flashcardId);
        $srch->addCondition('sflashcard_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addOrder('flashcard_id', 'DESC');
        $srch->addMultipleFields(array(
            'flashcard_id',
            'flashcard_title',
            'wordLang.slanguage_code as wordLanguageCode',
            'flashcard_pronunciation',
            'flashcard_defination',
            'wordDefLang.slanguage_code as wordDefLanguageCode',
            'flashcard_notes',
            'flashcard_added_on'
        ));
        $srch->setPageSize(1);
        $rsFlashcard = $srch->getResultSet();
        $flashCard = FatApp::getDb()->fetch($rsFlashcard);
        if (empty($flashCard)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('flashCardData', $flashCard);
        $this->_template->render(false, false);
    }

    public function removeFlashcard($flashCardId)
    {
        $flashCardId = FatUtility::int($flashCardId);
        /* validation [ */
        $srch = new FlashCardSearch();
        $srch->joinSharedFlashCard();
        $srch->joinLesson();
        //$srch->addCondition( 'flashcard_created_by_user_id', '=', UserAuthentication::getLoggedUserId() );
        $srch->addCondition('flashcard_id', '=', $flashCardId);
        $srch->addMultipleFields(array(
            'flashcard_id',
            'sflashcard_learner_id',
            'sflashcard_teacher_id',
            'flashcard_created_by_user_id'
        ));
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (empty($row)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        if ($row['flashcard_created_by_user_id'] != UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Record_cannot_be_deleted,_Access_Denied'));
        }
        /* ] */
        $db = FatApp::getDb();
        if (!$db->deleteRecords(FlashCard::DB_TBL_SHARED, array(
            'smt' => 'sflashcard_flashcard_id = ?',
            'vals' => array($row['flashcard_id'])
        ))) {
            FatUtility::dieJsonError($db->getError());
        }


        if (!$db->deleteRecords(FlashCard::DB_TBL, array(
            'smt' => 'flashcard_id = ?',
            'vals'=> array($row['flashcard_id'])
        ))) {
            FatUtility::dieJsonError($db->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel("LBL_Record_Deleted_Successfully!"));
    }

    private function getFlashcardFrm()
    {
        $frm= new Form('flashcardFrm');
        $frm->addRequiredField(Label::getLabel('LBL_Title'), 'flashcard_title');
        $langArr = SpokenLanguage::getAllLangs();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Title_Language'), 'flashcard_slanguage_id', $langArr);
        $fld->requirements()->setRequired(true);
        $frm->addRequiredField(Label::getLabel('LBL_Defination'), 'flashcard_defination');
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Defination_Language'), 'flashcard_defination_slanguage_id', $langArr);
        $fld->requirements()->setRequired(true);
        $frm->addTextBox(Label::getLabel('LBL_Pronunciation'), 'flashcard_pronunciation');
        $fld = $frm->addHiddenField('', 'flashcard_id', 0);
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'slesson_id', 0);
        $fld->requirements()->setInt();
        $frm->addTextArea(Label::getLabel('LBL_Notes'), 'flashcard_notes');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save'));
        return $frm;
    }

    public function flashCardForm()
    {
        $post = FatApp::getPostedData();
        $flashCardId = FatApp::getPostedData('flashcardId', FatUtility::VAR_INT, 0);
        $lessonId = FatApp::getPostedData('lessonId', FatUtility::VAR_INT, 0);
        if ($lessonId <= 0) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getFlashcardFrm();
        $frmData['slesson_id'] = $lessonId;
        if ($flashCardId > 0) {
            $frmData = $frmData + FlashCard::getAttributesById($flashCardId);
        }
        $frm->fill($frmData);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setupFlashCard()
    {
        $frm = $this->getFlashcardFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lessonId = $post['slesson_id'];
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id', 'slesson_learner_id'));
        if (empty($lessonRow) || $lessonRow['slesson_teacher_id'] != UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $flashCardId = $post['flashcard_id'];
        $post['flashcard_user_id'] = $lessonRow['slesson_learner_id'];
        $post['flashcard_created_by_user_id'] = UserAuthentication::getLoggedUserId();
        $flashCardObj = new FlashCard($flashCardId);
        $flashCardObj->assignValues($post);
        if (!$flashCardObj->save($post)) {
            FatUtility::dieJsonError($flashCardObj->getError());
        }

        if (0 == $flashCardId) {
            $db = FatApp::getDb();
            $flashcardId = $flashCardObj->getMainTableRecordId();
            $db->insertFromArray(FlashCard::DB_TBL_SHARED, array(
                'sflashcard_flashcard_id' => $flashcardId,
                'sflashcard_learner_id' => $lessonRow['slesson_learner_id'],
                'sflashcard_teacher_id' => $lessonRow['slesson_teacher_id'],
                'sflashcard_slesson_id' => $lessonId,
            ));
            if ($db->getError()) {
                FatUtility::dieJsonError($db->getError());
            }
        }
        $this->set('msg', Label::getLabel("LBL_Flashcard_Saved_Successfully!"));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function startLessonAuthentication($lessonId)
    {
        $srch = new ScheduledLessonSearch(false);
        $srch->addMultipleFields(
            array(
                'slns.slesson_id'
            )
        );
        $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_date', '=', date('Y-m-d'));
        $srch->addCondition('slns.slesson_start_time', '<=', date('H:i:s'));
        $srch->addCondition('slns.slesson_end_time', '>=', date('H:i:s'));
        $rs = $srch->getResultSet();
        echo $count = $srch->recordCount();
    }

    public function checkEveryMinuteStatus($lessonId)
    {
        $srch = new ScheduledLessonSearch(false);
        $srch->addMultipleFields(
            array(
                'slns.slesson_status'
            )
        );
        //$srch->addCondition( 'slns.slesson_status',' = ', ScheduledLesson::STATUS_SCHEDULED );
        $srch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_date', '<=', date('Y-m-d'));
        $srch->addCondition('slns.slesson_start_time', '<=', date('H:i:s'));
        //$srch->addCondition( 'slns.slesson_end_time',' >= ', date('H:i:s') );
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        echo FatUtility::convertToJson($data);
    }

    public function endLessonSetup()
    {
        $lessonId = FatApp::getPostedData('lessonId', FatUtility::VAR_INT, 0);
        if ($lessonId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->doNotCalculateRecords();
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        $to_time = strtotime($lessonRow['slesson_date'].' '.$lessonRow['slesson_start_time']);
        $from_time = strtotime(date('Y-m-d H:i:s'));
        $diff = round(abs($to_time - $from_time) / 60, 2);
        if ($lessonRow['slesson_date'] == date('Y-m-d') and $diff < FatApp::getConfig('CONF_ALLOW_TEACHER_END_LESSON', FatUtility::VAR_INT, 10)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Cannot_End_Lesson_So_Early!'));
        }
        if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Re-schedule_request_sent_successfully!'));
        }
        if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_COMPLETED) {
            $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
            $sLessonObj->assignValues(array('slesson_teacher_end_time' => date('Y-m-d H:i:s')));
            if (!$sLessonObj->save()) {
                FatUtility::dieJsonError($sLessonObj->getError());
            }
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Already_Ended_by_Learner!'));
        }

        $dataUpdateArr = array(
            'slesson_status' => ScheduledLesson::STATUS_COMPLETED,
            'slesson_ended_by' => User::USER_TYPE_TEACHER,
            'slesson_ended_on' => date('Y-m-d H:i:s'),
            'slesson_teacher_end_time' => date('Y-m-d H:i:s'),
        );

        $db = FatApp::getDb();
        $db->startTransaction();
        if ($lessonRow['slesson_is_teacher_paid'] == 0) {
            $lessonObj = new ScheduledLesson($lessonId);
            if ($lessonObj->payTeacherCommission()) {
                $userNotification = new UserNotifications($lessonRow['teacherId']);
                $userNotification->sendWalletCreditNotification($lessonRow['slesson_id']);
                $dataUpdateArr['slesson_is_teacher_paid'] = 1;
            }
        }
        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->assignValues($dataUpdateArr);
        if (!$sLessonObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        $db->commitTransaction();
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Ended_Successfully!'));
    }

    public function endLessonNotification($lessonId)
    {
        $this->set('lesonId', $lessonId);
        $this->_template->render(false, false);
    }

    public function markTeacherJoinTime()
    {
        $lessonId = FatApp::getPostedData('lessonId', FatUtility::VAR_INT, 0);
        if ($lessonId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $srch = new ScheduledLessonSearch(false);
        $srch->addMultipleFields(
            array(
            'slns.slesson_status',
            'slns.slesson_teacher_join_time'
        )
        );
        $srch->addCondition('slns.slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_teacher_join_time', '=', '0000-00-00');
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        if ($data) {
            $sLessonObj = new ScheduledLesson($lessonId);
            $sLessonObj->assignValues(array('slesson_teacher_join_time' => date('Y-m-d H:i:s')));
            if (!$sLessonObj->save()) {
                FatUtility::dieJsonError($sLessonObj->getError());
            }
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Teacher_Join_Time_Marked!'));
    }
}
