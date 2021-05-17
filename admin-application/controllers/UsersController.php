<?php

class UsersController extends AdminBaseController
{

    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewUsers();
    }

    public function index()
    {
        $frmSearch = $this->getUserSearchForm();
        $data = FatApp::getPostedData();
        if ($data) {
            $data['user_id'] = $data['id'];
            unset($data['id']);
            $frmSearch->fill($data);
        }
        $this->set('frmSearch', $frmSearch);
        $this->_template->addJs('js/import-export.js');
        $this->_template->render();
    }

    public function search()
    {
        $canEdit = $this->objPrivilege->canEditUsers(AdminAuthentication::getLoggedAdminId(), true);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $frmSearch = $this->getUserSearchForm();
        $data = FatApp::getPostedData();
        $post = $frmSearch->getFormDataFromArray($data);
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $userObj = new User();
        $srch = $userObj->getUserSearchObj(null, true);
        $srch->addOrder('u.user_id', 'DESC');
        $srch->addOrder('credential_active', 'DESC');
        $user_id = FatApp::getPostedData('user_id', FatUtility::VAR_INT, -1);
        if ($user_id > 0) {
            $srch->addCondition('user_id', '=', $user_id);
        } else {
            $keyword = FatApp::getPostedData('keyword', null, '');
            if (!empty($keyword)) {
                $keywordsArr = array_unique(array_filter(explode(' ', $keyword)));
                foreach ($keywordsArr as $kw) {
                    $cnd = $srch->addCondition('u.user_first_name', 'like', '%' . $kw . '%');
                    $cnd->attachCondition('u.user_last_name', 'like', '%' . $kw . '%');
                    $cnd->attachCondition('uc.credential_username', 'like', '%' . $kw . '%');
                    $cnd->attachCondition('uc.credential_email', 'like', '%' . $kw . '%');
                }
            }
        }
        $user_active = FatApp::getPostedData('user_active', FatUtility::VAR_INT, -1);
        if ($user_active > -1) {
            $srch->addCondition('uc.credential_active', '=', $user_active);
        }
        $user_verified = FatApp::getPostedData('user_verified', FatUtility::VAR_INT, -1);
        if ($user_verified > -1) {
            $srch->addCondition('uc.credential_verified', '=', $user_verified);
        }
        $type = FatApp::getPostedData('type', FatUtility::VAR_STRING, 0);
        switch ($type) {
            case User::USER_TYPE_LEANER:
                $srch->addCondition('u.user_is_learner', '=', applicationConstants::YES);
                $srch->addCondition('u.user_is_teacher', '=', applicationConstants::NO);
                break;
            case User::USER_TYPE_TEACHER:
                $srch->addCondition('u.user_is_teacher', '=', applicationConstants::YES);
                break;
            case User::USER_TYPE_LEARNER_TEACHER:
                $srch->addCondition('u.user_is_teacher', '=', applicationConstants::YES);
                $srch->addCondition('u.user_is_learner', '=', applicationConstants::YES);
                break;
        }
        $user_regdate_from = FatApp::getPostedData('user_regdate_from', FatUtility::VAR_DATE, '');
        if (!empty($user_regdate_from)) {
            $srch->addCondition('user_added_on', '>=', $user_regdate_from . ' 00:00:00');
        }
        $user_regdate_to = FatApp::getPostedData('user_regdate_to', FatUtility::VAR_DATE, '');
        if (!empty($user_regdate_to)) {
            $srch->addCondition('user_added_on', '<=', $user_regdate_to . ' 23:59:59');
        }
        $latestTeacherRequest = new SearchBase(TeacherRequest::DB_TBL, 'ltr');
        ;
        $latestTeacherRequest->addFld(['max(ltr.utrequest_id) latestRequestId']);
        $latestTeacherRequest->addGroupBy('ltr.utrequest_user_id');
        $latestTeacherRequest->doNotCalculateRecords();
        $latestTeacherRequest->doNotLimitRecords();
        $teacherRequest = new TeacherRequestSearch(false);
        $teacherRequest->doNotCalculateRecords();
        $teacherRequest->doNotLimitRecords();
        $teacherRequest->joinTable("(" . $latestTeacherRequest->getQuery() . ")", 'INNER JOIN', 'lastrequest.latestRequestId = tr.utrequest_id', 'lastrequest');
        $srch->joinTable("(" . $teacherRequest->getQuery() . ")", 'LEFT JOIN', 'utr.utrequest_user_id = user_id', 'utr');
        $srch->addFld([
            'user_is_learner',
            'user_is_teacher',
            'user_first_name',
            'user_last_name',
            'user_registered_initially_for',
            'utr.utrequest_status',
            'utr.utrequest_id',
            'utr.utrequest_user_id'
        ]);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs, 'user_id');
        $adminId = AdminAuthentication::getLoggedAdminId();
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('canEdit', $canEdit);
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false);
    }

    public function login($userId)
    {
        $this->objPrivilege->canEditUsers();
        $userObj = new User($userId);
        $user = $userObj->getUserInfo(['credential_username', 'credential_password'], false, false);
        $isAjaxRequest = FatUtility::isAjaxCall();
        if (!$user) {
            Message::addErrorMessage($this->str_invalid_request);
            if ($isAjaxRequest) {
                FatUtility::dieJsonError(Message::getHtml());
            }
            FatApp::redirectUser(CommonHelper::generateUrl('Users'));
        }
        $userAuthObj = new UserAuthentication();
        if (!$userAuthObj->login($user['credential_username'], $user['credential_password'], $_SERVER['REMOTE_ADDR'], false, true) === true) {
            Message::addErrorMessage($userAuthObj->getError());
            if ($isAjaxRequest) {
                FatUtility::dieJsonError(Message::getHtml());
            }
            FatApp::redirectUser(CommonHelper::generateUrl('Users'));
        }
        if ($isAjaxRequest) {
            FatUtility::dieJsonSuccess(Label::getLabel("MSG_LOGIN_SUCCESSFULL"));
        }
        FatApp::redirectUser(CommonHelper::generateUrl('account', '', [], CONF_WEBROOT_FRONTEND));
    }

    public function setup()
    {
        $this->objPrivilege->canEditUsers();
        $frm = $this->getForm();
        $post = FatApp::getPostedData();
        $post = $frm->getFormDataFromArray($post);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $user_id = FatUtility::int($post['user_id']);
        unset($post['user_id']);
        unset($post['credential_username']);
        unset($post['credential_email']);
        $userObj = new User($user_id);
        $userObj->assignValues($post);
        if (!$userObj->save()) {
            Message::addErrorMessage($userObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function form($user_id = 0)
    {
        $this->objPrivilege->canEditUsers();
        $user_id = FatUtility::int($user_id);
        $frmUser = $this->getForm($user_id);
        $stateId = 0;
        if (0 < $user_id) {
            $userObj = new User($user_id);
            $srch = $userObj->getUserSearchObj();
            $srch->addMultipleFields(['u.*']);
            $data = FatApp::getDb()->fetch($srch->getResultSet(), 'user_id');
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $stateId = $data['user_state_id'];
            $frmUser->fill($data);
        }
        $this->set('user_id', $user_id);
        $this->set('stateId', $stateId);
        $this->set('frmUser', $frmUser);
        $this->_template->render(false, false);
    }

    public function view($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        $stateId = 0;
        if (0 < $user_id) {
            $userObj = new User($user_id);
            $srch = $userObj->getUserSearchObj();
            $srch->addMultipleFields(['u.*', 'country_name', 'state_name']);
            $srch->joinTable('tbl_countries_lang', 'LEFT JOIN', 'u.user_country_id=countrylang_country_id');
            $srch->joinTable('tbl_states_lang', 'LEFT JOIN', 'u.user_state_id=statelang_state_id');
            $rs = $srch->getResultSet();
            if (!$rs) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $data = FatApp::getDb()->fetch($rs, 'user_id');
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
        }
        $stateId = $data['user_state_id'];
        $this->set('stateId', $stateId);
        $this->set('user_id', $user_id);
        $this->set('data', $data);
        $this->_template->render(false, false);
    }

    public function transaction($userId = 0)
    {
        $userId = FatUtility::int($userId);
        if (1 > $userId) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $post = FatApp::getPostedData();
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : $post['page'];
        $page = (empty($page) || $page <= 0) ? 1 : FatUtility::int($page);
        $srch = Transaction::getSearchObject();
        $srch->addCondition('utxn.utxn_user_id', '=', $userId);
        $balSrch = Transaction::getSearchObject();
        $balSrch->doNotCalculateRecords();
        $balSrch->doNotLimitRecords();
        $balSrch->addMultipleFields(['utxn.*', "utxn_credit - utxn_debit as bal"]);
        $balSrch->addCondition('utxn_user_id', '=', $userId);
        $balSrch->addCondition('utxn_status', '=', 1);
        $qryUserPointsBalance = $balSrch->getQuery();
        $srch->joinTable('(' . $qryUserPointsBalance . ')', 'JOIN', 'tqupb.utxn_id <= utxn.utxn_id', 'tqupb');
        $srch->addMultipleFields(['utxn.*', "SUM(tqupb.bal) balance"]);
        $srch->addOrder('utxn_id', 'DESC');
        $srch->addGroupBy('utxn.utxn_id');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('userId', $userId);
        $this->set('statusArr', Transaction::getStatusArr($this->adminLangId));
        $this->_template->render(false, false);
    }

    public function addUserTransaction($userId = 0)
    {
        $userId = FatUtility::int($userId);
        if (1 > $userId) {
            FatUtility::dieWithError($this->str_invalid_request_id);
        }
        $frm = $this->addUserTransactionForm($this->adminLangId);
        $frm->fill(['user_id' => $userId]);
        $this->set('userId', $userId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setupUserTransaction()
    {
        $this->objPrivilege->canEditUsers();
        $frm = $this->addUserTransactionForm($this->adminLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userId = FatUtility::int($post['user_id']);
        if (1 > $userId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $tObj = new Transaction($userId);
        $data = [
            'utxn_user_id' => $userId,
            'utxn_date' => date('Y-m-d H:i:s'),
            'utxn_comments' => $post['description'],
            'utxn_status' => Transaction::STATUS_COMPLETED
        ];
        if ($post['type'] == Transaction::CREDIT_TYPE) {
            $data['utxn_credit'] = $post['amount'];
        }
        if ($post['type'] == Transaction::DEBIT_TYPE) {
            $data['utxn_debit'] = $post['amount'];
        }
        if (!$tObj->addTransaction($data)) {
            Message::addErrorMessage($tObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        /* send email to user[ */
        $emailNotificationObj = new EmailHandler();
        $emailNotificationObj->sendTxnNotification($tObj->getMainTableRecordId(), $this->adminLangId);
        /* ] */
        $userNotification = new UserNotifications($userId);
        $userNotification->sendWalletCreditNotification();
        $this->set('userId', $userId);
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function changePasswordForm($user_id)
    {
        $this->objPrivilege->canEditUsers();
        $user_id = FatUtility::int($user_id);
        $frm = $this->getChangePasswordForm($user_id);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function updatePassword()
    {
        $pwdFrm = $this->getChangePasswordForm();
        $post = $pwdFrm->getFormDataFromArray(FatApp::getPostedData());
        if (!$pwdFrm->validate($post)) {
            Message::addErrorMessage($pwdFrm->getValidationErrors());
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ($post['new_password'] != $post['conf_new_password']) {
            Message::addErrorMessage(Label::getLabel('LBL_New_Password_and_Confirm_new_password_does_not_match', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (true !== CommonHelper::validatePassword($post['new_password'])) {
            Message::addErrorMessage(Label::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $user_id = FatUtility::int($post['user_id']);
        if ($user_id < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userObj = new User($user_id);
        $srch = $userObj->getUserSearchObj(['user_id']);
        $rs = $srch->getResultSet();
        if (!$rs) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $data = FatApp::getDb()->fetch($rs, 'user_id');
        if ($data === false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (!$userObj->setLoginPassword($post['new_password'])) {
            Message::addErrorMessage(Label::getLabel('LBL_Password_could_not_be_set ', $this->adminLangId) . ' ' . $userObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        // TODo:: Can send change password notification using configuration
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
    
    public function autoCompleteJson()
    {
        $pagesize = 20;
        $post = FatApp::getPostedData();
        $skipDeletedUser = true;
        if (isset($post['deletedUser']) && $post['deletedUser'] == true) {
            $skipDeletedUser = false;
        }
        $userObj = new User();
        $srch = $userObj->getUserSearchObj([
            'u.user_first_name',
            'u.user_last_name',
            'u.user_id',
            'credential_username',
            'credential_email'
                ], false, $skipDeletedUser);
        if (!$skipDeletedUser) {
            $srch->addCondition('user_deleted', '=', applicationConstants::YES);
        }
        $srch->addOrder('credential_email', 'ASC');
        $keyword = FatApp::getPostedData('keyword', null, '');
        if (!empty($keyword)) {
            $cond = $srch->addCondition('uc.credential_username', 'like', '%' . $keyword . '%');
            $cond->attachCondition('uc.credential_email', 'like', '%' . $keyword . '%', 'OR');
            $cond->attachCondition('u.user_first_name', 'like', '%' . $keyword . '%');
            $cond->attachCondition('u.user_last_name', 'like', '%' . $keyword . '%');
        }
        if (isset($post['user_is_learner'])) {
            $user_is_learner = FatUtility::int($post['user_is_learner']);
            $srch->addCondition('u.user_is_learner', '=', $user_is_learner);
        }
        if (isset($post['user_is_teacher'])) {
            $user_is_teacher = FatUtility::int($post['user_is_teacher']);
            $srch->addCondition('u.user_is_teacher', '=', $user_is_teacher);
        }
        if (isset($post['credential_active'])) {
            $credential_active = $post['credential_active'];
            $srch->addCondition('uc.credential_active', '=', $credential_active);
        }
        if (isset($post['credential_verified'])) {
            $credential_verified = $post['credential_verified'];
            $srch->addCondition('uc.credential_verified', '=', $credential_verified);
        }
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $users = $db->fetchAll($rs, 'user_id');
        $json = [];
        foreach ($users as $key => $user) {
            $user_full_name = strip_tags(html_entity_decode($user['user_first_name'], ENT_QUOTES, 'UTF-8')) . ' ' . strip_tags(html_entity_decode($user['user_last_name'], ENT_QUOTES, 'UTF-8'));
            $json[] = [
                'id' => $key, 'name' => $user_full_name,
                'username' => strip_tags(html_entity_decode($user['credential_username'], ENT_QUOTES, 'UTF-8')),
                'credential_email' => strip_tags(html_entity_decode($user['credential_email'], ENT_QUOTES, 'UTF-8'))
            ];
        }
        die(json_encode($json));
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditUsers();
        $userId = FatApp::getPostedData('userId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (0 == $userId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $userObj = new User($userId);
        $srch = $userObj->getUserSearchObj();
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        if (!$userObj->activateAccount($status)) {
            Message::addErrorMessage($userObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function activate()
    {
        $this->objPrivilege->canEditUsers();
        $userId = FatApp::getPostedData('userId', FatUtility::VAR_INT);
        $v = FatApp::getPostedData('v', FatUtility::VAR_INT);
        $userObj = new User($userId);
        if (!$userObj->activateAccount($v)) {
            Message::addErrorMessage($userObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', ((1 == $v) ? Label::getLabel('MSG_Account_Deactivated', $this->adminLangId) : Label::getLabel('MSG_Account_Activated', $this->adminLangId)));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function createTestUsers(int $user_count = 2500)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try {
            $this->createTestTeachers($user_count);
            $this->createTestStudents($user_count);
        } catch (Exception $e) {
            die($e->getMessage());
        }
        die('<h2>Users created Successfully</h2>');
    }

    private function createTestStudents(int $user_count)
    {
        $db = FatApp::getDb();
        $db->startTransaction();
        $attachments1 = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_USER_PROFILE_IMAGE, 1);
        $attachments2 = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE, 1);
        $attachments = $attachments1 + $attachments2;
        for ($i = 0; $i < $user_count; $i++) {
            $user_data = [
                'user_first_name' => 'Test',
                'user_last_name' => 'Learner' . ($i + 1),
                'user_email' => 'testlearner' . ($i + 1) . '@dummyid.com',
                'user_password' => 'learner@123',
                'user_is_learner' => 1,
                'user_timezone' => MyDate::getTimeZone(),
                'user_country_id' => 91,
                'user_preferred_dashboard' => User::USER_LEARNER_DASHBOARD,
                'user_registered_initially_for' => User::USER_TYPE_LEANER,
            ];
            $userSrch = new UserSearch(true, false);
            $userId = $userSrch->getUserIdByEmail($user_data['user_email']);
            $user = new User($userId);
            $user->assignValues($user_data);
            if (!$user->save()) {
                $db->rollbackTransaction();
                throw new Exception($user->getError());
            }
            if (!$user->setLoginCredentials($user_data['user_email'], $user_data['user_email'], $user_data['user_password'], 1, 1)) {
                $db->rollbackTransaction();
                throw new Exception($user->getError());
            }
            $userId = $user->getMainTableRecordId();
            if (!User::isProfilePicUploaded($userId)) {
                foreach ($attachments as $attachment) {
                    $attachment['afile_id'] = 0;
                    $attachment['afile_record_id'] = $userId;
                    $attachedFile = new AttachedFile();
                    $attachedFile->assignValues($attachment);
                    if (!$attachedFile->save()) {
                        $db->rollbackTransaction();
                        throw new Exception($attachedFile->getError());
                    }
                }
            }
        }
        $db->commitTransaction();
    }

    private function createTestTeachers(int $user_count)
    {
        $db = FatApp::getDb();
        $db->startTransaction();
        $attachments1 = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_USER_PROFILE_IMAGE, 1);
        $attachments2 = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE, 1);
        $attachments = $attachments1 + $attachments2;
        for ($i = 0; $i < $user_count; $i++) {
            $user_data = [
                'user_first_name' => 'Test',
                'user_last_name' => 'Teacher' . ($i + 1),
                'user_email' => 'testteacher' . ($i + 1) . '@dummyid.com',
                'user_url_name' => 'testteacher' . ($i + 1),
                'user_password' => 'teacher@123',
                'user_is_learner' => 1,
                'user_is_teacher' => 1,
                'user_timezone' => MyDate::getTimeZone(),
                'user_country_id' => 91,
                'user_preferred_dashboard' => User::USER_TEACHER_DASHBOARD,
                'user_registered_initially_for' => User::USER_TYPE_TEACHER,
            ];
            $userSrch = new UserSearch(true, false);
            $userId = $userSrch->getUserIdByEmail($user_data['user_email']);
            $user = new User($userId);
            $user->assignValues($user_data);
            if (!$user->save()) {
                $db->rollbackTransaction();
                throw new Exception($user->getError());
            }
            if (!$user->setLoginCredentials($user_data['user_email'], $user_data['user_email'], $user_data['user_password'], 1, 1)) {
                $db->rollbackTransaction();
                throw new Exception($user->getError());
            }
            $userId = $user->getMainTableRecordId();
            if (!User::isProfilePicUploaded($userId)) {
                foreach ($attachments as $attachment) {
                    $attachment['afile_id'] = 0;
                    $attachment['afile_record_id'] = $userId;
                    $attachedFile = new AttachedFile();
                    $attachedFile->assignValues($attachment);
                    if (!$attachedFile->save()) {
                        $db->rollbackTransaction();
                        throw new Exception($attachedFile->getError());
                    }
                }
            }
            try {
                $this->makeTeacher($userId);
            } catch (Exception $e) {
                $db->rollbackTransaction();
                throw new Exception($e->getMessage());
            }
        }
        $db->commitTransaction();
    }

    private function makeTeacher($userId)
    {
        $db = FatApp::getDb();
        // add teacher settings
        $userSetting = new UserSetting($userId);
        if (!$userSetting->saveData(['us_booking_before' => 0])) {
            throw new Exception($userSetting->getError());
        }
        // add teacher spoken language
        $db->query("REPLACE INTO `tbl_spoken_languages` 
        (`slanguage_id`, `slanguage_code`, `slanguage_identifier`, `slanguage_flag`, `slanguage_display_order`, `slanguage_active`) 
        VALUES  (1, 'EN', 'English', 'gb.png', 1, 1) (2, 'FR', 'French', 'fr.png', 2, 1) ");
        $spokenData = [
            ['utsl_slanguage_id' => 1, 'utsl_proficiency' => SpokenLanguage::PROFICIENCY_BEGINNER, 'utsl_user_id' => $userId],
            ['utsl_slanguage_id' => 2, 'utsl_proficiency' => SpokenLanguage::PROFICIENCY_BEGINNER, 'utsl_user_id' => $userId]
        ];
        $userToLang = new UserToLanguage();
        foreach ($spokenData as $row) {
            $userToLang->assignValues($row);
            $userToLang->save();
        }
        // add teacher teach language
        $db->query("REPLACE INTO `tbl_teaching_languages` (`tlanguage_id`, `tlanguage_code`, `tlanguage_identifier`, `tlanguage_flag`, `tlanguage_display_order`, `tlanguage_active`) VALUES
        (1, 'EN', 'English', 'gb.png', 1, 1),        (2, 'FR', 'French', 'fr.png', 2, 1)");
        $langData = [
            [
                'utl_slanguage_id' => 1,
                'utl_user_id' => $userId,
                'utl_single_lesson_amount' => 25,
                'utl_bulk_lesson_amount' => 20,
            ],
            [
                'utl_slanguage_id' => 2,
                'utl_user_id' => $userId,
                'utl_single_lesson_amount' => 25,
                'utl_bulk_lesson_amount' => 20,
            ]
        ];
        $userToLang = new UserToLanguage($userId);
        foreach ($langData as $row) {
            if (!$userToLang->saveTeachLang($row)) {
                // throw new Exception('UserToLanuage:'.$userToLang->getError());
            }
        }
        $uqdata = [
            'uqualification_user_id' => $userId,
            'uqualification_experience_type' => 1,
            'uqualification_title' => 'test',
            'uqualification_institute_name' => 'test',
            'uqualification_institute_address' => 'test',
            'uqualification_description' => 'test',
            'uqualification_start_year' => date('Y'),
            'uqualification_end_year' => date('Y'),
            'uqualification_active' => 1,
        ];
        $uqsrch = new UserQualificationSearch();
        $uqsrch->addCondition('uqualification_user_id', '=', $userId);
        $row = FatApp::getDb()->fetch($uqsrch->getResultSet());
        $uqId = $row ? $row['uqualification_id'] : 0;
        for ($i = 0; $i < 2; $i++) {
            $uQualification = new UserQualification($uqId);
            $uQualification->assignValues($uqdata);
            if (!$uQualification->save()) {
                // throw new Exception($uQualification->getError());
            }
        }
        if (!$db->insertFromArray(Preference::DB_TBL_USER_PREF, ['utpref_preference_id' => 1, 'utpref_user_id' => $userId])) {
            // throw new Exception($db->getError());            
        }
        $availabilityData = '[{"start":"15:00:00","end":"00:00:00","startTime":"15:00","endTime":"00:00","day":"1","dayStart":"1","dayEnd":"2","classtype":1},{"start":"15:00:00","end":"00:00:00","startTime":"15:00","endTime":"00:00","day":"2","dayStart":"2","dayEnd":"3","classtype":1},{"start":"15:00:00","end":"00:00:00","startTime":"15:00","endTime":"00:00","day":"3","dayStart":"3","dayEnd":"4","classtype":1},{"start":"15:00:00","end":"00:00:00","startTime":"15:00","endTime":"00:00","day":"4","dayStart":"4","dayEnd":"5","classtype":"1"},{"start":"15:00:00","end":"00:00:00","startTime":"15:00","endTime":"00:00","day":"5","dayStart":"5","dayEnd":"6","classtype":1}]';
        $tGAvail = new TeacherGeneralAvailability();
        if (!$tGAvail->addTeacherGeneralAvailability(['data' => $availabilityData], $userId)) {
            throw new Exception($tGAvail->getError());
        }
        $weekData = [];
        $dateTime = new DateTime(date('Y-m-d'));
        $weekStartAndEndDate = MyDate::getWeekStartAndEndDate($dateTime);
        $start = $weekStartAndEndDate['weekStart'];
        $end = $weekStartAndEndDate['weekEnd'];
        while ($start <= $end) {
            $weekData[] = [
                'start' => "15:00:00",
                "end" => "00:00:00",
                "date" => $start,
                "action" => "fromGeneralAvailability",
                "classtype" => 1,
                "_id" => "_fc6"
            ];
            $start = date('Y-m-d', strtotime($start . '+1 day'));
        }
        $availabilityData = json_encode($weekData);
        $tGAvail = new TeacherWeeklySchedule();
        if (!$tGAvail->addTeacherWeeklySchedule(['data' => $availabilityData], $userId)) {
            throw new Exception($tGAvail->getError());
        }
        return true;
    }

    private function getForm($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        $frm = new Form('frmUser', ['id' => 'frmUser']);
        $frm->addHiddenField('', 'user_id', $user_id);
        $frm->addTextBox(Label::getLabel('LBL_Username', $this->adminLangId), 'credential_username', '');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name', $this->adminLangId), 'user_first_name');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Last_Name', $this->adminLangId), 'user_last_name');
        $fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone'), 'user_phone');
        $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
        $frm->addTextBox(Label::getLabel('LBL_Email', $this->adminLangId), 'credential_email', '');
        $countryObj = new Country();
        $countriesArr = $countryObj->getCountriesArr($this->adminLangId);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Country', $this->adminLangId), 'user_country_id', $countriesArr, FatApp::getConfig('CONF_COUNTRY', FatUtility::VAR_INT, 223));
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getChangePasswordForm($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        $frm = new Form('changePwdFrm');
        $frm->addHiddenField('', 'user_id', $user_id);
        $newPwd = $frm->addPasswordField(Label::getLabel('LBL_New_Password', $this->adminLangId), 'new_password', '', ['id' => 'new_password']);
        $newPwd->requirements()->setRequired();
        $conNewPwd = $frm->addPasswordField(Label::getLabel('LBL_Confirm_New_Password', $this->adminLangId), 'conf_new_password', '', ['id' => 'conf_new_password']);
        $conNewPwdReq = $conNewPwd->requirements();
        $conNewPwdReq->setRequired();
        $conNewPwdReq->setCompareWith('new_password', 'eq');
        $conNewPwdReq->setCustomErrorMessage(Label::getLabel('LBL_Confirm_Password_Not_Matched!', $this->adminLangId));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId), ['id' => 'btn_submit']);
        return $frm;
    }

    private function addUserTransactionForm($langId)
    {
        $frm = new Form('frmUserTransaction');
        $frm->addHiddenField('', 'user_id');
        $typeArr = Transaction::getCreditDebitTypeArr($langId);
        $frm->addSelectBox(Label::getLabel('LBL_Type', $this->adminLangId), 'type', $typeArr)->requirements()->setRequired(true);
        $fld = $frm->addFloatField(Label::getLabel('LBL_Amount', $this->adminLangId), 'amount');
        $fld->requirements()->setRange(1, 99999);
        $frm->addTextArea(Label::getLabel('LBL_Description', $this->adminLangId), 'description')->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

}
