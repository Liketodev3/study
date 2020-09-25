var cart = {
	add: function( teacherId, lpackageId, startDateTime, endDateTime, languageId, grpclsId ){
		$('.loading-wrapper').show();
		if( isUserLogged() == 0 ){
			logInFormPopUp();
			return false;
		}
		
		if( startDateTime == undefined ){
			startDateTime = '';
		}
		if( endDateTime == undefined ){
			endDateTime = '';
		}
		if( grpclsId == undefined ){
			grpclsId = 0;
		}
		var data = 'grpcls_id=' + grpclsId + '&teacher_id=' + teacherId + '&startDateTime=' + startDateTime + '&endDateTime=' + endDateTime + '&lpackageId=' + lpackageId +'&languageId='+ languageId;
		
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
			$.mbsmessage(resObj.msg,true, 'alert alert--danger');

			
		});
	}
};