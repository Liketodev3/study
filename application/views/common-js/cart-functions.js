var cart = {
	add: function( teacherId, lpackageId, startDateTime, endDateTime, languageId, grpclsId ){
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
		
		fcom.updateWithAjax( fcom.makeUrl('Cart','add'), data ,function(ans){
			if( ans.redirectUrl ){
				fcom.waitAndRedirect( ans.redirectUrl );
			}
		});
	}
};