<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$lesson_duration = $lessonRow['op_lesson_duration'];
$layoutDirection = CommonHelper::getLayoutDirection(); 
$teacherBookingBefore = (!empty($teacherBookingBefore)) ? $teacherBookingBefore : 0;
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
$isRescheduleRequest = (!empty($isRescheduleRequest));
$getAllMonthName =  CommonHelper::getAllMonthName();
$weekDayName =  CommonHelper::dayNames();
?>
<style>
.unavailable {
	background :#000;
}
</style>

<div id="loaderCalendar" style="display: none;"><div class="loader"></div></div>
<div class="calendar-view">
	<?php if($isRescheduleRequest) { ?>
	<div class="box">
        <h4><?php echo Label::getLabel('Lbl_Reschedule_Reason'); ?><span class="spn_must_field">*</span></h4>
        <?php $commentField =  $rescheduleRequestfrm->getField('reschedule_lesson_msg');
		$commentField->addFieldTagAttribute('placeholder',Label::getLabel('Lbl_Reschedule_Reason_*'));
		$commentField->addFieldTagAttribute('id','reschedule-reason-js');
		echo $commentField->getHTML(); ?>
	</div>
	<br>
	<br>
     <?php } ?>

    <div class="row">
        <div class="col-sm-5">
            <h4><?php echo $userRow['user_full_name']." ".Label::getLabel('Lbl_Calendar'); ?></h4>
        </div>

        <div class="col-sm-7 justify-content-sm-end justify-content-start">
            <div class="cal-status">
                <span class="ml-0 box-hint disabled-box">&nbsp;</span>
                <p><?php echo Label::getLabel('LBL_Not_Available'); ?></p>
            </div>
            <div class="cal-status">
                <span class="box-hint available-box">&nbsp;</span>
                <p><?php echo Label::getLabel('Lbl_Available'); ?></p>
            </div>
            <div class="cal-status">
                <span class="box-hint booked-box">&nbsp;</span>
                <p><?php echo Label::getLabel('Lbl_Booked'); ?></p>
            </div>
        </div>
    </div>
    <span> <?php echo MyDate::displayTimezoneString();?> </span>
</div>

<div id='calendar-container'>
    <div id='d_calendar'></div>
</div>

<script>
var isRescheduleRequest = <?php  echo (!empty($isRescheduleRequest)) ? 1 : 0 ; ?>;
var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
var checkSlotAvailabiltAjaxRun =  false;
var calendarEl = document.getElementById('d_calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    headerToolbar: {
        left: 'time',
        center: 'title',
        right: 'prev,next today'
    },
    views: {
        timeGridWeek: { // name of view
            titleFormat: {  month: 'short', day: '2-digit', year: 'numeric' }
        }
    },
    nowIndicator: true,
    locale: '<?php echo $currentLangCode ?>',
    navLinks: true, // can click day/week names to navigate views
    // dayMaxEvents: true, // allow "more" link when too many events
    eventOverlap: false,
    slotEventOverlap : false,
    defaultTimedEventDuration : "<?php echo gmdate("H:i:s", $lesson_duration*60); ?>",
    snapDuration : "<?php echo gmdate("H:i:s", $lesson_duration*60); ?>",
    duration : "<?php echo gmdate("H:i:s", $lesson_duration*60); ?>",
    slotDuration : '<?php echo gmdate("H:i:s", 15*60); ?>',
    allDaySlot: false,
    selectable: true,
    editable: false,
    forceEventDuration: true,
    now:'<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>',
    // timeZone: 'UTC',
    // unselectAuto: true,
    selectLongPressDelay: 50,
    eventLongPressDelay: 50,
    longPressDelay: 50,
    allDaySlot: false,
    timeZone: "<?php echo $user_timezone; ?>",
    loading: function( isLoading, view ) {
        if(isLoading == true){
            $("#loaderCalendar").show();
        }else{
            $("#loaderCalendar").hide();
        }
    },
    eventSources: [
        {
            url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData',[<?php echo $teacher_id; ?>]),
            method: 'POST',
            success: function(docs){
                for(i in docs){
					docs[i].display = 'background';
				}
            }
        },{
            url: fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[<?php echo $teacher_id; ?>]),
            method: 'POST',
            success: function(docs){
                for(i in docs){
					docs[i].display = 'background';
					docs[i].color = 'var(--color-secondary)';
				}
            }
        }
    ],
    select: function(arg){
        $('body #d_calendar .closeon').click();
        $("#loaderCalendar").show();
        if(checkSlotAvailabiltAjaxRun) {
            return false;
        }
        
        if(!validateSelectedSlot(arg)){
            $("#loaderCalendar").hide();
            $("body").css( {"cursor": "default"} );
            $("body").css( {"pointer-events": "initial"} );
            calendar.unselect();
            return false;
        }

        checkSlotAvailabiltAjaxRun = true;

        var newEvent = {start: moment(arg.startStr).format('YYYY-MM-DD HH:mm:ss'), end: moment(arg.endStr).format('YYYY-MM-DD HH:mm:ss')};
        fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability',[<?php echo $teacher_id; ?>]), newEvent, function(doc) {
            checkSlotAvailabiltAjaxRun = false;
            $("#loaderCalendar").hide();
            $("body").css( {"cursor": "default"} );
            $("body").css( {"pointer-events": "initial"} );
            var res = JSON.parse(doc);
            if( res.status == 1 ){
                getSlotBookingConfirmationBox(newEvent, arg.jsEvent);
            }
            if( res.status == 0 ){
                $('.tooltipevent').remove();
            }
            if(res.msg && res.msg  != ""){
                $.mbsmessage(res.msg,true,'alert alert--danger');
            }
        });
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

