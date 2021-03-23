<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'groupClassesFrm');
$frm->setFormTagAttribute('class', 'form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
$dateformat = FatApp::getConfig('CONF_DATEPICKER_FORMAT', FatUtility::VAR_STRING, 'Y-m-d');
$timeformat = FatApp::getConfig('CONF_DATEPICKER_FORMAT_TIME', FatUtility::VAR_STRING, 'H:i');
$frm->getField('grpcls_start_datetime')->setFieldTagAttribute('data-fatdatetimeformat', $dateformat . ' ' . $timeformat);
$frm->getField('grpcls_end_datetime')->setFieldTagAttribute('data-fatdatetimeformat', $dateformat . ' ' . $timeformat);
$submit = $frm->getField('submit');
$submit->developerTags['col'] = 12;
$fld = $frm->getField('grpcls_max_learner');
$fld->developerTags['col'] = 12;
?>
<div class="box -padding-20">
	<!--page-head start here-->

	<div class="tabs-small tabs-offset tabs-scroll-js">
		<ul>
			<li class="is-active"><a href="javascript:void(0)"><?php echo Label::getLabel('LBL_General'); ?></a></li>
			<?php foreach ($languages as $langId => $language) { ?>
				<li><a href="javascript:void(0)" <?php if ($grpclsId > 0) { ?> onclick="editGroupClassLangForm(<?php echo $grpclsId ?>, <?php echo $langId; ?>);" <?php } ?>><?php echo $language['language_name']; ?></a></li>

			<?php } ?>
		</ul>
	</div>

	<div class="d-flex justify-content-between align-items-center">
		<div>
			<h4><?php echo Label::getLabel("LBL_Add_Group_Class") ?></h4>
		</div>
		<div><a class="btn btn--small" href="javascript:void(0);" onclick="searchGroupClasses(document.frmSrch);"><?php echo Label::getLabel("LBL_Cancel") ?></a></div>
	</div>


	<span class="-gap"></span>
	<!--page-head end here-->
	<?php echo $frm->getFormHtml(); ?>
</div>