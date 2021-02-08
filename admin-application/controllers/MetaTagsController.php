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
        $canAdd = false;
        $showFilters = true;
        if (in_array($metaType, array(MetaTag::META_GROUP_DEFAULT))) {
            $showFilters = false;
        }
        if (in_array($metaType, array(MetaTag::META_GROUP_OTHER))) {
            $canAdd = true;
        }
        $this->set('metaTypeDefault', MetaTag::META_GROUP_DEFAULT);
        $this->set('showFilters', $showFilters);
        $this->set('canAdd', $canAdd);
        $this->set('metaType', $metaType);
        $this->set('frmSearch', $searchForm);
        $this->_template->render(false, false);
    }

    public function search()
    {
        $metaType = FatApp::getPostedData('metaType', FatUtility::VAR_INT, MetaTag::META_GROUP_DEFAULT);
        $this->set('metaType', $metaType);
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
                $this->renderTemplateForDefaultMetaTag(true);
                break;
        }
    }

    public function form()
    {
        $metaId = FatApp::getPostedData('metaId', FatUtility::VAR_INT, 0);
        $metaType = FatApp::getPostedData('metaType', FatUtility::VAR_INT, MetaTag::META_GROUP_DEFAULT);
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


        $meta_record_id = FatApp::getPostedData('meta_record_id');
        $metaId = FatApp::getPostedData('meta_id');
        $tabsArr = MetaTag::getTabsArr($this->adminLangId);
        $metaType = FatApp::getPostedData('meta_type', FatUtility::VAR_INT);
        $frm = $this->getForm($metaId, $metaType, $meta_record_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (!isset($tabsArr[$metaType])) {
            Message::addErrorMessage($this->str_invalid_access);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->setUrlComponents($metaType, $post);

        $metaTag = new MetaTag($metaId);
        $metaTag->assignValues($post);
        if (!$metaTag->save()) {
            Message::addErrorMessage($metaTag->getError());
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
            $metaId = $metaTag->getMainTableRecordId();
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('metaId', $metaId);
        $this->set('metaType', $metaType);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($metaId = 0, $lang_id = 0)
    {
        $metaId = FatUtility::int($metaId);
        $lang_id = FatUtility::int($lang_id);

        if (!$data = MetaTag::getAttributesById($metaId)) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm = $this->getLangForm($metaId, $lang_id);

        $recordId = FatUtility::int($data['meta_record_id']);
        $langData = MetaTag::getAttributesByLangId($lang_id, $metaId);

        if ($langData) {
            $langFrm->fill($langData);
        }
        $this->set('languages', Language::getAllNames());
        $this->set('metaId', $metaId);
        $this->set('recordId', $recordId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    public function langSetup()
    {
        $data = FatApp::getPostedData();
        $metaId = $data['meta_id'];
        $langId = $data['lang_id'];
        if ($metaId == 0 || $langId == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        if (!$data['meta_other_meta_tags'] == '' && $data['meta_other_meta_tags'] == strip_tags($data['meta_other_meta_tags'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Other_Meta_Tag', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getLangForm($metaId, $langId);
        $data = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        unset($data['meta_id']);
        unset($data['lang_id']);


        $data['metalang_lang_id'] = $langId;
        $data['metalang_meta_id'] = $metaId;

        $metaTag = new MetaTag($metaId);
        if (!$metaTag->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($metaTag->getError());
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
        $metaId = FatApp::getPostedData('metaId', FatUtility::VAR_INT, 0);

        $metaTag = new MetaTag($metaId);
        if (!$metaTag->loadFromDb()) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (!$metaTag->deleteRecord(true)) {
            Message::addErrorMessage($metaTag->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    private function getSearchForm(int $metaType): Form
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
        //@check to be in view file
        $fld_submit->attachField($fld_cancel);
        return  $frm;
    }
    //check var names uniform metaTagId
    private function getForm(int $metaTagId = 0, $metaType = MetaTag::META_GROUP_DEFAULT, $recordId = 0)
    {
        $frm = new Form('frmMetaTag');
        $frm->addHiddenField('', 'meta_id', $metaTagId);
        $tabsArr = MetaTag::getTabsArr($this->adminLangId);
        $frm->addHiddenField('', 'meta_type', $metaType);
        //@check
        if ($metaTagId != 0 && ($metaType == -2 || !isset($tabsArr[$metaType]))) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ($metaType == MetaTag::META_GROUP_OTHER) {
            $fld = $frm->addRequiredField(Label::getLabel('LBL_Slug', $this->adminLangId), 'meta_slug');

            $fld->htmlAfterField = "<small>" . sprintf(Label::getLabel("LBL_Ex_slug_%s_%s_%s", $this->adminLangId), CommonHelper::getRootUrl() . '/contact', 'contact', CommonHelper::getRootUrl() . '/contact') . "</small>";
        } else {
            $frm->addHiddenField(Label::getLabel('LBL_Entity_Id', $this->adminLangId), 'meta_record_id', $recordId);
        }
        $frm->addRequiredField(Label::getLabel('LBL_Identifier', $this->adminLangId), 'meta_identifier');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    //@check type hinting whrever possible
    private function getLangForm($metaId = 0, $lang_id = 0)
    {
        //@remove unused vars
        $frm = new Form('frmMetaTagLang');
        $frm->addHiddenField('', 'meta_id', $metaId);
        $frm->addHiddenField('', 'lang_id', $lang_id);
        $fldTitle = $frm->addTextBox(Label::getLabel('LBL_Meta_Title', $this->adminLangId), 'meta_title');
        $fldMetaKeywords = $frm->addTextarea(Label::getLabel('LBL_Meta_Keywords', $this->adminLangId), 'meta_keywords');
        $fldMetaDescription = $frm->addTextarea(Label::getLabel('LBL_Meta_Description', $this->adminLangId), 'meta_description');

        //@htmlAfterfields view file
        $fld = $frm->addTextarea(Label::getLabel('LBL_Other_Meta_Tags', $this->adminLangId), 'meta_other_meta_tags');
        $fld->htmlAfterField = '<small>' . Label::getLabel('LBL_For_Example:', $this->adminLangId) . ' ' . htmlspecialchars(' <meta name="copyright" content="text">') . '</small>';
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    public function searchMeta()
    {
        $metaSrch =  new MetaTagSearch($this->adminLangId);
        $dbFields = ['*'];
        $viewTmpltFields = [];
        switch ($metaType) {
            case MetaTag::META_GROUP_CMS_PAGE:
                $metaSrch->joinCmsPage(); // right joins
                $dbFields;
                $viewTmpltFields;
                break;
            case MetaTag::META_GROUP_TEACHER:
                $metaSrch->joinTeachers();
                $dbFields;
                $viewTmpltFields;
                break;
            case MetaTag::META_GROUP_GRP_CLASS:
                $metaSrch->joinGrpClasses();
                $dbFields;
                $viewTmpltFields;
                break;
            case MetaTag::META_GROUP_BLOG_POST:
                $metaSrch->joinBlogPOsts();
                $dbFields;
                $viewTmpltFields;
                break;
        }

        $srch->addMultipleFields($fields);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("meta_record_id", 'cpage_id');
        $this->set("columnsArr", $this->getColumns($metaType));
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }

    private function renderTemplateForCMSPage()
    {
        extract($this->GeneralParametersListing());

        //@check page
        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("meta_record_id", 'cpage_id');
        $this->set("columnsArr", $this->getColumns($metaType));
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    private function renderTemplateForTeachers()
    {
        extract($this->GeneralParametersListing());
        $srch = new MetaTagSearch($this->adminLangId);
        $srch = User::getSearchObject();
        $srch->joinTable(MetaTag::DB_TBL, 'LEFT OUTER JOIN', "mt.meta_record_id = u.user_url_name and mt.meta_type=" . MetaTag::META_GROUP_TEACHER, 'mt');
        $srch->joinTable(MetaTag::DB_LANG_TBL, 'LEFT OUTER JOIN', "mt_l.metalang_meta_id = mt.meta_id AND mt_l.metalang_lang_id = " . $this->adminLangId, 'mt_l');
        $srch->addCondition('u.user_is_teacher', '=', 1);
        $srch->addMultipleFields(array('meta_id', 'meta_record_id', 'meta_identifier', 'meta_title', 'CONCAT(user_first_name, " ", user_last_name) as teacher_name', 'u.user_id'));
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
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("columnsArr", $this->getColumns($metaType));
        $this->set("meta_record_id", 'user_id');
        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    private function renderTemplateForGrpClasses()
    {
        extract($this->GeneralParametersListing());
        $srch = MetaTag::getMetaTagsAdvancedGrpClasses($this->adminLangId);
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
        $this->set("columnsArr", $this->getColumns($metaType));
        $this->set("meta_record_id", 'grpcls_id');
        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    private function renderTemplateForDefaultMetaTag($checkRecordId = false)
    {
        extract($this->GeneralParametersListing());
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
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("columnsArr", $this->getColumns($metaType));
        $this->set("arr_listing", $records);
        $this->set("meta_record_id", 'meta_record_id');
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    // private function renderTemplateForMetaType()
    // {
    //     $data = FatApp::getPostedData();
    //     $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
    //     $searchForm = $this->getSearchForm($data['metaType']);
    //     $post = $searchForm->getFormDataFromArray($data);
    //     $metaType = FatUtility::convertToType($post['metaType'], FatUtility::VAR_STRING);
    //     $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
    //     $tabsArr = MetaTag::getTabsArr($this->adminLangId);
    //     $controller = FatUtility::convertToType($tabsArr[$metaType]['controller'], FatUtility::VAR_STRING);
    //     $action = FatUtility::convertToType($tabsArr[$metaType]['action'], FatUtility::VAR_STRING);
    //     $srch = new MetaTagSearch($this->adminLangId);
    //     $srch->addFld('mt.* , mt_l.meta_title');
    //     $srch->addCondition('mt.meta_controller', 'like', $controller);
    //     $srch->addCondition('mt.meta_action', 'like', $action);
    //     $srch->addFld('mt.* , mt_l.meta_title');
    //     $page = (empty($page) || $page <= 0) ? 1 : $page;
    //     $page = FatUtility::int($page);
    //     $srch->setPageNumber($page);
    //     $srch->setPageSize($pagesize);
    //     $records = FatApp::getDb()->fetchAll($srch->getResultSet());
    //     $this->set("columnsArr", $this->getColumns($metaType));
    //     $this->set("arr_listing", $records);
    //     $this->set('pageCount', $srch->pages());
    //     $this->set('recordCount', $srch->recordCount());
    //     $this->set('page', $page);
    //     $this->set('pageSize', $pagesize);
    //     $this->set('postedData', $post);
    //     $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    // }
    private function renderTemplateForBlogCategories()
    {
        extract($this->GeneralParametersListing());
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
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("meta_record_id", 'bpcategory_id');
        $this->set("columnsArr", $this->getColumns($metaType));
        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    private function renderTemplateForBlogPosts()
    {
        extract($this->GeneralParametersListing());
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
        if (isset($post['hasTagsAssociated']) && $post['hasTagsAssociated'] != '') {
            if ($post['hasTagsAssociated'] == applicationConstants::YES) {
                $srch->addCondition('mt.meta_id', 'is not', 'mysql_func_NULL', 'AND', true);
            } elseif ($post['hasTagsAssociated'] == applicationConstants::NO) {
                $srch->addCondition('mt.meta_id', 'is', 'mysql_func_NULL', 'AND', true);
            }
        }
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("columnsArr", $this->getColumns($metaType));

        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set("meta_record_id", 'post_id');
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false, 'meta-tags/default-meta-tag.php');
    }
    private function setUrlComponents($metaType, &$post)
    {
        $metaId = FatUtility::int($post['meta_id']);
        $tabsArr = MetaTag::getTabsArr($this->adminLangId);
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
                $post['meta_controller'] = FatUtility::dashed2Camel($post['meta_controller'], true);
                $post['meta_action'] = FatUtility::dashed2Camel($post['meta_action']);
                break;
            default:
                $post['meta_controller'] = $tabsArr[$metaType]['controller'];
                $post['meta_action'] = $tabsArr[$metaType]['action'];
                if ($metaId == 0) {
                    $post['meta_subrecord_id'] = 0;
                }
                break;
        }

        return true;
    }

    private function getColumns(int $metaType)
    {
        $columnsArr = [];
        switch ($metaType) {
            case MetaTag::META_GROUP_DEFAULT:
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'meta_identifier' => Label::getLabel('LBL_Identifier', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
            case MetaTag::META_GROUP_OTHER:
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'url' => Label::getLabel('LBL_Slug', $this->adminLangId),
                    'meta_identifier' => Label::getLabel('LBL_Identifier', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
            case MetaTag::META_GROUP_CMS_PAGE:
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'cpage_title' => Label::getLabel('LBL_CMS_Page', $this->adminLangId),
                    'meta_identifier' => Label::getLabel('LBL_Identifier', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
            case MetaTag::META_GROUP_TEACHER:
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'teacher_name' => Label::getLabel('LBL_Teacher_Name', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
            case MetaTag::META_GROUP_GRP_CLASS:
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'grpcls_title' => Label::getLabel('LBL_Group_Class', $this->adminLangId),
                    'teacher_name' => Label::getLabel('LBL_Teacher_Name', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Meta_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
            case MetaTag::META_GROUP_BLOG_CATEGORY;
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'bpcategory_identifier' => Label::getLabel('LBL_Blog_Categories', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
            case MetaTag::META_GROUP_BLOG_POST;
                $columnsArr = [
                    'listserial' => Label::getLabel('LBL_Sr._No', $this->adminLangId),
                    'bpcategory_identifier' => Label::getLabel('LBL_Blog_Categories', $this->adminLangId),
                    'meta_title' => Label::getLabel('LBL_Title', $this->adminLangId),
                    'action' => Label::getLabel('LBL_Action', $this->adminLangId),
                ];
                break;
        }
        return $columnsArr;
    }

    private function GeneralParametersListing()
    {
        $result['data'] = FatApp::getPostedData();
        $result['page'] = (empty($result['data']['page']) ||  $result['data']['page'] <= 0) ? 1 :  $result['data']['page'];
        $result['searchForm'] = $this->getSearchForm($result['data']['metaType']);
        $result['post'] = $result['searchForm']->getFormDataFromArray($result['data']);
        $result['metaType'] = FatUtility::convertToType($result['post']['metaType'], FatUtility::VAR_STRING);
        $result['pagesize'] = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $result['tabsArr'] = MetaTag::getTabsArr($this->adminLangId);

        return $result;
    }
}
