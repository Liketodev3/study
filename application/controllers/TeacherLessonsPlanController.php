<?php
class TeacherLessonsPlanController extends TeacherBaseController {
	
	public function __construct($action){
		parent::__construct($action);
	}
	
	public function index(){
		$this->_template->addJs('js/jquery.tagsinput.min.js');
		$this->_template->addCss('css/jquery.tagsinput.min.css');
		$this->_template->addJs('js/jquery-confirm.min.js');
		$this->_template->addCss('css/jquery-confirm.min.css');                
		$this->set('statusArr', LessonPlan::getDifficultyArr());
		$this->_template->render();
	}
	
	public function getFrm(){
		$frm = new Form('lessonPlanFrm');
		$frm->addRequiredField(Label::getLabel('LBl_Title'), 'tlpn_title');
		$frm->addTextarea(Label::getLabel('LBl_Description'), 'tlpn_description');
		$frm->addSelectBox(Label::getLabel('LBl_Difficulty_Level'), 'tlpn_level', LessonPlan::getDifficultyArr())->requirement->setRequired(true);
		$fld = $frm->addFileUpload(Label::getLabel('LBl_Plan_Files'), 'tlpn_file[]', array('multiple' => 'multiple', 'id' => 'tlpn_file'));
		$fld->htmlAfterField = "<small>".Label::getLabel('LBL_NOTE:_Allowed_Lesson_File_types!')."</small>";		
		$frm->addHtml('', 'tlpn_file_display', '', array('id' => 'tlpn_file_display'));        
		$fld = $frm->addRequiredField(Label::getLabel('LBl_Tags'),'tlpn_tags','',array('id'=>'tlpn_tags'));
		$fld->htmlAfterField = "<small>".Label::getLabel('LBL_NOTE:_Press_enter_inside_text_box_to_create_tag!')."</small>";
		$fld = $frm->addTextarea(Label::getLabel('LBl_Links'),'tlpn_links');
		$fld->htmlAfterField = "<small>".Label::getLabel('LBl_Links')."</small>";        
		$frm->addFileUpload(Label::getLabel('LBl_Plan_Banner_Image'),'tlpn_image');
		$frm->addHiddenField('', 'tlpn_id');
		$frm->addSubmitButton('', 'submit', 'Save');
		return $frm;
	}
	
	public function add( $lessonPlanId = 0 ){
		$frm = $this->getFrm();
		if($lessonPlanId > 0){
			$data=LessonPlan::getAttributesById( $lessonPlanId );
			$frm->fill($data);
		}
		$this->set('userId',UserAuthentication::getLoggedUserId());
		$this->set('lessonPlanId',$lessonPlanId);
		$this->set('frm',$frm);
		$this->_template->render(false,false);
	}
	
	public function uploadMultipleFiles(){
		$lessonPlan = new LessonPlan( );
		$lessonPlanId=$lessonPlan->getMainTableRecordId()+1;
		for($i=0;$i<count($_FILES['tlpn_file']['name']);$i++){
			if(!empty($_FILES['tlpn_file']['name'][$i])){
				$fileHandlerObj = new AttachedFile();
				if(!$res = $fileHandlerObj->saveDoc($_FILES['tlpn_file']['tmp_name'][$i], AttachedFile::FILETYPE_LESSON_PLAN_FILE, $lessonPlanId, 0 ,$_FILES['tlpn_file']['name'][$i],0)
				){
					Message::addErrorMessage($fileHandlerObj->getError());
					FatUtility::dieJsonError( Message::getHtml() );
				}
			}
		}
	}
	
	public function setup(){
		$frm = $this->getFrm();
		$post = $frm->getFormDataFromArray(FatApp::getPostedData());
		 if ($post === false) {
            FatUtility::dieJsonError(($frm->getValidationErrors()));
		 }
		$db = FatApp::getDb();
		$db->startTransaction();
		$post['tlpn_user_id'] = UserAuthentication::getLoggedUserId();
		$lessonPlanId = FatApp::getPostedData('tlpn_id',FatUtility::VAR_INT,0);
		$lessonPlan = new LessonPlan( $lessonPlanId );
		$lessonPlan->assignValues( $post );
		if (true !== $lessonPlan->save()) {
			FatUtility::dieJsonError( $lessonPlan->getError());
		}
		$lessonPlanId=$lessonPlan->getMainTableRecordId();
		for($i=0;$i<count($_FILES['tlpn_file']['name']);$i++){
			if(!empty($_FILES['tlpn_file']['name'][$i])){
				$fileHandlerObj = new AttachedFile();
				if(!$res = $fileHandlerObj->saveDoc($_FILES['tlpn_file']['tmp_name'][$i], AttachedFile::FILETYPE_LESSON_PLAN_FILE, $lessonPlanId, 0 ,$_FILES['tlpn_file']['name'][$i],0)
				){
					$db->rollbackTransaction();
					FatUtility::dieJsonError( $fileHandlerObj->getError() );
				}
			}
		}
		if(!empty($_FILES['tlpn_image']['name'])){
			$fileHandlerObj = new AttachedFile();
			if(!$res = $fileHandlerObj->saveImage($_FILES['tlpn_image']['tmp_name'], AttachedFile::FILETYPE_LESSON_PLAN_IMAGE, $lessonPlanId, 0 ,$_FILES['tlpn_image']['name'],0,true)
			){
				$db->rollbackTransaction();
				FatUtility::dieJsonError( $fileHandlerObj->getError() );
			}
		}
		$db->commitTransaction();
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Plan_Saved_Successfully!'));
	}
	
