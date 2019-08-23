<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'frmSettings');
$frm->setFormTagAttribute('class','form');
$frm->setFormTagAttribute('onsubmit', 'setUpTeacherSettings(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$us_is_trial_lesson_enabled = $frm->getField('us_is_trial_lesson_enabled');
$us_is_trial_lesson_enabled->developerTags['col'] = 12;
?>
<div class="section-head">
	<div class="d-flex justify-content-between align-items-center">
		<div><h5 class="page-heading"><?php echo Label::getLabel('LBL_Price'); ?></h5></div>
	</div>
</div>
<?php echo $frm->getFormHtml();?>