<?php

class TwocheckoutPayController extends PaymentController
{
    protected $keyName = "Twocheckout";
    private $paymentType = "API"; //holds two values HOSTED or API

    public function __construct($action)
    {
        parent::__construct($action);
        
        if (!is_array($this->allowedCurrenciesArr())) {
            $this->setErrorAndRedirect(Label::getLabel('MSG_INVALID_CURRENCY_FORMAT', $this->siteLangId));
        }

        if (!in_array($this->systemCurrencyCode, $this->allowedCurrenciesArr())) {
            $msg = Label::getLabel('MSG_INVALID_ORDER_CURRENCY_({CURRENCY})_PASSED_TO_GATEWAY', $this->siteLangId);
            $msg = CommonHelper::replaceStringData($msg, ['{CURRENCY}' => $this->systemCurrencyCode]);
            $this->setErrorAndRedirect($msg);
        }
    }

    protected function allowedCurrenciesArr()
    {
        return [
            'USD', 'EUR', 'GBP', 'JPY', 'CAD', 'RON', 'CZK', 'HUF', 'TRY', 'ZAR', 'EGP', 'MXN', 'PEN'
        ];
    }

    public function charge($orderId)
    {
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

        if (!$orderInfo['order_id']) {
            FatUtility::exitWIthErrorCode(404);
        } elseif ($orderInfo["order_is_paid"] == Order::ORDER_IS_PENDING) {
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);

            if ($this->paymentType != 'HOSTED') {
                $this->set('sellerId', $this->settings['sellerId']);
                $this->set('publishableKey', $this->settings['publishableKey']);
                $this->set('transaction_mode', 'production');
            }
        } else {
            $this->set('error', Label::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId));
        }

        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Order::TYPE_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }

        $this->set('cancelBtnUrl', $cancelBtnUrl);

        $this->set('paymentAmount', $paymentAmount);
        $this->set('paymentType', $this->paymentType);
        $this->set('orderInfo', $orderInfo);
        $this->set('exculdeMainHeaderDiv', true);
        if (FatUtility::isAjaxCall()) {
            $json['html'] = $this->_template->render(false, false, 'twocheckout-pay/charge-ajax.php', true, false);
            FatUtility::dieJsonSuccess($json);
        }
        // $this->_template->addCss('css/payment.css');
        $this->_template->render(true, false);
    }

    /**
     * Description: This method will be called when the payment type is HOSTED CHECKOUT i.e. $paymentType has HOSTED value.
     */
    public function callback()
    {
        $post = FatApp::getPostedData();
        $orderId = $post['li_0_product_id']; //in our case it is order id (hosted checkout case)
        //$orderPaymentAmount = $request['total'];
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $orderPaymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $hashSecretWord = $this->settings['hashSecretWord']; //2Checkout Secret Word
        $hashSid = $this->settings['sellerId']; //2Checkout account number
        $hashOrder = $post['order_number']; //2Checkout Order Number
        $hashTotal = $orderPaymentAmount; //Sale total to validate against
        $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));

        if ($StringToHash == $post['key']) {
            if ($post['credit_card_processed'] == 'Y') {
                $message = '';
                $message .= '2Checkout Order Number: ' . $post['order_number'] . "\n";
                $message .= '2Checkout Invoice Id: ' . $post['invoice_id'] . "\n";
                $message .= 'Merchant Order Id: ' . $post['merchant_order_id'] . "\n";
                $message .= 'Pay Method: ' . $post['pay_method'] . "\n";
                $message .= 'Description: ' . $post['li_0_name'] . "\n";
                $message .= 'Hash Match: ' . 'Keys matched' . "\n";
                /* Recording Payment in DB */
                $orderPaymentObj->addOrderPayment($this->settings["plugin_code"], $post['invoice_id'], $orderPaymentAmount, Label::getLabel("LBL_Received_Payment", $this->siteLangId), $message);
                /* End Recording Payment in DB */
                FatApp::redirectUser(CommonHelper::generateUrl('custom', 'paymentSuccess', array($orderId)));
            }
        }
        Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
        FatApp::redirectUser(CommonHelper::getPaymentFailurePageUrl());
    }

    /**
     * Description: This function will be called in case of Payment type is API CHECKOUT i.e. $paymentType = API.
     */
    public function send($orderId)
    {
        $post = FatApp::getPostedData();
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        /* Retrieve Payment to charge corresponding to your order */
        $orderPaymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        if ($orderPaymentAmount > 0) {
            /* Retrieve Primary Info corresponding to your order */
            $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
            $order_actual_paid = number_format(round($orderPaymentAmount, 2), 2, ".", "");
            $params = array (
                'sellerId' => $this->settings['sellerId'],
                'privateKey' => $this->settings['privateKey'],
                'merchantOrderId' => $orderId,
                'token' => $post['token'],
                'currency' => $orderInfo["order_currency_code"],
                'total' => $order_actual_paid,
                'billingAddr' => 
                array (
                  'name' => FatUtility::decodeHtmlEntities($post['cc_owner'], ENT_QUOTES, 'UTF-8'),
                  "addrLine1" => '530, Phase 2', // @todo
                  "city" => 'Mohali', // @todo
                  "state" => 'Punjab', //@todo
                  "zipCode" => '160055',// @todo
                  'country' => 'USA', // @todo
                  'email' => $orderInfo['user_email'],
                  'phoneNumber' => '5555555555',
                ),
                'demo' => true,
              );
            

            if (FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false) == false) {
                $params['demo'] = true;
            }
            $url = 'https://www.2checkout.com/checkout/api/1/' . $this->settings['sellerId'] . '/rs/authService';

            $curl = curl_init($url);
            $params = json_encode($params);
            $header = array("content-type:application/json", "content-length:" . strlen($params));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_USERAGENT, "2Checkout PHP/0.1.0%s");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($curl);
            $json = array();
            $json['redirect'] = CommonHelper::generateUrl('custom', 'paymentFailed');

            if (curl_error($curl)) {
                $json['error'] = 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl);
            } elseif ($result) {
                $object = json_decode($result, true);
                $result_array = array();
                foreach ($object as $member => $data) {
                    $result_array[$member] = $data;
                }

                $exception = $result_array['exception']; //must be null in case of successful orders
                $response = $result_array['response'];
                $message = '';
                if (!is_null($response)) {
                    $errors = $response['errors'];
                    $validationErrors = !empty($response['validationErrors']) ? $response['validationErrors'] : ''; // '' or null
                    if (is_null($errors)) {
                        $responseCode = $response['responseCode']; //APPROVED : Code indicating the result of the authorization attempt.
                        $responseMsg = $response['responseMsg']; //Message indicating the result of the authorization attempt.
                        $orderNumber = $response['orderNumber']; //2Checkout Order Number
                        $merchantOrderId = $response['merchantOrderId']; //must be equal to order id sent
                        $transactionId = $response['transactionId']; //2Checkout Invoice ID
                        $message .= 'Response Code: ' . $responseCode . "\n";
                        $message .= 'Order Number: ' . $orderNumber . "\n";
                        $message .= 'Merchant Order Id: ' . $merchantOrderId . "\n";
                        $message .= 'Transaction Id: ' . $transactionId . "\n";
                        $message .= 'Payment Method: 2Checkout API' . "\n";
                        $message .= 'Response Message: ' . $responseMsg . "\n";
                        if ($responseCode == 'APPROVED') {
                            $orderPaymentObj->addOrderPayment($this->settings["pmethod_code"], $transactionId, $orderPaymentAmount, Label::getLabel("LBL_Received_Payment", $this->siteLangId), $message);
                            $json['redirect'] = CommonHelper::generateUrl('custom', 'paymentSuccess', array($orderId));
                        }
                    } else {
                        $json['error'] = $errors;
                    }
                } else {
                    $json['error'] = $exception['errorMsg'];
                }
            } else {
                $json['error'] = Label::getLabel('MSG_EMPTY_GATEWAY_RESPONSE', $this->siteLangId);
            }
        } else {
            $json['error'] = Label::getLabel('MSG_Invalid_Request', $this->siteLangId);
        }
        curl_close($curl);
        echo json_encode($json);
    }

    private function getPaymentForm($orderId)
    {
        return $this->getAPICheckoutForm($orderId);
    }

    private function getAPICheckoutForm($orderId)
    {
        $frm = new Form('frmTwoCheckout', array('id' => 'frmTwoCheckout', 'action' => CommonHelper::generateUrl('TwocheckoutPay', 'send', array($orderId)), 'class' => "form form--normal"));

        $frm->addRequiredField(Label::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER', $this->siteLangId), 'ccNo');
        $frm->addRequiredField(Label::getLabel('LBL_CARD_HOLDER_NAME', $this->siteLangId), 'cc_owner');
        $frm->addHiddenField('', 'token', '');

        $data['months'] = applicationConstants::getMonthsArr($this->siteLangId);
        $today = getdate();
        $data['year_expire'] = array();
        for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
            $data['year_expire'][strftime('%Y', mktime(0, 0, 0, 1, 1, $i))] = strftime('%Y', mktime(0, 0, 0, 1, 1, $i));
        }
        $frm->addSelectBox(Label::getLabel('LBL_EXPIRY_MONTH', $this->siteLangId), 'expMonth', $data['months'], '', array(), '');
        $frm->addSelectBox(Label::getLabel('LBL_EXPIRY_YEAR', $this->siteLangId), 'expYear', $data['year_expire'], '', array(), '');
        $fld = $frm->addPasswordField(Label::getLabel('LBL_CVV_SECURITY_CODE', $this->siteLangId), 'cvv');
        $fld->requirements()->setRequired(true);
        /* $frm->addCheckBox(Label::getLabel('LBL_SAVE_THIS_CARD_FOR_FASTER_CHECKOUT',$this->siteLangId), 'cc_save_card','1'); */
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Pay_Now', $this->siteLangId));

        return $frm;
    }

    public function getExternalLibraries()
    {
        $json['libraries'] = [
            "https://www.2checkout.com/checkout/api/2co.min.js",
        ];
        FatUtility::dieJsonSuccess($json);
    }
}
