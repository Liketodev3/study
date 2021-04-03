var cart = {
	add: function( teacherId, lpackageId, startDateTime, endDateTime, languageId, grpclsId, lessonDuration ){
		$.loader.show();
		if( isUserLogged() == 0 ){
			logInFormPopUp();
			$.loader.hide();
			return false;
		}
		
		startDateTime = startDateTime || '';
		endDateTime = endDateTime || '';
		grpclsId = grpclsId || 0;
		lessonDuration = lessonDuration || 0;

		var data = 'grpcls_id=' + grpclsId + '&teacher_id=' + teacherId + '&startDateTime=' + startDateTime + '&endDateTime=' + endDateTime + '&lpackageId=' + lpackageId +'&languageId='+ languageId + '&lessonDuration=' + lessonDuration;
		
		fcom.ajax( fcom.makeUrl('Cart','add'), data ,function(res){

			var resObj = $.parseJSON(res);
			if(resObj.status == 1){
				if(resObj.isFreeLesson){
					fcom.updateWithAjax(fcom.makeUrl('Checkout', 'confirmOrder'), '', function(ans) {
						if( ans.redirectUrl != '' ){
							window.location.href = ans.redirectUrl;
						}
					});
					return;
				}
				if( resObj.redirectUrl ){
					window.location.href = resObj.redirectUrl;
					return;
				}
			}
			$.loader.hide();
			$.mbsmessage(resObj.msg,true, 'alert alert--danger');

			
		});
	}
};