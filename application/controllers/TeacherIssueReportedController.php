<?php
class TeacherIssueReportedController extends TeacherBaseController {
	
	public function __construct($action){
		parent::__construct($action);
	}
	
	public function index(){
		$frmSrch = $this->getSearchForm();
		$this->set( 'frmSrch', $frmSrch );
		$this->set('statusArr',ScheduledLesson::getStatusArr());
		$this->_template->render();
	}
	
	public function search() {
		$frmSrch = $this->getSearchForm();
		$post = $frmSrch->getFormDataFromArray( FatApp::getPostedData() );
		$referer = CommonHelper::redirectUserReferer(true);
		
		if ( false === $post ) {
			FatUtility::dieWithError( $frmSrch->getValidationErrors() );
		}
		
		$srch = IssuesReported::getSearchObject();
		$srch->addCondition('slesson_teacher_id', '=', UserAuthentication::getLoggedUserId() );
		$srch->addMultipleFields(array(
			'i.*',
			'user_first_name',
			'slesson_id',
			'slesson_learner_id',
			'slesson_date',
			'slesson_start_time',
			'user_timezone',
			'slesson_status',
			'slesson_slanguage_id',
			'op_lesson_duration',
			'op_lpackage_is_free_trial',
		));
		
		$page = $post['page'];
		$pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
		$srch->setPageSize($pageSize);
		$srch->setPageNumber( $page );
		
		$rs = $srch->getResultSet();
		$issuesReported = FatApp::getDb()->fetchAll($rs);
		
		$this->set('issuesReported',$issuesReported); 
		
		/* [ */
		$totalRecords = $srch->recordCount();
		$pagingArr = array(
			'pageCount'	=>	$srch->pages(),
			'page'	=>	$page,
			'pageSize'	=>	$pageSize,
			'recordCount'	=>	$totalRecords,
		);
		$this->set( 'postedData', $post );
		$this->set( 'pagingArr', $pagingArr );
		
		$startRecord = ( $page - 1 ) * $pageSize + 1 ;
		$endRecord = $page * $pageSize;
		if ($totalRecords < $endRecord) {
			$endRecord = $totalRecords; 
		}
		
		$this->set( 'startRecord', $startRecord );
		$this->set( 'endRecord', $endRecord );
		$this->set( 'totalRecords', $totalRecords );
		$this->set('referer',$referer);	
		$this->set('statusArr',ScheduledLesson::getStatusArr());		
		$this->set('issues_options', IssueReportOptions::getOptionsArray( $this->siteLangId ));		
		/* ] */
		
		$this->_template->render(false,false);
	}
	
	public function issueDetails( $issueId ) {
		$issueId = FatUtility::int($issueId);
		$srch = IssuesReported::getSearchObject();
		$srch->addCondition('issrep_id', '=', $issueId );
		$srch->addMultipleFields(array(
			'i.*',
			'user_first_name',
			'slesson_id',
			'slesson_learner_id',
			'slesson_date',
			'slesson_start_time',
			'user_timezone',
			'slesson_status',
			'slesson_slanguage_id',
			'op_lesson_duration',
			'op_lpackage_is_free_trial',
		));
		$rs = $srch->getResultSet();
		$issuesReportedDetails = FatApp::getDb()->fetch($rs);
		$this->set( 'issueDeatils',$issuesReportedDetails );
		$this->set('issues_options', IssueReportOptions::getOptionsArray( $this->siteLangId ));
		$this->_template->render(false,false);
	}
	
	public function resolveIssue( $issueId, $slesson_id ) {
		$issueId = FatUtility::int($issueId);
		$frm = $this->getIssueReportedFrm();
		$frm->fill( array('issue_id' => $issueId , 'slesson_id' => $slesson_id) );
		$this->set('frm',$frm);
		$this->_template->render(false,false);
	}
	public static function getIssueDetails($issueId) {
		$srch = IssuesReported::getSearchObject();
		$srch->addCondition('issrep_id', '=', $issueId );
		$srch->addMultipleFields(array(
			'i.*'
		));
		$rs = $srch->getResultSet();
		$issuesReportedDetails = FatApp::getDb()->fetch($rs);
		return $issuesReportedDetails;
	}
	
