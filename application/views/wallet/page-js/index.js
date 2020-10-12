$(document).ready(function () {
    searchCredits(document.frmCreditSrch);
});
(function () {
    var dv = '#creditListing';

    searchCredits = function (frm) {
        var data = fcom.frmData(frm);
        $(dv).html(fcom.getLoader());

        fcom.ajax(fcom.makeUrl('Wallet', 'search'), data, function (res) {
            $(dv).html(res);
        });
    };

    goToCreditSearchPage = function (page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmCreditSrchPaging;
        $(frm.page).val(page);
        searchCredits(frm);
    };

    clearSearch = function () {
        document.frmCreditSrch.reset();
        searchCredits(document.frmCreditSrch);
    };

    closeForm = function () {
        $(document).trigger('close.facebox');
    }

    setUpWalletRecharge = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Wallet', 'setUpWalletRecharge'), data, function (t) {
            if (t.redirectUrl) {
                window.location = t.redirectUrl;
            }
        });
    }

    redeemGiftcardForm = function () {
        $('.loading-wrapper').show();
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('Wallet', 'giftcard-redeem-form'), '', function (t) {
                $.facebox(t, 'faceboxWidth');
                $('.loading-wrapper').hide();
            });
        });
    };

    giftcardRedeem = function (frm1) {
        if (!$(frm1).validate()) return;
        var data1 = fcom.frmData(frm1);

        fcom.updateWithAjax(fcom.makeUrl('Wallet', 'reedemGiftcard'), data1, function (res1) {
            $(document).trigger('close.facebox');
            //searchCredits(document.frmCreditSrch);
            document.location.reload();
        });
    };

    withdrwalRequestForm = function (methodId) {
        $('.loading-wrapper').show();
        var postData = '';
        if (typeof methodId != 'undefined') {
            postData = 'methodId=' + methodId;
        }
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('Wallet', 'requestWithdrawal'), postData, function (t) {
                $.facebox(t, 'facebox-medium request-Withdrawal-js');
                $('.loading-wrapper').hide();
            });
        });
    };

    getWithdrwalRequestForm = function (methodId) {
        $('.request-Withdrawal-js').html(fcom.getLoader());
        postData = '';
        if (typeof methodId != 'undefined') {
            postData = 'methodId=' + methodId;
        }
        fcom.ajax(fcom.makeUrl('Wallet', 'requestWithdrawal'), postData, function (t) {
            $('.request-Withdrawal-js').html(t);
        });
    };

    var isWithdrawlReqAjaxRunning = false;
	setupWithdrawalReq = function(frm){
		if (!$(frm).validate() || isWithdrawlReqAjaxRunning==true) return;
        isWithdrawlReqAjaxRunning = true;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Wallet', 'setupRequestWithdrawal'), data, function(t) {
            $(document).trigger('close.facebox');
			searchCredits(document.frmCreditSrch);
            isWithdrawlReqAjaxRunning = false;
		});
	};


})();
