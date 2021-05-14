var cart = {
	isUserLogged: function (){
		$.loader.show();
		if (isUserLogged() == 0) {
			logInFormPopUp();
			$.loader.hide();
			return false;
		}
		return false;
	},
	add: function (teacherId, lessonQty, lessonDuration, languageId) {
		teacherId = parseInt(teacherId);
		lessonQty = parseInt(lessonQty);
		lessonDuration = parseInt(lessonDuration);
		languageId = parseInt(languageId);
		if(1 > teacherId)
		{
			return false;
		}
		var data = '&teacherId=' + teacherId + '&lessonQty=' + lessonQty + '&languageId=' + languageId + '&lessonDuration=' + lessonDuration;
		cart.addToCart(data);
	},
	addGroupClass: function (teacherId, groupClassId) {
		teacherId = parseInt(teacherId);
		groupClassId = parseInt(lessonQty);
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
	addToCart: function (data) {
		if(!isUserLogged()){
			return false;
		}
		fcom.ajax(fcom.makeUrl('Cart', 'add'), data, function (res) {
			if (res.status == 1) {
				if (res.isFreeLesson) {
					cart.confirmOrder();
					return;
				}
				if (res.redirectUrl) {
					window.location.href = res.redirectUrl;
					return;
				}
			}
			$.loader.hide();
			$.mbsmessage(res.msg, true, 'alert alert--danger');

		},{fOutMode:'json'});
	},
	confirmOrder : function () {
		fcom.updateWithAjax(fcom.makeUrl('Checkout', 'confirmOrder'), '', function (ans) {
			if (ans.redirectUrl != '') {
				window.location.href = ans.redirectUrl;
			}
		});
	}
	

};