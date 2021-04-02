<?php

require_once CONF_INSTALLATION_PATH . 'library/payment-plugins/stripe/init.php';

class StripePayController extends PaymentController
{

    protected $keyName = "Stripe";
    private $error = false;
    private $paymentSettings = false;

    protected function allowedCurrenciesArr()
    {
        return [
            'USD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB',
            'BRL', 'BSD', 'BWP', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB',
            'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK',
            'JMD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK',
            'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP',
            'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SZL',
            'THB', 'TJS', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW'
        ];
    }

    private function zeroDecimalCurrencies()
    {
        return ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];
    }

    public function charge($orderId)
    {
        if (empty(trim($orderId))) {
            FatUtility::exitWIthErrorCode(404);
        }
        $this->paymentSettings = $this->getPaymentSettings();
        if (!isset($this->paymentSettings['privateKey']) && !isset($this->paymentSettings['publishableKey'])) {
            Message::addErrorMessage(Label::getLabel('STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        $stripe = array('secret_key' => $this->paymentSettings['privateKey'], 'publishable_key' => $this->paymentSettings['publishableKey']);
        $this->set('stripe', $stripe);
        if (strlen(trim($this->paymentSettings['privateKey'])) > 0 && strlen(trim($this->paymentSettings['publishableKey'])) > 0) {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
        } else {
            $this->error = Label::getLabel('STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', $this->siteLangId);
        }
        $systemCurrencyCode = Currency::getAttributesById(FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1), 'currency_code');
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $payableAmount = $this->formatPayableAmount($paymentAmount);
        $orderSrch = new OrderSearch();
        $orderSrch->joinUser();
        $orderSrch->joinUserCredentials();
        $orderSrch->addCondition('order_id', '=', $orderId);
        $orderSrch->addMultipleFields(array(
            'order_id',
            'order_language_id',
            'order_currency_code',
            'u.user_first_name as user_first_name',
            'cred.credential_email as customer_email',
            'order_is_paid',
            'order_language_code'
        ));
        $orderRs = $orderSrch->getResultSet();
        $orderInfo = FatApp::getDb()->fetch($orderRs);
        if (!$orderInfo['order_id']) {
            FatUtility::exitWithErrorCode(404);
        } elseif ($orderInfo && $orderInfo["order_is_paid"] == Order::ORDER_IS_PENDING) {
            try {
                $session = \Stripe\Checkout\Session::create([
                            'customer_email' => $orderInfo['customer_email'],
                            'payment_method_types' => ['card'],
                            'metadata' => [
                                'order_id' => $orderId
                            ],
                            'line_items' => [[
                            'price_data' => [
                                'currency' => $systemCurrencyCode,
                                'product_data' => [
                                    'name' => Label::getLabel('LBL_Buy_Lessons'),
                                ],
                                'unit_amount' => $payableAmount
                            ],
                            'quantity' => 1,
                                ]],
                            'mode' => 'payment',
                            'success_url' => CommonHelper::generateFullUrl('StripePay', 'callback') . "?session_id={CHECKOUT_SESSION_ID}",
                            'cancel_url' => CommonHelper::getPaymentCancelPageUrl(),
                ]);
                $this->set('stripeSessionId', $session->id);
            } catch (exception $e) {
                $this->set('error', $e->getMessage());
            }
        } else {
            $message = Label::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId);
            $this->error = $message;
        }
        $this->set('paymentAmount', $paymentAmount);
        $this->set('orderInfo', $orderInfo);
        if ($this->error) {
            $this->set('error', $this->error);
        }
        $this->set('exculdeMainHeaderDiv', true);
        $this->_template->render(true, false);
    }

    private function formatPayableAmount($amount = null)
    {
        if ($amount == null) {
            return false;
        }
        $systemCurrencyCode = Currency::getAttributesById(FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1), 'currency_code');
        $amount = number_format($amount, 2, '.', '');
        if (in_array($systemCurrencyCode, $this->zeroDecimalCurrencies())) {
            return round($amount);
        }
        return $amount * 100;
    }

    private function getPaymentSettings()
    {
        $pmObj = new PaymentSettings($this->keyName);
        return $pmObj->getPaymentSettings();
    }

    public function callback()
    {
        $get = FatApp::getQueryStringData();
        $sessionId = $get['session_id'];
        $this->updatePaymentStatus($sessionId);
    }

    public function webhook()
    {
        $payload = file_get_contents('php://input');
        try {
            $event = \Stripe\Event::constructFrom(
                            json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }
        $sessionId = $event->data->id;
        $this->updatePaymentStatus($sessionId);
    }

    private function updatePaymentStatus($sessionId)
    {
        $this->paymentSettings = $this->getPaymentSettings();
        $stripe = array(
            'secret_key' => $this->paymentSettings['privateKey'],
            'publishable_key' => $this->paymentSettings['publishableKey']
        );
        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $orderId = $session->metadata->order_id;
        if (empty($orderId)) {
            Message::addErrorMessage(Label::getLabel('STRIPE_INVALID_OrderId', $this->siteLangId));
            FatApp::redirectUser($session->cancel_url);
        }
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if ($orderInfo["order_is_paid"] == Order::ORDER_IS_PAID) {
            FatApp::redirectUser(CommonHelper::generateUrl('Custom', 'paymentSuccess'));
        }
        $paymentGatewayCharge = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $payableAmount = $this->formatPayableAmount($paymentGatewayCharge);
        $payment_comments = '';
        $totalPaidMatch = $session->amount_total == $payableAmount;
        if (strtolower($session->payment_status) != 'paid') {
            $payment_comments .= "STRIPE_PAYMENT :: Status is: " . strtolower($session->payment_status) . "\n\n";
        }
        if (!$totalPaidMatch) {
            $payment_comments .= "STRIPE_PAYMENT :: TOTAL PAID MISMATCH! " . strtolower($session->amount_total) . "\n\n";
        }
        if (strtolower($session->payment_status) == 'paid' && $totalPaidMatch) {
            $orderPaymentObj->addOrderPayment($this->paymentSettings["pmethod_code"], $sessionId, $paymentGatewayCharge, 'Received Payment', serialize($session));
        } else {
            $orderPaymentObj->addOrderPaymentComments($payment_comments);
            FatApp::redirectUser($session->cancel_url);
        }
        FatApp::redirectUser(CommonHelper::generateUrl('Custom', 'paymentSuccess'));
    }

}