function validateSelectedSlot(arg){
    var start = arg.startStr;
    var end = arg.endStr;
    var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore;?>' ,'hours').format('YYYY-MM-DD HH:mm:ss');
    var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
    var duration = moment.duration(moment(end).diff(moment(start)));
    var minutesDiff = duration.asMinutes();
    var minutes = "<?php echo $lesson_duration ?>";
    if(minutesDiff > minutes)
    {
        return false;
    }
    if ( selectedDateTime < validSelectDateTime ) {
        /* if( selectedDateTime > moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm:ss') ) {
            $.systemMessage('<?php echo Label::getLabel('LBL_Teacher_Disable_the_Booking_before') .' '. $teacherBookingBefore .' Hours.' ; ?>','alert alert--danger');
            setTimeout(function() {
                $.systemMessage.close();
            }, 3000);
        } */
        return false;
    }

    if( moment('<?php echo $nowDate; ?>').diff(moment(start)) >= 0 || moment(start).format('YYYY-MM-DD HH:mm:ss') > moment(end).format('YYYY-MM-DD HH:mm:ss')) {
        return false;
    }

    return true;
}

function getSlotBookingConfirmationBox(calEvent, jsEvent){
    var monthName = moment(calEvent.start).format('MMMM');
    var date = monthName+" "+moment(calEvent.start).format('DD, YYYY');
    var start = moment(calEvent.start).format('HH:mm A');
    var end = moment(calEvent.end).format('HH:mm A');
    var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss');
    var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss');
    
    var tooltip = '<div class="tooltipevent" style="position:absolute;z-index:10001;"><div class="booking-view"><h3 class="-display-inline"><?php echo $userRow['user_first_name']; ?></h3><span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($userRow['user_country_id'], 'DEFAULT') ); ?>" alt=""></span><div class="inline-list"><span class="inline-list__value highlight"><strong>Date</strong> &nbsp; &nbsp; '+date+' at '+start+'-'+end+'</span></div></div><div class="-align-center"><a href="javascript:void(0);" onClick="setUpLessonSchedule(&quot;<?php echo $teacher_id; ?>&quot;, &quot;<?php echo $lDetailId; ?>&quot;, &quot;'+ selectedStartDateTime +'&quot;, &quot;'+ selectedEndDateTime +'&quot;, &quot;'+ moment(calEvent.start).format('YYYY-MM-DD') +'&quot; );" class="btn btn--secondary btn--small btn--wide"><?php echo Label::getLabel('LBL_Confirm_It!'); ?></a></div><a onclick="$(&apos;.tooltipevent&apos;).remove();" href="javascript:;" class="-link-close"></a></div>';
    
    $("body").append(tooltip);
    let tooltipTop = 0, tooltipLeft = 0;
    if(jsEvent.changedTouches){
        tooltipTop = jsEvent.changedTouches[jsEvent.changedTouches.length-1].clientY - 110;
        tooltipLeft = jsEvent.changedTouches[jsEvent.changedTouches.length-1].clientX - 100;
        $('.tooltipevent').css('position', 'fixed');
    } else {
        tooltipTop = jsEvent.pageY - 110;
        tooltipLeft = jsEvent.pageX - 100;
    }
    $('.tooltipevent').css('top', tooltipTop);
    $('.tooltipevent').css('left', tooltipLeft);

    $(this).mouseover(function(e) {
        $(this).css('z-index', 10000);
        $('.tooltipevent').fadeIn('500');
        $('.tooltipevent').fadeTo('10', 1.9);
    });
}

$(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
    $('.tooltipevent').remove();
});
$(document).bind('close.facebox', function() {
    $('.tooltipevent').remove();
});
</script>