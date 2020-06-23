<?php
class GuestUserController extends MyAppController
{
    public function loginForm()
    {
        if (UserAuthentication::isUserLogged()) {
            FatApp::redirectUser(User::getPreferedDashbordRedirectUrl());
        }
        $frm = $this->getLoginForm();
        $this->set('frm', $frm);
		$this->set('userType', User::USER_TYPE_LEANER);
        $this->_template->render();
    }
    public function logInFormPopUp()
    {
        if (UserAuthentication::isUserLogged()) {
            Message::addErrorMessage(Label::getLabel('MSG_Already_Logged_in,_Please_try_after_reloading_the_page'));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getLoginForm();
        $frm->setFormTagAttribute('name', 'frmLoginPopUp');
        $frm->setFormTagAttribute('id', 'frmLoginPopUp');
        $this->set('frm', $frm);
        $this->set('userType', User::USER_TYPE_LEANER);
        $this->_template->render(false, false);
    }
    public function setUpLogin()
    {
        $authentication = new UserAuthentication();
        if (true !== $authentication->login(FatApp::getPostedData('username'), FatApp::getPostedData('password'), $_SERVER['REMOTE_ADDR'])) {
            FatUtility::dieWithError(Label::getLabel($authentication->getError()));
        }
        $rememberme = FatApp::getPostedData('remember_me', FatUtility::VAR_INT, 0);
        if ($rememberme == 1) {
            if (true !== $this->setUserLoginCookie()) {
                //Message::addErrorMessage(Label::getLabel('MSG_Problem_in_configuring_remember_me'));
            }
        }
        $userId = UserAuthentication::getLoggedUserId();
        setcookie('uc_id', $userId, time() + 3600 * 24 * 30, CONF_WEBROOT_URL);
        $this->set('redirectUrl', User::getPreferedDashbordRedirectUrl());
        $this->set('msg', Label::getLabel("MSG_LOGIN_SUCCESSFULL"));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function registrationForm()
    {
        $frm = $this->getSignUpForm();
        $this->set('frm', $frm);
        /* [ */
        $cPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_TERMS_AND_CONDITIONS_PAGE', FatUtility::VAR_INT, 0));
        $cpage = FatApp::getDb()->fetch($cPageSrch->getResultSet());
        if (!empty($cpage) && is_array($cpage)) {
            $termsAndConditionsLinkHref = CommonHelper::generateUrl('Cms', 'view', array($cpage['cpage_id']));
        } else {
            $termsAndConditionsLinkHref = 'javascript:void(0)';
        }
        $this->set('termsAndConditionsLinkHref', $termsAndConditionsLinkHref);
        /* ] */
        /* [ */

        $cPPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_PRIVACY_POLICY_PAGE', FatUtility::VAR_INT, 0));
        $cpppage = FatApp::getDb()->fetch($cPPageSrch->getResultSet());
        if (!empty($cpppage) && is_array($cpppage)) {
            $privacyPolicyLinkHref = CommonHelper::generateUrl('Cms', 'view', array($cpppage['cpage_id']));
        } else {
            $privacyPolicyLinkHref = 'javascript:void(0)';
        }
        $this->set('privacyPolicyLinkHref', $privacyPolicyLinkHref);
        /* ] */
        $this->_template->render(true, true, 'guest-user/registration-form.php');
    }

    public function signUpFormPopUp()
    {
        $json = array();
        $json['status'] = true;
        $json['msg'] = '';
        $post = FatApp::getPostedData();
		$userType = User::USER_TYPE_LEANER;

        if (UserAuthentication::isUserLogged()) {
            if ($post['signUpType'] == "teacher") {
                $user_preferred_dashboard = User::USER_TEACHER_DASHBOARD;
            } else {
                $userRow = User::getAttributesById(UserAuthentication::getLoggedUserId(), array('user_preferred_dashboard'));
                $user_preferred_dashboard = $userRow['user_preferred_dashboard'];
            }
            $json['redirectUrl'] = User::getPreferedDashbordRedirectUrl($user_preferred_dashboard, false);
            FatUtility::dieJsonSuccess($json);
        }
        $user_preferred_dashboard = User::USER_LEARNER_DASHBOARD;
        if ($post['signUpType'] == "teacher") {
            $user_preferred_dashboard = User::USER_TEACHER_DASHBOARD;
			$userType = User::USER_TYPE_TEACHER;
        }
        $frm = $this->getSignUpForm();
        $frm->setFormTagAttribute('name', 'frmRegisterPopUp');
        $frm->setFormTagAttribute('id', 'frmRegisterPopUp');
        $frm->fill(array('user_preferred_dashboard' => $user_preferred_dashboard));
        $this->set('frm', $frm);
        /* [ */
        $cPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_TERMS_AND_CONDITIONS_PAGE', FatUtility::VAR_INT, 0));
        $cpage = FatApp::getDb()->fetch($cPageSrch->getResultSet());
        if (!empty($cpage) && is_array($cpage)) {
            $termsAndConditionsLinkHref = CommonHelper::generateUrl('Cms', 'view', array($cpage['cpage_id']));
        } else {
            $termsAndConditionsLinkHref = 'javascript:void(0)';
        }
        $this->set('termsAndConditionsLinkHref', $termsAndConditionsLinkHref);
        /* ] */
        /*[*/
        $cPPageSrch = ContentPage::getSearchObject($this->siteLangId);
        $cPPageSrch->addCondition('cpage_id', '=', FatApp::getConfig('CONF_PRIVACY_POLICY_PAGE', FatUtility::VAR_INT, 0));
        $cpppage = FatApp::getDb()->fetch($cPPageSrch->getResultSet());
        if (!empty($cpppage) && is_array($cpppage)) {
            $privacyPolicyLinkHref = CommonHelper::generateUrl('Cms', 'view', array($cpppage['cpage_id']));
        } else {
            $privacyPolicyLinkHref = 'javascript:void(0)';
        }
		$this->set('userType', $userType);
        $this->set('privacyPolicyLinkHref', $privacyPolicyLinkHref);
        /*]*/
        $json['html'] = $this->_template->render(false, false, 'guest-user/sign-up-form-pop-up.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }

    public function setUpSignUp()
    {
        $frm = $this->getSignUpForm();
        $post = FatApp::getPostedData();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if ($post == false) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError(Message::getHtml());
            }
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'registrationForm'));
        }
        if (true !== CommonHelper::validatePassword($post['user_password'])) {
            Message::addErrorMessage(Label::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC'));
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError(Message::getHtml());
            }
            $this->registrationForm();
            return;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $user = new User();
        /* saving user data[ */
        $post['user_is_learner'] = 1;
        $user_preferred_dashboard = User::USER_LEARNER_DASHBOARD;
        $user_registered_initially_for = User::USER_TYPE_LEANER;
        $posted_user_preferred_dashboard = FatApp::getPostedData('user_preferred_dashboard', FatUtility::VAR_INT, 0);
        if ($posted_user_preferred_dashboard == User::USER_TEACHER_DASHBOARD) {
            $user_preferred_dashboard = User::USER_TEACHER_DASHBOARD;
            $user_registered_initially_for = User::USER_TYPE_TEACHER;
        }
        $post['user_timezone'] = $_COOKIE['user_timezone'];
        $post['user_preferred_dashboard'] = $user_preferred_dashboard;
        $post['user_registered_initially_for'] = $user_registered_initially_for;
        $user->assignValues($post);
        if (true !== $user->save()) {
            $db->rollbackTransaction();
            Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET") . $user->getError());
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError(Message::getHtml());
            }
            $this->registrationForm();
            return;
        }
        $active = FatApp::getConfig('CONF_ADMIN_APPROVAL_REGISTRATION', FatUtility::VAR_INT, 1) ? 0 : 1;
        $verify = FatApp::getConfig('CONF_EMAIL_VERIFICATION_REGISTRATION', FatUtility::VAR_INT, 1) ? 0 : 1;

        if (true !== $user->setLoginCredentials($post['user_email'], $post['user_email'], $post['user_password'], $active, $verify)) {
            Message::addErrorMessage(Label::getLabel("MSG_LOGIN_CREDENTIALS_COULD_NOT_BE_SET") . $user->getError());
            $db->rollbackTransaction();
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieWithError(Message::getHtml());
            }
            $this->registrationForm();
            return;
        }
        /* ] */

