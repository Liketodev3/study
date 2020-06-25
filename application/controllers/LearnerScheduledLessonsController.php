<?php
class LearnerScheduledLessonsController extends LearnerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->_template->addJs('js/jquery-confirm.min.js');
        $this->_template->addCss('css/jquery-confirm.min.css');
    }

    public function index()
    {
        $this->_template->addJs('js/learnerLessonCommon.js');
        $this->_template->addCss('css/custom-full-calendar.css');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addCss('css/fullcalendar.min.css');
        $this->_template->addCss(array('css/star-rating.css'));
        $this->_template->addJs(array('js/jquery.barrating.min.js'));
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
        $srch->addFld(array(
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            'IFNULL(iss.issrep_id,0) AS issrep_id',
            'IFNULL(iss.issrep_issues_resolve_type,0) AS issrep_issues_resolve_by',
            'CONCAT(slns.slesson_date, " ", slns.slesson_start_time) as startDateTime'
        ));
        // $srch->addOrder('slesson_status', 'ASC');
		$srch->addOrder('startDateTime', 'ASC');
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        if (isset(FatApp::getPostedData()['dashboard'])) {
            $srch->addCondition('slesson_status', ' != ', ScheduledLesson::STATUS_NEED_SCHEDULING);
        }
        /* called from My-teacher detail Page. [ */
        if (isset(FatApp::getPostedData()['teacherId'])) {
            $teacherId = FatApp::getPostedData('teacherId', FatUtility::VAR_INT, 0);
            if ($teacherId > 0) {
                $srch->addCondition('slns.slesson_teacher_id', ' = ', FatApp::getPostedData()['teacherId']);
            }
        }
        /* ] */
        //$srch->addGroupBy('slesson_id');
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
        $this->set('startRecord', $startRecord);
        $this->set('endRecord', $endRecord);
        $this->set('totalRecords', $totalRecords);
        /* ] */
        $teachLanguages = TeachingLanguage::getAllLangs($this->siteLangId);
        $this->set('teachLanguages', $teachLanguages);
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
        $srch->joinTeacherCountry($this->siteLangId);
        $srch->addCondition('slns.slesson_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $srch->joinTeacherSettings();
        //$srch->joinTeacherTeachLanguage( $this->siteLangId );
        // $srch->joinTeacherTeachLanguageView( $this->siteLangId );
        $srch->addOrder('slesson_date', 'ASC');
        $srch->addOrder('slesson_status', 'ASC');
        $srch->addMultipleFields(array(
            'slns.slesson_id',
            'slns.slesson_slanguage_id',
            'slns.slesson_learner_id as learnerId',
            'slns.slesson_teacher_id as teacherId',
            'ut.user_first_name as teacherFname',
            'ut.user_last_name as teacherLname',
            'ut.user_url_name',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
            /* 'ut.user_timezone as teacherTimeZone', */
            'IFNULL(teachercountry_lang.country_name, teachercountry.country_code) as teacherCountryName',
            'slns.slesson_date',
            'slns.slesson_end_date',
            'slns.slesson_start_time',
            'slns.slesson_end_time',
            'slns.slesson_status',
            'slns.slesson_is_teacher_paid',
             '"-" as teacherTeachLanguageName',
            // 'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
            'op_lpackage_is_free_trial as is_trial',
            'op_lesson_duration'
        ));

        if (isset($post) && !empty($post['keyword'])) {
            $keywordsArr = array_unique(array_filter(explode(' ', $post['keyword'])));
            foreach ($keywordsArr as $keyword) {
                $cnd = $srch->addCondition('ut.user_first_name', 'like', '%'.$keyword.'%');
                $cnd->attachCondition('ut.user_last_name', 'like', '%'.$keyword.'%');
            }
        }

        if (isset($post) && !empty($post['status'])) {
            if ($post['status'] == ScheduledLesson::STATUS_ISSUE_REPORTED) {
                $srch->addCondition('issrep_id', '>', 0);
            } elseif ($post['status'] == ScheduledLesson::STATUS_UPCOMING) {
                $srch->addCondition('slns.slesson_date', '>=', date('Y-m-d'));
                $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
            } else {
                $srch->addCondition('slns.slesson_status', '=', $post['status']);
            }
        }
    }

    public function view($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id', 'slesson_learner_id'));
        if (!$lessonRow) {
            FatUtility::exitWithErrorCode(404);
        }

        if ($lessonRow['slesson_learner_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatApp::redirectUser(CommonHelper::generateUrl('LearnerScheduledLessons'));
        }

        $this->_template->addJs('js/learnerLessonCommon.js');
        $this->_template->addCss('css/custom-full-calendar.css');
        $this->_template->addJs('js/moment.min.js');
        $this->_template->addJs('js/fullcalendar.min.js');
        $this->_template->addCss('css/fullcalendar.min.css');
        $this->_template->addJs('js/jquery.countdownTimer.min.js');
        $this->_template->addCss('css/jquery.countdownTimer.css');
        $this->_template->addCss(array('css/star-rating.css'));
        $this->_template->addJs(array('js/jquery.barrating.min.js'));
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
        $srch->joinIssueReported(User::USER_TYPE_LEANER);
        $srch->joinLearnerCountry($this->siteLangId);
        $srch->addFld(array(
            'ul.user_first_name as learnerFname',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
            'ul.user_url_name',
            'IFNULL(learnercountry_lang.country_name, learnercountry.country_code) as learnerCountryName',
            /* 'ul.user_timezone as learnerTimeZone', */
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            'IFNULL(iss.issrep_id,0) AS issrep_id',
            'IFNULL(iss.issrep_issues_resolve_type,0) AS issrep_issues_resolve_by'
        ));
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatApp::redirectUser(CommonHelper::generateUrl('LearnerScheduledLessons'));
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
        $myteacher = (isset(FatApp::getPostedData()['teacherId']))?FatApp::getPostedData()['teacherId']:0;
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
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
        if ($lessonId) {
            $srch->addCondition('sflashcard_slesson_id', '=', $lessonId);
        }
        $srch->addCondition('sflashcard_learner_id', '=', UserAuthentication::getLoggedUserId());
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
        $startRecord = ($page - 1) * $pageSize + 1 ;
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
                'ut.user_first_name',
                'ut.user_id',
                'ut.user_url_name'
            )
        );
        $srch->addCondition('slns.slesson_learner_id', ' = ', UserAuthentication::getLoggedUserId());
        $srch->joinTeacher();
        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $jsonArr = array();
        if (!empty($rows)) {
            $user_timezone = MyDate::getUserTimeZone();
            foreach ($rows as $k=>$row) {
                $slesson_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_date'] .' '. $row['slesson_start_time'], true, $user_timezone);
                $slesson_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_end_date'] .' '. $row['slesson_end_time'], true, $user_timezone);
                $jsonArr[$k] = array(
                    "title" => $row['user_first_name'],
                    "date" => $slesson_start_time,
                    "start" => $slesson_start_time,
                    "end" => $slesson_end_time,
                    'lid' => $row['slesson_learner_id'],
                    'liFname' => substr($row['user_first_name'], 0, 1),
                    'classType' => $row['slesson_status'],
                    'className' => $cssClassNamesArr[$row['slesson_status']]
                    );
                if (true == User::isProfilePicUploaded($row['user_id'])) {
                    //$teacherUrl = CommonHelper::generateFullUrl('Teachers','view', array($row['user_id']));
                    $teacherUrl = CommonHelper::generateUrl('Teachers').'/'. $row['user_url_name'];
                    $img = CommonHelper::generateFullUrl('Image', 'User', array($row['user_id']));
                    $jsonArr[$k]['imgTag'] = '<a href="'.$teacherUrl.'"><img src="'.$img.'" /></a>';
                } else {
                    $jsonArr[$k]['imgTag'] = '';
                }
            }
        }
        echo FatUtility::convertToJson($jsonArr);
    }

    private function getCancelLessonFrm($deductionNote = false)
    {
        $frm = new Form('cancelFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'cancel_lesson_msg', '');
        if ($deductionNote) {
            $frm->addHtml('', 'note_text', '<small>'.sprintf(Label::getLabel('LBL_Note:_Refund_Would_Be_%s_Percent.', $this->siteLangId), FatApp::getConfig('CONF_LEARNER_REFUND_PERCENTAGE', FatUtility::VAR_INT, 10)).'</small>');
        }
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
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_learner_id', 'slesson_date', 'slesson_start_time'));
        if ($lessonRow['slesson_learner_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        $to_time = strtotime($lessonRow['slesson_date'].' '.$lessonRow['slesson_start_time']);
        $from_time = strtotime(date('Y-m-d H:i:s'));
        $diff = round(($to_time - $from_time) / 3600, 2);
        if ($diff<24) {
            $frm = $this->getCancelLessonFrm(true);
        } else {
            $frm = $this->getCancelLessonFrm();
        }
        $frm->fill(array('slesson_id' => $lessonId));
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
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('slesson_id', '=', $lessonId);
        $srch->addFld(
            array(
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
                '"-" as teacherTeachLanguageName',
                'tcred.credential_email as teacherEmailId'
            )
        );

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        /* update lesson status[ */
        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->assignValues(array('slesson_status' =>	ScheduledLesson::STATUS_CANCELLED));
        if (!$sLessonObj->save()) {
            Message::addErrorMessage($sLessonObj->getError());
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        /* ] */

        if (!$sLessonObj->refundToLearner(true)) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        /* send email to teacher[ */
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{learner_comment}' => $post['cancel_lesson_msg'],
            '{lesson_date}' => FatDate::format($lessonRow['slesson_date']),
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time'],
            '{action}' => ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_CANCELLED],
        );
        if (!EmailHandler::sendMailTpl($lessonRow['teacherEmailId'], 'learner_cancelled_email', $this->siteLangId, $vars)) {
            Message::addErrorMessage(Label::getLabel("LBL_Mail_not_sent!!"));
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        /* ] */
        Message::addMessage(Label::getLabel("LBL_Lesson_Cancelled_Successfully!"));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Cancelled_Successfully!'));
    }

    public function getRescheduleFrm()
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
        $frm = $this->getRescheduleFrm();
        $frm->fill(array('slesson_id' => $lessonId));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function requestRescheduleSetup()
    {
        $frm = $this->getRescheduleFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lessonId = $post['slesson_id'];
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('slesson_id', '=', $lessonId);
        $srch->addCondition('slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addFld(
            array(
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
                '"-" as teacherTeachLanguageName',
                'tcred.credential_email as teacherEmailId',
                'tcred.credential_user_id as teacherId'
            )
        );
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $sLessonArr = array(
            'slesson_status' => ScheduledLesson::STATUS_NEED_SCHEDULING,
            'slesson_date' => '',
            'slesson_end_date' => '',
            'slesson_start_time' => '',
            'slesson_end_time' => ''
        );

        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->assignValues($sLessonArr);
        if (!$sLessonObj->save()) {
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        $tpl = 'learner_reschedule_email';
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{learner_comment}' => $post['reschedule_lesson_msg'],
            '{lesson_date}' => $lessonRow['slesson_date'],
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time'],
            '{action}' => Label::getLabel('LBL_Rescheduled'),
        );
        if (!EmailHandler::sendMailTpl($lessonRow['teacherEmailId'], $tpl, $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        $userNotification = new UserNotifications($lessonRow['teacherId']);
        $userNotification->sendSchLessonByLearnerNotification($lessonId, true);
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Re-Scheduled_Successfully!'));
    }

    public function viewBookingCalendar($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_learner_id', 'slesson_teacher_id'));
        if ($lessonRow['slesson_learner_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

        $teacher_id = $lessonRow['slesson_teacher_id'];
        $userRow = User::getAttributesById($teacher_id, array('user_first_name','CONCAT(user_first_name," ",user_last_name) AS user_full_name', 'user_country_id'));
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
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
        $this->set('userRow', $userRow);
        $this->set('action', FatApp::getPostedData('action'));
        $this->set('teacher_id', $teacher_id);
        $this->set('lessonId', $lessonId);
        $this->set('cssClassArr', $cssClassNamesArr);
        $this->_template->render(false, false);
    }

    public function setUpLessonSchedule()
    {
        $post = FatApp::getPostedData();
        $db = FatApp::getDb();
        if (empty($post)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $lessonId = FatApp::getPostedData('lessonId', FatUtility::VAR_INT, 0);
        if ($lessonId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $teacher_id = FatApp::getPostedData('teacherId', FatUtility::VAR_INT, 0);
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $startTime = MyDate::changeDateTimezone($post['date'].' '. $post['startTime'], $user_timezone, $systemTimeZone);
        $endTime = MyDate::changeDateTimezone($post['date'].' '. $post['endTime'], $user_timezone, $systemTimeZone);
        $teacherBookingBefore = current(UserSetting::getUserSettings($teacher_id))['us_booking_before'];
        if ('' == FatUtility::int($teacherBookingBefore)) {
            $teacherBookingBefore = 0;
        }
        $validDate = date('Y-m-d H:i:s', strtotime('+'.$teacherBookingBefore. 'hours', strtotime(date('Y-m-d H:i:s'))));
        $validDateTimeStamp = strtotime($validDate);
        $SelectedDateTimeStamp = strtotime($startTime); //== always should be greater then current date
        $endDateTimeStamp = strtotime($endTime);
        $difference =  $SelectedDateTimeStamp - $validDateTimeStamp; //== Difference should be always greaten then 0
        if ($difference < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Teacher_Disable_the_Booking_before').' '. $teacherBookingBefore .' Hours');
        }
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('slesson_id', '=', $lessonId);
        $srch->joinTeacherTeachLanguage($this->siteLangId);
        $srch->addFld(
            array(
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                'ut.user_timezone as teacherTimeZone',
                'IFNULL(tl_l.tlanguage_name, t_t_lang.tlanguage_identifier) as teacherTeachLanguageName',
                //'"-" as teacherTeachLanguageName',
                'tcred.credential_email as teacherEmailId',
                'tcred.credential_user_id as teacherId',
            )
        );

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $sLessonArr = array(
            'slesson_date' => date('Y-m-d', $SelectedDateTimeStamp),
            'slesson_end_date' => date('Y-m-d', $endDateTimeStamp),
            'slesson_start_time' => date('H:i:s', $SelectedDateTimeStamp),
            'slesson_end_time' => date('H:i:s', $endDateTimeStamp),
            'slesson_status' => ScheduledLesson::STATUS_SCHEDULED
        );

        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->assignValues($sLessonArr);
        if (!$sLessonObj->save()) {
            FatUtility::dieJsonError($sLessonObj->getError());
        }
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{lesson_date}' => MyDate::convertTimeFromSystemToUserTimezone('Y-m-d',  date('Y-m-d H:i:s', $SelectedDateTimeStamp),false, $lessonRow['teacherTimeZone']),
            '{lesson_start_time}' =>  MyDate::convertTimeFromSystemToUserTimezone('H:i:s', date('Y-m-d H:i:s', $SelectedDateTimeStamp), true, $lessonRow['teacherTimeZone']),
            '{lesson_end_time}' =>  MyDate::convertTimeFromSystemToUserTimezone('H:i:s', date('Y-m-d H:i:s', $endDateTimeStamp), true, $lessonRow['teacherTimeZone']),
            '{learner_comment}' => '',
            '{action}' => ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_SCHEDULED],
        );

        if (!EmailHandler::sendMailTpl($lessonRow['teacherEmailId'], 'learner_schedule_email', $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }

        $userNotification = new UserNotifications($lessonRow['teacherId']);
        $userNotification->sendSchLessonByLearnerNotification($lessonId);
        Message::addMessage(Label::getLabel("LBL_Lesson_Scheduled_Successfully"));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Scheduled_Successfully!'));
    }

    public function viewAssignedLessonPlan($lessonId)
    {
        $lessonId = FatUtility::int($lessonId);
        $srch = new LessonPlanSearch(false);
        $srch->addMultipleFields(array(
            'tlpn_id',
            'tlpn_title',
            'tlpn_level',
            'tlpn_user_id',
            'tlpn_tags',
            'tlpn_description',
        ));
        $srch->joinTable('tbl_scheduled_lessons_to_teachers_lessons_plan', 'inner join', 'tlpn_id = ltp_tlpn_id');
        //$srch->addCondition( 'tlpn_user_id',' = ', UserAuthentication::getLoggedUserId() );
        $srch->addCondition('ltp_slessonid', '=', $lessonId);
        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetch($rs);
        $this->set('statusArr', LessonPlan::getDifficultyArr());
        $this->set('data', $rows);
        $this->_template->render(false, false);
    }

    public function getIssueReportedFrm()
    {
        $frm = new Form('issueReportedFrm');
        /***************/
        $arr_options = IssueReportOptions::getOptionsArray($this->siteLangId);
        $fldIssue = $frm->addCheckBoxes(Label::getLabel('LBL_Issue_To_Report'), 'issues_to_report', $arr_options);
        $fldIssue->requirement->setSelectionRange(1, count($arr_options));
        $fldIssue->requirement->setCustomErrorMessage(Label::getLabel('LBL_Issue_To_Report').' '.Label::getLabel('LBL_is_Mandatory'));
        /***************/
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'issue_reported_msg', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'slesson_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Send'));
        return $frm;
    }

    public function issueReported($lesson_id)
    {
        $lesson_id = FatUtility::int($lesson_id);
        $frm = $this->getIssueReportedFrm();
        $frm->fill(array('slesson_id' => $lesson_id));
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

        $_reason_ids = $post['issues_to_report'];
        if (empty($_reason_ids)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Please_Choose_Issue_to_Report'));
        }
        $lessonId = $post['slesson_id'];
        /* [ check If Already reorted */
        /* if(IssuesReported::isAlreadyReported($lessonId,User::USER_TYPE_LEANER)){
            FatUtility::dieJsonError( Label::getLabel('LBL_Issue_Already_Reported') );
        } */
        /* ] */

        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('slesson_id', '=', $lessonId);
        $srch->addFld(
            array(
                'ut.user_id as teacher_id',
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
                '"-" as teacherTeachLanguageName',
                'tcred.credential_email as teacherEmailId'
            )
        );

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $reportedArr = array();
        $reportedArr['issrep_comment'] = $post['issue_reported_msg'];
        $reportedArr['issrep_reported_by'] = User::USER_TYPE_LEANER;
        $reportedArr['issrep_slesson_id']= $lessonId;
        $reportedArr['issrep_issues_to_report'] = implode(',', $_reason_ids);
        $record = new IssuesReported();
        $record->assignValues($reportedArr);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError($record->getError());
        }

        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->holdPayment($lessonRow['teacher_id'], $lessonId);
        $sLessonObj->changeLessonStatus($lessonId, ScheduledLesson::STATUS_ISSUE_REPORTED);
        $reason_html = '';
        $issues_options = IssueReportOptions::getOptionsArray($this->siteLangId);
        foreach ($_reason_ids as $_id) {
            $reason_html .= $issues_options[$_id].'<br />';
        }
        /* [ */
        $tpl = 'learner_issue_reported_email';
        $vars = array(
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{lesson_issue_reason}' => $reason_html,
            '{learner_comment}' => $post['issue_reported_msg'],
            '{lesson_date}' => $lessonRow['slesson_date'],
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time'],
            '{action}' => ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_ISSUE_REPORTED],
        );

        if (!EmailHandler::sendMailTpl($lessonRow['teacherEmailId'], $tpl, $this->siteLangId, $vars)) {
            Message::addErrorMessage(Label::getLabel('LBL_Mail_not_sent!', $this->siteLangId));
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        /* ] */

        $userNotification = new UserNotifications($lessonRow['teacherId']);
        $userNotification->sendIssueRefundNotification($lessonId, IssuesReported::ISSUE_REPORTED_NOTIFICATION);
        Message::addMessage(Label::getLabel('LBL_Lesson_Issue_Reported_Successfully!', $this->siteLangId));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Issue_Reported_Successfully!'));
    }

    public function issueDetails($issueLessonId)
    {
        $issueLessonId = FatUtility::int($issueLessonId);
        $srch = IssuesReported::getSearchObject();
        $srch->addCondition('issrep_slesson_id', '=', $issueLessonId);
        $srch->addMultipleFields(array(
            'i.*',
            'slesson_id',
            'slesson_learner_id',
            'slesson_date',
            'slesson_start_time',
            'slesson_status',
            'slesson_slanguage_id',
            'op_lesson_duration',
            'op_lpackage_is_free_trial',
        ));
        $srch->addOrder('issrep_id', 'ASC');
        $rs = $srch->getResultSet();
        $issuesReportedDetails = FatApp::getDb()->fetchAll($rs);
        $this->set('issueDeatils', $issuesReportedDetails);
        $this->set('issues_options', IssueReportOptions::getOptionsArray($this->siteLangId));
        $this->set('resolve_type_options', IssuesReported::RESOLVE_TYPE);
        $this->_template->render(false, false);
    }
    private function getLessonFeedbackForm($lessonId, $langId)
    {
        $frm = new Form('frmLessonFeedback');
        $ratingAspects = TeacherLessonRating::getRatingAspectsArr();
        foreach ($ratingAspects as $aspectVal => $aspectLabel) {
            $fld = $frm->addSelectBox($aspectLabel, "review_rating[$aspectVal]", array(
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4",
                "5" => "5"
            ), "", array('class' => "star-rating"), Label::getLabel('L_Rate'));
            $fld->requirements()->setRequired(true);
            $fld->setWrapperAttribute('class', 'rating-f');
        }
        $frm->addRequiredField(Label::getLabel('LBL_Title'), 'tlreview_title');
        $frm->addTextArea(Label::getLabel('LBL_Description'), 'tlreview_description')->requirements()->setRequired();
        $frm->addHiddenField('', 'tlreview_lesson_id', $lessonId);
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Send_Review'));
        return $frm;
    }

    public function lessonFeedback($lessonId = 0)
    {
        $lessonId = FatUtility::int($lessonId);
        if (1 > $lessonId) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $userId = UserAuthentication::getLoggedUserId();
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('slesson_id', '=', $lessonId);
        $srch->addCondition('slesson_learner_id', '=', $userId);
        $srch->addCondition('slesson_status', '=', ScheduledLesson::STATUS_COMPLETED);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $lFeedbackSrch = new TeacherLessonReviewSearch();
        $lFeedbackSrch->doNotCalculateRecords();
        $lFeedbackSrch->doNotLimitRecords();
        $lFeedbackSrch->addCondition('tlreview_postedby_user_id', '=', $userId);
        $lFeedbackSrch->addCondition('tlreview_lesson_id', '=', $lessonId);
        $lFeedbackRs = $lFeedbackSrch->getResultSet();
        if (FatApp::getDb()->fetch($lFeedbackRs)) {
            Message::addErrorMessage(Label::getLabel('MSG_Already_submitted_order_feedback', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getLessonFeedbackForm($lessonId, $this->siteLangId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setupLessonFeedback()
    {
        $lessonId = FatApp::getPostedData('tlreview_lesson_id', FatUtility::VAR_INT, 0);
        if (1 > $lessonId) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        $userId = UserAuthentication::getLoggedUserId();
        $row = ScheduledLesson::getAttributesById($lessonId, array('slesson_teacher_id'));
        $teacherId = $row['slesson_teacher_id'];
        $frm = $this->getLessonFeedbackForm($lessonId, $this->siteLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            $this->lessonFeedback($lessonId);
            return true;
        }
        $post['tlreview_teacher_user_id'] = $teacherId;
        $post['tlreview_postedby_user_id'] = $userId;
        $post['tlreview_posted_on'] = date('Y-m-d H:i:s');
        $post['tlreview_lang_id'] = $this->siteLangId;
        $post['tlreview_status'] = FatApp::getConfig('CONF_DEFAULT_REVIEW_STATUS', FatUtility::VAR_INT, 0);
        $selProdReview = new TeacherLessonReview();
        $selProdReview->assignValues($post);
        $db = FatApp::getDb();
        $db->startTransaction();
        if (!$selProdReview->save()) {
            Message::addErrorMessage($selProdReview->getError());
            $db->rollbackTransaction();
            $this->lessonFeedback($lessonId);
            return true;
        }
        $spreviewId = $selProdReview->getMainTableRecordId();
        $ratingsPosted = FatApp::getPostedData('review_rating');
        $ratingAspects = TeacherLessonRating::getRatingAspectsArr($this->siteLangId);
        foreach ($ratingsPosted as $ratingAspect => $ratingValue) {
            if (isset($ratingAspects[$ratingAspect])) {
                $selProdRating = new TeacherLessonRating();
                $ratingRow = array(
                    'tlrating_tlreview_id' => $spreviewId,
                    'tlrating_rating_type' => $ratingAspect,
                    'tlrating_rating' => $ratingValue
                );
                $selProdRating->assignValues($ratingRow);
                if (!$selProdRating->save()) {
                    Message::addErrorMessage($selProdRating->getError());
                    $db->rollbackTransaction();
                    $this->lessonFeedback($lessonId);
                    return true;
                }
            }
        }
        $db->commitTransaction();
        Message::addMessage(Label::getLabel('MSG_Feedback_Submitted_Successfully', $this->siteLangId));
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_Feedback_Submitted_Successfully', $this->siteLangId));
        //FatApp::redirectUser(CommonHelper::generateUrl('Buyer','Orders'));
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
        $srch->addCondition('sflashcard_learner_id', '=', UserAuthentication::getLoggedUserId());
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
        $srch->addCondition('sflashcard_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('flashcard_id', '=', $flashCardId);
        $srch->addMultipleFields(array(
            'flashcard_id',
            'sflashcard_learner_id',
            'sflashcard_teacher_id'
        ));
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (empty($row)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        $db = FatApp::getDb();
        if (!$db->deleteRecords(FlashCard::DB_TBL_SHARED, array(
            'smt'=>'sflashcard_flashcard_id = ?',
            'vals'=>array($row['flashcard_id'])
        ))) {
            FatUtility::dieJsonError($db->getError());
        }
        if (!$db->deleteRecords(FlashCard::DB_TBL, array(
            'smt'=>'flashcard_id = ? ',
            'vals'=>array($row['flashcard_id'])
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
        if (empty($lessonRow) || $lessonRow['slesson_learner_id'] != UserAuthentication::getLoggedUserId()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $flashCardId = $post['flashcard_id'];
        $post['flashcard_user_id'] = UserAuthentication::getLoggedUserId();
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
        $srch->addCondition('slns.slesson_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_date', '=', date('Y-m-d'));
        $srch->addCondition('slns.slesson_start_time', '<=', date('H:i:s'));
        $srch->addCondition('slns.slesson_end_time', '>=', date('H:i:s'));
        $rs = $srch->getResultSet();
        echo $count = $srch->recordCount();
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
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('slesson_id', '=', $lessonId);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* ] */
        if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Re-schedule_requested!'));
        }
        if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_COMPLETED) {
            $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
            $sLessonObj->assignValues(array('slesson_learner_end_time'=> date('Y-m-d H:i:s')));
            if (!$sLessonObj->save()) {
                FatUtility::dieJsonError($sLessonObj->getError());
            }
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Already_Ended_by_Teacher!'));
        }

        $dataUpdateArr = array(
            'slesson_status' => ScheduledLesson::STATUS_COMPLETED,
            'slesson_ended_by' => User::USER_TYPE_LEANER,
            'slesson_ended_on' => date('Y-m-d H:i:s'),
            'slesson_learner_end_time' => date('Y-m-d H:i:s'),
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

    public function checkEveryMinuteStatus($lessonId)
    {
        $srch = new ScheduledLessonSearch();
        $srch->addMultipleFields(
            array(
                'slns.slesson_status'
            )
        );
        //$srch->addCondition( 'slns.slesson_status',' = ', ScheduledLesson::STATUS_SCHEDULED );
        $srch->addCondition('slns.slesson_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_date', '<=', date('Y-m-d'));
        $srch->addCondition('slns.slesson_start_time', '<=', date('H:i:s'));
        //$srch->addCondition( 'slns.slesson_end_time',' >= ', date('H:i:s') );
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        echo FatUtility::convertToJson($data);
    }

    public function isSlotTaken()
    {
        $post = FatApp::getPostedData();
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $startDateTime = MyDate::changeDateTimezone($post['date'].' '. $post['startTime'], $user_timezone, $systemTimeZone);
        $endDateTime = MyDate::changeDateTimezone($post['date'].' '.$post['endTime'], $user_timezone, $systemTimeZone);
        $db = FatApp::getDb();
        if (empty($post)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $srch = new ScheduledLessonSearch();
        $srch->addMultipleFields(
            array(
                'slns.slesson_status'
            )
        );
        $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addCondition('slns.slesson_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_date', '=', date('Y-m-d', strtotime($startDateTime)));
        $cnd = $srch->addCondition('slns.slesson_start_time', '>=', date('H:i:s', strtotime($startDateTime)), 'AND');
        $cnd->attachCondition('slns.slesson_start_time', '<=', date('H:i:s', strtotime($endDateTime)), 'AND');
        $cnd1 = $cnd->attachCondition('slns.slesson_end_time', '>=', date('H:i:s', strtotime($startDateTime)), 'OR');
        $cnd1->attachCondition('slns.slesson_end_time', '<=', date('H:i:s', strtotime($endDateTime)), 'AND');
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetchAll($rs);
        $this->set('count', count($data));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function markLearnerJoinTime()
    {
        $lessonId = FatApp::getPostedData('lessonId', FatUtility::VAR_INT, 0);
        if ($lessonId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $srch = new ScheduledLessonSearch(false);
        $srch->addMultipleFields(
            array(
                'slns.slesson_status',
                'slns.slesson_learner_join_time'
            )
        );
        $srch->addCondition('slns.slesson_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('slns.slesson_id', '=', $lessonId);
        $srch->addCondition('slns.slesson_learner_join_time', '=', '0000-00-00');
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        if ($data) {
            $sLessonObj = new ScheduledLesson($lessonId);
            $sLessonObj->assignValues(array('slesson_learner_join_time' => date('Y-m-d H:i:s')));
            if (!$sLessonObj->save()) {
                FatUtility::dieJsonError($sLessonObj->getError());
            }
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Learner_Join_Time_Marked!'));
    }

    public function reportIssueToAdmin($issueId, $lessonId, $escalated_by)
    {
        $reportedArr = array();
        $reportedArr['issrep_status'] = IssuesReported::STATUS_PROGRESS;
        $reportedArr['issrep_is_for_admin'] = 1;
        $reportedArr['issrep_escalated_by'] = $escalated_by;
        $record = new IssuesReported($issueId);
        $record->assignValues($reportedArr);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError($record->getError());
        }
        $srch = new ScheduledLessonSearch();
        $srch->addCondition('slesson_id', '=', $lessonId);
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'ut.user_id = slns.slesson_teacher_id', 'ut');
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'ul.user_id = slns.slesson_learner_id', 'ul');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tLang.tlanguage_id = slns.slesson_slanguage_id', 'tLang');
        $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT JOIN', 'tLangLang.tlanguagelang_tlanguage_id = tLang.tlanguage_id AND tlanguagelang_lang_id = '. $this->siteLangId, 'tLangLang');
        if ($escalated_by == USER::USER_TYPE_TEACHER) {
            $srch->addCondition('slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        } else {
            $srch->addCondition('slesson_learner_id', '=', UserAuthentication::getLoggedUserId());
        }

        $srch->addFld(
            array(
                'slns.*',
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                'IFNULL(tLangLang.tlanguage_name, tLang.tlanguage_identifier) as teacherTeachLanguageName'
            )
        );

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if ($escalated_by == USER::USER_TYPE_TEACHER) {
            $escalated_by_user = $lessonRow['teacherFullName'];
        } else {
            $escalated_by_user = $lessonRow['learnerFullName'];
        }

        $tpl = 'admin_new_issue_reported_email';
        $vars = array(
            '{escalated_by}' => $escalated_by_user,
            '{learner_name}' => $lessonRow['learnerFullName'],
            '{teacher_name}' => $lessonRow['teacherFullName'],
            '{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
            '{lesson_date}' => $lessonRow['slesson_date'],
            '{lesson_start_time}' => $lessonRow['slesson_start_time'],
            '{lesson_end_time}' => $lessonRow['slesson_end_time']
        );

        if (!EmailHandler::sendMailTpl(FatApp::getConfig('CONF_SITE_OWNER_EMAIL', FatUtility::VAR_STRING, 'yocoach@dummyid.com'), $tpl, $this->siteLangId, $vars)) {
            Message::addErrorMessage(Label::getLabel('LBL_Mail_not_sent!', $this->siteLangId));
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }

        Message::addMessage(Label::getLabel('LBL_Lesson_Issue_Reported_to_the_Support', $this->siteLangId));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Issue_Reported_to_the_Support!'));
    }
}
