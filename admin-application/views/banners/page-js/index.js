$(document).ready(function(){
	searchBanners(document.frmBannerSearch);
});

(function() {
	var currentPage = 1;
	var active = 1;
	var inActive = 0;
	var runningAjaxReq = false;
	var dv = '#listing';
	
addBannersLayouts = function() {
		$.facebox(function() {bannersLayouts();

		});
	};

	bannersLayouts = function() {

	//	$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Banners', 'layouts'), '', function(t) {
				//$.facebox(t,'faceboxWidth');
				fcom.updateFaceboxContent(t);
			});
		//});
	};
	redirecrt= function(redirecrt){

	var url=	SITE_ROOT_URL +''+redirecrt;
	window.location=url;
	}
	
	goToSearchPage = function(page) {	
		if(typeof page == undefined || page == null){
			page =1;
		}
		var frm = document.frmBannerSearchPaging;		
		$(frm.page).val(page);
		searchBanners(frm);
	};
	
	reloadList = function() {
		var frm = document.frmBannerSearchPaging;
		searchBanners(frm);
	};
	
	searchBanners = function(form){		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Banners','search'),data,function(res){			
			$(dv).html(res);
		});
	};
	
	addBannerLocation = function(blocationId){
		$.facebox(function() {
			bannerLocation(blocationId);
		});
	};

	bannerLocation = function(blocationId){
		fcom.displayProcessing();
			fcom.ajax(fcom.makeUrl('Banners', 'bannerLocation', [blocationId]), '', function(t) {
				fcom.updateFaceboxContent(t);
			});
	};
	bannerLocationLangForm = function(blocationId,langId){
			fcom.displayProcessing();
			fcom.ajax(fcom.makeUrl('Banners', 'bannerLocLangForm', [blocationId,langId]), '', function(t) {
				fcom.updateFaceboxContent(t);
			});
	};
	
	setupLocation = function(frm){ 
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Banners', 'setupLocation'), data, function(t) {								
			reloadList();			
			$(document).trigger('close.facebox');
		});
	};
	langSetup = function(frm){ 
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Banners', 'langSetupLocation'), data, function(t) {								
			reloadList();			
			$(document).trigger('close.facebox');
		});
	};
	
	clearSearch = function(){
		document.frmTaxSearch.reset();
		searchTax(document.frmTaxSearch);
	};
	
	activeStatusBannerLocation = function( obj ){
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var blocationId = parseInt(obj.value);
		if( blocationId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data = 'blocationId='+blocationId+"&status="+active;
		fcom.ajax(fcom.makeUrl('Banners','changeStatusBannerLocation'),data,function(res){
			var ans =$.parseJSON(res);
			if(ans.status == 1){
				fcom.displaySuccessMessage(ans.msg);
				$(obj).removeClass("inactive");
				$(obj).addClass("active");
				$(".status_"+blocationId).attr('onclick','inactiveStatusBannerLocation(this)');
			}else{
				fcom.displayErrorMessage(ans.msg);
			}
		});
	};
	
	
	inactiveStatusBannerLocation = function( obj ){
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var blocationId = parseInt(obj.value);
		if( blocationId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data = 'blocationId='+blocationId+"&status="+inActive;
		fcom.ajax(fcom.makeUrl('Banners','changeStatusBannerLocation'),data,function(res){
			var ans =$.parseJSON(res);
			if(ans.status == 1){
				fcom.displaySuccessMessage(ans.msg);
				$(obj).removeClass("active");
				$(obj).addClass("inactive");
				$(".status_"+blocationId).attr('onclick','activeStatusBannerLocation(this)');
			}else{
				fcom.displayErrorMessage(ans.msg);
			}
		});
	};
	
	
	
})();

(function() {
	displayImageInFacebox = function(str){
		$.facebox('<img width="800px;" src="'+str+'">');
	}
})();