<?php

class UserAuthentication extends FatModel
{

    const SESSION_ELEMENT_NAME = 'yoCoachUserSession';
    const SESSION_GUEST_USER_ELEMENT_NAME = 'yoCoachGuestUserSession';
    const YOCOACHUSER_COOKIE_NAME = '_uyocoach';
    const DB_TBL_USER_PRR = 'tbl_user_password_reset_requests';
    const DB_TBL_UPR_PREFIX = 'uprr_';
    const DB_TBL_USER_AUTH = 'tbl_user_auth_token';
    const DB_TBL_UAUTH_PREFIX = 'uauth_';
    const TOKEN_LENGTH = 32;

    private $commonLangId;

    public function __construct()
    {
        $this->commonLangId = CommonHelper::getLangId();
    }

    public static function encryptPassword($pass)
    {
        return md5(PASSWORD_SALT . $pass . PASSWORD_SALT);
    }

    public function logFailedAttempt($ip, $username)
    {
        $db = FatApp::getDb();
        $db->deleteRecords('tbl_failed_login_attempts', ['smt' => 'attempt_time < ?', 'vals' => [date('Y-m-d H:i:s', strtotime("-7 Day"))]]);
        $db->insertFromArray('tbl_failed_login_attempts', ['attempt_username' => $username, 'attempt_ip' => $ip, 'attempt_time' => date('Y-m-d H:i:s')]);
        // For improvement, we can send an email about the failed attempt here.
    }

    public function clearFailedAttempt($ip, $username)
    {
        $db = FatApp::getDb();
        return $db->deleteRecords('tbl_failed_login_attempts', ['smt' => 'attempt_username = ? and attempt_ip = ?', 'vals' => [$username, $ip]]);
    }

    public function isBruteForceAttempt($ip, $username)
    {
        $db = FatApp::getDb();
        $srch = new SearchBase('tbl_failed_login_attempts');
        $srch->addCondition('attempt_ip', '=', $ip)->attachCondition('attempt_username', '=', $username);
        $srch->addCondition('attempt_time', '>=', date('Y-m-d H:i:s', strtotime("-5 minutes")));
        $srch->addFld('COUNT(*) AS total');
        $row = $db->fetch($srch->getResultSet());
        return ($row['total'] > 3);
    }

    public static function doCookieLogin($returnAuthRow = true)
    {
        $cookieName = self::YOCOACHUSER_COOKIE_NAME;
        if (!array_key_exists($cookieName, $_COOKIE)) {
            return false;
        }
        $token = $_COOKIE[$cookieName];
        $authRow = false;
        $authRow = self::checkLoginTokenInDB($token);
        if (strlen($token) != self::TOKEN_LENGTH || empty($authRow)) {
            self::clearLoggedUserLoginCookie();
            return false;
        }
        $browser = CommonHelper::userAgent();
        if (strtotime($authRow['uauth_expiry']) < strtotime('now') || $authRow['uauth_browser'] != $browser || CommonHelper::userIp() != $authRow['uauth_last_ip']) {
            self::clearLoggedUserLoginCookie();
            return false;
        }
        $ths = new UserAuthentication();
        if ($ths->loginByCookie($authRow)) {
            if (true === $returnAuthRow) {
                return $authRow;
            }
            return true;
        }
        return false;
    }

