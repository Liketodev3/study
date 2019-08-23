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
        $data      = FatApp::getPostedData();
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
        $canEdit   = $this->objPrivilege->canEditUsers(AdminAuthentication::getLoggedAdminId(), true);
        $pagesize  = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $frmSearch = $this->getUserSearchForm();
        $data      = FatApp::getPostedData();
        $post      = $frmSearch->getFormDataFromArray($data);
        $page      = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $userObj = new User();
        $srch    = $userObj->getUserSearchObj(null, true);
        $srch->addOrder('u.user_id', 'DESC');
        $srch->addOrder('credential_active', 'DESC');
        $user_id = FatApp::getPostedData('user_id', FatUtility::VAR_INT, -1);
        if ($user_id > 0) {
            $srch->addCondition('user_id', '=', $user_id);
        } else {
            $keyword = FatApp::getPostedData('keyword', null, '');
            if (!empty($keyword)) {
				$keywordsArr = array_unique(array_filter( explode( ' ', $keyword ) ));
				foreach( $keywordsArr as $kw ){
					$cnd = $srch->addCondition('u.user_first_name', 'like', '%'.$kw.'%');
					$cnd->attachCondition( 'u.user_last_name', 'like', '%'.$kw.'%');
					$cnd->attachCondition( 'uc.credential_username', 'like', '%'.$kw.'%');
					$cnd->attachCondition( 'uc.credential_email', 'like', '%'.$kw.'%');
				}

                /* $cnd = $srch->addCondition('uc.credential_username', 'like', '%' . $keyword . '%');
                $cnd->attachCondition('uc.credential_email', 'like', '%' . $keyword . '%', 'OR');
                $cnd->attachCondition('u.user_first_name', 'like', '%' . $keyword . '%');
                $cnd->attachCondition('u.user_last_name', 'like', '%' . $keyword . '%'); */
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
                break;
            case User::USER_TYPE_TEACHER:
                $srch->addCondition('u.user_is_teacher', '=', applicationConstants::YES);
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
        $srch->addFld(array(
            'user_is_learner',
            'user_is_teacher',
			'user_first_name',
			'user_last_name',
			'user_registered_initially_for'
        ));
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        //echo $srch->getQuery();
        $rs      = $srch->getResultSet();
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
        $user    = $userObj->getUserInfo(array(
            'credential_username',
            'credential_password'
        ), false, false);
        if (!$user) {
            Message::addErrorMessage($this->str_invalid_request);
            FatApp::redirectUser(CommonHelper::generateUrl('Users'));
        }
        $userAuthObj = new UserAuthentication();
        if (!$userAuthObj->login($user['credential_username'], $user['credential_password'], $_SERVER['REMOTE_ADDR'], false, true) === true) {
            Message::addErrorMessage($userObj->getError());
            FatApp::redirectUser(CommonHelper::generateUrl('Users'));
        }
        FatApp::redirectUser(CommonHelper::generateUrl('account', '', array(), '/'));
    }

	public function setup()
    {
        $this->objPrivilege->canEditUsers();
        $frm                   = $this->getForm();
        $post                  = FatApp::getPostedData();
        //$user_state_id         = FatUtility::int($post['user_state_id']);
        $post                  = $frm->getFormDataFromArray($post);
        //$post['user_state_id'] = $user_state_id;
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
            $srch    = $userObj->getUserSearchObj();
            $srch->addMultipleFields(array(
                'u.*'
            ));
            $rs = $srch->getResultSet();
            if (!$rs) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $data = FatApp::getDb()->fetch($rs, 'user_id');
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            /* if(isset($data['credential_username'])){
            $data['credential_username'] = htmlentities($data['credential_username']);
            } */
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
            $srch    = $userObj->getUserSearchObj();
            $srch->addMultipleFields(array(
                'u.*',
                'country_name',
                'state_name'
            ));
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
        $post     = FatApp::getPostedData();
        $page     = (empty($post['page']) || $post['page'] <= 0) ? 1 : $post['page'];
        $page     = (empty($page) || $page <= 0) ? 1 : FatUtility::int($page);
        $srch     = Transaction::getSearchObject();
        $srch->addCondition('utxn.utxn_user_id', '=', $userId);
        $balSrch = Transaction::getSearchObject();
        $balSrch->doNotCalculateRecords();
        $balSrch->doNotLimitRecords();
        $balSrch->addMultipleFields(array(
            'utxn.*',
            "utxn_credit - utxn_debit as bal"
        ));
        $balSrch->addCondition('utxn_user_id', '=', $userId);
        $balSrch->addCondition('utxn_status', '=', 1);
        $qryUserPointsBalance = $balSrch->getQuery();
        $srch->joinTable('(' . $qryUserPointsBalance . ')', 'JOIN', 'tqupb.utxn_id <= utxn.utxn_id', 'tqupb');
        $srch->addMultipleFields(array(
            'utxn.*',
            "SUM(tqupb.bal) balance"
        ));
        $srch->addOrder('utxn_id', 'DESC');
        $srch->addGroupBy('utxn.utxn_id');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs      = $srch->getResultSet();
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
        $frm->fill(array(
            'user_id' => $userId
        ));
        $this->set('userId', $userId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

	public function setupUserTransaction()
    {
        $this->objPrivilege->canEditUsers();
        $frm  = $this->addUserTransactionForm($this->adminLangId);
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
        $data = array(
            'utxn_user_id' => $userId,
            'utxn_date' => date('Y-m-d H:i:s'),
            'utxn_comments' => $post['description'],
            'utxn_status' => Transaction::STATUS_COMPLETED
        );
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
        $frm     = $this->getChangePasswordForm($user_id);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

	public function updatePassword()
    {
        $pwdFrm = $this->getChangePasswordForm();
        $post   = $pwdFrm->getFormDataFromArray(FatApp::getPostedData());
        if (!$pwdFrm->validate($post)) {
            Message::addErrorMessage($pwdFrm->getValidationErrors());
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ($post['new_password'] != $post['conf_new_password']) {
            Message::addErrorMessage(Label::getLabel('LBL_New_Password_and_Confirm_new_password_does_not_match', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ( true !== CommonHelper::validatePassword($post['new_password'])) {
            Message::addErrorMessage(Label::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $user_id = FatUtility::int($post['user_id']);
        if ($user_id < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userObj = new User($user_id);
        $srch    = $userObj->getUserSearchObj(array(
            'user_id'
        ));
        $rs      = $srch->getResultSet();
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

    public function viewTeacherRequest($requestId)
    {
        $requestId = FatUtility::int($requestId);
        if (1 > $requestId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }
        $userObj = new User();
        $srch    = $userObj->getTeacherRequestsObj($requestId);
        $srch->addFld('tusr.*');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        if (!$rs) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $teacherRequest = FatApp::getDb()->fetch($rs);
        if ($teacherRequest == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $teacherRequest["field_values"] = $userObj->getTeacherRequestFieldsValueArr($requestId, $this->adminLangId);
        $this->set('reqStatusArr', User::getTeacherReqStatusArr($this->adminLangId));
        $this->set('teacherRequest', $teacherRequest);
        $this->_template->render(false, false);
    }

    public function autoCompleteJson()
    {
        $pagesize        = 20;
        $post            = FatApp::getPostedData();
        $skipDeletedUser = true;
        if (isset($post['deletedUser']) && $post['deletedUser'] == true) {
            $skipDeletedUser = false;
        }
        $userObj = new User();
        $srch    = $userObj->getUserSearchObj(array(
            'u.user_first_name',
            'u.user_last_name',
            'u.user_id',
            'credential_username',
            'credential_email'
        ), false, $skipDeletedUser);
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
        $rs    = $srch->getResultSet();
        $db    = FatApp::getDb();
        $users = $db->fetchAll($rs, 'user_id');
        $json  = array();
        foreach ($users as $key => $user) {
			$user_full_name = strip_tags(html_entity_decode($user['user_first_name'], ENT_QUOTES, 'UTF-8')).' '.strip_tags(html_entity_decode($user['user_last_name'], ENT_QUOTES, 'UTF-8'));
            $json[] = array(
                'id' => $key,
                'name' => $user_full_name,
                'username' => strip_tags(html_entity_decode($user['credential_username'], ENT_QUOTES, 'UTF-8')),
                'credential_email' => strip_tags(html_entity_decode($user['credential_email'], ENT_QUOTES, 'UTF-8'))
            );
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
        $srch    = $userObj->getUserSearchObj();
        $rs      = $srch->getResultSet();
        $data    = FatApp::getDb()->fetch($rs);
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
        $userId  = FatApp::getPostedData('userId', FatUtility::VAR_INT);
        $v       = FatApp::getPostedData('v', FatUtility::VAR_INT);
        $userObj = new User($userId);
        if (!$userObj->activateAccount($v)) {
            Message::addErrorMessage($userObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', ((1 == $v) ? Label::getLabel('MSG_Account_Deactivated', $this->adminLangId) : Label::getLabel('MSG_Account_Activated', $this->adminLangId)));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getForm($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        $frm     = new Form('frmUser', array(
            'id' => 'frmUser'
        ));
        $frm->addHiddenField('', 'user_id', $user_id);
        $frm->addTextBox(Label::getLabel('LBL_Username', $this->adminLangId), 'credential_username', '');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name', $this->adminLangId), 'user_first_name');
        $fld->requirements()->setCharOnly();        
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Last_Name', $this->adminLangId), 'user_last_name');
        $fld->requirements()->setCharOnly();                
       /*$frm->addDateField(Label::getLabel('LBL_Date_Of_Birth', $this->adminLangId), 'user_dob', '', array(
            'readonly' => 'readonly'
        ));*/
		$fldPhn = $frm->addTextBox(Label::getLabel('LBL_Phone'), 'user_phone');
        $fldPhn->requirements()->setRegularExpressionToValidate(applicationConstants::PHONE_NO_REGEX);
        $frm->addTextBox(Label::getLabel('LBL_Email', $this->adminLangId), 'credential_email', '');
        $countryObj   = new Country();
        $countriesArr = $countryObj->getCountriesArr($this->adminLangId);
        $fld          = $frm->addSelectBox(Label::getLabel('LBL_Country', $this->adminLangId), 'user_country_id', $countriesArr, FatApp::getConfig('CONF_COUNTRY', FatUtility::VAR_INT, 223));
        $fld->requirement->setRequired(true);
        //$frm->addSelectBox(Label::getLabel('LBL_State', $this->adminLangId), 'user_state_id', array());
        //$frm->addTextBox(Label::getLabel('LBL_City', $this->adminLangId), 'user_city');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    private function getChangePasswordForm($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        $frm     = new Form('changePwdFrm');
        $frm->addHiddenField('', 'user_id', $user_id);
        $newPwd = $frm->addPasswordField(Label::getLabel('LBL_New_Password', $this->adminLangId), 'new_password', '', array(
            'id' => 'new_password'
        ));
        $newPwd->requirements()->setRequired();
        $conNewPwd    = $frm->addPasswordField(Label::getLabel('LBL_Confirm_New_Password', $this->adminLangId), 'conf_new_password', '', array(
            'id' => 'conf_new_password'
        ));
        $conNewPwdReq = $conNewPwd->requirements();
        $conNewPwdReq->setRequired();
        $conNewPwdReq->setCompareWith('new_password', 'eq');
        $conNewPwdReq->setCustomErrorMessage(Label::getLabel('LBL_Confirm_Password_Not_Matched!', $this->adminLangId));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId), array(
            'id' => 'btn_submit'
        ));
        return $frm;
    }

    private function addUserTransactionForm($langId)
    {
        $frm = new Form('frmUserTransaction');
        $frm->addHiddenField('', 'user_id');
        $typeArr = Transaction::getCreditDebitTypeArr($langId);
        $frm->addSelectBox(Label::getLabel('LBL_Type', $this->adminLangId), 'type', $typeArr)->requirements()->setRequired(true);
        $frm->addRequiredField(Label::getLabel('LBL_Amount', $this->adminLangId), 'amount')->requirements()->setFloatPositive();
        $frm->addTextArea(Label::getLabel('LBL_Description', $this->adminLangId), 'description')->requirements()->setRequired();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
