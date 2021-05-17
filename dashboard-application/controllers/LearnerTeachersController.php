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
        $pastLesSrch->addDirectCondition('CONCAT(slesson_date, " ", slesson_start_time) < "' . date('Y-m-d H:i:s') . '"');
        $unSchLesSrch->addCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_NEED_SCHEDULING);
        $teacherOfferPriceSrch = new SearchBase(TeacherOfferPrice::DB_TBL);
        $teacherOfferPriceSrch->addMultipleFields([
            'top_learner_id', 
            'top_teacher_id',
            'GROUP_CONCAT(top_percentage ORDER BY top_lesson_duration) as percentage',
            'GROUP_CONCAT(top_lesson_duration ORDER BY top_lesson_duration) as lessonDuration'
        ]);
        $teacherOfferPriceSrch->addGroupBy('top_learner_id');
        $teacherOfferPriceSrch->doNotLimitRecords();
        $teacherOfferPriceSrch->doNotCalculateRecords();

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
        $srch->joinTable('(' . $teacherOfferPriceSrch->getQuery() . ')', 'LEFT JOIN', 'sldetail_learner_id = top_learner_id AND top_teacher_id = slesson_teacher_id', 'top');
        $srch->addMultipleFields([
            'slns.slesson_teacher_id as teacherId',
            'slns.slesson_slanguage_id as languageID',
            'sld.sldetail_learner_id as learnerId',
            'ut.user_url_name as user_url_name',
            'ut.user_first_name as teacherFname',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
            'IFNULL(teachercountry_lang.country_name, teachercountry.country_code) as teacherCountryName',
            '(' . $schLesSrch->getQuery() . ') as scheduledLessonCount',
            '(' . $pastLesSrch->getQuery() . ') as pastLessonCount',
            '(' . $unSchLesSrch->getQuery() . ') as unScheduledLessonCount',
            'percentage',
            'CASE WHEN percentage IS NULL THEN 0 ELSE 1 END as isSetUpOfferPrice',
            'lessonDuration'
        ]);
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $srch->addOrder('ut.user_first_name');
        if (isset($post['keyword']) && !empty($post['keyword'])) {
            $keywordsArr = array_unique(array_filter(explode(' ', $post['keyword'])));
            foreach ($keywordsArr as $keyword) {
                $cnd = $srch->addCondition('ut.user_first_name', 'like', '%' . $keyword . '%');
                $cnd->attachCondition('ut.user_last_name', 'like', '%' . $keyword . '%');
            }
        }
        $rs = $srch->getResultSet();
        $teachers = FatApp::getDb()->fetchAll($rs);
        $this->set('teachers', $teachers);

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

    public function getMessageToTeacherFrm()
    {
        $frm = new Form('messageToLearnerFrm');
        $fld = $frm->addTextArea(Label::getLabel('LBL_Comment'), 'msg_to_teacher', '', ['style' => 'width:300px;']);
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'submit', 'Send');
        return $frm;
    }

}
