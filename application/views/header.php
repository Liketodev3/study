<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 

/** Filter Session Destroy **/
$__controller = FatApp::getController();
if ( $__controller !='TeachersController' && isset( $_SESSION['search_filters'] ) ) {
	unset($_SESSION['search_filters']);
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
);

$this->includeTemplate( '_partial/header/commonHead1.php', $commonHead1DataArr );
/* ] */

echo $this->writeMetaTags();
echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE);


/* commonHead2[ */
$commonHead2DataArr = array(
	'siteLangId'	=>	$siteLangId,
	'controllerName'	=>	$controllerName,
);

if( isset($includeEditor) && $includeEditor == true ){
	$commonHead2DataArr['includeEditor']	= $includeEditor;
}

$htmlBodyClassesArr = array();
switch( $controllerName ){
	case 'Blog':
		array_push( $htmlBodyClassesArr, 'is--blog' );
	break;
	
	case 'Home':
		array_push( $htmlBodyClassesArr, 'is-landing' );
	break;

	case 'Teach':
		array_push( $htmlBodyClassesArr, 'is-landing' );
	break;
}
$htmlBodyClassesString = implode( " ", $htmlBodyClassesArr );
$commonHead2DataArr['htmlBodyClassesString'] = $htmlBodyClassesString;

$this->includeTemplate( '_partial/header/commonHead2.php', $commonHead2DataArr );
/* ] */

if( !isset($exculdeMainHeaderDiv) ){
	$this->includeTemplate('_partial/header/topHeader.php',array('siteLangId'=>$siteLangId),false);
}