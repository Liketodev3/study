<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$user_timezone = MyDate::getUserTimeZone();
$nowDate = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$getAllMonthName =  CommonHelper::getAllMonthName();
$weekDayName =  CommonHelper::dayNames();
?>
<h6><?php echo Label::getLabel('LBL_General_Availability_Note'); ?></h6>
<br />
<button class="btn btn--secondary" onclick="saveGeneralAvailability();"><?php echo Label::getLabel('LBL_Save'); ?></button>
<span class="-gap"></span>
<div class="calendar-view -no-padding">
    <div id="loaderCalendar" style="display: none;">
        <div class="loader"></div>
    </div>
    <span> <?php echo MyDate::displayTimezoneString();?> </span>
</div>

<div id='calendar-container'>
    <div id='ga_calendar'></div>
</div>

<script>
var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
var userId = '<?php echo $userId ?>';

var calendarEl = document.getElementById('ga_calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
        left: 'time',
        center: '',
        right: ''
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
    selectLongPressDelay:50,
    eventLongPressDelay:50,
    longPressDelay:50,
    eventTimeFormat : {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: true
    },
    allDaySlot: false,
    // columnHeaderFormat :"ddd",
    // timeZone: '<?php echo $user_timezone; ?>',
    select: function (arg ) {
        var start = arg.start;
        var end = arg.end;
        if(moment(start).format('d') != moment(end).format('d') ) {
            calendar.unselect();
            return false;
        }
        var newEvent = new Object();
        newEvent.title = '';
        newEvent.start = moment(start).format('YYYY-MM-DD')+"T"+moment(start).format('HH:mm:ss');
        newEvent.end = moment(end).format('YYYY-MM-DD')+"T"+moment(end).format('HH:mm:ss'),
        newEvent.startTime = moment(start).format('HH:mm:ss');
        newEvent.endTime = moment(end).format('HH:mm:ss'),
        newEvent.daysOfWeek = moment(start).format('d'),
        newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>',
        newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>',
        newEvent.allday = false;
        newEvent.overlap = false;
        
        var events = calendar.getEvents();
        for(i in events){
            if(moment(end).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].start).format('YYYY-MM-DD HH:mm:ss')){
                newEvent.end = moment(events[i].end).format('YYYY-MM-DD')+"T"+moment(events[i].end).format('HH:mm:ss');
                newEvent.endTime = moment(events[i].end).format('HH:mm:ss');
                events[i].remove();
            }else if(moment(start).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].end).format('YYYY-MM-DD HH:mm:ss')){
                newEvent.start = moment(events[i].start).format('YYYY-MM-DD')+"T"+moment(events[i].start).format('HH:mm:ss');
                newEvent.startTime = moment(events[i].start).format('HH:mm:ss');
                events[i].remove();
            }
        }         
        calendar.addEvent(newEvent);
    },
    eventDrop: function(info){
        var start = info.event.start;
        var end = info.event.end;
        var events = calendar.getEvents();
        for(i in events){
            if(moment(end).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].start).format('YYYY-MM-DD HH:mm:ss')){
                info.event.setEnd(events[i].end);
                events[i].remove();
            }else if(moment(start).format('YYYY-MM-DD HH:mm:ss')==moment(events[i].end).format('YYYY-MM-DD HH:mm:ss')){
                info.event.setStart(events[i].start);
                events[i].remove();
            }
        }
    },
    now: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
    eventSources: [
        {
            url: fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData',[userId]),
            method: 'POST',
            success: function(docs){
                // console.log(doc);
                
            }
        }
    ],
    eventClick: function(arg) {
        if (confirm(langLbl.confirmRemove)) {
            arg.event.remove()
        }
    },
    loading :function( isLoading, view ) {
        if(isLoading == true){
            $("#loaderCalendar").show();
        }else{
            $("#loaderCalendar").hide();
        }
    },
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
