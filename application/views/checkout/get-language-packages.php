<?php
defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20" style="margin-bottom:30px;">
	<h3><?php echo Label::getLabel('LBL_Lessons_Packages'); ?></h3>
	<p><?php echo Label::getLabel('LBL_How_many_lessons_would_you_like_to_purchase?'); ?></p>

	<div class="selection-list">
		<ul>
			<?php
			$bulkLessonAmount =  $selectedLang['utl_bulk_lesson_amount'];
			$singleLessonAmount =  $selectedLang['utl_single_lesson_amount'];
			if (!empty($teacherOffer['top_single_lesson_price']) && !empty($teacherOffer['top_bulk_lesson_price'])) {
				$bulkLessonAmount =  $teacherOffer['top_bulk_lesson_price'];
				$singleLessonAmount =  $teacherOffer['top_single_lesson_price'];
			}
			foreach ($lessonPackages as $lpackage) { ?>
				<li class="<?php echo ($cartData['lpackage_id'] == $lpackage['lpackage_id']) ? 'is-active' : ''; ?>">
					<label class="selection">
						<span class="radio">
							<input onClick="addToCart('<?php echo $cartData['user_id'] ?>', '<?php echo $lpackage['lpackage_id']; ?>', '<?php echo $languageId; ?>', '', '', 0, '<?php echo $cartData['lessonDuration'] ?>');" type="radio" <?php echo ($cartData['lpackage_id'] == $lpackage['lpackage_id']) ? 'checked="checked"' : ''; ?> name="lpackage_qty" value="<?php echo $lpackage['lpackage_id']; ?>"><i class="input-helper"></i>
						</span>
						<span class="selection__item">
							<?php echo $lpackage['lpackage_title'] . " : " . CommonHelper::int($lpackage['lpackage_lessons']) . " " . Label::getLabel('LBL_Lesson(s)'); ?> <small class="-float-right"> <?php echo CommonHelper::displayMoneyFormat(($lpackage['lpackage_lessons'] > 1) ? $bulkLessonAmount : $singleLessonAmount); ?>/ <?php echo Label::getLabel('LBL_Per_Lesson'); ?></small>
						</span>
					</label>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>