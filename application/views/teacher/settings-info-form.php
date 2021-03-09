<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'frmSettings');
$frm->setFormTagAttribute('class','form');
$frm->setFormTagAttribute('onsubmit', 'setUpTeacherSettings(this); return(false);');

$defaultSlot = FatApp::getConfig('conf_default_paid_lesson_duration', FatUtility::VAR_STRING, 60);

if(!empty($defaultSlot)){
	$defaultSlotFld = $frm->getField('duration['.$defaultSlot.']');
	if($defaultSlotFld){
		$defaultSlotFld->setFieldTagAttribute('checked', 'checked');
		$defaultSlotFld->setFieldTagAttribute('disabled', 'disabled');
	}
}
?>
<div class="section-head">
	<div class="d-flex justify-content-between align-items-center">
		<div><h5 class="page-heading"><?php echo Label::getLabel('LBL_Price'); ?></h5></div>
	</div>
</div>
<?php //echo $frm->getFormHtml(); ?>
<?php echo $frm->getFormTag(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="field-set">
                <div class="caption-wraper">&nbsp;</div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <span class="checkbox">
                        <?php
                            if (!empty($frm->getField('us_is_trial_lesson_enabled'))) {
                                $frm->getField('us_is_trial_lesson_enabled')->htmlAfterField = '<i class="input-helper"></i>';
                                $trialFld = $frm->getFieldHtml('us_is_trial_lesson_enabled');
                                echo str_replace(array('<label>', '</label>'), array(), $trialFld);
                            }
                        ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h6><?php echo Label::getLabel('LBL_Lesson_Durations') ?></h6>
        </div>
    </div>
    
    <?php $lesson_durations = explode(',', FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_STRING, 60)); ?>
    
    <div class="row">
        <?php foreach($lesson_durations as $lesson_duration): ?>
        <div class="col-md-3">
            <div class="field-set">
                <div class="caption-wraper">&nbsp;</div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <span class="checkbox">
                        <?php $frm->getField('duration['.$lesson_duration.']')->htmlAfterField = '<i class="input-helper"></i>';
                        $durFld = $frm->getFieldHtml('duration['.$lesson_duration.']');
                        echo str_replace(array('<label>', '</label>'), array(), $durFld); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php foreach($lesson_durations as $lesson_duration): ?>
    <div class="row">
        <div class="col-sm-6 fld_wrapper-js">
            <div class="table-box-bordered box-signle-price">
                <h5><?php echo Label::getLabel('LBL_Single_Lesson') ?> (<?php echo sprintf(Label::getLabel('LBL_%d_minutes'), $lesson_duration) ?>)</h5>
                <table class="table-pricing">
                    <tbody>
                    <?php $uTeachLangs = array_unique(array_column($tprices, 'utl_slanguage_id')); ?>
                    <?php foreach($uTeachLangs as $uTeachLang): 
                    if (!isset($teachLangs[$uTeachLang])) continue;
                    $single_lesson_name = 'utl_single_lesson_amount['.$uTeachLang.']['.$lesson_duration.']';
                    ?>
                    <tr>
                        <td width="70%"><?php echo $teachLangs[$uTeachLang]['tlanguage_name'] ?></td>
                        <td><?php echo $frm->getFieldHtml($single_lesson_name) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-sm-6 fld_wrapper-js">
            <div class="table-box-bordered box-bulk-price">
                <h5><?php echo Label::getLabel('LBL_Bulk_Lesson') ?> (<?php echo sprintf(Label::getLabel('LBL_%d_minutes'), $lesson_duration) ?>)</h5>
                <table class="table-pricing">                   
                    <tbody>
                    <?php $uTeachLangs = array_unique(array_column($tprices, 'utl_slanguage_id')); ?>
                    <?php foreach($uTeachLangs as $uTeachLang): 
                    if (!isset($teachLangs[$uTeachLang])) continue;
                    $bulk_lesson_name = 'utl_bulk_lesson_amount['.$uTeachLang.']['.$lesson_duration.']';
                    ?>
                    <tr>
                        <td width="70%"><?php echo $teachLangs[$uTeachLang]['tlanguage_name'] ?></td>
                        <td><?php echo $frm->getFieldHtml($bulk_lesson_name) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
        
    </div>
    
    <?php endforeach; ?>
    
    
    <div class="row">
        <div class="fld_wrapper-js col-md-4">
            <div class="field-set">
                <div class="caption-wraper"><label class="field_label">&nbsp;</label></div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <?php echo $frm->getFieldHtml('submit') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php echo $frm->getExternalJs(); ?>