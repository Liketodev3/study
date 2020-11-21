<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="group group--social">

	<a href="javascript:void(0)" onclick="dofacebookInLoginForBuyerpopup()">
		<img src="<?php echo CONF_WEBROOT_URL; ?>images/facebook_login_btn.png">
	</a>

	<a href="<?php echo CommonHelper::generateUrl('GuestUser', 'socialMediaLogin',array('google', $userType)); ?>">
        <img src="<?php echo CONF_WEBROOT_URL; ?>images/google_login_btn.png">
	</a>

</div>
<span class="-gap"></span>
<script>
/*Facebook Login API JS SDK*/

	function dofacebookInLoginForBuyerpopup()
	{
		FB.getLoginStatus(function(response) {
			if (response.status === 'connected') {
				//user is authorized
				getUserData();
			} else {
				//user is not authorized
			}
		});

		FB.login(function(response) {
			if (response.authResponse) {
				//user just authorized your app
					getUserData();
			}
		}, {scope: 'email,public_profile', return_scopes: true});
	}

	function getUserData()
	{
		FB.api('/me?fields=id,name,email, first_name, last_name', function(response) {
			response['type'] = <?php echo $userType; ?>;
			fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'loginFacebook'), response, function(t) {
				location.href = t.url;
			});
		}, {scope: 'public_profile,email'});
	}

	window.fbAsyncInit = function() {
		//SDK loaded, initialize it
		FB.init({
			appId      : '<?php echo FatApp::getConfig('CONF_FACEBOOK_APP_ID',FatUtility::VAR_STRING,'') ?>',
			xfbml      : true,
			version    : 'v2.2'
		});
	};

	//load the JavaScript SDK
	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	/*Facebook Login API JS SDK*/
</script>
