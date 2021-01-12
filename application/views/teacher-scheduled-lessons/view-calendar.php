<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$layoutDirection = CommonHelper::getLayoutDirection();
$weekDayName =  CommonHelper::dayNames();

$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
$getAllMonthName =  CommonHelper::getAllMonthName();
?>

<div class="calendar-view">
	<span> <?php echo MyDate::displayTimezoneString();?> </span>
</div>

<div id='calendar-container'>
	<div id='listing_calendar'></div>
</div>
<script>
var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
var calendarEl = document.getElementById('listing_calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
	headerToolbar: {
        left: 'time'
    },
    nowIndicator: true,
    locale: '<?php echo $currentLangCode ?>',
	selectable: true,
	editable: false,
	selectOverlap: false,
	eventOverlap: false,
	slotEventOverlap : false,
	allDaySlot: false,
	timeZone: "<?php echo $user_timezone; ?>",
	select: function (arg) {
		var start = arg.start;
		var end = arg.end;
		if(moment(start).format('d')!=moment(end).format('d') ) {
			calender.unselect();
			return false;
		}
	},
	eventTimeFormat : {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: true
    },
	events: "<?php echo CommonHelper::generateUrl('TeacherScheduledLessons','calendarJsonData'); ?>"
});
   
calendar.render();

$('body').find(".fc-time-button").parent().html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'>"+moment('<?php echo $nowDate; ?>').format('hh:mm A')+"</span></h6>");
var timeInterval;
var seconds = 2;
clearInterval(timeInterval);
timeInterval = setInterval(currentTimer, 1000);
function currentTimer() {
    $('body').find(".fc-toolbar-ltr h6 span.timer").html(moment('<?php echo $nowDate; ?>').add(seconds,'seconds').format('hh:mm A'));
    seconds++;
}
</script>