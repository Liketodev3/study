var FatEventCalendar = function(teacherId){
    this.teacherId = teacherId;
    var timeInterval;
    var seconds = 2;

    this.calDefaultConf = {
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
        navLinks: true, // can click day/week names to navigate views
        // dayMaxEvents: true, // allow "more" link when too many events
        eventOverlap: false,
        slotEventOverlap : false,
        selectable: false,
        editable: false,
        selectLongPressDelay: 50,
        eventLongPressDelay: 50,
        longPressDelay: 50,
        allDaySlot: false,
        eventTimeFormat : {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: true
        },
        loading: function( isLoading ) {
            if(isLoading == true){
                jQuery("#loaderCalendar").show();
            }else{
                jQuery("#loaderCalendar").hide();
            }
        }
    };

    updateTime = function(time) {
        jQuery('body').find(".fc-toolbar-ltr h6 span.timer").html(moment(time).add(seconds,'seconds').format('hh:mm A'));
    };

    this.setLocale = function(locale){
        this.calDefaultConf.locale = locale;
    };

    this.startTimer = function(current_time){
        clearInterval(timeInterval);

        timeInterval = setInterval(function(){
            this.updateTime(current_time);
            seconds++;
        }, 1000);
    };
    
    getSlotBookingConfirmationBox = function(calEvent, jsEvent){
        var monthName = moment(calEvent.start).format('MMMM');
        var date = monthName+" "+moment(calEvent.start).format('DD, YYYY');
        var start = moment(calEvent.start).format('HH:mm A');
        var end = moment(calEvent.end).format('HH:mm A');
        var selectedStartDateTime = moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss');
        var selectedEndDateTime = moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss');    
        var tooltip = jQuery('.tooltipevent-wrapper-js').html();
        tooltip = tooltip.replace('{{displayEventDate}}', date+' at '+start+'-'+end);
        tooltip = tooltip.replace('{{selectedStartDateTime}}', selectedStartDateTime);
        tooltip = tooltip.replace('{{selectedEndDateTime}}', selectedEndDateTime);
        tooltip = tooltip.replace('{{selectedDate}}', moment(calEvent.start).format('YYYY-MM-DD'));
        jQuery("body").append(tooltip);
        let tooltipTop = 0, tooltipLeft = 0;
        if(jsEvent.changedTouches){
            tooltipTop = jsEvent.changedTouches[jsEvent.changedTouches.length-1].clientY - 110;
            tooltipLeft = jsEvent.changedTouches[jsEvent.changedTouches.length-1].clientX - 100;
            jQuery('.tooltipevent').css('position', 'fixed');
        } else {
            tooltipTop = jsEvent.pageY - 110;
            tooltipLeft = jsEvent.pageX - 100;
        }
        jQuery('.tooltipevent').css('top', tooltipTop);
        jQuery('.tooltipevent').css('left', tooltipLeft);
    
        jQuery(this).mouseover(function(e) {
            jQuery(this).css('z-index', 10000);
            jQuery('.tooltipevent').fadeIn('500');
            jQuery('.tooltipevent').fadeTo('10', 1.9);
        });
    };
};

FatEventCalendar.prototype.validateSelectedSlot = function (arg, current_time, duration, bookingBefore){
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
        return false;
    }

    if( moment(current_time).diff(moment(start)) >= 0 || moment(start).format('YYYY-MM-DD HH:mm:ss') > moment(end).format('YYYY-MM-DD HH:mm:ss')) {
        return false;
    }

    return true;
};

