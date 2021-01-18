$(function() {
	var dv = '#listItems';
	searchStudents = function(frm){
		$(dv).html(fcom.getLoader());
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('TeacherStudents','search'),data,function(t){
			$(dv).html(t);
		});
	};
	
	clearSearch = function(){
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
	
	offerPriceForm = function(LearnerId){
		var data = "top_learner_id="+LearnerId;
		fcom.ajax(fcom.makeUrl('TeacherStudents','offerPriceForm'),data,function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	setUpOfferPrice = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherStudents','setUpOfferPrice'),data,function(t){
			$.facebox.close();				
			var frm = document.frmTeacherStudentsSearchPaging;				
			searchStudents(frm);
		}); 
	};
	
	unlockOfferPrice = function(learnerId){
		var agree = confirm(langLbl.confirmUnLockPrice);
		if( !agree ){
			return false;
		}
		fcom.updateWithAjax(fcom.makeUrl('TeacherStudents', 'unlockOfferPrice'), 'learnerId='+learnerId , function(t) {
			$.facebox.close();	
			var frm = document.frmTeacherStudentsSearchPaging;				
			searchStudents(frm);
		});
		return false;
	};
	
	sendMessageToLearner = function(id){
		fcom.ajax(fcom.makeUrl('TeacherStudents','sendMessageToLearner',[id]),'',function(t){
			$.facebox( t,'facebox-medium');
		}); 
	};
	
	messageToLearnerSetup = function(frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('TeacherStudents', 'messageToLearnerSetup'), data , function(t) {		
			$.facebox.close();				
			searchStudents(document.frmSrch);
		});	
	};
	
	searchStudents(document.frmSrch);
});


