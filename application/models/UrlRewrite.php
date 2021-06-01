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
    }

    public static function getHttpCodeArr(int $langId): array
    {
        return [
            static::HTTP_CODE_REDIRECT_PERMANENTLY => Label::getLabel('LBL_301_Redirect_permanently', $langId),
            static::HTTP_CODE_REDIRECT_TEMPERARY => Label::getLabel('LBL_302_Redirect_temprary', $langId),
            static::HTTP_CODE_PAGE_NOT_FOUND => Label::getLabel('LBL_404_Page_Not_Found', $langId),
            static::HTTP_CODE_NOT_AVAILABLE => Label::getLabel('LBL_410_No_Longer_Available', $langId),
        ];
    }

    public static function getDataByOriginalUrl(string $originalUrl, bool $excludeThisCustomUrl = false): array
    {
        $urlSrch = new UrlRewriteSearch();
        $urlSrch->doNotCalculateRecords();
        $urlSrch->setPageSize(1);
        $urlSrch->addMultipleFields(['urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom']);
        $urlSrch->addCondition('urlrewrite_original', '=', $originalUrl);
        $excludeThisCustomUrl && $urlSrch->addCondition('urlrewrite_custom', '!=', $excludeThisCustomUrl);
        return (array)FatApp::getDb()->fetch($urlSrch->getResultSet());
    }

}
