<?php
class UrlRewritingController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewUrlRewrite();
    }

    public function index()
    {
        $canEdit = $this->objPrivilege->canEditUrlRewrite();
        $srchFrm = $this->getSearchForm();
        $this->set("srchFrm", $srchFrm);
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }

    public function search()
    {
        $canEdit = $this->objPrivilege->canEditUrlRewrite();
        $pageSize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray($data);
        $srch = UrlRewrite::getSearchObject($this->adminLangId);
        $srch->joinTable(Language::DB_TBL, 'LEFT OUTER JOIN', 'lng.language_id = ur.urlrewrite_lang_id', 'lng');

        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('ur.urlrewrite_original', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('ur.urlrewrite_custom', 'like', '%' . $post['keyword'] . '%', 'OR');
        }


        if ($post['lang_id'] > 0) {
            $srch->addCondition('ur.urlrewrite_lang_id', '=', $post['lang_id']);
        }

        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);
        $srch->addMultipleFields([
            'urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom',
            'urlrewrite_http_code', 'language_code'
        ]);
        $srch->addOrder('urlrewrite_id', 'DESC');
        $srch->addOrder('urlrewrite_original', 'asc');
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $this->set("arr_listing", $records);
        $this->set("canEdit", $canEdit);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    public function form($urlrewrite_id = 0)
    {
        $this->objPrivilege->canViewUrlRewrite();
        $urlrewrite_id = FatUtility::int($urlrewrite_id);
        $frm = $this->getForm($urlrewrite_id);
        if (0 < $urlrewrite_id) {
            $srch = UrlRewrite::getSearchObject();
            $srch->joinTable(UrlRewrite::DB_TBL, 'LEFT OUTER JOIN', 'temp.urlrewrite_original = ur.urlrewrite_original', 'temp');
            $srch->addCondition('ur.urlrewrite_id', '=', $urlrewrite_id);
            $rs = $srch->getResultSet();
            $data = [];
            while ($row = FatApp::getDb()->fetch($rs)) {
                $data['urlrewrite_original'] = $row['urlrewrite_original'];
                $data['urlrewrite_custom'][$row['urlrewrite_lang_id']] = $row['urlrewrite_custom'];
                $data['urlrewrite_http_code'][$row['urlrewrite_lang_id']] = $row['urlrewrite_http_code'];
            }
            if (empty($data)) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('urlrewrite_id', $urlrewrite_id);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditUrlRewrite();
        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $row = [];
        $urlrewrite_id = FatUtility::int($post['urlrewrite_id']);
        unset($post['urlrewrite_id']);

        if (0 < $urlrewrite_id) {
            $srch = UrlRewrite::getSearchObject();
            $srch->joinTable(UrlRewrite::DB_TBL, 'LEFT OUTER JOIN', 'temp.urlrewrite_original = ur.urlrewrite_original', 'temp');
            $srch->addCondition('ur.urlrewrite_id', '=', $urlrewrite_id);
            $rs = $srch->getResultSet();
            $row = FatApp::getDb()->fetchAll($rs, 'urlrewrite_lang_id');
            $originalUrl = $row ? current($row)['urlrewrite_original'] : '';
        } else {
            $originalUrl = FatApp::getPostedData('urlrewrite_original', FatUtility::VAR_STRING, '');
        }

        if (empty($originalUrl)) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_REQUEST', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $langArr = Language::getAllNames();
        foreach ($langArr as $langId => $langName) {
            $recordId = 0;
            if (array_key_exists($langId, $row)) {
                $recordId = $row[$langId]['urlrewrite_id'];
            }
            $url = $post['urlrewrite_custom'][$langId];
            $data = [
                'urlrewrite_original' => $originalUrl,
                'urlrewrite_lang_id' => $langId,
                'urlrewrite_http_code' => $post['urlrewrite_http_code'][$langId],
                'urlrewrite_custom' => CommonHelper::seoUrl($url)
            ];
            $record = new UrlRewrite($recordId);
            $record->assignValues($data);

            if (!$record->save()) {
                Message::addErrorMessage($record->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('urlrewrite_id', $urlrewrite_id);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditUrlRewrite();
        $urlrewrite_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($urlrewrite_id < 1) {
            FatUtility::dieJsonError($this->str_invalid_request_id);
        }
        $res =     UrlRewrite::getAttributesById($urlrewrite_id, array('urlrewrite_id'));
        if ($res == false) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $obj = new UrlRewrite($urlrewrite_id);
        if (!$obj->deleteRecord(false)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
        $langArr = Language::getAllNames();
        $defaultLangId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
        $frm->addSelectBox(Label::getLabel('LBL_Language', $this->adminLangId), 'lang_id', $langArr, $defaultLangId);
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId), array('onclick' => 'clearSearch();'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    private function getForm(int $urlrewrite_id = 0)
    {
        $frm = new Form('frmUrlRewrite');
        $frm->addHiddenField('', 'urlrewrite_id', $urlrewrite_id);
        $frm->addRequiredField(Label::getLabel('LBL_Original_URL', $this->adminLangId), 'urlrewrite_original');
        $langArr = Language::getAllNames();
        foreach ($langArr as $langId => $langName) {
            $frm->addRequiredField(Label::getLabel('LBL_Custom_URL', $this->adminLangId) . '(' . $langName . ')', 'urlrewrite_custom[' . $langId . ']');
            $fldhttpcode = $frm->addSelectBox(Label::getLabel('LBL_Http_Code', $this->adminLangId) . '(' . $langName . ')', 'urlrewrite_http_code[' . $langId . ']', UrlRewrite::getHttpCodeArr($this->adminLangId));
            $fldhttpcode->requirements()->setRequired();
        }
        $fld =  $frm->addHTML('', '', '');
        $fld->htmlAfterField = '<small>' . Label::getLabel('LBL_Example:_Custom_URL_Example', $this->adminLangId) . '</small>';
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
