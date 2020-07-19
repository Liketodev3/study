<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$referer = preg_replace("(^https?://)", "", $referer );

MyDate::setUserTimeZone();
$user_timezone = MyDate::getUserTimeZone();

$date = new DateTime("now", new DateTimeZone($user_timezone));
$curDate = $date->format('Y-m-d');
$nextDate = date('Y-m-d', strtotime('+1 days', strtotime($curDate)));
$curDateTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );
if(!empty($lessonArr)) { ?>
<div class="col-md-12 text-right">
<strong class="-color-primary span-right"><span class="spn_must_field">*</span> <?php
$label =  Label::getLabel('LBL_All_times_listed_are_in_your_selected_{timezone}');
$getTimeZoneString = MyDate::displayTimezoneString(false);
$label = str_replace('{timezone}', $getTimeZoneString, $label);
echo $label;
?></strong>
</div>
<?php
}

foreach( $lessonArr as $key=>$lessons ){ ?>
<div class="col-list-group">
<?php if ($key!='0000-00-00') {  ?>
<h6><?php
if (strtotime($curDate) == strtotime($key)) {
	echo Label::getLabel('LBL_Today');
} elseif(strtotime($nextDate) == strtotime($key)) {
	echo Label::getLabel('LBL_Tommorrow');
} else {
	echo date('l, F d, Y',strtotime($key));
}
?></h6>
<?php } ?>
<div class="col-list-container">
<?php
foreach ( $lessons as $lesson ) {
	$action = '';
	if ( $lesson['is_trial'] == 1 ) {
		$action = 'free_trial';
	}
	$lessonsStatus = $statusArr[$lesson['sldetail_learner_status']];
	$lesson['lessonReschedulelogId'] =  FatUtility::int($lesson['lessonReschedulelogId']);

	if($lesson['lessonReschedulelogId'] > 0 && 
		( $lesson['sldetail_learner_status']== ScheduledLesson::STATUS_NEED_SCHEDULING ||
		$lesson['sldetail_learner_status']== ScheduledLesson::STATUS_SCHEDULED )
		
		) {
		$lessonsStatus = Label::getLabel('LBL_Rescheduled');
		if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
			$lessonsStatus = Label::getLabel('LBL_Pending_for_Reschedule');
		}
	}
?>
	<div class="col-list">
		<div class="d-lg-flex align-items-center">
			<div class="col-xl-4 col-lg-4 col-md-12">
				<div class="avtar avtar--normal" data-text="<?php echo CommonHelper::getFirstChar($lesson['teacherFname']); ?>">
					<?php
					if( true == User::isProfilePicUploaded( $lesson['teacherId'] ) ){
						$img = CommonHelper::generateUrl('Image','user', array( $lesson['teacherId'] )).'?'.time();
						echo '<img src="'.$img.'" />';
					} ?>
				</div>
				<h6><?php echo $lesson['teacherFname']; ?></h6>
				<p><?php echo $lesson['teacherCountryName']; ?> <br>
				<p><?php echo Label::getLabel('LBL_Order_Id').' : '.$lesson['sldetail_order_id']; ?> <br>

				<?php /* echo CommonHelper::getDateOrTimeByTimeZone($lesson['teacherTimeZone'],'H:i A P'); */ ?></p>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-12 ">
				<div class="schedule-list">
					<ul>
						<?php
						if( $lesson['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING  && $lesson['is_trial'] == 1 ) {
							?>
						<li>
							<h6 class="-color-secondary"><?php echo Label::getLabel('LBL_Trial_Lesson'); ?></h6>
						</li>
						<?php
						}
						$date = DateTime::createFromFormat('Y-m-d', $lesson['slesson_date']);
						if($date && ($date->format('Y-m-d') === $lesson['slesson_date'])){ ?>
							<li>
								<span class="span-left"><?php echo Label::getLabel('LBL_Schedule'); ?></span>
								<span class="span-right">
									<h4>
									<?php
										echo MyDate::convertTimeFromSystemToUserTimezone( 'h:i A', $lesson['slesson_start_time'], true , $user_timezone ).' - ';
										echo MyDate::convertTimeFromSystemToUserTimezone( 'h:i A', $lesson['slesson_end_time'], true , $user_timezone );
									?>
									</h4>
									<?php
										echo MyDate::convertTimeFromSystemToUserTimezone( 'l, F d, Y', $lesson['slesson_date']." ". $lesson['slesson_start_time'] , true , $user_timezone );
									?>
								</span>
							</li>
						<?php } ?>

						<li>
							<span class="span-left"><?php echo Label::getLabel('LBL_Status'); ?></span>
							<span class="span-right"><?php echo $lessonsStatus; ?></span>
						</li>

						<?php if($lesson['order_is_paid'] == Order::ORDER_IS_CANCELLED) {?>
						<li>
							<span class="span-left"><?php echo Label::getLabel('LBL_Order_Status'); ?></span>
							<span class="span-right"><?php echo Label::getLabel('LBL_Canceled'); ?></span>
						</li><br><br>

						<?php } ?>

                        <?php if( $lesson['issrep_id'] ){ ?>
                            <li>
                                <span class="span-left"><?php echo Label::getLabel('LBL_Issue_Status'); ?></span>
                                <span class="span-right"><?php echo IssuesReported::getStatusArr()[$lesson['issrep_status']]; ?></span>
                            </li>
                        <?php } ?>
						<li>
							<span class="span-left"><?php echo Label::getLabel('LBL_Details'); ?></span>
							<span class="span-right">

							<?php
							if($lesson['is_trial'] == applicationConstants::NO) {
								echo empty($teachLanguages[$lesson['slesson_slanguage_id']]) ? '': $teachLanguages[$lesson['slesson_slanguage_id']] ; ?>
								<br>
							<?php }
							if( $lesson['slesson_date'] != "0000-00-00" ){
								// $str = Label::getLabel( 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson' );
								// $str = Label::getLabel( 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson' );
								// $arrReplacements = array(
								// 	'{n}'	=>	$lesson['op_lesson_duration'],
								// 	'{trial-or-paid}'	=>	($lesson['is_trial']) ? Label::getLabel('LBL_Trial') : '',
								// );
								// foreach( $arrReplacements as $key => $val ){
								// 	$str = str_replace( $key, $val, $str );
								// }
								// echo $str;
							} ?>
							</span>
						</li>
					</ul>
				</div>

			</div>
			<?php if($lesson['order_is_paid'] != Order::ORDER_IS_CANCELLED) { ?>
			<div class="col-xl-4 col-lg-4 col-md-12 col-positioned">
				<div class="schedule-list">
					<ul>
					<?php
						$lessonsStartTime = $lesson['slesson_date']." ". $lesson['slesson_start_time'];
						$timerEndTimer = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', $lessonsStartTime, true , $user_timezone );
						if($lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED) {
							if(strtotime($timerEndTimer) > strtotime($curDateTime)) {
						?>
							<li class="lesson-listing-timer timer">
								<span class="span-right">
									<span class="-color-secondary label"> <?php echo Label::getLabel('LBL_Lesson_Starts_in'); ?></span>
									<span class="countdowntimer" id="countdowntimer-<?php echo $lesson['slesson_id']?>" data-startTime="<?php echo $curDateTime; ?>"  data-endTime="<?php echo $timerEndTimer; ?>"></span>
								</span>
							</li>
					<?php
						}else{
						?>
						<li class="span-right">
							<span class="-color-secondary"><?php echo Label::getLabel('LBL_Lesson_time_has_passed'); ?></span>
						</li>
						<?php
							}
						}
					?>
				</ul>
				</div>
				<ul class="actions">
					<li>
						<a href="<?php echo CommonHelper::generateUrl('LearnerScheduledLessons','view',[$lesson['sldetail_id']]); ?>" class="" title="<?php echo Label::getLabel('LBL_View'); ?>">
							<svg version="1.1" width="20px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								 viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
								<g>
									<g>
										<path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035
										c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201
										C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418
										c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418
										C447.361,287.923,358.746,385.406,255.997,385.406z"/>
									</g>
								</g>
								<g>
									<g>
										<path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275
										s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516
										s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z"/>
									</g>
								</g>
							</svg>
						</a>
					</li>
					<?php if($lesson['slesson_status'] != ScheduledLesson::STATUS_CANCELLED AND $referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('learner-scheduled-lessons'))){ ?>
					<?php if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) { ?>
						<li>
							<a href="javascript:void(0);" onclick="viewBookingCalendar('<?php echo $lesson['sldetail_id']; ?>', '<?php echo $action; ?>')" class="" title="<?php echo Label::getLabel('LBL_Schedule_Lesson'); ?>">
								<svg id="Layer_1_1_" enable-background="new 0 0 64 64" width="30px" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m56 40.10529v-28.10529c0-2.75684-2.24316-5-5-5h-2v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-5v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-6v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-5v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-2c-2.75684 0-5 2.24316-5 5v40c0 2.75684 2.24316 5 5 5h33.62347c2.07868 3.58081 5.94617 6 10.37653 6 6.61719 0 12-5.38281 12-12 0-4.83142-2.87561-8.99408-7-10.89471zm-11-35.10529c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-11 0c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-12 0c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-11 0c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-4 4h2v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h5v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h6v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h5v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h2c1.6543 0 3 1.3457 3 3v5h-50v-5c0-1.6543 1.3457-3 3-3zm0 46c-1.6543 0-3-1.3457-3-3v-33h50v20.39484c-.96082-.24866-1.96246-.39484-3-.39484-.6828 0-1.34808.07056-2 .1806v-5.1806c0-.55273-.44727-1-1-1h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h2.38086c-3.23914 2.15106-5.38086 5.82843-5.38086 10 0 1.40411.25494 2.74664.70001 4zm40-16h-4v-4h4zm4 22c-5.51367 0-10-4.48633-10-10s4.48633-10 10-10 10 4.48633 10 10-4.48633 10-10 10z"/><path d="m52 49.2774v-6.2774h-2v6.2774c-.59528.34644-1 .98413-1 1.7226 0 .10126.01526.19836.02979.29553l-3.65479 2.92322 1.25 1.5625 3.65161-2.92133c.22492.08759.46753.14008.72339.14008 1.10455 0 2-.89545 2-2 0-.73846-.40472-1.37616-1-1.7226z"/><path d="m15 22h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m26 22h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m37 22h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m42 30h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1zm1-6h4v4h-4z"/><path d="m15 33h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m26 33h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m37 33h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m15 44h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m26 44h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m37 44h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/></svg>
							</a>
						</li>
					<?php } ?>
					<?php if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED && $lesson['issrep_id'] < 1 || $lesson['issrep_status'] == IssuesReported::STATUS_RESOLVED && ($lesson['issrep_issues_resolve_by'] != 3 && $lesson['issrep_issues_resolve_by'] != 4)) { ?>
						<li>
							<a href="javascript:void(0);" onclick="issueReported('<?php echo $lesson['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Issue_Reported'); ?>">
								<svg width="20px" viewBox="0 0 60 60" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="070---Laptop-Message-Not-Sent" fill="rgb(0,0,0)" fill-rule="nonzero"><path id="Shape" d="m58 48h-2v-33c-.0033061-2.7600532-2.2399468-4.9966939-5-5h-1v-7c0-1.65685425-1.3431458-3-3-3h-34c-1.6568542 0-3 1.34314575-3 3v7h-1c-2.76005315.0033061-4.99669388 2.2399468-5 5v33h-2c-1.1045695 0-2 .8954305-2 2v3c.00495836 3.8639376 3.13606244 6.9950416 7 7h46c3.8639376-.0049584 6.9950416-3.1360624 7-7v-3c0-1.1045695-.8954305-2-2-2zm-46-45c0-.55228475.4477153-1 1-1h34c.5522847 0 1 .44771525 1 1v28c0 .5522847-.4477153 1-1 1h-24c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h.8l-5.8 4.01v-4.01h1c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6c-.5522847 0-1-.4477153-1-1zm-6 12c0-1.6568542 1.34314575-3 3-3h1v19c0 1.6568542 1.3431458 3 3 3h3v4.01c.0055034 1.1006623.899324 1.9900138 2 1.99.4068132-.0004921.803803-.1250311 1.138-.357l8.174-5.643h19.688c1.6568542 0 3-1.3431458 3-3v-19h1c1.6568542 0 3 1.3431458 3 3v33h-48zm15 35h18v1c0 1.1045695-.8954305 2-2 2h-14c-1.1045695 0-2-.8954305-2-2zm37 3c-.0033061 2.7600532-2.2399468 4.9966939-5 5h-46c-2.76005315-.0033061-4.99669388-2.2399468-5-5v-3h17v1c0 2.209139 1.790861 4 4 4h14c2.209139 0 4-1.790861 4-4v-1h17z"/><path id="Shape" d="m15 52h-2c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h2c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><circle id="Oval" cx="5" cy="53" r="1"/><circle id="Oval" cx="9" cy="53" r="1"/><path id="Shape" d="m30 30c7.1797017 0 13-5.8202983 13-13 0-7.17970175-5.8202983-13-13-13s-13 5.82029825-13 13c.008266 7.1762751 5.8237249 12.991734 13 13zm0-24c6.0751322 0 11 4.9248678 11 11s-4.9248678 11-11 11-11-4.9248678-11-11c.0071635-6.0721626 4.9278374-10.9928365 11-11z"/><path id="Shape" d="m25.7 24.019c.1510172.6758719.6385372 1.2268337 1.291 1.459.9655907.3454686 1.9834689.5220496 3.009.522.7093957-.0007512 1.4163592-.0829563 2.107-.245 2.4813348-.5949487 4.5927859-2.2166431 5.8076453-4.4605466 1.2148595-2.2439034 1.4185843-4.8984494.5603547-7.3014534-.2308809-.6525752-.7807135-1.1408537-1.456-1.293-.6678543-.1529236-1.367337.0497373-1.85.536l-8.933 8.933c-.4854126.4831671-.6879357 1.1821742-.536 1.85zm10.9-9.352c.4529815 1.2787496.527288 2.6610594.214 3.981-.4622316 1.9319167-1.7262636 3.5750969-3.4750127 4.5173479s-3.816293 1.0941715-5.6839873.4176521z"/><path id="Shape" d="m23.423 21.35c.5285959-.0002743 1.035296-.2111595 1.408-.586l8.933-8.933c.4854126-.4831671.6879357-1.1821742.536-1.85-.1510172-.67587187-.6385372-1.22683366-1.291-1.459-3.2707615-1.16856777-6.9220918-.34781853-9.3781538 2.1080297-2.4560621 2.4558483-3.2771292 6.1071071-2.1088462 9.3779703.2876055.8027571 1.0472816 1.3393283 1.9 1.342zm-.231-6c.60786-2.5542076 2.6019103-4.5486446 5.156-5.157.5387455-.1273922 1.0903984-.1921543 1.644-.193.804837-.00046099 1.6036627.1385976 2.361.411l-8.948 8.92c-.453213-1.2787345-.527181-2.6612067-.213-3.981z"/></g></g></svg>
							</a>
						</li>
					<?php } ?>
					<?php if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED ) { ?>
						<li>
							<a width="20px" href="javascript:void(0);" onclick="lessonFeedback('<?php echo $lesson['sldetail_id'];  ?>');" class="" title="<?php echo Label::getLabel('LBL_Rate_Lesson'); ?>">
							<svg  viewBox="0 0 60 60" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="070---Laptop-Message-Not-Sent" fill="rgb(0,0,0)" fill-rule="nonzero"><path id="Shape" d="m58 48h-2v-33c-.0033061-2.7600532-2.2399468-4.9966939-5-5h-1v-7c0-1.65685425-1.3431458-3-3-3h-34c-1.6568542 0-3 1.34314575-3 3v7h-1c-2.76005315.0033061-4.99669388 2.2399468-5 5v33h-2c-1.1045695 0-2 .8954305-2 2v3c.00495836 3.8639376 3.13606244 6.9950416 7 7h46c3.8639376-.0049584 6.9950416-3.1360624 7-7v-3c0-1.1045695-.8954305-2-2-2zm-46-45c0-.55228475.4477153-1 1-1h34c.5522847 0 1 .44771525 1 1v28c0 .5522847-.4477153 1-1 1h-24c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h.8l-5.8 4.01v-4.01h1c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6c-.5522847 0-1-.4477153-1-1zm-6 12c0-1.6568542 1.34314575-3 3-3h1v19c0 1.6568542 1.3431458 3 3 3h3v4.01c.0055034 1.1006623.899324 1.9900138 2 1.99.4068132-.0004921.803803-.1250311 1.138-.357l8.174-5.643h19.688c1.6568542 0 3-1.3431458 3-3v-19h1c1.6568542 0 3 1.3431458 3 3v33h-48zm15 35h18v1c0 1.1045695-.8954305 2-2 2h-14c-1.1045695 0-2-.8954305-2-2zm37 3c-.0033061 2.7600532-2.2399468 4.9966939-5 5h-46c-2.76005315-.0033061-4.99669388-2.2399468-5-5v-3h17v1c0 2.209139 1.790861 4 4 4h14c2.209139 0 4-1.790861 4-4v-1h17z"/><path id="Shape" d="m15 52h-2c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h2c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><circle id="Oval" cx="5" cy="53" r="1"/><circle id="Oval" cx="9" cy="53" r="1"/><path id="Shape" d="m30 30c7.1797017 0 13-5.8202983 13-13 0-7.17970175-5.8202983-13-13-13s-13 5.82029825-13 13c.008266 7.1762751 5.8237249 12.991734 13 13zm0-24c6.0751322 0 11 4.9248678 11 11s-4.9248678 11-11 11-11-4.9248678-11-11c.0071635-6.0721626 4.9278374-10.9928365 11-11z"/><path id="Shape" d="m25.7 24.019c.1510172.6758719.6385372 1.2268337 1.291 1.459.9655907.3454686 1.9834689.5220496 3.009.522.7093957-.0007512 1.4163592-.0829563 2.107-.245 2.4813348-.5949487 4.5927859-2.2166431 5.8076453-4.4605466 1.2148595-2.2439034 1.4185843-4.8984494.5603547-7.3014534-.2308809-.6525752-.7807135-1.1408537-1.456-1.293-.6678543-.1529236-1.367337.0497373-1.85.536l-8.933 8.933c-.4854126.4831671-.6879357 1.1821742-.536 1.85zm10.9-9.352c.4529815 1.2787496.527288 2.6610594.214 3.981-.4622316 1.9319167-1.7262636 3.5750969-3.4750127 4.5173479s-3.816293 1.0941715-5.6839873.4176521z"/><path id="Shape" d="m23.423 21.35c.5285959-.0002743 1.035296-.2111595 1.408-.586l8.933-8.933c.4854126-.4831671.6879357-1.1821742.536-1.85-.1510172-.67587187-.6385372-1.22683366-1.291-1.459-3.2707615-1.16856777-6.9220918-.34781853-9.3781538 2.1080297-2.4560621 2.4558483-3.2771292 6.1071071-2.1088462 9.3779703.2876055.8027571 1.0472816 1.3393283 1.9 1.342zm-.231-6c.60786-2.5542076 2.6019103-4.5486446 5.156-5.157.5387455-.1273922 1.0903984-.1921543 1.644-.193.804837-.00046099 1.6036627.1385976 2.361.411l-8.948 8.92c-.453213-1.2787345-.527181-2.6612067-.213-3.981z"/></g></g></svg>
							</a>
						</li>
					<?php } ?>

					<?php if( $lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED || $lesson['issrep_id'] > 0) { ?>
						<li>
							<a href="javascript:void(0);" onclick="issueDetails('<?php echo $lesson['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Issue_Details'); ?>">
								<svg version="1.1"  width="35px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 363 363" style="enable-background:new 0 0 363 363;" xml:space="preserve">
									 <path d="M277.73,94.123c0,10.997-8.006,17.685-13.852,22.593c-2.214,1.859-6.335,5.251-6.324,6.518
									c0.04,4.97-3.956,8.939-8.927,8.939c-0.025,0-0.05,0-0.075,0c-4.936,0-8.958-3.847-8.998-8.792
									c-0.079-9.747,7.034-15.584,12.75-20.383c4.485-3.766,7.426-6.416,7.426-8.841c0-4.909-3.994-8.903-8.903-8.903
									c-4.911,0-8.906,3.994-8.906,8.903c0,4.971-4.029,9-9,9s-9-4.029-9-9c0-14.834,12.069-26.903,26.904-26.903
									C265.661,67.253,277.73,79.288,277.73,94.123z M248.801,140.481c-4.971,0-8.801,4.029-8.801,9v0.069
									c0,4.971,3.831,8.966,8.801,8.966s9-4.064,9-9.035S253.772,140.481,248.801,140.481z M67.392,203.174c-4.971,0-9,4.029-9,9
									s4.029,9,9,9h0.75c4.971,0,9-4.029,9-9s-4.029-9-9-9H67.392z M98.671,203.174c-4.971,0-9,4.029-9,9s4.029,9,9,9h0.749
									c4.971,0,9-4.029,9-9s-4.029-9-9-9H98.671z M363,59.425v101.301c0,23.985-19.232,43.448-43.217,43.448H203.066
									c-2.282,0-4.161-0.013-5.733-0.046c-1.647-0.034-3.501-0.047-4.224,0.033c-0.753,0.5-2.599,2.191-4.378,3.83
									c-0.705,0.649-1.503,1.363-2.364,2.149l-33.022,30.098c-2.634,2.403-6.531,3.025-9.793,1.587c-3.262-1.439-5.552-4.669-5.552-8.234
									v-95.417H43.72c-14.062,0-25.72,11.523-25.72,25.583v101.301c0,14.061,11.659,25.116,25.72,25.116h130.374
									c2.245,0,4.345,1.031,6.003,2.545L207,317.523v-85.539c0-4.971,4.029-9,9-9s9,4.029,9,9v105.938c0,3.565-2.04,6.747-5.303,8.186
									c-1.167,0.515-2.339,0.718-3.566,0.718c-2.204,0-4.378-0.905-6.069-2.449l-39.457-36.204H43.72c-23.986,0-43.72-19.13-43.72-43.116
									V163.757c0-23.985,19.734-43.583,43.72-43.583H138V59.425c0-23.986,19.885-43.251,43.871-43.251h137.913
									C343.768,16.174,363,35.439,363,59.425z M345,59.425c0-14.061-11.157-25.251-25.217-25.251H181.871
									C167.81,34.174,156,45.364,156,59.425v69.833v83.934l18.095-16.353c0.838-0.765,1.777-1.465,2.462-2.097
									c8.263-7.614,10.377-8.831,21.155-8.609c1.47,0.031,3.221,0.042,5.354,0.042h116.717c14.06,0,25.217-11.388,25.217-25.448V59.425z"
									/>
								</svg>
							</a>
						</li>
					<?php } ?>
					<?php if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
						<li>
							<a href="javascript:void(0);" onclick="requestReschedule('<?php echo $lesson['sldetail_id']; ?>')" title="<?php echo Label::getLabel('LBL_Reschedule_Lesson'); ?>">
								<svg version="1.1" width="30px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								viewBox="0 0 460.801 460.801" style="enable-background:new 0 0 460.801 460.801;" xml:space="preserve">
									<g>
										<g>
											<path d="M231.298,17.068c-57.746-0.156-113.278,22.209-154.797,62.343V17.067C76.501,7.641,68.86,0,59.434,0
											S42.368,7.641,42.368,17.067v102.4c-0.002,7.349,4.701,13.874,11.674,16.196l102.4,34.133c8.954,2.979,18.628-1.866,21.606-10.82
											c2.979-8.954-1.866-18.628-10.82-21.606l-75.605-25.156c69.841-76.055,188.114-81.093,264.169-11.252
											s81.093,188.114,11.252,264.169s-188.114,81.093-264.169,11.252c-46.628-42.818-68.422-106.323-57.912-168.75
											c1.653-9.28-4.529-18.142-13.808-19.796s-18.142,4.529-19.796,13.808c-0.018,0.101-0.035,0.203-0.051,0.304
											c-2.043,12.222-3.071,24.592-3.072,36.983C8.375,361.408,107.626,460.659,230.101,460.8
											c122.533,0.331,222.134-98.734,222.465-221.267C452.896,117,353.832,17.399,231.298,17.068z"/>
										</g>
									</g>
								</svg>
							</a>
						</li>
					<?php } ?>
					<?php if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING || ScheduledLesson::STATUS_SCHEDULED) { ?>
						<li>
							<a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lesson['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Cancel_Lesson'); ?>">
								<svg  width="14px" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
							</a>
						</li>
					<?php } ?>
					<?php
						$countRel=ScheduledLessonSearch::countPlansRelation($lesson['sldetail_id']);
						if( $countRel > 0 ){
					?>
					<li>
						<a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lesson['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?>">
							<svg   width="35px" enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m454.808 33.134h-9.067v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067-13.271 0-24.067 10.796-24.067 24.067v9.067h-9.068c-22.405 0-40.632 18.228-40.632 40.632v183.537l-18.346-18.346c-9.384-9.384-24.652-9.383-34.036 0l-23.429 23.429c-9.384 9.383-9.384 24.652 0 34.035l75.81 75.81v99.136c0 22.405 18.228 40.633 40.632 40.633h255.136c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-255.135c-14.134 0-25.632-11.499-25.632-25.633v-84.136l61.184 61.184c.178.178.364.346.557.503.131.107.267.202.403.299.064.045.124.096.189.139.172.115.349.217.528.316.034.019.066.041.1.059.189.101.382.189.576.272.029.012.056.028.086.04.189.078.381.144.574.206.04.013.078.029.118.041.182.055.366.098.55.138.055.012.108.029.163.04.179.035.359.058.54.08.063.008.124.021.187.027.243.024.488.036.732.036h43.751l9.518 9.518c1.464 1.464 3.384 2.197 5.303 2.197s3.839-.733 5.303-2.197c2.929-2.929 2.929-7.678 0-10.607l-9.518-9.517v-43.749c0-.246-.012-.491-.036-.736-.005-.054-.017-.106-.023-.16-.023-.19-.048-.379-.085-.567-.01-.048-.024-.095-.035-.143-.042-.191-.087-.382-.143-.57-.01-.034-.024-.066-.035-.1-.063-.199-.132-.397-.212-.592-.009-.021-.02-.041-.029-.062-.086-.203-.179-.404-.283-.6-.014-.026-.03-.049-.044-.075-.103-.188-.211-.373-.331-.553-.037-.056-.081-.108-.12-.163-.102-.145-.204-.29-.317-.429-.158-.193-.325-.379-.503-.557l-61.185-61.185h258.087c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-2.203 0-4.179.956-5.551 2.469l-44.933-44.933v-141.333h366.034v51.151c0 4.142 3.358 7.5 7.5 7.5s7.5-3.358 7.5-7.5v-108.352c.001-22.405-18.227-40.632-40.632-40.632zm-234.556 402.478h-21.251l21.251-21.251zm-157.317-121.065 12.823-12.822 117.959 117.959-12.822 12.822zm141.388 94.53-117.959-117.959 12.823-12.822 117.959 117.959zm-170.12-136.084 23.429-23.429c3.535-3.535 9.288-3.536 12.823 0l18.126 18.126-36.252 36.25-18.125-18.125c-3.536-3.535-3.536-9.287-.001-12.822zm387.471-257.993c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c.001-5 4.068-9.067 9.067-9.067zm-82.833 0c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c0-5 4.067-9.067 9.067-9.067zm-91.9 9.067c0-5 4.067-9.067 9.067-9.067s9.067 4.067 9.067 9.067v16.557.01s0 .006 0 .01v16.556c0 5-4.067 9.067-9.067 9.067-4.999 0-9.067-4.067-9.067-9.067zm-73.767-9.067c4.999 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-33.133c0-5 4.067-9.067 9.067-9.067zm-58.767 100.967v-42.201c0-14.134 11.499-25.632 25.632-25.632h9.068v9.066c0 13.271 10.796 24.067 24.067 24.067 13.27 0 24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h9.067c14.134 0 25.633 11.499 25.633 25.632v42.201z"/><path d="m487.941 204.619c-4.142 0-7.5 3.358-7.5 7.5v259.248c0 14.134-11.499 25.633-25.633 25.633h-29.634c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h29.634c22.405 0 40.633-18.228 40.633-40.633v-259.248c0-4.142-3.358-7.5-7.5-7.5z"/><path d="m164.89 180.667h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 230.367h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 280.067h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m437.458 371.967c0-4.142-3.358-7.5-7.5-7.5h-173.95c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h173.95c4.142 0 7.5-3.358 7.5-7.5z"/></g></svg>
						</a>
					</li>
					<?php } ?>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
</div>
<?php }
if ( empty($lessons) ) {
	$this->includeTemplate('_partial/no-record-found.php');
} else {
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmSLnsSearchPaging'
	) );
	if ( $referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('learner-scheduled-lessons'))){
		$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	} else {
		echo "<div class='load-more -align-center'><a href='".CommonHelper::generateFullUrl('learner-scheduled-lessons')."' class='btn btn--bordered btn--xlarge'>".Label::getLabel('LBL_View_all')."</a></div>";
	}
}
?>
<script type="text/javascript">
jQuery(document).ready(function () {
	$('.countdowntimer').each(function (i) {
		var countdowntimerid = $(this).attr('id');
		$("#"+countdowntimerid).countdowntimer({
				startDate : $(this).attr('data-startTime'),
				dateAndTime : $(this).attr('data-endTime'),
				size : "sm",
			});
	});
	/*$(".toggle__trigger-js").click(function () {
        var t = $(this).parents(".toggle-group").children(".toggle__target-js").is(":hidden");
        $(".toggle-group .toggle__target-js").hide();
        $(".toggle-group .toggle__trigger-js").removeClass("is-active");
        if (t) {
            $(this).parents(".toggle-group").children(".toggle__target-js").toggle().parents(".toggle-group").children(".toggle__trigger-js").addClass("is-active")
        }

    });
    $(document).bind("click", function (t) {
        var n = $(t.target);
        if (!n.parents().hasClass("toggle-group")) $(".toggle-group .toggle__target-js").hide();
    });
    $(document).bind("click", function (t) {
        var n = $(t.target);
        if (!n.parents().hasClass("toggle-group")) $(".toggle-group .toggle__trigger-js").removeClass("is-active");
    })*/
});
</script>
