var cart = {
	add: function( teacherId, lpackageId, startDateTime, endDateTime, languageId ){
		if( startDateTime == undefined ){
			startDateTime = '';
		}
		if( endDateTime == undefined ){
			endDateTime = '';
		}
		
		var data = 'teacher_id=' + teacherId + '&startDateTime=' + startDateTime + '&endDateTime=' + endDateTime + '&lpackageId=' + lpackageId +'&languageId='+ languageId;
		
		fcom.updateWithAjax( fcom.makeUrl('Cart','add'), data ,function(ans){
			if( ans.redirectUrl ){
				fcom.waitAndRedirect( ans.redirectUrl );
			}
		});
	}
};