
$("document").ready(function(){
	$("#SHOW-password").click(function(){
	var fld = $('input[name="user_password"]');
	if($(fld).attr('type')=='password'){
		$(fld).attr('type','text');
	}else{
		$(fld).attr('type','password');
	}
	})
});