FatEventCalendar.prototype.WeeklyBookingCalendar = function(current_time, duration, bookingBefore){
    var fecal = this;
    var calConf = {
        now:current_time,
        selectable: true,
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData', [this.teacherId], confFrontEndUrl),
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
                url: fcom.makeUrl('Teachers', 'getTeacherScheduledLessonData', [this.teacherId], confFrontEndUrl),
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
            jQuery('body #d_calendar .closeon').click();
            jQuery("#loaderCalendar").show();
            if(checkSlotAvailabiltAjaxRun) {
                return false;
            }

            var time_diff = arg.end - arg.start;
            var durationMS = FullCalendar.createDuration(duration).milliseconds;
            if(time_diff<durationMS){
                arg.end = new Date(arg.start);
                var ms = arg.end.getTime() + durationMS;
                arg.end.setTime(ms);
                arg.endStr = moment(arg.end).format('YYYY-MM-DDTHH:mm:ssZ');
            }
            
            if(!fecal.validateSelectedSlot(arg, current_time, duration, bookingBefore)){
                jQuery("#loaderCalendar").hide();
                jQuery("body").css( {"cursor": "default"} );
                jQuery("body").css( {"pointer-events": "initial"} );
                calendar.unselect();
                return false;
            }
    
            checkSlotAvailabiltAjaxRun = true;
            var newEvent = {start: moment(arg.startStr).format('YYYY-MM-DD HH:mm:ss'), end: moment(arg.endStr).format('YYYY-MM-DD HH:mm:ss')};
            fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability', [fecal.teacherId], confFrontEndUrl), newEvent, function(doc) {
                checkSlotAvailabiltAjaxRun = false;
                jQuery("#loaderCalendar").hide();
                jQuery("body").css( {"cursor": "default"} );
                jQuery("body").css( {"pointer-events": "initial"} );
                var res = JSON.parse(doc);
                if( res.status == 1 ){
                    this.getSlotBookingConfirmationBox(newEvent, arg.jsEvent);
                }
                if( res.status == 0 ){
                    jQuery('body > .tooltipevent').remove();
                    calendar.unselect();
                }
                if(res.msg && res.msg  != ""){
                    jQuery.mbsmessage(res.msg,true,'alert alert--danger');
                }
            });
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};

    var calendarEl = document.getElementById('d_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, conf);
    
    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>"+langLbl.myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    
    this.startTimer(current_time);

    jQuery(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
        jQuery('body > .tooltipevent').remove();
    });
    jQuery(document).bind('close.facebox', function() {
        jQuery('body > .tooltipevent').remove();
    });
};


