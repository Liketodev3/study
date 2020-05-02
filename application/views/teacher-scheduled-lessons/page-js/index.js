$(function() {
	var dv = '#listItemsLessons';
	searchLessons = function(frm){
		$(dv).html(fcom.getLoader());
		$('.calender-js').removeClass('is-active');
		$('.list-js').addClass('is-active');
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','search'),data,function(t){
			$(dv).html(t);
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

	/*listLessonPlans = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','listLessonPlans',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		});
	};

	changeLessonPlan = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','changeLessonPlan',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};

	scheduleLessonSetup = function(lessonId,startTime,endTime,date){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'scheduleLessonSetup'), 'lessonId='+lessonId+'&startTime='+startTime+'&endTime='+endTime+'&date='+date, function(doc) {
						console.log(doc);
						$.facebox.close();
						searchLessons(document.frmSrch);
		});
	}

	assignLessonPlanToLessons = function(lessonId,planId){
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'assignLessonPlanToLessons'), 'ltp_slessonid='+lessonId+'&ltp_tlpn_id='+planId , function(t) {
			$.facebox.close();
			//searchLessons(document.frmSrch);
		});
	};

	removeAssignedLessonPlan = function(lessonId){
		if(confirm(langLbl.confirmRemove))
		{
			fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'removeAssignedLessonPlan'), 'ltp_slessonid='+lessonId , function(t) {
					$.facebox.close();
					searchLessons(document.frmSrch);
			});
		}
	};

	viewAssignedLessonPlan = function(lessonId){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons', 'viewAssignedLessonPlan',[lessonId]), '', function(t) {
				searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};*/

	/*viewBookingCalendar = function(id){
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewBookingCalendar',[id]),'',function(t){
			//searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};*/

	viewCalendar = function(frm){
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','viewCalendar'),data,function(t){
			$(dv).html(t);
		});
	};

	/*cancelLesson = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','cancelLesson',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};

	cancelLessonSetup = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'cancelLessonSetup'), data , function(t) {
				$.facebox.close();
				searchLessons(document.frmSrch);
		});
	};*/

	/*issueReported = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','issueReported',[id]),'',function(t){
			searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};

	issueReportedSetup = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'issueReportedSetup'), data , function(t) {
				$.facebox.close();
				searchLessons(document.frmSrch);
		});
	};*/

	/*requestReschedule = function(id){
		//$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','requestReschedule',[id]),'',function(t){
			//searchLessons(document.frmSrch);
			$.facebox( t,'facebox-medium');
		});
	};

	requestRescheduleSetup = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherScheduledLessons', 'requestRescheduleSetup'), data , function(t) {
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
