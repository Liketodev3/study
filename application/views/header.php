<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
/** Filter Session Destroy **/
$__controller = FatApp::getController();
$__method = FatApp::getAction();

if ($__controller != 'TeachersController' && isset($_SESSION['search_filters'])) {
	//unset($_SESSION['search_filters']);
}
/***********/
/* commonHead1[ */
$commonHead1DataArr = array(
	'siteLangId'	=>	$siteLangId,
	'jsVariables'	=>	$jsVariables,
	'controllerName'	=>	$controllerName,
	'canonicalUrl'	=>	isset($canonicalUrl) ? $canonicalUrl : '',
	'currencySymbolLeft' => $currencySymbolLeft,
	'currencySymbolRight' => $currencySymbolRight,
	'layoutDirection' =>  CommonHelper::getLayoutDirection(),
	'cookieConsent' =>  $cookieConsent
);

$this->includeTemplate('header/commonHead1.php', $commonHead1DataArr, false);
/* ] */

echo $this->writeMetaTags();
/***********/
echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE);


/* commonHead2[ */
$commonHead2DataArr = array(
	'siteLangId'	=>	$siteLangId,
	'controllerName'	=>	$controllerName,
);

if (isset($includeEditor) && $includeEditor == true) {
	$commonHead2DataArr['includeEditor']	= $includeEditor;
}

$htmlBodyClassesArr = array();
switch ($controllerName) {
	case 'Blog':
		array_push($htmlBodyClassesArr, 'is--blog');
		break;

	case 'Home':
		array_push($htmlBodyClassesArr, 'is-landing');
		break;

	case 'Teach':
		array_push($htmlBodyClassesArr, 'is-landing');
		break;
	case 'TeacherRequest':
		if($__method=='index'){
			array_push($htmlBodyClassesArr, 'is-landing');
		}else{
			array_push($htmlBodyClassesArr, 'is-landing is-registration');
		}
		
		break;
}
$htmlBodyClassesString = implode(" ", $htmlBodyClassesArr);
$commonHead2DataArr['htmlBodyClassesString'] = $htmlBodyClassesString;

$this->includeTemplate('header/commonHead2.php', $commonHead2DataArr);
/* ] */

if (!isset($exculdeMainHeaderDiv)) {
	$this->includeTemplate('header/top.php', array('siteLangId' => $siteLangId, 'languages' => $languages), false);
}
