<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$user_timezone = MyDate::getUserTimeZone();
$nowDate = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );
?>

<h6><?php echo Label::getLabel('LBL_Weekly_Availability_Note'); ?></h6>
<br />
<button onclick="setUpWeeklyAvailability();" class="btn btn--secondary"><?php echo Label::getLabel( 'LBL_Save' );?></button>
<span class="-gap"></span>

<div class="calendar-view -no-padding">
	<div id="loaderCalendar" style="display: none;">
		<div class="loader"></div>
	</div>
	<span> <?php echo MyDate::displayTimezoneString();?> </span>
</div>

<div id='calendar-container'>
	<div id='w_calendar'></div>
</div>

<script>
var fecal = new FatEventCalendar(<?php echo $userId; ?>);
fecal.setLocale('<?php echo $currentLangCode ?>');
var calendar = fecal.TeacherWeeklyAvailaibility( '<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>');
</script>