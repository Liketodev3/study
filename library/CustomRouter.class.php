<?php

class CustomRouter
{
    static function setRoute(&$controller, &$action, &$queryString)
    {
        if (defined('SYSTEM_FRONT') && SYSTEM_FRONT === true && !FatUtility::isAjaxCall()) {
            if (UrlHelper::isStaticContentProvider($controller, $action)) {
                return true;
            }
            $url_components = parse_url($_SERVER['REQUEST_URI']);
            $uri = Common::getUriFromPath($url_components['path']);
            $urlQueryString = '';
            $uriSegment = explode('/', $uri);
            if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0) && in_array(strtoupper($controller), LANG_CODES_ARR)) {
                $langCodes = array_flip(LANG_CODES_ARR);
                if (in_array(strtoupper($controller), LANG_CODES_ARR)) {
                    $langId = $langCodes[strtoupper($controller)];
                } else {
                    $langId = CommonHelper::getLangId();
                }
                define('SYSTEM_LANG_ID', $langId);
                $controller = ($action == 'index') ? 'Home' : $action;
                if (!array_key_exists(0, $queryString)) {
                    $action = 'index';
                } else {
                    $action = $queryString[0];
                    array_shift($queryString);
                }
                array_shift($uriSegment);
            }

            $uriSegmentCount = count($uriSegment);
            $urlWithoutParm = $uriSegment;

            if ($uriSegmentCount > 2) {
                $urlWithoutParm = array_slice($uriSegment, 0, 2);
                $urlQueryString = '/' . implode('/', $queryString);
                $replaceArray = array_fill(count($urlWithoutParm) - 1, count($queryString), 'urlparameter');
                $uriSegment = array_merge($urlWithoutParm, $replaceArray);
            }

            $new_url = urldecode(implode('/', $uriSegment));
            $url = urldecode($_SERVER['REQUEST_URI']);
            if (strpos($url, "?") !== false && strpos($url, "/?") === false) {
                $url = str_replace('?', '/?', $url);
            }
            $customUrl = rtrim(substr($url, strlen(CONF_WEBROOT_URL)), '/');
            $customUrl = explode('/?', $customUrl);

            /* [ Handled lang code in url */
            if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0)) {
                $langCustomUrl = explode('/', $customUrl[0]);
                if (isset($langCustomUrl[0]) && $langCustomUrl[0] != '') {
                    if (in_array(strtoupper($langCustomUrl[0]), LANG_CODES_ARR)) {
                        $customUrl[0] = ltrim(substr($customUrl[0], strlen(reset($langCustomUrl))), '/');
                    }
                }
            }
            /* ] */
            /* [ Check url rewritten by the system or system url with query parameter */

            $row = Null;
            if (!empty($customUrl[0])) {
                $row = UrlHelper::getOriginalUrlFromCustom($customUrl[0], $new_url);

                if (is_null($row)) {
                    $customUrlFromOrig = UrlHelper::getCustomUrlFromOrignal($customUrl[0], $urlWithoutParm, $urlQueryString);

                    if (!is_null($customUrlFromOrig)) {
                        $code = FatUtility::int($customUrlFromOrig['urlrewrite_http_resp_code']);
                        header("Location:" . rtrim(FatUtility::generateFullUrl(CONF_WEBROOT_URL), '/') . '/' . $customUrlFromOrig['urlrewrite_custom'], true, $code);
                        header("Connection: close");
                    }
                }
            }
            /* ] */


            $url = ((!empty($row['urlrewrite_original'])) ? $row['urlrewrite_original'] : '') . $urlQueryString;

            if (!$row && (!isset($customUrl[1]) || (isset($customUrl[1]) && strpos($customUrl[1], 'pagesize') === false))) {
                return;
            }
            if (!$row && isset($customUrl[1])) {
                $url = $customUrl[0];
            }
            $arr = explode('/', $url);
            $controller = array_shift($arr);
            $action = array_shift($arr);

            $queryString = $arr;

            if ($controller != '' && $action == '') {
                $action = 'index';
            }
            if ($controller == '' && $action == '') {
                $controller = 'Content';
                $action = 'error404';
            }
        }
    }
}
