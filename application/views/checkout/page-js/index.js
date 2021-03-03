var financialSummaryDiv = "#financialSummaryListing";
var paymentDiv = '#paymentDiv';
var couponDiv = '.cpn-frm';

$("document").ready(function(){
	loadFinancialSummary();
});

(function() {
	loadFinancialSummary = function(){
		$(financialSummaryDiv).html( fcom.getLoader() );
		fcom.ajax(fcom.makeUrl('Checkout', 'listFinancialSummary'), '', function(ans) {
		var ans = JSON.parse(ans);
			$(financialSummaryDiv).html(ans.html);
			if(ans.couponApplied > 0){
				$('.coupon-input').click();
			}
		});
	};

	loadPaymentSummary = function(){
		$(paymentDiv).html( fcom.getLoader() );
		fcom.ajax(fcom.makeUrl('Checkout', 'paymentSummary'), '', function(ans) {
			try {
					data = JSON.parse(ans);
					if( data.redirectUrl != '' ){
						window.location.href = data.redirectUrl;
					}
						return false;
			} catch (e) {
					$(paymentDiv).html(ans);
			}


			//$("#payment_methods_tab  li:first a").trigger('click');
		});
	};

	walletSelection = function(el){
		var wallet = ( $(el).is(":checked") ) ? 1 : 0;
		var data = 'payFromWallet=' + wallet;
		fcom.ajax(fcom.makeUrl('Checkout', 'walletSelection'), data, function(ans) {
			loadPaymentSummary();
			loadFinancialSummary();
		});
	};

	confirmOrder = function( frm ){
		var data = fcom.frmData(frm);
		//$("#checkout-left-side").addClass('form--processing');
		fcom.updateWithAjax(fcom.makeUrl('Checkout', 'confirmOrder'), data, function(ans) {
			if( ans.redirectUrl != '' ){
				$( location ).attr( "href", ans.redirectUrl );
			}
		});
		return false;
	}

	$(document).on('click','.coupon-input',function(){
		$(couponDiv).html( fcom.getLoader() );
		fcom.ajax(fcom.makeUrl('Checkout', 'getCouponForm'), '', function(ans) {
			$(couponDiv).html(ans);
			$("input[name='coupon_code']").focus();
		});
	});

	applyPromoCode  = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);

		fcom.updateWithAjax(fcom.makeUrl('Cart','applyPromoCode'),data,function(res){
			$("#facebox .close").trigger('click');
			$.systemMessage.close();
			loadFinancialSummary();
			loadPaymentSummary();
		});
	};

	triggerApplyCoupon = function(coupon_code){
		document.frmPromoCoupons.coupon_code.value = coupon_code;
		applyPromoCode(document.frmPromoCoupons);
		return false;
	};

	removePromoCode  = function(){
		fcom.updateWithAjax(fcom.makeUrl('Cart','removePromoCode'),'',function(res){
		loadFinancialSummary();
		loadPaymentSummary();
		});
	};

    redeemGiftcardForm = function () {
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('wallet', 'giftcard-redeem-form'),'', function (t) {
                $.facebox(t, 'faceboxWidth');
            });
        });
    };
	giftcardRedeem = function(frm1){
		if (!$(frm1).validate()) return;
			var data1 = fcom.frmData(frm1);

		fcom.updateWithAjax(fcom.makeUrl('Wallet','reedemGiftcard'), data1, function(res1){
			searchCredits(document.frmCreditSrch);
		});
	};


	addToCart =  function( teacherId, lpackageId, languageId ,startDateTime, endDateTime, grpclsId, lessonDuration ){
		startDateTime ||= '';
		endDateTime ||= '';
		grpclsId ||= 0;
		lessonDuration ||= 60;

        if(parseInt(grpclsId)>0){
            return false;
        }

		var data = 'teacher_id=' + teacherId + '&languageId=' + languageId + '&startDateTime=' + startDateTime + '&endDateTime=' + endDateTime + '&lpackageId=' + lpackageId + '&lessonDuration=' + lessonDuration + '&checkoutPage=1';
		$('.cart-lang-id-js').html(teachLanguages[languageId]);
		fcom.updateWithAjax( fcom.makeUrl('Cart','add'), data ,function(ans){
			if( ans.redirectUrl ){
				//fcom.waitAndRedirect( ans.redirectUrl );
				loadFinancialSummary();
				loadPaymentSummary();
				getLangPackages(teacherId, languageId, lessonDuration);
			}
		});
	};

	getLangPackages =  function( teacherId, languageId, lessonDuration ){

		var data = 'teacher_id=' + teacherId + '&languageId=' + languageId + '&lessonDuration='+lessonDuration ;
		fcom.ajax( fcom.makeUrl('Checkout','getLanguagePackages'), data ,function(ans){
			$('#lsn-pckgs').html(ans);
			getBookingDurations();
		});
	};

	getBookingDurations =  function(){
		fcom.ajax( fcom.makeUrl('Checkout', 'getBookingDurations'), '' ,function(ans){
			$('#booking-durations-js').html(ans);
		});
	};

})();
