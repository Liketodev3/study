<?php
class LabelController extends AdminBaseController
{
   
    public function __construct($action)
    {
        $ajaxCallArray = array(
            'form',
            'search',
            'setup'
        );
        if (!FatUtility::isAjaxCall() && in_array($action, $ajaxCallArray)) {
            die($this->str_invalid_Action);
        }
        parent::__construct($action);
		$this->objPrivilege->canViewLanguageLabel();
       
    }
    public function index()
    {
        $frmSearch = $this->getSearchForm();
		$adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit  = $this->objPrivilege->canEditLanguageLabel($adminId, true);
        $this->set("canEdit", $canEdit);
        $this->set("frmSearch", $frmSearch);
        $this->_template->render();
    }
    public function search()
    {
        
        $pagesize   = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data       = FatApp::getPostedData();
        //$page = ( empty($data['page']) || $data['page'] <= 0 ) ? 1 : $data['page'];
        $page       = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $post = $searchForm->getFormDataFromArray($data);
		if( false === $post ){
			Message::addErrorMessage($searchForm->getValidationErrors());
			FatUtility::dieWithError( Message::getHtml() );	
		}
        $srch = Label::getSearchObject();
        $srch->joinTable('tbl_languages', 'inner join', 'label_lang_id = language_id and language_active = ' . applicationConstants::ACTIVE);
        $srch->addOrder('lbl.' . Label::DB_TBL_PREFIX . 'lang_id', 'ASC');
        $srch->addGroupBy('lbl.' . Label::DB_TBL_PREFIX . 'key');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        if (!empty($post['keyword'])) {
            $cond = $srch->addCondition('lbl.label_key', 'like', '%' . $post['keyword'] . '%', 'AND');
            $cond->attachCondition('lbl.label_caption', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        $srch->addCondition('lbl.label_lang_id', '=', $this->adminLangId);
        $rs      = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
		
		$adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit  = $this->objPrivilege->canEditLanguageLabel($adminId, true);
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }
    public function form($label_id)
    {
        
        $label_id = FatUtility::int($label_id);
        if ($label_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $data = Label::getAttributesById($label_id, array(
            'label_key'
        ));
        if ($data == false) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $labelKey = $data['label_key'];
        $frm      = $this->getForm($labelKey);
        $srch     = Label::getSearchObject();
        $srch->addCondition('lbl.label_key', '=', $labelKey);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs     = $srch->getResultSet();
        $record = array();
        if ($rs) {
            $record = FatApp::getDb()->fetchAll($rs, 'label_lang_id');
        }
        if ($record == false) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $arr = array();
        foreach ($record as $k => $v) {
            $arr['label_key']          = $v['label_key'];
            $arr['label_caption' . $k] = $v['label_caption'];
        }
        $frm->fill($arr);
        $this->set('labelKey', $labelKey);
        $this->set('frm', $frm);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    }
    public function setup()
    {
        $this->objPrivilege->canEditLanguageLabel();
        $data = FatApp::getPostedData();
        $frm  = $this->getForm($data['label_key']);
        $post = $frm->getFormDataFromArray($data);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $labelKey = $post['label_key'];
        $srch     = Label::getSearchObject();
        $srch->addCondition('lbl.label_key', '=', $labelKey);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs     = $srch->getResultSet();
        $record = FatApp::getDb()->fetchAll($rs, 'label_lang_id');
        if ($record == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            $data = array(
                'label_lang_id' => $langId,
                'label_key' => $labelKey,
                'label_caption' => $post['label_caption' . $langId]
            );
            $obj  = new Label();
            if (!$obj->addUpdateData($data)) {
                Message::addErrorMessage($obj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
  
    private function getSearchForm()
    {
        
        $frm        = new Form('frmLabelsSearch');
        $f1         = $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $frm->addHiddenField('', 'page', 1);        
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
    private function getForm($label_key)
    {
        
        $frm = new Form('frmLabels');
        $frm->addHiddenField('', 'label_key', $label_key);
        $languages = Language::getAllNames();
        $frm->addTextbox(Label::getLabel('LBL_Key', $this->adminLangId), 'key', $label_key);
        foreach ($languages as $langId => $langName) {
            //$frm->addRequiredField($langName,'label_caption'.$langId);
            $fld = null;
            $fld = $frm->addTextArea($langName, 'label_caption' . $langId);
            $fld->requirements()->setRequired();
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}