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
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'updateEmail'), data, function(t) {
			location.reload();
		});
    };


})();
