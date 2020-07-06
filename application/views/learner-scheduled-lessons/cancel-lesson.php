<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'cancelLessonSetup(this); return(false);');
$cancelBtn = $frm->getField('reset');
$cancelBtn->setFieldTagAttribute('onclick','closeCancelLessonPopup(this); return(false);');
$frm->setFormTagAttribute('onsubmit', 'cancelLessonSetup(this); return(false);');
?>
<div class="box -padding-20">
	<h4><?php echo Label::getLabel('LBL_Cancel_Plan'); ?></h4>
	<?php echo $frm->getFormHtml(); ?>
</div>
