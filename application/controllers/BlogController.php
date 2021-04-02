<?php
class BlogController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->set('blogPage', true);
        $this->set('bodyClass', 'is--blog');
    }

    public function getBreadcrumbNodes($action)
    {
        $nodes = array();
        $className = get_class($this);
        $arr = explode('-', FatUtility::camel2dashed($className));
        array_pop($arr);
        $urlController = implode('-', $arr);
        $className = ucwords(implode(' ', $arr));
        if ($action == 'index') {
            $nodes[] = array('title' => $className);
        } else {
            $nodes[] = array('title' => $className, 'href' => CommonHelper::generateUrl($urlController));
        }
        $parameters = FatApp::getParameters();

        if (!empty($parameters)) {
            if ($action == 'category') {
                $id = reset($parameters);
                $id = FatUtility::int($id);
                $data = BlogPostCategory::getAttributesByLangId($this->siteLangId, $id);
                $title = $data['bpcategory_name'];
                $nodes[] = array('title' => $title);
            } elseif ($action == 'postDetail') {
                $id = reset($parameters);
                $id = FatUtility::int($id);
                $data = BlogPost::getAttributesByLangId($this->siteLangId, $id);
                $title = CommonHelper::truncateCharacters($data['post_title'], 40);
                $nodes[] = array('title' => $title);
            }
        } elseif ($action == 'contributionForm' || $action == 'setupContribution') {
            $nodes[] = array('title' => Label::getLabel('Lbl_Contribution', $this->siteLangId));
        }
        return $nodes;
    }

    public function index()
    {
        $this->_template->render();
    }

    public function category($categoryId)
    {
        $categoryId = FatUtility::int($categoryId);
        if ($categoryId < 1) {
            Message::addErrorMessage(Label::getLabel('Lbl_Invalid_Request', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        $this->set('bpCategoryId', $categoryId);
        $this->_template->render(true, true, 'blog/index.php');
    }

    public function search()
    {
        $post = FatApp::getPostedData();
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : FatUtility::int($post['page']);
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = BlogPost::getSearchObject($this->siteLangId, true, false, true);
        $srch->addMultipleFields(array('bp.*', 'IFNULL(bp_l.post_title,post_identifier) as post_title', 'bp_l.post_author_name', 'bp_l.post_short_description', 'group_concat(bpcategory_id) categoryIds', 'group_concat(IFNULL(bpcategory_name, bpcategory_identifier) SEPARATOR "~") categoryNames', 'group_concat(GETBLOGCATCODE(bpcategory_id)) AS categoryCodes'));
        $srch->addCondition('postlang_post_id', 'is not', 'mysql_func_null', 'and', true);
        if ($categoryId = FatApp::getPostedData('categoryId', FatUtility::VAR_INT, 0)) {
            $srch->addCondition('ptc_bpcategory_id', '=', $categoryId);
        } elseif ($keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '')) {
            $keywordCond = $srch->addCondition('post_title', 'like', "%$keyword%");
            $keywordCond->attachCondition('post_short_description', 'like', "%$keyword%");
            $keywordCond->attachCondition('post_description', 'like', "%$keyword%");
        }
        $srch->addCondition('post_published', '=', applicationConstants::YES);
        $srch->addOrder('post_added_on', 'desc');
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $srch->addGroupby('post_id');
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        $this->set("postList", $records);
        $this->set('recordCount', $srch->recordCount());
        $this->set('postedData', $post);
        $json['html'] = $this->_template->render(false, false, 'blog/search.php', true);
        $json['loadMoreBtnHtml'] = $this->_template->render(false, false, 'blog/load-more-btn.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }

    public function postDetail($blogPostId)
    {
        $blogPostId = FatUtility::int($blogPostId);
        if ($blogPostId <= 0) {
            Message::addErrorMessage(Label::getLabel('Lbl_Invalid_Request', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('Blog'));
        }
        $post_images = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_BLOG_POST_IMAGE, $blogPostId, 0, $this->siteLangId);
        $this->set('post_images', $post_images);
        $srch = BlogPost::getSearchObject($this->siteLangId, true, true);
        $srch->addCondition('post_id', '=', $blogPostId);
        $srch->addMultipleFields(array('bp.*', 'IFNULL(bp_l.post_title,post_identifier) as post_title', 'bp_l.post_author_name', 'bp_l.post_description', 'group_concat(bpcategory_id) categoryIds', 'group_concat(IFNULL(bpcategory_name, bpcategory_identifier) SEPARATOR "~") categoryNames'));
        $srchComment = clone $srch;
        $srch->addGroupby('post_id');
        if (!$blogPostData = FatApp::getDb()->fetch($srch->getResultSet())) {
            Message::addErrorMessage(Label::getLabel('Lbl_Invalid_Request', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('Blog'));
        }
        $this->set('blogPostData', $blogPostData);
        $srchComment->addGroupby('bpcomment_id');
        $srchComment->joinTable(BlogComment::DB_TBL, 'inner join', 'bpcomment.bpcomment_post_id = post_id and bpcomment.bpcomment_deleted=0', 'bpcomment');
        $srchComment->addMultipleFields(array('bpcomment.*'));
        $srchComment->addCondition('bpcomment_approved', '=', BlogComment::COMMENT_STATUS_APPROVED);
        $commentsResultSet = $srchComment->getResultSet();
        $this->set('commentsCount', $srchComment->recordCount());
        $this->set('blogPostComments', FatApp::getDb()->fetchAll($commentsResultSet));
        if ($blogPostData['post_comment_opened'] && UserAuthentication::isUserLogged()) {
            $frm = $this->getPostCommentForm($blogPostId);
            if (UserAuthentication::isUserLogged()) {
                $loggedUserId = UserAuthentication::getLoggedUserId(true);
                $userObj = new User($loggedUserId);
                $userInfo = $userObj->getUserInfo();
                $frm->getField('bpcomment_author_name')->value = $userInfo['user_first_name'] . ' ' . $userInfo['user_last_name'];
                $frm->getField('bpcomment_author_email')->value = $userInfo['credential_email'];
            }
            $this->set('postCommentFrm', $frm);
        }
        $title = $blogPostData['post_title'];
        $post_description = trim(CommonHelper::subStringByWords(strip_tags(CommonHelper::renderHtml($blogPostData["post_description"], true)), 500));
        $post_description .= ' - ' . Label::getLabel('LBL_See_more_at', $this->siteLangId) . ": " . CommonHelper::getCurrUrl();
        $postImageUrl = CommonHelper::generateFullUrl('Image', 'blogPostFront', array($blogPostData['post_id'], $this->siteLangId, ''));
        $socialShareContent = array(
            'type' => 'Blog Post',
            'title' => $title,
            'description' => $post_description,
            'image' => $postImageUrl,
        );
        $this->set('socialShareContent', $socialShareContent);
        $srchCommentsFrm = $this->getCommentSearchForm($blogPostId);
        $this->set('srchCommentsFrm', $srchCommentsFrm);
        $this->_template->addJs(array('js/slick.js'));
        $this->_template->addCss(array('css/slick.css'));
        $this->_template->render();
    }

    public function setupPostComment()
    {
        $userId = UserAuthentication::getLoggedUserId(true);
        $userId = FatUtility::int($userId);
        if (1 > $userId) {
            Message::addErrorMessage(Label::getLabel('MSG_User_Not_Logged', $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $blogPostId = FatApp::getPostedData('bpcomment_post_id', FatUtility::VAR_INT, 0);
        if ($blogPostId <= 0) {
            Message::addErrorMessage(Label::getLabel('Lbl_Invalid_Request'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getPostCommentForm($blogPostId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
        /* checking Abusive Words[ */
        $enteredAbusiveWordsArr = array();
        if (!Abusive::validateContent($post['bpcomment_content'], $enteredAbusiveWordsArr)) {
            if (!empty($enteredAbusiveWordsArr)) {
                $errStr =  Label::getLabel("LBL_Word_{abusiveword}_is/are_not_allowed_to_post", $this->siteLangId);
                $errStr = str_replace("{abusiveword}", '"' . implode(", ", $enteredAbusiveWordsArr) . '"', $errStr);
                Message::addErrorMessage($errStr);
                FatUtility::dieWithError(Message::getHtml());
            }
        }
        /* ] */
        $post['bpcomment_user_id'] = $userId;
        $post['bpcomment_added_on'] = date('Y-m-d H:i:s');
        $post['bpcomment_user_ip'] = $_SERVER['REMOTE_ADDR'];
        $post['bpcomment_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $blogComment = new BlogComment();
        $blogComment->assignValues($post);
        if (!$blogComment->save()) {
            Message::addErrorMessage($blogComment->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('Msg_Blog_Comment_Saved_and_awaiting_admin_approval.', $this->siteLangId));
    }

    public function searchComments()
    {
        $post = FatApp::getPostedData();
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : FatUtility::int($post['page']);
        $pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $blogPostId = FatApp::getPostedData('post_id', FatUtility::VAR_INT, 0);
        $srch = BlogPost::getSearchObject($this->siteLangId, true, true);
        $srch->joinTable(BlogComment::DB_TBL, 'inner join', 'bpcomment.bpcomment_post_id = post_id and bpcomment.bpcomment_deleted=0', 'bpcomment');
        $srch->addMultipleFields(array('bpcomment.*'));
        $srch->addCondition('bpcomment_approved', '=', BlogComment::COMMENT_STATUS_APPROVED);
        $srch->addCondition('post_id', '=', $blogPostId);
        $srch->setPageSize($pageSize);
        $srch->setPageNumber($page);
        $srch->addGroupby('bpcomment_id');
        $srch->addOrder('bpcomment_added_on', 'desc');
        $this->set('blogPostComments', FatApp::getDb()->fetchAll($srch->getResultSet()));
        $this->set('commentsCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        $this->set('postedData', $post);
        $json['html'] = $this->_template->render(false, false, 'blog/search-comments.php', true, false);
        $json['loadMoreBtnHtml'] = $this->_template->render(false, false, 'blog/load-more-comments-btn.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }

    public function contributionForm()
    {
        $frm = $this->getContributionForm();
        if (UserAuthentication::isUserLogged()) {
            $loggedUserId = UserAuthentication::getLoggedUserId(true);
            $userObj = new User($loggedUserId);
            $userInfo = $userObj->getUserInfo();
            $frm->getField('bcontributions_author_first_name')->value = $userInfo['user_first_name'];
            $frm->getField('bcontributions_author_last_name')->value = $userInfo['user_last_name'];
            $frm->getField('bcontributions_author_email')->value = $userInfo['credential_email'];
            $frm->getField('bcontributions_author_phone')->value = $userInfo['user_phone'];
        }
        if ($post = FatApp::getPostedData()) {
            $frm->fill($post);
        }
        $this->set('frm', $frm);
        $this->_template->render(true, true, 'blog/contribution-form.php');
    }

    public function setupContribution()
    {
        $frm = $this->getContributionForm();
        $post = FatApp::getPostedData();
        $post['file'] = 'file';
        $post = $frm->getFormDataFromArray($post);
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            $this->contributionForm();
            return false;
        }

        if (FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '') != '' && FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '') != '') {
            if (!CommonHelper::verifyCaptcha()) {
                Message::addErrorMessage(Label::getLabel('MSG_That_captcha_was_incorrect', $this->siteLangId));
                $this->contributionForm();
                return false;
            }
        }
        $post['bcontributions_added_on'] = date('Y-m-d H:i:s');
        $post['bcontributions_user_id'] = UserAuthentication::getLoggedUserId(true);
        if ($loggedUserId = UserAuthentication::getLoggedUserId(true)) {
            $userObj = new User($loggedUserId);
            $userInfo = $userObj->getUserInfo();
            $post['bcontributions_author_first_name'] = $userInfo['user_first_name'];
        }
        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Please_select_a_file', $this->siteLangId));
            $this->contributionForm();
            return false;
        } else {
            $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $fileExt = strtolower($fileExt);
            if (!in_array($fileExt, applicationConstants::allowedFileExtensions())) {
                Message::addErrorMessage(Label::getLabel('MSG_INVALID_FILE_EXTENSION', $this->siteLangId));
                $this->contributionForm();
                return false;
            }

            $fileMimeType = mime_content_type($_FILES['file']['tmp_name']);
            if (!in_array($fileMimeType, applicationConstants::allowedMimeTypes())) {
                Message::addErrorMessage(Label::getLabel('MSG_INVALID_FILE_MIME_TYPE', $this->siteLangId));
                $this->contributionForm();
                return false;
            }
        }
        $uploadedFile = $_FILES['file']['tmp_name'];
        if (!AttachedFile::checkSize($uploadedFile, 10240000)) {
            Message::addErrorMessage(Label::getLabel('MSG_Please_upload_file_size_less_than_10MB', $this->siteLangId));
            $this->contributionForm();
            return false;
        }
        $contribution = new BlogContribution();
        $contribution->assignValues($post);
        if (!$contribution->save()) {
            Message::addErrorMessage($contribution->getError());
            $this->contributionForm();
            return false;
        }
        $contributionId = $contribution->getMainTableRecordId();
        $fileHandlerObj = new AttachedFile();
        if (!$res = $fileHandlerObj->saveAttachment($_FILES['file']['tmp_name'], AttachedFile::FILETYPE_BLOG_CONTRIBUTION, $contributionId, 0, $_FILES['file']['name'], -1, $unique_record = true)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            $this->contributionForm();
            return false;
        }
        Message::addMessage(Label::getLabel('Lbl_Contributed_Successfully', $this->siteLangId));
        FatApp::redirectUser(CommonHelper::generateUrl('Blog', 'contributionForm'));
    }

    private function getContributionForm()
    {
        $frm = new Form('frmBlogContribution');
        $frm->addRequiredField(Label::getLabel('LBL_First_Name', $this->siteLangId), 'bcontributions_author_first_name', '');
        $frm->addRequiredField(Label::getLabel('LBL_Last_Name', $this->siteLangId), 'bcontributions_author_last_name', '');
        $frm->addEmailField(Label::getLabel('LBL_Email_Address', $this->siteLangId), 'bcontributions_author_email', '');
        $fld_phn = $frm->addRequiredField(Label::getLabel('LBL_Phone', $this->siteLangId), 'bcontributions_author_phone');
        $fld_phn->requirements()->setRegularExpressionToValidate('^[\s()+-]*([0-9][\s()+-]*){5,20}$');
        $frm->addFileUpload(Label::getLabel('LBL_Upload_File', $this->siteLangId), 'file')->requirements()->setRequired(true);
        if (FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '') != '' && FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '') != '') {
            $frm->addHtml('', 'htmlNote', '<div class="g-recaptcha" data-sitekey="' . FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '') . '"></div>');
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('BTN_SUBMIT', $this->siteLangId));
        return $frm;
    }

    private function getPostCommentForm($postId)
    {
        $frm = new Form('frmBlogPostComment');
        $frm->addTextarea(Label::getLabel('LBL_Message', $this->siteLangId), 'bpcomment_content')->requirements()->setRequired(true);
        $frm->addRequiredField(Label::getLabel('LBL_Name', $this->siteLangId), 'bpcomment_author_name');
        $frm->addEmailField(Label::getLabel('LBL_Email_Address', $this->siteLangId), 'bpcomment_author_email', '');
        $frm->addHiddenField('', 'bpcomment_post_id', $postId);
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('Btn_Post_Comment', $this->siteLangId));
        return $frm;
    }

    private function getCommentSearchForm($postId)
    {
        $frm = new Form('frmSearchComments');
        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'post_id', $postId);
        return $frm;
    }
}
