<?php

class CustomRouter
{

    static function setRoute(&$controller, &$action, &$queryString)
    {
        if (defined('SYSTEM_FRONT') && SYSTEM_FRONT === true && !FatUtility::isAjaxCall()) {
            if (UrlHelper::isStaticContentProvider($controller, $action)) {
                return true;
            }
            $url = $_SERVER['REQUEST_URI'];
            $url_components = parse_url($url);
            $path = $url_components['path'];
            $uri = Common::getUriFromPath($path);
            $last_param = '';
            $uri_segment = explode('/', $uri);
            if (count($uri_segment) > 1) {
                $last_param = '/' . array_pop($uri_segment);
                array_push($uri_segment, 'urlparameter');
            }
            $new_url = implode('/', $uri_segment);
            $srch = UrlRewrite::getSearchObject();
            $srch->doNotCalculateRecords();
            $srch->setPagesize(1);
            $cond = $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', 'LIKE', $uri);
            if (!empty($new_url)) {
                $cond->attachCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', 'LIKE', $new_url, 'OR');
            }
            $rs = $srch->getResultSet();
            if (!$row = FatApp::getDb()->fetch($rs)) {
                return;
            }
            $url = $row['urlrewrite_original'] . $last_param;
            $arr = explode('/', $url);
            $controller = (isset($arr[0])) ? $arr[0] : '';
            array_shift($arr);
            $action = (isset($arr[0])) ? $arr[0] : '';
            array_shift($arr);
            $queryString = $arr;
            if ($controller != '' && $action == '') {
                $action = 'index';
            }
            if ($controller == '') {
                $controller = 'Content';
            }
            if ($action == '') {
                $action = 'error404';
            }
        }
    }

}
