<?php
class TopLanguagesReportController extends AdminBaseController {
	private $canView;
	private $canEdit;
	
	public function __construct($action){ 
		parent::__construct($action);
		$this->admin_id = AdminAuthentication::getLoggedAdminId();
		$this->canView = $this->objPrivilege->canViewTopLangReport($this->admin_id,true);
		$this->canEdit = $this->objPrivilege->canEditTopLangReport($this->admin_id,true);
		$this->set( "canView", $this->canView );
		$this->set( "canEdit", $this->canEdit );		
	}
	
	public function index($orderDate = '') {
		$this->objPrivilege->canViewTopLangReport();	
		$frmSearch = $this->getSearchForm($orderDate);
		$this->set('frmSearch',$frmSearch);	
		$this->set('orderDate',$orderDate);	
		$this->_template->render();
	}
	
	public function search() {
		$this->objPrivilege->canViewTopLangReport();
		$db = FatApp::getDb();
		$orderDate = FatApp::getPostedData('orderDate') ;
		
		$srchFrm = $this->getSearchForm($orderDate);
		
		$post = $srchFrm->getFormDataFromArray( FatApp::getPostedData() );
		$page = (empty($post['page']) || $post['page'] <= 0) ? 1 : intval($post['page']);
		$pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
		
		$srch = AdminStatistic::LessonLanguagesObject($this->adminLangId);
		$srch->addGroupBy('slesson_slanguage_id');
		if ( empty($orderDate) ) {
			$date_from = FatApp::getPostedData('date_from', FatUtility::VAR_DATE, '') ;
			if ( !empty($date_from) ) {
				$srch->addCondition('slesson_added_on', '>=', $date_from. ' 00:00:00');
			}
			
			$date_to = FatApp::getPostedData('date_to', FatUtility::VAR_DATE, '') ;
			if ( !empty($date_to) ) {
				$srch->addCondition('slesson_added_on', '<=', $date_to. ' 23:59:59');
			}						
		} else {
			$this->set( 'orderDate', $orderDate );
			$srch->addCondition('slesson_added_on', '>=', $orderDate. ' 00:00:00');
			$srch->addCondition('slesson_added_on', '<=', $orderDate. ' 23:59:59');
		}		
		
		$srch->addOrder('lessonsSold','desc');
		$srch->setPageNumber($page);
		$srch->setPageSize($pagesize);
		$rs = $srch->getResultSet();
		$arr_listing = $db->fetchAll($rs);
		
		$this->set("arr_listing",$arr_listing);
		$this->set('pageCount', $srch->pages());
		$this->set('recordCount', $srch->recordCount());
		$this->set('page', $page);
		$this->set('pageSize', $pagesize);
		$this->set('postedData', $post);
		$this->_template->render(false,false);
	}
	
	public function viewSchedules( $langId ) {
		
		$srch = new ScheduledLessonSearch();
		$srch->joinTeacher();
		$srch->joinLearner();
		$srch->joinTeacherSettings();
		$srch->joinLessonLanguage( $this->adminLangId );
		$srch->addCondition( 'slns.slesson_slanguage_id',' = ', $langId );
		$srch->addMultipleFields(
			array(
			'slns.slesson_id',
			'slns.slesson_date',
			'slns.slesson_status',
			'IFNULL(sl.tlanguage_name, tlang.tlanguage_identifier) as teacherTeachLanguageName',
			'CONCAT(ul.user_first_name, " " , ul.user_last_name) AS learner_username',
			'CONCAT(ut.user_first_name, " " , ut.user_last_name) AS teacher_username',
			));
		
		$rs = $srch->getResultSet();
		$data = FatApp::getDb()->fetchAll($rs);
		if ( $data == false ) { 
			Message::addErrorMessage('Error: Lessons not allocated yet.');
			FatApp::redirectUser(FatUtility::generateUrl("TopLanguagesReport"));
		}
		 
		
		$statusArr = ScheduledLesson::getStatusArr();
        $this->set( 'arr_listing', $data );
        $this->set( 'status_arr', $statusArr );
        $this->_template->render();
	}
	
	
	private function getSearchForm($orderDate = ''){
		$frm = new Form('frmSalesReportSearch');
		$frm->addHiddenField('','page');		
		$frm->addHiddenField('','orderDate',$orderDate);	
		if(empty($orderDate)){	
			$frm->addDateField(Label::getLabel('LBL_Date_From',$this->adminLangId), 'date_from','',array('readonly' => 'readonly','class' => 'small dateTimeFld field--calender' ));
			$frm->addDateField(Label::getLabel('LBL_Date_To',$this->adminLangId), 'date_to' , '', array('readonly' => 'readonly','class' => 'small dateTimeFld field--calender'));
			$fld_submit = $frm->addSubmitButton('','btn_submit',Label::getLabel('LBL_Search',$this->adminLangId));
			$fld_cancel = $frm->addButton("","btn_clear",Label::getLabel('LBL_Clear_Search',$this->adminLangId),array('onclick'=>'clearSearch();'));
			$fld_submit->attachField($fld_cancel);
		}
		return $frm;
	}	
}	