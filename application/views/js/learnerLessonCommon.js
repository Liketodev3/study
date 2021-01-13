var isLessonCancelAjaxRun = false;
var isRescheduleRequest = (isRescheduleRequest) ? true : false;
var timeInterval;
lessonFeedback = function (lDetailId) {
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'lessonFeedback', [lDetailId]), '', function (t) {
        $.facebox(t, 'facebox-medium');
    });
};

setupLessonFeedback = function (frm) {
    if (!$(frm).validate()) return false;
    var data = fcom.frmData(frm);

    fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setupLessonFeedback'), data, function (t) {
        $.facebox.close();
        window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusCompleted;
        location.reload();
    });
};

requestReschedule = function (id) {
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'requestReschedule', [id]), '', function (t) {
        $.facebox(t, 'facebox-medium booking-calendar-pop-js');
    });
};

requestRescheduleSetup = function (frm) {
    if (!$(frm).validate()) return;
    var data = fcom.frmData(frm);
    fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'requestRescheduleSetup'), data, function (t) {
        $.facebox.close();
        window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusScheduled;
        location.reload();
    });
};

viewBookingCalendar = function (id, action = '') {
    var data = { 'action': action };
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'viewBookingCalendar', [id]), data, function (t) {
        $.facebox(t, 'facebox-medium booking-calendar-pop-js');
    });
};

loadWeeklyBookingCalendar = function(teacherId, locale, current_time, duration, bookingBefore){
    var calendarEl = document.getElementById('d_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'time',
            center: 'title',
            right: 'prev,next today '
        },
        views: {
            timeGridWeek: { // name of view
                titleFormat: {  month: 'short', day: '2-digit', year: 'numeric' }
            }
        },
        nowIndicator: true,
        locale: locale,
        navLinks: true, // can click day/week names to navigate views
        // dayMaxEvents: true, // allow "more" link when too many events
        eventOverlap: false,
        forceEventDuration: true,
        slotEventOverlap : false,
        defaultTimedEventDuration : duration,
        snapDuration : duration,
        // duration : duration,
        slotDuration : duration,
        selectable: true,
        editable: false,
        now:current_time,
        // timeZone: 'UTC',
        // unselectAuto: true,
        selectLongPressDelay: 50,
        eventLongPressDelay: 50,
        longPressDelay: 50,
        allDaySlot: false,
        dayMaxEventRows: true, 
        // timeZone: "<?php echo $user_timezone; ?>",
        loading: function( isLoading ) {
            if(isLoading == true){
                $("#loaderCalendar").show();
            }else{
                $("#loaderCalendar").hide();
            }
        },
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData',[teacherId]),
                method: 'POST',
                success: function(docs){
                    for(i in docs){
                        docs[i].display = 'background';
                        // docs[i].rendering ='background',
                        docs[i].editable = false,
                        docs[i].selectable = true;
                    }
                }
            },{
                url: fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData',[teacherId]),
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
            
            if(!validateSelectedSlot(arg, current_time, duration, bookingBefore)){
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                calendar.unselect();
                return false;
            }
    
            checkSlotAvailabiltAjaxRun = true;
    
            var newEvent = {start: moment(arg.startStr).format('YYYY-MM-DD HH:mm:ss'), end: moment(arg.endStr).format('YYYY-MM-DD HH:mm:ss')};
            fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability',[teacherId]), newEvent, function(doc) {
                checkSlotAvailabiltAjaxRun = false;
                $("#loaderCalendar").hide();
                $("body").css( {"cursor": "default"} );
                $("body").css( {"pointer-events": "initial"} );
                var res = JSON.parse(doc);
                if( res.status == 1 ){
                    getSlotBookingConfirmationBox(newEvent, arg.jsEvent);
                }
                if( res.status == 0 ){
                    $('body > .tooltipevent').remove();
                }
                if(res.msg && res.msg  != ""){
                    $.mbsmessage(res.msg,true,'alert alert--danger');
                }
            });
        }
    });
    
    calendar.render();
    $('body').find(".fc-time-button").parent().html("<h6><span>"+myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    clearInterval(timeInterval);
    timeInterval = setInterval(function(){
        currentTimer(current_time);
    }, 1000);

    $(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
        $('body > .tooltipevent').remove();
    });
    $(document).bind('close.facebox', function() {
        $('body > .tooltipevent').remove();
    });
};

var seconds = 2;
currentTimer = function(time) {
    $('body').find(".fc-toolbar-ltr h6 span.timer").html(moment(time).add(seconds,'seconds').format('hh:mm A'));
    seconds++;
};


