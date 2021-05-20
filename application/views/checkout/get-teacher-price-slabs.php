<?php
defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$minValue = min(array_column($slabs, 'ustelgpr_min_slab', 'ustelgpr_min_slab'));
$maxValue = max(array_column($slabs, 'ustelgpr_max_slab', 'ustelgpr_max_slab'));


?>
<div class="box -padding-20" style="margin-bottom:30px;">
	<h3><?php echo Label::getLabel('LBL_Slabs'); ?></h3>
	<p><?php echo Label::getLabel('LBL_How_many_lessons_would_you_like_to_purchase?'); ?></p>
	<div>
	<div class="row">
    <div class="col-3">
        <div class="field-set">
            <div class="caption-wraper">
				<label class="field_label"><?php echo Label::getLabel('LBL_Lesson(s)'); ?>
					<span class="spn_must_field">*</span>
			</label>
            </div>
            <div class="field-wraper">
                <div class="field_cover">
					<input type="number" id="lessonQty" name="lessonQty" min="<?php echo $minValue; ?>" max="<?php echo $maxValue; ?>" value="<?php echo $cartData['lessonQty'];  ?>">
			</div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="field-set">
            <div class="field-wraper">
				<label class="field_label"></label>
			<div>
                <div class="field_cover">
					<input class="btn btn--secondary btn--sm" onClick="addToCart('<?php echo $cartData['teacherId']; ?>', '<?php echo  $cartData['languageId']; ?>','<?php echo $cartData['lessonDuration']; ?>', document.getElementById('lessonQty').value);" type="submit" name="btn_submit" value="Update"></div>
            </div>
        </div>
    </div>
</div>
	</div>
	<div class="selection-list">
		<ul>
			<?php
			foreach ($slabs as $slab) {
				$price =  FatUtility::float($slab['ustelgpr_price']);
				$percentage = CommonHelper::getPercentValue($slab['top_percentage'], $price);
				$price = $price - $percentage;
				$title = Label::getLabel('LBL_{min}_to_{max}_Lesson(s)');
				$title = str_replace(['{min}', '{max}'], [$slab['ustelgpr_min_slab'], $slab['ustelgpr_max_slab']], $title);
			?>
				<li class="<?php echo ($slab['isSlapCollapse']) ? 'is-active' : ''; ?>">
					<label class="selection">
						<span class="radio">
							<!-- <input onchange="addToCart(<?php //echo $cartData['teacherId'].', '.$cartData['languageId'].', '.$cartData['lessonDuration'] .', '.$slab['ustelgpr_min_slab']; ?>);" type="radio" <?php //echo ($slab['isSlapCollapse']) ? 'checked="checked"' : ''; ?> name="lessonQty-pack" value="<?php //echo $slab['ustelgpr_min_slab']; ?>"> -->
							<!-- <i class="input-helper"></i> -->
						</span>
						<span class="selection__item">
							<?php echo $title; ?> <small class="-float-right"> <?php echo CommonHelper::displayMoneyFormat($price); ?>/ <?php echo Label::getLabel('LBL_Per_Lesson'); ?></small>
						</span>
					</label>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>