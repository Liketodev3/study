(function($){
	var _this			= false;
	var _subText 		= false;
	$(document).ready(function() {
		$(window).on('load',function(){
			try{

				if(typeof publishable_key != typeof undefined){
					// this identifies your website in the createToken call below
					Stripe.setPublishableKey(publishable_key);
					function stripeResponseHandler(status, response) {
						$('#frmPaymentForm').find(":submit").attr('disabled', 'disabled');
						$submit = true;
						if(_this && _subText){
							_this.find('input[type=submit]').val(_subText);
						}

						if (response.error) {
							$("#frmPaymentForm").prepend('<div class="alert alert--danger">'+response.error.message+'</div>');
							$("#frmPaymentForm").find(":submit").removeAttr('disabled');
						} else {

							var form$ = $("#frmPaymentForm");
							// token contains id, last4, and card type
							var token = response['id'];
							// insert the token into the form so it gets submitted to the server
							form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
									// and submit
							form$.get(0).submit();

						}

					}
					$submit = true;
					$("#frmPaymentForm").submit(function(event) {
						// prop('disabled', true);
						$('.alert--danger').remove();

						_this				= $(this);
						var _numberWrap 	= $('#cc_number');
						var _cvvWrap	 	= $('#cc_cvv');
						var _expMonthWrap 	= $('#cc_expire_date_month');
						var _expYearWrap 	= $('#cc_expire_date_year');
						_subText 			= _this.find('input[type=submit]').val();


						if($submit && _numberWrap.length > 0 && _cvvWrap.length > 0 && _expMonthWrap.length > 0 && _expYearWrap.length > 0 ){

							var _numberValue 	= _numberWrap.val().trim();
							var _cvvValue 		= _cvvWrap.val().trim();
							var _expMonthValue 	= _expMonthWrap.val().trim();
							var _expYearValue 	= _expYearWrap.val().trim();

							if( _numberValue != '' && _cvvValue != '' && _expMonthValue != '' && _expYearValue != '' ){
								$submit = false;
								_subText = _this.find('input[type=submit]').val();
								_this.find('input[type=submit]').val(_this.find('input[type=submit]').data('processing-text'));

								Stripe.createToken({
									number: _numberValue,
									cvc: _cvvValue,
									exp_month: _expMonthValue,
									exp_year: _expYearValue
								}, stripeResponseHandler);
							}

						}
						return $submit; // submit from callback
					});

				}

			}catch(e){
				console.log(e.message);
			}
		});

		$("#cc_number" ).keydown(function() {
			var obj = $(this);
			var cc = obj.val();
			obj.attr('class','p-cards');
			if(cc != ''){
				var card_type = getCardType(cc).toLowerCase();
				obj.addClass('p-cards ' + card_type );
			}
		});

	});
})(jQuery);

function getCardType(number){
    // visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "Visa";

    // Mastercard
    re = new RegExp("^5[1-5]");
    if (number.match(re) != null)
        return "Mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "AMEX";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "Discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "Diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "Diners - Carte Blanche";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "JCB";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "Visa Electron";

    return "";
}