	public function issueResolveStepTwo( $issueId, $slesson_id  ) {
		$issueId = FatUtility::int($issueId);
		$issuesReportedDetails = self::getIssueDetails($issueId);
		$frm = $this->getIssueReportedFrmStepTwo();
		$frm->fill( array('issue_id' => $issueId , 'slesson_id' => $slesson_id) );
		$this->set('frm',$frm);
		$this->set( 'issueDeatils',$issuesReportedDetails );
		$this->set('issues_options', IssueReportOptions::getOptionsArray( $this->siteLangId ));
		$this->_template->render(false,false);
	}
	
	
	public function issueResolveSetup() {
		$frm = $this->getIssueReportedFrm();
		$post = $frm->getFormDataFromArray( FatApp::getPostedData() );
		
		if (false === $post) {
			FatUtility::dieJsonError( $frm->getValidationErrors() );
		}
		
		$_reason_ids = $post['issues_to_report'];
		if ( empty( $_reason_ids )) {
			FatUtility::dieJsonError(Label::getLabel('LBL_Please_Choose_Issue_to_Report'));
		}

		$lessonId = $post['slesson_id'];

		/* [ check If Already resolved */
		if (IssuesReported::isAlreadyResolved($lessonId)) {
			FatUtility::dieJsonError( Label::getLabel('LBL_Issue_Already_Resolved_/_inprogress') );				
		}
		/* ] */		
		
		$reportedArr = array();
		$reportedArr['issrep_status'] = IssuesReported::STATUS_PROGRESS;
		$reportedArr['issrep_resolve_comments'] = $post['issue_reported_msg'];
		$reportedArr['issrep_issues_resolve'] = implode(',', $_reason_ids );
		
		$record = new IssuesReported( $post['issue_id'] );
        $record->assignValues( $reportedArr );
        if (!$record->save()) {
			Message::addErrorMessage($record->getError());			
			FatUtility::dieJsonError($record->getError());
        }
		
		$reason_html = '';
		$issues_options = IssueReportOptions::getOptionsArray( $this->siteLangId );
		
		foreach ( $_reason_ids as $_id ) {
			$reason_html .= $issues_options[$_id].'<br />';
		}

		Message::addMessage(Label::getLabel( 'LBL_Lesson_Issue_Updated_Successfully!', $this->siteLangId ));		
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Issue_Reported_Successfully!'));
	
	}
	
