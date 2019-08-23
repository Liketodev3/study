<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'EmailFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 7;
$frm->setFormTagAttribute('autocomplete', 'off');
$frm->setFormTagAttribute('onsubmit', 'setUpEmail(this); return(false);');
?>

<div class="section-head">
	<div class="d-flex justify-content-between align-items-center">
		<div>
			<h4 class="page-heading"><?php echo Label::getLabel('LBL_Change_Password_or_Email'); ?></h4>
		</div>
	</div>
	<?php if( !empty( $userPendingRequest ) ) { 
	?>
	<div class="system_message alert--positioned-top-full alert  alert--warning" style="">
        <a class="closeMsg" href="javascript:void(0)"></a>
        <div class="content">
            <div class="div_info">
				<ul>
					<li><?php echo Label::getLabel('LBL_Email_Verification_pending_for_email_change'); ?></li>
				</ul>
			</div>        
		</div>
    </div>	
	<?php
	}
	?>
	
</div>
<div class="tabs-small tabs-offset tabs-scroll-js">
    <ul>
        <li><a href="javascript::void(0)" onclick="changePasswordForm()" ><?php echo Label::getLabel('LBL_Password'); ?></a></li>
		<li class="is-active"><a href="javascript::void(0)" onclick="changeEmailForm()" ><?php echo Label::getLabel('LBL_Email'); ?></a></li>
    </ul>
</div>
<?php echo $frm->getFormHtml();