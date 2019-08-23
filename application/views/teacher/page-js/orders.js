$(document).ready(function(){
	searchOrders(document.frmOrderSrch);
})

var dv = '#listItems';
searchOrders = function(frm){	
	var data = fcom.frmData(frm);
	fcom.ajax(fcom.makeUrl('Teacher','getOrders'),data,function(t){
		$(dv).html(t);
	});
};

clearSearch = function(){
	document.frmOrderSrch.reset();
	searchOrders(document.frmOrderSrch);
};		

goToSearchPage = function(page) {
	if(typeof page == undefined || page == null){
		page = 1;
	}		
	var frm = document.frmOrderSearchPaging;		
	$(frm.page).val(page);
	searchOrders(frm);
};