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
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->_template->render();
    }

    public function search()
    {
        $frmSrch = $this->getSearchForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }
        $srch = new ScheduledLessonSearch(false);
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->joinLearner();
        $srch->joinTeacher();
        $srch->joinTeacherSettings();
        $srch->joinTeacherTeachLanguageView($this->siteLangId);
        $srch->joinTeacherOfferPrice(UserAuthentication::getLoggedUserId());
        $srch->addCondition('slesson_teacher_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addGroupBy('sldetail_learner_id', 'slesson_status');
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $srch->addOrder('order_date_added', 'DESC');
        $srch->addOrder('ul.user_first_name');
        $srch->addMultipleFields(array(
            'slns.slesson_id',
            'sld.sldetail_learner_id as learnerId',
            'slns.slesson_teacher_id as teacherId',
            'ul.user_first_name as learnerFname',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
            //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
            'COUNT(IF(slns.slesson_status="'.ScheduledLesson::STATUS_SCHEDULED.'",1,null)) as scheduledLessonCount',
            'COUNT(IF(slns.slesson_status="'.ScheduledLesson::STATUS_NEED_SCHEDULING.'",1,null)) as unScheduledLessonCount',
            'COUNT(IF(slns.slesson_date < "'.date('Y-m-d').'"  AND slns.slesson_date != "0000-00-00", 1, null)) as pastLessonCount',
            'CASE WHEN top_single_lesson_price IS NULL THEN 0 ELSE 1 END as isSetUpOfferPrice',
            'IFNULL(top_single_lesson_price, ts.us_single_lesson_amount ) as singleLessonAmount',
            'IFNULL(top_bulk_lesson_price, ts.us_bulk_lesson_amount ) as bulkLessonAmount',
        ));

        if (!empty($post['keyword'])) {
            $keywordsArr = array_unique(array_filter(explode(' ', $post['keyword'])));
            foreach ($keywordsArr as $keyword) {
                $cnd = $srch->addCondition('ul.user_first_name', 'like', '%'.$keyword.'%');
                $cnd->attachCondition('ul.user_last_name', 'like', '%'.$keyword.'%');
            }
        }

        $rs = $srch->getResultSet();
        $students = FatApp::getDb()->fetchAll($rs);
        $this->set('students', $students);
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
        /* ] */
        $this->_template->render(false, false);
    }

    public function offerPriceForm()
    {
        $frm = $this->getOfferPriceForm();
        $frm->fill(array('top_learner_id' => FatApp::getPostedData('top_learner_id', FatUtility::VAR_INT, 0)));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setUpOfferPrice()
    {
        $frmSrch = $this->getOfferPriceForm();
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }
        $post['top_teacher_id'] = UserAuthentication::getLoggedUserId();
        $teacherOffer = new TeacherOfferPrice();
        if (!$teacherOffer->saveData($post)) {
            FatUtility::dieWithError($teacherOffer->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Price_Locked_Successfully!'));
    }

    private function getOfferPriceForm()
    {
        $frm = new Form('frmOfferPrice');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Single_Lesson_Price'), 'top_single_lesson_price');
        $fld->requirements()->setFloatPositive();
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Bulk_Lesson_Price'), 'top_bulk_lesson_price');
        $fld->requirements()->setFloatPositive();
        $fld = $frm->addHiddenField('', 'top_learner_id');
        $fld->requirements()->setInt();
        $fld->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save'));
        return $frm;
    }

    public function unlockOfferPrice()
    {
        $learnerId = FatApp::getPostedData('learnerId', FatUtility::VAR_INT, 0);
        if ($learnerId < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $teacherOffer = new TeacherOfferPrice();
        if (!$teacherOffer->removeOffer($learnerId, UserAuthentication::getLoggedUserId())) {
            FatUtility::dieWithError($teacherOffer->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Price_Unlocked_Successfully!'));
    }

    public function getMessageToLearnerFrm()
    {
        $frm = new Form('messageToLearnerFrm');
        $fld = $frm->addTextArea('Comment', 'msg_to_learner', '', array('style' => 'width:300px;'));
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
        $teacherData = User::getAttributesById(UserAuthentication::getLoggedUserId(), array('user_first_name', 'user_last_name'));
        $learnerData = User::getAttributesById($learnerId, array('user_first_name', 'user_last_name'));
        $userSrch = User::getSearchObject(true);
        $userSrch->addMultipleFields(array('credential_email'));
        $userSrch->addCondition('credential_user_id', '=', $learnerId);
        $userRs = $userSrch->getResultSet();
        $userData = $db->fetch($userRs);
        $tpl = 'teacher_message_to_learner_email';
        $vars = array(
            '{learner_name}' => $learnerData['user_first_name']." ".$learnerData['user_last_name'],
            '{teacher_name}' => $teacherData['user_first_name']." ".$teacherData['user_last_name'],
            '{teacher_message}' => $post['msg_to_learner'],
            '{action}' => 'Message To Learner',
        );
        if (!EmailHandler::sendMailTpl($userData['credential_email'], $tpl, $this->siteLangId, $vars)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Message_Sent_Successfully!'));
    }
}
