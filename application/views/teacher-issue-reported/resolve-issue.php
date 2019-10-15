<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$selectFld = $frm->getField('issues_to_report');
$selectFld->setOptionListTagAttribute('class', 'listing listing--vertical listing--selection isuueOptions');

$frm->setFormTagAttribute('onsubmit', 'issueResolveSetup(this); return(false);');
?>
<div class="box -padding-20">
	<h4><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></h4>
	<?php echo $frm->getFormHtml(); ?>
</div>