<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="group group--social">
	<a class="btn btn--social-fb btn--block" <a href="javascript:void(0)" onclick="dofacebookInLoginForBuyerpopup()">
		<span class="svg-icon">
		  <svg xmlns="http://www.w3.org/2000/svg" width="7.5" height="15" viewBox="0 0 7.5 15">
			  <path fill="#fff" d="M400,3550.52a1.163,1.163,0,0,1,.215-0.79,1.386,1.386,0,0,1,.957-0.23H402.5V3547h-2.187a3.715,3.715,0,0,0-2.735.84,3.417,3.417,0,0,0-.82,2.48V3552H395v2.5h1.758v7.5H400v-7.5h2.188l0.312-2.5H400v-1.48Z" transform="translate(-395 -3547)"/>
		  </svg>
	  </span> <?php echo Label::getLabel('LBL_Facebook'); ?>
	</a>
	
	<a class="btn btn--social-gp btn--block" href="<?php echo CommonHelper::generateUrl('GuestUser', 'socialMediaLogin',array('google')); ?>">
		<span class="svg-icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve" width="18px" height="18px">
            <g>
            <g>
            <path fill="#f14336" d="M286.875,229.5v63.75h150.45c-15.3,89.25-86.7,153-175.95,153c-104.55,0-191.25-86.7-191.25-191.25
            s86.7-191.25,191.25-191.25c53.55,0,99.45,22.95,132.6,58.65l45.9-45.9c-45.9-45.9-107.1-76.5-178.5-76.5
            c-140.25,0-255,114.75-255,255s114.75,255,255,255s242.25-114.75,242.25-255v-25.5H286.875z"></path>
            </g>
            </g>
            </svg>
	  </span> <?php echo Label::getLabel('LBL_Google'); ?>
	</a>
	<span class="-gap"></span>
	<p class="-align-center"><?php echo Label::getLabel('LBL_Or'); ?></p>
</div>
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
			response['type'] = <?php echo User::USER_TYPE_LEANER; ?>;
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
