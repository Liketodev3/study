<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

MyDate::setUserTimeZone();
$user_timezone = MyDate::getUserTimeZone();

$date = new DateTime("now", new DateTimeZone($user_timezone));
$curDate = $date->format('Y-m-d');
$nextDate = date('Y-m-d', strtotime('+1 days', strtotime($curDate)));

$curDateTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );

$referer = preg_replace("(^https?://)", "", $referer );
foreach( $lessonArr as $key=>$lessons ){ ?>
<div class="col-list-group">
<?php if($key!='0000-00-00'){ ?>
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

foreach( $lessons as $lesson ){

	$lessonsStatus = $statusArr[$lesson['sldetail_learner_status']];
	$lesson['lessonReschedulelogId'] =  FatUtility::int($lesson['lessonReschedulelogId']);

	if($lesson['lessonReschedulelogId'] > 0 &&
		( $lesson['sldetail_learner_status']== ScheduledLesson::STATUS_NEED_SCHEDULING ||
			$lesson['sldetail_learner_status']== ScheduledLesson::STATUS_SCHEDULED ) ) {
			$lessonsStatus = Label::getLabel('LBL_Rescheduled');
		if($lesson['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
			$lessonsStatus = Label::getLabel('LBL_Pending_for_Reschedule');
		}
	}

?>
	<div class="col-list">
		<div class="d-lg-flex align-items-center">
			<div class="col-xl-4 col-lg-4 col-md-12">
                <?php if($lesson['slesson_grpcls_id']>0): //dashboard ?>
                <h6><?php echo Label::getLabel('LBL_Group_Class') ?></h6>
                <p><?php echo $lesson['grpcls_title']; ?></p>
                <?php else: ?>
				<div class="avtar avtar--normal" data-text="<?php echo CommonHelper::getFirstChar($lesson['learnerFname']); ?>">
					<?php
					if( true == User::isProfilePicUploaded( $lesson['learnerId'] ) ){
						$img = CommonHelper::generateUrl('Image','user', array( $lesson['learnerId'] ), CONF_WEBROOT_FRONT_URL).'?'.time();
						echo '<img src="'.$img.'" />';
					} ?>
				</div>
				<h6><?php echo $lesson['learnerFname']; ?></h6>
				<p><?php echo $lesson['learnerCountryName']; ?> <br>
                <?php endif; ?>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-12">
				
				<div class="schedule-list">
					<ul>
						<?php
						$date = DateTime::createFromFormat('Y-m-d', $lesson['slesson_date']);
						if($date && ($date->format('Y-m-d') === $lesson['slesson_date'])){ ?>
							<li>
								<span class="span-left"><?php echo Label::getLabel('LBL_Schedule'); ?></span>
								<span class="span-right">
									<h4>
									<?php
										echo MyDate::convertTimeFromSystemToUserTimezone( 'h:i A', $lesson['slesson_start_time'], true , $user_timezone );
									?>
									</h4>
									<?php
										echo MyDate::convertTimeFromSystemToUserTimezone( 'l, F d, Y', $lesson['slesson_date'].' '. $lesson['slesson_start_time'], true , $user_timezone );
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
								<?php if($lesson['is_trial'] == applicationConstants::NO) {
									 echo empty($teachLanguages[$lesson['slesson_slanguage_id']])?'':$teachLanguages[$lesson['slesson_slanguage_id']] ; ?><br>
								<?php }
								if( $lesson['slesson_date'] != "0000-00-00" ){
									$str = Label::getLabel( 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson' );
									$arrReplacements = array(
										'{n}'	=>	$lesson['op_lesson_duration'],
										'{trial-or-paid}'	=>	($lesson['is_trial']) ? Label::getLabel('LBL_Trial') : '',
									);
									foreach( $arrReplacements as $key => $val ){
										$str = str_replace( $key, $val, $str );
									}
									echo $str;
								}
								/* $to_time = strtotime($lesson['slesson_start_time']);
								$from_time = strtotime($lesson['slesson_end_time']);
								echo $minutes = round(abs($to_time - $from_time) / 60,2);
								echo Label::getLabel('LBL_Minute'); ?> <?php echo ($minutes == 30) ? 'Trial' : ''; ?> <?php echo Label::getLabel('LBL_Lesson'); */ ?>
							</span>
						</li>
					</ul>
				</div>
			</div>
            <?php $lessonsStartTime = $lesson['slesson_date']." ". $lesson['slesson_start_time'];
            $startTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', $lessonsStartTime, true , $user_timezone );
            $endTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date($lesson['slesson_end_date'] .' '. $lesson['slesson_end_time']), true , $user_timezone );
            ?>
            <?php if($lesson['order_is_paid'] != Order::ORDER_IS_CANCELLED) { ?>
			<div class="col-xl-4 col-lg-4 col-md-12 col-positioned">
				<div class="schedule-list">
					<ul>
					<?php if($lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED) {
							if(strtotime($startTime) > strtotime($curDateTime)) {
						?>
							<li class="lesson-listing-timer timer">
								<span class="span-right">
									<span class="-color-secondary label"> <?php echo Label::getLabel('LBL_Lesson_Starts_in'); ?></span>
									<span class="countdowntimer" id="countdowntimer-<?php echo $lesson['slesson_id']?>" data-startTime="<?php echo $curDateTime; ?>"  data-endTime="<?php echo $startTime; ?>"></span>
								</span>
							</li>
					<?php
						}else{
							if(strtotime($endTime) > strtotime($curDateTime)) {
								$lessonInfoLblKey = 'LBL_Lesson_ongoing';
							} else {
								$lessonInfoLblKey = 'LBL_Lesson_time_has_passed';
							}
						?>
						<li class="span-right">
							<span class="-color-secondary"><?php echo Label::getLabel($lessonInfoLblKey); ?></span>
						</li>
						<?php
							}
						}
					?>
				</ul>
				</div>
				<ul class="actions">
					<li>
						<a href="<?php echo CommonHelper::generateUrl('TeacherScheduledLessons','view',[$lesson['slesson_id']]); ?>" class="" title="<?php echo Label::getLabel('LBL_View'); ?>">
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
					<?php if($referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('teacher-scheduled-lessons'))){ ?>

					<?php if(($lesson['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING || $lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED) && $curDateTime<$startTime) { ?>
						<li>
							<a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lesson['slesson_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Cancel_Lesson'); ?>">
								<svg  width="14px" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
							</a>
						</li>
					<?php } ?>

					<?php if( $lesson['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED || $lesson['issrep_id'] > 0) { ?>
						<li>
							<a href="javascript:void(0);" onclick="issueReportedDetails('<?php echo $lesson['slesson_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Issue_Details'); ?>">
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
					<?php }
					 ?>
					<?php if( $lesson['issrep_id']>0 && $lesson['issrep_status'] == 0) { ?>
						<li>
							<a href="javascript:void(0);"  onclick="resolveIssue('<?php echo $lesson['slesson_id'].','.$lesson['issrep_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Check_And_Resolve_Issue'); ?>">
								<svg width="30px" viewBox="0 -1 512.0004 512" xmlns="http://www.w3.org/2000/svg"><path d="m476.195312 356.402344c-9.472656-92.132813-124.835937-131.347656-187.847656-61.082032-62.253906 69.429688-12.839844 180.179688 80.21875 180.179688 64.164063.003906 114.167969-55.503906 107.628906-119.097656zm-107.636718 103.988281c-46.960938 0-87.363282-35.601563-92.28125-83.433594-8.792969-85.554687 92.207031-134.558593 154.53125-78.648437 62.640625 56.199218 23.917968 162.082031-62.25 162.082031zm0 0"/><path d="m436.449219 325.722656c-2.953125-2.949218-7.738281-2.949218-10.6875 0l-84.945313 84.945313-35.800781-35.796875c-2.949219-2.949219-7.734375-2.953125-10.683594 0-2.953125 2.949218-2.953125 7.734375 0 10.683594l36.640625 36.640624c5.4375 5.445313 14.242188 5.445313 19.683594.003907l85.792969-85.792969c2.949219-2.949219 2.949219-7.730469 0-10.683594zm0 0"/><path d="m275.015625 49.089844h-212.546875c-11.492188 0-20.84375 9.359375-20.84375 20.855468v74.085938c0 11.496094 9.351562 20.851562 20.84375 20.851562h212.546875c11.496094 0 20.84375-9.355468 20.84375-20.851562v-74.085938c0-11.496093-9.347656-20.855468-20.84375-20.855468zm5.730469 94.941406c0 3.164062-2.566406 5.742188-5.730469 5.742188h-212.546875c-3.160156 0-5.730469-2.578126-5.730469-5.742188v-74.085938c0-3.164062 2.570313-5.742187 5.730469-5.742187h212.546875c3.164063 0 5.730469 2.578125 5.730469 5.742187zm0 0"/><path d="m255.714844 80.5625h-173.941406c-4.175782 0-7.558594 3.382812-7.558594 7.554688 0 4.175781 3.382812 7.558593 7.558594 7.558593h173.941406c4.171875 0 7.554687-3.382812 7.554687-7.558593 0-4.171876-3.382812-7.554688-7.554687-7.554688zm0 0"/><path d="m208.789062 118.300781h-28.289062c-4.171875 0-7.554688 3.386719-7.554688 7.554688 0 4.183593 3.382813 7.558593 7.554688 7.558593h28.289062c4.167969 0 7.554688-3.375 7.554688-7.558593 0-4.167969-3.386719-7.554688-7.554688-7.554688zm0 0"/><path d="m150.277344 118.300781h-68.503906c-4.171876 0-7.558594 3.386719-7.558594 7.554688 0 4.183593 3.386718 7.558593 7.558594 7.558593h68.503906c4.171875 0 7.554687-3.375 7.554687-7.558593 0-4.167969-3.382812-7.554688-7.554687-7.554688zm0 0"/><path d="m255.714844 228.953125h-173.941406c-4.175782 0-7.558594 3.382813-7.558594 7.558594 0 4.171875 3.382812 7.554687 7.558594 7.554687h173.941406c4.171875 0 7.554687-3.382812 7.554687-7.554687 0-4.175781-3.382812-7.558594-7.554687-7.558594zm0 0"/><path d="m131.351562 266.695312h-49.578124c-4.175782 0-7.558594 3.382813-7.558594 7.554688 0 4.175781 3.382812 7.558594 7.558594 7.558594h49.578124c4.171876 0 7.554688-3.382813 7.554688-7.558594 0-4.171875-3.382812-7.554688-7.554688-7.554688zm0 0"/><path d="m81.773438 392.457031h124.496093c4.171875 0 7.554688-3.382812 7.554688-7.554687 0-4.175782-3.382813-7.558594-7.554688-7.558594h-124.496093c-4.175782 0-7.558594 3.382812-7.558594 7.558594 0 4.171875 3.382812 7.554687 7.558594 7.554687zm0 0"/><path d="m142.21875 415.085938h-60.445312c-4.171876 0-7.558594 3.386718-7.558594 7.558593 0 4.167969 3.386718 7.554688 7.558594 7.554688h60.445312c4.167969 0 7.554688-3.386719 7.554688-7.554688 0-4.171875-3.386719-7.558593-7.554688-7.558593zm0 0"/><path d="m218.832031 415.085938h-46.390625c-4.171875 0-7.558594 3.386718-7.558594 7.558593 0 4.167969 3.386719 7.554688 7.558594 7.554688h46.390625c4.179688 0 7.554688-3.386719 7.554688-7.554688 0-4.171875-3.375-7.558593-7.554688-7.558593zm0 0"/><path d="m464.441406 260.8125c-35.535156-31.882812-83.042968-43.054688-126.957031-33.214844v-203.296875c0-13.398437-10.898437-24.300781-24.308594-24.300781h-288.867187c-13.410156 0-24.308594 10.902344-24.308594 24.300781v462.164063c0 13.398437 10.898438 24.296875 24.308594 24.296875h288.867187c5.933594 0 11.375-2.125 15.59375-5.660157 30.3125 8.808594 62.25 7.109376 90.527344-3.539062 3.910156-1.46875 5.882813-5.832031 4.414063-9.730469-1.472657-3.90625-5.832032-5.882812-9.730469-4.410156-44.582031 16.75-96.628907 7.976563-133.253907-26.546875-83.785156-78.9375-28.644531-221.621094 88.160157-221.621094 65.855469 0 120.609375 49.472656 127.347656 115.085938 4.953125 48.210937-17.320313 93.960937-56.898437 120.136718-3.484376 2.296876-4.441407 6.980469-2.136719 10.464844 2.296875 3.484375 6.992187 4.433594 10.46875 2.136719 78.746093-52.058594 86.222656-163.960937 16.773437-226.265625zm-215.226562 185.746094h-186.746094c-3.160156 0-5.730469-2.578125-5.730469-5.742188v-74.085937c0-3.164063 2.570313-5.742188 5.730469-5.742188h163.082031c-1.269531 29.382813 6.363281 59.460938 23.664063 85.570313zm31.53125-192.234375c-15.316406 11.921875-28.125 26.917969-37.484375 43.84375h-180.792969c-3.160156 0-5.730469-2.582031-5.730469-5.742188v-74.085937c0-3.164063 2.570313-5.742188 5.730469-5.742188h212.546875c3.164063 0 5.730469 2.578125 5.730469 5.742188zm41.628906-22.476563c-9.25 3.144532-18.125 7.234375-26.515625 12.191406v-25.699218c0-11.496094-9.347656-20.855469-20.84375-20.855469h-212.546875c-11.492188 0-20.84375 9.359375-20.84375 20.855469v74.085937c0 11.492188 9.351562 20.851563 20.84375 20.851563h173.558594c-4.269532 10.445312-7.300782 21.386718-8.996094 32.601562h-164.5625c-11.492188 0-20.84375 9.359375-20.84375 20.851563v74.085937c0 11.496094 9.351562 20.855469 20.84375 20.855469h198.28125c12.085938 13.878906 27.125 25.601563 43.914062 33.980469h-280.355468c-5.078125 0-9.199219-4.121094-9.199219-9.1875v-462.164063c0-5.070312 4.121094-9.1875 9.199219-9.1875h288.867187c5.078125 0 9.199219 4.117188 9.199219 9.1875zm0 0"/></svg>
							</a>
						</li>
					<?php } ?>
					<?php if ($lesson['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED && $lesson['issrep_status'] == 1 && $lesson['issrep_issues_resolve_type'] < 1) { ?>
						<li>
							<a href="javascript:void(0);" onclick="issueResolveStepTwo('<?php echo $lesson['issrep_id']; ?>', '<?php echo $lesson['slesson_id']; ?>')" title="<?php echo Label::getLabel('LBL_Resolve_Issue'); ?>">
								<svg width="30px" viewBox="0 -1 512.0004 512"  xmlns="http://www.w3.org/2000/svg"><path d="m476.195312 356.402344c-9.472656-92.132813-124.835937-131.347656-187.847656-61.082032-62.253906 69.429688-12.839844 180.179688 80.21875 180.179688 64.164063.003906 114.167969-55.503906 107.628906-119.097656zm-107.636718 103.988281c-46.960938 0-87.363282-35.601563-92.28125-83.433594-8.792969-85.554687 92.207031-134.558593 154.53125-78.648437 62.640625 56.199218 23.917968 162.082031-62.25 162.082031zm0 0"/><path d="m436.449219 325.722656c-2.953125-2.949218-7.738281-2.949218-10.6875 0l-84.945313 84.945313-35.800781-35.796875c-2.949219-2.949219-7.734375-2.953125-10.683594 0-2.953125 2.949218-2.953125 7.734375 0 10.683594l36.640625 36.640624c5.4375 5.445313 14.242188 5.445313 19.683594.003907l85.792969-85.792969c2.949219-2.949219 2.949219-7.730469 0-10.683594zm0 0"/><path d="m275.015625 49.089844h-212.546875c-11.492188 0-20.84375 9.359375-20.84375 20.855468v74.085938c0 11.496094 9.351562 20.851562 20.84375 20.851562h212.546875c11.496094 0 20.84375-9.355468 20.84375-20.851562v-74.085938c0-11.496093-9.347656-20.855468-20.84375-20.855468zm5.730469 94.941406c0 3.164062-2.566406 5.742188-5.730469 5.742188h-212.546875c-3.160156 0-5.730469-2.578126-5.730469-5.742188v-74.085938c0-3.164062 2.570313-5.742187 5.730469-5.742187h212.546875c3.164063 0 5.730469 2.578125 5.730469 5.742187zm0 0"/><path d="m255.714844 80.5625h-173.941406c-4.175782 0-7.558594 3.382812-7.558594 7.554688 0 4.175781 3.382812 7.558593 7.558594 7.558593h173.941406c4.171875 0 7.554687-3.382812 7.554687-7.558593 0-4.171876-3.382812-7.554688-7.554687-7.554688zm0 0"/><path d="m208.789062 118.300781h-28.289062c-4.171875 0-7.554688 3.386719-7.554688 7.554688 0 4.183593 3.382813 7.558593 7.554688 7.558593h28.289062c4.167969 0 7.554688-3.375 7.554688-7.558593 0-4.167969-3.386719-7.554688-7.554688-7.554688zm0 0"/><path d="m150.277344 118.300781h-68.503906c-4.171876 0-7.558594 3.386719-7.558594 7.554688 0 4.183593 3.386718 7.558593 7.558594 7.558593h68.503906c4.171875 0 7.554687-3.375 7.554687-7.558593 0-4.167969-3.382812-7.554688-7.554687-7.554688zm0 0"/><path d="m255.714844 228.953125h-173.941406c-4.175782 0-7.558594 3.382813-7.558594 7.558594 0 4.171875 3.382812 7.554687 7.558594 7.554687h173.941406c4.171875 0 7.554687-3.382812 7.554687-7.554687 0-4.175781-3.382812-7.558594-7.554687-7.558594zm0 0"/><path d="m131.351562 266.695312h-49.578124c-4.175782 0-7.558594 3.382813-7.558594 7.554688 0 4.175781 3.382812 7.558594 7.558594 7.558594h49.578124c4.171876 0 7.554688-3.382813 7.554688-7.558594 0-4.171875-3.382812-7.554688-7.554688-7.554688zm0 0"/><path d="m81.773438 392.457031h124.496093c4.171875 0 7.554688-3.382812 7.554688-7.554687 0-4.175782-3.382813-7.558594-7.554688-7.558594h-124.496093c-4.175782 0-7.558594 3.382812-7.558594 7.558594 0 4.171875 3.382812 7.554687 7.558594 7.554687zm0 0"/><path d="m142.21875 415.085938h-60.445312c-4.171876 0-7.558594 3.386718-7.558594 7.558593 0 4.167969 3.386718 7.554688 7.558594 7.554688h60.445312c4.167969 0 7.554688-3.386719 7.554688-7.554688 0-4.171875-3.386719-7.558593-7.554688-7.558593zm0 0"/><path d="m218.832031 415.085938h-46.390625c-4.171875 0-7.558594 3.386718-7.558594 7.558593 0 4.167969 3.386719 7.554688 7.558594 7.554688h46.390625c4.179688 0 7.554688-3.386719 7.554688-7.554688 0-4.171875-3.375-7.558593-7.554688-7.558593zm0 0"/><path d="m464.441406 260.8125c-35.535156-31.882812-83.042968-43.054688-126.957031-33.214844v-203.296875c0-13.398437-10.898437-24.300781-24.308594-24.300781h-288.867187c-13.410156 0-24.308594 10.902344-24.308594 24.300781v462.164063c0 13.398437 10.898438 24.296875 24.308594 24.296875h288.867187c5.933594 0 11.375-2.125 15.59375-5.660157 30.3125 8.808594 62.25 7.109376 90.527344-3.539062 3.910156-1.46875 5.882813-5.832031 4.414063-9.730469-1.472657-3.90625-5.832032-5.882812-9.730469-4.410156-44.582031 16.75-96.628907 7.976563-133.253907-26.546875-83.785156-78.9375-28.644531-221.621094 88.160157-221.621094 65.855469 0 120.609375 49.472656 127.347656 115.085938 4.953125 48.210937-17.320313 93.960937-56.898437 120.136718-3.484376 2.296876-4.441407 6.980469-2.136719 10.464844 2.296875 3.484375 6.992187 4.433594 10.46875 2.136719 78.746093-52.058594 86.222656-163.960937 16.773437-226.265625zm-215.226562 185.746094h-186.746094c-3.160156 0-5.730469-2.578125-5.730469-5.742188v-74.085937c0-3.164063 2.570313-5.742188 5.730469-5.742188h163.082031c-1.269531 29.382813 6.363281 59.460938 23.664063 85.570313zm31.53125-192.234375c-15.316406 11.921875-28.125 26.917969-37.484375 43.84375h-180.792969c-3.160156 0-5.730469-2.582031-5.730469-5.742188v-74.085937c0-3.164063 2.570313-5.742188 5.730469-5.742188h212.546875c3.164063 0 5.730469 2.578125 5.730469 5.742188zm41.628906-22.476563c-9.25 3.144532-18.125 7.234375-26.515625 12.191406v-25.699218c0-11.496094-9.347656-20.855469-20.84375-20.855469h-212.546875c-11.492188 0-20.84375 9.359375-20.84375 20.855469v74.085937c0 11.492188 9.351562 20.851563 20.84375 20.851563h173.558594c-4.269532 10.445312-7.300782 21.386718-8.996094 32.601562h-164.5625c-11.492188 0-20.84375 9.359375-20.84375 20.851563v74.085937c0 11.496094 9.351562 20.855469 20.84375 20.855469h198.28125c12.085938 13.878906 27.125 25.601563 43.914062 33.980469h-280.355468c-5.078125 0-9.199219-4.121094-9.199219-9.1875v-462.164063c0-5.070312 4.121094-9.1875 9.199219-9.1875h288.867187c5.078125 0 9.199219 4.117188 9.199219 9.1875zm0 0"/></svg>
							</a>
						</li>
					<?php } ?>
					<?php if ($lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED && $curDateTime<$startTime) { ?>
						<li>
							<a  href="javascript:void(0);" onclick="requestReschedule('<?php echo $lesson['slesson_id']; ?>')" title="<?php echo Label::getLabel('LBL_Reschedule_Lesson'); ?>">
								<svg version="1.1" width="18px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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
					<li>
						<a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lesson['slesson_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?>">
							<svg   width="35px" enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m454.808 33.134h-9.067v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067-13.271 0-24.067 10.796-24.067 24.067v9.067h-9.068c-22.405 0-40.632 18.228-40.632 40.632v183.537l-18.346-18.346c-9.384-9.384-24.652-9.383-34.036 0l-23.429 23.429c-9.384 9.383-9.384 24.652 0 34.035l75.81 75.81v99.136c0 22.405 18.228 40.633 40.632 40.633h255.136c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-255.135c-14.134 0-25.632-11.499-25.632-25.633v-84.136l61.184 61.184c.178.178.364.346.557.503.131.107.267.202.403.299.064.045.124.096.189.139.172.115.349.217.528.316.034.019.066.041.1.059.189.101.382.189.576.272.029.012.056.028.086.04.189.078.381.144.574.206.04.013.078.029.118.041.182.055.366.098.55.138.055.012.108.029.163.04.179.035.359.058.54.08.063.008.124.021.187.027.243.024.488.036.732.036h43.751l9.518 9.518c1.464 1.464 3.384 2.197 5.303 2.197s3.839-.733 5.303-2.197c2.929-2.929 2.929-7.678 0-10.607l-9.518-9.517v-43.749c0-.246-.012-.491-.036-.736-.005-.054-.017-.106-.023-.16-.023-.19-.048-.379-.085-.567-.01-.048-.024-.095-.035-.143-.042-.191-.087-.382-.143-.57-.01-.034-.024-.066-.035-.1-.063-.199-.132-.397-.212-.592-.009-.021-.02-.041-.029-.062-.086-.203-.179-.404-.283-.6-.014-.026-.03-.049-.044-.075-.103-.188-.211-.373-.331-.553-.037-.056-.081-.108-.12-.163-.102-.145-.204-.29-.317-.429-.158-.193-.325-.379-.503-.557l-61.185-61.185h258.087c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-2.203 0-4.179.956-5.551 2.469l-44.933-44.933v-141.333h366.034v51.151c0 4.142 3.358 7.5 7.5 7.5s7.5-3.358 7.5-7.5v-108.352c.001-22.405-18.227-40.632-40.632-40.632zm-234.556 402.478h-21.251l21.251-21.251zm-157.317-121.065 12.823-12.822 117.959 117.959-12.822 12.822zm141.388 94.53-117.959-117.959 12.823-12.822 117.959 117.959zm-170.12-136.084 23.429-23.429c3.535-3.535 9.288-3.536 12.823 0l18.126 18.126-36.252 36.25-18.125-18.125c-3.536-3.535-3.536-9.287-.001-12.822zm387.471-257.993c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c.001-5 4.068-9.067 9.067-9.067zm-82.833 0c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c0-5 4.067-9.067 9.067-9.067zm-91.9 9.067c0-5 4.067-9.067 9.067-9.067s9.067 4.067 9.067 9.067v16.557.01s0 .006 0 .01v16.556c0 5-4.067 9.067-9.067 9.067-4.999 0-9.067-4.067-9.067-9.067zm-73.767-9.067c4.999 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-33.133c0-5 4.067-9.067 9.067-9.067zm-58.767 100.967v-42.201c0-14.134 11.499-25.632 25.632-25.632h9.068v9.066c0 13.271 10.796 24.067 24.067 24.067 13.27 0 24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h9.067c14.134 0 25.633 11.499 25.633 25.632v42.201z"/><path d="m487.941 204.619c-4.142 0-7.5 3.358-7.5 7.5v259.248c0 14.134-11.499 25.633-25.633 25.633h-29.634c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h29.634c22.405 0 40.633-18.228 40.633-40.633v-259.248c0-4.142-3.358-7.5-7.5-7.5z"/><path d="m164.89 180.667h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 230.367h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 280.067h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m437.458 371.967c0-4.142-3.358-7.5-7.5-7.5h-173.95c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h173.95c4.142 0 7.5-3.358 7.5-7.5z"/></g></svg>
						</a>
					</li>
					<?php $countRel = ScheduledLessonSearch::countPlansRelation($lesson['slesson_id']);
                    if($lesson['slesson_status'] != ScheduledLesson::STATUS_CANCELLED) {
                    if($countRel > 0){
					?>
					<li>
						<a href="javascript:void(0);" onclick="changeLessonPlan('<?php echo $lesson['slesson_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Change_Lesson_Plan'); ?>">
							<svg xmlns="http://www.w3.org/2000/svg"  width="35px" viewBox="0 0 512 512" width="512" ><g id="outline"><path d="M136,120H312a8,8,0,0,0,0-16H136a8,8,0,0,0,0,16Z"/><path d="M136,168H312a8,8,0,0,0,0-16H136a8,8,0,0,0,0,16Z"/><path d="M136,216H312a8,8,0,0,0,0-16H136a8,8,0,0,0,0,16Z"/><path d="M136,264H312a8,8,0,0,0,0-16H136a8,8,0,0,0,0,16Z"/><path d="M136,312h64a8,8,0,0,0,0-16H136a8,8,0,0,0,0,16Z"/><path d="M452.132,205.015a24.024,24.024,0,0,0-32.841,8.571L400,246.5V48a8,8,0,0,0-8-8H331.314s-24.038-24.48-25-23.656A7.976,7.976,0,0,0,304,16H72a8,8,0,0,0-8,8V49.013a32,32,0,0,0,0,61.974v18.026a32,32,0,0,0,0,61.974v18.026a32,32,0,0,0,0,61.974v10.026a32,32,0,0,0,0,61.974V392a8,8,0,0,0,8,8h8v24a8,8,0,0,0,8,8H291.292l-5.482,9.354a7.938,7.938,0,0,0-.981,2.721c-.008.046-.021.085-.028.132l-.02.131,0,.03-6.4,42.44a8,8,0,0,0,12.817,7.511L325.226,467.9c2.18-1.693,3.245-2.729,3.076-4.119L346.926,432H392a8,8,0,0,0,8-8V341.438l60.7-103.583A24.027,24.027,0,0,0,452.132,205.015ZM437.977,217.98a8,8,0,0,1,8.922,11.786l-4.045,6.9-13.8-8.09,4.046-6.9A7.944,7.944,0,0,1,437.977,217.98ZM317.462,450.632l-13.8-8.09L408.826,263.089l13.8,8.09ZM312,43.314,340.686,72H312Zm104.916,205.97,4.044-6.9,13.805,8.09-4.045,6.9ZM384,56V272a8.041,8.041,0,0,0,.152,1.546L368,301.108V80a7.978,7.978,0,0,0-2.336-5.649l-.007-.008L347.314,56ZM80,32H296V80a8,8,0,0,0,8,8h48V328.41L319.422,384H80V342.988a31.974,31.974,0,0,0,22.991-22.994,8,8,0,0,0-15.5-3.988A15.948,15.948,0,0,1,80,325.85V270.988a31.974,31.974,0,0,0,22.991-22.994,8,8,0,0,0-15.5-3.988A15.948,15.948,0,0,1,80,253.85V190.988a31.974,31.974,0,0,0,22.991-22.994,8,8,0,0,0-15.5-3.988A15.948,15.948,0,0,1,80,173.85V110.988a31.974,31.974,0,0,0,22.991-22.994,8,8,0,0,0-15.5-3.988A15.948,15.948,0,0,1,80,93.85ZM56,80a16,16,0,0,1,8-13.835v27.67A16,16,0,0,1,56,80Zm0,80a16,16,0,0,1,8-13.835v27.67A16,16,0,0,1,56,160Zm0,80a16,16,0,0,1,8-13.835v27.67A16,16,0,0,1,56,240Zm0,72a16,16,0,0,1,8-13.835v27.67A16,16,0,0,1,56,312ZM96,416V400H310.045l-9.377,16Zm202.861,42.276,7.219,4.231-8.9,6.908ZM384,416H356.3L384,368.739Z"/></g></svg>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="removeAssignedLessonPlan('<?php echo $lesson['slesson_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Remove_Lesson_Plan'); ?>">
							<svg  width="35px" viewBox="0 0 512 512"  xmlns="http://www.w3.org/2000/svg"><path d="m256 512c-141.164062 0-256-114.835938-256-256s114.835938-256 256-256 256 114.835938 256 256-114.835938 256-256 256zm0-480c-123.519531 0-224 100.480469-224 224s100.480469 224 224 224 224-100.480469 224-224-100.480469-224-224-224zm0 0"/><path d="m176.8125 351.1875c-4.097656 0-8.195312-1.554688-11.308594-4.691406-6.25-6.25-6.25-16.382813 0-22.632813l158.398438-158.402343c6.253906-6.25 16.386718-6.25 22.636718 0s6.25 16.382812 0 22.636718l-158.402343 158.398438c-3.15625 3.136718-7.25 4.691406-11.324219 4.691406zm0 0"/><path d="m335.1875 351.1875c-4.09375 0-8.191406-1.554688-11.304688-4.691406l-158.398437-158.378906c-6.253906-6.25-6.253906-16.382813 0-22.632813 6.25-6.253906 16.382813-6.253906 22.632813 0l158.398437 158.398437c6.253906 6.25 6.253906 16.382813 0 22.632813-3.132813 3.117187-7.230469 4.671875-11.328125 4.671875zm0 0"/></svg>
						</a>
					</li>

				<?php }else{ ?>
					<li>
						<a href="javascript:void(0);" onclick="listLessonPlans('<?php echo $lesson['slesson_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Attach_Lesson_Plan'); ?>">
							<svg version="1.1" width="21px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							viewBox="0 0 494.398 494.398" style="enable-background:new 0 0 494.398 494.398;" xml:space="preserve">
								<path d="M247.199,0C173.289,0,113.14,60.131,113.14,134.061v253.806c0,9.06,7.331,16.392,16.393,16.392
								c9.056,0,16.387-7.331,16.387-16.392V134.061c0-55.844,45.424-101.28,101.279-101.28c55.861,0,101.284,45.436,101.284,101.28
								v261.668c0,36.325-29.562,65.89-65.896,65.89c-0.27,0-0.513,0.145-0.782,0.159c-0.288-0.015-0.513-0.159-0.8-0.159
								c-36.331,0-65.893-29.564-65.893-65.89V238.766c0-17.703,14.387-32.091,32.087-32.091c17.704,0,32.092,14.388,32.092,32.091v149.101
								c0,9.06,7.344,16.392,16.388,16.392c9.042,0,16.392-7.331,16.392-16.392V238.766c0-35.773-29.099-64.871-64.872-64.871
								c-35.768,0-64.867,29.099-64.867,64.871v156.963c0,54.398,44.255,98.67,98.672,98.67c0.287,0,0.512-0.145,0.8-0.161
								c0.27,0.017,0.513,0.161,0.782,0.161c54.404,0,98.671-44.271,98.671-98.67V134.061C381.258,60.131,321.11,0,247.199,0z"/>
							</svg>
						</a>
					</li>
				<?php
						}
					}
                }
				?>
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
	if ($referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('teacher-scheduled-lessons'))) {
		$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
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