<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

MyDate::setUserTimeZone();
$user_timezone = MyDate::getUserTimeZone();

$date = new DateTime("now", new DateTimeZone($user_timezone));
$curDate = $date->format('Y-m-d');
$nextDate = date('Y-m-d', strtotime('+1 days', strtotime($curDate)));

$curDateTime = MyDate::convertTimeFromSystemToUserTimezone('Y/m/d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);

$referer = preg_replace("(^https?://)", "", $referer);
?>
<div class="results">		
<?php
foreach ($lessonArr as $key => $lessons) { ?>
<div class="lessons-group margin-top-10">
	<?php if ($key!='0000-00-00') { ?>
		<date class="date uppercase small bold-600">
			<?php
				if (strtotime($curDate) == strtotime($key)) {
					echo Label::getLabel('LBL_Today');
				} elseif (strtotime($nextDate) == strtotime($key)) {
					echo Label::getLabel('LBL_Tommorrow');
				} else {
					echo date('l, F d, Y', strtotime($key));
				}
			?>
		</date>
	<?php } ?>
	<?php
		foreach ($lessons as $lesson) {
			$lessonsStatus = $statusArr[$lesson['sldetail_learner_status']];
			$lesson['lessonReschedulelogId'] =  FatUtility::int($lesson['lessonReschedulelogId']);

			if ($lesson['lessonReschedulelogId'] > 0 &&
				($lesson['sldetail_learner_status']== ScheduledLesson::STATUS_NEED_SCHEDULING ||
					$lesson['sldetail_learner_status']== ScheduledLesson::STATUS_SCHEDULED)) {
				$lessonsStatus = Label::getLabel('LBL_Rescheduled');
				if ($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
					$lessonsStatus = Label::getLabel('LBL_Pending_for_Reschedule');
				}
			} 
            $teachLang = Label::getLabel('LBL_Trial');
            if ($lesson['is_trial'] == applicationConstants::NO) {
				$teachLang = empty($teachLanguages[$lesson['slesson_slanguage_id']]) ? '': $teachLanguages[$lesson['slesson_slanguage_id']] ;
			}
	?>
		<!-- [ LESSON CARD ========= -->
		<div class="card-landscape">
			<div class="card-landscape__colum card-landscape__colum--first">
			<?php 
				$lessonsStartTime = $lesson['slesson_date']." ". $lesson['slesson_start_time'];
				$startTime = MyDate::convertTimeFromSystemToUserTimezone('M-d-Y H:i:s', $lessonsStartTime, true, $user_timezone);
				$startUnixTime = strtotime($startTime);
				if ($lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED && $lesson['order_is_paid'] != Order::ORDER_IS_CANCELLED) { ?>
				<div class="card-landscape__head">
					<?php 
						$endDateTime = $lesson['slesson_end_date']." ". $lesson['slesson_end_time'];
						$endDateTime =  MyDate::convertTimeFromSystemToUserTimezone('M-d-Y H:i:s', $lessonsStartTime, true, $user_timezone);
					?>
					<time class="card-landscape__time"><?php echo date('h:i A',$startUnixTime); ?></time>
					<date class="card-landscape__date"><?php echo date('l, F d, Y',$startUnixTime); ?></date>		
				</div>	
				<div class="timer">
					<div class="timer__media"><span><svg class="icon icon--clock icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#clock'; ?>"></use></svg></span></div>
					<div class="timer__content">
						<?php if ($startUnixTime > strtotime($curDateTime)) { ?>
						<div class="timer__controls countdowntimer timer-js"  id="countdowntimer-<?php echo $lesson['slesson_id']?>" data-startTime="<?php echo $curDateTime; ?>" data-endTime="<?php echo date('Y/m/d H:i:s', $startUnixTime); ?>">
							<!-- <div class="timer__digit">00</div>
							<div class="timer__digit">06</div>
							<div class="timer__digit">33</div>
							<div class="timer__digit">16</div> -->
						</div>
						<?php }else { 
							$lessonInfoLblKey = 'LBL_Lesson_time_has_passed';
                            if (strtotime($endDateTime) > strtotime($curDateTime)) {
								$lessonInfoLblKey = 'LBL_Lesson_ongoing';
							}
							echo '<span class="color-red">'.Label::getLabel($lessonInfoLblKey).'</span>';
						 } ?>
					</div>
				</div>		
			<?php } ?>	
			</div>
			<div class="card-landscape__colum card-landscape__colum--second">
				<div class="card-landscape__head">
					<?php if ($lesson['slesson_grpcls_id'] > 0) { ?>
						<span class="card-landscape__title"><?php echo $lesson['grpcls_title']; ?></span>
					<?php }else{ ?>
						<span class="card-landscape__title">
							<?php 
								$str = Label::getLabel('LBL_{teach-lang},{n}_minutes_of_Lesson');
								echo  str_replace(['{teach-lang}','{n}'], [$teachLang, $lesson['op_lesson_duration']], $str);
							 ?>
						</span>
					<?php } ?>
					<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0"><?php echo $lessonsStatus; ?></span>
				</div>
				<?php if ($lesson['order_is_paid'] != Order::ORDER_IS_CANCELLED) {
                        if ($lesson['slesson_status'] != ScheduledLesson::STATUS_CANCELLED && $lesson['isLessonPlanAttach'] > 0) { 
				?>
						<div class="card-landscape__docs">
							<div class="d-flex align-items-center">
								<a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lesson['slesson_id']; ?>')" class="attachment-file">
									<svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#attach'; ?>"></use></svg>
									<?php echo $lesson['tlpn_title'] ?>
								</a>
								<a href="javascript:void(0);" onclick="changeLessonPlan('<?php echo $lesson['slesson_id']; ?>');" class="underline color-black  btn btn--transparent btn--small"><?php echo Label::getLabel('LBL_Change_Lesson_Plan'); ?></a>
								<a href="javascript:void(0);" onclick="removeAssignedLessonPlan('<?php echo $lesson['slesson_id']; ?>');" class="underline color-black  btn btn--transparent btn--small"><?php echo Label::getLabel('LBL_Remove'); ?></a>
							</div>
						</div>
				<?php } else { ?>
					<div class="card-landscape__docs">
						<a href="javascript:void(0);" onclick="listLessonPlans('<?php echo $lesson['slesson_id']; ?>');" class="btn btn--transparent btn--addition color-black btn--small"><?php echo Label::getLabel('LBL_Add_Lesson_Plan'); ?></a>
					</div>
				<?php }
                    }
				?>
				
			</div>
			<div class="card-landscape__colum card-landscape__colum--third">
				<div class="card-landscape__actions">
					
						<div class="profile-meta">
							<?php  if ( $lesson['slesson_grpcls_id'] <= 0 ) { ?>	
								<div class="profile-meta__media">
									<span class="avtar" data-title="<?php echo CommonHelper::getFirstChar($lesson['learnerFname']); ?>">
										<?php
											if (true == User::isProfilePicUploaded($lesson['learnerId'])) {
												$img = CommonHelper::generateUrl('Image', 'user', array( $lesson['learnerId'] ), CONF_WEBROOT_FRONT_URL).'?'.time();
												echo '<img src="'.$img.'" alt="'.$lesson['learnerFname'].'" />';
											}
										?>	
									</span>
								</div>
								<div class="profile-meta__details">
									<p class="bold-600 color-black"><?php echo $lesson['learnerFname']; ?></p>
									<p class="small"><?php echo $lesson['learnerCountryName']; ?></p>
								</div>
							<?php } ?>
						</div>
					
					<div class="actions-group">
						<?php if ($lesson['order_is_paid'] != Order::ORDER_IS_CANCELLED) { ?>

							<a href="<?php echo CommonHelper::generateUrl('TeacherScheduledLessons', 'view', [$lesson['slesson_id']]); ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
								<svg class="icon icon--enter icon--18"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#enter'; ?>"></use></svg>
								<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Enter_Classroom'); ?></div>
							</a>

							<?php if ($referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('teacher-scheduled-lessons'))) { ?>

								<?php if (($lesson['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING || $lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED) && strtotime($curDateTime) < $startUnixTime) { ?>
									<a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lesson['slesson_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
										<svg class="icon icon--cancel icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#cancel'; ?>"></use></svg>
										<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Cancel'); ?></div>
									</a>
								<?php } ?>
								<?php if ($lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED && strtotime($curDateTime) < $startUnixTime) { ?>
									<a  href="javascript:void(0);" onclick="requestReschedule('<?php echo $lesson['slesson_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
										<svg class="icon icon--reschedule icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#reschedule'; ?>"></use></svg>
										<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Reschedule'); ?></div>
									</a>
								<?php } ?>
								<?php if ($lesson['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED || $lesson['issrep_id'] > 0) { ?>
									<a href="javascript:void(0);" onclick="issueReportedDetails('<?php echo $lesson['slesson_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
										<svg class="icon icon--reschedule icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#view-report'; ?>"></use></svg>
										<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Issue_Details'); ?></div>
									</a>
								<?php } ?>
								<?php if ($lesson['issrep_id'] > 0 && $lesson['issrep_status'] == 0) { ?>
									<a  href="javascript:void(0);"  onclick="resolveIssue('<?php echo $lesson['slesson_id'].','.$lesson['issrep_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
										<svg class="icon icon--reschedule icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#resolve-issue'; ?>"></use></svg>
										<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Check_And_Resolve_Issue'); ?></div>
									</a>
								<?php } ?>
								<?php if ($lesson['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED && $lesson['issrep_status'] == 1 && $lesson['issrep_issues_resolve_type'] < 1) { ?>
									<a  href="javascript:void(0);"  onclick="issueResolveStepTwo('<?php echo $lesson['issrep_id']; ?>', '<?php echo $lesson['slesson_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
										<svg class="icon icon--reschedule icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#resolve-issue'; ?>"></use></svg>
										<div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Issue_Details'); ?></div>
									</a>
								<?php } ?>

							<?php } ?>

						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<!-- ] ========= -->
<?php } ?>
</div>
<?php } ?>
</div>
<?php 
if (empty($lessons)) {
    $this->includeTemplate('_partial/no-record-found.php');
} else {
    echo FatUtility::createHiddenFormFromData($postedData, array(
        'name' => 'frmSLnsSearchPaging'
    ));
    if ($referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('teacher-scheduled-lessons'))) {
        $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
    } else {
        echo "<div class='load-more -align-center'><a href='".CommonHelper::generateFullUrl('teacher-scheduled-lessons')."' class='btn btn--bordered btn--xlarge'>".Label::getLabel('LBL_View_all')."</a></div>";
    }
}
?>
<script >
jQuery(document).ready(function () {
	$('.countdowntimer').each(function (i) {
		var countdowntimerid = $(this).attr('id');
		$("#"+countdowntimerid).countdowntimer({
            startDate : $(this).attr('data-startTime'),
            dateAndTime : $(this).attr('data-endTime'),
            size : "sm",
        });
	});
});
</script>
