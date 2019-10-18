	lessonFeedback = function (lessonId){	
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','lessonFeedback',[lessonId]),'',function(t){
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
	
    setUpLessonSchedule = function(teacherId,lessonId,startTime,endTime,date){
        fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','isSlotTaken'),'&startTime='+startTime+'&endTime='+endTime+'&date='+date,function(t){ 
            t = JSON.parse(t);
            slot = t.count;

            if(slot > 0){
                $.confirm({
                    title: 'Confirm!',
                    content: 'You have already booked this slot. Do you want to continue?',
                    buttons: {
                        Proceed: {
                            text: 'Proceed',
                            btnClass: 'btn btn--primary',
                            keys: ['enter', 'shift'],
                            action: function(){
                                fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), 'teacherId='+teacherId +'&lessonId='+lessonId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
                                $.facebox.close();
                                location.reload();
                                });
                            }
                        },                        
                        Quit: {
                            text: 'Quit',
                            btnClass: 'btn btn--secondary',
                            keys: ['enter', 'shift'],
                            action: function(){
                            }
                        }                        
                    }
                });            
            }else{ 
                fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), 'teacherId='+teacherId+'&lessonId='+lessonId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
                    $.facebox.close();
                    location.reload();
                });
            }            

		});
	};
    
	cancelLesson = function(id){	
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','cancelLesson',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};

	cancelLessonSetup = function(frm){	
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data , function(t) {		
				$.facebox.close();		
				location.reload();
		});	
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
	
	issueDetails = function(issueLessonId) {
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','issueDetails',[issueLessonId]),'',function(t){
			$.facebox( t,'facebox-medium issueDetailPopup');
		});
	};
	
	reportIssueToAdmin = function(issueId, lessonId) {
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'reportIssueToAdmin', [issueId, lessonId]),'' , function(t) {	
			$.facebox.close();				
			location.reload();	
		});	
	};