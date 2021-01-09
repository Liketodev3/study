<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<?php
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
$getAllMonthName =  CommonHelper::getAllMonthName();
$weekDayName =  CommonHelper::dayNames();
?>
<style>
.fc button.fc-time-button{display:none;}
</style>
<div id="loaderCalendar" style="display: none;"><div class="loader"></div></div>
<div class="calendar-view">
    <?php //if( 'free_trial' != $action ){ ?>
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
    <?php //} ?>

    <span> <?php echo MyDate::displayTimezoneString();?> </span>
    <!-- (<span id="currentTime"> </span>) -->
    <?php if( 'free_trial' != $action ): ?>
    <small class="label label--warning"><?php echo Label::getLabel('Note_This_calendar_is_to_only_check_availability') ?></small>
    <?php endif; ?>

</div>

<div id='calendar-container'>
    <div id='d_calendar'></div>
</div>

<script >
var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
var calendarEl = document.getElementById('d_calendar');
var action = '<?php echo $action; ?>';
var checkSlotAvailabiltAjaxRun =  false;
var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    nowIndicator: true,
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
    buttonText :{
        today: '<?php echo Label::getLabel('LBL_Today'); ?>',
    },
    navLinks: true, // can click day/week names to navigate views
    dayMaxEvents: true, // allow "more" link when too many events
    eventOverlap: false,
    slotEventOverlap : false,
    forceEventDuration : true,
    defaultTimedEventDuration : "<?php echo $bookingSnapDuration; ?>",
    snapDuration : "<?php echo $bookingSnapDuration; ?>",
    allDaySlot: false,
    selectable: (action === 'free_trial'),
    now:'<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>',
    // timeZone: 'UTC',
    // unselectAuto: true,
    selectLongPressDelay: 50,
    eventLongPressDelay: 50,
    longPressDelay: 50,
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
        }
    ],
    select: function(arg){
        console.log('select:', arg);
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
    var duration = moment.duration( moment(end).diff(moment(start)) );
    var minutesDiff = duration.asMinutes();
    var minutes = "<?php echo $bookingMinutesDuration ?>";
    if ( minutesDiff > minutes ) {
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
    
    <?php if( 'free_trial' == $action ){ ?>
        var tooltip = '<div class="tooltipevent" style="position:absolute;z-index:10001;"><div class="booking-view"><h3 class="-display-inline"><?php echo $teacher_name?></h3><span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($teacher_country_id, 'DEFAULT') ); ?>" alt=""></span><div class="inline-list"><span class="inline-list__value highlight"><strong>Date</strong> &nbsp; &nbsp; '+date+' at '+start+'-'+end+'</span></div></div><div class="-align-center"><a href="javascript:void(0)" onClick="cart.add(&quot;<?php echo $teacher_id; ?>&quot;, <?php echo $lPackageId; ?>, &quot;'+ selectedStartDateTime +'&quot;, &quot;'+ selectedEndDateTime +'&quot;, &quot;'+ '<?php echo $languageId;?>' +'&quot; );" class="btn btn--secondary btn--small btn--wide"><?php echo Label::getLabel('LBL_Book_Lesson'); ?></a></div><a onclick="$(&apos;.tooltipevent&apos;).remove();" href="javascript:;" class="-link-close"></a></div>';
    <?php } ?>
    
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