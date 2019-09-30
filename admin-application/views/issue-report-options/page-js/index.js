$(document).ready(function(){
	searchIssueReportOptions(document.frmIssueReoprtOptions);
});

(function() {
	var active = 1;
	var inActive = 0;
	var runningAjaxReq = false;
	var dv = '#listing';
	
	goToSearchPage = function(page) {	
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmIssueReportOptionsPaging;		
		$(frm.page).val(page);
		searchIssueReportOptions(frm);
	}

	reloadList = function() {
		searchIssueReportOptions();
	};	
	
	searchIssueReportOptions = function(form){		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('IssueReportOptions','search'),data,function(res){
			$(dv).html(res);			
		});
	};
	clearSearch = function(){
		document.frmIssueReoprtOptions.reset();
		searchIssueReportOptions(document.frmIssueReoprtOptions);
	};
	
	
	addIssueReportOptionForm = function(id) {
		$.facebox(function() {
			IssueReoprtOptionForm(id);
		});
	};
	
	IssueReoprtOptionForm = function(id) {
		fcom.displayProcessing();
		//$.facebox(function() {
			fcom.ajax(fcom.makeUrl('IssueReportOptions', 'form', [id]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	editTeachingLanguageFormNew = function(tLangId){
		$.facebox(function() {	editOptionForm(tLangId);
		});
	};
	
	editOptionForm = function(tLangId){
		fcom.displayProcessing();
		//$.facebox(function() {
			fcom.ajax(fcom.makeUrl('TeachingLanguage', 'form', [tLangId]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	
	setupIssueOption = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('IssueReportOptions', 'setup'), data, function(t) {			
			//$.mbsmessage.close();
			reloadList();
			if (t.langId>0) {
				editOptionLangForm(t.optId, t.langId);
				return ;
			}
				
			$(document).trigger('close.facebox');
		});
	}
	
	editOptionLangForm = function(optId,langId){
		fcom.displayProcessing();
			fcom.ajax(fcom.makeUrl('IssueReportOptions', 'langForm', [optId, langId]), '', function(t) {
				fcom.updateFaceboxContent(t);
			});
	};
	
	setupLangIssueReport = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('IssueReportOptions', 'langSetup'), data, function(t) {
			reloadList();			
			if (t.langId>0) {
				editOptionLangForm(t.optId, t.langId);
				return ;
			}
						
			$(document).trigger('close.facebox');
		});
	};
	
	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='tLangId='+id;
		fcom.updateWithAjax(fcom.makeUrl('TeachingLanguage', 'deleteRecord'),data,function(res){		
			reloadList();
		});
	};
	
	activeStatus = function(obj){
		
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var tLangId = parseInt(obj.value);
		if(tLangId < 1){

			//$.mbsmessage(langLbl.invalidRequest,true,'alert--danger');
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='tLangId='+tLangId+"&status="+active;
		fcom.ajax(fcom.makeUrl('TeachingLanguage','changeStatus'),data,function(res){
		var ans =$.parseJSON(res);
			if(ans.status == 1){
				$(obj).removeClass("inactive");
				$(obj).addClass("active");
				$(".status_"+tLangId).attr('onclick','inactiveStatus(this)');
				fcom.displaySuccessMessage(ans.msg);				
			}else{
				fcom.displayErrorMessage(ans.msg);				
			}
		});
	};
	
	inactiveStatus = function(obj){
	
	if(!confirm(langLbl.confirmUpdateStatus)){
		e.preventDefault();
		return;
	}
	var tLangId = parseInt(obj.value);
	if(tLangId < 1){

		//$.mbsmessage(langLbl.invalidRequest,true,'alert--danger');
		fcom.displayErrorMessage(langLbl.invalidRequest);
		return false;
	}
	data='tLangId='+tLangId+"&status="+inActive;
	fcom.ajax(fcom.makeUrl('IssueReportOptions','changeStatus'),data,function(res){
	var ans =$.parseJSON(res);
		if(ans.status == 1){
			$(obj).removeClass("active");
				$(obj).addClass("inactive");
				$(".status_"+tLangId).attr('onclick','activeStatus(this)');
			fcom.displaySuccessMessage(ans.msg);				
		}else{
			fcom.displayErrorMessage(ans.msg);				
		}
	});
	};
	   
})();	