    public function login($username, $password, $ip, $encryptPassword = true, $isAdmin = false)
    {
        $db = FatApp::getDb();
        if ($this->isBruteForceAttempt($ip, $username)) {
            $srch = new UserSearch();
            $srch->joinCredentials(true, true);
            $cnd = $srch->addCondition('credential_email', '=', $username);
            $cnd = $srch->addCondition('credential_username', '=', $username);
            $srch->addMultipleFields(['user_first_name', 'user_last_name', 'credential_email']);
            if ($row = FatApp::getDb()->fetch($srch->getResultSet())) {
                $emailHandler = new EmailHandler();
                $emailHandler->failedLoginAttempt(CommonHelper::getLangId(), $row);
            }
            $this->error = Label::getLabel('ERR_LOGIN_ATTEMPT_LIMIT_EXCEEDED_PLEASE_TRY_LATER', $this->commonLangId);
            return false;
        }
        if ($encryptPassword) {
            $password = UserAuthentication::encryptPassword($password);
        }
        $srch = User::getSearchObject(true, false);
        $condition = $srch->addCondition('credential_username', '=', $username);
        $condition->attachCondition('credential_email', '=', $username, 'OR');
        $srch->addCondition('credential_password', '=', $password);
        $rs = $srch->getResultSet();
        if (!$row = $db->fetch($rs)) {
            $this->logFailedAttempt($ip, $username);
            $this->error = Label::getLabel('ERR_INVALID_USERNAME_OR_PASSWORD', $this->commonLangId);
            return false;
        }
        if ($row && $row['user_deleted'] == applicationConstants::YES) {
            $this->logFailedAttempt($ip, $username);
            $this->error = Label::getLabel('ERR_USER_INACTIVE_OR_DELETED', $this->commonLangId);
            return false;
        }
        if ((!(strtolower($row['credential_username']) === strtolower($username) || strtolower($row['credential_email']) === strtolower($username))) || $row['credential_password'] !== $password) {
            $this->logFailedAttempt($ip, $username);
            $this->error = Label::getLabel('ERR_INVALID_USERNAME_OR_PASSWORD', $this->commonLangId);
            return false;
        }
        if ($row['credential_verified'] != applicationConstants::YES) {
            $this->error = Label::getLabel('ERR_Account_verification_is_pending', $this->commonLangId);
            if (!$isAdmin) {
                $this->error = str_replace("{clickhere}", '<a href="javascript:void(0)" onclick="resendEmailVerificationLink(' . "'" . $username . "'" . ')">' . Label::getLabel('LBL_Click_Here', $this->commonLangId) . '</a>', Label::getLabel('MSG_Your_Account_verification_is_pending_{clickhere}', $this->commonLangId));
            }
            return false;
        }
        if ($row['credential_active'] != applicationConstants::ACTIVE) {
            $this->error = Label::getLabel('ERR_ACCOUNT_HAS_BEEN_DEACTIVATED_OR_NOT_ACTIVE', $this->commonLangId);
            return false;
        }
        $rowUser = User::getAttributesById($row['credential_user_id']);
        $rowUser['user_ip'] = $ip;
        $rowUser['user_email'] = $row['credential_email'];
        $userCookieeConsent = new UserCookieConsent($row['credential_user_id']);
        $userCookieSettings = $userCookieeConsent->getCookieSettings();
        if (empty($userCookieSettings) && !empty($_COOKIE[UserCookieConsent::COOKIE_NAME])) {
            $cookieSettings = json_decode($_COOKIE[UserCookieConsent::COOKIE_NAME], true);
            $userCookieeConsent->saveOrUpdateSetting($cookieSettings);
        }
        if (!empty($userCookieSettings)) {
            CommonHelper::setCookieConsent($userCookieSettings);
        }
        $this->setSession($rowUser);
        unset($_SESSION[UserAuthentication::SESSION_GUEST_USER_ELEMENT_NAME]);
        /* clear failed login attempt for the user [ */
        $this->clearFailedAttempt($ip, $username);
        /* ] */
        return true;
    }

    private function setSession($data)
    {
        $_SESSION[UserAuthentication::SESSION_ELEMENT_NAME] = [
            'user_id' => $data['user_id'],
            'user_first_name' => $data['user_first_name'],
            'user_last_name' => $data['user_last_name'],
            'user_ip' => $data['user_ip'],
            'user_email' => $data['user_email'],
        ];
        return true;
    }

