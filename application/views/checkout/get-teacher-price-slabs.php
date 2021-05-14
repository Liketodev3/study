<?php
defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20" style="margin-bottom:30px;">
	<h3><?php echo Label::getLabel('LBL_Slabs'); ?></h3>
	<p><?php echo Label::getLabel('LBL_How_many_lessons_would_you_like_to_purchase?'); ?></p>

	<div class="selection-list">
		<ul>
			<?php
			foreach ($slabs as $slab) {
                $price = $slab['ustelgpr_price'];
				$percentage = CommonHelper::getPercentValue($slab['top_percentage'], $price);
				$price = $price - $percentage;
				$title = Label::getLabel('LBL_{min}_to_{max}_Lesson(s)'); //5 to 9 hrs
				$title = str_replace(['{min}', '{max}'], [$slab['prislab_min'], $slab['prislab_max']], $title);
			?>
				<li class="<?php echo ($slab['isSlapCollapse']) ? 'is-active' : ''; ?>">
					<label class="selection">
						<span class="radio">
							<input onClick="addToCart('<?php echo $cartData['teacherId']; ?>', '<?php echo  $cartData['languageId']; ?>','<?php echo $cartData['lessonDuration']; ?>', '<?php echo $slab['prislab_min']; ?>');" type="radio" <?php echo ($slab['isSlapCollapse']) ? 'checked="checked"' : ''; ?> name="lessonQty" value="<?php echo $slab['prislab_min']; ?>"><i class="input-helper"></i>
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