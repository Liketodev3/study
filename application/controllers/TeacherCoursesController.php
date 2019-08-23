<?php
class TeacherCoursesController extends TeacherBaseController {
	
	public function __construct($action){
		parent::__construct($action);
	}
	
	public function index(){
		$this->_template->addJs('js/jquery.tagsinput.min.js');
		$this->_template->addCss('css/jquery.tagsinput.min.css');
		$this->set('statusArr',LessonPlan::getDifficultyArr());
		$this->_template->render();
	}
	
	public function getFrm(){
		$frm = new Form('coursesFrm');
		$frm->addRequiredField(Label::getLabel('LBl_Title'),'tcourse_title');
		$frm->addTextarea(Label::getLabel('LBl_Description'),'tcourse_description');
		$noOfLessonsArr = explode(",",FatApp::getConfig('CONF_TEACHER_NO_OF_LESSON'));
		$nOLsnArr = array();
		foreach($noOfLessonsArr as $val)
		{
			$nOLsnArr[$val] = $val;
		}
		
		$srch = new CourseCategorySearch($this->siteLangId);
		$srch->addMultipleFields(array('ccategory_id','ccategory_title'));
		$rs = $srch->getResultSet();
		$rows = FatApp::getDb()->fetchAll($rs);
		$catArr = array();
		foreach($rows as $row)
		{
			$catArr[$row['ccategory_id']] = $row['ccategory_title'];
		}
		$fld3 = $frm->addSelectBox(Label::getLabel('LBl_Course_Category'),'tcourse_category',$catArr,'',array('id'=>'tcourse_category'));
		$fld3->requirement->setRequired(true);
		$fld = $frm->addSelectBox(Label::getLabel('LBl_No._Of_Lessons'),'tcourse_no_of_lessons',$nOLsnArr,'',array('id'=>'tcourse_no_of_lessons'));
		$fld->requirement->setRequired(true);
		$fld2 = $frm->addSelectBox(Label::getLabel('LBl_Difficulty_Level'),'tcourse_level',TeacherCourse::getDifficultyArr());
		$fld2->requirement->setRequired(true);
		$frm->addHtml('','lesson_plan','');
		$fld = $frm->addTextBox(Label::getLabel('LBl_Tags'),'tcourse_tags','',array('id'=>'tcourse_tags'));
		$fld->htmlAfterField = "<small>".Label::getLabel('LBL_NOTE:_Press_enter_inside_text_box_to_create_tag!')."<small>"; 
		$frm->addFileUpload(Label::getLabel('LBl_Course_Image'),'tcourse_image');
		$frm->addHiddenField('','tcourse_id');
		$frm->addSubmitButton('','submit',Label::getLabel('LBL_Save'));
		return $frm;
	}
	
	public function add( $courseId = 0 ){
		$frm = $this->getFrm();
		$courseId = FatUtility::int($courseId);
		if($courseId > 0){
			$data=TeacherCourse::getAttributesById( $courseId );
			$frm->fill($data);
		}
		$this->set('userId',UserAuthentication::getLoggedUserId());
		$this->set('courseId',$courseId);
		$this->set('frm',$frm);
		$this->_template->render(false,false);
	}
	
