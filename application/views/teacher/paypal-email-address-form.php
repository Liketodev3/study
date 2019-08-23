<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'setUpPaypalInfo(this); return(false);');

$ub_paypal_email_address = $frm->getField('ub_paypal_email_address');
$ub_paypal_email_address->developerTags['col'] = 8;
?>

	<div class="section-head">
	 <div class="d-flex justify-content-between align-items-center">
		 <div><h4 class="page-heading"><?php echo Label::getLabel('LBL_Payment_Method'); ?></h4></div>
		 <div>
			<!--a href="#" class="btn btn--secondary btn--small"><?php //echo Label::getLabel('LBL_Add_New'); ?></a-->
		 </div>
	 </div>
	</div>
	<div class="tabs-small tabs-offset">
		<ul id="innerTabs">
			<li class="is-active"><a href="javascript:void(0);" onclick="paypalEmailAddressForm();"><?php echo Label::getLabel('LBL_Paypal'); ?></a></li>
			<li><a href="javascript:void(0);" onclick="bankInfoForm();"><?php echo Label::getLabel('LBL_Bank_Account'); ?></a></li>
		</ul>
	</div>
		<?php echo $frm->getFormHtml();?>
