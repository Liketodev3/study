<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'pwdFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 7;
$frm->setFormTagAttribute('autocomplete', 'off');
$frm->setFormTagAttribute('onsubmit', 'setUpPassword(this); return(false);');
?>

<div class="section-head">
		 <div class="d-flex justify-content-between align-items-center">
			 <div><h4 class="page-heading"><?php echo Label::getLabel('LBL_Change_Password_or_Email'); ?></h4></div>
		 </div>
</div>
<div class="tabs-small tabs-offset tabs-scroll-js">
    <ul>
        <li class="is-active"><a href="javascript::void(0)" onclick="changePasswordForm()" ><?php echo Label::getLabel('LBL_Password'); ?></a></li>
		<li><a href="javascript::void(0)" onclick="changeEmailForm()" ><?php echo Label::getLabel('LBL_Email'); ?></a></li>
     </ul>
</div>
<?php echo $frm->getFormHtml();