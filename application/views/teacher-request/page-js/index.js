
$("document").ready(function(){
	$("#show-password,#hide-password").click(function(){
	var fld = $('input[name="user_password"]');
	if($(fld).attr('type')=='password'){
		$(fld).attr('type','text');
		$('#hide-password').show();
		$('#show-password').hide();
	}else{
		$(fld).attr('type','password');
		$('#show-password').show();
		$('#hide-password').hide();
	}
	})
});
