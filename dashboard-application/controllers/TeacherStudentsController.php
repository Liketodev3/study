<?php

class TeacherStudentsController extends TeacherBaseController
{

    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $frmSrch = $this->getSearchForm();
        $this->set('frmSrch', $frmSrch);
        $this->_template->render();
    }

    public function search()
    {
        $frmSrch = $this->getSearchForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }
        $teacherOfferPriceSrch = new SearchBase(TeacherOfferPrice::DB_TBL);
        $teacherOfferPriceSrch->addMultipleFields([
            'top_learner_id', 'top_teacher_id',
            'GROUP_CONCAT(top_percentage ORDER BY top_lesson_duration) as percentages',
            'GROUP_CONCAT(top_lesson_duration ORDER BY top_lesson_duration) as lessonDuration'
        ]);
        $teacherOfferPriceSrch->addGroupBy('top_learner_id');
        $teacherOfferPriceSrch->doNotLimitRecords();
        $teacherOfferPriceSrch->doNotCalculateRecords();
        
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);

        $srch = new ScheduledLessonSearch(false);
        $srch->addMultipleFields(['slns.slesson_id',
            'sld.sldetail_learner_id as learnerId',
            'slns.slesson_teacher_id as teacherId',
            'ul.user_first_name as learnerFname',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
            'COUNT(IF(slns.slesson_status = "' . ScheduledLesson::STATUS_SCHEDULED . '", 1, null)) as scheduledLessonCount',
            'COUNT(IF(slns.slesson_status = "' . ScheduledLesson::STATUS_NEED_SCHEDULING . '",1,null)) as unScheduledLessonCount',
            'COUNT(IF(CONCAT(slesson_date, " ", slesson_start_time) < "' . date('Y-m-d H:i:s') . '" AND slns.slesson_status!=' . ScheduledLesson::STATUS_CANCELLED . ' AND slns.slesson_date != "0000-00-00", 1, null)) as pastLessonCount',
            'CASE WHEN percentages IS NULL THEN 0 ELSE 1 END as isSetUpOfferPrice',
            'percentages',
            'lessonDuration',
        ]);
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->joinLearner();
        $srch->joinTeacher();
        $srch->joinUserTeachLanguages($this->siteLangId);
        $srch->joinTable('(' . $teacherOfferPriceSrch->getQuery() . ')', 'LEFT JOIN', 'sldetail_learner_id = top_learner_id AND top_teacher_id = slesson_teacher_id', 'top');
        $srch->addCondition('slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('sldetail_learner_status', '!=', ScheduledLesson::STATUS_CANCELLED);
        $srch->addGroupBy('sldetail_learner_id', 'slesson_status');
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $srch->addOrder('order_date_added', 'DESC');
        $srch->addOrder('ul.user_first_name');
        if (!empty($post['keyword'])) {
            $keywordsArr = array_unique(array_filter(explode(' ', $post['keyword'])));
            foreach ($keywordsArr as $keyword) {
                $cnd = $srch->addCondition('ul.user_first_name', 'like', '%' . $keyword . '%');
                $cnd->attachCondition('ul.user_last_name', 'like', '%' . $keyword . '%');
            }
        }
        $rs = $srch->getResultSet();
        $students = FatApp::getDb()->fetchAll($rs);
        $this->set('students', $students);
        /* [ */
        $totalRecords = $srch->recordCount();
        $pagingArr = [
            'pageCount' => $srch->pages(),
            'page' => $page,
            'pageSize' => $pageSize,
            'recordCount' => $totalRecords,
        ];
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
        /* ] */
        $this->_template->render(false, false);
    }

    public function offerForm()
    {
        $teacherId = UserAuthentication::getLoggedUserId();
        $learnerId = FatApp::getPostedData('top_learner_id', FatUtility::VAR_INT, 0);
        $teacherOffer = new TeacherOfferPrice();
        $offerData = $teacherOffer->getTeacherOffer($learnerId, $teacherId);
        $teacherOfferData = ['top_learner_id' => $learnerId];
        $isOfferSet =  (!empty($offerData));

        foreach ($offerData as $offer) {
            $teacherOfferData['top_percentage'][$offer['top_lesson_duration']] = $offer['top_percentage'];
        }

        $teachLangPrice = new TeachLangPrice();
        $userSlots = $teachLangPrice->getTeachingSlots($teacherId);

        $frm = $this->getOfferForm($userSlots);
        $frm->fill($teacherOfferData);
        $this->set('frm', $frm);
     
        $this->set('userSlots', $userSlots);
        $this->set('isOfferSet', $isOfferSet);
        $this->set('user_info', User::getAttributesById($learnerId, ['user_id', 'user_first_name', 'user_last_name']));
        $this->_template->render(false, false);
    }

    public function setUpOffer()
    {
        $frmSrch = $this->getOfferForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError($frmSrch->getValidationErrors());
        }
        $teacherId = UserAuthentication::getLoggedUserId();
        $learnerId = $post['top_learner_id'];
      
        foreach ($post['top_percentage'] as $lesonDuration => $offer) {
            $teacherOfferPrice = new TeacherOfferPrice($teacherId, $learnerId);
            if (!$teacherOfferPrice->saveOffer($offer, $lesonDuration)) {
                FatUtility::dieJsonError($teacherOfferPrice->getError());
            }
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Price_Locked_Successfully!'));
    }

    private function getOfferForm(array $userSlots = null)
    {
        if($userSlots == null){
            $teacherId = UserAuthentication::getLoggedUserId();
            $teachLangPrice = new TeachLangPrice();
            $userSlots = $teachLangPrice->getTeachingSlots($teacherId);
        }
        $frm = new Form('frmOfferPrice');
        foreach ($userSlots as $slot) {
            $label = str_replace('{slot}', $slot, Label::getLabel('LBL_{slot}_slot_Offer(%)'));
            $fld = $frm->addRequiredField($label, 'top_percentage[' . $slot . ']');
            $fld->requirements()->setFloatPositive();
            $fld->requirements()->setRange(1, 100);
        }
        $fld = $frm->addHiddenField('', 'top_learner_id');
        $fld->requirements()->setInt();
        $fld->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save'));
        return $frm;
    }

    public function unlockOffer()
    {
        $learnerId = FatApp::getPostedData('learnerId', FatUtility::VAR_INT, 0);
        $teacherId = UserAuthentication::getLoggedUserId();
        if ($learnerId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        $teacherOfferPrice = new TeacherOfferPrice($teacherId, $learnerId);
        if (!$teacherOfferPrice->removeOffer()) {
            FatUtility::dieJsonError($teacherOfferPrice->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Price_Unlocked_Successfully!'));
    }

    public function getMessageToLearnerFrm()
    {
        $frm = new Form('messageToLearnerFrm');
        $fld = $frm->addTextArea('Comment', 'msg_to_learner', '', ['style' => 'width:300px;']);
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'submit', 'Send');
        return $frm;
    }

    public function sendMessageToLearner($learnerId = 0)
    {
        $learnerId = FatUtility::int($learnerId);
        $frm = $this->getMessageToLearnerFrm();
        $frm->addHiddenField('', 'slesson_learner_id', $learnerId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function messageToLearnerSetup()
    {
        $db = FatApp::getDb();
        $post = FatApp::getPostedData();
        if (empty($post)) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $learnerId = $post['slesson_learner_id'];
        $teacherData = User::getAttributesById(UserAuthentication::getLoggedUserId(), ['user_first_name', 'user_last_name']);
        $learnerData = User::getAttributesById($learnerId, ['user_first_name', 'user_last_name']);
        $userSrch = User::getSearchObject(true);
        $userSrch->addMultipleFields(['credential_email']);
        $userSrch->addCondition('credential_user_id', '=', $learnerId);
        $userRs = $userSrch->getResultSet();
        $userData = $db->fetch($userRs);
        $tpl = 'teacher_message_to_learner_email';
        $vars = [
            '{learner_name}' => $learnerData['user_first_name'] . " " . $learnerData['user_last_name'],
            '{teacher_name}' => $teacherData['user_first_name'] . " " . $teacherData['user_last_name'],
            '{teacher_message}' => $post['msg_to_learner'],
            '{action}' => 'Message To Learner',
        ];
        if (!EmailHandler::sendMailTpl($userData['credential_email'], $tpl, $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Message_Sent_Successfully!'));
    }

}
