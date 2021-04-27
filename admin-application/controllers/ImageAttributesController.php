<?php

class ImageAttributesController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewImageAttributes($this->admin_id);
    }

    public function index()
    {
        $searchForm = $this->getSearchForm(AttachedFile::FILETYPE_BANNER, $this->adminLangId);
        $this->set('activeTab', AttachedFile::FILETYPE_BANNER);
        $this->set("tabsArr", AttachedFile::getFileTypesArrayForAttributes($this->adminLangId));
        $this->set('frmSearch', $searchForm);
        $this->_template->render();
    }

    public function search()
    {
        $langArr = Language::getAllCodesAssoc(true);
        $canEdit = $this->objPrivilege->canEditImageAttributes($this->admin_id);
        $pageSize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $data = FatApp::getPostedData();
        $searchForm = $this->getSearchForm($data['imageAttributeType'], $this->adminLangId);
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray($data);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $srch = AttachedFile::getSearchObject();
        switch ($post['imageAttributeType']) {
            case AttachedFile::FILETYPE_BANNER:
                $srch = $this->joinBanners($srch);
                if (!empty($post['keyword'])) {
                    $srch->addCondition('banner_title', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            case AttachedFile::FILETYPE_HOME_PAGE_BANNER:
                $srch = $this->joinHomePageBanner($srch);
                if (!empty($post['keyword'])) {
                    $srch->addCondition('slide_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            case AttachedFile::FILETYPE_CPAGE_BACKGROUND_IMAGE:
                $srch = $this->joinContentBackgroudImage($srch);
                if (!empty($post['keyword'])) {
                    $srch->addCondition('cpage_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            case AttachedFile::FILETYPE_TEACHING_LANGUAGES:
                $srch = $this->joinTeachingLanguage($srch);
                if (!empty($post['keyword'])) {
                    $srch->addCondition('tlanguage_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            case AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES:
                $srch = $this->joinFlagTeachingLangugage($srch);
                if (!empty($post['keyword'])) {
                    $srch->addCondition('tlanguage_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
            case AttachedFile::FILETYPE_BLOG_POST_IMAGE:
                $srch = $this->joinBlogPostImage($srch);
                if (!empty($post['keyword'])) {
                    $srch->addCondition('post_identifier', 'like', '%' . $post['keyword'] . '%');
                }
                break;
        }
        $srch->addOrder('afile_id', 'DESC');
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $page =  max(FatApp::getPostedData('page', FatUtility::VAR_INT, 1), 1);
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);
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
        $this->set('langArr', $langArr);
        $this->_template->render(false, false);
    }

    public function form()
    {
        $recordId = FatApp::getPostedData('recordid', FatUtility::VAR_INT, 0);
        $type = FatApp::getPostedData('Type', FatUtility::VAR_INT, MetaTag::META_GROUP_DEFAULT);
        $fileId =  FatApp::getPostedData('afile_id', FatUtility::VAR_INT, 0);

        if (!$fileId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $frm = $this->getForm($recordId, $type, $fileId, $this->adminLangId);

        if (0 <  $fileId) {
            $srch = AttachedFile::getSearchObject();
            $srch->addCondition('afile_id', '=', $fileId);
            $rs = $srch->getResultSet();
            $records = FatApp::getDb()->fetch($rs);
            $frm->fill($records);
        }

        $this->set('frm', $frm);
        $this->set('adminLangId', $this->adminLangId);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditImageAttributes();
        $post = FatApp::getPostedData();
        $recordId = FatUtility::int($post['record_id']);
        $moduleType = FatUtility::int($post['type']);
        $fileId = FatUtility::int($post['fileId']);

        if (!$recordId || !$moduleType || !$fileId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $frm = $this->getForm($recordId, $moduleType, $fileId, $this->adminLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $db = FatApp::getDb();

        $where = ['smt' => 'afile_record_id = ? and afile_id = ?', 'vals' => [$recordId, $fileId]];
        if (!$db->updateFromArray(AttachedFile::DB_TBL, ['afile_attribute_title' => $post['afile_attribute_title'], 'afile_attribute_alt' => $post['afile_attribute_alt']], $where)) {
            Message::addErrorMessage($db->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('recordId', $recordId);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getSearchForm(int $imageAttributeType, int $langId)
    {
        $frm = new Form('frmSearch');
        $frm->addHiddenField(Label::getLabel('LBL_Type', $langId), 'imageAttributeType', $imageAttributeType);
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $langId), 'keyword');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $langId));
        $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $langId));
        return $frm;
    }

    private function getForm(int $recordId, int $type, int $fileId, int $langId)
    {
        $frm = new Form('frmImageAttributes');
        $frm->addHiddenField('', 'record_id', $recordId);
        $frm->addHiddenField('', 'type', $type);
        $frm->addHiddenField('', 'fileId', $fileId);
        $frm->addRequiredField(Label::getLabel('LBL_Image_Title', $langId), 'afile_attribute_title');
        $frm->addRequiredField(Label::getLabel('LBL_Image_Alt', $langId), 'afile_attribute_alt');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }

    private function joinBanners($attachedSrch)
    {
        $attachedSrch->joinTable(Banner::DB_TBL, 'LEFT OUTER JOIN', 'banner_id = afile_record_id', 'banner');
        $attachedSrch->joinTable(Banner::DB_LANG_TBL, 'LEFT OUTER JOIN', Banner::DB_LANG_TBL_PREFIX . 'banner_id = banner.banner_id and bannerlang_lang_id=' . $this->adminLangId, 'banner_l');
        $attachedSrch->addCondition('afile_type', '=', AttachedFile::FILETYPE_BANNER);
        $attachedSrch->addMultipleFields(
            ['afile_id,banner_id as record_id', 'afile_lang_id', 'banner_title as record_name', 'afile_type']
        );

        return $attachedSrch;
    }

    private function joinHomePageBanner($attachedSrch)
    {
        $attachedSrch->joinTable(Slides::DB_TBL, 'LEFT OUTER JOIN', 'slide_id = afile_record_id', 'slide');
        $attachedSrch->addCondition('afile_type', '=', AttachedFile::FILETYPE_HOME_PAGE_BANNER);
        $attachedSrch->addMultipleFields(
            ['afile_id,slide_id as record_id', 'afile_lang_id', 'slide_identifier as record_name', 'afile_type']
        );

        return $attachedSrch;
    }

    private function joinContentBackgroudImage($attachedSrch)
    {
        $attachedSrch->joinTable(ContentPage::DB_TBL, 'LEFT OUTER JOIN', 'cpage_id = afile_record_id', 'cp');
        $attachedSrch->addCondition('afile_type', '=', AttachedFile::FILETYPE_CPAGE_BACKGROUND_IMAGE);
        $attachedSrch->addCondition('cpage_deleted', '=', applicationConstants::NO);
        $attachedSrch->addMultipleFields(
            ['afile_id,cpage_id as record_id', 'afile_lang_id', 'cpage_identifier as record_name', 'afile_type']
        );
        return $attachedSrch;
    }

    private function joinTeachingLanguage($attachedSrch)
    {
        $attachedSrch->joinTable(TeachingLanguage::DB_TBL, 'LEFT OUTER JOIN', 'tlanguage_id = afile_record_id', 'tl');
        $attachedSrch->addCondition('afile_type', '=', AttachedFile::FILETYPE_TEACHING_LANGUAGES);
        $attachedSrch->addMultipleFields(
            ['afile_id,tlanguage_id as record_id', 'afile_lang_id', 'tlanguage_identifier as record_name', 'afile_type']
        );
        return $attachedSrch;
    }

    private function joinFlagTeachingLangugage($attachedSrch)
    {
        $attachedSrch->joinTable(TeachingLanguage::DB_TBL, 'LEFT OUTER JOIN', 'tlanguage_id = afile_record_id', 'tl');
        $attachedSrch->addCondition('afile_type', '=', AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES);
        $attachedSrch->addMultipleFields(
            ['afile_id,tlanguage_id as record_id', 'afile_lang_id', 'tlanguage_identifier as record_name', 'afile_type']
        );
        return $attachedSrch;
    }

    private function joinBlogPostImage($attachedSrch)
    {
        $attachedSrch->joinTable(BlogPost::DB_TBL, 'LEFT OUTER JOIN', 'post_id = afile_record_id', 'bp');
        $attachedSrch->addCondition('afile_type', '=', AttachedFile::FILETYPE_BLOG_POST_IMAGE);
        $attachedSrch->addMultipleFields(
            ['afile_id,post_id as record_id', 'afile_lang_id', 'post_identifier as record_name', 'afile_type']
        );
        return $attachedSrch;
    }
}
