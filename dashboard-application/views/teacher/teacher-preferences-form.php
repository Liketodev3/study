<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$teacherPreferencesFrm->setFormTagAttribute('id', 'teacherPreferencesFrm');
$teacherPreferencesFrm->setFormTagAttribute('class','form');
$teacherPreferencesFrm->setFormTagAttribute('onsubmit', 'setupTeacherPreferences(this); return(false);');
$teacherPreferencesFrm->developerTags['colClassPrefix'] = 'col-md-';
$teacherPreferencesFrm->developerTags['fld_default_col'] = 12;
$teach_lang = $teacherPreferencesFrm->getField('teach_lang');
$teach_lang->value = CommonHelper::htmlEntitiesDecode($teachLang);
$teach_lang->developerTags['col'] = 6;
?>
<div class="section-head">
 <div class="d-flex justify-content-between align-items-center">
	 <div><h5 class="page-heading"><?php echo Label::getLabel('LBL_Skills'); ?></h5></div>
 </div>
</div>
<?php echo $teacherPreferencesFrm->getFormHtml();?>
