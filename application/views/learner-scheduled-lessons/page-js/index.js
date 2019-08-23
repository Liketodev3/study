$(function() {
	var dv = '#listItemsLessons';
	searchLessons = function(frm){
		$(dv).html(fcom.getLoader());
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','search'),data,function(t){
			$(dv).html(t);
		});
	};
	
	getListingLessonPlans = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','getListingLessonPlans',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	changeLessonPlan = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','changeLessonPlan',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	remove = function(elem,id){	
		if(confirm(langLbl.confirmRemove))
		{
			$(elem).closest('tr').remove();
			$(dv).html(fcom.getLoader());
			fcom.ajax(fcom.makeUrl('TeacherLessonsPlan','remove',[id]),'',function(t){
				searchLessons(document.frmSrch);
			}); 
		}
	};
	
	/*setUpLessonSchedule = function(lessonId,startTime,endTime,date){
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'setUpLessonSchedule'), 'lessonId='+lessonId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
			$.facebox.close();				
			searchLessons(document.frmSrch);
		});
	}*/
	
	assignLessonPlanToLessons = function(lessonId,planId){
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'assignLessonPlanToLessons'), 'ltp_slessonid='+lessonId+'&ltp_tlpn_id='+planId , function(t) {		
				$.facebox.close();				
				searchLessons(document.frmSrch);	
		});	
	};
	
	removeAssignedLessonPlan = function(lessonId){
		if(confirm(langLbl.confirmRemove))
		{
			fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'removeAssignedLessonPlan'), 'ltp_slessonid='+lessonId , function(t) {		
					$.facebox.close();				
					searchLessons(document.frmSrch);
			});	
		}
	};
	
	viewAssignedLessonPlan = function(lessonId){	
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'viewAssignedLessonPlan',[lessonId]), '', function(t) {		
				searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});	
	};
	
	/*viewBookingCalendar = function(id){	
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','viewBookingCalendar',[id]),'',function(t){
			//searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};*/
	
	viewCalendar = function(frm){	
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','viewCalendar'),data,function(t){
			$(dv).html(t);
		});
	};
	
	/*cancelLesson = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','cancelLesson',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	cancelLessonSetup = function(frm){	
	if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'cancelLessonSetup'), data , function(t) {		
				$.facebox.close();				
				searchLessons(document.frmSrch);
		});	
	};*/
		
	/*issueReported = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','issueReported',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	issueReportedSetup = function(frm){	
	if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'issueReportedSetup'), data , function(t) {		
				$.facebox.close();				
				searchLessons(document.frmSrch);	
		});	
	};*/
	
	/*requestReschedule = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','requestReschedule',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	requestRescheduleSetup = function(frm){	
	if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerScheduledLessons', 'requestRescheduleSetup'), data , function(t) {		
				$.facebox.close();				
				searchLessons(document.frmSrch);
		});	
	};*/
	
	clearSearch = function(){
		document.frmSrch.reset();
		searchLessons( document.frmSrch );
	};
	
	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}		
		var frm = document.frmSLnsSearchPaging;		
		$(frm.page).val(page);
		searchLessons(frm);
	};
	
	searchLessons(document.frmSrch);
});


$(document).on('click','.tab-swticher a',function(){
	$('.tab-swticher a').removeClass('is-active');
	$(this).addClass('is-active');
});