    private function loginByCookie($authRow)
    {
        $userObj = new User($authRow['uauth_user_id']);
        if ($row = $userObj->getProfileData()) {
            if ($row['credential_verified'] != applicationConstants::YES) {
                return false;
            }
            if ($row['credential_active'] != applicationConstants::YES) {
                return false;
            }
            $row['user_ip'] = CommonHelper::userIp();
            $this->setSession($row);
            return true;
        }
        return false;
    }

    public static function saveLoginToken(&$values)
    {
        $db = FatApp::getDb();
        if ($db->insertFromArray(static::DB_TBL_USER_AUTH, $values)) {
            return true;
        }
        return false;
    }

    public static function checkLoginTokenInDB($token)
    {
        $db = FatApp::getDb();
        $srch = new SearchBase(static::DB_TBL_USER_AUTH);
        $srch->addCondition(static::DB_TBL_UAUTH_PREFIX . 'token', '=', $token);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        return $db->fetch($rs);
    }

    public static function logout()
    {
        unset($_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]);
        UserAuthentication::clearLoggedUserLoginCookie();
    }

    public static function clearLoggedUserLoginCookie()
    {
        if (!isset($_COOKIE[static::YOCOACHUSER_COOKIE_NAME])) {
            return false;
        }
        $db = FatApp::getDb();
        if (strlen($_COOKIE[static::YOCOACHUSER_COOKIE_NAME])) {
            $db->deleteRecords(static::DB_TBL_USER_AUTH, [
                'smt' => static::DB_TBL_UAUTH_PREFIX . 'token = ?',
                'vals' => [$_COOKIE[static::YOCOACHUSER_COOKIE_NAME]]
            ]);
        }
        CommonHelper::setCookie($_COOKIE[static::YOCOACHUSER_COOKIE_NAME], '', time() - 3600, CONF_WEBROOT_FRONTEND, '', true);
        return true;
    }

    public static function isUserLogged($ip = '', $token = '')
    {
        if ($ip == '') {
            $ip = CommonHelper::getClientIp();
        }
        if (isset($_SESSION [static::SESSION_ELEMENT_NAME]) && is_numeric($_SESSION [static::SESSION_ELEMENT_NAME] ['user_id']) && 0 < $_SESSION [static::SESSION_ELEMENT_NAME] ['user_id']) {
            $userObj = new User($_SESSION [static::SESSION_ELEMENT_NAME] ['user_id']);
            $srch = $userObj->getUserSearchObj(['credential_email']);
            $userRow = FatApp::getDb()->fetch($srch->getResultSet());
            $credential_email = $userRow['credential_email'];
            return ($_SESSION [static::SESSION_ELEMENT_NAME]['user_email'] == $credential_email) ? true : false;
        }
        if ($token != '' && static::doAppLogin($token)) {
            return true;
        }
        if (static::doCookieLogin(false)) {
            return true;
        }
        return false;
    }

    public static function getLoggedUserAttribute($attr, $returnNullIfNotLogged = false)
    {
        if (!static::isUserLogged()) {
            if ($returnNullIfNotLogged) {
                return null;
            }
            Message::addErrorMessage(Label::getLabel('MSG_USER_NOT_LOGGED', CommonHelper::getLangId()));
            FatUtility::dieWithError(Message::getHtml());
        }
        if (array_key_exists($attr, $_SESSION [static::SESSION_ELEMENT_NAME])) {
            return $_SESSION [static::SESSION_ELEMENT_NAME][$attr];
        }
        return User::getAttributesById($_SESSION[static::SESSION_ELEMENT_NAME]['user_id'], $attr);
    }

    public static function getLoggedUserId($returnZeroIfNotLogged = false)
    {
        return FatUtility::int(static::getLoggedUserAttribute('user_id', $returnZeroIfNotLogged));
    }

    public static function getGuestTeacherUserId()
    {
        return ($_SESSION[static::SESSION_GUEST_USER_ELEMENT_NAME]) ? FatUtility::int($_SESSION[static::SESSION_GUEST_USER_ELEMENT_NAME]):0;
    }

    public function getUserByEmail($email, $isActive = true, $isVerfied = true)
    {
        $db = FatApp::getDb();
        $srch = new SearchBase(User::DB_TBL);
        $srch->joinTable(User::DB_TBL_CRED, 'INNER JOIN', User::tblFld('id') . '=' . User::DB_TBL_CRED_PREFIX . 'user_id');
        $srch->addCondition(User::DB_TBL_CRED_PREFIX . 'email', '=', $email);
        if (true === $isActive) {
            $srch->addCondition(User::DB_TBL_CRED_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        } else {
            $srch->addFld(User::DB_TBL_CRED_PREFIX . 'active');
        }
        if (true === $isVerfied) {
            $srch->addCondition(User::DB_TBL_CRED_PREFIX . 'verified', '=', applicationConstants::YES);
        } else {
            $srch->addFld(User::DB_TBL_CRED_PREFIX . 'verified');
        }
        $srch->addMultipleFields([
            User::tblFld('id'),
            User::tblFld('name'),
            User::DB_TBL_CRED_PREFIX . 'email',
            User::DB_TBL_CRED_PREFIX . 'password'
        ]);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        if (!$row = $db->fetch($srch->getResultSet(), User::tblFld('id'))) {
            $this->error = Label::getLabel('ERR_INVALID_EMAIL_ADDRESS', $this->commonLangId);
            return false;
        }
        return $row;
    }

    public function getUserByEmailOrUserName($user, $isActive = true, $isVerfied = true, $addDeletedCheck = true)
    {
        $db = FatApp::getDb();
        $srch = new SearchBase(User::DB_TBL);
        $srch->joinTable(User::DB_TBL_CRED, 'INNER JOIN', User::tblFld('id') . '=' . User::DB_TBL_CRED_PREFIX . 'user_id');
        $cnd = $srch->addCondition('credential_username', '=', $user);
        $cnd->attachCondition('credential_email', '=', $user, 'OR');
        if (true === $isActive) {
            $srch->addCondition('credential_active', '=', applicationConstants::ACTIVE);
        } else {
            $srch->addFld('credential_active');
        }
        if (true === $isVerfied) {
            $srch->addCondition('credential_verified', '=', applicationConstants::YES);
        } else {
            $srch->addFld('credential_verified');
        }
        if (true === $addDeletedCheck) {
            $srch->addCondition('user_deleted', '=', applicationConstants::NO);
        }
        $srch->addMultipleFields([
            'user_id',
            'user_first_name',
            'user_last_name',
            'user_deleted',
            'credential_email',
            'credential_password'
        ]);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        if (!$row = $db->fetch($srch->getResultSet(), 'user_id')) {
            $this->error = Label::getLabel('ERR_INVALID_CREDENTIAL', $this->commonLangId);
            return false;
        }
        return $row;
    }

    public function checkUserPwdResetRequest($userId)
    {
        $db = FatApp::getDb();
        $srch = new SearchBase(static::DB_TBL_USER_PRR);
        $srch->addCondition(static::DB_TBL_UPR_PREFIX . 'user_id', '=', $userId);
        $srch->addCondition(static::DB_TBL_UPR_PREFIX . 'expiry', '>', date('Y-m-d H:i:s'));
        $srch->addFld(static::DB_TBL_UPR_PREFIX . 'user_id');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        if (!$row = $db->fetch($rs)) {
            return false;
        }
        $this->error = Label::getLabel('ERR_RESET_PASSWORD_REQUEST_ALREADY_PLACED', $this->commonLangId);
        return true;
    }

    public function deleteOldPasswordResetRequest()
    {
        $db = FatApp::getDb();
        if (!$db->deleteRecords(static::DB_TBL_USER_PRR, ['smt' => static::DB_TBL_UPR_PREFIX . 'expiry < ?', 'vals' => [date('Y-m-d H:i:s')]])) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }

    public function addPasswordResetRequest($data = [])
    {
        if (!isset($data['user_id']) || $data['user_id'] < 1 || strlen($data['token']) < 20) {
            return false;
        }
        $db = FatApp::getDb();
        if ($db->insertFromArray(
                        static::DB_TBL_USER_PRR,
                        [
                            static::DB_TBL_UPR_PREFIX . 'user_id' => intval($data['user_id']),
                            static::DB_TBL_UPR_PREFIX . 'token' => $data['token'],
                            static::DB_TBL_UPR_PREFIX . 'expiry' => date('Y-m-d H:i:s', strtotime("+1 DAY"))
                        ]
                )) {
            $db->deleteRecords(static::DB_TBL_USER_AUTH, [
                'smt' => static::DB_TBL_UAUTH_PREFIX . 'user_id = ?',
                'vals' => [$data['user_id']]
            ]);
            return true;
        }
        return false;
    }

    public function checkResetLink($uId, $token)
    {
        $uId = FatUtility::convertToType($uId, FatUtility::VAR_INT);
        $token = FatUtility::convertToType($token, FatUtility::VAR_STRING);
        if (intval($uId) < 1 || strlen($token) < 20) {
            $this->error = Label::getLabel('ERR_INVALID_RESET_PASSWORD_REQUEST', $this->commonLangId);
            return false;
        }
        $db = FatApp::getDb();
        $srch = new SearchBase(static::DB_TBL_USER_PRR);
        $srch->addCondition(static::DB_TBL_UPR_PREFIX . 'user_id', '=', $uId);
        $srch->addCondition(static::DB_TBL_UPR_PREFIX . 'token', '=', $token);
        $srch->addCondition(static::DB_TBL_UPR_PREFIX . 'expiry', '>', date('Y-m-d H:i:s'));
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        if (!$row = $db->fetch($rs)) {
            $this->error = Label::getLabel('ERR_LINK_IS_INVALID_OR_EXPIRED', $this->commonLangId);
            return false;
        }
        if ($row[static::DB_TBL_UPR_PREFIX . 'user_id'] == $uId && $row[static::DB_TBL_UPR_PREFIX . 'token'] === $token) {
            return true;
        }
        $this->error = Label::getLabel('ERR_LINK_IS_INVALID_OR_EXPIRED', $this->commonLangId);
        return false;
    }

    public function resetUserPassword($userId, $pwd)
    {
        $userId = FatUtility::convertToType($userId, FatUtility::VAR_INT);
        if ($userId < 1) {
            $this->error = Label::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }
        if (!empty($pwd)) {
            $user = new User($userId);
            if (!$user->resetPassword($pwd)) {
                $this->error = $user->getError();
                return false;
            }
            FatApp::getDb()->deleteRecords(static::DB_TBL_USER_PRR, ['smt' => static::DB_TBL_UPR_PREFIX . 'user_id =?', 'vals' => [$userId]]);
            return true;
        }
        $this->error = Label::getLabel('ERR_INVALID_PASSWORD', $this->commonLangId);
        return false;
    }

    public static function checkLogin($redirect = true)
    {
        if (!static::isUserLogged()) {
            if (FatUtility::isAjaxCall()) {
                Message::addErrorMessage(Label::getLabel('MSG_Session_seems_to_be_expired', CommonHelper::getLangId()));
                FatUtility::dieWithError(Message::getHtml());
            }
            $_SESSION['referer_page_url'] = CommonHelper::getCurrUrl();
            if ($redirect == true) {
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm', [], CONF_WEBROOT_FRONT_URL));
            } else {
                return false;
            }
        }
        return true;
    }

    public function updateSessionData($post = [])
    {
        if ($post) {
            $_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]['user_first_name'] = $post['user_first_name'];
            $_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]['user_last_name'] = $post['user_last_name'];
        }
    }

}
