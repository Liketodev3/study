var isLessonCancelAjaxRun = false;
	lessonFeedback = function (lDetailId){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','lessonFeedback',[lDetailId]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	setupLessonFeedback = function (frm){
		if (!$(frm).validate()) return false;
		var data = fcom.frmData(frm);

	 fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setupLessonFeedback'), data , function(t) {
			$.facebox.close();
			location.reload();
		});
	};

	requestReschedule = function(id){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','requestReschedule',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	requestRescheduleSetup = function(frm){
	if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'requestRescheduleSetup'), data , function(t) {
            $.facebox.close();
            location.reload();
		});
	};

	viewBookingCalendar = function(id, action=''){
		var data = {'action' : action };
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','viewBookingCalendar',[id]),data ,function(t){
			$.facebox( t,'facebox-medium');
		});
	};

    var slot = 0;

    setUpLessonSchedule = function(teacherId,lDetailId,startTime,endTime,date){

        fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','isSlotTaken'),'teacherId='+teacherId +'&startTime='+startTime+'&endTime='+endTime+'&date='+date,function(t){
            t = JSON.parse(t);
            slot = t.count;

            if(slot > 0){
                $.confirm({
                    title: langLbl.Confirm,
                    content: langLbl.bookedSlotAlert,
                    buttons: {
                        Proceed: {
                            text: langLbl.Proceed,
                            btnClass: 'btn btn--primary',
                            keys: ['enter', 'shift'],
                            action: function(){
                                fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), 'teacherId='+teacherId +'&lDetailId='+lDetailId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
                                $.facebox.close();
                                location.reload();
                                });
                            }
                        },
                        Quit: {
                            text: langLbl.Quit,
                            btnClass: 'btn btn--secondary',
                            keys: ['enter', 'shift'],
                            action: function(){
                            }
                        }
                    }
                });
            }else{
                fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), 'teacherId='+teacherId+'&lDetailId='+lDetailId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
                    $.facebox.close();
                    location.reload();
                });
            }

		});
	};

	cancelLesson = function(id){
		isLessonCancelAjaxRun = false;
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','cancelLesson',[id]),'',function(t){
			$.facebox( t,'facebox-medium cancelLesson');
		});
	};

	closeCancelLessonPopup  = function(obj){
		$.facebox.close();
		isLessonCancelAjaxRun = false;
	}

	cancelLessonSetup = function(frm){
		if(isLessonCancelAjaxRun) {
			return false;
		}
		isLessonCancelAjaxRun = true;
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','cancelLessonSetup'),data,function(ans){
			isLessonCancelAjaxRun = false;
			if (ans.status != 1) {
				$(document).trigger('close.mbsmessage');
				$.mbsmessage(ans.msg,true, 'alert alert--danger');
				/* Custom Code[ */
				if( ans.redirectUrl ){
					setTimeout(function(){ window.location.href = ans.redirectUrl }, 3000);
				}
				/* ] */
				return ;
			}
			$.mbsmessage(ans.msg,true, 'alert alert--success');
			$.facebox.close();
			location.reload();
		},{fOutMode:'json'});

		// fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data , function(t) {
		// 		$.facebox.close();
		// 		location.reload();
		// });
	};

	issueReported = function(id){
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','issueReported',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	issueReportedSetup = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'issueReportedSetup'), data , function(t) {
				$.facebox.close();
				location.reload();
		});
	};

	issueDetails = function(issuelDetailId) {
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','issueDetails',[issuelDetailId]),'',function(t){
			$.facebox( t,'facebox-medium issueDetailPopup');
		});
	};

	reportIssueToAdmin = function(issueId, lDetailId, escalated_by) {
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'reportIssueToAdmin', [issueId, lDetailId, escalated_by]),'', function(t) {
			$.facebox.close();
			location.reload();
		});
	};
