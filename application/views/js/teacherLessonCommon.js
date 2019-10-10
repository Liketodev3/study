	requestReschedule = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','requestReschedule',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	requestRescheduleSetup = function(frm){	
	if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'requestRescheduleSetup'), data , function(t) {		
			$.facebox.close();				
			location.reload();
		});	
	};
	
	issueReported = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','issueReported',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	issueReportedSetup = function(frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'issueReportedSetup'), data , function(t) {		
				$.facebox.close();				
				location.reload();
		});	
	};	
	
	cancelLesson = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','cancelLesson',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	cancelLessonSetup = function(frm){	
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'cancelLessonSetup'), data , function(t) {		
			$.facebox.close();				
			location.reload();
		});	
	};	
	
	viewBookingCalendar = function(id){	
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewBookingCalendar',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};
	
listLessonPlans = function(id){	
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','listLessonPlans',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};
	
	changeLessonPlan = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','changeLessonPlan',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	assignLessonPlanToLessons = function( lessonId, planId ){
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'assignLessonPlanToLessons'), 'ltp_slessonid='+lessonId+'&ltp_tlpn_id='+planId , function(t) {		
			$.facebox.close();				
			location.reload();	
		});	
	};
	
	removeAssignedLessonPlan = function(lessonId){
		if(confirm(langLbl.confirmRemove)){
			fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'removeAssignedLessonPlan'), 'ltp_slessonid='+lessonId , function(t) {		
				$.facebox.close();				
				location.reload();
			});	
		}
	};
	
	viewAssignedLessonPlan = function(lessonId){	
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'viewAssignedLessonPlan',[lessonId]), '', function(t) {
			$.facebox( t,'facebox-medium');
		});	
	};

	scheduleLessonSetup = function(lessonId,startTime,endTime,date){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'scheduleLessonSetup'), 'lessonId='+lessonId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
			$.facebox.close();				
			location.reload();
		});
	};	
	
		issueReportedDetails = function( issueId ) {
		fcom.ajax(fcom.makeUrl('TeacherIssueReported','issueDetails',[issueId]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	resolveIssue = function( issueId, slesson_id ) {
		fcom.ajax(fcom.makeUrl('TeacherIssueReported','resolveIssue',[issueId, slesson_id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	issueResolveSetup = function( frm ) {
		if (!$(frm).validate()) return;	
		var issue_id = $('input[name="issue_id"]').val();
		var slesson_id = $('input[name="slesson_id"]').val();
		var data = fcom.frmData(frm);
		
		fcom.updateWithAjax(fcom.makeUrl('TeacherIssueReported', 'issueResolveSetup'), data , function(t) {	
			//$.facebox.close();	
			issueResolveStepTwo( issue_id, slesson_id );
		});	
		
	};
	
	issueResolveStepTwo = function( issueId, slesson_id ) {
		fcom.ajax(fcom.makeUrl('TeacherIssueReported','issueResolveStepTwo',[issueId, slesson_id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 	
	};
	
	issueResolveSetupStepTwo = function( frm ) {
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherIssueReported', 'issueResolveSetupStepTwo'), data , function(t) {	
			$.facebox.close();	
			window.location.reload();
		});	
	};
	