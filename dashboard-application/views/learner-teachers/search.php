<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="table-scroll">
	<table class="table table--styled table--responsive table--aligned-middle">
		<tr class="title-row">
			<th><?php echo $teacherLabel = Label::getLabel('LBL_Teacher'); ?></th>
			<th><?php echo $teachesLabel = Label::getLabel('LBL_Teaches'); ?></th>
			<th><?php echo $ratingLabel = Label::getLabel('LBL_Average_Rating'); ?></th>
			<th><?php echo $loclLabel = Label::getLabel('LBL_Lock_(Single/Bulk_Price)'); ?></th>
			<th><?php echo $scheduledLabel = Label::getLabel('LBL_Scheduled'); ?></th>
			<th><?php echo $pastLabel = Label::getLabel('LBL_Past'); ?></th>
			<th><?php echo $unscheduledLabel = Label::getLabel('LBL_Unscheduled'); ?></th>
			<th><?php echo $actionLabel = Label::getLabel('LBL_Actions'); ?></th>
		</tr>
		<?php
		foreach ($teachers as $teacher) {
			$teacherDetailPageUrl = CommonHelper::generateUrl('teachers', 'view', array($teacher['user_url_name']), CONF_WEBROOT_FRONTEND); ?>
			<tr>
				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $teacherLabel; ?></div>
						<div class="flex-cell__content">
							<div class="profile-meta">
								<div class="profile-meta__media">
									<a title="<?php echo $teacher['teacherFullName']; ?>" href="<?php echo $teacherDetailPageUrl; ?>">
										<span class="avtar avtar--small" data-title="<?php echo CommonHelper::getFirstChar($teacher['teacherFname']); ?>">
											<?php
											if (true == User::isProfilePicUploaded($teacher['teacherId'])) {
												$img = CommonHelper::generateUrl('Image', 'user', array($teacher['teacherId'], 'normal', 1), CONF_WEBROOT_FRONT_URL) . '?' . time();
												echo '<img src="' . $img . '"  alt="' . $teacher['teacherFname'] . '"/>';
											}
											?>
										</span>
									</a>
								</div>
								<div class="profile-meta__details">
									<p class="bold-600 color-black"><?php echo $teacher['teacherFname']; ?></p>
									<p class="small"><?php echo $teacher['teacherCountryName']; ?></p>
								</div>
							</div>
						</div>
					</div>
				</td>
				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $teachesLabel; ?></div>
						<div class="flex-cell__content"><?php echo $teacher['teacherTeachLanguageName']; ?></div>
					</div>
				</td>
				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $ratingLabel; ?></div>
						<div class="flex-cell__content"><?php echo $teacher['teacher_rating']; ?></div>
					</div>
				</td>

				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $loclLabel; ?></div>
						<div class="flex-cell__content">
							<?php
							$svgIconClass = " color-black";
							$svgIcon = 'unlock';
							if ($teacher['isSetUpOfferPrice']) {
								$svgIconClass =  "color-primary";
								$svgIcon = 'lock';
							}
							?>
							<a href="javascript:void(0);" class="padding-3 <?php echo $svgIconClass; ?>">
								<svg class="icon icon--clock icon--small margin-right-2">
									<use xlink:href="<?php ?>images/sprite.yo-coach.svg#<?php echo $svgIcon ?>"></use>
								</svg>
							</a>
							<div class="lesson-price">
								<?php
								$durations = explode(',', $teacher['lessonDuration']);
								$percentage = explode(',', $teacher['percentage']);
								foreach ($durations as $i => $duration) {
								?>
									<p>
										<?php
										if (!empty($percentage[$i])) {
											echo sprintf(Label::getLabel('LBL_%d_mins'), $duration) . ': ' . $percentage[$i] . "%";
										} else {
											echo Label::getLabel('LBL_N/A');
										}
										?>
									</p>
								<?php } ?>
							</div>


						</div>
					</div>
				</td>
				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $scheduledLabel; ?></div>
						<div class="flex-cell__content"><?php echo $teacher['scheduledLessonCount'];  ?></div>
					</div>
				</td>
				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $pastLabel; ?></div>
						<div class="flex-cell__content"><?php echo $teacher['pastLessonCount']; ?></div>
					</div>
				</td>
				<td>
					<div class="flex-cell">
						<div class="flex-cell__label"><?php echo $unscheduledLabel; ?></div>
						<div class="flex-cell__content"><?php echo $teacher['unScheduledLessonCount']; ?></div>
					</div>
				</td>
				<td>
					<?php if (!empty($teacher['utl_tlanguage_id']) && !empty($teacher['ustelgpr_slot']) && !empty($teacher['ustelgpr_min_slab'])) { ?>
						<div class="flex-cell">
							<div class="flex-cell__label"><?php echo $actionLabel; ?></div>
							<div class="flex-cell__content">
								<div class="actions-group">
									<a href="javascript:void(0);" onclick="cart.proceedToStep({teacherId: <?php echo $teacher['teacherId']; ?>},'getUserTeachLangues');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
										<svg class="icon icon--buy">
											<use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#icon-buy'; ?>"></use>
										</svg>
										<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Buy_Now'); ?></div>
									</a>
								</div>
							</div>
						</div>
				</td>
			<?php } ?>
			</tr>
		<?php } ?>
	</table>
</div>
<?php
echo FatUtility::createHiddenFormFromData($postedData, array(
	'name' => 'frmLearnerTeachersSearchPaging'
));
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
if (empty($teachers)) {
	$this->includeTemplate('_partial/no-record-found.php');
}
