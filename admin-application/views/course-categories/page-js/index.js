$(document).ready(function(){
	searchCourseCategory(document.frmCourseCategorySearch);
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
		var frm = document.frmCourseCategorySearchPaging;		
		$(frm.page).val(page);
		searchCourseCategory(frm);
	}

	reloadList = function() {
		searchCourseCategory();
	};	
	
	searchCourseCategory = function(form){		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('CourseCategories','search'),data,function(res){
			$(dv).html(res);			
		});
	};
	addCourseCategoryForm = function(id) {
		
		$.facebox(function() { CourseCategoryForm(id);
		});
	};
	
	CourseCategoryForm = function(id) {
		fcom.displayProcessing();
		//$.facebox(function() {
			fcom.ajax(fcom.makeUrl('CourseCategories', 'form', [id]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	editCourseCategoryFormNew = function(cCategoryId){
		$.facebox(function() {	editCourseCategoryForm(cCategoryId);
		});
	};
	
	editCourseCategoryForm = function(cCategoryId){
		fcom.displayProcessing();
		//$.facebox(function() {
			fcom.ajax(fcom.makeUrl('CourseCategories', 'form', [cCategoryId]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	
	setupCourseCategory = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('CourseCategories', 'setup'), data, function(t) {			
			//$.mbsmessage.close();
			reloadList();
			if (t.langId>0) {
				editCourseCategoryLangForm(t.cCategoryId, t.langId);
				return ;
			}
				
			$(document).trigger('close.facebox');
		});
	}
	
	editCourseCategoryLangForm = function(cCategoryId,langId){
		fcom.displayProcessing();
	//	$.facebox(function() {
			fcom.ajax(fcom.makeUrl('CourseCategories', 'langForm', [cCategoryId,langId]), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	
	setupLangCourseCategory = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('CourseCategories', 'langSetup'), data, function(t) {
			reloadList();			
			if (t.langId>0) {
				editCourseCategoryLangForm(t.cCategoryId, t.langId);
				return ;
			}
						
			$(document).trigger('close.facebox');
		});
	};
	
	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='cCategoryId='+id;
		fcom.updateWithAjax(fcom.makeUrl('CourseCategories', 'deleteRecord'),data,function(res){		
			reloadList();
		});
	};
	
	activeStatus = function(obj){
		
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var cCategoryId = parseInt(obj.value);
		if(cCategoryId < 1){

			//$.mbsmessage(langLbl.invalidRequest,true,'alert--danger');
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='cCategoryId='+cCategoryId+"&status="+active;
		fcom.ajax(fcom.makeUrl('CourseCategories','changeStatus'),data,function(res){
		var ans =$.parseJSON(res);
			if(ans.status == 1){
				$(obj).removeClass("inactive");
				$(obj).addClass("active");
				$(".status_"+cCategoryId).attr('onclick','inactiveStatus(this)');
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
	var cCategoryId = parseInt(obj.value);
	if(cCategoryId < 1){

		//$.mbsmessage(langLbl.invalidRequest,true,'alert--danger');
		fcom.displayErrorMessage(langLbl.invalidRequest);
		return false;
	}
	data='cCategoryId='+cCategoryId+"&status="+inActive;
	fcom.ajax(fcom.makeUrl('CourseCategories','changeStatus'),data,function(res){
	var ans =$.parseJSON(res);
		if(ans.status == 1){
			$(obj).removeClass("active");
				$(obj).addClass("inactive");
				$(".status_"+cCategoryId).attr('onclick','activeStatus(this)');
			fcom.displaySuccessMessage(ans.msg);				
		}else{
			fcom.displayErrorMessage(ans.msg);				
		}
	});
	};
	
	clearSearch = function(){
		document.frmSearch.reset();
		searchCourseCategory(document.frmSearch);
	};
	
	

})();	


