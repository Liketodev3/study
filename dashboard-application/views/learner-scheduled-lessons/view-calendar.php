<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$layoutDirection = CommonHelper::getLayoutDirection();
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
?>
<div class="results page-panel montly-lesson-calendar margin-top-6">


	<div id='calendar-container'>
		<div id='d_calendar' class="calendar-view"></div>
	</div>
</div>
<script>
var fecal = new FatEventCalendar(0);
fecal.setLocale('<?php echo $currentLangCode ?>');
fecal.LearnerMonthlyCalendar( '<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>');
</script>