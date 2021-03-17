<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<?php
$myTimeZoneLabel =  Label::getLabel('Lbl_My_Current_Time');
$nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
$getAllMonthName =  CommonHelper::getAllMonthName();
$weekDayName =  CommonHelper::dayNames();
?>
<script >
var myTimeZoneLabel = '<?php echo $myTimeZoneLabel; ?>';
var checkSlotAvailabiltAjaxRun =  false;
var action = '<?php echo $action; ?>';
function getEventsByTime( start, stop ) {
   var json = JSON.stringify($("#d_calendar").fullCalendar("clientEvents").map(function(e) {
        return 	{
            start: moment(e.start).format('YYYY-MM-DD HH:mm:ss'),
            end: moment(e.end).format('YYYY-MM-DD HH:mm:ss'),
            day: moment(e.start).format('d'),
            _id: e._id,
            action: e.action,
            classtype: e.classType,
        };
    }));
   return json;
}
var timeInterval;
var seconds = 2;
clearInterval(timeInterval);
timeInterval = setInterval(currentTimer, 1000);
function currentTimer() {
    $('body').find(".fc-left h6 span.timer").html(moment('<?php echo $nowDate; ?>').add(seconds,'seconds').format('hh:mm A'));
    seconds++;
}
$(document).ready(function() {
    $(document).bind('close.facebox', function() {
        $('.tooltipevent').remove();
    });
    var calendarObj  = $('#d_calendar').fullCalendar({
        slotLabelFormat: 'HH:mm',
        header: {
            left: 'time',
            center: '',
            right: 'title prev,next today'
        },
        buttonText :{
            today:    '<?php echo Label::getLabel('LBL_Today'); ?>',
        },
        defaultView: 'agendaWeek',
        selectable: (action === 'free_trial') ? true : false,
        <?php if (strtolower($layoutDirection ) == 'rtl') { ?>
        rtl : true,
        isRTL : true,
        <?php } ?>
        unselectAuto: true,
        monthNames: <?php echo  json_encode($getAllMonthName['monthNames']); ?>,
        monthNamesShort: <?php echo  json_encode($getAllMonthName['monthNamesShort']); ?>,
        dayNames: <?php echo  json_encode($weekDayName['dayNames']); ?>,
        dayNamesShort: <?php echo  json_encode($weekDayName['dayNamesShort']); ?>,
        editable: false,
        nowIndicator:true,
        selectLongPressDelay:50,
        eventLongPressDelay:50,
        longPressDelay:50,
        now:'<?php echo date('Y-m-d H:i:s', strtotime($nowDate)); ?>',
        eventOverlap: false,
        slotEventOverlap : false,
        forceEventDuration : true,
        defaultTimedEventDuration : "<?php echo $bookingSnapDuration; ?>",
        snapDuration : "<?php echo $bookingSnapDuration; ?>",
        slotDuration : '<?php echo gmdate("H:i:s", 15*60); ?>',
        allDaySlot: false,
        timezone: "<?php echo $user_timezone; ?>",
        loading :function( isLoading, view ) {
            if(isLoading == true){
                $("#loaderCalendar").show();
            }else{
                $("#loaderCalendar").hide();
            }
        },
        <?php if( 'free_trial' == $action ){ ?>
        select: function (start, end, jsEvent, view ) {
            //console.log(jsEvent,'jsEvent');
            if(checkSlotAvailabiltAjaxRun) {
                return false;
            }
            checkSlotAvailabiltAjaxRun = true;
            $('body #d_calendar .closeon').click();
            $("#loaderCalendar").show();
            // $("body").css( {"pointer-events": "none"} );
            // $("body").css( {"cursor": "wait"} );
            //==================================//
            var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
            var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore;?>' ,'hours').format('YYYY-MM-DD HH:mm:ss');

            if ( selectedDateTime < validSelectDateTime ) {
                if( selectedDateTime > moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm:ss') ) {
                    // $.systemMessage('<?php //echo Label::getLabel('LBL_Teacher_Disable_the_Booking_before') .' '. $teacherBookingBefore .' Hours.' ; ?>','alert alert--danger');
                    // setTimeout(function() {
                    //     $.systemMessage.close();
                    // }, 3000);
                }
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                $('#d_calendar').fullCalendar('unselect');
                checkSlotAvailabiltAjaxRun =  false;
                return false;
            }
            //================================//

            // if( getEventsByTime( start, end ).length > 1 ){
            // 	// $('#d_calendar').fullCalendar('refetchEvents');
            // }

            if( moment('<?php echo $nowDate; ?>').diff(moment(start)) >= 0 ) {
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                $('#d_calendar').fullCalendar('unselect');
                checkSlotAvailabiltAjaxRun =  false;
                return false;
            }

            if( moment(start).format('YYYY-MM-DD HH:mm:ss') > moment(end).format('YYYY-MM-DD HH:mm:ss') ) {
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                $('#d_calendar').fullCalendar('unselect');
                checkSlotAvailabiltAjaxRun =  false;
                return false;
            }

            var duration = moment.duration( moment(end).diff(moment(start)) );
            var minutesDiff = duration.asMinutes();
            var minutes = "<?php echo $bookingMinutesDuration ?>";
            if ( minutesDiff > minutes ) {
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                $('#d_calendar').fullCalendar('unselect');
                $('.tooltipevent').remove();
                checkSlotAvailabiltAjaxRun =  false;
                return false;
            }

            var newEvent = new Object();
            newEvent.title = '';
            newEvent.startTime = moment(start).format('HH:mm:ss');
            newEvent.endTime = moment(end).format('HH:mm:ss');
            newEvent.start = moment(start).format('YYYY-MM-DD HH:mm:ss');
            newEvent.end = moment(end).format('YYYY-MM-DD HH:mm:ss');
            newEvent.date = moment(start).format('YYYY-MM-DD');
            newEvent.day = moment(start).format('d');
            newEvent.className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
            newEvent.classType = '<?php echo TeacherWeeklySchedule::AVAILABLE; ?>';
            newEvent.allday = 'false';
            newEvent.maxTime = "<?php echo $bookingSnapDuration; ?>";
            newEvent.eventOverlap = false;
            var currentDate = $('#calendar').fullCalendar('getDate');
            var beginOfWeek = moment(start).startOf('week').format('YYYY-MM-DD HH:mm:ss');
            var endOfWeek = moment(start).endOf('week').format('YYYY-MM-DD HH:mm:ss');

            newEvent.weekStart = moment(beginOfWeek).format('YYYY-MM-DD HH:mm:ss');
            newEvent.weekEnd = moment(endOfWeek).format('YYYY-MM-DD HH:mm:ss');

            checkSlotAvailabiltAjax = fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability',[<?php echo $teacher_id; ?>]), newEvent, function(doc) {
                checkSlotAvailabiltAjaxRun = false;
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                var res = JSON.parse(doc);
                if( res.status == 1 ){
                    // $('#d_calendar').fullCalendar('renderEvent',newEvent);
                    getSlotBookingConfirmationBox(newEvent, jsEvent);
                }
                if( res.status == 0 ){
                    $('.tooltipevent').remove();
                }
                if(res.msg && res.msg  != ""){
                    $.mbsmessage(res.msg,true,'alert alert--danger');
                }
            });
        },
        <?php } ?>
        eventLimit: true,
        defaultDate: '<?php echo date('Y-m-d', strtotime($nowDate)); ?>',
        events: function( start, end, timezone, callback ) {
            var data = { start:moment(start).format('YYYY-MM-DD HH:mm:ss'), end:moment(end).format('YYYY-MM-DD HH:mm:ss') };

            fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData',[<?php echo $teacher_id; ?>]), data, function(doc) {

                    var doc = JSON.parse(doc);
                    var events = [];
                    events.push({
                        title: '',
                        start: moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD 00:00:00'),
                        end: moment('<?php echo $nowDate; ?>'),
                        className: 'past_current_day',
                        editable: false,
                        rendering:'background'
                    });

                    var validSelectDateTime = moment('<?php echo $nowDate; ?>').add('<?php echo $teacherBookingBefore; ?>' ,'hours');
                    $(doc).each(function(i,e) {


                        if(  validSelectDateTime > moment(e.end) ) {
                            return;
                        }

                        if( validSelectDateTime.format('YYYY-MM-DD HH:mm:ss') > moment(e.start).format('YYYY-MM-DD HH:mm:ss') ) {
                            e.start =  validSelectDateTime.format('YYYY-MM-DD HH:mm:ss');
                        }
                        var classType = $(this).attr('classType');
                        if( classType == "<?php echo TeacherWeeklySchedule::AVAILABLE; ?>" ){
                            var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::AVAILABLE]; ?>';
                            if( moment('<?php echo $nowDate; ?>') > moment(e.start) ) {
                                var editable = false;
                            } else {
                                var editable = true;
                            }
                        }else if( classType == "<?php echo TeacherWeeklySchedule::UNAVAILABLE; ?>" ){
                            var className = '<?php echo $cssClassArr[TeacherWeeklySchedule::UNAVAILABLE]; ?>';
                            var editable = false;
                        }

                        events.push({
                            title: $(this).attr('title'),
                            start: $(this).attr('start'),
                            end: $(this).attr('end'),
                            color: $(this).attr('color'),
                            day: $(this).attr('day'),
                            _id: $(this).attr('_id'),
                            action: $(this).attr('action'),
                            classType: $(this).attr('classType'),
                            className: className,
                            rendering:'background',
                            editable: editable,
                            selectable: editable,
                        });
                    });
                    let postData = { weekStart:moment(start).format('YYYY-MM-DD'), weekEnd:moment(end).format('YYYY-MM-DD') };
                    fcom.ajax(fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[<?php echo $teacher_id; ?>]), '', function(doc2) {
                        var doc2 = JSON.parse(doc2);

                        $(doc2).each(function(i,e) {
                            events.push({
                            title: $(this).attr('title'),
                            start: $(this).attr('start'),
                            className: $(this).attr('className'),
                            end: $(this).attr('end'),
                            color: "var(--color-secondary)",
                            editable: false,
                            selectable: false,
                            });
                        });

                        callback(events);
                    });
            
            });
        },
        eventRender: function(event, element) {
            if( isNaN(event._id) && event.className != "sch_data" ){
                element.find(".fc-content").prepend( "<span class='closeon' >X</span>" );
            } else {
                var eventData = JSON.stringify({"start" :moment( event.start).format('YYYY-MM-DD HH:mm:ss'),"end" : moment(event.end).format('YYYY-MM-DD HH:mm:ss'),"_id":event._id,"classType":event.classType,"day":event.day});

                if( event.classType != 0  && event.className != "sch_data"){
                    element.find(".fc-content").prepend( "<span class='closeon' onclick='deleteTeacherWeeklySchedule(" + eventData + ");'>X</span>" );
                }
            }

            element.find(".closeon").click(function() {
                if(isNaN(event._id)){
                    $('#d_calendar').fullCalendar('removeEvents',event._id);
                    $('.tooltipevent').remove();
                }
            });

            var eventEnd = moment(event.end);
            var NOW = moment('<?php echo $nowDate; ?>');
            if(moment(event.end).format('YYYY-MM-DD HH:mm') < moment('<?php echo $nowDate; ?>').format('YYYY-MM-DD HH:mm') && event.className != "sch_data"){
                return false;
            }
        },
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            if( moment('<?php echo $nowDate; ?>').diff(moment(event.start)) >= 0) {
                $("#d_calendar").fullCalendar("refetchEvents");
                return false;
            }
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
        },
        eventResize: function(event, delta, revertFunc) {
            return false;
        },
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            if(moment('<?php echo $nowDate; ?>').diff(moment(event.start)) >= 0) {
                $("#d_calendar").fullCalendar("refetchEvents");
                return false;
            }
        },
        eventClick: function(calEvent, jsEvent) {
            getSlotBookingConfirmationBox(calEvent, jsEvent);
        }
    });

	$(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
        $('.tooltipevent').remove();
    });
    $('body').find(".fc-left").html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'></span></h6>");
});
    
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
    if( calEvent.className != "sch_data" ){
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
}

</script>
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

<div id='d_calendar'></div>
</div>
