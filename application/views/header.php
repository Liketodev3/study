<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
	header('X-Frame-Options: SAMEORIGIN');
	header('Strict-Transport-Security: max-age=10886400' );
	header('X-XSS-Protection: 1; mode=block' );
	header('X-Content-Type-Options: nosniff' );
	header('Content-Security-Policy: policy-definition' );
	header('Referrer-Policy: no-referrer-when-downgrade' );
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache"); 
	header("Cache-Control: max-age=86400");
	header('Cache-Control: public' ); 
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
	'layoutDirection' =>  CommonHelper::getLayoutDirection()
);

$this->includeTemplate( '_partial/header/commonHead1.php', $commonHead1DataArr );
/* ] */

/** Remove meta from teacher profile page **/
$__controller = FatApp::getController();
$__action = FatApp::getAction();
if ( $__controller =='TeachersController' && $__action =='view'  ){
	echo '';
} else {
	echo $this->writeMetaTags();
}
/***********/
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