$(document).ready(function(){
	//changeEmailForm();
});

(function() {
	var runningAjaxReq = false;

	checkRunningAjax = function(){
		if( runningAjaxReq == true ){
			return;
		}
		runningAjaxReq = true;
	};

	// changeEmailForm = function(){
	// 	$(dv).html(fcom.getLoader());
	// 	fcom.ajax(fcom.makeUrl('GuestUser', 'changeEmailForm'), '', function(t) {
	// 		$(dv).html(t);
	// 	});
  //   };

	updateEmail = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.ajax(fcom.makeUrl('GuestUser', 'updateEmail'), data, function(ans) {
			$.systemMessage.close();
			if (ans.status != 1) {

				$(document).trigger('close.mbsmessage');
				
				$.systemMessage(ans.msg , 'alert alert--danger');
				if( ans.redirectUrl ){
					setTimeout(function(){ window.location.href = ans.redirectUrl }, 3000);
				}

				return;
			}
			$.systemMessage(ans.msg , 'alert alert--success');
			frm.reset();
		},{fOutMode:'json'});
    };


})();
