<?php
class LearnerTeachersController extends LearnerBaseController
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
        
        $schLesSrch = new ScheduledLessonSearch();
        $schLesSrch->doNotLimitRecords();
        $schLesSrch->addFld('count(DISTINCT slesson_id)');
        $schLesSrch->addCondition('sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $schLesSrch->addDirectCondition('slesson_teacher_id=ut.user_id');
        
        $pastLesSrch = clone $schLesSrch;
        $unSchLesSrch = clone $schLesSrch;
        
        $schLesSrch->addCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $pastLesSrch->addCondition('sldetail_learner_status', '!=', ScheduledLesson::STATUS_NEED_SCHEDULING);
        $pastLesSrch->addCondition('sldetail_learner_status', '!=', ScheduledLesson::STATUS_CANCELLED);
        $pastLesSrch->addDirectCondition('CONCAT(slesson_date, " ", slesson_start_time) < "'. date('Y-m-d H:i:s').'"');        
        $unSchLesSrch->addCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_NEED_SCHEDULING);
        
        // echo $pastLesSrch->getQuery();die;
        
        $srch = new ScheduledLessonSearch(false);
        $srch->addCondition('sldetail_learner_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition('sldetail_learner_status', '!=', ScheduledLesson::STATUS_CANCELLED);
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->addGroupBy('slesson_teacher_id', 'slesson_status');
        $srch->joinLearner();
        $srch->joinTeacher();
        $srch->joinTeacherCountry($this->siteLangId);
        $srch->joinTeacherSettings();
        $srch->joinRatingReview();
        $srch->joinUserTeachLanguages($this->siteLangId);
        $srch->joinLearnerOfferPrice(UserAuthentication::getLoggedUserId());
        $srch->addMultipleFields(array(
            'slns.slesson_teacher_id as teacherId',
            'slns.slesson_slanguage_id as languageID',
            'sld.sldetail_learner_id as learnerId',
            'ut.user_url_name as user_url_name',
            'ut.user_first_name as teacherFname',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
            'IFNULL(teachercountry_lang.country_name, teachercountry.country_code) as teacherCountryName',
            '('.$schLesSrch->getQuery().') as scheduledLessonCount',
            '('.$pastLesSrch->getQuery().') as pastLessonCount',
            '('.$unSchLesSrch->getQuery().') as unScheduledLessonCount',
            'CASE WHEN top_single_lesson_price IS NULL THEN 0 ELSE 1 END as isSetUpOfferPrice',
            '( select utl_single_lesson_amount from '. UserToLanguage::DB_TBL_TEACH .' WHERE utl_us_user_id = ut.user_id Limit 0, 1 ) as singleLessonAmount',
            '( select utl_bulk_lesson_amount from '. UserToLanguage::DB_TBL_TEACH .' WHERE utl_us_user_id = ut.user_id Limit 0, 1 ) as bulkLessonAmount '
        ));

        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        // $srch->addOrder('order_date_added', 'DESC');
        $srch->addOrder('ut.user_first_name');

        if (isset($post['keyword']) && !empty($post['keyword'])) {
            $keywordsArr = array_unique(array_filter(explode(' ', $post['keyword'])));
            foreach ($keywordsArr as $keyword) {
                $cnd = $srch->addCondition('ut.user_first_name', 'like', '%'.$keyword.'%');
                $cnd->attachCondition('ut.user_last_name', 'like', '%'.$keyword.'%');
            }
        }
        // echo $srch->getQuery();die;
        $rs = $srch->getResultSet();
        $teachers = FatApp::getDb()->fetchAll($rs);
        $this->set('teachers', $teachers);
        $srch = LessonPackage::getSearchObject($this->siteLangId);
        $srch->addCondition('lpackage_is_free_trial', '=', 0);
        $srch->addMultipleFields(array(
            'lpackage_id',
            'IFNULL(lpackage_title, lpackage_identifier) as lpackage_title',
            'lpackage_lessons'
        ));
        $rs = $srch->getResultSet();
        $lessonPackages = FatApp::getDb()->fetchAll($rs);
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
        $this->set('lessonPackages', $lessonPackages);
        /* ] */
        $this->_template->render(false, false);
    }

    public function getMessageToTeacherFrm()
    {
        $frm = new Form('messageToLearnerFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'msg_to_teacher', '', array('style' => 'width:300px;'));
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'submit', 'Send');
        return $frm;
    }

    /* public function sendMessageToTeacher( $teacherId = 0 ){
        $teacherId = FatUtility::int($teacherId);
        $frm = $this->getMessageToTeacherFrm();
        $frm->addHiddenField('','slesson_teacher_id',$teacherId);
        $this->set('frm',$frm);
        $this->_template->render(false,false);
    }

    public function messageToTeacherSetup(){
        $db = FatApp::getDb();
        $post = FatApp::getPostedData();
        if(empty($post))
        {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $teacherId = $post['slesson_teacher_id'];
        $teacherData = User::getAttributesById($teacherId, array('user_first_name','user_last_name'));
        $learnerData = User::getAttributesById(UserAuthentication::getLoggedUserId(), array('user_first_name','user_last_name'));

        $userSrch = User::getSearchObject(true);
        $userSrch->addMultipleFields(array('credential_email'));
        $userSrch->addCondition('credential_user_id','=',$teacherId);
        $userRs = $userSrch->getResultSet();
        $userData = $db->fetch($userRs);

        $tpl = 'learner_message_to_teacher_email';
        $vars = array(
            '{learner_name}' => $learnerData['user_first_name']." ".$learnerData['user_last_name'],
            '{teacher_name}' => $teacherData['user_first_name']." ".$teacherData['user_last_name'],
            '{learner_message}' => $post['msg_to_teacher'],
            '{action}' => 'Message To Teacher',
        );

        if(!EmailHandler::sendMailTpl($userData['credential_email'], $tpl ,$this->siteLangId, $vars)){
            FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Message_Sent_Successfully!'));
    } */
}
