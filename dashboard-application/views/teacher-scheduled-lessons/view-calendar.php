<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
?>
<div class="results montly-lesson-calendar">
	<div class="calendar-view margin-top-10">
		<span> <?php echo MyDate::displayTimezoneString();?> </span>
	</div>
	<div id='calendar-container'>
		<div id='d_calendar'></div>
	</div>
</div>
<script>
var fecal = new FatEventCalendar(0);
fecal.setLocale('<?php echo $currentLangCode ?>');
fecal.TeacherMonthlyCalendar( '<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>');
</script>