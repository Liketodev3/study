var isLessonCancelAjaxRun = false;
var isRescheduleRequest = (isRescheduleRequest) ? true : false;

lessonFeedback = function (lDetailId) {
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'lessonFeedback', [lDetailId]), '', function (t) {
        $.facebox(t, 'facebox-medium');
    });
};

setupLessonFeedback = function (frm) {
    if (!$(frm).validate())
        return false;
    var data = fcom.frmData(frm);

    fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setupLessonFeedback'), data, function (t) {
        $.facebox.close();
        window.location.href = fcom.makeUrl('LearnerScheduledLessons') + '#' + statusCompleted;
        location.reload();
    });
};

requestReschedule = function (id) {
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'requestReschedule', [id]), '', function (t) {
        $.facebox(t, 'facebox-large booking-calendar-pop-js');
    });
};

requestRescheduleSetup = function (frm) {
    if (!$(frm).validate())
        return;
    var data = fcom.frmData(frm);
    fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'requestRescheduleSetup'), data, function (t) {
        $.facebox.close();
        window.location.href = fcom.makeUrl('LearnerScheduledLessons') + '#' + statusScheduled;
        location.reload();
    });
};

viewBookingCalendar = function (id, action = '') {
    var data = {'action': action};
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'viewBookingCalendar', [id]), data, function (t) {
        $.facebox(t, 'facebox-large booking-calendar-pop-js');
    });
};

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
                                window.location.href = fcom.makeUrl('LearnerScheduledLessons') + '#' + statusScheduled;
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
                window.location.href = fcom.makeUrl('LearnerScheduledLessons') + '#' + statusScheduled;
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
    if (!$(frm).validate())
        return;
    var data = fcom.frmData(frm);
    fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data, function (ans) {
        isLessonCancelAjaxRun = false;
        if (ans.status != 1) {
            $(document).trigger('close.mbsmessage');
            $.mbsmessage(ans.msg, true, 'alert alert--danger');
            /* Custom Code[ */
            if (ans.redirectUrl) {
                setTimeout(function () {
                    window.location.href = ans.redirectUrl
                }, 3000);
            }
            /* ] */
            return;
        }
        $.mbsmessage(ans.msg, true, 'alert alert--success');
        $.facebox.close();
        window.location.href = fcom.makeUrl('LearnerScheduledLessons') + '#' + statusUpcoming;
        location.reload();
    }, {fOutMode: 'json'});

    // fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data , function(t) {
    // 		$.facebox.close();
    // 		location.reload();
    // });
};

issueReported = function (id) {
    fcom.ajax(fcom.makeUrl('ReportIssue', 'form', [id]), '', function (res) {
        if (isJson(res)) {
            var response = JSON.parse(res);
            if (response.status == 1) {
                $.mbsmessage(response.msg, true, 'alert--success');
            } else {
                $.mbsmessage(response.msg, true, 'alert--danger');
            }
        } else {
            $.facebox(res, 'facebox-medium');
        }
    });
};

issueReportedSetup = function (frm) {
    if (!$(frm).validate()) {
        return;
    }
    var action = fcom.makeUrl('ReportIssue', 'setup');
    fcom.updateWithAjax(action, fcom.frmData(frm), function (response) {
        $.facebox.close();
        if (response.status == 1) {
            $.mbsmessage(response.msg, true, 'alert alert--success');
            $("#lesson-status" + statusIssueReported).trigger("click");
        } else {
            $.mbsmessage(response.msg, true, 'alert alert--danger');
        }
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
        window.location.href = fcom.makeUrl('LearnerScheduledLessons') + '#' + statusCompleted;
        location.reload();
    });
};