        $db->commitTransaction();
        $redirectUrl = CommonHelper::redirectUserReferer(true);
        if ($user->getMainTableRecordId() and $user_registered_initially_for == User::USER_TYPE_TEACHER) {
            $_SESSION[UserAuthentication::SESSION_GUEST_USER_ELEMENT_NAME] = $user->getMainTableRecordId();
            $redirectUrl = CommonHelper::generateUrl('TeacherRequest');
        } else {
            unset($_SESSION[UserAuthentication::SESSION_GUEST_USER_ELEMENT_NAME]);
        }
        if (1 == FatApp::getConfig('CONF_NOTIFY_ADMIN_REGISTRATION', FatUtility::VAR_INT, 1)) {
            if (true !== $user->notifyAdminRegistration($post, $this->siteLangId)) {
                Message::addErrorMessage(Label::getLabel("MSG_NOTIFICATION_EMAIL_COULD_NOT_BE_SENT"));
                if (FatUtility::isAjaxCall()) {
                    FatUtility::dieWithError(Message::getHtml());
                }
                $this->registrationForm();
                return;
            }
        }
        if (1 == FatApp::getConfig('CONF_EMAIL_VERIFICATION_REGISTRATION', FatUtility::VAR_INT, 1)) {
            if (true !== $this->sendEmailVerificationLink($user, $post)) {
                Message::addErrorMessage(Label::getLabel("MSG_VERIFICATION_EMAIL_COULD_NOT_BE_SENT"));
                if (FatUtility::isAjaxCall()) {
                    FatUtility::dieWithError(Message::getHtml());
                }
                $this->registrationForm();
                return;
            }
            //$redirectUrl = CommonHelper::generateUrl('GuestUser', 'registrationSuccess');
            Message::addMessage(Label::getLabel('MSG_VERIFICATION_EMAIL_SENT'));
            $this->set('msg', Label::getLabel('MSG_VERIFICATION_EMAIL_SENT'));
            $this->set('redirectUrl', $redirectUrl);
            $this->_template->render(false, false, 'json-success.php');
        }
        if (1 == FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1)) {
            if (true !== $this->sendSignUpWelcomeEmail($user, $post)) {
                Message::addErrorMessage(Label::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT"));
                if (FatUtility::isAjaxCall()) {
                    FatUtility::dieWithError(Message::getHtml());
                }
                $this->registrationForm();
                return;
            }
        }

        $confAutoLoginRegisteration = FatApp::getConfig('CONF_AUTO_LOGIN_REGISTRATION', FatUtility::VAR_INT, 1);
        if (1 === $confAutoLoginRegisteration && (0 === FatApp::getConfig('CONF_ADMIN_APPROVAL_REGISTRATION', FatUtility::VAR_INT, 1))) {
            $authentication = new UserAuthentication();
            if (true != $authentication->login(FatApp::getPostedData('user_email'), FatApp::getPostedData('user_password'), $_SERVER['REMOTE_ADDR'])) {
                Message::addErrorMessage(Label::getLabel($authentication->getError()));
                if (FatUtility::isAjaxCall()) {
                    FatUtility::dieWithError(Message::getHtml());
                }
                $this->registrationForm();
                return;
            }
            Message::addMessage(Label::getLabel('LBL_Registeration_Successfull'));
            $redirectUrl = User::getPreferedDashbordRedirectUrl();
            $this->set('redirectUrl', $redirectUrl);
            $this->set('msg', Label::getLabel('LBL_Registeration_Successfull'));
            $this->_template->render(false, false, 'json-success.php');
        }
        //$redirectUrl = CommonHelper::generateUrl('GuestUser', 'registrationSuccess');
        Message::addMessage(Label::getLabel('LBL_Registeration_Successfull'));
        $this->set('msg', Label::getLabel('LBL_Registeration_Successfull'));
        $this->set('redirectUrl', $redirectUrl);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function registrationSuccess()
    {
        if (1 === FatApp::getConfig('CONF_EMAIL_VERIFICATION_REGISTRATION', FatUtility::VAR_INT, 1)) {
            $this->set('registrationMsg', Label::getLabel("MSG_SUCCESS_USER_SIGNUP_EMAIL_VERIFICATION_PENDING"));
        } else {
            $this->set('registrationMsg', Label::getLabel("MSG_SUCCESS_USER_SIGNUP_ADMIN_APPROVAL_PENDING"));
        }
        $this->_template->render();
    }

    public function userCheckEmailVerification($code)
    {
        $code = FatUtility::convertToType($code, FatUtility::VAR_STRING, '');
        if (strlen($code) < 1) {
            Message::addMessage(Label::getLabel("MSG_PLEASE_CHECK_YOUR_EMAIL_IN_ORDER_TO_VERIFY"));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $codeArr = explode('_', $code, 2);
        $userId = FatUtility::int($codeArr[0]);
        if ($userId < 1) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_CODE'));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $userObj = new User($userId);
        $userData = User::getAttributesById($userId, array(
            'user_id'
        ));
        if (!$userData || $userData['user_id'] != $userId) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_CODE'));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        /* if (!$userObj->verifyUserEmailVerificationCode($code)){
        $db->rollbackTransaction();
        Message::addErrorMessage(Label::getLabel("ERR_MSG_INVALID_VERIFICATION_REQUEST"));
        FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        } */
        $srch = new SearchBase('tbl_user_credentials');
        $srch->addCondition('credential_user_id', '=', $userId);
        $rs = $srch->getResultSet();
        $userCredentialRow = $db->fetch($rs);
        if (applicationConstants::ACTIVE !== $userCredentialRow['credential_active']) {
            $active = FatApp::getConfig('CONF_ADMIN_APPROVAL_REGISTRATION', FatUtility::VAR_INT, 1) ? 0 : 1;
            if (0 === $userObj->activateAccount($active)) {
                $db->rollbackTransaction();
                Message::addErrorMessage(Label::getLabel('MSG_INVALID_CODE'));
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
            }
        }
        if (true !== $userObj->verifyAccount()) {
            $db->rollbackTransaction();
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_CODE'));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $userdata = $userObj->getUserInfo(array(
            'credential_email',
            'credential_password',
            'user_first_name',
            'user_last_name',
            'credential_active'
        ), false);
        if (1 === FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1)) {
            $data['user_email'] = $userdata['credential_email'];
            $data['user_first_name'] = $userdata['user_first_name'];
            $data['user_last_name'] = $userdata['user_last_name'];
            if (true !== $this->sendSignUpWelcomeEmail($userObj, $data)) {
                Message::addErrorMessage(Label::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT"));
                $db->rollbackTransaction();
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
            }
        }
        $db->commitTransaction();
        /* if (1 === FatApp::getConfig('CONF_AUTO_LOGIN_REGISTRATION', FatUtility::VAR_INT, 1)) {
            $authentication = new UserAuthentication();
            if (true !== $authentication->login($userdata['credential_email'], $userdata['credential_password'], $_SERVER['REMOTE_ADDR'], false)) {
                $msg = Label::getLabel("MSG_EMAIL_VERIFIED_SUCCESFULLY");
                $msg .= " <br/>" .Label::getLabel( 'MSG_Tried_Autologin_in_system,_faced_below_error' ) . '<br/>'. Label::getLabel($authentication->getError());
                Message::addErrorMessage( $msg );
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
            }
            FatApp::redirectUser(CommonHelper::generateUrl('Account'));
        } */
        Message::addMessage(Label::getLabel("MSG_EMAIL_VERIFIED_SUCCESFULLY"));
        FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
    }

    public function logout()
    {
        // Delete googleplus session if exist
        /* if(isset($_SESSION['access_token'])){
        unset($_SESSION['access_token']);
        } */
        //Delete facebook session if exist
        /* require_once (CONF_INSTALLATION_PATH . 'library/facebook/facebook.php');
        $facebook = new Facebook(array(
        'appId' => FatApp::getConfig("CONF_FACEBOOK_APP_ID"),
        'secret' => FatApp::getConfig("CONF_FACEBOOK_APP_SECRET"),
        ));

        $user = $facebook->getUser();

        if ($user) {
        unset($_SESSION['fb_'.FatApp::getConfig("CONF_FACEBOOK_APP_ID").'_code']);
        unset($_SESSION['fb_'.FatApp::getConfig("CONF_FACEBOOK_APP_ID").'_access_token']);
        unset($_SESSION['fb_'.FatApp::getConfig("CONF_FACEBOOK_APP_ID").'_user_id']);
        } */
        unset($_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]);
        UserAuthentication::clearLoggedUserLoginCookie();
        FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
    }

    private function setUserLoginCookie()
    {
        $userId = UserAuthentication::getLoggedUserAttribute('user_id', true);
        if (null == $userId) {
            return false;
        }
        $token = $this->generateLoginToken();
        $expiry = strtotime("+7 DAYS");
        $values = array(
            'uauth_user_id' => $userId,
            'uauth_token' => $token,
            'uauth_expiry' => date('Y-m-d H:i:s', $expiry),
            'uauth_browser' => CommonHelper::userAgent(),
            'uauth_last_access' => date('Y-m-d H:i:s'),
            'uauth_last_ip' => CommonHelper::getClientIp()
        );
        if (UserAuthentication::saveLoginToken($values)) {
            $cookieName = UserAuthentication::WEYAKYAKUSER_COOKIE_NAME;
            $cookres = setcookie($cookieName, $token, $expiry, CONF_WEBROOT_URL);
            return true;
        }
        return false;
    }

    private function generateLoginToken()
    {
        return substr(md5(rand(1, 99999) . microtime()), 0, UserAuthentication::TOKEN_LENGTH);
    }

    private function getSignUpForm()
    {
        $frm = new Form('frmRegister');
        $frm->addHiddenField('', 'user_id', 0);
        $fld = $frm->addRequiredField(Label::getLabel('LBL_First_Name'), 'user_first_name');
        $fld->requirements()->setCharOnly();
        $fld =$frm->addRequiredField(Label::getLabel('LBL_Last_Name'), 'user_last_name');
        $fld->requirements()->setCharOnly();
        $fld = $frm->addEmailField(Label::getLabel('LBL_Email_ID'), 'user_email', '', array('autocomplete="off"'));
        $fld->setUnique('tbl_user_credentials', 'credential_email', 'credential_user_id', 'user_id', 'user_id');
        $fld = $frm->addPasswordField(Label::getLabel('LBL_Password'), 'user_password');
        $fld->requirements()->setRequired();
        $fld->setRequiredStarPosition(Form::FORM_REQUIRED_STAR_POSITION_NONE);
        $fld->requirements()->setRegularExpressionToValidate("^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%-_]{8,15}$");
        $fld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Please_Enter_8_Digit_AlphaNumeric_Password'));
        //$frm->addHtml('','note_links','<small>'.Label::getLabel('LBL_Eg:_user@123').'</small>');
        $termsConditionLabel = Label::getLabel('LBL_I_accept_to_the');
        $fld = $frm->addCheckBox($termsConditionLabel, 'agree', 1);
        $fld->requirements()->setRequired();
        $fld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Terms_and_Condition_and_Privacy_Policy_are_mandatory.'));
        $frm->addHiddenField('', 'user_preferred_dashboard', User::USER_LEARNER_DASHBOARD);
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Register'));
        return $frm;
    }

    private function getLoginForm()
    {
        $frm = new Form('frmLogin');
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Email'), 'username', '', array(
            'placeholder' => Label::getLabel('LBL_EMAIL_ADDRESS')
        ));
        $pwd = $frm->addPasswordField(Label::getLabel('LBL_Password'), 'password', '', array(
            'placeholder' => Label::getLabel('LBL_PASSWORD')
        ));
        $pwd->requirements()->setRequired();
        $frm->addCheckbox(Label::getLabel('LBL_Remember_Me'), 'remember_me', 1, array(), '', 0);
        $frm->addHtml('', 'forgot', '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_LOGIN'));
        return $frm;
    }

    private function sendEmailVerificationLink($userObj, $data)
    {
        $verificationCode = $userObj->prepareUserVerificationCode();
        $link = CommonHelper::generateFullUrl('GuestUser', 'userCheckEmailVerification', array(
            'verify' => $verificationCode
        ));
        $data = array(
            'user_first_name' => $data['user_first_name'],
            'user_last_name' => $data['user_last_name'],
            'user_email' => $data['user_email'],
            'link' => $link,
        );
        $email	= new EmailHandler();
        if (true !== $email->sendEmailVerificationLink($this->siteLangId, $data)) {
            return false;
        }
        return true;
    }

    private function sendSignUpWelcomeEmail($userObj, $data)
    {
        $link = CommonHelper::generateFullUrl('GuestUser', 'loginForm');
        $data = array(
            'user_first_name' => $data['user_first_name'],
            'user_last_name' => $data['user_last_name'],
            'user_email' => $data['user_email'],
            'link' => $link
        );
        $email = new EmailHandler();
        if (true !== $email->sendWelcomeEmail($this->siteLangId, $data)) {
            Message::addMessage(Label::getLabel("MSG_ERROR_IN_SENDING_WELCOME_EMAIL"));
            return false;
        }
        return true;
    }

    public function socialMediaLogin($oauthProvider, $userType = User::USER_TYPE_LEANER)
    {
        if (isset($oauthProvider)) {
            if ($oauthProvider == 'googleplus') {
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginGoogleplus', array($userType)));
            } elseif ($oauthProvider == 'google') {
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginGoogle', array($userType)));
            } elseif ($oauthProvider == 'facebook') {
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginFacebook'));
            } else {
                Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_REQUEST'));
            }
        }
        CommonHelper::redirectUserReferer();
    }

    /*public function loginFacebook()
    {
        require_once(CONF_INSTALLATION_PATH . 'library/facebook/facebook.php');
        $facebook = new Facebook(array(
            'appId' => FatApp::getConfig("CONF_FACEBOOK_APP_ID", FatUtility::VAR_STRING, ''),
            'secret' => FatApp::getConfig("CONF_FACEBOOK_APP_SECRET", FatUtility::VAR_STRING, '')
        ));
        $user     = $facebook->getUser();
        if (!$user) {
            $loginUrl = $facebook->getLoginUrl(array(
                'scope' => 'email'
            ), CommonHelper::generateFullUrl('GuestUser', 'loginFacebook', array(), '', false));
            FatApp::redirectUser($loginUrl);
        }
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $userProfile = $facebook->api('/me?fields=id,name,email');
        }
        catch (FacebookApiException $e) {
            Message::addErrorMessage($e->getMessage());
            $user = null;
        }
        if (empty($userProfile)) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_REQUEST'));
            CommonHelper::redirectUserReferer();
        }

        # User info ok? Let's print it (Here we will be adding the login and registering routines)
        $facebookName   = $userProfile['name'];
        $userFacebookId = $userProfile['id'];
        $facebookEmail  = $userProfile['email'];
        $db             = FatApp::getDb();
        $userObj        = new User();
        $srch           = $userObj->getUserSearchObj(array(
            'user_id',
            'user_facebook_id',
            'credential_email',
            'credential_active',
            'user_deleted'
        ), false, false);

        if (empty($facebookEmail)) {
            Message::addErrorMessage(Label::getLabel("MSG_THERE_WAS_SOME_PROBLEM_IN_AUTHENTICATING_YOUR_ACCOUNT_WITH_FACEBOOK,_PLEASE_TRY_WITH_DIFFERENT_LOGIN_OPTIONS"));
            unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_code']);
            unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_access_token']);
            unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_user_id']);
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }

        $srch->addCondition('credential_email', '=', $facebookEmail);
        //$srch->addCondition('user_facebook_id','=',$userFacebookId);
        $rs  = $srch->getResultSet();
        $row = $db->fetch($rs);
        if ($row) {
            if ($row['credential_active'] != applicationConstants::ACTIVE) {
                Message::addErrorMessage(Label::getLabel("ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED"));
                CommonHelper::redirectUserReferer();
            }
            if ($row['user_deleted'] == applicationConstants::YES) {
                Message::addErrorMessage(Label::getLabel("ERR_USER_INACTIVE_OR_DELETED"));
                CommonHelper::redirectUserReferer();
            }
            $userObj->setMainTableRecordId($row['user_id']);
            $arr = array(
                'user_facebook_id' => $userFacebookId
            );
            if (!$userObj->setUserInfo($arr)) {
                Message::addErrorMessage(Label::getLabel($userObj->getError()));
                CommonHelper::redirectUserReferer();
            }
        } else {
            $userNameArr = explode(" ", $facebookName);
            $user_first_name = $userNameArr[0];
            $user_last_name = $userNameArr[1];
            $db->startTransaction();
            $userData	= array(
                'user_first_name'	=>	$user_first_name,
                'user_last_name'	=>	$user_last_name,
                'user_is_learner' => 1,
                'user_facebook_id' => $userFacebookId,
                'user_preferred_dashboard' => User::USER_LEARNER_DASHBOARD,
                'user_registered_initially_for' => User::USER_TYPE_LEANER,
            );
            $userObj->assignValues($userData);
            if (!$userObj->save()) {
                Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET") . $userObj->getError());
                $db->rollbackTransaction();
                CommonHelper::redirectUserReferer();
            }
            $username = str_replace(" ", "", $facebookName) . $userFacebookId;
            if (!$userObj->setLoginCredentials($username, $facebookEmail, uniqid(), 1, 1)) {
                Message::addErrorMessage(Label::getLabel("MSG_LOGIN_CREDENTIALS_COULD_NOT_BE_SET") . $userObj->getError());
                $db->rollbackTransaction();
                CommonHelper::redirectUserReferer();
            }
            $userData['user_username'] = $username;
            $userData['user_email']    = $facebookEmail;

            if (FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1) && $facebookEmail) {
                $data['user_email'] = $facebookEmail;
                $data['user_first_name']  = $user_first_name;
                $data['user_last_name']  = $user_last_name;

                $userId = $userObj->getMainTableRecordId();
                $userObj = new User($userId);
                if (!$this->userWelcomeEmailRegistration( $userObj, $data )) {
                    Message::addErrorMessage(Label::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT"));
                    $db->rollbackTransaction();
                    FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
                }
            }
            $db->commitTransaction();
        }

        $userInfo = $userObj->getUserInfo(array(
            'user_facebook_id',
            'credential_username',
            'credential_password'
        ));
        if (!$userInfo || ($userInfo && $userInfo['user_facebook_id'] != $userFacebookId)) {
            Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET"));
            CommonHelper::redirectUserReferer();
        }

        $authentication = new UserAuthentication();
        if (!$authentication->login($userInfo['credential_username'], $userInfo['credential_password'], $_SERVER['REMOTE_ADDR'], false)) {
            Message::addErrorMessage(Label::getLabel($authentication->getError()));
            FatUtility::dieWithError(Message::getHtml());
        }
        unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_code']);
        unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_access_token']);
        unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_user_id']);
        FatApp::redirectUser( User::getPreferedDashbordRedirectUrl() );
    }*/

    public function loginFacebook()
    {
        $post = FatApp::getPostedData();
        $facebookEmail = isset($post['email']) ? $post['email'] : '';
        $userFacebookId = $post['id'];
        $userFirstName = $post['first_name'];
        $userLastName = $post['last_name'];
        $user_type = $post['type'];
		$preferredDashboard = User::USER_LEARNER_DASHBOARD;
		if($user_type == User::USER_TYPE_TEACHER) {
			$preferredDashboard = User::USER_TEACHER_DASHBOARD;
			//$userIsTeacher = 1;
		}else {
            $user_type = User::USER_TYPE_LEANER;
        }


        $facebookName = $userFirstName.' '.$userLastName;
        // User info ok? Let's print it (Here we will be adding the login and registering routines)
        $db = FatApp::getDb();
        $userObj = new User();
        $srch = $userObj->getUserSearchObj(array('user_id', 'user_facebook_id', 'credential_email', 'credential_active', 'user_deleted'), false, false);
        if (!empty($facebookEmail)) {
            $srch->addCondition('credential_email', '=', $facebookEmail);
        // }
        // else {
        //     if (empty($userFacebookId)) {
        //         Message::addErrorMessage(Labels::getLabel("MSG_THERE_WAS_SOME_PROBLEM_IN_AUTHENTICATING_YOUR_ACCOUNT_WITH_FACEBOOK,_PLEASE_TRY_WITH_DIFFERENT_LOGIN_OPTIONS", $this->siteLangId));
        //         unset($_SESSION['fb_'.FatApp::getConfig("CONF_FACEBOOK_APP_ID").'_code']);
        //         unset($_SESSION['fb_'.FatApp::getConfig("CONF_FACEBOOK_APP_ID").'_access_token']);
        //         unset($_SESSION['fb_'.FatApp::getConfig("CONF_FACEBOOK_APP_ID").'_user_id']);
        //         $url = CommonHelper::generateUrl('GuestUser', 'loginForm');
        //         $this->set('url', $url);
        //         $this->set('msg', Labels::getLabel('MSG_Invalid_login', $this->siteLangId));
        //         $this->_template->render(false, false, 'json-success.php');
        //     }
        //     $srch->addCondition('user_facebook_id', '=', $userFacebookId);
        // }
        $rs = $srch->getResultSet();
        $row = $db->fetch($rs);
        if ($row) {
            // print_r(var_dump($row));

            if ($row['credential_active'] != applicationConstants::ACTIVE) {

                // Message::addErrorMessage(Label::getLabel("ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED"));
                $this->set('url', CommonHelper::redirectUserReferer(true));
                $this->set('msg', Label::getLabel("ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED"));
                $this->_template->render(false, false, 'json-error.php');
            }
            if ($row['user_deleted'] == applicationConstants::YES) {
                $this->set('url', CommonHelper::redirectUserReferer(true));
                $this->set('msg', Label::getLabel("ERR_USER_INACTIVE_OR_DELETED"));
                $this->_template->render(false, false, 'json-error.php');
            }

            $userObj->setMainTableRecordId($row['user_id']);
            $arr = array(
                'user_facebook_id' => $userFacebookId
            );
            if (!$userObj->setUserInfo($arr)) {
                Message::addErrorMessage(Label::getLabel($userObj->getError()));
                $this->set('url', CommonHelper::redirectUserReferer(true));
                $this->set('msg', Message::getHtml());
                $this->_template->render(false, false, 'json-error.php');
            }
        } else {
            $userNameArr = explode(" ", $facebookName);
            $user_first_name = $userNameArr[0];
            $user_last_name = $userNameArr[1];
            $db->startTransaction();
            $userData = array(
                'user_first_name' => $user_first_name,
                'user_last_name' => $user_last_name,
                'user_is_learner' => 1,
                //'user_is_teacher' => $userIsTeacher,
                'user_facebook_id' => $userFacebookId,
                'user_preferred_dashboard' => $preferredDashboard,
                'user_registered_initially_for' => $user_type,
            );
            $userObj->assignValues($userData);
            if (!$userObj->save()) {
                $db->rollbackTransaction();
                // Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET") . $userObj->getError());
                $this->set('url', CommonHelper::redirectUserReferer(true));
                $this->set('msg', Label::getLabel("MSG_USER_COULD_NOT_BE_SET") . $userObj->getError());
                $this->_template->render(false, false, 'json-error.php');
            }
            $username = str_replace(" ", "", $facebookName) . $userFacebookId;
            if (!$userObj->setLoginCredentials($username, $facebookEmail, uniqid(), 1, 1)) {
                $db->rollbackTransaction();
                $this->set('url', CommonHelper::redirectUserReferer(true));
                $this->set('msg', Label::getLabel("MSG_LOGIN_CREDENTIALS_COULD_NOT_BE_SET") . $userObj->getError());
                $this->_template->render(false, false, 'json-error.php');
            }
            $userData['user_username'] = $username;
            $userData['user_email'] = $facebookEmail;

            if (FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1) && $facebookEmail) {
                $data['user_email'] = $facebookEmail;
                $data['user_first_name'] = $user_first_name;
                $data['user_last_name'] = $user_last_name;
                $userId = $userObj->getMainTableRecordId();
                $userObj = new User($userId);
                if (!$this->userWelcomeEmailRegistration($userObj, $data)) {
                    $db->rollbackTransaction();
                    $this->set('url', CommonHelper::redirectUserReferer(true));
                    $this->set('msg', Label::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT"));
                    $this->_template->render(false, false, 'json-error.php');
                }
            }
            $db->commitTransaction();
        }
        $userInfo = $userObj->getUserInfo(array(
            'user_facebook_id',
            'credential_username',
            'credential_password'
        ));
        if (!$userInfo || ($userInfo && $userInfo['user_facebook_id'] != $userFacebookId)) {
            // Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET"));
            $this->set('url', CommonHelper::redirectUserReferer(true));
            $this->set('msg',Label::getLabel("MSG_USER_COULD_NOT_BE_SET"));
            $this->_template->render(false, false, 'json-error.php');
        }
        $authentication = new UserAuthentication();
        if (!$authentication->login($userInfo['credential_username'], $userInfo['credential_password'], $_SERVER['REMOTE_ADDR'], false)) {
            // Message::addErrorMessage(Label::getLabel($authentication->getError()));
            $this->set('url', CommonHelper::redirectUserReferer(true));
            $this->set('msg', Label::getLabel($authentication->getError()));
            $this->_template->render(false, false, 'json-error.php');
        }


        unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_code']);
        unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_access_token']);
        unset($_SESSION['fb_' . FatApp::getConfig("CONF_FACEBOOK_APP_ID") . '_user_id']);

		$redirectUrl = User::getPreferedDashbordRedirectUrl();
		if ($user_type == User::USER_TYPE_TEACHER) {
			$redirectUrl = CommonHelper::generateUrl('TeacherRequest');
		}
        $this->set('url', $redirectUrl);
        $this->set('msg', Label::getLabel('MSG_LoggedIn_SUCCESSFULLY', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
        }
        // Message::addErrorMessage(Label::getLabel("MSG_UNABLE_To_FETCH_YOUR_EMAIL_ID"));
        $this->set('url', CommonHelper::generateUrl());
        $this->set('msg', Label::getLabel("MSG_UNABLE_To_FETCH_YOUR_EMAIL_ID"));
        $this->_template->render(false, false, 'json-error.php');
    }

    public function configureEmail()
    {
        $this->_template->render();
    }

    public function loginGoogleplus($userType = User::USER_TYPE_LEANER)
    {
        require_once CONF_INSTALLATION_PATH . 'library/googleplus/Google_Client.php'; // include the required calss files for google login
        require_once CONF_INSTALLATION_PATH . 'library/googleplus/contrib/Google_PlusService.php';
        require_once CONF_INSTALLATION_PATH . 'library/googleplus/contrib/Google_Oauth2Service.php';
        $client = new Google_Client();
        $client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_'.$this->siteLangId)); // Set your applicatio name
        $client->setScopes(array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/plus.me'
        )); // set scope during user login
        $client->setClientId(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_ID", FatUtility::VAR_STRING, '')); // paste the client id which you get from google API Console
        $client->setClientSecret(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_SECRET", FatUtility::VAR_STRING, '')); // set the client secret
        $currentPageUri = CommonHelper::generateFullUrl('GuestUser', 'loginGoogleplus', array($userType), '', false);
        $client->setRedirectUri($currentPageUri);
        $client->setDeveloperKey(FatApp::getConfig("CONF_GOOGLEPLUS_DEVELOPER_KEY", FatUtility::VAR_STRING, '')); // Developer key
        $plus = new Google_PlusService($client);
        $oauth2 = new Google_Oauth2Service($client); // Call the OAuth2 class for get email address
        if (isset($_GET['code'])) {
            $client->authenticate(); // Authenticate
            $_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
            FatApp::redirectUser($currentPageUri);
        }
        if (isset($_SESSION['access_token'])) {
            $client->setAccessToken($_SESSION['access_token']);
        }
        if (!$client->getAccessToken()) {
            $authUrl = $client->createAuthUrl();
            FatApp::redirectUser($authUrl);
        }
        $user = $oauth2->userinfo->get();
        //echo "<pre>"; print_r($oauth2->userinfo);die();
        $_SESSION['access_token'] = $client->getAccessToken();
        $userGoogleplusEmail = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
        $userGoogleplusId = $user['id'];
        $userGoogleplusName = $user['email'];
        if (isset($userGoogleplusEmail) && (!empty($userGoogleplusEmail))) {

            if ($userType == User::USER_TYPE_TEACHER) {
				$preferredDashboard = User::USER_TEACHER_DASHBOARD;
                $userType =   User::USER_TYPE_TEACHER;
			}else{
                $preferredDashboard = User::USER_LEARNER_DASHBOARD;
                $userType = User::USER_TYPE_LEANER;
            }

            $db = FatApp::getDb();
            $userObj = new User();
            $srch = $userObj->getUserSearchObj(array('user_id', 'user_googleplus_id', 'credential_email', 'credential_active', 'user_deleted'));
            $srch->addCondition('credential_email', '=', $userGoogleplusEmail);
            $rs = $srch->getResultSet();
            $row = $db->fetch($rs);
            if ($row) {
                if ($row['credential_active'] != applicationConstants::ACTIVE) {
                    Message::addErrorMessage(Label::getLabel("ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED"));
                    CommonHelper::redirectUserReferer();
                }
                $userObj->setMainTableRecordId($row['user_id']);
                $arr = array(
                    'user_googleplus_id' => $userGoogleplusId
                );
                if (!$userObj->setUserInfo($arr)) {
                    Message::addErrorMessage(Label::getLabel($userObj->getError()));
                    CommonHelper::redirectUserReferer();
                }
            } else {
                $db->startTransaction();
                $userNameArr = explode(" ", $userGoogleplusName);
                $user_first_name = $userNameArr[0];
                $user_last_name = $userNameArr[1];
                $userData = array(
                    'user_first_name' => $user_first_name,
                    'user_last_name' => $user_last_name,
                    'user_is_learner' => 1,
                    'user_googleplus_id' => $userGoogleplusId,
                    'user_preferred_dashboard' => $userType,
                    'user_registered_initially_for' => $userType,
                );
                $userObj->assignValues($userData);
                if (!$userObj->save()) {
                    Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET") . $userObj->getError());
                    $db->rollbackTransaction();
                    CommonHelper::redirectUserReferer();
                }
                $username = str_replace(" ", "", $userGoogleplusName) . $userGoogleplusId;
                if (!$userObj->setLoginCredentials($username, $userGoogleplusEmail, uniqid(), 1, 1)) {
                    Message::addErrorMessage(Label::getLabel("MSG_LOGIN_CREDENTIALS_COULD_NOT_BE_SET") . $userObj->getError());
                    $db->rollbackTransaction();
                    CommonHelper::redirectUserReferer();
                }
                $userData['user_username'] = $username;
                $userData['user_email'] = $userGoogleplusEmail;
                if (FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1) && $userGoogleplusEmail) {
                    $data['user_email'] = $userGoogleplusEmail;
                    $data['user_first_name'] = $user_first_name;
                    $data['user_last_name'] = $user_last_name;
                    $userId = $userObj->getMainTableRecordId();
                    $userObj = new User($userId);
                    if (!$this->userWelcomeEmailRegistration($userObj, $data)) {
                        Message::addErrorMessage(Label::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT"));
                        $db->rollbackTransaction();
                        CommonHelper::redirectUserReferer();
                    }
                }
                $db->commitTransaction();
            }
            $userInfo = $userObj->getUserInfo(array(
                'user_googleplus_id',
                'credential_username',
                'credential_password'
            ));
            if (!$userInfo || ($userInfo && $userInfo['user_googleplus_id'] != $userGoogleplusId)) {
                Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET"));
                CommonHelper::redirectUserReferer();
            }
            $authentication = new UserAuthentication();
            if (!$authentication->login($userInfo['credential_username'], $userInfo['credential_password'], $_SERVER['REMOTE_ADDR'], false)) {
                Message::addErrorMessage(Label::getLabel($authentication->getError()));
                CommonHelper::redirectUserReferer();
            }
            unset($_SESSION['access_token']);
            $redirectUrl = CommonHelper::generateUrl('Teachers');
    		if ($userType == User::USER_TYPE_TEACHER) {
    			$redirectUrl = CommonHelper::generateUrl('TeacherRequest');
    		}
    		FatApp::redirectUser($redirectUrl);
        }
        Message::addErrorMessage(Label::getLabel("MSG_UNABLE_To_FETCH_YOUR_EMAIL_ID"));
        FatApp::redirectUser(CommonHelper::generateUrl());
    }

    public function loginGoogle($userType = User::USER_TYPE_LEANER)
    {

        require_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php'; // include the required calss files for google login
        $client = new Google_Client();
        $client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_'.$this->siteLangId)); // Set your applicatio name
        $client->setScopes(['email','profile']); // set scope during user login
        $client->setClientId(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_ID")); // paste the client id which you get from google API Console
        $client->setClientSecret(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_SECRET")); // set the client secret
        $currentPageUri = CommonHelper::generateFullUrl('GuestUser', 'loginGoogle', array($userType), '', false);
        $client->setRedirectUri($currentPageUri);
        $client->setDeveloperKey(FatApp::getConfig("CONF_GOOGLEPLUS_DEVELOPER_KEY")); // Developer key
        $oauth2 =new Google_Service_Oauth2($client); // Call the OAuth2 class for get email address
        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']); // Authenticate
            $_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
            FatApp::redirectUser($currentPageUri);
        }
        if (isset($_SESSION['access_token'])) {
            $client->setAccessToken($_SESSION['access_token']);
        }
        if (!$client->getAccessToken()) {
            $authUrl = $client->createAuthUrl();
            FatApp::redirectUser($authUrl);
        }
        $user = $oauth2->userinfo->get();
        $_SESSION['access_token'] = $client->getAccessToken();
        $userGoogleEmail = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
        $userGoogleId = $user['id'];
        $userGoogleName = $user['name'];
        if (isset($userGoogleEmail) && (!empty($userGoogleEmail))) {

			if ($userType == User::USER_TYPE_TEACHER) {
				$preferredDashboard = User::USER_TEACHER_DASHBOARD;
                $userType =   User::USER_TYPE_TEACHER;
			}else{
                $preferredDashboard = User::USER_LEARNER_DASHBOARD;
                $userType = User::USER_TYPE_LEANER;
            }
            $db = FatApp::getDb();
            $userObj = new User();
            $srch = $userObj->getUserSearchObj(array(
                'user_id',
                'user_preferred_dashboard',
                'credential_email',
                'credential_active'
            ));
            $srch->addCondition('credential_email', '=', $userGoogleEmail);
            $rs = $srch->getResultSet();
            $row = $db->fetch($rs);
            if ($row) {
                if ($row['credential_active'] != applicationConstants::ACTIVE) {
                    Message::addErrorMessage(Label::getLabel("ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED"));
                    FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
                }
                $userObj->setMainTableRecordId($row['user_id']);
                $arr = array(
                    'user_googleplus_id' => $userGoogleId,
                );
                if (!$userObj->setUserInfo($arr)) {
                    Message::addErrorMessage(Label::getLabel($userObj->getError()));
                    FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
                }
                if($row['user_preferred_dashboard'] == User::USER_TEACHER_DASHBOARD) {
                    $userType = User::USER_TYPE_TEACHER;
                }
            } else {
                $db->startTransaction();
                $userNameArr = explode(" ", $userGoogleName);
                $user_first_name = $userNameArr[0];
                $user_last_name = isset($userNameArr[1])?$userNameArr[1]:'';
                $userData = array(
                    'user_first_name' => $user_first_name,
                    'user_last_name' => $user_last_name,
                    'user_is_learner' => 1,
                    'user_googleplus_id' => $userGoogleId,
                    'user_preferred_dashboard' => $preferredDashboard,
                    'user_registered_initially_for' => $userType,
                );
                $userObj->assignValues($userData);

                if (!$userObj->save()) {
                    Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET") . $userObj->getError());
                    $db->rollbackTransaction();
                    FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
                }

                $username = str_replace(" ", "", $userGoogleName) . $userGoogleId;
                if (!$userObj->setLoginCredentials($username, $userGoogleEmail, uniqid(), 1, 1)) {
                    Message::addErrorMessage(Label::getLabel("MSG_LOGIN_CREDENTIALS_COULD_NOT_BE_SET") . $userObj->getError());
                    $db->rollbackTransaction();
                    FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
                }
                $userData['user_username'] = $username;
                $userData['user_email'] = $userGoogleEmail;
                if (FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1) && $userGoogleEmail) {
                    $data['user_email'] = $userGoogleEmail;
                    $data['user_first_name'] = $user_first_name;
                    $data['user_last_name'] = $user_last_name;
                    $userId = $userObj->getMainTableRecordId();
                    $userObj = new User($userId);
                    if (!$this->userWelcomeEmailRegistration($userObj, $data)) {
                        Message::addErrorMessage(Label::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT"));
                        $db->rollbackTransaction();
                        FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
                    }
                }
                $db->commitTransaction();
            }
            $userInfo = $userObj->getUserInfo(array(
                'user_googleplus_id',
                'credential_username',
                'credential_password'
            ));
            if (!$userInfo || ($userInfo && $userInfo['user_googleplus_id'] != $userGoogleId)) {
                Message::addErrorMessage(Label::getLabel("MSG_USER_COULD_NOT_BE_SET"));
                FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
            }
            $authentication = new UserAuthentication();
            if (!$authentication->login($userInfo['credential_username'], $userInfo['credential_password'], $_SERVER['REMOTE_ADDR'], false)) {
                Message::addErrorMessage(Label::getLabel($authentication->getError()));
                FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
            }
            unset($_SESSION['access_token']);
            $redirectUrl = CommonHelper::generateUrl('Teachers');
    		if ($userType == User::USER_TYPE_TEACHER) {
    			$redirectUrl = CommonHelper::generateUrl('TeacherRequest');
    		}
            FatApp::redirectUser($redirectUrl);
        }
        Message::addErrorMessage(Label::getLabel("MSG_UNABLE_To_FETCH_YOUR_EMAIL_ID"));
        FatApp::redirectUser(CommonHelper::generateUrl());
    }

    public function forgotPasswordForm()
    {
        $frm = $this->getForgotForm();
        $this->set('frm', $frm);
        $this->set('siteLangId', $this->siteLangId);
        $this->_template->render();
    }

    public function forgotPassword()
    {
        $frm = $this->getForgotForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'forgotPasswordForm'));
        }
        if (FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '') != '' && FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '') != '') {
            if (!CommonHelper::verifyCaptcha()) {
                Message::addErrorMessage(Label::getLabel('MSG_That_captcha_was_incorrect'));
                FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'forgotPasswordForm'));
            }
        }
        $userAuthObj = new UserAuthentication();
        $row = $userAuthObj->getUserByEmailOrUserName($post['user_email_username'], '', false);
        if (!$row || false === $row) {
            Message::addErrorMessage(Label::getLabel($userAuthObj->getError()));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'forgotPasswordForm'));
        }

        if ($userAuthObj->checkUserPwdResetRequest($row['user_id'])) {
            Message::addErrorMessage(Label::getLabel($userAuthObj->getError()));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'forgotPasswordForm'));
        }
        $token = UserAuthentication::encryptPassword(FatUtility::getRandomString(20));
        $row['token'] = $token;
        $userAuthObj->deleteOldPasswordResetRequest();
        $db = FatApp::getDb();
        $db->startTransaction();
        // commonHelper::printArray($row); die;
        if (!$userAuthObj->addPasswordResetRequest($row)) {
            $db->rollbackTransaction();
            Message::addErrorMessage(Label::getLabel($userAuthObj->getError()));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'forgotPasswordForm'));
        }
        $row['link'] = CommonHelper::generateFullUrl('GuestUser', 'resetPassword', array(
            $row['user_id'],
            $token
        ));
        $email = new EmailHandler();
        if (!$email->sendForgotPasswordLinkEmail($this->siteLangId, $row)) {
            $db->rollbackTransaction();
            Message::addErrorMessage(Label::getLabel("MSG_ERROR_IN_SENDING_PASSWORD_RESET_LINK_EMAIL"));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'forgotPasswordForm'));
        }
        $db->commitTransaction();
        Message::addMessage(Label::getLabel("MSG_YOUR_PASSWORD_RESET_INSTRUCTIONS_TO_YOUR_EMAIL"));
        FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
    }

    public function resetPassword($userId = 0, $token = '')
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1 || strlen(trim($token)) < 20) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_RESET_PASSWORD_REQUEST'));
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $userAuthObj = new UserAuthentication();
        if (!$userAuthObj->checkResetLink($userId, trim($token), 'form')) {
            Message::addErrorMessage($userAuthObj->getError());
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $frm = $this->getResetPwdForm($userId, trim($token));
        $this->set('frm', $frm);
        $this->_template->render();
    }

    private function getForgotForm()
    {
        $siteLangId = $this->siteLangId;
        $frm = new Form('frmPwdForgot');
        $fld = $frm->addTextBox(Label::getLabel('LBL_Email', $siteLangId), 'user_email_username')->requirements()->setRequired();
        if (FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '') != '' && FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '') != '') {
            $frm->addHtml('', 'htmlNote', '<div class="g-recaptcha" data-sitekey="' . FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '') . '"></div>');
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('BTN_SUBMIT', $siteLangId));
        return $frm;
    }

    private function getResetPwdForm($uId, $token)
    {
        $siteLangId = $this->siteLangId;
        $frm = new Form('frmResetPwd');
        $fld_np = $frm->addPasswordField(Label::getLabel('LBL_NEW_PASSWORD', $siteLangId), 'new_pwd');
        /* $fld_np->htmlAfterField = '<span class="text--small">' . sprintf(Label::getLabel('LBL_Example_password', $siteLangId), 'User@123') . '</span>'; */
        $fld_np->requirements()->setRequired();
        $fld_np->requirements()->setRegularExpressionToValidate("^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%-_]{8,15}$");
        $fld_np->requirements()->setCustomErrorMessage(Label::getLabel('MSG_Valid_password', $siteLangId));
        $fld_cp = $frm->addPasswordField(Label::getLabel('LBL_CONFIRM_NEW_PASSWORD', $siteLangId), 'confirm_pwd');
        $fld_cp->requirements()->setRequired();
        $fld_cp->requirements()->setCompareWith('new_pwd', 'eq', '');
        $frm->addHiddenField('', 'user_id', $uId, array(
            'id' => 'user_id'
        ));
        $frm->addHiddenField('', 'token', $token, array(
            'id' => 'token'
        ));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_RESET_PASSWORD', $siteLangId));
        return $frm;
    }

    public function resetPasswordSetup()
    {
        $newPwd = FatApp::getPostedData('new_pwd');
        $confirmPwd = FatApp::getPostedData('confirm_pwd');
        $userId = FatApp::getPostedData('user_id', FatUtility::VAR_INT);
        $token = FatApp::getPostedData('token', FatUtility::VAR_STRING);
        if ($userId < 1 && strlen(trim($token)) < 20) {
            Message::addErrorMessage(Label::getLabel('MSG_REQUEST_IS_INVALID_OR_EXPIRED'));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $frm = $this->getResetPwdForm($userId, $token);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if ($post == false) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (true !== CommonHelper::validatePassword($post['new_pwd'])) {
            Message::addErrorMessage(Label::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC'));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $userAuthObj = new UserAuthentication();
        if (!$userAuthObj->checkResetLink($userId, trim($token), 'submit')) {
            Message::addErrorMessage($userAuthObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $pwd = UserAuthentication::encryptPassword($newPwd);
        if (!$userAuthObj->resetUserPassword($userId, $pwd)) {
            Message::addErrorMessage($userAuthObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $email = new EmailHandler();
        $userObj = new User($userId);
        $row = $userObj->getUserInfo(array(
            'user_first_name',
            'user_last_name',
            'credential_email'
        ), '', false);
        $email->sendResetPasswordConfirmationEmail($this->siteLangId, $row);
        $this->set('msg', Label::getLabel('MSG_PASSWORD_CHANGED_SUCCESSFULLY'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function resendEmailVerificationLink($username = "")
    {
        if (empty($username)) {
            FatUtility::dieWithError(Label::getLabel('MSG_ERROR_INVALID_REQUEST'));
        }
        $userAuthObj = new UserAuthentication();
        if (!$row = $userAuthObj->getUserByEmailOrUserName($username, false, false)) {
            FatUtility::dieWithError(Label::getLabel($userAuthObj->getError()));
        }
        if ($row['credential_verified'] == 1) {
            FatUtility::dieWithError(Label::getLabel("MSG_You_are_already_verified_please_login."));
        }
        $row['user_email'] = $row['credential_email'];
        $userObj = new User($row['user_id']);
        if (!$this->sendEmailVerificationLink($userObj, $row)) {
            FatUtility::dieWithError(Label::getLabel("MSG_VERIFICATION_EMAIL_COULD_NOT_BE_SENT"));
        }
        $this->set('msg', Label::getLabel('MSG_VERIFICATION_EMAIL_HAS_BEEN_SENT_AGAIN'));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function checkAjaxUserLoggedIn()
    {
        $json = array();
        $json['isUserLogged'] = FatUtility::int(UserAuthentication::isUserLogged());
        if (!$json['isUserLogged']) {
            $json['isUserLogged'] = FatUtility::int(UserAuthentication::isGuestUserLogged());
        }
        die(json_encode($json));
    }

    private function userWelcomeEmailRegistration($userObj, $data)
    {
        $email = new EmailHandler();
        if (!$email->sendWelcomeEmail($this->siteLangId, $data)) {
            Message::addMessage(Labels::getLabel("MSG_ERROR_IN_SENDING_WELCOME_EMAIL", $this->siteLangId));
            return false;
        }
        return true;
    }
    public function verifyEmail($_token)
    {
        $emailChangeReqObj = new UserEmailChangeRequest();
        $userRequest = $emailChangeReqObj->checkUserRequest($_token);
        if (empty($userRequest)) {
            Message::addErrorMessage(Label::getLabel("MSG_INVAILD_VERIFICATION_LINK", $this->siteLangId));
            $this->logout();
        }

        $userObj = new User($userRequest['uecreq_user_id']);
        $srch = $userObj->getUserSearchObj(array('user_id', 'credential_password'));
        $rs = $srch->getResultSet();
        $userRow = FatApp::getDb()->fetch($rs, 'user_id');

        if (false == $userRow) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_REQUEST'));
            $this->logout();
            //FatApp::redirectUser( FatUtility::generateUrl('GuestUser', '') );
        }

        if (!$userObj->changeEmail($userRequest['uecreq_email'])) {
            Message::addErrorMessage(Label::getLabel('MSG_Email_could_not_be_set'). $userObj->getError());
            $this->logout();
            //FatApp::redirectUser( FatUtility::generateUrl('GuestUser', '') );
        }
        $userRequest['status'] = 1;
        $emailCheReqObj = new UserEmailChangeRequest($userRequest['uecreq_id']);
        if (!$emailCheReqObj->updateUserRequestStatus()) {
            //Message::addErrorMessage(Label::getLabel('MSG_Email_could_not_be_set'). $userObj->getError());
            //FatUtility::dieWithError( Message::getHtml() );
        }
        Message::addMessage(Label::getLabel('MSG_Email_Updated._Please_Login_again_in_your_profile_with_new_email'));
        $this->logout();
    }
}
