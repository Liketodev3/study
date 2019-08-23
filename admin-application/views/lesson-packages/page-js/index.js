$(document).ready(function(){
	searchLessonPackage(document.frmLessonPackageSearch);
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
		var frm = document.frmLessonPackageSearchPaging;		
		$(frm.page).val(page);
		searchLessonPackage(frm);
	}

	reloadList = function() {
		searchLessonPackage();
	};	
	
	searchLessonPackage = function(form){		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('LessonPackages','search'),data,function(res){
			$(dv).html(res);			
		});
	};
	addLessonPackageForm = function(id) {
		
		$.facebox(function() { LessonPackageForm(id);
		});
	};
	
	LessonPackageForm = function(id) {
		fcom.displayProcessing();
		//$.facebox(function() {
			fcom.ajax(fcom.makeUrl('LessonPackages', 'form', [id]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	editLessonPackageFormNew = function(lPackageId){
		$.facebox(function() {	editLessonPackageForm(lPackageId);
		});
	};
	
	editLessonPackageForm = function(lPackageId){
		fcom.displayProcessing();
		//$.facebox(function() {
			fcom.ajax(fcom.makeUrl('LessonPackages', 'form', [lPackageId]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	
	setupLessonPackage = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LessonPackages', 'setup'), data, function(t) {			
			//$.mbsmessage.close();
			reloadList();
			if (t.langId>0) {
				editLessonPackageLangForm(t.lPackageId, t.langId);
				return ;
			}
				
			$(document).trigger('close.facebox');
		});
	}
	
	editLessonPackageLangForm = function(lPackageId,langId){
		fcom.displayProcessing();
	//	$.facebox(function() {
			fcom.ajax(fcom.makeUrl('LessonPackages', 'langForm', [lPackageId,langId]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	
	setupLangLessonPackage = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('LessonPackages', 'langSetup'), data, function(t) {
			reloadList();			
			if (t.langId>0) {
				editLessonPackageLangForm(t.lPackageId, t.langId);
				return ;
			}
						
			$(document).trigger('close.facebox');
		});
	};
	
	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='lPackageId='+id;
		fcom.updateWithAjax(fcom.makeUrl('LessonPackages', 'deleteRecord'),data,function(res){		
			reloadList();
		});
	};
	
	activeStatus = function(obj){
		
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var lPackageId = parseInt(obj.value);
		if(lPackageId < 1){

			//$.mbsmessage(langLbl.invalidRequest,true,'alert--danger');
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='lPackageId='+lPackageId+"&status="+active;
		fcom.ajax(fcom.makeUrl('LessonPackages','changeStatus'),data,function(res){
		var ans =$.parseJSON(res);
			if(ans.status == 1){
				$(obj).removeClass("inactive");
				$(obj).addClass("active");
				$(".status_"+lPackageId).attr('onclick','inactiveStatus(this)');
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
	var lPackageId = parseInt(obj.value);
	if(lPackageId < 1){

		//$.mbsmessage(langLbl.invalidRequest,true,'alert--danger');
		fcom.displayErrorMessage(langLbl.invalidRequest);
		return false;
	}
	data='lPackageId='+lPackageId+"&status="+inActive;
	fcom.ajax(fcom.makeUrl('LessonPackages','changeStatus'),data,function(res){
	var ans =$.parseJSON(res);
		if(ans.status == 1){
			$(obj).removeClass("active");
				$(obj).addClass("inactive");
				$(".status_"+lPackageId).attr('onclick','activeStatus(this)');
			fcom.displaySuccessMessage(ans.msg);				
		}else{
			fcom.displayErrorMessage(ans.msg);				
		}
	});
	};
	
	clearSearch = function(){
		document.frmSearch.reset();
		searchLessonPackage(document.frmSearch);
	};
	
	

})();	


