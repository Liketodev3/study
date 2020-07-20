<?php
class PaypalStandardSettingsController extends PaymentSettingsController
{
    private $keyName = "PaypalStandard";

    public function index()
    {
        $paymentSettings = $this->getPaymentSettings($this->keyName);

        $frm = $this->getForm();
        $frm->fill($paymentSettings);

        $this->set('frm', $frm);
        $this->set('paymentMethod', $this->keyName);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $frm = $this->getForm();
        $this->setUpPaymentSettings($frm, $this->keyName);
    }

    private function getForm()
    {
        $frm = new Form('frmPaymentMethods');
        $frm->addRequiredField(Label::getLabel('LBL_Merchant_Email', $this->adminLangId), 'merchant_email');

        $paymentGatewayStatus = Order::getPaymentStatusArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Initial)', $this->adminLangId), 'order_status_initial', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Pending)', $this->adminLangId), 'order_status_pending', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Processed)', $this->adminLangId), 'order_status_processed', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Completed)', $this->adminLangId), 'order_status_completed', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Others)', $this->adminLangId), 'order_status_others', $paymentGatewayStatus)->requirement->setRequired(true);

        $fld = $frm->addTextBox(Label::getLabel('LBL_Client_Id',$this->adminLangId), 'paypal_client_id');
		$fld->htmlAfterField = '<small>'. Label::getLabel('LBL_Required_for_Paypal_Payout', $this->adminLangId) .'</small>';

		$fld1 = $frm->addTextBox(Label::getLabel('LBL_Client_Secret',$this->adminLangId), 'paypal_client_secret');
		$fld1->htmlAfterField = '<small>'. Label::getLabel('LBL_Required_for_Paypal_Payout', $this->adminLangId) .'</small>';

        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
