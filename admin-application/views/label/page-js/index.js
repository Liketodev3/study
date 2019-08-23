$(document).ready(function(){
	searchLabels(document.frmLabelsSearch);
});

(function(){
	var currentPage = 1;
	var runningAjaxReq = false;
	var dv = '#listing';
	
	goToSearchPage = function(page) {	
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmLabelsSrchPaging;		
		$(frm.page).val(page);
		searchLabels(frm);
	};
	
	reloadList = function() {
		var frm = document.frmLabelsSrchPaging;
		searchLabels(frm);
	};
	
	searchLabels = function(frm){
		//if (!$(frm).validate()) return;
		$(dv).html(fcom.getLoader());
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		fcom.ajax(fcom.makeUrl('Label','search'),data,function(res){
			$(dv).html(res);
		});
	};
	
	labelsForm = function(labelId){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Label', 'form', [labelId]), '', function(t) {
				$.facebox(t,'faceboxWidth');
			});
		});
	};
	
	setupLabels = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Label', 'setup'), data, function(t) {						
			reloadList();			
			$(document).trigger('close.facebox');
		});
	};
	
	clearSearch = function(){		
		document.frmLabelsSearch.reset();		
		searchLabels(document.frmLabelsSearch);
	};
	
	
})()	