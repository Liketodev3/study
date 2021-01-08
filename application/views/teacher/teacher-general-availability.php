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
<button id='setUpGABtn' class="btn btn--secondary"><?php echo Label::getLabel('LBL_Save'); ?></button>
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
    var timeInterval;
    var userId = '<?php echo $userId ?>';
    var seconds = 2;
    clearInterval(timeInterval);
    timeInterval = setInterval(currentTimer, 1000);
    function currentTimer() {
      $('body').find(".fc-left h6 span.timer").html(moment('<?php echo $nowDate; ?>').add(seconds,'seconds').format('hh:mm A'));
      seconds++;
    }
      
    $("#setUpGABtn").click(function () {
        var allevents = calendar.getEvents();
        let data = allevents.map(function (e) {
            return {
                start: moment(e.start).format('HH:mm:ss'),
                end: moment(e.end).format('HH:mm:ss'),
                startTime: moment(e.start).format('HH:mm'),
                endTime: moment(e.end).format('HH:mm'),
                day: moment(e.start).format('d'),
                dayStart: moment(e.start).format('d'),
                dayEnd: moment(e.end).format('d'),
                classtype: e.classType,
            };
        });

        data.forEach(element => {

            if ((element.dayStart != element.dayEnd)
                &&
                ((element.endTime != '00:00') || (element.startTime == '00:00'))
            ) {

                if ((element.dayEnd - element.dayStart == 1)) {

                    if ((element.endTime != '00:00') || (element.startTime != '00:00')) {

                        let elementClone = $.parseJSON(JSON.stringify(element));
                        elementClone.day = parseInt(element.dayEnd);
                        elementClone.start = '00:00:00';
                        elementClone.startTime = '00:00';
                        data[data.length] = elementClone;
                    }
                } else {

                    for (let index = 0; index < element.dayEnd - element.dayStart; index++) {

                        if ((element.endTime == '00:00') && (parseInt(element.dayStart) + index + 1 == element.dayEnd)) {

                            continue;
                        }
                        let elementClone = $.parseJSON(JSON.stringify(element));
                        elementClone.day = parseInt(element.dayStart) + index + 1;
                        elementClone.start = '00:00:00';
                        elementClone.startTime = '00:00';
                        if (index + 1 != element.dayEnd - element.dayStart) {
                            elementClone.end = '24:00:00';
                            elementClone.endTime = '24:00';
                        }
                        data[data.length] = elementClone;
                    }
                }
                element.end = '24:00:00';
                element.endTime = '24:00';
            }
        });
        var json = JSON.stringify(data);

        setupTeacherGeneralAvailability(json);
    });
    
    var calendarEl = document.getElementById('ga_calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: '',
            center: '',
            right: ''
        },
        buttonText :{
            today: '<?php echo Label::getLabel('LBL_Today'); ?>',
        },
        /* monthNames: <?php echo  json_encode($getAllMonthName['monthNames']); ?>,
        monthNamesShort: <?php echo  json_encode($getAllMonthName['monthNamesShort']); ?>,
        dayNames: <?php echo  json_encode($weekDayName['dayNames']); ?>,
        dayNamesShort: <?php echo  json_encode($weekDayName['dayNamesShort']); ?>, */
        initialView: 'timeGridWeek',
        selectable: true,
        editable: true,
        selectLongPressDelay:50,
        eventLongPressDelay:50,
        longPressDelay:50,
        // allDaySlot: false,
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
            // newEvent.display = 'background',
            // newEvent.backgroundColor = 'none',
            newEvent.overlap = true,
            // newEvent.allday = false;
            // console.log('new Event:', newEvent);
            calendar.addEvent(newEvent);
            // $('#ga_calendar').fullCalendar('renderEvent',newEvent);
            // mergeEvents();
        },/* 
        eventClick: function(arg) {
            if (confirm('Are you sure you want to delete this event?')) {
                arg.event.remove()
            }
        }, */
        now: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData',[userId]),
                method: 'POST',
                success: function(doc){
                    // console.log(doc);
                }
            }
        ],
        eventSourceSuccess: function(events, xhr){
            /* for(i in events){
                var event = events[i];
                console.log(event, xhr);
                if(isNaN(event._id)){
                    element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
                }
                else{
                    element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherGeneralAvailability("+event._id+");'>X</span>" );
                }
                element.find(".closeon").click(function() {
                    if(isNaN(event._id)){
                        // $('#ga_calendar').fullCalendar('removeEvents',event._id);
                        var event = calendar.getEventById(event.id);
                        event.remove();
                    }
                });
            } */
            // mergeEvents();
        },
        loading :function( isLoading, view ) {
            if(isLoading == true){
                $("#loaderCalendar").show();
            }else{
                $("#loaderCalendar").hide();
            }
        },
        /* eventRender: function(event, element) {
            
            if(isNaN(event._id)){
                element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
            }
            else{
                element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherGeneralAvailability("+event._id+");'>X</span>" );
            }
            element.find(".closeon").click(function() {
                if(isNaN(event._id)){
                    $('#ga_calendar').fullCalendar('removeEvents',event._id);
                }
            });
            // mergeEvents();
        }, */
    });
    calendar.render();

    $('body').find(".fc-left").html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'></span></h6>");

var current_day = '<?php echo strtolower(date('D')); ?>';
$(document).ready(function(){
    $(".fc-view-container").find('.fc-'+current_day).addClass('fc-today');
})
</script>