	public function searchLessons(&$srch, $post = array()) {
		$srch = new ScheduledLessonSearch(false);
		$srch->joinOrder();
		$srch->joinOrderProducts();
		$srch->joinTeacher();
		$srch->joinLearner();
		$srch->joinLearnerCountry( $this->siteLangId );
		$srch->addCondition( 'slns.slesson_teacher_id',' = ', UserAuthentication::getLoggedUserId() );
		$srch->addCondition( 'order_is_paid',' = ', Order::ORDER_IS_PAID );
		
		$srch->joinTeacherSettings();
		//$srch->joinTeacherTeachLanguage( $this->siteLangId );
		$srch->addOrder('slesson_date','DESC');
		$srch->addOrder('slesson_status','ASC');
		
		$srch->addMultipleFields(array(
			'slns.slesson_id',
			'slns.slesson_learner_id as learnerId',
			'slns.slesson_teacher_id as teacherId',
			'slns.slesson_slanguage_id',
			'ul.user_first_name as learnerFname',
			'ul.user_last_name as learnerLname',
			'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
			/* 'ul.user_timezone as learnerTimeZone', */
			'IFNULL(learnercountry_lang.country_name, learnercountry.country_code) as learnerCountryName',
			'slns.slesson_date',
			'slns.slesson_end_date',
			'slns.slesson_start_time',
			'slns.slesson_end_time',
			'slns.slesson_status',
			'slns.slesson_is_teacher_paid',
			//'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
			'"-" as teacherTeachLanguageName',
			'op_lpackage_is_free_trial as is_trial',
			'op_lesson_duration'
		));
	}
	
	
	public function issueResolveSetupStepTwo() {
		$frm = $this->getIssueReportedFrm();
		$post = $frm->getFormDataFromArray( FatApp::getPostedData() );
		
		if (false === $post) {
			FatUtility::dieJsonError( $frm->getValidationErrors() );
		}
		
		$_reason_ids = $post['issues_to_report'];
		$lessonId = $post['slesson_id'];
		$issueId = $post['issue_id'];
		$issue_resolve_type = $post['issue_resolve_type'];
		$issuesReportedDetails = self::getIssueDetails($issueId);
		
		$reportedArr = array();
		$reportedArr['issrep_status'] = IssuesReported::STATUS_RESOLVED;
		$reportedArr['issrep_issues_resolve'] = $post['issue_resolve_type'];
		$reportedArr['issrep_updated_on'] = date('Y-m-d H:i:s');
		
		$record = new IssuesReported( $issueId );
		$record->assignValues( $reportedArr );
		
        if ( !$record->save() ) {
			Message::addErrorMessage($record->getError());			
			FatUtility::dieJsonError($record->getError());
        }
		
		/*********************/
		switch( $issue_resolve_type ) {
			case 1 :
			
			break;
			
			case 2 :
			
			break;
			
		}
		
		
		
		$reason_html = '';
		$issues_options = IssueReportOptions::getOptionsArray( $this->siteLangId );
		
		foreach ( $_reason_ids as $_id ) {
			$reason_html .= $issues_options[$_id].'<br />';
		}

		Message::addMessage(Label::getLabel( 'LBL_Lesson_Issue_Updated_Successfully!', $this->siteLangId ));		
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Lesson_Issue_Reported_Successfully!'));
	
	}
	
	
	private function getIssueReportedFrm() {
		$frm = new Form('issueResolveFrm');
		$arr_options = IssueReportOptions::getOptionsArray( $this->siteLangId );
		$fldIssue = $frm->addCheckBoxes( Label::getLabel('LBL_Issue_To_Resolve'), 'issues_to_report',  $arr_options, array());
		$fldIssue->requirement->setSelectionRange(1, count( $arr_options ));
		$fld = $frm->addTextArea( Label::getLabel('LBL_Comment'),'issue_reported_msg','');
		$fld->requirement->setRequired(true);
		$fld = $frm->addHiddenField( '', 'issue_id' );
		$fld = $frm->addHiddenField( '', 'slesson_id' );
		$fld->requirements()->setRequired();
		$fld->requirements()->setIntPositive();
		$frm->addSubmitButton( '', 'submit', Label::getLabel('LBL_Next') );
		return $frm;
	}
	
	private function getIssueReportedFrmStepTwo() {
		$frm = new Form('issueResolveFrmStepTwo');
		$arr_options = IssuesReported::RESOLVE_TYPE;
		$fldIssue = $frm->addRadioButtons( Label::getLabel('LBL_How_would_you_like_to_resolve_this?'), 'issue_resolve_type',  $arr_options );
		$fldIssue->requirement->setRequired(true);
		$fld = $frm->addTextArea( Label::getLabel('LBL_Comment'),'issue_reported_msg','');
		$fld->requirement->setRequired(true);
		$fld = $frm->addHiddenField( '', 'issue_id' );
		$fld = $frm->addHiddenField( '', 'slesson_id' );
		$fld->requirements()->setRequired();
		$fld->requirements()->setIntPositive();
		$frm->addSubmitButton( '', 'submit', Label::getLabel('LBL_Resolve') );
		return $frm;
	}
	
}