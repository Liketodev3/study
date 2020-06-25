<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$selectFld = $frm->getField('issues_to_report');
$selectFld->setOptionListTagAttribute('class', 'listing listing--vertical listing--selection isuueOptions');
$selectFld->setFieldTagAttribute('data-add-error-in-parent',1);
$selectFld->captionWrapper = ['','<span class="spn_must_field">*</span>'];
$selectFld->fieldWrapper = ['<div class="form__list form__list--check">','<div>'];
$frm->setFormTagAttribute('onsubmit', 'issueReportedSetup(this); return(false);');
?>
<div class="box -padding-20">
	<h4><?php echo Label::getLabel('LBL_Issue_Reported'); ?></h4>
		<?php echo $frm->getFormHtml(); ?>
</div>
