<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<!doctype html>
<html class="<?php echo (FatApp::getConfig('conf_auto_restore_on', FatUtility::VAR_INT, 1) && CommonHelper::demoUrl()) ? 'sticky-demo-header': '' ?>">
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="author" content="">
	<?php
	if( isset($includeEditor) && $includeEditor == true ){
		$extendEditorJs	= 'true';
	}else{
		$extendEditorJs	= 'false';
	}

	echo $str = '<script type="text/javascript">
		var SITE_ROOT_URL = "' . CONF_WEBROOT_URL . '" ;
		var SITE_ROOT_DASHBOARD_URL = "' . CONF_WEBROOT_DASHBOARD . '" ;
		var SITE_ROOT_FRONT_URL = "' . CONF_WEBROOT_FRONTEND . '" ;

		var langLbl = ' . json_encode(
			CommonHelper::htmlEntitiesDecode($jsVariables)
		) . ';
		var CONF_AUTO_CLOSE_SYSTEM_MESSAGES = ' . FatApp::getConfig("CONF_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 0) . ';
		var layoutDirection ="'.$layoutDirection.'";
		var CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = ' . FatApp::getConfig("CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 3) . ';
		var extendEditorJs = ' . $extendEditorJs . ';
		if( CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES <= 0  ){
			CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = 3;
		}
		</script>' . "\r\n";;


	 if( AttachedFile::getAttachment( AttachedFile::FILETYPE_FAVICON, 0, 0, $adminLangId ) ){ ?>
	<link rel="shortcut icon" href="<?php echo CommonHelper::generateUrl('image', 'favicon', array($adminLangId), CONF_WEBROOT_FRONT_URL) ?>">
	<?php } ?>

	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,500i,700,700i,900,900i" rel="stylesheet">
