<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'frmSettings');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setUpTeacherSettings(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 3;
// prx($frm);
$defaultSlot = FatApp::getConfig('conf_default_paid_lesson_duration', FatUtility::VAR_STRING, 60);
if (!empty($defaultSlot)) {
    $defaultSlotFld = $frm->getField('duration[' . $defaultSlot . ']');
    if ($defaultSlotFld) {
        $defaultSlotFld->setFieldTagAttribute('checked', 'checked');
        $defaultSlotFld->setFieldTagAttribute('disabled', 'disabled');
    }
}
$lessonDurations = CommonHelper::getPaidLessonDurations();
$userTeachLangData = array_column($userToTeachLangRows, 'teachLangName', 'utl_id');

$updatePrice = $frm->addFloatField(Label::getLabel('Lbl_Upadte_price'), 'price_update');
$updatePrice->requirements()->setRange(1, 99999);
$updatePrice->addFieldTagAttribute('onchange','updatePrice(this, this.form);');
$updatePrice->addFieldTagAttribute('placeholder','0.00');
?>
<div class="content-panel__head">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h5><?php echo Label::getLabel('LBL_Manage_Prices'); ?></h5>
        </div>
        <div></div>
    </div>
</div>
<div class="content-panel__body">
    <?php echo $frm->getFormTag(); ?>
        <div class="action-bar d-flex justify-content-center">
            <div class="selection-tabs">
                <?php 
                    $slectedDuration = [];
                    foreach ($lessonDurations as $key => $value) { 
                       $durationFld = $frm->getField('duration[' . $value . ']');
                       $checkedStr = '';
                       if($durationFld->checked){
                            $slectedDuration[$value] = $value;
                            $checkedStr = 'checked';
                       }
                ?>
                    <label class="selection-tabs__label">
                        <input type="checkbox" name="<?php echo $durationFld->getName(); ?>" <?php echo $checkedStr; ?>  <?php  echo ($defaultSlot == $value) ? 'disabled' : ''; ?>  class="selection-tabs__input">
                        <div class="selection-tabs__title"><?php echo $value; ?><span><?php echo Label::getLabel('Lbl_Mins'); ?></span></div>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="pricing-wrapper">
            <div class="form__body">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-10 col-xl-12">
                       <?php foreach ($slectedDuration as $lessonDuration) { ?>
                            <div class="price-box is--active">
                                <div class="price-box__head ">
                                    <div>
                                        <span><?php echo sprintf(Label::getLabel('LBL_Time_Slot_(%d_Mins)'),$lessonDuration); ?></span>
                                    </div>
                                    <div>
                                        <div class="common-slot-price d-flex align-items-center">
                                            <label class="field_label mb-0"><?php echo Label::getLabel('Lbl_add_price') ?></label>
                                            <?php echo $updatePrice->getHTML(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="price-box__body">
                                    <?php foreach ($slabs as $slab) { ?>
                                        <div class="slab-wrapper">
                                            <div class="slab__head">
                                                <h6><?php echo sprintf(Label::getLabel('LBL_Slab_%d_to_%d_Lessons'),$slab['prislab_min'],$slab['prislab_max']) ?></h6>
                                            </div>
                                            <div class="slab__body">
                                                <div class="row align-items-center justify-content-between">
                                                    <?php 
                                                        foreach ($userTeachLangData  as $uTeachLangId => $uTeachLang) { 
                                                        $filedName = 'teach_lang_price[' . $lessonDuration . '][' . $slab['prislab_id'] . '][' . $uTeachLangId . ']';
                                                        $priceField = $frm->getField($filedName);
                                                        $priceField->addFieldTagAttribute('class', 'slab-price-js');
                                                    ?>
                                                        <div class="col-6 col-md-3 col-sm-3 col-lg-3 col-xl-3">
                                                            <div class="field-wrapper">
                                                                <label class="field_label"><?php echo $uTeachLang; ?></label>
                                                                <?php
                                                                    echo $priceField->getHTML();
                                                                 ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                     <?php  } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="form__actions">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <input type="button" name="Back" value="Back">
                    </div>
                    <div>
                        <input type="submit" name="Save" value="Save">
                        <input type="button"  name="Next" value="Next">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php 
        echo $frm->getExternalJs();
    ?>
</div>
<script>
var confirmLabel = `<?php echo Label::getLabel('Lbl_Are_You_Sure_To_Update_This_Price!'); ?>`
function updatePrice (fieldObj, form){
	if (!$(form).validate()) return;
    if(!confirm(confirmLabel)) return;
	let price = $(fieldObj).val();
	$(fieldObj).parents('.price-box').find('.slab-price-js').val(price);
};

</script>

<?php 
//echo $frm->getFormHtml();

