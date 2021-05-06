<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$teacherPreferencesFrm->setFormTagAttribute('id', 'teacherPreferencesFrm');
$teacherPreferencesFrm->setFormTagAttribute('class','form');
$teacherPreferencesFrm->setFormTagAttribute('onsubmit', 'setupTeacherPreferences(this, false); return(false);');
$teacherPreferencesFrm->developerTags['colClassPrefix'] = 'col-md-';
$teacherPreferencesFrm->developerTags['fld_default_col'] = 12;
$teach_lang = $teacherPreferencesFrm->getField('teach_lang');
$teach_lang->value = CommonHelper::htmlEntitiesDecode($teachLang);
$teach_lang->developerTags['col'] = 6;
$getAllfields = $teacherPreferencesFrm->getAllFields();
$teachLangField = $teacherPreferencesFrm->getField('teach_lang');

$backBtn = $teacherPreferencesFrm->getField('btn_back');
$backBtn->addFieldTagAttribute('onclick','teacherQualification()');

$nextBtn = $teacherPreferencesFrm->getField('btn_next');
$nextBtn->addFieldTagAttribute('onclick','setupTeacherPreferences(this.form, true); return(false);');
?>
<div class="content-panel__head border-bottom margin-bottom-5">
	<div class="d-flex align-items-center justify-content-between">
		<div><h5><?php echo Label::getLabel('LBL_Skills'); ?></h5></div>
		<div></div>
	</div>
	
</div>
<div class="content-panel__body">
	<?php echo $teacherPreferencesFrm->getFormTag(); ?>
		<div class="form__body">
			<div class="row">
				<div class="col-md-6">
					<div class="field-set">
						<div class="caption-wraper">
							<label class="field_label"> <?php echo $teachLangField->getCaption(); ?>
							</label>
						</div>
						<div class="field-wraper">
							<div class="field_cover">
							<?php echo $teachLangField->getHTML(); ?>
						</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
				foreach ($getAllfields as $key => $field) {
					if( in_array($field->getName(), ['teach_lang', 'submit', 'btn_next', 'btn_back'])) {
						continue;
					}
					$field->developerTags['cbHtmlBeforeCheckbox'] = '<span class="checkbox">';
					$field->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i></span>';
			?>
				<div class="row">
					<div class="col-md-12">
						<div class="field-set">
							<div class="caption-wraper">
								<label class="field_label"> <?php echo $field->getCaption(); ?></label>
							</div>
							<div class="field-wraper">
								<div class="field_cover">
									<?php echo $field->getHTML(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php  } ?>
		</div>
		<div class="form__actions">
			<div class="d-flex align-items-center justify-content-between">
				<div>
				<?php echo $backBtn->getHTML('btn_back'); ?>
				</div>
				<div>
					<?php echo $teacherPreferencesFrm->getFieldHTML('submit'); ?>
					<?php echo $teacherPreferencesFrm->getFieldHTML('btn_next'); ?>
				</div>
			</div>
		</div>
	</form>
</div>
<?php echo $teacherPreferencesFrm->getExternalJS(); ?>

