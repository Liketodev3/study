var cart = {
	teacherId : 0,
	teachLangId : 0,
	slot : 0,
	lessonQty : 0,
	getTeachLangues: function(teacherId) {
		teacherId = parseInt(teacherId);
		if(1 > teacherId)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&teachLangId="+cart.teachLangId;
		cart.checkoutStep("getUserTeachLangues", data);
	},
	getSlotDuration: function() {
		teacherId = parseInt(cart.teacherId);
		teachLangId =  parseInt(cart.teachLangId);
		slot = parseInt(cart.slot);
		if(1 > teacherId || 1 > teachLangId)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&teachLangId="+teachLangId+"&slot="+slot;
		cart.checkoutStep("getSlotDuration", data);
	},
	getTeacherPriceSlabs:  function() {
		teacherId = parseInt(cart.teacherId);
		teachLangId =  parseInt(cart.teachLangId);
		slot =  parseInt(cart.slot);
		lessonQty =  parseInt(cart.lessonQty);
		if(1 > teacherId || 1 > teachLangId || 1 > slot)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&teachLangId="+teachLangId+"&slot="+slot+"&lessonQty="+lessonQty;
		cart.checkoutStep("getTeacherPriceSlabs", data);
	},
	getTeacherPriceSlabs:  function() {
		teacherId = parseInt(cart.teacherId);
		teachLangId =  parseInt(cart.teachLangId);
		slot =  parseInt(cart.slot);
		lessonQty =  parseInt(cart.lessonQty);
		if(1 > teacherId || 1 > teachLangId || 1 > slot)
		{
			return false;
		}
		cart.teacherId = teacherId;
		data = "teacherId="+teacherId+"&teachLangId="+teachLangId+"&slot="+slot+"&lessonQty="+lessonQty;
		cart.checkoutStep("getTeacherPriceSlabs", data);
	},
	getLessonQtyPrice :function(){
		teacherId = parseInt(cart.teacherId);
		teachLangId =  parseInt(cart.teachLangId);
		slot =  parseInt(cart.slot);
		lessonQty =  parseInt(cart.lessonQty);
		if(1> lessonQty && 1 > teacherId || 1 > teachLangId || 1 > slot)
		{
			return false;
		}
		data = "teacherId="+teacherId+"&teachLangId="+teachLangId+"&slot="+slot+"&lessonQty="+lessonQty;
		fcom.ajax(fcom.makeUrl('Checkout', 'getLessonQtyPrice'), data, function (res) {
			res.status = parseInt(res.status);
			if (res.status == 1) {
				$('.slab-price-js').html(res.priceLabel);
				return;
			}
			$.mbsmessage(res.msg, true, 'alert alert--danger');
			
		},{fOutMode:'json'});
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
	add: function (teacherId, languageId, lessonDuration, lessonQty, screen) {
		teacherId = parseInt(teacherId);
		lessonQty = parseInt(lessonQty);
		lessonDuration = parseInt(lessonDuration);
		languageId = parseInt(languageId);
		if(1 > teacherId)
		{
			return false;
		}
		screen = (screen) ? screen : 'getTeacherPriceSlabs';

		var data = '&teacherId=' + teacherId + '&lessonQty=' + lessonQty + '&languageId=' + languageId + '&lessonDuration=' + lessonDuration;
		cart.addToCart(data, screen);
	},
	addGroupClass: function (teacherId, groupClassId) {
		teacherId = parseInt(teacherId);
		groupClassId = parseInt(groupClassId);
		if(1 > teacherId || 1 > groupClassId)
		{
			return false;
		}
		var data = '&teacherId=' + teacherId + '&grpclsId=' + groupClassId;
		cart.addToCart(data);
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
	addToCart: function (data, screen) {
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
				if(screen){
					cart.changeCheckoutScreen(screen);
				}
			}else{
				$.mbsmessage(res.msg, true, 'alert alert--danger');
			}
			$.loader.hide();
		},{fOutMode:'json'});
	},
	checkoutStep :function(step, data){
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
	confirmOrder : function () {
		fcom.updateWithAjax(fcom.makeUrl('Checkout', 'confirmOrder'), '', function (ans) {
			if (ans.redirectUrl != '') {
				window.location.href = ans.redirectUrl;
			}
		});
	}
	

};