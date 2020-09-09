<?php
class AuthorizeAimPayController extends PaymentController
{
    /* public const KEY_NAME = "AuthorizeAim"; */
    protected $keyName = "AuthorizeAim";
    /*	private $testEnvironmentUrl = 'https://test.authorize.net/gateway/transact.dll';
        private $liveEnvironmentUrl = 'https://secure.authorize.net/gateway/transact.dll';*/
    private $testEnvironmentUrl = "https://apitest.authorize.net/xml/v1/request.api";
    private $liveEnvironmentUrl = "https://api.authorize.net/xml/v1/request.api";
    public function charge($orderId)
    {
        if (empty($orderId)) {
            FatUtility::exitWIthErrorCode(404);
        }
        
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        // $orderInfo = $orderPaymentObj->getOrderById($orderId);
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if (!$orderInfo['order_id']) {
            FatUtility::exitWIthErrorCode(404);
        } elseif ($orderInfo["order_is_paid"] == Order::ORDER_IS_PENDING) {
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);
            $this->set('paymentAmount', $paymentAmount);
        } else {
            $this->set('error', Label::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId));
        }

        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Order::TYPE_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }
        $this->set('cancelBtnUrl', $cancelBtnUrl);
        $this->set('orderInfo', $orderInfo);
        $this->set('paymentAmount', $paymentAmount);
        $this->set('exculdeMainHeaderDiv', true);
        $this->_template->addCss('css/payment.css');
        $this->_template->render(true, false);
    }

    public function send($orderId)
    {
        $pmObj=new PaymentSettings($this->keyName);
        $paymentSettings=$pmObj->getPaymentSettings();
        $post = FatApp::getPostedData();
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        /* Retrieve Payment to charge corresponding to your order */
        $orderPaymentAamount=$orderPaymentObj->getOrderPaymentGatewayAmount();

        $oPObj =  $orderPaymentObj->getOrderPayment($this->keyName);
        $resultset =  $oPObj->getResultSet();
        $orderPayment = FatApp::getDb()->fetch($resultset);
        if ($orderPaymentAamount > 0 && empty($orderPayment)) {
    
            $orderInfo =  $orderPaymentObj->getOrderPrimaryinfo();
            $orderActualPaid = number_format(round($orderPaymentAamount, 2), 2, ".", "");
            $actionUrl = (FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false) == true)?$this->liveEnvironmentUrl:$this->testEnvironmentUrl;
        
            $orderPaymentGatewayDescription = sprintf(Label::getLabel("MSG_Order_Payment_Gateway_Description", $this->siteLangId), FatApp::getConfig("CONF_WEBSITE_NAME_". $orderInfo["order_language_id"]), $orderInfo['order_id']);
            $data = array(
                "createTransactionRequest" => array(
                    "merchantAuthentication" => array(
                        "name" => $paymentSettings['login_id'],
                        "transactionKey" => $paymentSettings['transaction_key']
                    ),
                    "refId" => $orderId,
                    "transactionRequest" => array(
                        "transactionType" => 'authCaptureTransaction',
                        "amount" => $orderActualPaid,
                        "payment" => array(
                            "creditCard" => array(
                                "cardNumber" => str_replace(' ', '', $post['cc_number']),
                                "expirationDate" => $post['cc_expire_date_year'] . "-" . $post['cc_expire_date_month'],
                                "cardCode" => $post['cc_cvv'],
                            )
                        ),
                        "order" => array(
                            "invoiceNumber" => $orderId,
                            "description" => FatUtility::decodeHtmlEntities($orderPaymentGatewayDescription, ENT_QUOTES, 'UTF-8')
                        ),
                        "lineItems" => array(
                            "lineItem" => array(
                                "itemId" => $orderId,
                                "name" => "Cart Payment",
                                "description" => sprintf(Label::getLabel("Order_Payment_Gateway_Description"), FatApp::getConfig("CONF_WEBSITE_NAME_".$this->siteLangId), $orderId),
                                "quantity" => "1",
                                "unitPrice" => $orderActualPaid,
                            )
                        ),
                    
                        "customerIP" => $_SERVER['REMOTE_ADDR'],
                        "transactionSettings"=> array(
                            "setting"=> array(
                                "settingName"=> "testRequest",
                                "settingValue"=> (FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false) == true) ? 'false' : 'true'
                            )
                        )
                    )
                )
            );

            $response = $this->executeCurl($data, $actionUrl);
            
            $json = array();
            if ($response['status'] == 0) {
                $json['error'] = Label::getLabel('LBL_Payment_cannot_be_processed_right_now._Please_try_after_some_time.');
            } elseif ((!empty($response['response']['transactionResponse']['errors'])) || ( (!empty($response['response']['transactionResponse']['responseCode'])) && $response['response']['transactionResponse']['responseCode'] != 1)) {
                $errorMsg = isset($response['response']['transactionResponse']['errors'][0]['errorText'])?$response['response']['transactionResponse']['errors'][0]['errorText']:current($response['response']['messages']['message'])['text'];
                $json['error'] = $errorMsg;
            } elseif ($response['status'] == 1) {
                $json = array();
                if ($response['response']['messages']['resultCode'] == 'Ok') {
                    $message = '';
                    if (isset($response['response']['transactionResponse']['authCode'])) {
                        $message .= 'Authorization Code: ' . $response['response']['transactionResponse']['authCode'] . "\n";
                    }
                    if (isset($response['response']['transactionResponse']['avsResultCode'])) {
                        $message .= 'AVS Response: ' . $response['response']['transactionResponse']['avsResultCode'] . "\n";
                    }
                    if (isset($response['response']['transactionResponse']['transId'])) {
                        $message .= 'Transaction ID: ' . $response['response']['transactionResponse']['transId'] . "\n";
                    }
                    if (isset($response['response']['transactionResponse']['cvvResultCode'])) {
                        $message .= 'Card Code Response: ' . $response['response']['transactionResponse']['cvvResultCode'] . "\n";
                    }
                    if (isset($response['response']['transactionResponse']['cavvResultCode'])) {
                        $message .= 'Cardholder Authentication Verification Response: ' . $response['response']['transactionResponse']['cavvResultCode'] . "\n";
                    }
                    if (isset($response['response']['transactionResponse']['accountNumber'])) {
                        $message .= 'Account Number: ' . $response['response']['transactionResponse']['accountNumber'] . "\n";
                    }
                    if (isset($response['response']['transactionResponse']['accountType'])) {
                        $message .= 'Account Type: ' . $response['response']['transactionResponse']['accountType'] . "\n";
                    }
                    //if (!$paymentSettings['md5_hash'] || (strtoupper($responseInfo[38]) == strtoupper(md5($paymentSettings['md5_hash'].$paymentSettings['login_id'].$responseInfo[7] . $orderActualPaid)))) {
                    /* Recording Payment in DB */
                    if (!$orderPaymentObj->addOrderPayment($this->keyName, $response['response']['transactionResponse']['transId'], $orderPaymentAamount, $message, serialize($response))) {
                        $orderPaymentObj->addOrderPaymentComments(serialize($response));
                        $json['error'] = "Invalid Action";
                    }
                    /* End Recording Payment in DB */
                    //} else {
                    /*  Do what ever you want to do */
                    //}
                    $json['redirect'] = FatUtility::generateUrl("custom", "paymentSuccess", array($orderId));
                } else {
                    $errorMsg = isset($response['response']['transactionResponse']['errors'][0]['errorText'])?$response['response']['transactionResponse']['errors'][0]['errorText']:current($response['response']['messages']['message'])['text'];
                    $json['error'] = $errorMsg;
                }
            } else {
                $json['error'] = Label::getLabel('MSG_EMPTY_GATEWAY_RESPONSE', $this->siteLangId);
            }
        } else {
            $json['error'] = Label::getLabel('MSG_Invalid_Request', $this->siteLangId);
        }
        echo json_encode($json);
    }

    public function checkCardType()
    {
        $post = FatApp::getPostedData();
        $res=CommonHelper::validate_cc_number($post['cc']);
        echo json_encode($res);
        exit;
    }

    private function getPaymentForm($orderId = '')
    {
        $frm = new Form('frmPaymentForm', array('id' => 'frmPaymentForm', 'action' => CommonHelper::generateUrl('AuthorizeAimPay', 'send', array($orderId)), 'class' => "form form--normal"));
        $frm->addRequiredField(Label::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER', $this->siteLangId), 'cc_number');
        $frm->addRequiredField(Label::getLabel('LBL_CARD_HOLDER_NAME', $this->siteLangId), 'cc_owner');
        $data['months'] = applicationConstants::getMonthsArr($this->siteLangId);
        $today = getdate();
        $data['year_expire'] = array();
        for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
            $data['year_expire'][strftime('%Y', mktime(0, 0, 0, 1, 1, $i))] = strftime('%Y', mktime(0, 0, 0, 1, 1, $i));
        }
        $frm->addSelectBox(Label::getLabel('LBL_EXPIRY_MONTH', $this->siteLangId), 'cc_expire_date_month', $data['months'], '', array(), '');
        $frm->addSelectBox(Label::getLabel('LBL_EXPIRY_YEAR', $this->siteLangId), 'cc_expire_date_year', $data['year_expire'], '', array(), '');
        $fld = $frm->addPasswordField(Label::getLabel('LBL_CVV_SECURITY_CODE', $this->siteLangId), 'cc_cvv');
        $fld->requirements()->setRequired(true);
        $fld->requirements()->setLength(3, 5);
        /* $frm->addCheckBox(Label::getLabel('LBL_SAVE_THIS_CARD_FOR_FASTER_CHECKOUT',$this->siteLangId), 'cc_save_card','1'); */
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Pay_Now', $this->siteLangId));
        return $frm;
    }

    private function executeCurl($data, $url)
    {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            return ['status' => 0, 'error' => 'CURL ERROR: ' . curl_errno($ch) . '::' . curl_error($ch)];
        } else {
            $bom = pack('H*', 'EFBBBF');
            $response = preg_replace("/^$bom/", '', $response);
            return ['status' => 1, 'response' => json_decode($response, true)];
        }
    }
}
