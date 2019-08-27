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
	var weyakyak_timezone  = getCookie('weyakyak_timezone');
	var tz = jstz.determine();
	var timezone = tz.name();
	
	if(	( weyakyak_timezone == null || weyakyak_timezone =='' || weyakyak_timezone == undefined ) || weyakyak_timezone != timezone ) {
		setCookie('weyakyak_timezone', timezone);
	}	
	  
});