$(function() {
	var dv = '#listItems';
	searchTeachers = function(frm){
		$(dv).html(fcom.getLoader());
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('LearnerTeachers','search'),data,function(t){
			$(dv).html(t);
		});
	};
	
	/* sendMessageToTeacher = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('LearnerTeachers','sendMessageToTeacher',[id]),'',function(t){
			searchTeachers(document.frmSrch);
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	messageToTeacherSetup = function(frm){	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LearnerTeachers', 'messageToTeacherSetup'), data , function(t) {		
			$.facebox.close();				
			searchTeachers(document.frmSrch);
		});	
	}; */

	clearSearch = function(){
		document.frmSrch.reset();
		searchTeachers( document.frmSrch );
	}
	
	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}		
		var frm = document.frmLearnerTeachersSearchPaging;		
		$(frm.page).val(page);
		searchTeachers(frm);
	};
	
	searchTeachers(document.frmSrch);
});


