<?php

class BlogContributionsController extends AdminBaseController
{

    private $canView;
    private $canEdit;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->canView = $this->objPrivilege->canViewBlogContributions($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditBlogContributions($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        $this->objPrivilege->canViewBlogContributions();
        $search = $this->getSearchForm();
        $data = FatApp::getPostedData();
        if ($data) {
            $data['bcontributions_id'] = $data['id'];
            unset($data['id']);
            $search->fill($data);
        }
        $this->set("search", $search);
        $this->_template->render();
    }

    public function search()
    {
        $this->objPrivilege->canViewBlogContributions();
        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $post = $searchForm->getFormDataFromArray($data);
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : intval($post['page']);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = BlogContribution::getSearchObject();
        if (!empty($post['keyword'])) {
            $keywordCond = $srch->addCondition('bcontributions_author_first_name', 'like', '%' . $post['keyword'] . '%');
            $keywordCond->attachCondition('bcontributions_author_last_name', 'like', '%' . $post['keyword'] . '%');
            $keywordCond->attachCondition('bcontributions_author_email', 'like', '%' . $post['keyword'] . '%');
            $keywordCond->attachCondition('bcontributions_author_phone', 'like', '%' . $post['keyword'] . '%');
        }
        if (isset($post['bcontributions_status']) && $post['bcontributions_status'] != '') {
            $srch->addCondition('bcontributions_status', '=', $post['bcontributions_status']);
        }
        if (isset($post['bcontributions_id']) && $post['bcontributions_id'] != '') {
            $srch->addCondition('bcontributions_id', '=', $post['bcontributions_id']);
        }
        $srch->addMultipleFields(['*', 'concat(bcontributions_author_first_name," ",bcontributions_author_last_name) author_name']);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $srch->addOrder('bcontributions_id', 'DESC');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    public function downloadAttachedFile($recordId, $recordSubid = 0)
    {
        $recordId = FatUtility::int($recordId);
        if (1 > $recordId) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_BLOG_CONTRIBUTION, $recordId, $recordSubid);
        if (false == $file_row) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $fileName = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
        AttachedFile::downloadAttachment($fileName, $file_row['afile_name']);
    }

    public function view($bcontributions_id)
    {
        $this->objPrivilege->canViewBlogContributions();
        $bcontributions_id = FatUtility::int($bcontributions_id);
        if ($bcontributions_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $frm = $this->getForm($bcontributions_id);
        $data = BlogContribution::getAttributesById($bcontributions_id);
        if ($data === false) {
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
        }
        $frm->fill($data);
        $statusArr = applicationConstants::getBlogContributionStatusArr($this->adminLangId);
        if ($attachedFile = AttachedFile::getAttachment(AttachedFile::FILETYPE_BLOG_CONTRIBUTION, $bcontributions_id)) {
            $this->set('attachedFile', $attachedFile['afile_name']);
        }
        $this->set('statusArr', $statusArr);
        $this->set('data', $data);
        $this->set('frm', $frm);
        $this->set('bcontributions_id', $bcontributions_id);
        $this->_template->render(false, false);
    }

    public function updateStatus()
    {
        $this->objPrivilege->canEditBlogContributions();
        $bcontributions_id = FatApp::getPostedData('bcontributions_id', FatUtility::VAR_INT, 0);
        if ($bcontributions_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $frm = $this->getForm($bcontributions_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $bcontributions_id = FatUtility::int($post['bcontributions_id']);
        unset($post['bcontributions_id']);
        $oldData = BlogContribution::getAttributesById($bcontributions_id);
        $record = new BlogContribution($bcontributions_id);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newData = BlogContribution::getAttributesById($bcontributions_id);
        if ($oldData['bcontributions_status'] != $newData['bcontributions_status']) {
            $this->sendEmail($newData);
        }
        $this->set('msg', Label::getLabel('MSG_Blog_Post_Setup_Successful', $this->adminLangId));
        $this->set('bcontributionsId', $bcontributions_id);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditBlogContributions();
        $bcontributions_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($bcontributions_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $obj = new BlogContribution($bcontributions_id);
        if (!$obj->deleteRecord()) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function sendEmail($data)
    {
        if (empty($data)) {
            return false;
        }
        $emailObj = new EmailHandler();
        $emailObj->sendBlogContributionStatusChangeEmail($this->adminLangId, $data);
    }

    private function getForm($bcontributions_id = 0)
    {
        $bcontributions_id = FatUtility::int($bcontributions_id);
        $frm = new Form('frmBlogContribution', ['id' => 'frmBlogContribution']);
        $frm->addHiddenField('', 'bcontributions_id', $bcontributions_id);
        $statusArr = applicationConstants::getBlogContributionStatusArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Contribution_Status', $this->adminLangId), 'bcontributions_status', $statusArr, '', ['class' => 'small'], '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch', ['id' => 'frmSearch']);
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '', ['class' => 'search-input']);
        $statusArr = applicationConstants::getBlogContributionStatusArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Contribution_Status', $this->adminLangId), 'bcontributions_status', $statusArr, '', ['class' => 'small'], 'Select');
        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'bcontributions_id');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

}