validateSelectedSlot = function (arg, current_time, duration, bookingBefore){
    var start = arg.startStr;
    var end = arg.endStr;
    var validSelectDateTime = moment(current_time).add(bookingBefore ,'hours').format('YYYY-MM-DD HH:mm:ss');
    var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
    var duration = moment.duration(moment(end).diff(moment(start)));
    var minutesDiff = duration.asMinutes();
    var minutes = duration;
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

getSlotBookingConfirmationBox = function(calEvent, jsEvent){
    var monthName = moment(calEvent.start).format('MMMM');
    var date = monthName+" "+moment(calEvent.start).format('DD, YYYY');
    var start = moment(calEvent.start).format('HH:mm A');
    var end = moment(calEvent.end).format('HH:mm A');
    var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss');
    var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss');    
    var tooltip = $('.tooltipevent-wrapper-js').html();
    tooltip = tooltip.replace('{{displayEventDate}}', date+' at '+start+'-'+end);
    tooltip = tooltip.replace('{{selectedStartDateTime}}', selectedStartDateTime);
    tooltip = tooltip.replace('{{selectedEndDateTime}}', selectedEndDateTime);
    tooltip = tooltip.replace('{{selectedDate}}', moment(calEvent.start).format('YYYY-MM-DD'));
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

var slot = 0;

setUpLessonSchedule = function (teacherId, lDetailId, startTime, endTime, date) {
    rescheduleReason = '';
    if (isRescheduleRequest) {
        var rescheduleReason = $('#reschedule-reason-js').val();
        if ($.trim(rescheduleReason) == "") {
            alert(langLbl.requriedRescheduleMesssage);
            $('.booking-calendar-pop-js').animate({
                scrollTop: $("#loaderCalendar").offset().top
            }, 500);
            return false;
        }
    }
    $.mbsmessage.close();
    $.mbsmessage(langLbl.requestProcessing, false, 'alert alert--process');
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'isSlotTaken'), 'teacherId=' + teacherId + '&startTime=' + startTime + '&endTime=' + endTime + '&date=' + date, function (t) {
        t = JSON.parse(t);
        slot = t.count;

        var ajaxData = 'teacherId=' + teacherId + '&lDetailId=' + lDetailId + '&startTime=' + startTime + '&endTime=' + endTime + '&date=' + date;

        if (isRescheduleRequest) {
            ajaxData += '&rescheduleReason=' + rescheduleReason + '&isRescheduleRequest=' + isRescheduleRequest;

        }

        if (slot > 0) {
            $.mbsmessage.close();
            $.confirm({
                title: langLbl.Confirm,
                content: langLbl.bookedSlotAlert,
                buttons: {
                    Proceed: {
                        text: langLbl.Proceed,
                        btnClass: 'btn btn--primary',
                        keys: ['enter', 'shift'],
                        action: function () {
                            $.mbsmessage(langLbl.requestProcessing, false, 'alert alert--process');
                            fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), ajaxData, function (doc) {
                                $.facebox.close();
                                window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusScheduled;
                                location.reload();
                            });
                        }
                    },
                    Quit: {
                        text: langLbl.Quit,
                        btnClass: 'btn btn--secondary',
                        keys: ['enter', 'shift'],
                        action: function () {
                        }
                    }
                }
            });
        } else {

            fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), ajaxData, function (doc) {
                $.mbsmessage.close();
                $.facebox.close();
                window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusScheduled;
                location.reload();
            });
        }

    });
};

cancelLesson = function (id) {
    isLessonCancelAjaxRun = false;
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLesson', [id]), '', function (t) {
        $.facebox(t, 'facebox-medium cancelLesson');
    });
};

closeCancelLessonPopup = function (obj) {
    $.facebox.close();
    isLessonCancelAjaxRun = false;
}

cancelLessonSetup = function (frm) {
    if (isLessonCancelAjaxRun) {
        return false;
    }
    isLessonCancelAjaxRun = true;
    if (!$(frm).validate()) return;
    var data = fcom.frmData(frm);
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data, function (ans) {
        isLessonCancelAjaxRun = false;
        if (ans.status != 1) {
            $(document).trigger('close.mbsmessage');
            $.mbsmessage(ans.msg, true, 'alert alert--danger');
            /* Custom Code[ */
            if (ans.redirectUrl) {
                setTimeout(function () { window.location.href = ans.redirectUrl }, 3000);
            }
            /* ] */
            return;
        }
        $.mbsmessage(ans.msg, true, 'alert alert--success');
        $.facebox.close();
        window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusUpcoming;
        location.reload();
    }, { fOutMode: 'json' });

    // fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data , function(t) {
    // 		$.facebox.close();
    // 		location.reload();
    // });
};

issueReported = function (id) {
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'issueReported', [id]), '', function (t) {
        $.facebox(t, 'facebox-medium');
    });
};

issueReportedSetup = function (frm) {
    if (!$(frm).validate()) return;
    $(frm).find('[type=submit]').attr('disabled', true);
    var data = fcom.frmData(frm);
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'issueReportedSetup'), data, function (t) {
        $.facebox.close();
        window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusCompleted;
        location.reload();
    });
};

issueDetails = function (issuelDetailId) {
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'issueDetails', [issuelDetailId]), '', function (t) {
        $.facebox(t, 'facebox-medium issueDetailPopup');
    });
};

reportIssueToAdmin = function (issueId, lDetailId, escalated_by) {
    fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'reportIssueToAdmin', [issueId, lDetailId, escalated_by]), '', function (t) {
        $.facebox.close();
        window.location.href= fcom.makeUrl('LearnerScheduledLessons') + '#' + statusCompleted;
        location.reload();
    });
};
