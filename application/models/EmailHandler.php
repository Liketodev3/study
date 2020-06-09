<?php
class EmailHandler extends FatModel
{
    private $commonLangId;
    public function __construct()
    {
        $this->commonLangId = CommonHelper::getLangId();
    }

    public static function getMailTpl($tpl, $langId = 1)
    {
        $langId = FatUtility::int($langId);

        $srch = new SearchBase('tbl_email_templates');
        $srch->addCondition('etpl_code', '=', $tpl);
        if (1 > $langId) {
            $srch->addOrder('etpl_lang_id');
            $srch->addCondition('etpl_lang_id', '!=', 0);
        } else {
            $srch->addCondition('etpl_lang_id', '=', $langId);
        }
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        //echo $srch->getQuery();
        $rs = $srch->getResultSet();
        if (!$row = FatApp::getDb()->fetch($rs)) {
            return false;
        }
        return $row;
    }

    public static function sendMailTpl($to, $tpl, $langId, $vars = array(), $extra_headers = '', $smtp = 0, $smtp_arr = array())
    {
        $langId = FatUtility::int($langId);
        if (!$row = static::getMailTpl($tpl, $langId)) {
            $langId = FatApp::getConfig('conf_default_site_lang');
            if (!$row = static::getMailTpl($tpl, $langId)) {
                if (!$row = static::getMailTpl($tpl, 0)) {
                    trigger_error(Label::getLabel('ERR_Email_Template_Not_Found', CommonHelper::getLangId()), E_USER_ERROR);
                }
            }
        }

        if ($row['etpl_status'] != applicationConstants::ACTIVE) {
            return false;
        }

        if (!isset($row['etpl_body']) || $row['etpl_body'] == '') {
            return false;
        }

        $subject = $row['etpl_subject'];
        $body = $row['etpl_body'];

        $vars += SELF::commonVars($langId);

        foreach ($vars as $key => $val) {
            $subject = str_replace($key, $val, $subject);
            $body = str_replace($key, $val, $body);
        }


        if (FatApp::getConfig('CONF_SEND_SMTP_EMAIL')) {
            if (!$sendEmail = static::sendSmtpEmail($to, $subject, $body, '', $tpl, $langId, '', $smtp_arr)) {
                return static::sendMail($to, $subject, $body, '', $tpl, $langId);
            } else {
                return true;
            }
        } else {
            return static::sendMail($to, $subject, $body, '', $tpl, $langId);
        }
    }

    public function sendTxnNotification($txnId, $langId)
    {
        $langId = FatUtility::int($langId);
        $txn = new Transaction(0, $txnId);

        $txnDetail = $txn->getAttributesWithUserInfo(0, array( 'utxn_user_id', 'utxn_credit', 'utxn_debit','utxn_comments', 'user_first_name', 'user_last_name', 'credential_email' ));
        $statusArr = Transaction::getStatusArr($langId);

        $txnAmount = $txnDetail["utxn_credit"] > 0 ? $txnDetail["utxn_credit"] : $txnDetail["utxn_debit"];

        $arrReplacements = array(
            '{user_first_name}' => $txnDetail["user_first_name"],
            '{user_last_name}' => $txnDetail["user_last_name"],
            '{user_full_name}' => $txnDetail["user_first_name"].' '.$txnDetail['user_last_name'],
            '{txn_id}' => Transaction::formatTransactionNumber($txnId),
            '{txn_type}' => ($txnDetail["utxn_credit"] > 0) ? Label::getLabel('LBL_credited', $langId) : Label::getLabel('L_debited', $langId),
            '{txn_amount}' => CommonHelper::displayMoneyFormat($txnAmount, true, true),
            '{txn_comments}' => Transaction::formatTransactionComments($txnDetail["utxn_comments"]),
        );
        self::sendMailTpl($txnDetail["credential_email"], "account_credited_debited", $langId, $arrReplacements);
        return true;
    }

