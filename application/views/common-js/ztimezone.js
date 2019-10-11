//== function to set and get cookies

function setCookie(key, value) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
	document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}
function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}

//=== function to get timezone /== use functions from jstz.min.js file

$(document).ready(function() {
	var user_timezone  = getCookie('user_timezone');
	var tz = jstz.determine();
	var timezone = tz.name();
	
	if(	( user_timezone == null || user_timezone =='' || user_timezone == undefined ) || user_timezone != timezone ) {
		setCookie('user_timezone', timezone);
	}	
	  
});