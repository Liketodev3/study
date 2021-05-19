<?php

class UrlRewrite extends MyAppModel
{

    const DB_TBL = 'tbl_url_rewrites';
    const DB_TBL_PREFIX = 'urlrewrite_';

    const HTTP_CODE_REDIRECT_PERMANENTLY = 301;
    const HTTP_CODE_REDIRECT_TEMPERARY = 302;
    const HTTP_CODE_PAGE_NOT_FOUND = 404;
    const HTTP_CODE_NOT_AVAILABLE = 410;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'ur');
        return $srch;
    }

    public static function getHttpCodeArr(int $langId)
    {
        return [
            static::HTTP_CODE_REDIRECT_PERMANENTLY => Label::getLabel('LBL_301_Redirect_permanently', $langId),
            static::HTTP_CODE_REDIRECT_TEMPERARY => Label::getLabel('LBL_302_Redirect_temprary', $langId),
            static::HTTP_CODE_PAGE_NOT_FOUND => Label::getLabel('LBL_404_Page_Not_Found', $langId),
            static::HTTP_CODE_NOT_AVAILABLE => Label::getLabel('LBL_410_No_Longer_Available', $langId),
        ];
    }

    public static function getDataByCustomUrl($customUrl, $excludeThisOriginalUrl = false)
    {
        $urlSrch = static::getSearchObject();
        $urlSrch->doNotCalculateRecords();
        $urlSrch->setPageSize(1);
        $urlSrch->addMultipleFields(['urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom']);
        $urlSrch->addCondition('urlrewrite_custom', '=', $customUrl);
        if ($excludeThisOriginalUrl) {
            $urlSrch->addCondition('urlrewrite_original', '!=', $excludeThisOriginalUrl);
        }
        $urlRow = FatApp::getDb()->fetch($urlSrch->getResultSet());
        if ($urlRow == false) {
            return [];
        }
        return $urlRow;
    }

    public static function getDataByOriginalUrl($originalUrl, $excludeThisCustomUrl = false)
    {
        $urlSrch = static::getSearchObject();
        $urlSrch->doNotCalculateRecords();
        $urlSrch->setPageSize(1);
        $urlSrch->addMultipleFields(['urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom']);
        $urlSrch->addCondition('urlrewrite_original', '=', $originalUrl);
        if ($excludeThisCustomUrl) {
            $urlSrch->addCondition('urlrewrite_custom', '!=', $excludeThisCustomUrl);
        }
        $rs = $urlSrch->getResultSet();
        $urlRow = FatApp::getDb()->fetch($rs);
        if ($urlRow == false) {
            return [];
        }
        return $urlRow;
    }

    public static function getValidSeoUrl($urlKeyword, $excludeThisOriginalUrl = false)
    {
        $customUrl = CommonHelper::seoUrl($urlKeyword);
        $res = static::getDataByCustomUrl($customUrl, $excludeThisOriginalUrl);
        if (empty($res)) {
            return $customUrl;
        }
        $i = 1;
        $slug = $customUrl;
        while (static::getDataByCustomUrl($slug, $excludeThisOriginalUrl)) {
            $slug = $customUrl . "-" . $i++;
        }
        return $slug;
    }

}