    public static function sendSmtpEmail($toAdress, $Subject, $body, $extra_headers = '', $tpl_name = '', $langId, $attachment="", $smtp_arr=array())
    {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $headers .= 'From: ' . FatApp::getConfig("CONF_FROM_NAME_".$langId) ."<".FatApp::getConfig("CONF_FROM_EMAIL").">" . "\r\nReply-to: ".FatApp::getConfig("CONF_REPLY_TO_EMAIL");

        if ($extra_headers != '') {
            $headers .= $extra_headers;
        }
        if (!FatApp::getDb()->insertFromArray('tbl_email_archives', array(
            'emailarchive_to_email'=>$toAdress,
            'emailarchive_tpl_name'=>$tpl_name,
            'emailarchive_subject'=>$Subject,
            'emailarchive_body'=>$body,
            'emailarchive_headers'=>FatApp::getDb()->quoteVariable($headers),
            'emailarchive_sent_on'=>date('Y-m-d H:i:s')
        ))) {
            return false;
        }
        require_once(CONF_INSTALLATION_PATH . 'library/PHPMailer/PHPMailerAutoload.php');
        $host = isset($smtp_arr["host"])?$smtp_arr["host"]:FatApp::getConfig("CONF_SMTP_HOST");
        $port = isset($smtp_arr["port"])?$smtp_arr["port"]:FatApp::getConfig("CONF_SMTP_PORT");
        $username = isset($smtp_arr["username"])?$smtp_arr["username"]:FatApp::getConfig("CONF_SMTP_USERNAME");
        $password = isset($smtp_arr["password"])?$smtp_arr["password"]:FatApp::getConfig("CONF_SMTP_PASSWORD");
        $secure = isset($smtp_arr["secure"])?$smtp_arr["secure"]:FatApp::getConfig("CONF_SMTP_SECURE");
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->IsHTML(true);
        $mail->Host = $host;
        $mail->Port = $port;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->SMTPDebug = false;
        $mail->SetFrom(FatApp::getConfig('CONF_FROM_EMAIL'));
        $mail->addAddress($toAdress);
        $mail->Subject = '=?UTF-8?B?'.base64_encode($Subject).'?=';
        $mail->MsgHTML($body);
        if (!$mail->send()) {
            return false;
        }
        return true;
    }

    private static function sendMail($to, $subject, $body, $extra_headers = '', $tpl_name = '', $langId)
    {
        $db = FatApp::getDb();
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $headers .= 'From: ' . FatApp::getConfig("CONF_FROM_NAME_".$langId) ."<".FatApp::getConfig("CONF_FROM_EMAIL").">" . "\r\nReply-to: ".FatApp::getConfig("CONF_REPLY_TO_EMAIL");

        if ($extra_headers != '') {
            $headers .= $extra_headers;
        }

        if (!$db->insertFromArray('tbl_email_archives', array(
            'emailarchive_to_email'=>$to,
            'emailarchive_tpl_name'=>$tpl_name,
            'emailarchive_subject'=>$subject,
            'emailarchive_body'=>$body,
            'emailarchive_headers'=>$db->quoteVariable($headers),
            'emailarchive_sent_on'=>date('Y-m-d H:i:s')
        ))) {
            return false;
        }

        if (1 == FatApp::getConfig("CONF_SEND_EMAIL")) {
            $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
            if (!mail($to, $subject, $body, $headers)) {
                return false;
            }
        }
        return true;
    }

