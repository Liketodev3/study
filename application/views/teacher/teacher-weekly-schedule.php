<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$user_timezone = MyDate::getUserTimeZone();
$nowDate = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$getAllMonthName =  CommonHelper::getAllMonthName();
$weekDayName =  CommonHelper::dayNames();
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
<style>
.fc button.fc-time-button{display:none;}
</style>

<script>
var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
var userId = '<?php echo $userId ?>';
   
var calendarEl = document.getElementById('w_calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
        left: 'time',
        center: 'title',
		right: 'prev,next today'
	},
	buttonText :{
		today:    '<?php echo Label::getLabel('LBL_Today'); ?>',
	},
	/* monthNames: <?php echo  json_encode($getAllMonthName['monthNames']); ?>,
	monthNamesShort: <?php echo  json_encode($getAllMonthName['monthNamesShort']); ?>,
	dayNames: <?php echo  json_encode($weekDayName['dayNames']); ?>,
	dayNamesShort: <?php echo  json_encode($weekDayName['dayNamesShort']); ?>, */
	initialView: 'timeGridWeek',
	selectable: true,
	editable: true,
	nowIndicator:true,
	now:'<?php echo $nowDate; ?>',
	selectOverlap: false,
	eventOverlap: false,
	slotEventOverlap : false,
	// forceEventDuration : true,
	selectLongPressDelay:50,
	eventLongPressDelay:50,
	longPressDelay:50,
	overlap: false,
	defaultTimedEventDuration : "00:30:00",
	// timeZone: '<?php echo $user_timezone; ?>',
	allDaySlot: false,
	select: function (arg ) {
		var start = arg.start;
        var end = arg.end;
		if(moment('<?php echo $nowDate; ?>').diff(moment(start)) >= 0) {
			calendar.unselect();
			return false;
		}
		if(moment(start).format('d')!=moment(end).format('d') ) {
			calendar.unselect();
			return false;
		}
		var newEvent = new Object();
		newEvent.title = '';
		newEvent.start = moment(start).format();
		newEvent.end = moment(end).format(),
		newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>',
		newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>',
		newEvent.date = moment(start).format('YYYY-MM-DD');
		newEvent.overlap = false;
		newEvent.allday = false;
		
		var addEventFlag = true;
        var events = calendar.getEvents();
        for(i in events){
            if(moment(end).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].start).format('YYYY-MM-DD HH:mm:ss')){
                addEventFlag = false;
                events[i].setStart(start);
            }else if(moment(start).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].end).format('YYYY-MM-DD HH:mm:ss')){
                addEventFlag = false;
                events[i].setEnd(end);
            }
        } 
        if(addEventFlag === true){
            calendar.addEvent(newEvent);
        }
	},
	// defaultDate: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
	loading :function( isLoading, view ) {
		if(isLoading == true){
			$("#loaderCalendar").show();
		}else{
			$("#loaderCalendar").hide();
		}
	},
	eventSources: [
        {
            url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData', [userId]),
            method: 'POST',
            success: function(docs){
				// console.log(doc);
				
            }
        }
    ],
	eventDrop: function(info){
        var start = info.event.start;
        var end = info.event.end;
        var events = calendar.getEvents();
        for(i in events){
            if(moment(end).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].start).format('YYYY-MM-DD HH:mm:ss')){
                info.event.remove();
                events[i].setStart(start);
            }else if(moment(start).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].end).format('YYYY-MM-DD HH:mm:ss')){
                info.event.remove();
                events[i].setEnd(end);
            }
        }
	},
	eventClick: function(arg) {
        if (confirm(langLbl.confirmRemove)) {
        	arg.event.remove()
        }
    }
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