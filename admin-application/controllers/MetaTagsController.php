<?php
class MetaTagsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewMetaTags();
        $canEdit = $this->objPrivilege->canEditMetaTags($this->admin_id, true);
        $this->set("canEdit", $canEdit);
    }
    public function index()
    {
        $tabsArr = MetaTag::getTabsArr($this->adminLangId);
        $this->set('tabsArr', $tabsArr);
        $this->set('activeTab', MetaTag::META_GROUP_DEFAULT);
        $this->_template->render();
    }

    public function listMetaTags()
    {
        $metaType = FatApp::getPostedData('metaType', FatUtility::VAR_INT, MetaTag::META_GROUP_DEFAULT);
        $searchForm = $this->getSearchForm($metaType);
        //@check
        $canAddNew = false;
        $toShowForm = true;
        if (in_array($metaType, array(MetaTag::META_GROUP_DEFAULT))) {
            $toShowForm = false;
        }
        if (in_array($metaType, array(MetaTag::META_GROUP_OTHER))) {
            $canAddNew = true;
        }
        //@check
        $this->set('metaTypeDefault', MetaTag::META_GROUP_DEFAULT);
        $this->set('toShowForm', $toShowForm);
        $this->set('canAddNew', $canAddNew);
        $this->set('metaType', $metaType);
        $this->set('frmSearch', $searchForm);
        $this->_template->render(false, false);
    }

    public function search()
    {
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $metaType = FatApp::getPostedData('metaType', FatUtility::VAR_INT);
        $this->set('metaType', $metaType);
        // $adminId = AdminAuthentication::getLoggedAdminId();
        //@check keep in constructor
        $canEdit = $this->objPrivilege->canEditMetaTags($this->admin_id, true);
        $this->set("canEdit", $canEdit);

        switch ($metaType) {
            case MetaTag::META_GROUP_DEFAULT:
                $this->renderTemplateForDefaultMetaTag(true);
                break;
            case MetaTag::META_GROUP_OTHER:
                $this->renderTemplateForDefaultMetaTag();
                break;
            case MetaTag::META_GROUP_CMS_PAGE:
                $this->renderTemplateForCMSPage();
                break;
            case MetaTag::META_GROUP_TEACHER:
                $this->renderTemplateForTeachers();
                break;
            case MetaTag::META_GROUP_GRP_CLASS:
                $this->renderTemplateForGrpClasses();
                break;
            case MetaTag::META_GROUP_BLOG_CATEGORY;
                $this->renderTemplateForBlogCategories();
                break;
            case MetaTag::META_GROUP_BLOG_POST;
                $this->renderTemplateForBlogPosts();
                break;
            default:
                $this->renderTemplateForMetaType();
                break;
        }
    }

    public function form()
    {
        $metaId = FatApp::getPostedData('metaId', FatUtility::VAR_INT, 0);
        $metaType = FatApp::getPostedData('metaType', FatUtility::VAR_INT, -2);
        $recordId = FatApp::getPostedData('recordId', FatUtility::VAR_INT, 0);
        $frm = $this->getForm($metaId, $metaType, $recordId);
        if (0 < $metaId) {
            $data = MetaTag::getAttributesById($metaId);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }

            if ($metaType == MetaTag::META_GROUP_OTHER) {
                $data['meta_slug'] =  MetaTag::getOrignialUrlFromComponents($data);
            }

            $frm->fill($data);
        }

        $this->set('frm', $frm);
        $this->set('recordId', $recordId);
        $this->set('metaId', $metaId);
        $this->set('metaType', $metaType);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditMetaTags();
        $post = FatApp::getPostedData();

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $metaId = FatUtility::int($post['meta_id']);
        $record = new MetaTag($metaId);
        $tabsArr = MetaTag::getTabsArr();
        $metaType = FatUtility::convertToType($post['meta_type'], FatUtility::VAR_INT, -2);
        $post['meta_record_id'] = 0;

        if ($metaType == -2 || !isset($tabsArr[$metaType])) {
            Message::addErrorMessage($this->str_invalid_access);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $frm = $this->getForm($metaId, $metaType, $post['meta_record_id']);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $this->getUrlComponents($metaType, $post);
        $post['meta_controller'] = FatUtility::dashed2Camel($post['meta_controller'], true);
        $post['meta_action'] = FatUtility::dashed2Camel($post['meta_action']);
        $record = new MetaTag($metaId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $newTabLangId = 0;
        if ($metaId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = MetaTag::getAttributesByLangId($langId, $metaId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $metaId = $record->getMainTableRecordId();
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('metaId', $metaId);
        $this->set('metaType', $metaType);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($metaId = 0, $lang_id = 0, $metaType = 'default')
    {
        $metaId = FatUtility::int($metaId);
        $lang_id = FatUtility::int($lang_id);

        if ($metaId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request_id);
        }

        $langFrm = $this->getLangForm($metaId, $lang_id);

        if (!$data = MetaTag::getAttributesById($metaId)) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $recordId = FatUtility::int($data['meta_record_id']);

        $langData = MetaTag::getAttributesByLangId($lang_id, $metaId);

        if ($langData) {
            $langFrm->fill($langData);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('metaId', $metaId);
        $this->set('recordId', $recordId);
        $this->set('metaType', $metaType);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditMetaTags();
        $post = FatApp::getPostedData();

        $metaId = $post['meta_id'];
        $lang_id = $post['lang_id'];

        if ($metaId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        /* echo strip_tags($post['meta_other_meta_tags']); die; */

        if (!$post['meta_other_meta_tags'] == '' && $post['meta_other_meta_tags'] == strip_tags($post['meta_other_meta_tags'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Other_Meta_Tag', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getLangForm($metaId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['meta_id']);
        unset($post['lang_id']);

        $data = array(
            'metalang_lang_id' => $lang_id,
            'metalang_meta_id' => $metaId,
            'meta_title' => $post['meta_title'],
            'meta_keywords' => $post['meta_keywords'],
            'meta_description' => $post['meta_description'],
            'meta_other_meta_tags' => $post['meta_other_meta_tags'],
        );

        $metaObj = new MetaTag($metaId);

        if (!$metaObj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($metaObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = MetaTag::getAttributesByLangId($langId, $metaId)) {
                $newTabLangId = $langId;
                break;
            }
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('metaId', $metaId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditMetaTags();

        $metaId = FatApp::getPostedData('metaId', FatUtility::VAR_INT, 0);
        if ($metaId < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $obj = new MetaTag($metaId);
        if (!$obj->deleteRecord(true)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function getSearchForm($metaType)
    {

        $frm = new Form('frmSearch');
        $frm->addHiddenField(Label::getLabel('LBL_Type', $this->adminLangId), 'metaType', $metaType);

        switch ($metaType) {
            case MetaTag::META_GROUP_DEFAULT:
                return $frm;
                break;
            case MetaTag::META_GROUP_OTHER:
                $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
                break;
            default:
                $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
                $frm->addSelectBox(Label::getLabel('LBL_Has_Tags_Associated', $this->adminLangId), 'hasTagsAssociated', applicationConstants::getYesNoArr($this->adminLangId), false, array(), Label::getLabel('LBL_Doesn\'t_Matter', $this->adminLangId));
                break;
        }

        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);

        return  $frm;
    }

    private function getForm($metaTagId = 0, $metaType = MetaTag::META_GROUP_DEFAULT, $recordId = 0)
    {
        $metaTagId = FatUtility::int($metaTagId);
        $frm = new Form('frmMetaTag');
        $frm->addHiddenField('', 'meta_id', $metaTagId);
        $tabsArr = MetaTag::getTabsArr();
        $frm->addHiddenField('', 'meta_type', $metaType);
        if ($metaTagId != 0 && ($metaType == -2 || !isset($tabsArr[$metaType]))) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ($metaType == MetaTag::META_GROUP_OTHER) {
            $fld = $frm->addRequiredField(Label::getLabel('LBL_Slug', $this->adminLangId), 'meta_slug');

            $fld->htmlAfterField = "<small>" . sprintf(Label::getLabel("LBL_Ex_slug_%s_%s_%s", $this->adminLangId), CommonHelper::getRootUrl('/') . '/contact', 'contact', CommonHelper::getRootUrl('/') . '/contact') . "</small>";
        } else {
            $frm->addHiddenField(Label::getLabel('LBL_Entity_Id', $this->adminLangId), 'meta_record_id', $recordId);
        }
        $frm->addRequiredField(Label::getLabel('LBL_Identifier', $this->adminLangId), 'meta_identifier');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getLangForm($metaId = 0, $lang_id = 0)
    {
        if ($metaId > 0) {
            $metaTagDetail = MetaTag::getAttributesById($metaId);
        }

        $frm = new Form('frmMetaTagLang');
        $frm->addHiddenField('', 'meta_id', $metaId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $fldTitle = $frm->addTextBox(Label::getLabel('LBL_Meta_Title', $this->adminLangId), 'meta_title');
        $fldMetaKeywords = $frm->addTextarea(Label::getLabel('LBL_Meta_Keywords', $this->adminLangId), 'meta_keywords');
        $fldMetaDescription = $frm->addTextarea(Label::getLabel('LBL_Meta_Description', $this->adminLangId), 'meta_description');
        // if (isset($metaTagDetail) && $metaTagDetail['meta_type'] != MetaTag::META_GROUP_DEFAULT || $metaId == 0) {
        //     $fldMetaDescription->requirements()->setRequired(true);
        //     $fldTitle->requirements()->setRequired(true);
        //     $fldMetaKeywords->requirements()->setRequired(true);
        // }
        $fld = $frm->addTextarea(Label::getLabel('LBL_Other_Meta_Tags', $this->adminLangId), 'meta_other_meta_tags');
        $fld->htmlAfterField = '<small>' . Label::getLabel('LBL_For_Example:', $this->adminLangId) . ' ' . htmlspecialchars(' <meta name="copyright" content="text">') . '</small>';
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function renderTemplateForCMSPage()
    {
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);

        $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_STRING);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        $tabsArr = MetaTag::getTabsArr();


        $srch = ContentPage::getSearchObject($this->adminLangId);

        $srch->joinTable(MetaTag::DB_TBL, 'LEFT OUTER JOIN', "mt.meta_record_id = p.cpage_id and mt.meta_type =" . MetaTag::META_GROUP_CMS_PAGE, 'mt');
        $srch->joinTable(MetaTag::DB_LANG_TBL, 'LEFT OUTER JOIN', "mt_l.metalang_meta_id = mt.meta_id AND mt_l.metalang_lang_id = " . $this->adminLangId, 'mt_l');

        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('mt.meta_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('mt_l.meta_title', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('p_l.cpage_title', 'like', '%' . $post['keyword'] . '%', 'OR');
        }

        if (isset($post['hasTagsAssociated']) && $post['hasTagsAssociated'] != '') {
            if ($post['hasTagsAssociated'] == applicationConstants::YES) {
                $srch->addCondition('mt.meta_id', 'is not', 'mysql_func_NULL', 'AND', true);
            } elseif ($post['hasTagsAssociated'] == applicationConstants::NO) {
                $srch->addCondition('mt.meta_id', 'is', 'mysql_func_NULL', 'AND', true);
            }
        }

        $srch->addMultipleFields(array('meta_id', 'meta_identifier', 'meta_title', 'cpage_id', 'IF(cpage_title is NULL or cpage_title = "" ,cpage_identifier, cpage_title) as cpage_title'));

        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);

        $rs = $srch->getResultSet();
        $records = array();

        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/cpage-detail.php');
    }
    private function renderTemplateForTeachers()
    {
        $records = array();
        $data = FatApp::getPostedData();
        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);
        $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_INT, -2);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $langId = $this->adminLangId;

        $srch = new MetaTagSearch($this->adminLangId);
        $srch = User::getSearchObject();
        $srch->joinTable(MetaTag::DB_TBL, 'LEFT OUTER JOIN', "mt.meta_record_id = u.user_url_name and mt.meta_type=" . MetaTag::META_GROUP_TEACHER, 'mt');
        $srch->joinTable(MetaTag::DB_LANG_TBL, 'LEFT OUTER JOIN', "mt_l.metalang_meta_id = mt.meta_id AND mt_l.metalang_lang_id = " . $langId, 'mt_l');
        $srch->addCondition('u.user_is_teacher', '=', 1);
        $srch->addMultipleFields(array('meta_id', 'meta_identifier', 'meta_title', 'CONCAT(user_first_name, " ", user_last_name) as teacherFullName', 'u.user_id'));
        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('mt.meta_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('mt_l.meta_title', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('u.user_first_name', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('u.user_last_name', 'like', '%' . $post['keyword'] . '%', 'OR');
        }

        $srch->addCondition('u.user_url_name', '!=', '');
        if (isset($post['hasTagsAssociated']) && $post['hasTagsAssociated'] != '') {
            if ($post['hasTagsAssociated'] == applicationConstants::YES) {
                $srch->addCondition('mt.meta_id', 'is not', 'mysql_func_NULL', 'AND', true);
            } elseif ($post['hasTagsAssociated'] == applicationConstants::NO) {
                $srch->addCondition('mt.meta_id', 'is', 'mysql_func_NULL', 'AND', true);
            }
        }

        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();

        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }

        //CommonHelper::printArray($records, true);
        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/advanced-meta-tags-teacher.php');
    }
    private function renderTemplateForGrpClasses()
    {
        $records = array();
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);
        $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_INT, -2);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $langId = $this->adminLangId;
        $srch = MetaTag::getMetaTagsAdvancedGrpClasses($langId);

        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('mt.meta_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('mt_l.meta_title', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('gcls.grpcls_title', 'like', '%' . $post['keyword'] . '%', 'OR');
        }

        if (isset($post['hasTagsAssociated']) && $post['hasTagsAssociated'] != '') {
            if ($post['hasTagsAssociated'] == applicationConstants::YES) {
                $srch->addCondition('mt.meta_id', 'is not', 'mysql_func_NULL', 'AND', true);
            } elseif ($post['hasTagsAssociated'] == applicationConstants::NO) {
                $srch->addCondition('mt.meta_id', 'is', 'mysql_func_NULL', 'AND', true);
            }
        }
        $srch->addCondition('gcls.grpcls_status', '=', TeacherGroupClasses::STATUS_ACTIVE);
        $srch->addCondition('gcls.grpcls_start_datetime', '>', date('Y-m-d H:i:s'));

        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/advanced-meta-tags-grp-classes.php');
    }
    private function renderTemplateForDefaultMetaTag($checkRecordId = false)
    {
        $data = FatApp::getPostedData();
        $page = max(1, FatApp::getPostedData('page', FatUtility::VAR_INT, 0));

        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);
        $metaType = FatUtility::int($post['metaType']);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = new MetaTagSearch($this->adminLangId);
        if ($checkRecordId) {
            $srch->addCondition('mt.meta_record_id', '=', 0);
            $srch->addCondition('mt.meta_subrecord_id', '=', 0);
        }
        $srch->addCondition('mt.meta_type', '=', $metaType);
        $srch->addFld('mt.* , mt_l.meta_title');

        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('mt.meta_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('mt_l.meta_title', 'like', '%' . $post['keyword'] . '%', 'OR');
        }

        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet(); // false
        $records = FatApp::getDb()->fetchAll($rs);
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    private function renderTemplateForMetaType()
    {
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);

        $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_STRING);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        $tabsArr = MetaTag::getTabsArr();
        $controller = FatUtility::convertToType($tabsArr[$metaType]['controller'], FatUtility::VAR_STRING);
        $action = FatUtility::convertToType($tabsArr[$metaType]['action'], FatUtility::VAR_STRING);

        $srch = new MetaTagSearch($this->adminLangId);
        $srch->addFld('mt.* , mt_l.meta_title');
        $srch->addCondition('mt.meta_controller', 'like', $controller);
        $srch->addCondition('mt.meta_action', 'like', $action);

        $srch->addFld('mt.* , mt_l.meta_title');

        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-template.php');
    }
    private function renderTemplateForBlogCategories()
    {
        $records = array();
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);
        $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_INT, -2);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = BlogPostCategory::getSearchObject(false, $this->adminLangId, false);
        $srch->joinTable(MetaTag::DB_TBL, 'LEFT OUTER JOIN', 'mt.meta_record_id=bpc.bpcategory_id and mt.meta_type=' . MetaTag::META_GROUP_BLOG_CATEGORY, 'mt');
        $srch->joinTable(MetaTag::DB_LANG_TBL, 'LEFT OUTER JOIN', 'mt.meta_id=mt_l.metalang_meta_id and mt_l.metalang_lang_id=' . $this->adminLangId, 'mt_l');
        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('mt.meta_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('mt_l.meta_title', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('bpc.bpcategory_identifier', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('bpc_l.bpcategory_name', 'like', '%' . $post['keyword'] . '%', 'OR');
        }


        if (isset($post['hasTagsAssociated']) && $post['hasTagsAssociated'] != '') {
            if ($post['hasTagsAssociated'] == applicationConstants::YES) {
                $srch->addCondition('mt.meta_id', 'is not', 'mysql_func_NULL', 'AND', true);
            } elseif ($post['hasTagsAssociated'] == applicationConstants::NO) {
                $srch->addCondition('mt.meta_id', 'is', 'mysql_func_NULL', 'AND', true);
            }
        }

        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }

        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/meta-tags-blog-post-categories.php');
    }
    private function renderTemplateForBlogPosts()
    {
        $records = array();
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $searchForm = $this->getSearchForm($data['metaType']);
        $post = $searchForm->getFormDataFromArray($data);
        $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_INT, -2);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = BlogPost::getSearchObject($this->adminLangId, true, true);
        $srch->joinTable(MetaTag::DB_TBL, 'LEFT OUTER JOIN', 'mt.meta_record_id=bp.post_id and mt.meta_type=' . MetaTag::META_GROUP_BLOG_POST, 'mt');
        $srch->joinTable(MetaTag::DB_LANG_TBL, 'LEFT OUTER JOIN', 'mt.meta_id=mt_l.metalang_meta_id and mt_l.metalang_lang_id=' . $this->adminLangId, 'mt_l');
        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('mt.meta_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('mt_l.meta_title', 'like', '%' . $post['keyword'] . '%', 'OR');
            $condition->attachCondition('bp.post_identifier', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }

        if (isset($post['hasTagsAssociated']) && $post['hasTagsAssociated'] != '') {
            if ($post['hasTagsAssociated'] == applicationConstants::YES) {
                $srch->addCondition('mt.meta_id', 'is not', 'mysql_func_NULL', 'AND', true);
            } elseif ($post['hasTagsAssociated'] == applicationConstants::NO) {
                $srch->addCondition('mt.meta_id', 'is', 'mysql_func_NULL', 'AND', true);
            }
        }

        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/meta-tags-blog-post.php');
    }
    private function getUrlComponents($metaType, &$post)
    {
        $metaId = FatUtility::int($post['meta_id']);
        $tabsArr = MetaTag::getTabsArr();
        switch ($metaType) {
            case MetaTag::META_GROUP_TEACHER:
                $userDetail = User::getAttributesById($post['meta_record_id']);
                if (!$userDetail) {
                    return false;
                }
                $post['meta_controller'] = $tabsArr[$metaType]['controller'];
                $post['meta_action'] = $tabsArr[$metaType]['action'];
                $post['meta_record_id'] = $userDetail['user_url_name'];
                if ($metaId == 0) {
                    $post['meta_subrecord_id'] = 0;
                }
                break;
            case MetaTag::META_GROUP_OTHER:
                $post['meta_controller'] = $post['meta_action'] = '';
                $post['meta_record_id'] = $post['meta_subrecord_id'] = 0;
                $componentsNameArray = ['meta_controller', 'meta_action', 'meta_record_id', 'meta_subrecord_id'];
                $urlComponents = explode("/", $post['meta_slug']);
                $urlComponents = array_combine(array_slice($componentsNameArray, 0, count($urlComponents)), $urlComponents);
                $post = array_merge($post, $urlComponents);
                break;
            default;
                $post['meta_controller'] = $tabsArr[$metaType]['controller'];
                $post['meta_action'] = $tabsArr[$metaType]['action'];
                if ($metaId == 0) {
                    $post['meta_subrecord_id'] = 0;
                }
                break;
        }

        return true;
    }
}
