(function() {
	resetpwd = function(frm, v) {
		v.validate();
		if (!v.isValid()) return;
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'resetPasswordSetup'), fcom.frmData(frm), function(t) {
			setTimeout(function(){
				location.href = fcom.makeUrl('GuestUser', 'loginForm');
			}, 3000);
		});
	};
})();