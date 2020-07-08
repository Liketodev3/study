<?php
class TeacherGroupClassesController extends TeacherBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $this->_template->addJs('js/jquery.datetimepicker.js');
        $this->_template->addCss('css/jquery.datetimepicker.css');
        $frmSrch = $this->getSearchForm();
        $this->set('frmSrch', $frmSrch);
        $this->_template->addCss('css/switch.css');
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
        
        $srch2 = new SearchBase('tbl_scheduled_lesson_details');
        $srch2->joinTable('tbl_scheduled_lessons', 'INNER JOIN', 'slesson_id=sldetail_slesson_id');
        $srch2->addDirectCondition('slesson_grpcls_id=grpcls_id');
        $cnd = $srch2->addCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_NEED_SCHEDULING);
        $cnd->attachCondition('sldetail_learner_status', '=', ScheduledLesson::STATUS_SCHEDULED);
        $srch2->doNotCalculateRecords();
        $srch2->doNotLimitRecords();
        $srch2->addFld('COUNT(DISTINCT sldetail_learner_id)');
        
        $srch3 = new SearchBase('tbl_scheduled_lessons');
        $srch3->addDirectCondition('slesson_grpcls_id=grpcls_id');
        $srch3->doNotCalculateRecords();
        $srch3->doNotLimitRecords();
        $srch3->addFld('slesson_teacher_join_time>0');

        $teacher_id = UserAuthentication::getLoggedUserId();
        $srch = new TeacherGroupClassesSearch(false);
        $srch->joinScheduledLesson();
        $srch->addMultipleFields(
            array(
                'grpcls_id',
                'grpcls_title',
                'grpcls_max_learner',
                'grpcls_min_learner',
                'grpcls_entry_fee',
                'grpcls_start_datetime',
                'grpcls_end_datetime',
                'grpcls_status',
                'slesson_id',
                '('.$srch2->getQuery().') total_learners',
                '('.$srch3->getQuery().') is_joined'
            )
        );
        // echo $srch->getQuery();die;
        
        $srch->addCondition('grpcls_teacher_id', '=', $teacher_id);
        
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
		// echo $srch->getQuery();die;
        $rs = $srch->getResultSet();
        $classes = FatApp::getDb()->fetchAll($rs);
		
        $user_timezone = MyDate::getUserTimeZone();
        
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
        $this->set('classes', $classes);
        $this->set('statusArr', ScheduledLesson::getStatusArr());
        $this->set('classStatusArr', TeacherGroupClasses::getStatusArr());
        $this->_template->render(false, false);
    }

    public function form($classId = 0)
    {
        $frm = $this->getFrm();
        $classId = FatUtility::int($classId);
        $teacher_id = UserAuthentication::getLoggedUserId();
        if ($classId > 0) {
			$user_timezone = MyDate::getUserTimeZone();
			$systemTimeZone = MyDate::getTimeZone();
            $data = TeacherGroupClasses::getAttributesById($classId);
            
            $isSlotBooked = ScheduledLessonSearch::isSlotBooked($teacher_id, $data['grpcls_start_datetime'], $data['grpcls_end_datetime']);
		
            if($isSlotBooked){
                $fld = $frm->getField('grpcls_start_datetime');
                $fld->setFieldTagAttribute('disabled', 'disabled');
                $fld->setFieldTagAttribute('title', Label::getLabel("LBL_Start_Time_can_not_change_for_Booked_Class"));
                $fld->requirements()->setRequired(false);
                $fld = $frm->getField('grpcls_end_datetime');
                $fld->setFieldTagAttribute('disabled', 'disabled');
                $fld->setFieldTagAttribute('title', Label::getLabel("LBL_End_Time_can_not_change_for_Booked_Class"));
                $fld->requirements()->setRequired(false);
            }
            
			$data['grpcls_start_datetime'] = MyDate::changeDateTimezone($data['grpcls_start_datetime'], $systemTimeZone, $user_timezone);
			$data['grpcls_end_datetime'] = MyDate::changeDateTimezone($data['grpcls_end_datetime'], $systemTimeZone, $user_timezone);
            $frm->fill($data);
        }
        $this->set('userId', UserAuthentication::getLoggedUserId());
        $this->set('classId', $classId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $teacher_id = UserAuthentication::getLoggedUserId();
        $frm = $this->getFrm();
        $post = FatApp::getPostedData();
        $grpcls_id = FatApp::getPostedData('grpcls_id', FatUtility::VAR_INT, 0);
        
		$user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        
        if($grpcls_id>0){
            $class_details = TeacherGroupClassesSearch::getClassDetailsByTeacher($grpcls_id, $teacher_id);
            if(empty($class_details)){
                FatUtility::dieJsonError(Label::getLabel("LBL_Unauthorized"));
            }
            
            $isSlotBooked = ScheduledLessonSearch::isSlotBooked($teacher_id, $class_details['grpcls_start_datetime'], $class_details['grpcls_end_datetime']);
		
            if($isSlotBooked){
                $post['grpcls_start_datetime'] = MyDate::changeDateTimezone($class_details['grpcls_start_datetime'], $systemTimeZone, $user_timezone);
                $post['grpcls_end_datetime'] = MyDate::changeDateTimezone($class_details['grpcls_end_datetime'], $systemTimeZone, $user_timezone);
            }
        }
        
        $post = $frm->getFormDataFromArray($post);
        if ($post === false) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        
        $price = UserToLanguage::getAttributesByUserAndLangId($teacher_id, $post['grpcls_slanguage_id'], 'utl_single_lesson_amount');
        if(empty($price) || $price<1){
            FatUtility::dieJsonError(Label::getLabel("LBL_Price_needs_to_be_set_for_the_selected_language"));
        }
        
        if($price<$post['grpcls_entry_fee']){
            FatUtility::dieJsonError(Label::getLabel("LBL_Price_needs_to_be_less_than_single_lesson"));
        }
       
        $post['grpcls_teacher_id'] = $teacher_id;
		
		$post['grpcls_start_datetime'] = MyDate::changeDateTimezone($post['grpcls_start_datetime'], $user_timezone, $systemTimeZone);
		$post['grpcls_end_datetime'] = MyDate::changeDateTimezone($post['grpcls_end_datetime'], $user_timezone, $systemTimeZone);
		
        $tGrpClsSrchObj = new TeacherGroupClassesSearch();
        if($grpcls_id>0){
            $tGrpClsSrchObj->addCondition('grpcls_id', '!=', $grpcls_id);
        }
        $tGrpClsSrchObj->addCondition('grpcls_teacher_id', '=', $teacher_id);
        $tGrpClsSrchObj->addCondition('grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
        $cnd = $tGrpClsSrchObj->addCondition('grpcls_start_datetime', '<=', $post['grpcls_start_datetime']);
        $cnd->attachCondition('grpcls_end_datetime', '>=', $post['grpcls_start_datetime'], 'AND');
        
        $cnd2 = $cnd->attachCondition('grpcls_start_datetime', '<=', $post['grpcls_end_datetime']);
        $cnd2->attachCondition('grpcls_end_datetime', '>=', $post['grpcls_end_datetime'], 'AND');
        
        $rs = $tGrpClsSrchObj->getResultSet();
        if(FatApp::getDb()->fetch($rs)){
            FatUtility::dieJsonError(Label::getLabel('LBL_A_class_already_exist_in_selected_time'));
        }
        
        $current_time = MyDate::changeDateTimezone(null, $user_timezone, $systemTimeZone);
        
        if($post['grpcls_start_datetime']<$current_time){
            FatUtility::dieJsonError(Label::getLabel('LBL_Can_not_add_time_for_old_date'));
        }
        
        $weekStartDay = date('W', strtotime($post['grpcls_start_datetime']));
        $weekStart = date("Y-m-d", strtotime(date('Y')."-W$weekStartDay+1"));
        
        $isSlotBooked = ScheduledLessonSearch::isSlotBooked($teacher_id, $post['grpcls_start_datetime'], $post['grpcls_end_datetime']);
		
        if($isSlotBooked){
            if($grpcls_id<=0 || $post['grpcls_start_datetime']!=$class_details['grpcls_start_datetime'] || $post['grpcls_end_datetime']!=$class_details['grpcls_end_datetime']){
                FatUtility::dieJsonError(Label::getLabel('LBL_Slot_is_already_booked'));
            }
        }
        
        $tWSchObj = new TeacherWeeklySchedule();
        $isAvailable = $tWSchObj->checkCalendarTimeSlotAvailability($teacher_id, $post['grpcls_start_datetime'], $post['grpcls_end_datetime'], $weekStart);
        
        if($grpcls_id==0){
            $post['grpcls_status'] = TeacherGroupClasses::STATUS_ACTIVE;
        }
        
        $tGrpClsObj = new TeacherGroupClasses($grpcls_id);
        $tGrpClsObj->assignValues($post);
        if (true !== $tGrpClsObj->save()) {
            FatUtility::dieJsonError($tGrpClsObj->getError());
        }
        
        $msg = Label::getLabel('LBL_Group_Class_Saved_Successfully!');
        if($isAvailable){
            $msg = Label::getLabel('LBL_Slot_is_already_added_for_1_to_1_class_whichever_first_booked_will_be_booked!');
        }
        FatUtility::dieJsonSuccess($msg);
    }

    public function removeClass($grpclsId)
    {
        $grpclsId = FatUtility::int($grpclsId);
        if ($grpclsId<1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        
        $teacher_id = UserAuthentication::getLoggedUserId();
        $class_details = TeacherGroupClassesSearch::getClassDetailsByTeacher($grpclsId, $teacher_id);
        if(empty($class_details)){
            FatUtility::dieJsonError(Label::getLabel("LBL_Unauthorized"));
        }
        $teacherGroupClassObj = new TeacherGroupClasses($grpclsId);
        $teacherGroupClassObj->deleteClass();
        if ($teacherGroupClassObj->getError()) {
            FatUtility::dieJsonError($teacherGroupClassObj->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel("LBL_Class_Deleted_Successfully!"));
    }
    
    
    public function cancelClass($grpclsId)
    {
        $grpclsId = FatUtility::int($grpclsId);
        if ($grpclsId<1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        
        $teacher_id = UserAuthentication::getLoggedUserId();
        $class_details = TeacherGroupClassesSearch::getClassDetailsByTeacher($grpclsId, $teacher_id);
        if(empty($class_details)){
            FatUtility::dieJsonError(Label::getLabel("LBL_Unauthorized"));
        }
        
        $db = FatApp::getDb();
        $db->startTransaction();
        
        $teacherGroupClassObj = new TeacherGroupClasses($grpclsId);
        $teacherGroupClassObj->cancelClass();
        if ($teacherGroupClassObj->getError()) {
            $db->rollbackTransaction();
            FatUtility::dieJsonError($teacherGroupClassObj->getError());
        }
        
        /* update all lesson status for this class[ */
        $sLessonSrchObj = new ScheduledLessonSearch();
        $lessons = $sLessonSrchObj->getLessonsByClass($grpclsId);
        foreach($lessons as $lesson){
            $sLessonObj = new ScheduledLesson($lesson['slesson_id']);
            $sLessonObj->assignValues(array('slesson_status' => ScheduledLesson::STATUS_CANCELLED));
            if (!$sLessonObj->save()) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($sLessonObj->getError());
            }
        }
        /* ] */
        
        $db->commitTransaction();
        
        FatUtility::dieJsonSuccess(Label::getLabel("LBL_Class_Cancelled_Successfully!"));
    }
    
    public function InterestList()
    {
        $post = FatApp::getPostedData();
        $grpClsId = FatApp::getPostedData('grpcls_id', FatUtility::VAR_INT, 0);
        $user_id = UserAuthentication::getLoggedUserId();
        
        if(!$grpClsId){
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        
        $clsDetails = TeacherGroupClasses::getAttributesById($grpClsId, array('grpcls_teacher_id'));
        
        if($clsDetails['grpcls_teacher_id']!=$user_id){
            Message::addErrorMessage(Label::getLabel('LBL_Unauthorized', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        
        $srch2 = new RequestedTimeslotsFollowersSearch();
        $srch2->doNotCalculateRecords(true);
        $srch2->addFld('COUNT(reqslfol_reqts_id)');
        $srch2->addDirectCondition('reqslfol_reqts_id=reqts_id');
        
        $reqtsSrch = new RequestedTimeslotsSearch();
        $reqtsSrch->addCondition('reqts_grpcls_id', '=', $grpClsId);
        $reqtsSrch->addMultipleFields(
            array(
                'reqts_id',
                'reqts_time',
                'reqts_added_by',
                'reqts_status',
                '('.$srch2->getQuery().') total_followers'
            )
        );
        
        $rs = $reqtsSrch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        
        $this->set('postedData', $post);
		$this->set('rows', $rows);
        
        $this->_template->render(false, false);
    }
    
    public function changeInterstListStatus()
    {
        $post = FatApp::getPostedData();
        if ($post === false) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        
        $id = FatUtility::int($post['id']);
        $status = FatUtility::int($post['status']);
        if ($id<1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        
        $user_id = UserAuthentication::getLoggedUserId();
        
        $reqts = RequestedTimeslots::getAttributesById($id, array('reqts_id', 'reqts_grpcls_id'));
        if(empty($reqts)){
            FatUtility::dieJsonError(Label::getLabel('LBL_Time_not_exist'));
        }
        if($reqts['reqts_id']!=$id){
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }
        
        $clsDetails = TeacherGroupClasses::getAttributesById($reqts['reqts_grpcls_id'], array('grpcls_teacher_id'));
        
        if($clsDetails['grpcls_teacher_id']!=$user_id){
            Message::addErrorMessage(Label::getLabel('LBL_Unauthorized', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        
        $reqTSObj = new RequestedTimeslots($id);
        if (true !== $reqTSObj->changeStatus($status)) {
            FatUtility::dieJsonError($reqTSObj->getError());
        }
        
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Status_Updated_Successfully!'));
    }
	
	private function getFrm()
    {
        $teacher_id = UserAuthentication::getLoggedUserId();
        $frm = new Form('groupClassesFrm');
        $frm->addHiddenField('', 'grpcls_id');
        $frm->addRequiredField(Label::getLabel('LBl_Title'), 'grpcls_title');
        $frm->addTextArea(Label::getLabel('LBl_DESCRIPTION'), 'grpcls_description')->requirements()->setRequired(true);
        $fld = $frm->addIntegerField(Label::getLabel('LBl_Min_No._Of_Learners'), 'grpcls_min_learner', '', array('id' => 'grpcls_min_learner'));
        $fld->requirements()->setRange(1,9999);
        $fld = $frm->addIntegerField(Label::getLabel('LBl_Max_No._Of_Learners'), 'grpcls_max_learner', '', array('id' => 'grpcls_max_learner'));
        $fld->requirements()->setRange(1,9999);
        $frm->addSelectBox(Label::getLabel('LBl_Language'), 'grpcls_slanguage_id', UserToLanguage::getTeachingAssoc($teacher_id, $this->siteLangId))->requirements()->setRequired(true);
        $fld = $frm->addFloatField(Label::getLabel('LBl_Entry_fee'), 'grpcls_entry_fee', '', array('id' => 'grpcls_entry_fee'));
        $fld->requirements()->setIntPositive(true);
        $start_time_fld = $frm->addRequiredField(Label::getLabel('LBl_Start_Time'), 'grpcls_start_datetime', '', array('id' => 'grpcls_start_datetime', 'autocomplete' => 'off'));
        $end_time_fld = $frm->addRequiredField(Label::getLabel('LBl_End_Time'), 'grpcls_end_datetime', '', array('id' => 'grpcls_end_datetime', 'autocomplete' => 'off'));
		$end_time_fld->requirements()->setCompareWith('grpcls_start_datetime', 'gt', Label::getLabel('LBl_Start_Time'));
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_Save'));
        return $frm;
    }
    
    protected function getSearchForm()
    {
        $frm = new Form('frmSrch');
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        return $frm;
    }
}
