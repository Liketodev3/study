$(function() {
	var dv = '#listItems';
	searchStudents = function(frm) {
		$(dv).html(fcom.getLoader());
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherIssueReported','search'),data,function(t){
			$(dv).html(t);
		});
	};
	
	clearSearch = function() {
		document.frmSrch.reset();
		searchStudents( document.frmSrch );
	}
	
	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}		
		var frm = document.frmTeacherStudentsSearchPaging;		
		$(frm.page).val(page);
		searchStudents(frm);
	};
	
	issueReportedDetails = function( issueId ) {
		fcom.ajax(fcom.makeUrl('TeacherIssueReported','issueDetails',[issueId]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	}
	
	resolveIssue = function( issueId, slesson_id ) {
		fcom.ajax(fcom.makeUrl('TeacherIssueReported','resolveIssue',[issueId, slesson_id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	}
	
	issueResolveSetup = function( frm ) {
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherIssueReported', 'issueResolveSetup'), data , function(t) {	
			$.facebox.close();	
			issueResolveStepTwo( frm );
			//location.reload();	
		});	
	}
	
	issueResolveStepTwo = function( frm ) {
		alert('working okay');
	}
	
	
	searchStudents(document.frmSrch);
});


