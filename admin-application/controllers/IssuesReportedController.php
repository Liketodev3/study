<?php 
class IssuesReportedController extends AdminBaseController
{
    public function __construct($action)
    {
		parent::__construct($action);				
		$this->objPrivilege->canViewIssuesReported();		
    }
    
    public function index()
    {
        $frmSearch = $this->getIssuesReportedForm();
        $data      = FatApp::getPostedData();

        $frmSearch->fill($data);

        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }
	
    private function getIssuesReportedForm()
    {
        $frm          = new Form('frmIssuesReportedSearch');
		$status_options  = array(
            '-1' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + IssuesReported::getStatusArr($this->adminLangId);
		$user_options  = array(
            '0' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + User::getUserTypesArr($this->adminLangId);

        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher', '', array(
            'id' => 'keyword',
            'autocomplete' => 'off'
        ));

		$keyword      = $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner', '', array(
            'id' => 'keyword',
            'autocomplete' => 'off'
        ));		
        $frm->addTextBox(Label::getLabel('LBL_Order_Id', $this->adminLangId), 'slesson_order_id');		
        $frm->addSelectBox(Label::getLabel('LBL_Issue_Status', $this->adminLangId), 'issrep_status', $status_options, -1, array(), '');		
        $frm->addSelectBox(Label::getLabel('LBL_Reported_By', $this->adminLangId), 'issrep_reported_by', $user_options, 0, array(), '');		

        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'slesson_teacher_id', 0);
        $frm->addHiddenField('', 'slesson_learner_id', 0);
        $fld_submit = $frm->addSubmitButton('&nbsp;', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }	
	
    public function search()
    {
        $pagesize  = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $frmSearch = $this->getIssuesReportedForm();
        $data      = FatApp::getPostedData();
        $post      = $frmSearch->getFormDataFromArray($data);

        $page      = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
		$srch = IssuesReported::getSearchObject();
		$srch->addMultipleFields( array(
			"i.*",
			'sl.slesson_order_id',
			'CONCAT(u.user_first_name, " " , u.user_last_name) AS reporter_username',
			) 
		);
		$srch->addCondition('issrep_is_for_admin', '=', 1);
		
		if (isset($post['issrep_status']) AND $post['issrep_status'] > -1) {
			$status = FatUtility::int($post['issrep_status']);
			$srch->addCondition('issrep_status', '=', $status);
		}		
		if (isset($post['issrep_reported_by']) AND $post['issrep_reported_by'] > 0) {
			$status = FatUtility::int($post['issrep_reported_by']);
			$srch->addCondition('issrep_reported_by', '=', $status);
		}		
		if (isset($post['slesson_order_id']) AND $post['slesson_order_id'] != Null) {
			$srch->addCondition('slesson_order_id', 'LIKE', '%'.$post['slesson_order_id'].'%');
		}		
        if (isset($post['slesson_teacher_id']) AND $post['slesson_teacher_id'] > 0) {
            $user_is_teacher = FatUtility::int($post['slesson_teacher_id']);
            $srch->addCondition('slesson_teacher_id', '=', $user_is_teacher);
        }		
        if (isset($post['slesson_learner_id']) AND $post['slesson_learner_id'] > 0) {
            $user_is_learner = FatUtility::int($post['slesson_learner_id']);
            $srch->addCondition('slesson_learner_id', '=', $user_is_learner);
        }
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $srch->addOrder('issrep_status', 'ASC');                        
        $srch->addOrder('issrep_added_on', 'DESC');        
        $rs      = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $adminId = AdminAuthentication::getLoggedAdminId();
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false, null, false, false);
    }

    public function viewDetail($issue_id) {
        $issue_id = FatUtility::int($issue_id);
		$statusArr = ScheduledLesson::getStatusArr();
        if (1 > $issue_id) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
		$issRepObj = new IssuesReported($issue_id);
		$srch = $issRepObj->getIssueDetails();
		$srch->joinTable( UserSetting::DB_TBL, 'INNER JOIN', 'sl.slesson_teacher_id = us.us_user_id', 'us' );
		$srch->joinTable( TeachingLanguage::DB_TBL, 'INNER JOIN', 'sl.slesson_slanguage_id = tlang.tlanguage_id', 'tlang' );
		if ( $this->adminLangId > 0) {
			$srch->joinTable( TeachingLanguage::DB_TBL_LANG, 'LEFT OUTER JOIN','sll.tlanguagelang_tlanguage_id = tlang.tlanguage_id AND sll.tlanguagelang_lang_id = ' . $this->adminLangId, 'sll');
		}
		$srch->joinTable( User::DB_TBL, 'INNER JOIN', 'sl.slesson_learner_id = ul.user_id', 'ul' );        
		$srch->joinTable( User::DB_TBL, 'INNER JOIN', 'sl.slesson_teacher_id = ut.user_id', 'ut' );        

		$srch->addMultipleFields( array(
			"i.*",
			"us.*",
			'sl.slesson_order_id',
			'sl.slesson_teacher_id',
			'sl.slesson_learner_id',
			'sl.slesson_learner_join_time',
			'sl.slesson_teacher_join_time',
			'sl.slesson_learner_end_time',
			'sl.slesson_teacher_end_time',
			'sl.slesson_ended_by',
			'sl.slesson_ended_on',
			'IFNULL(sll.tlanguage_name, tlang.tlanguage_identifier) as tlanguage_name',
			'CONCAT(u.user_first_name, " " , u.user_last_name) AS reporter_username',
			'CONCAT(ul.user_first_name, " " , ul.user_last_name) AS learner_username',
			'CONCAT(ut.user_first_name, " " , ut.user_last_name) AS teacher_username',
			) 
		);		
        $rs      = $srch->getResultSet();
        $issueDetail = FatApp::getDb()->fetch($rs);
		//$commet_teacher_id = $issueDetail['issrep_slesson_id'].'_'.$issueDetail['slesson_teacher_id'];
		$callHistory = IssuesReported::getCallHistory($issueDetail['slesson_teacher_id']);
		$issueStatusArr = IssuesReported::getStatusArr($this->adminLangId);		
        $this->set("callHistory", $callHistory);
        $this->set("statusArr", $issueStatusArr);
        $this->set("issueDetail", $issueDetail);
		$this->set('issues_options', IssueReportOptions::getOptionsArray( $this->adminLangId ));
        $this->_template->render(false, false);
    }	
	
	public function transaction($slessonId,$issueId)
    {
        $slessonId = FatUtility::int($slessonId);
        if (1 > $slessonId) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
		$canEdit = 		$this->objPrivilege->canEditIssuesReported($this->admin_id, true);
        $post     = FatApp::getPostedData();
        $srch     = Transaction::getSearchObject();
		$srch->joinTable( User::DB_TBL, 'INNER JOIN', 'utxn.utxn_user_id = u.user_id', 'u' );		
		$srch->addCondition('utxn.utxn_type', '=', Transaction::TYPE_ISSUE_REFUND);
		$srch->addCondition('utxn.utxn_slesson_id', '=', $slessonId);			
        $srch->addMultipleFields(array(
            'utxn.*',
			'CONCAT(u.user_first_name, " " , u.user_last_name) AS username'
        ));
        $srch->addOrder('utxn_id', 'DESC');
        $srch->addGroupBy('utxn.utxn_id');
        $rs      = $srch->getResultSet();
        $records = array();
		if(!$rs){
            trigger_error($srch->getError(), E_USER_ERROR);
		}
        $records = FatApp::getDb()->fetchAll($rs);
        $this->set("arr_listing", $records);
        $this->set('postedData', $post);
        $this->set('slessonId', $slessonId);
        $this->set('issueId', $issueId);
        $this->set('statusArr', Transaction::getStatusArr($this->adminLangId));
		$this->set("canEdit", $canEdit);		
        $this->_template->render(false, false);
    }	

	public function addLessonTransaction($lessonId,$issueId)
    {
		$this->objPrivilege->canEditIssuesReported($this->admin_id, true);
        $lessonId = FatUtility::int($lessonId);
        $issueId = FatUtility::int($issueId);
        if (1 > $lessonId || 1 > $issueId) {
            FatUtility::dieWithError($this->str_invalid_request_id);
        }
        $frm = $this->addLessonTransactionForm($this->adminLangId);
		$issRepObj = new IssuesReported($issueId);
		$srch = $issRepObj->getIssueDetails();
		$srch->addMultipleFields( array(
			"i.*",
			'u.user_id',
			'CONCAT(u.user_first_name, " " , u.user_last_name) AS reporter_username',
			) 
		);		
        $rs      = $srch->getResultSet();
        $issueDetail = FatApp::getDb()->fetch($rs);
        $frm->fill(array(
            'user_id' => $issueDetail['user_id'],
			'slesson_id' => $lessonId,
			'issue_id' => $issueId
        ));
		$reporterName = $issueDetail['reporter_username']." (".User::getUserTypesArr($this->adminLangId)[$issueDetail['issrep_reported_by']].")";
        $this->set('reporterName', $reporterName);
        $this->set('lessonId', $lessonId);
        $this->set('issueId', $issueId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
    
	public function setupLessonTransaction()
    {
		$this->objPrivilege->canEditIssuesReported($this->admin_id, true);
        $frm  = $this->addLessonTransactionForm($this->adminLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userId = FatUtility::int($post['user_id']);
        if (1 > $userId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $tObj = new Transaction($userId);
        $data = array(
            'utxn_user_id' => $userId,
            'utxn_date' => date('Y-m-d H:i:s'),
            'utxn_comments' => $post['description'],
            'utxn_status' => Transaction::STATUS_COMPLETED,
            'utxn_type' => Transaction::TYPE_ISSUE_REFUND,
			'utxn_slesson_id' => $post['slesson_id']
        );
        if ($post['type'] == Transaction::CREDIT_TYPE) {
            $data['utxn_credit'] = $post['amount'];
        }
        if ($post['type'] == Transaction::DEBIT_TYPE) {
            $data['utxn_debit'] = $post['amount'];
        }
        if (!$tObj->addTransaction($data)) {
            Message::addErrorMessage($tObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        /* send email to user[ */
        $emailNotificationObj = new EmailHandler();
        $emailNotificationObj->sendTxnNotification($tObj->getMainTableRecordId(), $this->adminLangId);
        /* ] */
        $this->set('slessonId', $post['slesson_id']);
        $this->set('issueId', $post['issue_id']);
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }	
	
    private function addLessonTransactionForm($langId)
    {
        $frm = new Form('frmUserTransaction');
        $frm->addHiddenField('', 'user_id');
        $frm->addHiddenField('', 'slesson_id');
        $frm->addHiddenField('', 'issue_id');
        $typeArr = Transaction::getCreditDebitTypeArr($langId);
        $frm->addSelectBox(Label::getLabel('LBL_Type', $this->adminLangId), 'type', $typeArr)->requirements()->setRequired(true);
        $frm->addRequiredField(Label::getLabel('LBL_Amount', $this->adminLangId), 'amount')->requirements()->setFloatPositive();
        $frm->addTextArea(Label::getLabel('LBL_Description', $this->adminLangId), 'description')->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }	

	public function updateStatus(){
        if(!$this->objPrivilege->canEditIssuesReported($this->admin_id, true)){
            FatUtility::dieJsonError($this->unAuthorizeAccess);            
        }                
		$data = FatApp::getPostedData();
		if(IssuesReported::getIssueStatus($data['issue_id']) == IssuesReported::STATUS_RESOLVED){
            FatUtility::dieJsonError(Label::getLabel("LBL_Status_Already_Resolved", CommonHelper::getLangId()));
		}
		$assignValues = array('issrep_status' => $data['issue_status'],'issrep_updated_on' => date('Y-m-d H:i:s'));
		if (!FatApp::getDb()->updateFromArray(IssuesReported::DB_TBL, $assignValues, array('smt' => 'issrep_id = ?', 'vals' => array($data['issue_id'])))) {
            FatUtility::dieJsonError(Label::getLabel("LBL_SYSTEM_ERROR", CommonHelper::getLangId()));
		}		
        $this->set('msg', 'Updated Successfully.');
        $this->_template->render(false, false, 'json-success.php');
	}	
	
	
}