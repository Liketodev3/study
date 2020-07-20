<?php
/**
 *
 * General configurations
 */

define('CONF_DEVELOPMENT_MODE',  (strpos(($_SERVER['SERVER_NAME']),'localhost') !== false ) ? true : false );
define('CONF_LIB_HALDLE_ERROR_IN_PRODUCTION', true);
define('CONF_URL_REWRITING_ENABLED', true);
define('PASSWORD_SALT', 'ewgfhgfhgfhgkuyajflfdsaf');
define('CONF_INSTALLATION_PATH', dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR );
define('CONF_UPLOADS_PATH', CONF_INSTALLATION_PATH . 'user-uploads' . DIRECTORY_SEPARATOR );
define('CONF_FRONT_END_APPLICATION_DIR', 'application/' );
define('GIFTCARD_EXPIRATION_DAYS',"+ 30 days");
define('GIFTCARD_ORDER_LIMIT',"5");
define('ENCRYPTION_SALT',"YOCOACH");

if(file_exists(CONF_INSTALLATION_PATH.'conf/'.$_SERVER['SERVER_NAME'].'.php')){
	require_once(CONF_INSTALLATION_PATH.'conf/'.$_SERVER['SERVER_NAME'].'.php');
}else{
	die('Domain specific settings file missing');
}


if ( strpos($_SERVER ['SERVER_NAME'], '.4livedemo.com') !== false) {
    define ( 'CONF_CORE_LIB_PATH', '/home/fatlib/v2.0/' );
} else {
	define ( 'CONF_CORE_LIB_PATH', CONF_INSTALLATION_PATH . 'library/core/' );
}

define('CONF_USE_FAT_CACHE', (strpos(($_SERVER['SERVER_NAME']),'localhost') !== false ) ? false : true);
define('CONF_DEF_CACHE_TIME', 2592000); // in seconds (2592000 = 30 days)
define('CONF_IMG_CACHE_TIME', 14400); // in seconds (1400 = 4 hours)
define('CONF_HOME_PAGE_CACHE_TIME', 28800); // in seconds (28800 = 8 hours)
