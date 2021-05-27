<?php

class UrlHelper
{

    public static function isStaticContentProvider(string $controller, string $action): bool
    {
        if (in_array($controller, array_merge(CONF_STATIC_FILE_CONTROLLERS, ['js-css', 'image']))) {
            return true;
        }
        $arr = [
            'teacher-lessons-plan' => [
                'lesson-plan-file',
                'lesson-plan-image',
            ],
            'teacher-courses' => [
                'teacher-course-image'
            ],
            'my-app' => [
                'pwa-manifest'
            ]
        ];
        return array_key_exists($controller, $arr) && in_array($action, $arr[$controller]);
    }

    public static function getOriginalUrlFromCustom($url, $overideUrlParamter = '')
    {
        $srch = new UrlRewriteSearch();
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields(['urlrewrite_custom', 'urlrewrite_original']);
        $srch->setPageSize(1);
        $cond = $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', '=', $url);
        if (!empty($overideUrlParamter)) {
            $cond->attachCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', '=', $overideUrlParamter, 'OR');
        }
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        return $row;
    }

    public static function getCustomUrlFromOrignal($url, $overideUrlParamter = [], $urlQueryString = '')
    {
        $langId = CommonHelper::getLangId();
        $srch = new UrlRewriteSearch();
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields(['urlrewrite_custom', 'urlrewrite_original', 'urlrewrite_http_resp_code']);
        $srch->setPageSize(1);
        $cond = $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'original', '=', $url);
        if (!empty($urlwithoutparameter)) {
            $cond->attachCondition(UrlRewrite::DB_TBL_PREFIX . 'original', '=', implode('/', $overideUrlParamter), 'OR');
        }
        if (defined("SYSTEM_LANG_ID")) {
            $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'lang_id', '=', SYSTEM_LANG_ID);
        } elseif (!is_null($langId)) {
            $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'lang_id', '=', $langId);
        } else {
            $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'lang_id', '=', FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1));
        }
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        if (!is_null($row)) {
            if (strpos($row['urlrewrite_custom'], 'urlparameter') !== false) {
                $row['urlrewrite_custom'] = implode('/', array_slice(explode('/', $row['urlrewrite_custom']), 0, 2)) . $urlQueryString;
            }

            return $row;
        }
    }
}