	public function remove( $lessonPlanId ){
		$lessonPlanId = FatUtility::int( $lessonPlanId );
		$lessonPlan=new LessonPlan( $lessonPlanId );
		$lessonPlan->deleteRecord();
		if($lessonPlan->getError())
		{
			FatUtility::dieJsonError($lessonPlan->getError());
		}
		FatUtility::dieJsonSuccess(Label::getLabel("Record Deleted Successfully!"));
	}
	
	public function removeLesson( $lessonPlanId = 0 ){
		$lessonPlanId = FatUtility::int($lessonPlanId);
		$this->set('lessonPlanId',$lessonPlanId);
		$this->_template->render(false,false);
	}
	
	public function removeLessonSetup(  ){
		$db = FatApp::getDb();
		$post = FatApp::getPostedData();
		if(isset($post['submit']) && empty($post))
		{
			FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
		}
		
		if(!$db->deleteRecords('tbl_scheduled_lessons_to_teachers_lessons_plan',array('smt'=>'ltp_tlpn_id = ?','vals'=>array($post['lessonPlanId']))))
		{
			FatUtility::dieWithError($db->getError());
		}
		
		if(!$db->deleteRecords('tbl_teacher_courses_to_teachers_lessons_plan',array('smt'=>'ctp_tlpn_id = ?','vals'=>array($post['lessonPlanId']))))
		{
			FatUtility::dieWithError($db->getError());
		}
		
		$lessonPlan=new LessonPlan( $post['lessonPlanId'] );
		if(!$lessonPlan->deleteRecord())
		{
			FatUtility::dieWithError($db->getError());
		}
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Student_Remove_Successfully!'));
	}
	
	public function removeFile( $fileId ){
		$fileId = FatUtility::int( $fileId );
		$lessonPlan=new AttachedFile( $fileId );
		$lessonPlan->deleteRecord();
		if($lessonPlan->getError())
		{
			FatUtility::dieJsonError($AttachedFile->getError());
		}
		FatUtility::dieJsonSuccess(Label::getLabel("Record Deleted Successfully!"));
	}
	
	public function getListing(){
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
		$this->set('userId',UserAuthentication::getLoggedUserId());
		$this->set('statusArr',LessonPlan::getDifficultyArr());
		$this->set('countData',$count);
		$this->set('lessonsPlanData',$rows);
		$this->_template->render(false,false);
	}
	
	public function lessonPlanFile($lessonPlanId = 0,$subRecordId=0, $sizeType = ''){
		$recordId = FatUtility::int($lessonPlanId);
		
		$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_LESSON_PLAN_FILE, $recordId,$subRecordId );
		$image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
		switch( strtoupper($sizeType) ){
			case 'THUMB':
				$w = 100;
				$h = 100;
				AttachedFile::displayImage( $image_name, $w, $h);
			break;
			default:				
				AttachedFile::displayOriginalImage( $image_name );
			break;
		}
	}
	
	public function lessonPlanImage($lessonPlanId = 0,$subRecordId=0, $sizeType = ''){
		$recordId = FatUtility::int($lessonPlanId);
		
		$file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_LESSON_PLAN_IMAGE, $recordId,$subRecordId );
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
	
	public function getFileById($afile_id = 0,$sizeType = ''){
		$afile_id = FatUtility::int($afile_id);
		$file_row = AttachedFile::getAttributesById($afile_id);
		$image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
		AttachedFile::downloadAttachment( $image_name,$file_row['afile_name']);        
        
/* 		switch( strtoupper($sizeType) ){
			case 'THUMB':
				$w = 100;
				$h = 100;
				AttachedFile::displayImage( $image_name, $w, $h);
			break;
			default:	
				AttachedFile::displayOriginalImage( $image_name);
			break;
		} */
		
	}
}