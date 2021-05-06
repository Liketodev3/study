$(document).ready(function(){
	searchPriceSlabs(1);
});

(function() {
	var dv = '#listing';
	
	goToSearchPage = function(page) {	
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.priceSlabPagingForm;		
		$(frm.page).val(page);
		searchPriceSlabs(frm);
	}

	reloadList = function() {
		searchPriceSlabs();
	};	
	
	searchPriceSlabs = function(page){		
		if(!page || page == 'undefind') {
			page = 1;
		}
		var data = 'page='+page;
		
		fcom.ajax(fcom.makeUrl('PriceSlabs','search'),data,function(res){
			$(dv).html(res);			
		});
	};

	priceSlabForm = function(id) {
		fcom.displayProcessing();
			fcom.ajax(fcom.makeUrl('PriceSlabs', 'form', [id]), '', function(t) {
				fcom.updateFaceboxContent(t);
			});
	};

		
	setupPriceSlab = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('PriceSlabs', 'setup'), data, function(t) {
			reloadList();
			$(document).trigger('close.facebox');
		});
	}

	changeStatus = function (statusTab, psId, status) {
		data =  "psId="+psId+"&status="+status;
		fcom.updateWithAjax(fcom.makeUrl('PriceSlabs', 'changeStatus'), data, function (data) {
			let newStatus = 1;
			statusClass = "";
			if (status == 1) {
				newStatus = 0;
				statusClass = "active";
			}

			$(statusTab).attr("onclick", "changeStatus(this," + psId + "," + newStatus + ")");
			$(statusTab).removeClass("active").addClass(statusClass);
		});
	};
	
	clearSearch = function(){
		document.frmSearch.reset();
		searchPriceSlabs(document.frmSearch);
	};
	
})();	


