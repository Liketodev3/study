var timeInterval;
var FatEventCalendar = function (teacherId) {
    this.teacherId = teacherId;

    var seconds = 2;

    this.calDefaultConf = {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'time',
            center: 'title',
            right: 'prev,next today'
        },
        slotDuration: '00:15',
        slotLabelFormat: {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        },
        views: {
            timeGridWeek: {// name of view
                titleFormat: {month: 'short', day: '2-digit', year: 'numeric'}
            }
        },
        nowIndicator: true,
        navLinks: true, // can click day/week names to navigate views
        // dayMaxEvents: true, // allow "more" link when too many events
        eventOverlap: false,
        slotEventOverlap: false,
        selectable: false,
        editable: false,
        selectLongPressDelay: 50,
        eventLongPressDelay: 50,
        longPressDelay: 50,
        allDaySlot: false,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: true
        },
        loading: function (isLoading) {
            if (isLoading == true) {
                jQuery("#loaderCalendar").show();
            } else {
                jQuery("#loaderCalendar").hide();
            }
        }
    };

    updateTime = function (time) {
        jQuery('body').find(".fc-toolbar-ltr h6 span.timer").html(moment(time).add(seconds, 'seconds').format('hh:mm:ss A'));
    };

    this.setLocale = function (locale) {
        this.calDefaultConf.locale = locale;
    };

    this.startTimer = function (current_time) {
        clearInterval(timeInterval);

        timeInterval = setInterval(function () {
            this.updateTime(current_time);
            seconds++;
        }, 1000);
    };

    getSlotBookingConfirmationBox = function (calEvent, jsEvent) {
        var monthName = moment(calEvent.start).format('MMMM');
        var date = monthName + " " + moment(calEvent.start).format('DD, YYYY');
        var start = moment(calEvent.start).format('HH:mm A');
        var end = moment(calEvent.end).format('HH:mm A');
        var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm');
        var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm');
        var tooltip = jQuery('.tooltipevent-wrapper-js').html();
        tooltip = tooltip.replace('{{displayEventDate}}', date + ' at ' + start + '-' + end);
        tooltip = tooltip.replace('{{selectedStartDateTime}}', selectedStartDateTime);
        tooltip = tooltip.replace('{{selectedEndDateTime}}', selectedEndDateTime);
        tooltip = tooltip.replace('{{selectedDate}}', moment(calEvent.start).format('YYYY-MM-DD'));
        jQuery("body").append(tooltip);
        let tooltipTop = 0, tooltipLeft = 0;
        if (jsEvent.changedTouches) {
            tooltipTop = jsEvent.changedTouches[jsEvent.changedTouches.length - 1].clientY - 110;
            tooltipLeft = jsEvent.changedTouches[jsEvent.changedTouches.length - 1].clientX - 100;
            jQuery('.tooltipevent').css('position', 'fixed');
        } else {
            tooltipTop = jsEvent.pageY - 110;
            tooltipLeft = jsEvent.pageX - 100;
        }
        jQuery('.tooltipevent').css('top', tooltipTop);
        jQuery('.tooltipevent').css('left', tooltipLeft);

        jQuery(this).mouseover(function (e) {
            jQuery(this).css('z-index', 10000);
            jQuery('.tooltipevent').fadeIn('500');
            jQuery('.tooltipevent').fadeTo('10', 1.9);
        });
    };
};

FatEventCalendar.prototype.validateSelectedSlot = function (arg, current_time, duration, bookingBefore) {
    var start = arg.startStr;
    var end = arg.endStr;
    var validSelectDateTime = moment(current_time).add(bookingBefore, 'hours').format('YYYY-MM-DD HH:mm:ss');
    var selectedDateTime = moment(start).format('YYYY-MM-DD HH:mm:ss');
    var duration = moment.duration(moment(end).diff(moment(start)));
    var minutesDiff = duration.asMinutes();
    var minutes = duration;
    if (minutesDiff > minutes) {
        return false;
    }
    if (selectedDateTime < validSelectDateTime) {
        return false;
    }

    if (moment(current_time).diff(moment(start)) >= 0 || moment(start).format('YYYY-MM-DD HH:mm:ss') > moment(end).format('YYYY-MM-DD HH:mm:ss')) {
        return false;
    }

    return true;
};

FatEventCalendar.prototype.AvailaibilityCalendar = function (current_time, duration, bookingBefore, selectable) {
    var fecal = this;
    var checkSlotAvailabiltAjaxRun = false;
    var calConf = {
        now: current_time,
        selectable: selectable,
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData', [this.teacherId]),
                method: 'POST',
                extraParams: {
                    bookingBefore: bookingBefore
                },
                success: function (docs) {
                    for (i in docs) {
                        docs[i].selectable = false;
                        if ((parseInt(docs[i].classType))) {
                            docs[i].display = 'background';
                            docs[i].selectable = true;
                        }
                        docs[i].editable = false;
                    }
                }
            }, {
                url: fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData', [this.teacherId]),
                method: 'POST'
            }
        ],
        select: function (arg) {
            jQuery('body #d_calendar .closeon').click();
            jQuery("#loaderCalendar").show();
            if (checkSlotAvailabiltAjaxRun) {
                return false;
            }
            var time_diff = arg.end - arg.start;
            var durationMS = FullCalendar.createDuration(duration).milliseconds;
            if (time_diff < durationMS) {
                arg.end = new Date(arg.start);
                var ms = arg.end.getTime() + durationMS;
                arg.end.setTime(ms);
                arg.endStr = moment(arg.end).format('YYYY-MM-DDTHH:mm:ssZ');
            }

            if (!fecal.validateSelectedSlot(arg, current_time, duration, bookingBefore)) {
                jQuery("#loaderCalendar").hide();
                jQuery("body").css({"cursor": "default"});
                jQuery("body").css({"pointer-events": "initial"});
                calendar.unselect();
                return false;
            }

            checkSlotAvailabiltAjaxRun = true;
            var newEvent = {start: moment(arg.startStr).format('YYYY-MM-DD HH:mm:ss'), end: moment(arg.endStr).format('YYYY-MM-DD HH:mm:ss')};
            fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability', [fecal.teacherId]), newEvent, function (doc) {
                checkSlotAvailabiltAjaxRun = false;
                jQuery("#loaderCalendar").hide();
                jQuery("body").css({"cursor": "default"});
                jQuery("body").css({"pointer-events": "initial"});
                var res = JSON.parse(doc);
                if (res.status == 1) {
                    this.getSlotBookingConfirmationBox(newEvent, arg.jsEvent);
                }
                if (res.status == 0) {
                    jQuery('body > .tooltipevent').remove();
                    calendar.unselect();
                }
                if (res.msg && res.msg != "") {
                    jQuery.mbsmessage(res.msg, true, 'alert alert--danger');
                }
            });
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};
    if (selectable) {
        var calendarEl = document.getElementById('d_calendarfree_trial');
    } else {
        var calendarEl = document.getElementById('d_calendar');
    }

    var calendar = new FullCalendar.Calendar(calendarEl, conf);

    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>" + langLbl.myTimeZoneLabel + " :-</span> <span class='timer'>" + moment(current_time).format('hh:mm:ss A') + "</span><span class='timezoneoffset'>(" + langLbl.timezoneString + " " + timeZoneOffset + ")</span></h6>");
    seconds = 2;

    this.startTimer(current_time);

    jQuery(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function () {
        jQuery('body > .tooltipevent').remove();
    });
    jQuery(document).bind('close.facebox', function () {
        jQuery('body > .tooltipevent').remove();
    });
};