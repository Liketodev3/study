<?php
class PaypalPayout {

	private $access_token_url_test = 'https://api.sandbox.paypal.com/v1/oauth2/token';
    private $access_token_url_live = 'https://api.paypal.com/v1/oauth2/token';
    private $payout_url_test = 'https://api.sandbox.paypal.com/v1/payments/payouts';
    private $payout_url_live = 'https://api.paypal.com/v1/payments/payouts';

    private $db;
    private $error;
	private $isError;
    private $commonLangId;

    public function __construct() {
        $this->db = FatApp::getDb();
        $this->error = '';
        $this->commonLangId = CommonHelper::getLangId();
    }

    function getError() {
        return $this->error;
    }

	function isError() {
		return $this->isError;
	}

	public function accessToken($clientid, $clientsecret) {

		if (empty($clientid) || empty($clientsecret)) {
			return false;
		}

		$actionUrl = (FatApp::getConfig('CONF_TRANSACTION_MODE',FatUtility::VAR_BOOLEAN,false) == true) ? $this->access_token_url_live : $this->access_token_url_test;
		//$result = false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $actionUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERPWD, $clientid . ':' . $clientsecret);

		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Accept-Language: en_US';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}

	public function releasePayout(string $token, array $requestData) : array
	{
		$requestData = json_encode($requestData);
		$actionUrl = (FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false) == true) ? $this->payout_url_live : $this->payout_url_test;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $actionUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Bearer '. $token;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->isError= true;
			$this->error =  'Error:' . curl_error($ch);
			return array();
		}

		curl_close($ch);

		$accessTokenResponse = json_decode($result, true);
		if (!array_key_exists('access_token', $accessTokenResponse)) {
			$this->isError= true;
			$this->error =  $accessTokenResponse['error'].' : '.$accessTokenResponse['error_description'];
			return array();
		}

		return $accessTokenResponse;
	}


	public function getSettings() : array
	{
		$keyName = "PaypalStandard";
		$pmObj = new PaymentSettings($keyName);
		$paymentSettings = $pmObj->getPaymentSettings();
		$paypal_client_id = $paymentSettings['paypal_client_id'];
		$paypal_client_secret = $paymentSettings['paypal_client_secret'];
		if (empty($paypal_client_id) || empty($paypal_client_secret)) {
			$this->isError= true;
			$this->error = Label::getLabel('LBL_Paypal_Client_id_And_Secret_is_required_for_payout',$this->adminLangId);
			return array();
		}
		return  [
			'paypal_client_id' => $paypal_client_id;
			'paypal_client_secret' => $paypal_client_id;
		];
	}

}
