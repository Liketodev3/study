<?php
class GdprRequestsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewPurchasedLessons($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditPurchasedLessons($this->admin_id, true);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        // $this->_template->addJs('js/jquery.datetimepicker.js');
        // $this->_template->addCss('css/jquery.datetimepicker.css');
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
        $srch = Gdpr::getSearchObj();
        if (isset($post['keyword']) && !empty($post['keyword'])) {
            $condition = $srch->addCondition('gdprdatareq_reason', 'LIKE', '%' . $post['keyword'] . '%');
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
       
        $user_timezone = MyDate::getUserTimeZone();

        /* [ */
        $totalRecords = $srch->recordCount();
        $this->set('postedData', $post);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
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
        $this->set('gdprRequests', $gdprRequests);
        $this->_template->render(false, false, null, false, false);
    }

    public function view()
    {
        $showReqStatus = Gdpr::getRequestStatus();
        $rs = $showReqStatus->getResultSet();
        $records = FatApp::getDb()->fetch($rs);
        $frm = $this->changeGdprRequestStatusForm();
        $frm->fill($records);
        $this->set("data", $records);
        $this->set("frm", $frm);
        $this->_template->render(false, false);
    }

    protected function getSearchForm()
    {
        $frm = new Form('frmSrch');
        $frm->addTextBox(Label::getLabel('LBL_Search_By_Keyword'), 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_By_Keyword')));
        $frm->addHiddenField('', 'teacher_id');
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setIntPositive();
        $frm->addSelectBox(Label::getLabel('LBl_Status'), 'status', TeacherGroupClasses::getStatusArr());
        $frm->addDateField(Label::getLabel('LBl_Added_On'), 'added_on');
        $btnSubmit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search'));
        $btnReset = $frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_Reset'));
        $btnSubmit->attachField($btnReset);
        return $frm;
    }

    private function changeGdprRequestStatusForm()
    {
        $frm = new Form('changeStatusForm');
        $statusArr = new Gdpr();
        $frm->addSelectBox('Status', 'gdprdatareq_status', $statusArr->getGdprAdminStatusArr($this->adminLangId), '')->requirements()->setRequired();
        $frm->addHiddenField('', 'gdprdatareq_id', 0);
        $frm->addHiddenField('', 'gdprdatareq_user_id', 0);
        $frm->addSubmitButton('', 'btn_submit', 'Update');
        return $frm;
    }

    public function updateStatus($gdpr_request_id = 0)
    {
        $gdpr_request_id = FatApp::getPostedData('gdprdatareq_id', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('gdprdatareq_status', FatUtility::VAR_INT, 0);
        if (1 > $gdpr_request_id) {
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
        // $statusArr = Gdpr::getGdprAdminStatusArr($this->adminLangId);
        // $postedByUser = $data['tlreview_postedby_user_id'];
        // $postedForUser = $data['tlreview_teacher_user_id'];
        // $statusName = $statusArr[$status];
        // $reveiwId = $data['tlreview_id'];
        /*$emailNotificationObj = new UserNotifications($postedByUser);
        $emailNotificationObj->sendOrderReviewStatusUpdateNotification($OrderId, compact('statusName', 'reveiwId'));
        if ($status == TeacherLessonReview::STATUS_APPROVED) {
            $emailNotificationObj = new UserNotifications($postedForUser);
            $emailNotificationObj->sendOrderReviewNotificationToSeller($OrderId, compact('reveiwId'));
        }*/


        // $this->set('msg', 'Updated Successfully.');
        // $this->set('gdprdatareq_id', $gdpr_request_id);
        // $this->_template->render(false, false, 'json-success.php');

        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Updated_Successfully!'));
    }

    public function eraseUserPersonalData()
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
