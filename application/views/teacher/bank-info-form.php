<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');

$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$frm->setFormTagAttribute('onsubmit', 'setUpBankInfo(this); return(false);');

$ub_bank_address = $frm->getField('ub_bank_address');
$ub_bank_address->developerTags['col'] = 12;
?>
<?php if($activePaypalPayout){ ?>
	<div class="section-head">
	 <div class="d-flex justify-content-between align-items-center">
		 <div><h4 class="page-heading"><?php echo Label::getLabel('LBL_Payment_Method'); ?></h4></div>
	 </div>
	</div>

	<div class="tabs-small tabs-offset">
		<ul id="innerTabs">
			<li class="is-active"><a href="javascript:void(0);" onclick="bankInfoForm();"><?php echo Label::getLabel('LBL_Bank_Account'); ?></a></li>
			<li><a href="javascript:void(0);" onclick="paypalEmailAddressForm();"><?php echo Label::getLabel('LBL_Paypal'); ?></a></li>
		</ul>
	</div>
<?php } ?>
		<?php echo $frm->getFormHtml();?>
