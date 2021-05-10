<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'frmSettings');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setUpTeacherSettings(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 3;

$defaultSlot = FatApp::getConfig('conf_default_paid_lesson_duration', FatUtility::VAR_STRING, 60);
if (!empty($defaultSlot)) {
    $defaultSlotFld = $frm->getField('duration[' . $defaultSlot . ']');
    if ($defaultSlotFld) {
        $defaultSlotFld->setFieldTagAttribute('checked', 'checked');
        $defaultSlotFld->setFieldTagAttribute('disabled', 'disabled');
    }
}
?>
<div class="section-head">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="page-heading"><?php echo Label::getLabel('LBL_Price'); ?></h5>
        </div>
    </div>
</div>
<?php echo $frm->getFormHtml();