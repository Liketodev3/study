var cart = {
	teacherId : 0,
	languageId : 0,
	lessonDuration : 0,
	lessonQty : 0,
	couponCode :'',
	isWalletSelect :0,
	paymentMethodId :0,
	getTeachLangues: function(teacherId) {
		
		teacherId = parseInt(teacherId);
		if(1 > teacherId)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&languageId="+cart.languageId;
		cart.checkoutStep("getUserTeachLangues", data);
	},
	getSlotDuration: function() {
		teacherId = parseInt(cart.teacherId);
		languageId =  parseInt(cart.languageId);
		lessonDuration = parseInt(cart.lessonDuration);
		if(1 > teacherId || 1 > languageId)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&languageId="+languageId+"&lessonDuration="+lessonDuration;
		cart.checkoutStep("getSlotDuration", data);
	},
	getTeacherPriceSlabs:  function() {
		teacherId = parseInt(cart.teacherId);
		languageId =  parseInt(cart.languageId);
		lessonDuration =  parseInt(cart.lessonDuration);
		lessonQty =  parseInt(cart.lessonQty);
		if(1 > teacherId || 1 > languageId || 1 > lessonDuration)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&languageId="+languageId+"&lessonDuration="+lessonDuration+"&lessonQty="+lessonQty;
		cart.checkoutStep("getTeacherPriceSlabs", data);
	},
	getLessonQtyPrice :function(){
		teacherId = parseInt(cart.teacherId);
		languageId =  parseInt(cart.languageId);
		lessonDuration =  parseInt(cart.lessonDuration);
		lessonQty =  parseInt(cart.lessonQty);
		if(1> lessonQty && 1 > teacherId || 1 > languageId || 1 > lessonDuration)
		{
			return false;
		}
		data = "teacherId="+teacherId+"&languageId="+languageId+"&lessonDuration="+lessonDuration+"&lessonQty="+lessonQty;
		fcom.ajax(fcom.makeUrl('Checkout', 'getLessonQtyPrice'), data, function (res) {
			res.status = parseInt(res.status);
			if (res.status == 1) {
				$('.slab-price-js').html(res.priceLabel);
				return;
			}
			$.mbsmessage(res.msg, true, 'alert alert--danger');
			
		},{fOutMode:'json'});
	},
	getPaymentSummary :function(){
		cart.checkoutStep("getPaymentSummary","");
	},
	addTeacherLesson :function(){
		teacherId = parseInt(cart.teacherId);
		languageId =  parseInt(cart.languageId);
		lessonDuration =  parseInt(cart.lessonDuration);
		lessonQty =  parseInt(cart.lessonQty);
		if(1> lessonQty && 1 > teacherId || 1 > languageId || 1 > lessonDuration)
		{
			return false;
		}
		data = "teacherId="+teacherId+"&languageId="+languageId+"&lessonDuration="+lessonDuration+"&lessonQty="+lessonQty;
		cart.addToCart(data, "getPaymentSummary", data);
	},
	walletSelection:  function(el){
		cart.isWalletSelect = ($(el).is(":checked")) ? 1 : 0;
		var data = 'payFromWallet=' + cart.isWalletSelect;
		$.loader.show();
		fcom.ajax(fcom.makeUrl('Checkout', 'walletSelection'), data, function (ans) {
			$.loader.hide();
			cart.getPaymentSummary();
		});
	},
	applyPromoCode : function(code) {
		cart.couponCode = code.toString();
		if(cart.couponCode == ''){
			return;
		}
		data = 'coupon_code='+cart.couponCode;
		fcom.updateWithAjax(fcom.makeUrl('Cart', 'applyPromoCode'), data, function (res) {
			cart.getPaymentSummary();
		});
	},
	update: function (teacherId) {

		teacherId = parseInt(teacherId);

		if(1 > teacherId)
		{
			return false;
		}
		
		lessonQty = parseInt($("#lessonQty").val());
		lessonDuration = parseInt($('[name="lessonDuration"]').val());


		languageId = parseInt($('[name="language"]').val());

		var data = '&teacherId=' + teacherId + '&lessonQty=' + lessonQty + '&languageId=' + languageId + '&lessonDuration=' + lessonDuration;
		cart.addToCart(data);
	},
	add: function (teacherId, languageId, lessonDuration, lessonQty, step) {
		cart.teacherId = parseInt(teacherId);
		cart.lessonQty = parseInt(lessonQty);
		cart.lessonDuration = parseInt(lessonDuration);
		cart.languageId = parseInt(languageId);
		if(1 > cart.teacherId || 1 > cart.languageId || 1 > cart.lessonDuration ||1 > cart.lessonQty)
		{
			return false;
		}

		var data = '&teacherId=' + cart.teacherId + '&lessonQty=' + cart.lessonQty + '&languageId=' + cart.languageId + '&lessonDuration=' + cart.lessonDuration;
		cart.addToCart(data, screen, data);
	},
	addGroupClass: function (teacherId, groupClassId) {
		teacherId = parseInt(teacherId);
		groupClassId = parseInt(groupClassId);
		if(1 > teacherId || 1 > groupClassId)
		{
			return false;
		}
		var data = '&teacherId=' + teacherId + '&grpclsId=' + groupClassId;
		cart.addToCart(data, "getPaymentSummary", data);
	},
	addFreeTrial: function (teacherId, startDateTime, endDateTime, languageId) {
		teacherId = parseInt(teacherId);
		languageId = parseInt(languageId);
		isStartDateTimeValid = moment(startDateTime).isValid();
		isEndDateTimeValid = moment(endDateTime).isValid();
		if(1 > teacherId || 1 > languageId || !isStartDateTimeValid ||  !isEndDateTimeValid || moment(startDateTime) >= moment(endDateTime))
		{
			return false;
		}
		var data = 'isFreeTrial=1'+ '&teacherId=' + teacherId +'&languageId='+languageId+'&startDateTime=' + startDateTime + '&endDateTime=' + endDateTime;
		cart.addToCart(data);
	},
	addToCart: function (data, setp, setpData) {
		$.loader.show();
		if (isUserLogged() == 0) {
			$.loader.hide();
			logInFormPopUp();
			return false;
		}
		fcom.ajax(fcom.makeUrl('Cart', 'add'), data, function (res) {
			$.loader.hide();
			if (res.status == 1) {
				if (res.isFreeLesson) {
					cart.confirmOrder();
					return;
				}
				if (res.redirectUrl) {
					window.location.href = res.redirectUrl;
					return;
				}
				if(setp){
					cart.checkoutStep(setp, setpData);
				}
			}else{
				$.mbsmessage(res.msg, true, 'alert alert--danger');
			}
			$.loader.hide();
		},{fOutMode:'json'});
	},
	checkoutStep :function(step, data){
		$.loader.show();
		if (isUserLogged() == 0) {
			$.loader.hide();
			logInFormPopUp();
			return false;
		}
		fcom.ajax(fcom.makeUrl('Checkout', step), data, function (data) {
			try {
				data = JSON.parse(data);
				if(data.status == 0){
					$.mbsmessage(data.msg, true, 'alert alert--danger');
					$.loader.hide();
					return;
				}
			} catch (e) {
				$.facebox(data, '');
			}
		});
	},
	confirmOrder : function (orderType) {
		cart.paymentMethodId = parseInt($('[name="payment_method"]:checked').val());
		orderType = parseInt(orderType);
		data = "order_type="+orderType+"&pmethod_id="+cart.paymentMethodId;

		fcom.updateWithAjax(fcom.makeUrl('Checkout', 'confirmOrder'), data, function (ans) {
			if (ans.redirectUrl != '') {
				window.location.href = ans.redirectUrl;
			}
		});
	}
	

};