FatEventCalendar.prototype.LearnerMonthlyCalendar = function(current_time){
    var calConf = {
        initialView: '',
        now:current_time,
        headerToolbar: {
            left: 'time'
        },
        eventSources: [
            {
                url: fcom.makeUrl('LearnerScheduledLessons', 'calendarJsonData',[])
            }
        ],
        select: function(arg){
            var start = arg.start;
            var end = arg.end;
            if(moment(start).format('d')!=moment(end).format('d') ) {
                calender.unselect();
                return false;
            }
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};

    var calendarEl = document.getElementById('d_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, conf);
    
    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>"+langLbl.myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    
    this.startTimer(current_time);
};

FatEventCalendar.prototype.TeacherMonthlyCalendar = function(current_time){
    var calConf = {
        initialView: '',
        now:current_time,
        headerToolbar: {
            left: 'time'
        },
        eventSources: [
            {
                url: fcom.makeUrl('TeacherScheduledLessons', 'calendarJsonData',[])
            }
        ],
        select: function(arg){
            var start = arg.start;
            var end = arg.end;
            if(moment(start).format('d')!=moment(end).format('d') ) {
                calender.unselect();
                return false;
            }
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};

    var calendarEl = document.getElementById('d_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, conf);
    
    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>"+langLbl.myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    
    this.startTimer(current_time);
};

FatEventCalendar.prototype.AvailaibilityCalendar = function(current_time, duration, bookingBefore, selectable){
    var fecal = this;
    var checkSlotAvailabiltAjaxRun = false;
    var calConf = {
        now:current_time,
        selectable: selectable,
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData', [this.teacherId], confFrontEndUrl),
                method: 'POST',
                success: function(docs){
                    for(i in docs){
                        docs[i].display = 'background';
                        // docs[i].rendering ='background',
                        docs[i].editable = false,
                        docs[i].selectable = true;
                    }
                }
            }
        ],
        select: function(arg){
            jQuery('body #d_calendar .closeon').click();
            jQuery("#loaderCalendar").show();
            if(checkSlotAvailabiltAjaxRun) {
                return false;
            }

            var time_diff = arg.end - arg.start;
            var durationMS = FullCalendar.createDuration(duration).milliseconds;
            if(time_diff<durationMS){
                arg.end = new Date(arg.start);
                var ms = arg.end.getTime() + durationMS;
                arg.end.setTime(ms);
                arg.endStr = moment(arg.end).format('YYYY-MM-DDTHH:mm:ssZ');
            }
            
            if(!fecal.validateSelectedSlot(arg, current_time, duration, bookingBefore)){
                jQuery("#loaderCalendar").hide();
                jQuery("body").css( {"cursor": "default"} );
                jQuery("body").css( {"pointer-events": "initial"} );
                calendar.unselect();
                return false;
            }
    
            checkSlotAvailabiltAjaxRun = true;
            var newEvent = {start: moment(arg.startStr).format('YYYY-MM-DD HH:mm:ss'), end: moment(arg.endStr).format('YYYY-MM-DD HH:mm:ss')};
            fcom.ajax(fcom.makeUrl('Teachers', 'checkCalendarTimeSlotAvailability', [fecal.teacherId], confFrontEndUrl), newEvent, function(doc) {
                checkSlotAvailabiltAjaxRun = false;
                jQuery("#loaderCalendar").hide();
                jQuery("body").css( {"cursor": "default"} );
                jQuery("body").css( {"pointer-events": "initial"} );
                var res = JSON.parse(doc);
                if( res.status == 1 ){
                    this.getSlotBookingConfirmationBox(newEvent, arg.jsEvent);
                }
                if( res.status == 0 ){
                    jQuery('body > .tooltipevent').remove();
                    calendar.unselect();
                }
                if(res.msg && res.msg  != ""){
                    jQuery.mbsmessage(res.msg,true,'alert alert--danger');
                }
            });
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};

    var calendarEl = document.getElementById('d_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, conf);
    
    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>"+langLbl.myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    
    this.startTimer(current_time);

    jQuery(".fc-today-button,button.fc-prev-button,button.fc-next-button").click(function() {
        jQuery('body > .tooltipevent').remove();
    });
    jQuery(document).bind('close.facebox', function() {
        jQuery('body > .tooltipevent').remove();
    });
};

FatEventCalendar.prototype.TeacherGeneralAvailaibility = function(current_time){
    var calConf = {
        selectable: true,
        editable: true,
        now:current_time,
        headerToolbar: {
            left: 'time'
        },
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherGeneralAvailabilityJsonData', [this.teacherId], confFrontEndUrl),
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
            newEvent.className = 'slot_available',
            newEvent.classType = 1,
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
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};

    var calendarEl = document.getElementById('ga_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, conf);
    
    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>"+langLbl.myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    
    this.startTimer(current_time);
    return calendar;
};


FatEventCalendar.prototype.TeacherWeeklyAvailaibility = function(current_time){
    var calConf = {
        selectable: true,
        editable: true,
        now:current_time,
        eventSources: [
            {
                url: fcom.makeUrl('Teachers', 'getTeacherWeeklyScheduleJsonData', [this.teacherId], confFrontEndUrl),
                method: 'POST',
                success: function(docs){
                    // console.log(doc);
                    for(i in docs){
                        docs[i].extendedProps = {};
                        docs[i].extendedProps._id = docs[i]._id || 0;
                        docs[i].extendedProps.action = docs[i].action;
                        docs[i].extendedProps.classType = docs[i].classType;
                    }
                }
            }
        ],
        eventClick: function(arg) {
            if (confirm(langLbl.confirmRemove)) {
                arg.event.remove()
            }
        },
        select: function (arg ) {
            var start = arg.start;
            var end = arg.end;
            if(moment(current_time).diff(moment(start)) >= 0) {
                calendar.unselect();
                return false;
            }
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
            newEvent.extendedProps = {};
            newEvent.extendedProps._id = 0;
            newEvent.extendedProps.className = 'slot_available',
            newEvent.extendedProps.classType = 1,
            newEvent.extendedProps.action = 'fromGeneralAvailability',
            newEvent.className = 'slot_available',
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
        }
    }
    var defaultConf = this.calDefaultConf;
    var conf = {...defaultConf, ...calConf};

    var calendarEl = document.getElementById('w_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, conf);
    
    calendar.render();

    jQuery('body').find(".fc-time-button").parent().html("<h6><span>"+langLbl.myTimeZoneLabel+" :-</span> <span class='timer'>"+moment(current_time).format('hh:mm A')+"</span></h6>");
    
    this.startTimer(current_time);
    return calendar;
};