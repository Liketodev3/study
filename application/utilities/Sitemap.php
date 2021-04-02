<?php

class Sitemap
{

    public static function getUrls($langId)
    {
        $sitemapUrls = array();
        // teachers profile
        $srch = new UserSearch();
        $srch->addFld('DISTINCT user_url_name, CONCAT(user_first_name, " ", user_last_name) as user_full_name');
        $srch->setTeacherDefinedCriteria(false, false);
        $tlangSrch = $srch->getMyTeachLangQry(true, $langId);
        $srch->joinTable("(" . $tlangSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utl_us_user_id', 'utls');
        $srch->joinUserSpokenLanguages($langId);
        $srch->joinUserCountry($langId);
        $srch->joinUserAvailibility();
        $rs = $srch->getResultSet();
        $teachersList = FatApp::getDb()->fetchAll($rs);
        $urls = array();
        foreach ($teachersList as $key => $val) {
            array_push($urls, array('url' => CommonHelper::generateFullUrl('Teachers', 'profile', array($val['user_url_name']), CONF_WEBROOT_FRONT_URL), 'value' => $val['user_full_name'], 'frequency' => 'weekly'));
        }
        $sitemapUrls = array_merge($sitemapUrls, array(Label::getLabel('LBL_Teachers') => $urls));
        /* ] */
        /* Group Classes [ */
        $grpClsSrch = new TeacherGroupClassesSearch();
        $grpClsSrch->joinTeacher();
        $grpClsSrch->setTeacherDefinedCriteria(false, false);
        $grpClsSrch->addFld('DISTINCT grpcls_id,grpcls_title');
        $grpClsSrch->doNotCalculateRecords();
        $grpClsSrch->doNotLimitRecords();
        $rs = $grpClsSrch->getResultSet();
        $grpClsList = FatApp::getDb()->fetchAll($rs);
        $urls = array();
        foreach ($grpClsList as $key => $val) {
            array_push($urls, array('url' => CommonHelper::generateFullUrl('GroupClasses', 'view', array($val['grpcls_id']), CONF_WEBROOT_FRONT_URL), 'value' => $val['grpcls_title'], 'frequency' => 'weekly'));
        }
        $sitemapUrls = array_merge($sitemapUrls, array(Label::getLabel('LBL_Group_Classes') => $urls));
        /* ] */
        /* CMS Pages [ */
        $srch = new NavigationLinkSearch($langId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->joinNavigation();
        $srch->addCondition('nlink_deleted', '=', applicationConstants::NO);
        $srch->addCondition('nav_active', '=', applicationConstants::ACTIVE);
        $srch->addMultipleFields(array('nav_id', 'nlink_type', 'nlink_cpage_id', 'nlink_url', 'nlink_identifier'));
        $srch->addOrder('nlink_display_order', 'ASC');
        $srch->addGroupBy('nlink_cpage_id');
        $srch->addGroupBy('nlink_url');
        $rs = $srch->getResultSet();
        $linksList = FatApp::getDb()->fetchAll($rs);
        $urls = array();
        foreach ($linksList as $key => $link) {
            if ($link['nlink_type'] == NavigationLinks::NAVLINK_TYPE_CMS && $link['nlink_cpage_id']) {
                array_push($urls, array('url' => CommonHelper::generateFullUrl('Cms', 'view', array($link['nlink_cpage_id']), CONF_WEBROOT_FRONT_URL), 'value' => $link['nlink_identifier'], 'frequency' => 'monthly'));
            } elseif ($link['nlink_type'] == NavigationLinks::NAVLINK_TYPE_EXTERNAL_PAGE) {
                $url = str_replace('{SITEROOT}', CONF_WEBROOT_FRONT_URL, $link['nlink_url']);
                $url = str_replace('{siteroot}', CONF_WEBROOT_FRONT_URL, $url);
                $url = CommonHelper::processURLString($url);
                array_push($urls, array('url' => CommonHelper::getUrlScheme() . $url, 'value' => $link['nlink_identifier'], 'frequency' => 'monthly'));
            }
        }
        $sitemapUrls = array_merge($sitemapUrls, array(Label::getLabel('LBL_CMS_Pages') => $urls));
        return $sitemapUrls;
    }

}
