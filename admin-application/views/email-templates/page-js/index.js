$(document).ready(function(){
	searchEtpls(document.frmEtplsSearch);
});

(function(){
	var currentPage = 1;
	var active = 1;
	var inActive = 0;
	var runningAjaxReq = false;
	var dv = '#listing';
	
	goToSearchPage = function(page) {	
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmEtplsSrchPaging;		
		$(frm.page).val(page);
		searchEtpls(frm);
	};
	
	reloadList = function() {
		var frm = document.frmEtplsSrchPaging;
		searchEtpls(frm);
	};
	
	searchEtpls = function(form){		
		/*[ this block should be written before overriding html of 'form's parent div/element, otherwise it will through exception in ie due to form being removed from div */
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		/*]*/
		$(dv).html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('EmailTemplates','search'),data,function(res){
			$(dv).html(res);
		});
	};
	
	editEtplLangForm = function(etplCode,langId){
		fcom.resetEditorInstance();
		$.facebox(function() {
			editLangForm(etplCode,langId);
		});
	};
	
	 
	editLangForm = function(etplCode,langId){
		fcom.displayProcessing();
		fcom.resetEditorInstance();	
		
		fcom.ajax(fcom.makeUrl('EmailTemplates', 'langForm', [etplCode,langId]), '', function(t) {
			fcom.updateFaceboxContent(t);
			fcom.setEditorLayout(langId);
			fcom.resetFaceboxHeight();
			var frm = $('#facebox form')[0];	
			var validator = $(frm).validation({errordisplay: 3});
			$(frm).submit(function(e) {		
				e.preventDefault();
				validator.validate();
				if (!validator.isValid()) return;	
				var data = fcom.frmData(frm);
				fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'langSetup'), data, function(t) {
					fcom.resetEditorInstance();							
					reloadList();						
					if (t.lang_id>0) {
						editLangForm(t.etplCode, t.lang_id);							
						return ;
					}						
					$(document).trigger('close.facebox');						
				});
			});
			
		});		
	};
	
	setupEtplLang = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);	
		fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'langSetup'), data, function(t) {						
			reloadList();			
			$(document).trigger('close.facebox');
		});
	};
	
	activeStatus = function(obj){
		if(!confirm(langLbl.confirmUpdateStatus)){return;}
		var etplCode = obj.id;
		if(etplCode == ''){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='etplCode='+etplCode+'&status='+active;
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('EmailTemplates','changeStatus'),data,function(res){
		var ans =$.parseJSON(res);
			if(ans.status == 1){
				$(obj).removeClass("inactive");
				$(obj).addClass("active");
				$(".status_"+etplCode).attr('onclick','inactiveStatus(this)');
				fcom.displaySuccessMessage(ans.msg);
			}else{
				fcom.displayErrorMessage(ans.msg);
			}
		});
		$.systemMessage.close();
	};

	inactiveStatus = function(obj){
		if(!confirm(langLbl.confirmUpdateStatus)){return;}
		var etplCode = obj.id;
		if(etplCode == ''){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='etplCode='+etplCode+'&status='+inActive;
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('EmailTemplates','changeStatus'),data,function(res){
		var ans =$.parseJSON(res);
			if(ans.status == 1){
				$(obj).removeClass("active");
				$(obj).addClass("inactive");
				$(".status_"+etplCode).attr('onclick','activeStatus(this)');
				fcom.displaySuccessMessage(ans.msg);
			}else{
				fcom.displayErrorMessage(ans.msg);
			}
		});
		$.systemMessage.close();
	};
	
	clearSearch = function(){		
		document.frmEtplsSearch.reset();		
		searchEtpls(document.frmEtplsSearch);
	};
})()	