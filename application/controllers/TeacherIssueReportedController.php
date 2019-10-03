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

		/* [ check If Already reorted */
		if (IssuesReported::isAlreadyReported($lessonId,User::USER_TYPE_LEANER)) {
			FatUtility::dieJsonError( Label::getLabel('LBL_Issue_Already_Resolved_/_inprogress') );				
		}
		/* ] */		
		
		$srch = new stdClass();
		$this->searchLessons( $srch );
		$srch->joinLearnerCredentials();
		$srch->doNotCalculateRecords();
		$srch->addCondition( 'slesson_id', '=', $lessonId );
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
		$lessonRow = FatApp::getDb()->fetch( $rs );
		if( empty($lessonRow) ){
			FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
		}

		$reportedArr = array();
		$reportedArr['issrep_status'] = $IssuesReported::STATUS_PROGRESS;
		$reportedArr['issrep_resolve_comments'] = $post['issue_reported_msg'];
		$reportedArr['issrep_issues_resolve'] = implode(',', $_reason_ids );
		
		$record = new IssuesReported();
        $record->assignValues($reportedArr);
        if(!$record->save()) {
			Message::addErrorMessage($record->getError());			
			FatUtility::dieJsonError($record->getError());
        }
		
		$reason_html = '';
		$issues_options = IssueReportOptions::getOptionsArray( $this->siteLangId );
		
		foreach ( $_reason_ids as $_id ) {
			$reason_html .= $issues_options[$_id].'<br />';
		}

		/* [ */
		$tpl = 'teacher_resolved_issue_email';
		$vars = array(
			'{learner_name}' => $lessonRow['learnerFullName'],
			'{teacher_name}' => $lessonRow['teacherFullName'],
			'{lesson_name}' => $lessonRow['teacherTeachLanguageName'],
			'{lesson_issue_resolve}' => $reason_html,
			'{learner_comment}' => $post['issue_reported_msg'],
			'{lesson_date}' => $lessonRow['slesson_date'],
			'{lesson_start_time}' => $lessonRow['slesson_start_time'],
			'{lesson_end_time}' => $lessonRow['slesson_end_time'],
			'{action}' => Label::getLabel('LBL_Teacher'),
		);

		if( !EmailHandler::sendMailTpl($lessonRow['teacherEmailId'], $tpl ,$this->siteLangId, $vars) ){
			Message::addErrorMessage(Label::getLabel( 'LBL_Mail_not_sent!', $this->siteLangId ));					
			FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
		}
		/* ] */

		Message::addMessage(Label::getLabel( 'LBL_Lesson_Issue_Reported_Successfully!', $this->siteLangId ));		
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
		$frm->addSubmitButton( '', 'submit', Label::getLabel('LBL_Send') );
		return $frm;
	}
}