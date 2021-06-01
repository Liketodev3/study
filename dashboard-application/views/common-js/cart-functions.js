var cart = {
	add: function(teacherId, languageId, lessonDuration, lessonQty) {
		teacherId = parseInt(teacherId);
		lessonQty = parseInt(lessonQty);
		lessonDuration = parseInt(lessonDuration);
		languageId = parseInt(languageId);
		$.loader.show();
		var data = '&teacherId=' + teacherId + '&lessonQty=' + lessonQty + '&languageId=' + languageId + '&lessonDuration=' + lessonDuration;
		fcom.ajax( fcom.makeUrl('Cart','add', [], confFrontEndUrl), data ,function(res){
			$.loader.hide();
			if(res.status == 1){
				if( res.redirectUrl ){
					window.location.href = res.redirectUrl;
					return;
				}
			}
			$.mbsmessage(res.msg,true, 'alert alert--danger');

			
		},{fOutMode:'json'});
	}
};