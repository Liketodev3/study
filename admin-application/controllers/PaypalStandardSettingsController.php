<?php
class PaypalStandardSettingsController extends PaymentSettingsController{
	private $keyName = "PaypalStandard";
	
	public function index(){ 		
		$paymentSettings = $this->getPaymentSettings($this->keyName);
		
		$frm = $this->getForm();
		$frm->fill($paymentSettings);
		
		$this->set('frm', $frm);
		$this->set('paymentMethod', $this->keyName);
		$this->_template->render(false,false);		
	}	
	
	public function setup(){
		$frm = $this->getForm();
		$this->setUpPaymentSettings($frm, $this->keyName);		
	}
		
	private function getForm() {
		$frm = new Form('frmPaymentMethods');	
		$frm->addRequiredField(Label::getLabel('LBL_Merchant_Email',$this->adminLangId), 'merchant_email');	
		
		$paymentGatewayStatus = Order::getPaymentStatusArr($this->adminLangId);	
		$frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Initial)',$this->adminLangId),'order_status_initial',$paymentGatewayStatus)->requirement->setRequired(true);
		$frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Pending)',$this->adminLangId),'order_status_pending',$paymentGatewayStatus)->requirement->setRequired(true);
		$frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Processed)',$this->adminLangId),'order_status_processed',$paymentGatewayStatus)->requirement->setRequired(true);
		$frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Completed)',$this->adminLangId),'order_status_completed',$paymentGatewayStatus)->requirement->setRequired(true);
		$frm->addSelectBox(Label::getLabel('LBL_Order_Status_(Others)',$this->adminLangId),'order_status_others',$paymentGatewayStatus)->requirement->setRequired(true);
		
		$frm->addSubmitButton('&nbsp;','btn_submit',Label::getLabel('LBL_Save_Changes',$this->adminLangId));
		return $frm;
	}
}
