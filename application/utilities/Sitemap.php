<?php
class Sitemap
{
    public static function getUrls($langId)
    {
        $sitemapUrls = array();
        // teachers profile
        $srch = new UserSearch();
        $srch->addFld('DISTINCT user_url_name');
		$srch->setTeacherDefinedCriteria(false,false);
        $tlangSrch = $srch->getMyTeachLangQry(true, $langId);
		$srch->joinTable("(" . $tlangSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utl_us_user_id', 'utls');
		$srch->joinUserSpokenLanguages($langId);
		$srch->joinUserCountry($langId);
		$srch->joinUserAvailibility();
        $rs = $srch->getResultSet();
		$teachersList = FatApp::getDb()->fetchAll($rs);
        
        foreach ($teachersList as $key => $val) {
            array_push($sitemapUrls, CommonHelper::generateFullUrl('Teachers', 'profile', array($val['user_url_name']), CONF_WEBROOT_FRONT_URL));
        }
        /* ]*/

        /* Group Classes [ */
        $grpClsSrch = new TeacherGroupClassesSearch();
        $grpClsSrch->joinTeacher();
        $grpClsSrch->setTeacherDefinedCriteria(false, false);
        $grpClsSrch->addFld('DISTINCT grpcls_id');
        $grpClsSrch->doNotCalculateRecords();
        $grpClsSrch->doNotLimitRecords();
        $rs = $grpClsSrch->getResultSet();
        $grpClsList = FatApp::getDb()->fetchAll($rs);
        foreach ($grpClsList as $key => $val) {
            array_push($sitemapUrls, CommonHelper::generateFullUrl('GroupClasses', 'view', array($val['grpcls_id']), CONF_WEBROOT_FRONT_URL));
        }
        /* ]*/

        /* CMS Pages [ */
        $srch = new NavigationLinkSearch($langId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->joinNavigation();
        $srch->addCondition('nlink_deleted', '=', applicationConstants::NO);
        $srch->addCondition('nav_active', '=', applicationConstants::ACTIVE);
        $srch->addMultipleFields(array('nav_id', 'nlink_type', 'nlink_cpage_id', 'nlink_url'));
        $srch->addOrder('nlink_display_order', 'ASC');
        $srch->addGroupBy('nlink_cpage_id');
        $srch->addGroupBy('nlink_url');
        // echo $srch->getQuery();die;
        $rs = $srch->getResultSet();
        $linksList = FatApp::getDb()->fetchAll($rs);
        // CommonHelper::printArray($linksList);die;
        
        foreach ($linksList as $key => $link) {
            if ($link['nlink_type'] == NavigationLinks::NAVLINK_TYPE_CMS && $link['nlink_cpage_id']) {
                array_push($sitemapUrls, CommonHelper::generateFullUrl('Cms', 'view', array($link['nlink_cpage_id']), CONF_WEBROOT_FRONT_URL));
            }elseif ($link['nlink_type']==NavigationLinks::NAVLINK_TYPE_EXTERNAL_PAGE) {
                $url = str_replace('{SITEROOT}', CONF_WEBROOT_FRONT_URL, $link['nlink_url']) ;
                $url = str_replace('{siteroot}', CONF_WEBROOT_FRONT_URL, $url) ;
                $url = CommonHelper::processURLString($url);
                array_push($sitemapUrls, CommonHelper::getUrlScheme().$url);
            }
        }
        return $sitemapUrls;
    }
}