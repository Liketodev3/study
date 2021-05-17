<?php
class GdprRequestsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewGdprRequests($this->admin_id, true);
      
    }

    public function index()
    {
        $frmSrch = $this->getSearchForm($this->adminLangId);
        $this->set('frmSrch', $frmSrch);
        $this->_template->render();
    }

    public function search()
    {
        $canEdit = $this->objPrivilege->canEditGdprRequests($this->admin_id, true);
        $frmSrch = $this->getSearchForm($this->adminLangId);
        $post = $frmSrch->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieWithError($frmSrch->getValidationErrors());
        }
        $srch = Gdpr::getSearchObj();
        if (isset($post['keyword']) && !empty($post['keyword'])) {
           $srch->addCondition('gdprdatareq_reason', 'LIKE', '%' . $post['keyword'] . '%');
        }

        if (isset($post['status']) && $post['status'] !== "") {
            $srch->addCondition('gdprdatareq_status', '=', $post['status']);
        }
        if ($post['added_on']) {
            $srch->addCondition('gdprdatareq_added_on', 'LIKE', $post['added_on'] . '%');
        }
        $page = $post['page'];
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->addOrder('gdprdatareq_added_on', 'DESC');
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $rs = $srch->getResultSet();
        $gdprRequests = FatApp::getDb()->fetchAll($rs);   
        
        /* [ */
        $totalRecords = $srch->recordCount();
        $this->set('postedData', $post);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->set('canEdit', $canEdit);
        $this->set('recordCount', $srch->recordCount());
        $startRecord = ($page - 1) * $pageSize + 1;
        $endRecord = $page * $pageSize;
        if ($totalRecords < $endRecord) {
            $endRecord = $totalRecords;
        }
        $this->set('startRecord', $startRecord);
        $this->set('endRecord', $endRecord);
        $this->set('totalRecords', $totalRecords);
        /* ] */
        $this->set('gdprStatus', Gdpr::getStatusArr($this->adminLangId));
        $this->set('gdprRequests', $gdprRequests);
        $this->_template->render(false, false, null, false, false);
    }

    public function view()
    {
        $id = FatApp::getPostedData('id',FatUtility::VAR_INT,0);
        if($id <= 0){
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request',$this->adminLangId));
        }
        $srch = new GdprReqSearch();
        $srch->joinUser();
        $srch->addCondition('gdprdatareq_id','=',$id);
        $rs = $srch->getResultSet();       
        $reqDetail = FatApp::getDb()->fetch($rs); 

        if(!$reqDetail){
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request',$this->adminLangId));
        }

        $frm = $this->changeGdprRequestStatusForm($this->adminLangId);
        $frm->fill($reqDetail);

        $this->set("data", $reqDetail);
        $this->set("frm", $frm);
        $this->_template->render(false, false);
    }

    protected function getSearchForm(int $langId)
    {
        $frm = new Form('frmSrch');
        $frm->addTextBox(Label::getLabel('LBL_Search_By_Keyword',$langId), 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_By_Keyword',$langId)));
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $frm->addSelectBox(Label::getLabel('LBl_Status',$langId), 'status', Gdpr::getStatusArr($this->adminLangId));
        $frm->addDateField(Label::getLabel('LBl_Added_On',$langId), 'added_on');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search',$langId));
        $frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_Reset',$langId)); 
        return $frm;
    }

    private function changeGdprRequestStatusForm(int $langId)
    {
        $frm = new Form('changeStatusForm');
        $frm->addSelectBox(Label::getLabel('LBL_Request_Status'), 'gdprdatareq_status', Gdpr::getStatusArr($this->adminLangId), '')->requirements()->setRequired();
        $frm->addHiddenField('', 'gdprdatareq_id', 0);
        $frm->addSubmitButton('', 'btn_submit', 'Update');
        return $frm;
    }

    public function updateStatus()
    {
        $gdpr_request_id = FatApp::getPostedData('gdprdatareq_id', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('gdprdatareq_status', FatUtility::VAR_INT, 0);
        if ($gdpr_request_id > 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $statusFetched = Gdpr::getAttributesById($gdpr_request_id, 'gdprdatareq_status');
        if ( is_null($statusFetched) ) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $assignValues = array('gdprdatareq_status' => $status);
        $record = new Gdpr($gdpr_request_id);
        $record->assignValues($assignValues);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Updated_Successfully!'));
    }

    private function eraseUserPersonalData()
    {
        $userID = FatApp::getPostedData('gdprdatareq_user_id', FatUtility::VAR_INT, 0);
        $eraseUserData = new Gdpr();
        if(!$eraseUserData->truncateUserPersonalData($userID))
        {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', 'Data Truncated Successfully!');
        $this->set('gdprdatareq_user_id', $userID);
        $this->_template->render(false, false, 'json-success.php');
    }
}
