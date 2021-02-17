<?php

class PaymentController extends MyAppController
{
    protected $systemCurrencyCode;
    protected $systemCurrencyId;
    public $settings = [];

    public function __construct($action)
    {
        parent::__construct($action);
        $currency_id = FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1);

        $currency = new Currency($currency_id);
        if(!$currency->loadFromDb()){
            $this->setErrorAndRedirect(Label::getLabel('MSG_DEFAULT_CURRENCY_NOT_SET', $this->siteLangId));
        }

        $this->systemCurrencyId = $currency_id;
        $this->systemCurrencyCode = strtoupper($currency->getFldValue('currency_code'));

        $this->set('systemCurrencyCode', $this->systemCurrencyCode);
        $this->loadPaymenMethod();
    }

    private function loadPaymenMethod(): void
    {
        $pmObj = new PaymentSettings($this->keyName);
        if (!$this->settings = $pmObj->getPaymentSettings()) {
            $this->setErrorAndRedirect($pmObj->getError());
        }
        
    }

    protected function setErrorAndRedirect(string $msg, $redirect = true)
    {
        if(FatUtility::isAjaxCall()){
            FatUtility::dieJsonError($msg);
        }
        Message::addErrorMessage($msg);
        if($redirect){
            CommonHelper::redirectUserReferer();
        }
    }
}