	public function setup(){
		$frm = $this->getFrm();
		$post = $frm->getFormDataFromArray(FatApp::getPostedData());
		 if ($post === false) {
            FatUtility::dieJsonError(($frm->getValidationErrors()));
		 }
		$db = FatApp::getDb();
		$db->startTransaction();
		$post['tcourse_user_id'] = UserAuthentication::getLoggedUserId();
		$courseId = FatApp::getPostedData('tcourse_id',FatUtility::VAR_INT,0);
		$srch = new SearchBase('tbl_teacher_courses_to_teachers_lessons_plan');
		if($post['tcourse_id'] == 0){
			$srch->addCondition('ctp_tcourse_id','=','-'.UserAuthentication::getLoggedUserId());
		}
		if($post['tcourse_id'] != 0){
			$srch->addCondition('ctp_tcourse_id','=',$courseId);
		}
		
		$rs = $srch->getResultSet();
		$count = $srch->recordCount();
		if($count == 0 && $post['tcourse_id'] == 0){
			FatUtility::dieJsonError(Label::getLabel('LBL_Add_'.$post['tcourse_no_of_lessons'].'_Lesson_Plans!'));
		}
		if($count == 0 || $count != $post['tcourse_no_of_lessons'] ){
			FatUtility::dieJsonError(Label::getLabel('LBL_Add_'.$post['tcourse_no_of_lessons'].'_Lesson_Plans!'));
		}
		 
		$teacherCourse = new TeacherCourse( $courseId );
		$teacherCourse->assignValues( $post );
		if (true !== $teacherCourse->save()) {
			FatUtility::dieJsonError( $teacherCourse->getError());
		}
		$courseId=$teacherCourse->getMainTableRecordId();
		FatApp::getDb()->updateFromArray('tbl_teacher_courses_to_teachers_lessons_plan',
			array('ctp_tcourse_id'=>$courseId),
			array('smt'=>'ctp_tcourse_id = ?',
			'vals'=>array('-'.UserAuthentication::getLoggedUserId()))
		);
		
		if(!empty($_FILES['tcourse_image']['name'])){
			$fileHandlerObj = new AttachedFile();
			if(!$res = $fileHandlerObj->saveImage($_FILES['tcourse_image']['tmp_name'], AttachedFile::FILETYPE_TEACHER_COURSE_IMAGE, $courseId, 0 ,$_FILES['tcourse_image']['name'],0,true)
			){
				$db->rollbackTransaction();
				FatUtility::dieJsonError( $fileHandlerObj->getError() );
			}
		}
		$db->commitTransaction();
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Course_Saved_Successfully!'));
	}
	
	public function remove( $courseId ){
		$courseId = FatUtility::int( $courseId );
		$teacherCourse = new TeacherCourse( $courseId );
		$teacherCourse->deleteRecord();
		if($teacherCourse->getError())
		{
			FatUtility::dieJsonError($teacherCourse->getError());
		}
		FatUtility::dieJsonSuccess(Label::getLabel("Record Deleted Successfully!"));
	}
	
	public function getListing(){
		$post = FatApp::getPostedData();
		if(isset($post['submit']) && empty($post))
		{
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$srch = new TeacherCourseSearch(false);
		$srch->addMultipleFields(array(
		'tcourse_id',
		'tcourse_title',
		'tcourse_description',
		'tcourse_tags',
		'tcourse_user_id',
		'tcourse_category',
		'tcourse_no_of_lessons',
		'tcourse_level',
		));
		$srch->addCondition( 'tcourse_user_id',' = ', UserAuthentication::getLoggedUserId() );
		if(!empty($post['keyword']))
		{
			$srch->addCondition('tcourse_title','like','%'.$post['keyword'].'%');
		}
		if(!empty($post['status']))
		{
			$srch->addCondition('tcourse_level','=',$post['status']);
		}
		$rs = $srch->getResultSet();
		$count = $srch->recordCount();
		$rows = FatApp::getDb()->fetchAll($rs);
		$this->set('userId',UserAuthentication::getLoggedUserId());
		$this->set('statusArr',LessonPlan::getDifficultyArr());
		$this->set('countData',$count);
		$this->set('teacherCoursesData',$rows);
		$this->_template->render(false,false);
	}
	
	public function teacherCourseImage($courseId = 0,$subRecordId=0, $sizeType = ''){
		$courseId = FatUtility::int($courseId);
		
		$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_TEACHER_COURSE_IMAGE, $courseId,$subRecordId );
		$image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
		switch( strtoupper($sizeType) ){
			case 'THUMB':
				$w = 100;
				$h = 100;
				AttachedFile::displayImage( $image_name, $w, $h);
			break;
			default:				
				$w = 60;
				$h = 60;
				AttachedFile::displayImage( $image_name, $w, $h);
			break;
		}
	}
	
