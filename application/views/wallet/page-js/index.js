$(document).ready(function(){
	searchCredits(document.frmCreditSrch);
});
(function() {
	var dv = '#creditListing';
	
	searchCredits = function(frm){
		var data = fcom.frmData(frm);
		$(dv).html( fcom.getLoader() );
		
		fcom.ajax(fcom.makeUrl('Wallet','search'), data, function(res){
			$(dv).html(res);
		}); 
	};
	
	goToCreditSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmCreditSrchPaging;		
		$(frm.page).val(page);
		searchCredits(frm);
	};
	
	clearSearch = function(){
		document.frmCreditSrch.reset();
		searchCredits(document.frmCreditSrch);
	};

    closeForm = function(){
        $(document).trigger('close.facebox');            
    }
    
	setUpWalletRecharge = function( frm ){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Wallet', 'setUpWalletRecharge'), data, function(t) {
			if(t.redirectUrl){
				window.location = t.redirectUrl;
			}
		});	
	}

    redeemGiftcardForm = function () {
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('Wallet', 'giftcard-redeem-form'),'', function (t) {
                $.facebox(t, 'faceboxWidth');
            });
        });
    };	
	
	giftcardRedeem = function(frm1){
		if (!$(frm1).validate()) return;
			var data1 = fcom.frmData(frm1);

		fcom.updateWithAjax(fcom.makeUrl('Wallet','reedemGiftcard'), data1, function(res1){
            $(document).trigger('close.facebox');            
			searchCredits(document.frmCreditSrch);
		});
	};	

    withdrwalRequestForm = function () {
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('Wallet', 'requestWithdrawal'),'', function (t) {
                $.facebox(t, 'facebox-medium');
            });
        });
    };	

	setupWithdrawalReq = function(frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Wallet', 'setupRequestWithdrawal'), data, function(t) {
            $(document).trigger('close.facebox');
			searchCredits(document.frmCreditSrch);		
		});	
	};	
	
	
})();