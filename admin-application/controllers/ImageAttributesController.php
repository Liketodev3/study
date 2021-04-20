<?php

class ImageAttributesController extends AdminBaseController
{

    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canViewImageAttributes($this->admin_id, true);
    }

    public function index()
    {
        $canEdit = $this->objPrivilege->canEditImageAttributes($this->admin_id, true);
        $this->set('adminId', $this->admin_id);
        $this->set("tabsArr", $this->getTabsArr());
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }

    public function listImageAttributes()
    {
        $imageAttributeType = FatApp::getPostedData('listImageAttributes', FatUtility::VAR_INT, AttachedFile::FILETYPE_HOME_PAGE_BANNER);
        $searchForm = $this->getSearchForm($imageAttributeType);
        $canAdd = false;
        $showFilters = true;
        $this->set('metaTypeDefault', MetaTag::META_GROUP_DEFAULT);
        $this->set('showFilters', $showFilters);
        $this->set('canAdd', $canAdd);
        $this->set('imageAttributeType', $imageAttributeType);
        $this->set('frmSearch', $searchForm);
        $this->_template->render(false, false);
    }

    public function search()
    {
        $canEdit = $this->objPrivilege->canEditImageAttributes($this->admin_id);
        $pageSize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $data = FatApp::getPostedData();
        $searchForm = $this->getSearchForm($data['imageAttributeType']);
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray($data);
        $srch = AttachedFile::getSearchObject();
        switch ($post['imageAttributeType']) {
            case AttachedFile::FILETYPE_HOME_PAGE_BANNER:
                $srch->joinTable(Banner::DB_TBL, 'LEFT OUTER JOIN', 'banner_id = afile_record_id', 'banner');
                $srch->joinTable(Banner::DB_LANG_TBL, 'LEFT OUTER JOIN', Banner::DB_LANG_TBL_PREFIX . 'banner_id = banner.banner_id and bannerlang_lang_id=' . $this->adminLangId, 'banner_l');
                $srch->addCondition('afile_type', '=', AttachedFile::FILETYPE_HOME_PAGE_BANNER);
                $srch->addMultipleFields(
                    array('banner_id as record_id', 'banner_title as record_name', 'afile_type')
                );

                break;
            case AttachedFile::FILETYPE_CATEGORY_BANNER:
                $srch->joinTable(ProductCategory::DB_TBL, 'LEFT OUTER JOIN', 'prodcat_id = afile_record_id', 'pc');
                $srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT OUTER JOIN', 'pc.prodcat_id = pc_l.prodcatlang_prodcat_id AND pc_l.prodcatlang_lang_id = ' . $this->adminLangId, 'pc_l');
                $srch->addMultipleFields(
                    array('prodcat_id as record_id', 'IFNULL(prodcat_name, prodcat_identifier) as record_name', 'afile_type')
                );
                if (!empty($post['keyword'])) {
                    $cnd = $srch->addCondition('prodcat_name', 'like', '%' . $post['keyword'] . '%');
                    $cnd->attachCondition('prodcat_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            case AttachedFile::FILETYPE_BLOG_POST_IMAGE:
                $srch->joinTable(BlogPost::DB_TBL, 'LEFT OUTER JOIN', 'post_id = afile_record_id', 'bp');
                $srch->joinTable(BlogPost::DB_TBL_LANG, 'LEFT OUTER JOIN', 'bp.post_id = bp_l.postlang_post_id AND bp_l.postlang_lang_id = ' . $this->adminLangId, 'bp_l');
                $srch->addMultipleFields(
                    array('post_id as record_id', 'IFNULL(post_title, post_identifier) as record_name', 'afile_type')
                );
                if (!empty($post['keyword'])) {
                    $cnd = $srch->addCondition('post_title', 'like', '%' . $post['keyword'] . '%');
                    $cnd->attachCondition('post_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            default:
                // $srch->joinTable(Brand::DB_TBL, 'LEFT OUTER JOIN', 'brand_id = afile_record_id', 'b');
                // $srch->joinTable(Brand::DB_TBL_LANG, 'LEFT OUTER JOIN', 'b.brand_id = b_l.brandlang_brand_id AND b_l.brandlang_lang_id = ' . $this->adminLangId, 'b_l');
                // $srch->addMultipleFields(
                //     array('brand_id as record_id', 'IFNULL(brand_name, brand_identifier) as record_name', 'afile_type')
                // );
                // if (!empty($post['keyword'])) {
                //     $cnd = $srch->addCondition('brand_name', 'like', '%' . $post['keyword'] . '%');
                //     $cnd->attachCondition('brand_identifier', 'like', '%' . $post['keyword'] . '%');
                // }
                break;
        }

        $srch->addGroupBy('afile_record_id');
        $srch->addOrder('afile_id', 'DESC');
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);
        $srch->addOrder('afile_id', 'DESC');
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);


        $this->set("arr_listing", $records);
        $this->set('imageAttributeType', $post['imageAttributeType']);
        $this->set('pageCount', $srch->pages());
        $this->set("canEdit", $canEdit);
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    public function attributeForm($recordId, $moduleType, $langId = 0)
    {
        $recordId = FatUtility::int($recordId);
        $moduleType = FatUtility::int($moduleType);
        $langId = FatUtility::int($langId);
        if ($recordId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        switch ($moduleType) {
            case AttachedFile::FILETYPE_PRODUCT_IMAGE:
                $data =  Product::getProductDataById($this->adminLangId, $recordId, 'IFNULL(product_name, product_identifier) as title');
                $title = $data['title'];
                break;
            case AttachedFile::FILETYPE_CATEGORY_BANNER:
                $srch = ProductCategory::getSearchObject(false, $this->adminLangId);
                $srch->addCondition(ProductCategory::DB_TBL_PREFIX . 'deleted', '=', 0);
                $srch->addFld('IFNULL(prodcat_name, prodcat_identifier) AS prodcat_name');
                $srch->addCondition('prodcat_id', '=', $recordId);
                $srch->addOrder('prodcat_id', 'DESC');
                $rs = $srch->getResultSet();
                $records = FatApp::getDb()->fetch($rs);
                $title = $records['prodcat_name'];
                break;
            case AttachedFile::FILETYPE_BLOG_POST_IMAGE:
                $srch = BlogPost::getSearchObject($this->adminLangId);
                $srch->addFld('IFNULL(post_title, post_identifier) as post_title');
                $srch->addCondition('post_id', '=', $recordId);
                $srch->addOrder('post_id', 'DESC');
                $rs = $srch->getResultSet();
                $records = FatApp::getDb()->fetch($rs);
                $title = $records['post_title'];
                break;
            default:
                $srch = Brand::getListingObj($this->adminLangId, null, true);
                $srch->addCondition('brand_id', '=', $recordId);
                $srch->addOrder('brand_id', 'DESC');
                $rs = $srch->getResultSet();
                $records = FatApp::getDb()->fetch($rs);
                $title = $records['brand_name'];
                break;
        }
        $images = AttachedFile::getMultipleAttachments($moduleType, $recordId, 0, $langId, false, 0, 0, true);
        $languages = Language::getAllNames();
        $frm = $this->getImgAttrForm($recordId, $moduleType, $langId, $images);
        $this->set('recordId', $recordId);
        $this->set('moduleType', $moduleType);
        $this->set('langId', $langId);
        $this->set('languages', $languages);
        $this->set('title', $title);
        $this->set('images', $images);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function form()
    {

        $recordId = FatApp::getPostedData('recordid', FatUtility::VAR_INT, 0);
        $type = FatApp::getPostedData('Type', FatUtility::VAR_INT, MetaTag::META_GROUP_DEFAULT);

        $frm = $this->getForm($recordId, $type);
        if (0 < $recordId) {
            $srch = AttachedFile::getSearchObject();
            $srch->addCondition('afile_record_id', '=', $recordId);
            $srch->addCondition('afile_type', '=', $type);
            $rs = $srch->getResultSet();
            $records = FatApp::getDb()->fetch($rs);
            $frm->fill($records);
        }
        $this->set('frm', $frm);
        $this->set('recordId', $recordId);
        $this->set('adminLangId', $this->adminLangId);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    }
    private function getImgAttrForm($recordId, $moduleType, $langId, $images)
    {
        $this->objPrivilege->canViewImageAttributes();
        $recordId = FatUtility::int($recordId);
        $moduleType = FatUtility::int($moduleType);
        $langId = FatUtility::int($langId);

        $images = AttachedFile::getMultipleAttachments($moduleType, $recordId, 0, $langId, false, 0, 0, true);

        $frm = new Form('frmImgAttr');
        $frm->addHiddenField('', 'module_type', $moduleType);
        $frm->addHiddenField('', 'record_id', $recordId);
        $languagesAssocArr = Language::getAllNames();
        $frm->addSelectBox(Label::getLabel('LBL_Language', $this->adminLangId), 'lang_id', array(0 => Label::getLabel('LBL_All_Languages', $this->adminLangId)) + $languagesAssocArr, $langId, array(), '');
        foreach ($images as $afileId => $afileData) {
            $frm->addTextBox(Label::getLabel('LBL_Image_Title', $this->adminLangId), 'image_title' . $afileId);
            $frm->addTextBox(Label::getLabel('LBL_Image_Alt', $this->adminLangId), 'image_alt' . $afileId);
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save', $this->adminLangId));
        $frm->addButton('', 'btn_discard', Label::getLabel('LBL_Discard', $this->adminLangId));
        return $frm;
    }

    /* public function images($recordId, $moduleType, $lang_id = 0)
    {
        $recordId = FatUtility::int($recordId);
        $moduleType = FatUtility::int($moduleType);
        if ($recordId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $images = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_PRODUCT_IMAGE, $recordId, 0, $lang_id, false, 0, 0, true);
        $this->set('images', $productImages);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    } */

    public function setup()
    {
        $this->objPrivilege->canEditImageAttributes();

        $post = FatApp::getPostedData();
        $recordId = FatUtility::int($post['record_id']);
        $moduleType = FatUtility::int($post['module_type']);
        $langId = FatUtility::int($post['lang_id']);

        if (!$recordId || !$moduleType) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $images = AttachedFile::getMultipleAttachments($moduleType, $recordId, 0, $langId, false, 0, 0, true);

        $frm = $this->getImgAttrForm($recordId, $moduleType, $langId, $images);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $db = FatApp::getDb();
        // $recordSaved = false;
        foreach ($images as $afileId => $afileData) {
            /* if(empty($post['image_title'.$afileId]) && empty($post['image_alt'.$afileId])) {
				continue;
			} */
            $where = array('smt' => 'afile_record_id = ? and afile_id = ?', 'vals' => array($recordId, $afileId));
            if (!$db->updateFromArray(AttachedFile::DB_TBL, array('afile_attribute_title' => $post['image_title' . $afileId], 'afile_attribute_alt' => $post['image_alt' . $afileId]), $where)) {
                Message::addErrorMessage($db->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            // $recordSaved = true;
        }
        /* if (!$recordSaved) {
            Message::addErrorMessage(Label::getLabel('MSG_Please_fill_any_one', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        } */
        $this->set('msg', $this->str_setup_successful);
        $this->set('recordId', $recordId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditImageAttributes();
        $urlrewrite_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($urlrewrite_id < 1) {
            FatUtility::dieJsonError($this->str_invalid_request_id);
        }
        $res = UrlRewrite::getAttributesById($urlrewrite_id, array('urlrewrite_id'));
        if ($res == false) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->markAsDeleted($urlrewrite_id);
        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    public function deleteSelected()
    {
        $this->objPrivilege->canEditImageAttributes();
        $urlrewriteIdsArr = FatUtility::int(FatApp::getPostedData('urlrewrite_ids'));
        if (empty($urlrewriteIdsArr)) {
            FatUtility::dieWithError(
                Label::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }
        foreach ($urlrewriteIdsArr as $urlrewriteId) {
            if (1 > $urlrewriteId) {
                continue;
            }
            $this->markAsDeleted($urlrewriteId);
        }
        $this->set('msg', $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function markAsDeleted($urlrewriteId)
    {
        $urlrewriteId = FatUtility::int($urlrewriteId);
        if (1 > $urlrewriteId) {
            FatUtility::dieWithError(
                Label::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }
        $obj = new UrlRewrite($urlrewriteId);
        if (!$obj->deleteRecord(false)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
    }

    private function getSearchForm(int $imageAttributeType)
    {
        $frm = new Form('frmSearch');
        $frm->addHiddenField(Label::getLabel('LBL_Type', $this->adminLangId), 'imageAttributeType', $imageAttributeType);
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId), array('onclick' => 'clearSearch();'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    private function getForm(int $recordId, int $type)
    {
        $frm = new Form('frmImageAttributes');
        $frm->addHiddenField('', 'record_id', $recordId);
        $frm->addHiddenField('', 'type', $type);
        $frm->addRequiredField(Label::getLabel('LBL_Image_Title', $this->adminLangId), 'afile_attribute_title');
        $frm->addRequiredField(Label::getLabel('LBL_Image_Alt', $this->adminLangId), 'afile_attribute_alt');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getTabsArr(): array
    {
        $imageAttributesGroups = array(
            AttachedFile::FILETYPE_HOME_PAGE_BANNER => Label::getLabel('IMGA_Home_Page_Banner', $this->adminLangId),
            AttachedFile::FILETYPE_BANNER => Label::getLabel('IMGA_BANNER', $this->adminLangId),
            AttachedFile::FILETYPE_CPAGE_BACKGROUND_IMAGE => Label::getLabel('IMGA_CPAGE_BACKGROUND_IMAGE', $this->adminLangId),
            AttachedFile::FILETYPE_TEACHING_LANGUAGES => Label::getLabel('IMGA_TEACHING_LANGUAGES', $this->adminLangId),
            AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES => Label::getLabel('IMGA_FLAG_TEACHING_LANGUAGES', $this->adminLangId),
            AttachedFile::FILETYPE_BLOG_PAGE_IMAGE => Label::getLabel('IMGA_BLOG_PAGE_IMAGE', $this->adminLangId),
        );
        return $imageAttributesGroups;
    }
}