	public function assignCoursesToLessonPlan(){
		$post = FatApp::getPostedData();
		if (empty($post)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
		 }
		 $deletePreviousPlans = FatApp::getDb()->deleteRecords('tbl_teacher_courses_to_teachers_lessons_plan',
		 array('smt' => 'ctp_tcourse_id = ?',
		 'vals' => array($post['course_id'])
		 ));
		 if($post['course_id'] == 0)
		 {
			$post['course_id'] = '-'.UserAuthentication::getLoggedUserId();
		 }
		 if($deletePreviousPlans){
			foreach($post['selecte_plans'] as $plan){
				$data = array(
				 "ctp_tcourse_id"=>$post['course_id'],
				 "ctp_tlpn_id"=>$plan,
				 );
				 if(!FatApp::getDb()->insertFromArray('tbl_teacher_courses_to_teachers_lessons_plan',$data, false,array(),$data))
				 {
					 FatUtility::dieJsonError(FatApp::getDb()->getError());
				 }
			}
		 }
		 FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Plan_Assigned_Successfully!'));
	}
	
	public function getListingLessonPlans( $courseId ){
		$courseId = FatUtility::int($courseId);
		$post = FatApp::getPostedData();
		if(isset($post['submit']) && empty($post))
		{
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		$srch = new LessonPlanSearch(false);
		$srch->addMultipleFields(array(
		'tlpn_id',
		'tlpn_title',
		'tlpn_level',
		'tlpn_user_id',
		'tlpn_tags',
		'tlpn_description',
		));
		
		$srch->addCondition( 'tlpn_user_id',' = ', UserAuthentication::getLoggedUserId() );
		if(!empty($post['keyword']))
		{
			$srch->addCondition('tlpn_title','like','%'.$post['keyword'].'%');
		}
		if(!empty($post['status']))
		{
			$srch->addCondition('tlpn_level','=',$post['status']);
		}
		
		$rs = $srch->getResultSet();
		$count = $srch->recordCount();
		$rows = FatApp::getDb()->fetchAll($rs);
		
		
		$srchCorseRelToPlan = new SearchBase('tbl_teacher_courses_to_teachers_lessons_plan');
		$srchCorseRelToPlan->addMultipleFields(array('ctp_tlpn_id'));
		$srchCorseRelToPlan->addCondition('ctp_tcourse_id','=',$courseId);
		$rsCorseRelToPlan = $srchCorseRelToPlan->getResultSet();
		$rowsCorseRelToPlan = FatApp::getDb()->fetchAll($rsCorseRelToPlan);
		
		$this->set('userId',UserAuthentication::getLoggedUserId());
		$this->set('statusArr',LessonPlan::getDifficultyArr());
		$this->set('countData',$count);
		$this->set('lessonsPlanData',$rows);
		$this->set('rowsCorseRelToPlan',array_column($rowsCorseRelToPlan,'ctp_tlpn_id'));
		$this->set('courseId',$courseId);
		$this->_template->render(false,false);
	}

	public function viewAssignedPlans( $courseId ){
		$courseId = FatUtility::int($courseId);
		$post = FatApp::getPostedData();
		if(isset($post['submit']) && empty($post))
		{
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		
		
		$srch = new SearchBase('tbl_teacher_courses_to_teachers_lessons_plan');
		$srch->addMultipleFields(array(
		'tlpn_id',
		'tlpn_title',
		'tlpn_level',
		'tlpn_user_id',
		'tlpn_tags',
		'tlpn_description',
		'ctp_tlpn_id'
		));
		$srch->joinTable(LessonPlan::DB_TBL,'inner join','tlpn_id = ctp_tlpn_id');
		$srch->addCondition('ctp_tcourse_id','=',$courseId);
		$rs = $srch->getResultSet();
		$count = $srch->recordCount();
		$rows = FatApp::getDb()->fetchAll($rs);
		
		
		
		$this->set('userId',UserAuthentication::getLoggedUserId());
		$this->set('statusArr',LessonPlan::getDifficultyArr());
		$this->set('countData',$count);
		$this->set('lessonsPlanData',$rows);
		$this->_template->render(false,false);
	}

}