<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if( $controllerName != 'GuestUser' && $controllerName != 'Error' && $controllerName != 'Teach' ){
	$_SESSION['referer_page_url'] = CommonHelper::getCurrUrl();
}
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="shortcut icon" href="<?php echo CommonHelper::generateUrl('Image','favicon', array($siteLangId)); ?>">
<link rel="apple-touch-icon" href="<?php echo CommonHelper::generateUrl('Image','appleTouchIcon', array($siteLangId)); ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo CONF_WEBROOT_URL; ?>images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo CONF_WEBROOT_URL; ?>images/apple-touch-icon-114x114.png">
<!--link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Open+Sans:400,600&display=swap" rel="stylesheet"-->
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="canonical" href="<?php echo $canonicalUrl;?>" />
<?php
echo $str = '<script type="text/javascript">
		var langLbl = ' . json_encode(
			CommonHelper::htmlEntitiesDecode($jsVariables)
		) . ';
		var CONF_AUTO_CLOSE_SYSTEM_MESSAGES = ' . FatApp::getConfig("CONF_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 0) . ';
		var CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = ' . FatApp::getConfig("CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 3) . ';
		var layoutDirection ="'.$layoutDirection.'";
		var currencySymbolLeft = "' . $currencySymbolLeft . '";
		var currencySymbolRight = "' . $currencySymbolRight . '";
		if( CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES <= 0  ){
			CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = 3;
		}
	</script>' . "\r\n";
