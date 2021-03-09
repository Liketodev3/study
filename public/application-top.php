<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}

ini_set('display_errors', CONF_DEVELOPMENT_MODE);

error_reporting(CONF_DEVELOPMENT_MODE ? E_ALL : E_ALL & ~E_NOTICE & ~E_WARNING);

require_once CONF_INSTALLATION_PATH . 'library/autoloader.php';
require_once CONF_INSTALLATION_PATH . 'vendor/autoload.php';
AttachedFile::registerS3ClientStream();

/* We must set it before initiating db connection. So that connection timezone is in sync with php */

$timeZone = FatApp::getConfig('CONF_TIMEZONE', FatUtility::VAR_STRING, 'America/New_York');
date_default_timezone_set($timeZone);

/* setting Time Zone of Mysql Server with same as of PHP[ */
$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
FatApp::getDb()->query("SET time_zone = '" . $offset . "'");
/* ] */

FatApp::getDb()->query("SET NAMES utf8mb4");

CommonHelper::setSeesionCookieParams();

session_start();

/* --- Redirect SSL --- */
$protocol = (FatApp::getConfig('CONF_USE_SSL', FatUtility::VAR_INT, 0) == 1) ? 'https://' : 'http://';
/* AWS */
//if ((!isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')  && (FatApp::getConfig('CONF_USE_SSL')==1)) {

if ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')  && (FatApp::getConfig('CONF_USE_SSL') == 1)) {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    FatApp::redirectUser($redirect);
}
/* --- Redirect SSL --- */
$_SESSION['WYSIWYGFileManagerRequirements'] = CONF_INSTALLATION_PATH . 'public/WYSIWYGFileManagerRequirements.php';

define('SYSTEM_INIT', true);
