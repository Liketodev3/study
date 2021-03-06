<?php

class BlogCommentsController extends AdminBaseController
{

    private $canView;
    private $canEdit;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->canView = $this->objPrivilege->canViewBlogComments($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditBlogComments($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        $this->objPrivilege->canViewBlogComments();
        $search = $this->getSearchForm();
        $data = FatApp::getPostedData();
        if ($data) {
            $data['bpcomment_id'] = $data['id'];
            unset($data['id']);
            $search->fill($data);
        }
        $this->set("search", $search);
        $this->_template->render();
    }

    public function search()
    {
        $this->objPrivilege->canViewBlogComments();
        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $post = $searchForm->getFormDataFromArray($data);
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : intval($post['page']);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = BlogComment::getSearchObject(true, $this->adminLangId);
        if (!empty($post['keyword'])) {
            $keywordCond = $srch->addCondition('bpcomment_author_name', 'like', '%' . $post['keyword'] . '%');
            $keywordCond->attachCondition('bpcomment_author_email', 'like', '%' . $post['keyword'] . '%');
        }
        if (isset($post['bpcomment_approved']) && $post['bpcomment_approved'] != '') {
            $srch->addCondition('bpcomment_approved', '=', $post['bpcomment_approved']);
        }
        if (isset($post['bpcomment_id']) && $post['bpcomment_id'] != '') {
            $srch->addCondition('bpcomment_id', '=', $post['bpcomment_id']);
        }
        $srch->addMultipleFields(['bpcomment_id', 'bpcomment_author_name', 'bpcomment_author_email', 'bpcomment_approved', 'bpcomment_added_on', 'post_id', 'ifnull(post_title,post_identifier) post_title']);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $srch->addOrder('bpcomment_added_on', 'desc');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $pageCount = $srch->pages();
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    public function view($bpcomment_id)
    {
        $this->objPrivilege->canViewBlogComments();
        $bpcomment_id = FatUtility::int($bpcomment_id);
        if ($bpcomment_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $frm = $this->getForm($bpcomment_id);
        $srch = BlogComment::getSearchObject(true, $this->adminLangId);
        $srch->addCondition('bpcomment_id', '=', $bpcomment_id);
        $data = FatApp::getDb()->fetch($srch->getResultSet());
        if ($data === false) {
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
        }
        $frm->fill($data);
        $statusArr = applicationConstants::getBlogCommentStatusArr($this->adminLangId);
        $this->set('statusArr', $statusArr);
        $this->set('data', $data);
        $this->set('frm', $frm);
        $this->set('bpcomment_id', $bpcomment_id);
        $this->_template->render(false, false);
    }

    public function updateStatus()
    {
        $this->objPrivilege->canEditBlogComments();
        $bpcomment_id = FatApp::getPostedData('bpcomment_id', FatUtility::VAR_INT, 0);
        if ($bpcomment_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $frm = $this->getForm($bpcomment_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $bpcomment_id = FatUtility::int($post['bpcomment_id']);
        unset($post['bpcomment_id']);
        $oldData = BlogComment::getAttributesById($bpcomment_id);
        $record = new BlogComment($bpcomment_id);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ($oldData['bpcomment_approved'] != $post['bpcomment_approved']) {
            $srch = BlogComment::getSearchObject(true, $this->adminLangId);
            $srch->addCondition('bpcomment_id', '=', $bpcomment_id);
            $newData = FatApp::getDb()->fetch($srch->getResultSet());
            $this->sendEmail($newData);
        }
        $this->set('msg', Label::getLabel('MSG_Blog_Post_Setup_Successful', $this->adminLangId));
        $this->set('bpcommentId', $bpcomment_id);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditBlogComments();
        $bpcomment_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($bpcomment_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $obj = new BlogComment($bpcomment_id);
        if (!$obj->canMarkRecordDelete($bpcomment_id)) {
            Message::addErrorMessage(Label::getLabel('MSG_Unauthorized_Access', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $obj->assignValues([BlogComment::tblFld('deleted') => 1]);
        if (!$obj->save()) {
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
    }

    private function getForm($bpcomment_id = 0)
    {
        $bpcomment_id = FatUtility::int($bpcomment_id);
        $frm = new Form('frmBlogComment', ['id' => 'frmBlogComment']);
        $frm->addHiddenField('', 'bpcomment_id', $bpcomment_id);
        $statusArr = applicationConstants::getBlogCommentStatusArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Comment_Status', $this->adminLangId), 'bpcomment_approved', $statusArr, '', ['class' => 'small'], '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch', ['id' => 'frmSearch']);
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '', ['class' => 'search-input']);
        $statusArr = applicationConstants::getBlogCommentStatusArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Comment_Status', $this->adminLangId), 'bpcomment_approved', $statusArr, '', ['class' => 'small'], 'Select');
        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'bpcomment_id');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

}
