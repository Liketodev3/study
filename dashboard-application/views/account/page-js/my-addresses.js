$(document).ready(function(){
	searchAddresses();		
});

(function() {
	var runningAjaxReq = false;
	var dv = '#listing';
	
	checkRunningAjax = function(){
		if( runningAjaxReq == true ){
			console.log(runningAjaxMsg);
			return;
		}
		runningAjaxReq = true;
	};
	
	searchAddresses = function(){		
		$(dv).html(fcom.getLoader());		
		fcom.ajax(fcom.makeUrl('Account','searchAddresses'),'',function(res){
			$(dv).html(res);
		});
	};
	
	addAddressForm = function(id){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'addAddressForm', [id]), '', function(t) {
			$(dv).html(t);
		});
	};
	
	setupAddress = function(frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Addresses', 'setUpAddress'), data, function(t) {						
			searchAddresses();
		});
	};
	
	setDefaultAddress = function(id){
		var agree = confirm(langLbl.confirmDefault);
		if( !agree ){
			return false;
		}
		data='id='+id;
		fcom.updateWithAjax(fcom.makeUrl('Addresses','setDefault'),data,function(res){		
			searchAddresses();
		});
	};
	
	removeAddress = function(id){
		var agree = confirm(langLbl.confirmDelete);
		if( !agree ){
			return false;
		}
		data='id='+id;
		fcom.updateWithAjax(fcom.makeUrl('Addresses','deleteRecord'),data,function(res){		
			searchAddresses();
		});
	};
	
})();	