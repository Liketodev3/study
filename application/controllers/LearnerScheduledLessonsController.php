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
        $this->_template->addJs('js/jquery.countdownTimer.min.js');
        $this->_template->addCss('css/jquery.countdownTimer.css');
        $frmSrch = $this->getSearchForm();
        $this->set('frmSrch', $frmSrch);
        
        $lessonStatuses = ScheduledLesson::getStatusArr();
        $lessonStatuses += array(ScheduledLesson::STATUS_ISSUE_REPORTED => Label::getLabel('LBL_Issue_Reported'));
        
        $this->set('lessonStatuses', $lessonStatuses);        
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
        $this->searchLessons($srch, $post, true, false);
        // list on lessons not classes in lessons list, group list is added seperately
        // $srch->addCondition('slesson_grpcls_id', '=', 0);
        if(empty($post['show_group_classes']) || $post['show_group_classes']==ApplicationConstants::NO){
            $srch->addCondition('slesson_grpcls_id', '=', 0);
        }
        // $srch->joinIssueReported(User::USER_TYPE_LEANER);
        $srch->joinIssueReported(UserAuthentication::getLoggedUserId());
		$srch->joinLessonRescheduleLog();
        $srch->addFld(array(
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            'IFNULL(iss.issrep_id,0) AS issrep_id',
			'IFNULL(lrsl.lesreschlog_id,0) as lessonReschedulelogId',
            'IFNULL(iss.issrep_issues_resolve_type,0) AS issrep_issues_resolve_by',
            'CONCAT(slns.slesson_date, " ", slns.slesson_start_time) as startDateTime',
            '(CASE when CONCAT(slns.slesson_date, " ", slns.slesson_start_time) < NOW() then 0 ELSE 1 END ) as upcomingLessonOrder',
            '(CASE when CONCAT(slns.slesson_date, " ", slns.slesson_start_time) < NOW() then CONCAT(slns.slesson_date, " ", slns.slesson_start_time) ELSE NOW() END ) as passedLessonsOrder',
            'sldetail_order_id',
            'slesson_teacher_join_time'
        ));
        if (!empty($post['status']) && $post['status'] == ScheduledLesson::STATUS_RESCHEDULED) {
            $srch->addCondition('lrsl.lesreschlog_id', '>', '0');
            $srch->addCondition('slns.slesson_status', 'IN', [ScheduledLesson::STATUS_SCHEDULED,ScheduledLesson::STATUS_NEED_SCHEDULING]);
        }

		$srch->addOrder('slesson_status', 'ASC');
		$srch->addOrder('upcomingLessonOrder', 'DESC');
		$srch->addOrder('passedLessonsOrder', 'DESC');
		$srch->addOrder('startDateTime', 'ASC');
		$srch->addOrder('slesson_id', 'DESC');

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
        $srch->addGroupBy('slesson_id');
		//echo $srch->getQuery();
		//die;
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

    private function searchLessons(&$srch, $post = array(), $getCancelledOrder = false, $addLessonDateOrder = true)
    {
        $srch = new ScheduledLessonSearch(false);
        $srch->joinGroupClass();
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->joinTeacher();
        $srch->joinLearner();
        $srch->joinTeacherCountry($this->siteLangId);
		$orderIsPaidCondition =  $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
		if($getCancelledOrder) {
			 $orderIsPaidCondition->attachCondition('order_is_paid','=',Order::ORDER_IS_CANCELLED,'OR');
		}
        $srch->addCondition('sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());

        $srch->joinTeacherSettings();
        //$srch->joinTeacherTeachLanguage( $this->siteLangId );
        // $srch->joinTeacherTeachLanguageView( $this->siteLangId );
		if($addLessonDateOrder) {
			 $srch->addOrder('slesson_date', 'ASC');
		}

        $srch->addOrder('slesson_status', 'ASC');
        $srch->addMultipleFields(array(
            'sld.sldetail_id',
            'slns.slesson_id',
            'slns.slesson_grpcls_id',
            'slns.slesson_slanguage_id',
            'slns.slesson_has_issue',
			'order_is_paid',
            'grpcls_title',
            'sldetail_learner_id as learnerId',
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
            'sld.sldetail_learner_status',
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
                $cnd->attachCondition('sldetail_order_id', 'like', '%'.$keyword.'%');
            }
        }

        if (isset($post) && !empty($post['status'])) {
            switch ($post['status']) {
                case ScheduledLesson::STATUS_ISSUE_REPORTED:
                    $srch->addCondition('issrep_id', '>', 0);
                break;
                case ScheduledLesson::STATUS_UPCOMING:
                    $srch->addCondition('mysql_func_CONCAT(slns.slesson_date, " ", slns.slesson_start_time )', '>=', date('Y-m-d H:i:s'), 'AND', true);
                    $srch->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
                    $srch->addCondition('sld.sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
                break;
                case ScheduledLesson::STATUS_RESCHEDULED:
                break;
                default:
                    $srch->addCondition('slns.slesson_status', '=', $post['status']);
                    $srch->addCondition('sld.sldetail_learner_status', '=', $post['status']);
                break;
            }
        }
    }

    public function view($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        if (1 > $lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonDetailRow = ScheduledLessonDetails::getAttributesById($lDetailId, array('sldetail_id', 'sldetail_slesson_id', 'sldetail_learner_id'));
        if (!$lessonDetailRow || $lessonDetailRow['sldetail_id']!=$lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonId = $lessonDetailRow['sldetail_slesson_id'];
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id', 'slesson_grpcls_id'));
        if (!$lessonRow || $lessonRow['slesson_id']!=$lessonId) {
            FatUtility::exitWithErrorCode(404);
        }

        if ($lessonDetailRow['sldetail_learner_id'] != UserAuthentication::getLoggedUserId()) {
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
        $this->set('lessonRow', $lessonRow);
        $this->set('lessonId', $lessonRow['slesson_id']);
        $this->set('lDetailId', $lDetailId);
        $this->_template->render();
    }

    public function viewLessonDetail($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        if (1 > $lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonDetailRow = ScheduledLessonDetails::getAttributesById($lDetailId, array('sldetail_id', 'sldetail_slesson_id', 'sldetail_learner_id'));
        if (!$lessonDetailRow || $lessonDetailRow['sldetail_id']!=$lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonId = $lessonDetailRow['sldetail_slesson_id'];
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id'));
        if (!$lessonRow || $lessonRow['slesson_id']!=$lessonId) {
            FatUtility::exitWithErrorCode(404);
        }

        if ($lessonDetailRow['sldetail_learner_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatApp::redirectUser(CommonHelper::generateUrl('LearnerScheduledLessons'));
        }
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinGroupClass();
		$srch->joinLessonRescheduleLog();
        $srch->doNotCalculateRecords();
        $srch->addCondition('sld.sldetail_id', '=', $lDetailId);
        // $srch->joinIssueReported(User::USER_TYPE_LEANER);
        $srch->joinIssueReported(UserAuthentication::getLoggedUserId());
        $srch->joinLearnerCountry($this->siteLangId);
        $srch->addFld(array(
            'grpcls_title',
            'ul.user_first_name as learnerFname',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
			'IFNULL(lrsl.lesreschlog_id,0) as lessonReschedulelogId',
            'ul.user_url_name',
            'IFNULL(learnercountry_lang.country_name, learnercountry.country_code) as learnerCountryName',
            /* 'ul.user_timezone as learnerTimeZone', */
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            'IFNULL(iss.issrep_id,0) AS issrep_id',
            'IFNULL(iss.issrep_issues_resolve_type,0) AS issrep_issues_resolve_by',
            'slesson_teacher_join_time',
            'sldetail_learner_join_time',
            'sldetail_learner_end_time',
        ));
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (!$lessonRow) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatApp::redirectUser(CommonHelper::generateUrl('LearnerScheduledLessons'));
        }

        $flashCardEnabled = FatApp::getConfig('CONF_ENABLE_FLASHCARD', FatUtility::VAR_BOOLEAN, true);
        if($flashCardEnabled){
            /* flashCardSearch Form[ */
            $frmSrchFlashCard = $this->getLessonFlashCardSearchForm();
            $frmSrchFlashCard->fill(array('lesson_id' => $lessonRow['slesson_id']));
            $this->set('frmSrchFlashCard', $frmSrchFlashCard);
            /* ] */
        }
        
        $this->set('flashCardEnabled', $flashCardEnabled);
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
        // $srch->addCondition('sflashcard_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addOrder('flashcard_id', 'DESC');
        $srch->addMultipleFields(array(
            'flashcard_id',
            'flashcard_created_by_user_id',
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
        $srch->joinGroupClass();
        $srch->addMultipleFields(
            array(
                'slns.slesson_teacher_id',
                'sld.sldetail_learner_id',
                'slns.slesson_date',
                'slns.slesson_end_date',
                'slns.slesson_start_time',
                'slns.slesson_end_time',
                'slns.slesson_status',
                'ut.user_first_name',
                'ut.user_id',
                'ut.user_url_name',
                'grpcls.grpcls_title'
            )
        );
        $srch->addCondition('sld.sldetail_learner_id', ' = ', UserAuthentication::getLoggedUserId());
        $srch->joinTeacher();
        $srch->addGroupBy('slesson_id');
        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $jsonArr = array();
        if (!empty($rows)) {
            $user_timezone = MyDate::getUserTimeZone();
            foreach ($rows as $k=>$row) {
                $slesson_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_date'] .' '. $row['slesson_start_time'], true, $user_timezone);
                $slesson_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $row['slesson_end_date'] .' '. $row['slesson_end_time'], true, $user_timezone);
                $jsonArr[$k] = array(
                    "title" => $row['grpcls_title'] ? $row['grpcls_title'] : $row['user_first_name'],
                    "date" => $slesson_start_time,
                    "start" => $slesson_start_time,
                    "end" => $slesson_end_time,
                    'lid' => $row['sldetail_learner_id'],
                    'liFname' => substr($row['user_first_name'], 0, 1),
                    'classType' => $row['slesson_status'],
                    'className' => $cssClassNamesArr[$row['slesson_status']]
                    );
                if (true == User::isProfilePicUploaded($row['user_id'])) {
                    //$teacherUrl = CommonHelper::generateFullUrl('Teachers','view', array($row['user_id']));
                    $teacherUrl = CommonHelper::generateUrl('Teachers', 'profile').'/'. $row['user_url_name'];
                    $img = CommonHelper::generateFullUrl('Image', 'User', array($row['user_id']));
                    $jsonArr[$k]['imgTag'] = '<a href="'.$teacherUrl.'"><img src="'.$img.'" /></a>';
                } else {
                    $jsonArr[$k]['imgTag'] = '';
                }
            }
        }
        echo FatUtility::convertToJson($jsonArr);
    }

    private function getCancelLessonFrm($deductionNote = false, $showCouponRefundNote = false, $orderId = '')
    {
        $frm = new Form('cancelFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'cancel_lesson_msg', '');
        if ($deductionNote && !$showCouponRefundNote) {
            $frm->addHtml('', 'note_text', '<spam class="-color-primary">'.sprintf(Label::getLabel('LBL_Note:_Refund_Would_Be_%s_Percent.', $this->siteLangId), FatApp::getConfig('CONF_LEARNER_REFUND_PERCENTAGE', FatUtility::VAR_INT, 10)).'</spam>');
        }
        if($showCouponRefundNote) {
            $label = Label::getLabel('LBL_Note:_If_you_cancelled_this_lesson_you_total_order_will_cancelled_becuase_you_puchase_this_order_with_discount.', $this->siteLangId);
            $label .= "<br> ".sprintf(Label::getLabel('LBL_Order_Id:_%s'),$orderId);
            $frm->addHtml('', 'coupon_refund_note_text', '<spam class="-color-primary">'.$label.'</spam>');

        }
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'sldetail_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $submitBtn =  $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Approve'));

		$cancelBtn = $frm->addResetButton('', 'reset', Label::getLabel('LBL_Cancel'));
		$submitBtn->attachField($cancelBtn);
        return $frm;
    }

    public function cancelLesson($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        if (1 > $lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }

        $scheduledLessonObj =  new ScheduledLessonSearch();
        $scheduledLessonObj->joinLearner();
        $scheduledLessonObj->joinOrder();
        $scheduledLessonObj->joinOrderProducts();
        $scheduledLessonObj->addMultipleFields(['slesson_grpcls_id', 'sldetail_learner_id', 'slesson_date','sldetail_order_id','slesson_start_time','op_lpackage_is_free_trial','order_net_amount']);
        $scheduledLessonObj->addCondition('sldetail_id', '=', $lDetailId);
        $scheduledLessonObj->addCondition('sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $scheduledLessonObj->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $resultSet =  $scheduledLessonObj->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($resultSet);
        if (empty($lessonRow)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }

		$lessonRow['order_net_amount'] =   FatUtility::float($lessonRow['order_net_amount']);

        $orderObj =  new Order;
        $orderSearch = $orderObj->getLessonsByOrderId($lessonRow['sldetail_order_id']);
        $orderSearch->addMultipleFields([
            'slesson_grpcls_id',
            'count(sldetail_order_id) as totalLessons',
            'SUM(CASE WHEN sld.sldetail_learner_status = '.ScheduledLesson::STATUS_NEED_SCHEDULING.' THEN 1 ELSE 0 END) needToscheduledLessonsCount',
			'SUM(CASE WHEN sld.sldetail_learner_status = '.ScheduledLesson::STATUS_CANCELLED.' THEN 1 ELSE 0 END) canceledLessonsCount',

        ]);
        $orderSearch->addGroupBy('sldetail_order_id');
        $resultSet = $orderSearch->getResultSet();
        $orderInfo =  FatApp::getDb()->fetch($resultSet);

        if(empty($orderInfo)) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
		$orderInfo['order_discount_total']  = FatUtility::float($orderInfo['order_discount_total']);
		$totalCanceledAndNeedToScheduledCount = $orderInfo['needToscheduledLessonsCount'] + $orderInfo['canceledLessonsCount'];;
        
        if(!empty($orderInfo['order_discount_total']) && $orderInfo['slesson_grpcls_id']==0 && $orderInfo['totalLessons'] != $totalCanceledAndNeedToScheduledCount) {
            Message::addErrorMessage(Label::getLabel('LBL_You_are_not_cancelled_the_lesson_becuase_you_purchase_the_lesson_with_coupon'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $showCouponRefundNote = ($orderInfo['order_discount_total'] > 0) ? true : false;
        $to_time = strtotime($lessonRow['slesson_date'].' '.$lessonRow['slesson_start_time']);
        $from_time = strtotime(date('Y-m-d H:i:s'));
        $diff = round(($to_time - $from_time) / 3600, 2);
        $deductionNote  = false;
        if ($diff < 24 && $lessonRow['order_net_amount'] > 0) {
            $deductionNote = ($lessonRow['op_lpackage_is_free_trial'] == applicationConstants::YES) ? false : true;
        }
        $frm = $this->getCancelLessonFrm($deductionNote, $showCouponRefundNote, $lessonRow['sldetail_order_id']);

        $frm->fill(array('sldetail_id' => $lDetailId));
        $this->set('frm', $frm);
        $this->set('lessonRow', $lessonRow);
        $this->set('showCouponRefundAlert', $showCouponRefundNote);
        $this->_template->render(false, false);
    }

    public function cancelLessonSetup()
    {
        $frm = $this->getCancelLessonFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lDetailId = $post['sldetail_id'];
        $sLessonDetailObj = new ScheduledLessonDetails($lDetailId);
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinLessonLanguage($this->siteLangId);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('sldetail_id', '=', $lDetailId);
        $srch->addCondition('sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $srch->addFld(
            array(
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                'ut.user_timezone as teacherTz',
                // 'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
                'IFNULL(tlanguage_name, tlang.tlanguage_identifier) as teacherTeachLanguageName',
                // '"-" as teacherTeachLanguageName',
                'tcred.credential_email as teacherEmailId',
                'sldetail_learner_status',
                'sldetail_order_id'
            )
        );

        $db = FatApp::getDb();
        $db->startTransaction();

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $statusArray =  [ScheduledLesson::STATUS_COMPLETED , ScheduledLesson::STATUS_ISSUE_REPORTED];
        if (!$lessonRow || in_array($lessonRow['sldetail_learner_status'], $statusArray)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        if($lessonRow['sldetail_learner_status'] == ScheduledLesson::STATUS_CANCELLED) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Lesson_Already_Cancelled'));
        }

        /* ] */
        /* update lesson status[ */
        $sLessonDetailObj = new ScheduledLessonDetails($lessonRow['sldetail_id']);
        $sLessonDetailObj->assignValues(array('sldetail_learner_status' =>	ScheduledLesson::STATUS_CANCELLED));
        if (!$sLessonDetailObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonDetailObj->getError());
        }

        // remove from student google calendar
        $token = current(UserSetting::getUserSettings(UserAuthentication::getLoggedUserId()))['us_google_access_token'];
        if($token){
            $sLessonDetailObj->loadFromDb();
            $oldCalId = $sLessonDetailObj->getFldValue('sldetail_learner_google_calendar_id');

            if($oldCalId){
                SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
            }
            $sLessonDetailObj->setFldValue('sldetail_learner_google_calendar_id', '');
            $sLessonDetailObj->save();
        }
        /* ] */

        /* Also update lesson status for 1 to 1[ */
		$sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
		if($lessonRow['slesson_grpcls_id']<=0){
			$sLessonObj->assignValues(array('slesson_status' =>	ScheduledLesson::STATUS_CANCELLED));
			if (!$sLessonObj->save()) {
				$db->rollbackTransaction();
				FatUtility::dieJsonError($sLessonObj->getError());
			}
		}
        // remove from teacher google calendar
        $token = current(UserSetting::getUserSettings($lessonRow['teacherId']))['us_google_access_token'];
        if($token){
            $sLessonObj->loadFromDb();
            $oldCalId = $sLessonObj->getFldValue('slesson_teacher_google_calendar_id');

            if($oldCalId){
                SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
            }

            $sLessonObj->setFldValue('slesson_teacher_google_calendar_id', '');
            $sLessonObj->save();
        }
        /* ] */

		if (!$sLessonDetailObj->refundToLearner(true, true)) {
			$db->rollbackTransaction();
			FatUtility::dieJsonError($sLessonDetailObj->getError());
		}
       

        $db->commitTransaction();
        /* send email to teacher[ */

        $start_date = $lessonRow['slesson_date'];
        $start_time = $lessonRow['slesson_start_time'];
        $end_time = $lessonRow['slesson_end_time'];

        $user_timezone = $lessonRow['teacherTz'];

        if($start_time){
            $start_time = $start_date.' '.$start_time;
            $end_time = $start_date.' '.$end_time;
            $start_date = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $start_time, true, $user_timezone);
            $start_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $start_time, true, $user_timezone);
            $end_time = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $end_time, true, $user_timezone);
        }

        
        $learnerId = UserAuthentication::getLoggedUserId();
        $userNotification = new UserNotifications($lessonRow['teacherId']);
        $userNotification->cancelLessonNotification($lessonRow['slesson_id'], $learnerId, $lessonRow['learnerFullName'], USER::USER_TYPE_TEACHER, $post['cancel_lesson_msg']);
        
        $vars = array(
            '{learner_name}'    => $lessonRow['learnerFullName'],
            '{teacher_name}'    => $lessonRow['teacherFullName'],
            '{lesson_name}'     => ($lessonRow['is_trial'] == applicationConstants::NO) ? $lessonRow['teacherTeachLanguageName'] : Label::getLabel('LBL_Trial', $this->siteLangId),
            '{learner_comment}' => $post['cancel_lesson_msg'],
            '{lesson_date}'     => FatDate::format($start_date),
            '{lesson_start_time}' => $start_time,
            '{lesson_end_time}' => $end_time,
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
        $fld = $frm->addHiddenField('', 'sldetail_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Send'));
        return $frm;
    }

    public function requestReschedule($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        $db =  FatApp::getDb();

        $getLessonDetailObj =  ScheduledLessonDetails::getLessonDetailSearchObj();
        $getLessonDetailObj->joinOrderProduct();
        $getLessonDetailObj->joinTable(UserSetting::DB_TBL, 'INNER JOIN', 'uts.us_user_id = ut.user_id', 'uts');
        $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('id'),'=', $lDetailId);
        $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('learner_id'),'=', UserAuthentication::getLoggedUserId());
        $getLessonDetailObj->addCondition('order_is_paid','=', Order::ORDER_IS_PAID);
        $getLessonDetailObj->addMultipleFields(
                                    array(
                                    'uts.us_booking_before as teacherBookingBefore',
                                    'ut.user_country_id as teacherCountryId',
                                    'ut.user_first_name as teacherFirstName',
                                    'op_lpackage_is_free_trial',
                                    )
                            );

        $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('learner_status'), '=', ScheduledLesson::STATUS_SCHEDULED);

        $getResultSet =  $getLessonDetailObj->getResultSet();
        $getLessonDetail  = $db->fetch($getResultSet);

        if(empty($getLessonDetail)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $teacherBookingBefore = 0;
        if (!empty($getLessonDetail['teacherBookingBefore'])) {
            $teacherBookingBefore =  $getLessonDetail['teacherBookingBefore'];
        }

        $user_timezone = MyDate::getUserTimeZone();

        $nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
        $userRow = [
            'user_full_name' => $getLessonDetail['teacherFullName'],
            'user_first_name' => $getLessonDetail['teacherFirstName'],
            'user_country_id' => $getLessonDetail['teacherCountryId']
        ];
        $action = ($getLessonDetail['op_lpackage_is_free_trial'] == applicationConstants::YES) ? 'free_trial' : '';
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $frm = $this->getRescheduleFrm();
        $frm->fill(array('sldetail_id' => $lDetailId));
        $this->set('rescheduleRequestfrm', $frm);
        $this->set('teacherBookingBefore', $teacherBookingBefore);
        $this->set('user_timezone', $user_timezone);
        $this->set('nowDate', $nowDate);
        $this->set('userRow', $userRow);
        $this->set('isRescheduleRequest', true);
        $this->set('action', $action);
        $this->set('teacher_id', $getLessonDetail['teacherId']);
        $this->set('lessonId', $getLessonDetail['slesson_id']);
        $this->set('lDetailId', $lDetailId);
        $this->set('cssClassArr', $cssClassNamesArr);
        $this->_template->render(false, false,'learner-scheduled-lessons/view-booking-calendar.php');
    }

    public function requestRescheduleSetup()
    {
        $frm = $this->getRescheduleFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frm->getValidationErrors());
        }
        $lDetailId = $post['sldetail_id'];
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('sldetail_id', '=', $lDetailId);
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

        $sLessonDetailObj = new ScheduledLessonDetails($lessonRow['sldetail_id']);
        $sLessonDetailObj->assignValues(array('sldetail_learner_status' =>	ScheduledLesson::STATUS_NEED_SCHEDULING));
        if (!$sLessonDetailObj->save()) {
            FatUtility::dieJsonError($sLessonDetailObj->getError());
        }

        $sLessonArr = array(
            'slesson_status' => ScheduledLesson::STATUS_NEED_SCHEDULING,
            'slesson_date' => '',
            'slesson_end_date' => '',
            'slesson_start_time' => '',
            'slesson_end_time' => '',
            'slesson_teacher_join_time' => '',
        );

        $lessonId = $lessonRow['slesson_id'];
        $db =  FatApp::getDb();
        $db->startTransaction();
        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->assignValues($sLessonArr);
        if (!$sLessonObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonObj->getError());
        }

        $lessonResLogArr = array(
            'lesreschlog_slesson_id' => $lessonRow['slesson_id'],
            'lesreschlog_reschedule_by' => UserAuthentication::getLoggedUserId(),
            'lesreschlog_user_type' => User::USER_TYPE_LEANER,
            'lesreschlog_comment' => $post['reschedule_lesson_msg'],
        );

        $lessonResLogObj = new LessonRescheduleLog();
        $lessonResLogObj->assignValues($lessonResLogArr);

        if (!$lessonResLogObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($lessonResLogObj->getError());
        }

        $db->commitTransaction();
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

    public function viewBookingCalendar($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        if (1 > $lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonDetailRow = ScheduledLessonDetails::getAttributesById($lDetailId, array('sldetail_id', 'sldetail_slesson_id', 'sldetail_learner_id'));
        if (!$lessonDetailRow || $lessonDetailRow['sldetail_id']!=$lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonId = $lessonDetailRow['sldetail_slesson_id'];
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id', 'slesson_teacher_id'));
        if (!$lessonRow || $lessonRow['slesson_id']!=$lessonId) {
            FatUtility::exitWithErrorCode(404);
        }

        if ($lessonDetailRow['sldetail_learner_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatApp::redirectUser(CommonHelper::generateUrl('LearnerScheduledLessons'));
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
        $this->set('lDetailId', $lDetailId);
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
        $lDetailId = FatApp::getPostedData('lDetailId', FatUtility::VAR_INT, 0);

        if (1 > $lDetailId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $isRescheduleRequest = FatApp::getPostedData('isRescheduleRequest', FatUtility::VAR_INT, 0);
        $rescheduleReason = FatApp::getPostedData('rescheduleReason', FatUtility::VAR_STRING, '');

        $lessonsStatus =  [ScheduledLesson::STATUS_SCHEDULED,ScheduledLesson::STATUS_NEED_SCHEDULING];

        $getLessonDetailObj = ScheduledLessonDetails::getLessonDetailSearchObj();
        $getLessonDetailObj->joinOrderProduct();
        $getLessonDetailObj->joinTable(UserSetting::DB_TBL, 'INNER JOIN', 'uts.us_user_id = ut.user_id', 'uts');

        $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('id'),'=', $lDetailId);
        $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('learner_id'),'=', UserAuthentication::getLoggedUserId());
       // $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('learner_join_time'), '=', 0);
       // $getLessonDetailObj->addCondition('slesson_teacher_join_time', '=', 0);
        $getLessonDetailObj->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);

        $getLessonDetailObj->addCondition(ScheduledLessonDetails::tblFld('learner_status'), 'IN', $lessonsStatus);
        $getLessonDetailObj->addCondition('sldetail_learner_status', 'IN', $lessonsStatus);

        $getLessonDetailObj->addMultipleFields(
                                    array(
                                    'uts.us_booking_before as teacherBookingBefore',
                                    'ut.user_country_id as teacherCountryId',
                                    'ut.user_first_name as teacherFirstName',
                                    'op_lpackage_is_free_trial',
                                    'tcred.credential_email as teacherEmailId',
                                    'ut.user_timezone as teacherTimeZone',
                                    )
                            );
        $getResultSet =  $getLessonDetailObj->getResultSet();
        $lessonDetail  = $db->fetch($getResultSet);
        if(empty($lessonDetail)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        if(($lessonDetail['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) && (empty($isRescheduleRequest) || empty($rescheduleReason))){
            FatUtility::dieJsonError(Label::getLabel('Lbl_Reschedule_Reason_Is_Requried'));
        }

        $teacher_id = $lessonDetail['teacherId'];

        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $startTime = MyDate::changeDateTimezone($post['startTime'], $user_timezone, $systemTimeZone);
        $endTime = MyDate::changeDateTimezone($post['endTime'], $user_timezone, $systemTimeZone);

        $teacherBookingBefore = FatUtility::int($lessonDetail['teacherBookingBefore']);

        $validDate = date('Y-m-d H:i:s', strtotime('+'.$teacherBookingBefore. 'hours', strtotime(date('Y-m-d H:i:s'))));

        $validDateTimeStamp = strtotime($validDate);
        $SelectedDateTimeStamp = strtotime($startTime); //== always should be greater then current date
        $endDateTimeStamp = strtotime($endTime);
        $difference =  $SelectedDateTimeStamp - $validDateTimeStamp; //== Difference should be always greaten then 0
        if ($difference < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Teacher_Disable_the_Booking_before').' '. $teacherBookingBefore .' Hours');
        }

        $userIds  = array($teacher_id, UserAuthentication::getLoggedUserId() );
        $scheduledLessonSearchObj = new ScheduledLessonSearch();
        $scheduledLessonSearchObj->checkUserLessonBooking($userIds, $startTime, $endTime);
        $scheduledLessonSearchObj->setPageSize(1);
        $getResultSet = $scheduledLessonSearchObj->getResultSet();
        $scheduledLessonData = $db->fetchAll($getResultSet);
        if(!empty($scheduledLessonData)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Requested_Slot_is_not_available'));
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $sLessonArr = array(
            'slesson_date' => date('Y-m-d', $SelectedDateTimeStamp),
            'slesson_end_date' => date('Y-m-d', $endDateTimeStamp),
            'slesson_start_time' => date('H:i:s', $SelectedDateTimeStamp),
            'slesson_end_time' => date('H:i:s', $endDateTimeStamp),
            'slesson_status' => ScheduledLesson::STATUS_SCHEDULED,
            'slesson_teacher_join_time' => '',
            'slesson_teacher_end_time' => '',
        );

        $sLessonObj = new ScheduledLesson($lessonDetail['slesson_id']);
        $sLessonObj->assignValues($sLessonArr);
        if (!$sLessonObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonObj->getError());
        }

        $sLessonDetailObj = new ScheduledLessonDetails($lessonDetail['sldetail_id']);
        $sLessonDetailObj->assignValues(array('sldetail_learner_status'=> ScheduledLesson::STATUS_SCHEDULED,
                                                'sldetail_learner_join_time'=>'',
                                                'sldetail_learner_end_time'=>'',
                                            ));
        if (!$sLessonDetailObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonDetailObj->getError());
        }
        
        if($cls = TeacherGroupClassesSearch::getTeacherClassByTime($teacher_id, date('Y-m-d H:i:s', $SelectedDateTimeStamp), date('Y-m-d H:i:s', $endDateTimeStamp))){
            $grpclsId = $cls['grpcls_id'];
            $grpclsObj = new TeacherGroupClasses($grpclsId);
            $grpclsObj->cancelClass();
        }
        $action = ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_SCHEDULED];

        if($lessonDetail['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED && $rescheduleReason) {

            $lessonResLogArr = array(
                'lesreschlog_slesson_id' => $lessonDetail['slesson_id'],
                'lesreschlog_reschedule_by' => UserAuthentication::getLoggedUserId(),
                'lesreschlog_user_type' => User::USER_TYPE_LEANER,
                'lesreschlog_comment' => $rescheduleReason,
            );

            $lessonResLogObj = new LessonRescheduleLog();
            $lessonResLogObj->assignValues($lessonResLogArr);

            if (!$lessonResLogObj->save()) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($lessonResLogObj->getError());
            }

            $action = ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_RESCHEDULED];

        }
        $db->commitTransaction();

        $vars = array(
            '{learner_name}' => $lessonDetail['learnerFullName'],
            '{teacher_name}' => $lessonDetail['teacherFullName'],
            '{lesson_name}' => ($lessonDetail['op_lpackage_is_free_trial'] == applicationConstants::NO) ? $lessonDetail['teacherTeachLanguageName'] : Label::getLabel('LBL_Trial', $this->siteLangId),
            '{lesson_date}' => MyDate::convertTimeFromSystemToUserTimezone('Y-m-d',  date('Y-m-d H:i:s', $SelectedDateTimeStamp),false, $lessonDetail['teacherTimeZone']),
            '{lesson_start_time}' =>  MyDate::convertTimeFromSystemToUserTimezone('H:i:s', date('Y-m-d H:i:s', $SelectedDateTimeStamp), true, $lessonDetail['teacherTimeZone']),
            '{lesson_end_time}' =>  MyDate::convertTimeFromSystemToUserTimezone('H:i:s', date('Y-m-d H:i:s', $endDateTimeStamp), true, $lessonDetail['teacherTimeZone']),
            '{learner_comment}' => $rescheduleReason,
            '{action}' => strtolower($action),
        );

        if (!EmailHandler::sendMailTpl($lessonDetail['teacherEmailId'], 'learner_schedule_email', $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }

        // share on student google calendar
        $token = current(UserSetting::getUserSettings(UserAuthentication::getLoggedUserId()))['us_google_access_token'];
        if($token){
            $sLessonDetailObj->loadFromDb();
            $oldCalId = $sLessonDetailObj->getFldValue('sldetail_learner_google_calendar_id');

            if($oldCalId){
                SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
            }
            $view_url = CommonHelper::generateFullUrl('LearnerScheduledLessons', 'view', array($lessonDetail['sldetail_id']));
            
            $title = sprintf(Label::getLabel('LBL_%1$s_LESSON_%2$s_with_%3$s'), ($lessonDetail['op_lpackage_is_free_trial'] == applicationConstants::NO ? $lessonDetail['teacherTeachLanguageName'] : Label::getLabel('LBL_Trial', $this->siteLangId)), $action, $lessonDetail['teacherFullName']);
            
            $google_cal_data = array(
                'title' => FatApp::getConfig('CONF_WEBSITE_NAME_'.$this->siteLangId),
                'summary' => $title,
                'description' => sprintf(Label::getLabel("LBL_Click_here_to_join_the_lesson:_%s"), $view_url),
                'url' => $view_url,
                'start_time' => date('c', $SelectedDateTimeStamp),
                'end_time' => date('c',$endDateTimeStamp),
                'timezone' => MyDate::getTimeZone(),
            );
            // CommonHelper::printArray($google_cal_data);die;
            $calId = SocialMedia::addEventOnGoogleCalendar($token, $google_cal_data);
            if($calId){
                $sLessonDetailObj->setFldValue('sldetail_learner_google_calendar_id', $calId);
                $sLessonDetailObj->save();
            }
        }

        // share on teacher google calendar
        $token = current(UserSetting::getUserSettings($lessonDetail['teacherId']))['us_google_access_token'];
        if($token){
            $sLessonObj->loadFromDb();
            $oldCalId = $sLessonObj->getFldValue('slesson_teacher_google_calendar_id');

            if($oldCalId){
                SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
            }
            $view_url = CommonHelper::generateFullUrl('TeacherScheduledLessons', 'view', array($lessonDetail['slesson_id']));
            
            $title = sprintf(Label::getLabel('LBL_%1$s_LESSON_Scheduled_by_%2$s'), ($lessonDetail['op_lpackage_is_free_trial'] == applicationConstants::NO ? $lessonDetail['teacherTeachLanguageName'] : Label::getLabel('LBL_Trial', $this->siteLangId)), $lessonDetail['learnerFullName']);
            
            $google_cal_data = array(
                'title' => FatApp::getConfig('CONF_WEBSITE_NAME_'.$this->siteLangId),
                'summary' => $title,
                'description' => sprintf(Label::getLabel("LBL_Click_here_to_deliver_the_lesson:_%s"), $view_url),
                'url' => $view_url,
                'start_time' => date('c', $SelectedDateTimeStamp),
                'end_time' => date('c',$endDateTimeStamp),
                'timezone' => MyDate::getTimeZone(),
            );
            $calId = SocialMedia::addEventOnGoogleCalendar($token, $google_cal_data);
            if($calId){
                $sLessonObj->setFldValue('slesson_teacher_google_calendar_id', $calId);
                $sLessonObj->save();
            }
        }

        $userNotification = new UserNotifications($lessonDetail['teacherId']);
        $userNotification->sendSchLessonByLearnerNotification($lessonDetail['slesson_id']);
        Message::addMessage(Label::getLabel("LBL_Lesson_Scheduled_Successfully"));
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Scheduled_Successfully!'));
    }

    public function viewAssignedLessonPlan($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        if (1 > $lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonDetailRow = ScheduledLessonDetails::getAttributesById($lDetailId, array('sldetail_id', 'sldetail_slesson_id', 'sldetail_learner_id'));
        if (!$lessonDetailRow || $lessonDetailRow['sldetail_id']!=$lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonId = $lessonDetailRow['sldetail_slesson_id'];
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id', 'slesson_teacher_id'));
        if (!$lessonRow || $lessonRow['slesson_id']!=$lessonId) {
            FatUtility::exitWithErrorCode(404);
        }
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
        $arr_options = IssueReportOptions::getOptionsArray($this->siteLangId, User::USER_TYPE_LEANER);
        $fldIssue = $frm->addCheckBoxes(Label::getLabel('LBL_Issue_To_Report'), 'issues_to_report', $arr_options);
        $fldIssue->requirement->setSelectionRange(1, count($arr_options));
        $fldIssue->requirement->setCustomErrorMessage(Label::getLabel('LBL_Issue_To_Report').' '.Label::getLabel('LBL_is_Mandatory'));
        /***************/
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'issue_reported_msg', '');
        $fld->requirement->setRequired(true);
        $fld = $frm->addHiddenField('', 'sldetail_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Send'));
        return $frm;
    }

    public function issueReported($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        $frm = $this->getIssueReportedFrm();
        $frm->fill(array('sldetail_id' => $lDetailId));
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
        $lDetailId = $post['sldetail_id'];
        
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('sldetail_id', '=', $lDetailId);
        $srch->addFld(
            array(
                'sldetail_order_id',
                'ut.user_id as teacher_id',
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
                //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
                '"-" as teacherTeachLanguageName',
                'tcred.credential_email as teacherEmailId'
            )
        );

        $srch->addOrder('sldetail_id', 'DESC');

        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $lessonId = $lessonRow['slesson_id'];
        
        /* [ check If Already reorted */
        if(IssuesReported::isAlreadyReported($lessonId, UserAuthentication::getLoggedUserId())){
            FatUtility::dieJsonError( Label::getLabel('LBL_Issue_Already_Reported') );
        }
        /* ] */

        $reportedArr = array();
        $reportedArr['issrep_comment'] = $post['issue_reported_msg'];
        $reportedArr['issrep_reported_by'] = UserAuthentication::getLoggedUserId();
        $reportedArr['issrep_slesson_id']= $lessonId;
        $reportedArr['issrep_issues_to_report'] = implode(',', $_reason_ids);
        $record = new IssuesReported();
        $record->assignValues($reportedArr);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError($record->getError());
        }

        $schLesDet = new ScheduledLessonDetails($lDetailId);
        $schLesDet->setFldValue('sldetail_learner_status', ScheduledLesson::STATUS_ISSUE_REPORTED);
        $schLesDet->save();

        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->setFldValue('slesson_has_issue', applicationConstants::YES);
        $sLessonObj->save();

        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->holdPayment($lessonRow['teacher_id'], $lessonId, $lessonRow['sldetail_order_id']);
        // $sLessonObj->changeLessonStatus($lessonId, ScheduledLesson::STATUS_ISSUE_REPORTED);
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

    public function issueDetails($issueLessonDetailId)
    {
        $issueLessonDetailId = FatUtility::int($issueLessonDetailId);
        $issueLessonId = ScheduledLessonDetails::getAttributesById($issueLessonDetailId, 'sldetail_slesson_id');
        $srch = IssuesReported::getSearchObject();
        $srch->addCondition('issrep_slesson_id', '=', $issueLessonId);
        $srch->addCondition('issrep_reported_by', '=', UserAuthentication::getLoggedUserId());
        $srch->addMultipleFields(array(
            'i.*',
            'slesson_id',
            'sldetail_learner_id',
            'slesson_date',
            'slesson_start_time',
            'slesson_status',
            'slesson_slanguage_id',
            'op_lesson_duration',
            'op_lpackage_is_free_trial',
        ));
        $srch->addGroupBy('issrep_id');
        $srch->addOrder('issrep_id', 'ASC');

        $rs = $srch->getResultSet();
        $issuesReportedDetails = FatApp::getDb()->fetchAll($rs);
        $this->set('issueDeatils', $issuesReportedDetails);
        $this->set('issues_options', IssueReportOptions::getOptionsArray($this->siteLangId));
        $this->set('resolve_type_options', IssuesReported::getResolveTypeArray());
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

    public function lessonFeedback($lDetailId)
    {
        $lDetailId = FatUtility::int($lDetailId);
        if (1 > $lDetailId) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $userId = UserAuthentication::getLoggedUserId();
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('sldetail_id', '=', $lDetailId);
        $srch->addCondition('sldetail_learner_id', '=', $userId);
        $srch->addCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_COMPLETED);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $lessonId = $lessonRow['slesson_id'];

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
        // $fld = $frm->addHiddenField('', 'sldetail_id', 0);
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
        $lessonId = $post['lessonId'];
        if (1 > $lessonId) {
            FatUtility::exitWithErrorCode(404);
        }

        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id'));
        if (!$lessonRow || $lessonRow['slesson_id']!=$lessonId) {
            FatUtility::exitWithErrorCode(404);
        }

        $frm = $this->getFlashcardFrm();
        $frmData['slesson_id'] = $lessonId;
        if ($flashCardId > 0) {
            $frmData = $frmData + (array)FlashCard::getAttributesById($flashCardId);
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
        /* $lDetailId = $post['sldetail_id'];
        if (1 > $lDetailId) {
            FatUtility::exitWithErrorCode(404);
        }
        $lessonDetailRow = ScheduledLessonDetails::getAttributesById($lDetailId, array('sldetail_id', 'sldetail_slesson_id', 'sldetail_learner_id'));
        if (!$lessonDetailRow || $lessonDetailRow['sldetail_id']!=$lDetailId) {
            FatUtility::exitWithErrorCode(404);
        } */
        $lessonId = $post['slesson_id'];//$lessonDetailRow['sldetail_slesson_id'];
        $lessonRow = ScheduledLesson::getAttributesById($lessonId, array('slesson_id', 'slesson_teacher_id'));
        if (!$lessonRow || $lessonRow['slesson_id']!=$lessonId) {
            FatUtility::exitWithErrorCode(404);
        }

        // anyone can edit
        /* if ($lessonDetailRow['sldetail_learner_id'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Access_Denied'));
            FatApp::redirectUser(CommonHelper::generateUrl('LearnerScheduledLessons'));
        } */

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
                'sflashcard_learner_id' => UserAuthentication::getLoggedUserId(),
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

    public function startLessonAuthentication($lDetailId)
    {
        $srch = new ScheduledLessonSearch(false);
        $srch->addMultipleFields(
            array(
                'slns.slesson_id'
            )
        );
        $srch->addCondition('sld.sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addCondition('sld.sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('sld.sldetail_id', '=', $lDetailId);
        $srch->addCondition('slns.slesson_date', '=', date('Y-m-d'));
        $srch->addCondition('slns.slesson_start_time', '<=', date('H:i:s'));
        $srch->addCondition('slns.slesson_end_time', '>=', date('H:i:s'));
        // echo $srch->getQuery();die;
        $rs = $srch->getResultSet();
        echo $count = $srch->recordCount();
    }

    public function endLessonSetup()
    {
        $lDetailId = FatApp::getPostedData('lDetailId', FatUtility::VAR_INT, 0);
        if ($lDetailId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        /* [ */
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinTeacherCredentials();
        $srch->doNotCalculateRecords();
        $srch->addCondition('sldetail_id', '=', $lDetailId);
        $srch->addMultipleFields(['slesson_teacher_end_time','slesson_ended_by','sldetail_learner_end_time']);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);

        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        if ($lessonRow['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Re-schedule_requested!'));
        }
        
        $dataUpdateArr = [];

        if($lessonRow['slesson_grpcls_id'] == 0 && $lessonRow['sldetail_learner_end_time'] > 0){
            if($lessonRow['slesson_ended_by'] == User::USER_TYPE_LEANER){
                FatUtility::dieJsonError(Label::getLabel('LBL_You_already_end_lesson!'));
            }
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Already_Ended_by_Teacher!'));
        }
        /* ] */
        //
        // if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_COMPLETED) {
        //     $sLessonDetailObj = new ScheduledLessonDetails($lessonRow['sldetail_id']);
        //     $sLessonDetailObj->assignValues(array('sldetail_learner_status' => ScheduledLesson::STATUS_COMPLETED, 'sldetail_learner_end_time'=> date('Y-m-d H:i:s')));
        //     if (!$sLessonDetailObj->save()) {
        //         FatUtility::dieJsonError($sLessonDetailObj->getError());
        //     }
        //     FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Already_Ended_by_Teacher!'));
        // }

        $db = FatApp::getDb();
        $db->startTransaction();

        // if its a 1 to 1 class
        if ($lessonRow['slesson_grpcls_id'] == 0 ){
            $dataUpdateArr = array(
                'slesson_status' => ScheduledLesson::STATUS_COMPLETED,
                'slesson_ended_by' => User::USER_TYPE_LEANER,
                'slesson_ended_on' => date('Y-m-d H:i:s'),
                'slesson_teacher_end_time' => date('Y-m-d H:i:s'),
            );

            $lessonId = $lessonRow['slesson_id'];
            // pay teacher
            if ( $lessonRow['slesson_is_teacher_paid'] == 0) {
                $lessonObj = new ScheduledLesson($lessonId);
                if ($lessonObj->payTeacherCommission()) {
                    $userNotification = new UserNotifications($lessonRow['teacherId']);
                    $userNotification->sendWalletCreditNotification($lessonRow['slesson_id']);
                    $dataUpdateArr['slesson_is_teacher_paid'] = 1;
                }
            }

            // update lesson status details
            $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
            $sLessonObj->assignValues($dataUpdateArr);
            if (!$sLessonObj->save()) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($sLessonObj->getError());
            }

        }

		$lessionDetailUpdateArr = array(
            'sldetail_learner_status' => ScheduledLesson::STATUS_COMPLETED,
			'sldetail_learner_end_time' => date('Y-m-d H:i:s')
		);

		$sLessonDetailObj = new ScheduledLessonDetails($lessonRow['sldetail_id']);
        $sLessonDetailObj->assignValues($lessionDetailUpdateArr);
        if (!$sLessonDetailObj->save()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($sLessonDetailObj->getError());
        }

        $db->commitTransaction();
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Ended_Successfully!'));
    }

    public function checkEveryMinuteStatus($lDetailId)
    {
        $srch = new ScheduledLessonSearch();
        $srch->addMultipleFields(
            array(
                'IF(slns.slesson_teacher_join_time>0, 1, 0) as has_teacher_joined',
                'IF(sld.sldetail_learner_join_time>0, 1, 0) as has_learner_joined',
                'sld.sldetail_learner_status',
                'slns.slesson_status'
            )
        );
        //$srch->addCondition( 'slns.slesson_status',' = ', ScheduledLesson::STATUS_SCHEDULED );
        $srch->addCondition('sld.sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('sld.sldetail_id', '=', $lDetailId);
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
        $date =  FatApp::getPostedData('date',FatUtility::VAR_STRING,'');
        $startTime = FatApp::getPostedData('startTime',FatUtility::VAR_STRING,'');
        $endTime = FatApp::getPostedData('endTime',FatUtility::VAR_STRING,'');
        $teacherId= FatApp::getPostedData('teacherId',FatUtility::VAR_INT,0);

        if(empty($startTime) || empty($endTime) || empty($teacherId) || empty($date)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }


        $startDateTime = MyDate::changeDateTimezone($post['startTime'], $user_timezone, $systemTimeZone);
        $endDateTime = MyDate::changeDateTimezone($post['endTime'], $user_timezone, $systemTimeZone);
        $db = FatApp::getDb();

        $userIds  = array($teacherId, UserAuthentication::getLoggedUserId() );
        $scheduledLessonSearchObj = new ScheduledLessonSearch();
        $scheduledLessonSearchObj->checkUserLessonBooking($userIds, $startDateTime, $endDateTime);
        $scheduledLessonSearchObj->setPageSize(1);
        $getResultSet = $scheduledLessonSearchObj->getResultSet();
        $scheduledLessonData = $db->fetchAll($getResultSet);
        // $srch->addMultipleFields(
        //     array(
        //         'slesson_status',
        //         'sld.sldetail_learner_status'
        //     )
        // );
        // $srch->addCondition('sld.sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        // $srch->addCondition('sld.sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        // $srch->addCondition('slns.slesson_date', '=', date('Y-m-d', strtotime($startDateTime)));
        // $cnd = $srch->addCondition('slns.slesson_start_time', '>=', date('H:i:s', strtotime($startDateTime)), 'AND');
        // $cnd->attachCondition('slns.slesson_start_time', '<=', date('H:i:s', strtotime($endDateTime)), 'AND');
        // $cnd1 = $cnd->attachCondition('slns.slesson_end_time', '>=', date('H:i:s', strtotime($startDateTime)), 'OR');
        // $cnd1->attachCondition('slns.slesson_end_time', '<=', date('H:i:s', strtotime($endDateTime)), 'AND');
        // $rs = $srch->getResultSet();
        // $data = FatApp::getDb()->fetchAll($rs);
        $this->set('count', count($scheduledLessonData));
        $this->_template->render(false, false, 'json-success.php');
    }
    
    private function getStartedLessonDetails($lDetailId)
    {
        $srch = new stdClass();
        $this->searchLessons($srch);
        $srch->joinLearnerCredentials();
        $srch->addMultipleFields(
            array(
                'slns.slesson_teacher_join_time',
                'sld.sldetail_learner_join_time',
                'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
                'lcred.credential_email as learnerEmail',
            )
        );
        $srch->addCondition('sld.sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('sld.sldetail_id', '=', $lDetailId);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetch($rs);
    }
    
    private function getZoomMeetingDetails(array $lessonData) : array
    {
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        $meetingRow = $lessonMeetingDetail->getMeetingDetails(LessonMeetingDetail::KEY_ZOOM_RAW_DATA);
        if(empty($meetingRow)) return array();
        
        $row = json_decode($meetingRow, true);
        if(empty($row)) return array();
        
        $zoom = new Zoom();
        $meetingData = array(
            'id'        => $row['id'],
            'url'       => $row['join_url'],
            'username'  => $lessonData['learnerFullName'],
            'email'     => $lessonData['learnerEmail'],
            'role'      => Zoom::ROLE_ATTENDEE,
            'signature' => $zoom->generateSignature($row['id'], Zoom::ROLE_ATTENDEE)
        );
        return $meetingData;        
    }

    public function startLesson()
    {
        $lDetailId = FatApp::getPostedData('lDetailId', FatUtility::VAR_INT, 0);
        if ($lDetailId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        
        // validate lesson, if it can be started
        $lessonData = $this->getStartedLessonDetails($lDetailId);        
        if(empty($lessonData)){
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Invalid_Request'));
        }
        
        // check if teacher has joined
        if($lessonData['slesson_teacher_join_time']<=0){
            FatUtility::dieJsonError(Label::getLabel("LBL_Please_Wait._Let_teacher_join"));
        }
        
        // get meeting details
        try{
            $lesMettings = new LessonMeetings();        
            $meetingData = $lesMettings->getMeetingData($lessonData);
        }catch(Exception $e){
            CommonHelper::dieJsonError($e->getMessage()); 
        }
        
        // update learner join time
        if($lessonData['sldetail_learner_join_time']<=0){
            $schLessonDetails = new ScheduledLessonDetails($lDetailId);
            if(!$schLessonDetails->markLearnerJoinTime()){
                CommonHelper::dieJsonError($schLessonDetails->getError());
            }
        }
        CommonHelper::dieJsonSuccess(['data' => $meetingData, 'msg' => Label::getLabel('LBL_Joining._Please_Wait...')]);
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
        $srch->joinTable(User::DB_TBL, 'LEFT JOIN', 'ul.user_id = sld.sldetail_learner_id', 'ul');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tLang.tlanguage_id = slns.slesson_slanguage_id', 'tLang');
        $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT JOIN', 'tLangLang.tlanguagelang_tlanguage_id = tLang.tlanguage_id AND tlanguagelang_lang_id = '. $this->siteLangId, 'tLangLang');
        if ($escalated_by == USER::USER_TYPE_TEACHER) {
            $srch->addCondition('slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        } else {
            $srch->addCondition('sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
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