    public function sendEmailVerificationLink($langId, $data)
    {
        $tpl = 'user_email_verification';

        $vars = array(
            '{user_first_name}' => $data['user_first_name'],
            '{user_last_name}' => $data['user_last_name'],
            '{user_full_name}' => $data['user_first_name'].' '.$data['user_last_name'],
            '{verification_url}' => $data['link'],
        );

        if (self::sendMailTpl($data['user_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendNewRegistrationNotification($langId, $data)
    {
        $tpl = 'new_registration_admin';

        $vars = array(
            '{user_first_name}'	=>	$data['user_first_name'],
            '{user_last_name}'	=>	$data['user_last_name'],
            '{user_full_name}' 	=> $data['user_first_name'].' '.$data['user_last_name'],
            '{user_email}' => $data['user_email'],
        );

        if (self::sendMailTpl(FatApp::getConfig('CONF_SITE_OWNER_EMAIL', FatUtility::VAR_STRING, 'weyakyak_admin@dummyid.com'), $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendWelcomeEmail($langId, $d)
    {
        $tpl = 'welcome_registration';

        $vars = array(
            '{user_first_name}' => $d['user_first_name'],
            '{user_last_name}' => $d['user_last_name'],
            '{user_full_name}' => $d['user_first_name'] . ' ' . $d['user_last_name'],
            '{contact_us_email}' => FatApp::getConfig('CONF_CONTACT_EMAIL', FatUtility::VAR_STRING, 'weyakyak_contact_us@dummyid.com'),
        );

        if (self::sendMailTpl($d['user_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendForgotPasswordLinkEmail($langId, $data)
    {
        $tpl = 'forgot_password';
        $vars = array(
            '{user_first_name}' => $data['user_first_name'],
            '{user_last_name}' => $data['user_last_name'],
            '{user_full_name}' => $data['user_first_name'].' '.$data['user_last_name'],
            '{reset_url}' 	 	=> $data['link'],
        );

        if (self::sendMailTpl($data['credential_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendResetPasswordConfirmationEmail($langId, $data)
    {
        $tpl = 'password_changed_successfully';
        $vars = array(
            '{user_first_name}' => $data['user_first_name'],
            '{user_last_name}' => $data['user_last_name'],
            '{user_full_name}' => $data['user_first_name'].' '.$data['user_last_name'],
            '{login_link}' => CommonHelper::generateFullUrl('GuestUser', 'loginForm'),
        );

        if (self::sendMailTpl($data['credential_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }
    public static function sendlearnerScheduleEmail($to, $data, $langId)
    {
        $tpl = 'learner_schedule_email';
        $vars = array(
            '{learner_name}' => $data['learnerFullName'],
            '{teacher_name}' => $data['teacherFullName'],
            '{lesson_name}' => $data['teacherTeachLanguageName'],
            '{lesson_date}' => $data['startDate'], //y-m-d
            '{lesson_start_time}' =>  $data['startTime'], // H:i:s
            '{lesson_end_time}' => $data['endTime'], // H:i:s
            '{learner_comment}' => '',
            '{action}' => ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_SCHEDULED],
        );
        if (self::sendMailTpl($to, $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function SendTeacherRequestStatusChangeNotification($langId, $data)
    {
        $tpl = 'teacher_request_status_change_learner';

        $teacherRequestComments = '';
        if ($data['utrequest_comments'] != '') {
            $teacherRequestComments = nl2br($data['utrequest_comments']);
        }

        $statusArr = TeacherRequest::getStatusArr($langId);

        $vars = array(
            '{user_first_name}'	=>	$data['user_first_name'],
            '{user_last_name}'	=>	$data['user_last_name'],
            '{user_full_name}' => $data['user_first_name'].' '.$data['user_last_name'],
            '{reference_number}' => $data['utrequest_reference'],
            '{new_request_status}' => $statusArr[$data['utrequest_status']],
            '{request_comments}' => $teacherRequestComments,
        );

        if (self::sendMailTpl($data['credential_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendContactRequestEmailToAdmin($langId, &$d)
    {
        $tpl = 'tpl_contact_request_received';
        $vars = array(
                    '{requests_link}' => $d['link'],
                );

        $to = FatApp::getConfig('CONF_CONTACT_TO_EMAIL', FatUtility::VAR_STRING, '');
        if (strlen(trim($to)) < 1) {
            $to = FatApp::getConfig('CONF_SITE_OWNER_EMAIL');
        }
        if (self::sendMailTpl($to, $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendContactFormEmail($to, $langId, $d)
    {
        $tpl = 'contact_us';
        $vars = array(
                    '{name}' => $d['name'],
                    '{email_address}' => $d['email'],
                    '{phone_number}' => $d['phone'],
                    '{message}' => nl2br($d['message'])
                );
        if (self::sendMailTpl($to, $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    private static function commonVars($langId)
    {
        $srch = SocialPlatform::getSearchObject($langId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('splatform_user_id', '=', 0);
        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);

        $social_media_icons='';
        $imgSrc='';
        foreach ($rows as $row) {
            $img = AttachedFile::getAttachment(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $row['splatform_id']);
            $title = ($row['splatform_title'] != '') ? $row['splatform_title'] : $row['splatform_identifier'];
            $target_blank = ($row['splatform_url'] != '') ? 'target="_blank"' : '';
            $url = $row['splatform_url']!=''?$row['splatform_url']:'javascript:void(0)';

            $imgSrc = '';
            if ($img) {
                $imgSrc = CommonHelper::generateFullUrl('Image', 'SocialPlatform', array($row['splatform_id']), CONF_WEBROOT_FRONT_URL);
            }
            $social_media_icons .= '<a style="display:inline-block;vertical-align:top; width:35px;height:35px; margin:0 0 0 5px; background:#1a1a1a;border-radius:100%;padding:4px;" href="'.$url.'" '.$target_blank.' title="'.$title.'" ><img alt="'.$title.'" width="24" style="margin:4px auto 0; display:block;" src = "'.$imgSrc.'"/></a>';
        }

        return array(
            '{website_name}'	=>	FatApp::getConfig('CONF_WEBSITE_NAME_'.$langId),
            '{website_url}'		=>	CommonHelper::generateFullUrl('', '', array(), CONF_WEBROOT_FRONT_URL),
            '{Company_Logo}'	=>	'<img style="max-width: 160px;" src="' . CommonHelper::generateFullUrl('Image', 'emailLogo', array($langId), CONF_WEBROOT_FRONT_URL) . '" />',
            '{current_date}'	=>	date('M d, Y'),
            '{social_media_icons}' => $social_media_icons,
            '{contact_us_url}' => CommonHelper::generateFullUrl('contact', '', array(), CONF_WEBROOT_FRONT_URL),
            '{notifcation_email}' => FatApp::getConfig('CONF_FROM_EMAIL')
        );
    }

    public static function sendSmtpTestEmail($langId, $smtpArr, $vars = array())
    {
        $tpl='test_email';
        $langId = FatUtility::int($langId);
        if (!$row =static::getMailTpl($tpl, $langId)) {
            $langId = FatApp::getConfig('conf_default_site_lang');
            if (!$row =static::getMailTpl($tpl, $langId)) {
                trigger_error(Label::getLabel('ERR_Email_Template_Not_Found', CommonHelper::getLangId()), E_USER_ERROR);
                return false;
            }
        }

        if (!isset($row['etpl_body']) || $row['etpl_body'] == '') {
            return false;
        }

        $subject = $row['etpl_subject'];
        $body = $row['etpl_body'];

        $vars += SELF::commonVars($langId);

        foreach ($vars as $key => $val) {
            $subject = str_replace($key, $val, $subject);
            $body = str_replace($key, $val, $body);
        }
        try {
            $email = EmailHandler::sendSmtpEmail(FatApp::getConfig("CONF_SITE_OWNER_EMAIL"), $subject, $body, '', $tpl, $langId, '', $smtpArr);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function sendTestEmail($langId)
    {
        $tpl='test_email';
        $langId = FatUtility::int($langId);
        if (!$row =static::getMailTpl($tpl, $langId)) {
            $langId = FatApp::getConfig('conf_default_site_lang');
            if (!$row =static::getMailTpl($tpl, $langId)) {
                trigger_error(Label::getLabel('ERR_Email_Template_Not_Found', CommonHelper::getLangId()), E_USER_ERROR);
                return false;
            }
        }

        if (!isset($row['etpl_body']) || $row['etpl_body'] == '') {
            return false;
        }

        $subject = $row['etpl_subject'];
        $body = $row['etpl_body'];

        $vars += SELF::commonVars($langId);

        foreach ($vars as $key => $val) {
            $subject = str_replace($key, $val, $subject);
            $body = str_replace($key, $val, $body);
        }

        try {
            $email = EmailHandler::sendSmtpEmail(FatApp::getConfig("CONF_SITE_OWNER_EMAIL"), $subject, $body, '', $tpl, $langId, '', $smtpArr);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function failedLoginAttempt($langId, $data)
    {
        $tpl = 'failed_login_attempt';

        $vars = array(
            '{user_first_name}' => $data['user_first_name'],
            '{user_last_name}' => $data['user_last_name'],
            '{user_full_name}' => $data['user_first_name'].' '.$data['user_last_name'],
        );
        if (self::sendMailTpl($data['credential_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    private function sendnotificationToRecipient($giftcardlist)
    {
        $langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);


        foreach ($giftcardlist as $GiftCardData) {
            $currencyData = Currency::getAttributesById($GiftCardData['order_currency_id']);
            if (!empty($GiftCardData)) {
                $arrReplacementsRecipient = array(
                '{sender_name}' => trim($GiftCardData['gcbuyer_name']) ,
                '{recipient_name}' => $GiftCardData['gcrecipient_name'],
                '{giftcard_code}' => $GiftCardData['giftcard_code'],
                '{giftcard_amount}' => $currencyData['currency_symbol_left'] . " " . $GiftCardData['giftcard_amount'],
                '{giftcard_expire_date}' => $GiftCardData['giftcard_expiry_date'],
                '{contact_us_email}' => FatApp::getConfig('CONF_CONTACT_EMAIL')
                );

                if (CommonHelper::isValidEmail($GiftCardData['gcrecipient_email'])) {
                    self::sendMailTpl($GiftCardData['gcrecipient_email'], "giftcard_recipient", $langId, $arrReplacementsRecipient);
                }
            }
        }
    }

    public function sendGiftCardNotification($giftcardlist)
    {
        $langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
        //$this->sendnotificationToBuyer($giftcardlist);

        $this->sendnotificationToRecipient($giftcardlist);

        $this->sendnotificationToAdmin($giftcardlist);
    }

    private function sendnotificationToAdmin($giftcardlist)
    {
        $langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
        $list = array();
        foreach ($giftcardlist as $GiftCardData) {
            $list[$GiftCardData['gcbuyer_email']]['gcbuyer_name'] = $GiftCardData['gcbuyer_name'];
            $list[$GiftCardData['gcbuyer_email']]['gcbuyer_email'] = $GiftCardData['gcbuyer_email'];
            $list[$GiftCardData['gcbuyer_email']]['cardDetail'][] = array('recipient_name'=>$GiftCardData['gcrecipient_name'],'gcrecipient_email'=>$GiftCardData['gcrecipient_email'],'code'=>$GiftCardData['giftcard_code'],'amount'=>$GiftCardData['giftcard_amount'],'expireon'=>$GiftCardData['giftcard_expiry_date'] ,'currency'=>$GiftCardData['order_currency_id']);
        }
        foreach ($list as $key=>$card) {
            $buyermailHtml = $this->buyerGiftcardData($card['cardDetail']);
            $arrReplacementsAdmin = array(
                        '{buyer_name}' => trim($card['gcbuyer_name']) ,
                        '{giftcard_codes}' => $buyermailHtml,
                        '{contact_us_email}' => FatApp::getConfig('CONF_CONTACT_EMAIL')
                    );
            if (CommonHelper::isValidEmail(FatApp::getConfig('CONF_SITE_OWNER_EMAIL', FatUtility::VAR_STRING))) {
                self::sendMailTpl(FatApp::getConfig('CONF_SITE_OWNER_EMAIL', FatUtility::VAR_STRING), "giftcard_admin", $langId, $arrReplacementsAdmin);
            }
        }
    }

    public function giftcardRedeenNotificationAdmin($giftcardCode, $langId, $currencyId)
    {
        $currencyData = Currency::getAttributesById($currencyId);
        $srch = new SearchBase(Giftcard::DB_TBL, 'giftcard');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'giftcard.giftcard_recipient_user_id = u.user_id', 'u');
        $srch->addMultipleFields(array("concat(u.user_first_name,' ',u.user_last_name) as giftcard_username","giftcard.giftcard_amount"));
        $srch->addCondition('giftcard.giftcard_code', '=', $giftcardCode);
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row)) {
            $arrReplacements = array(
                '{giftcard_username}' => trim($row['giftcard_username']) ,
                '{giftcard_code}' => strtoupper($giftcardCode),
                '{giftcard_amount}' => $currencyData['currency_symbol_left'].' '.$row['giftcard_amount'],
                '{contact_us_email}' => FatApp::getConfig('CONF_CONTACT_EMAIL')
            );

            if (CommonHelper::isValidEmail(FatApp::getConfig('CONF_SITE_OWNER_EMAIL', FatUtility::VAR_STRING))) {
                self::sendMailTpl(FatApp::getConfig('CONF_SITE_OWNER_EMAIL', FatUtility::VAR_STRING), "giftcard_redeem_admin", $langId, $arrReplacements);
            }
        }
    }

    public function buyerGiftcardData($giftcardlist)
    {
        $langId = CommonHelper::getLangId();
        $html = '<table style="border:1px solid #ddd; border-collapse:collapse; text-align: left;" cellspacing="0" cellpadding="0" border="0">';
        $html.= "<tr>";
        $html.= "<th style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;' width='153'>" . Label::getLabel('LBL_Giftcard_Code', $langId) . "</th>";
        $html.= "<th style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;' width='153'>" . Label::getLabel('LBL_Giftcard_Recipient_Name', $langId) . "</th>";
        $html.= "<th style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;' width='153'>" . Label::getLabel('LBL_Giftcard_Amount', $langId) . "</th>";
        $html.= "<th style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;' width='153'>" . Label::getLabel('LBL_Giftcard_Exipre_Date', $langId) . "</th></tr>";
        foreach ($giftcardlist as $giftcard) {
            $currencyData = Currency::getAttributesById($giftcard['currency']);
            $html.= "<tr>";
            $html.= "<td style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333;' width='153'><span style='border:3px dotted #ddd;padding:5px 10px;font-weight:bold'>" . $giftcard['code'] . "</span></td>";
            $html.= "<td style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333;' width='153'>" . $giftcard['recipient_name'] . "<br/>" . $giftcard['gcrecipient_email'] . "</td>";
            $html.= "<td style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333;' width='153'>" . $currencyData['currency_symbol_left'] . " " . $giftcard['amount'] . "</td>";
            $html.= "<td style='padding:10px;font-size:13px;border:1px solid #ddd; color:#333;' width='153'>" . $giftcard['expireon'] . "</td></tr>";
        }

        $html.= "</table>";
        return $html;
    }

    public function sendBlogContributionStatusChangeEmail($langId, $d)
    {
        $tpl = 'blog_contribution_status_changed';
        $statusArr = applicationConstants::getBlogContributionStatusArr(FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG'));
        $vars = array(
                    '{user_full_name}' => $d['bcontributions_author_first_name'],
                    '{new_status}' => $statusArr[$d['bcontributions_status']],
                    '{posted_on_datetime}' => $d['bcontributions_added_on'],
                );

        if (self::sendMailTpl($d['bcontributions_author_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendBlogCommentStatusChangeEmail($langId, $d)
    {
        $tpl = 'blog_comment_status_changed';
        $statusArr = applicationConstants::getBlogCommentStatusArr(FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG'));
        $vars = array(
                    '{user_full_name}' => $d['bpcomment_author_name'],
                    '{new_status}' => $statusArr[$d['bpcomment_approved']],
                    '{post_title}' => $d['post_title'],
                    '{comment}' => $d['bpcomment_content'],
                    '{posted_on_datetime}' => $d['bpcomment_added_on'],
                );

        if (self::sendMailTpl($d['bpcomment_author_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendEmailChangeVerificationLink($langId, $data)
    {
        $tpl = 'user_email_change_verification';

        $vars = array(
            '{user_first_name}' => $data['user_first_name'],
            '{user_last_name}' => $data['user_last_name'],
            '{user_full_name}' => $data['user_first_name'].' '.$data['user_last_name'],
            '{verification_url}' => $data['link'],
        );

        if (self::sendMailTpl($data['user_email'], $tpl, $langId, $vars)) {
            return true;
        }
        return false;
    }

    public function sendLessonReminderMail($templete, $langId, $data)
    {
        $LearnerVars = array(
            '{user_first_name}' => $data['user_first_name'],
            '{user_last_name}' => $data['user_last_name'],
            '{user_full_name}' => $data['user_full_name'],
            '{lessons_details}' => $data['lessons_details'],
        );

        if (self::sendMailTpl($data['user_email'], $templete, $langId, $LearnerVars)) {
            return true;
        }
        return false;
    }
}
