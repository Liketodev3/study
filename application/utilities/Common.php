<?php

class Common
{

    public static function homePageSlides($template)
    {
        
    }

    public static function getHomePageBanners($template)
    {
        
    }

    public static function languagesWithTeachersCount($template)
    {
        $template->set("allLanguages", TeachingLanguage::getAllLangsWithUserCount(CommonHelper::getLangId()));
    }

    public static function topRatedTeachers($template)
    {
        $userObj = new UserSearch();
        $topRatedTeachers = $userObj->getTopRatedTeachers();
        if ($topRatedTeachers) {
            foreach ($topRatedTeachers as $k => $topRatedTeacher) {
                if (empty($topRatedTeacher['user_id'])) {
                    unset($topRatedTeachers[$k]);
                }
            }
        }
        $template->set("topRatedTeachers", $topRatedTeachers);
    }

    public static function homePageHowItWorks($template)
    {
        $db = FatApp::getDb();
        $bannerSrch = Banner::getBannerLocationSrchObj(true, CommonHelper::getLangId());
        $rs = $bannerSrch->getResultSet();
        $bannerLocation = $db->fetchAll($rs, 'blocation_key');
        $banners = $bannerLocation;
        foreach ($bannerLocation as $val) {
            $srch = new BannerSearch(CommonHelper::getLangId(), true);
            $srch->doNotCalculateRecords();
            $srch->joinAttachedFile();
            $srch->addCondition('banner_blocation_id', '=', $val['blocation_id']);
            $rs = $srch->getResultSet();
            $bannerListing = $db->fetchAll($rs, 'banner_id');
            $banners[$val['blocation_key']]['banners'] = $bannerListing;
        }
        $template->set("banners", $banners);
        $template->set('siteLangId', CommonHelper::getLangId());
    }

    public static function homePageSlidesAboveFooter($template)
    {
        $srch = Testimonial::getSearchObject(CommonHelper::getLangId(), true);
        $srch->addMultipleFields(['t.*', 't_l.testimonial_title', 't_l.testimonial_text']);
        $srch->addCondition('testimoniallang_testimonial_id', 'is not', 'mysql_func_null', 'and', true);
        $srch->addOrder('testimonial_added_on', 'desc');
        $records = FatApp::getDb()->fetchAll($srch->getResultSet());
        $template->set("testimonials", $records);
    }

    public static function headerLanguageArea($template)
    {
        $template->set('siteLangId', CommonHelper::getLangId());
        $template->set('languages', Language::getAllNames(false));
    }

    public static function headerUserLoginArea($template)
    {
        $template->set('userName', UserAuthentication::getLoggedUserAttribute('user_first_name', true));
        $controllerName = FatApp::getController();
        $arr = explode('-', FatUtility::camel2dashed($controllerName));
        array_pop($arr);
        $urlController = implode('-', $arr);
        $controllerName = ucfirst(FatUtility::dashed2Camel($urlController));
        $action = FatApp::getAction();
        $template->set('controllerName', $controllerName);
        $template->set('action', $action);
    }

    public static function languageCurrencySection($template)
    {
        $template->set('siteLangId', CommonHelper::getLangId());
        $template->set('languages', Language::getAllNames(false));
        $template->set('siteCurrencyId', CommonHelper::getCurrencyId());
        $template->set('currencies', Currency::getCurrencyAssoc(CommonHelper::getLangId()));
    }

    public static function footerSocialMedia($template)
    {
        $siteLangId = CommonHelper::getLangId();
        $srch = SocialPlatform::getSearchObject($siteLangId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('splatform_user_id', '=', 0);
        $rows = FatApp::getDb()->fetchAll($srch->getResultSet());
        $template->set('rows', $rows);
        $template->set('siteLangId', $siteLangId);
    }

    public static function learnerSocialMediaSignUp($template)
    {
        
    }

    public static function teacherLeftFilters($template)
    {
        $frmFilters = new Form('teacherFilters');
        $filterSortBy = [
            'popularity_desc' => Label::getLabel('LBL_By_Popularity'),
            'price_asc' => Label::getLabel('LBL_By_Price_Low_to_High'),
            'price_desc' => Label::getLabel('LBL_By_Price_High_to_Low'),
        ];
        $frmFilters->addSelectBox('', 'filterSortBy', $filterSortBy, '', [], Label::getLabel('LBL_Sort_by'));
        $teacherSrchObj = new UserSearch();
        $teacherSrchObj->setTeacherDefinedCriteria(true);
        $teacherSrchObj->doNotLimitRecords();

        /* preferences/skills[ */
        $prefSrch = clone $teacherSrchObj;
        $prefSrch->joinTable(Preference::DB_TBL_USER_PREF, 'INNER JOIN', 'u.user_id = utp.utpref_user_id', 'utp');
        $prefSrch->joinTable(Preference::DB_TBL, 'INNER JOIN', 'utp.utpref_preference_id = p.preference_id', 'p');
        $prefSrch->joinTable(Preference::DB_TBL_LANG, 'LEFT OUTER JOIN', 'pl.preferencelang_preference_id = p.preference_id AND pl.preferencelang_lang_id = ' . CommonHelper::getLangId(), 'pl');
        $prefSrch->addGroupBy('preference_id');
        $prefSrch->addOrder('preference_display_order');
        $prefSrch->addMultipleFields(['preference_id', 'preference_type', 'IFNULL(preference_title, preference_identifier) as preference_titles']);
        $prefRs = $prefSrch->getResultSet();
        $teacherPreferences = FatApp::getDb()->fetchAll($prefRs);
        $allPreferences = [];
        foreach ($teacherPreferences as $teacherPreference) {
            $allPreferences[$teacherPreference['preference_type']][] = $teacherPreference;
        }
        $template->set('allPreferences', $allPreferences);
        /* ] */
        /* spoken languages[ */
        $spokenLangSrch = clone $teacherSrchObj;
        $spokenLangSrch->joinTable(UserToLanguage::DB_TBL, 'INNER JOIN', 'u.user_id = utsl2.utsl_user_id', 'utsl2');
        $spokenLangSrch->joinTable(SpokenLanguage::DB_TBL, 'INNER JOIN', 'utsl_slanguage_id = slanguage_id AND slanguage_active = 1');
        $spokenLangSrch->joinTable(SpokenLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'slanguagelang_slanguage_id = utsl_slanguage_id AND slanguagelang_lang_id = ' . CommonHelper::getLangId(), 'sl_lang');
        $spokenLangSrch->addGroupBy('utsl_slanguage_id');
        $spokenLangSrch->addMultipleFields(['slanguage_id', 'IFNULL(slanguage_name, slanguage_identifier) as slanguage_name']);
        $spokenLangSrch->addOrder('slanguage_display_order');
        $spokenLangRs = $spokenLangSrch->getResultSet();
        $spokenLangsArr = FatApp::getDb()->fetchAllAssoc($spokenLangRs);
        $template->set('spokenLangsArr', $spokenLangsArr);
        /* ] */
        /* [ */
        $priceSrch = clone $teacherSrchObj;
        $priceRs = $priceSrch->getResultSet();
        $priceArr = FatApp::getDb()->fetchAll($priceRs);

        if ($priceArr) {
            $newArr = [];
            $newArr['minPrice'] = min(array_column($priceArr, 'minPrice'));
            $newArr['maxPrice'] = max(array_column($priceArr, 'maxPrice'));
            $priceArr = $newArr;
        }

        if (CommonHelper::getCurrencyId() != CommonHelper::getSystemCurrencyId()) {
            $priceArr['minPrice'] = CommonHelper::displayMoneyFormat(($priceArr['minPrice']) ?? 0, false, false, false);
            $priceArr['maxPrice'] = CommonHelper::displayMoneyFormat(($priceArr['maxPrice']) ?? 0, false, false, false);
        }
        $filterDefaultMinValue = ($priceArr['minPrice']) ?? 0;
        $filterDefaultMaxValue = ($priceArr['maxPrice']) ?? 0;
        $template->set('filterDefaultMinValue', $filterDefaultMinValue);
        $template->set('filterDefaultMaxValue', $filterDefaultMaxValue);
        $template->set('priceArr', $priceArr);
        $template->set('currencySymbolLeft', CommonHelper::getCurrencySymbolLeft());
        $template->set('currencySymbolRight', CommonHelper::getCurrencySymbolRight());
        /* ] */
        /* from countries[ */
        $fromSrch = clone $teacherSrchObj;
        $fromSrch->joinUserCountry(CommonHelper::getLangId());
        $fromSrch->addMultipleFields(['user_country_id', 'IFNULL(country_name, country_code) as country_name']);
        $fromSrch->addGroupBy('user_country_id');
        $fromRs = $fromSrch->getResultSet();
        $fromArr = FatApp::getDb()->fetchAll($fromRs);
        $template->set('fromArr', $fromArr);
        /* ] */
        $template->set('genderArr', User::getGenderArr());
        $template->set('frmFilters', $frmFilters);
    }

    public static function blogSidePanelArea($template)
    {
        $siteLangId = CommonHelper::getLangId();
        $blogSrchFrm = static::getBlogSearchForm();
        $blogSrchFrm->setFormTagAttribute('action', CommonHelper::generateUrl('Blog'));
        /* to fill the posted data into form[ */
        $postedData = FatApp::getPostedData();
        $blogSrchFrm->fill($postedData);
        /* ] */
        /* Right Side Categories Data[ */
        $categoriesArr = BlogPostCategory::getBlogPostCatParentChildWiseArr($siteLangId);
        $template->set('categoriesArr', $categoriesArr);
        /* ] */
        $template->set('blogSrchFrm', $blogSrchFrm);
        $template->set('siteLangId', $siteLangId);
    }

    public static function blogTopFeaturedCategories($template)
    {
        $siteLangId = CommonHelper::getLangId();
        $bpCatObj = new BlogPostCategory();
        $arrCategories = $bpCatObj->getFeaturedCategories($siteLangId);
        $categories = $bpCatObj->makeAssociativeArray($arrCategories);
        $template->set('featuredBlogCategories', $categories);
        $template->set('siteLangId', $siteLangId);
    }

    public static function getBlogSearchForm()
    {
        $frm = new Form('frmBlogSearch');
        $frm->setFormTagAttribute('autocomplete', 'off');
        $frm->addTextBox('', 'keyword', '', ['placeholder' => Label::getLabel('Lbl_Search', CommonHelper::getLangId())]);
        $frm->addHiddenField('', 'page', 1);
        $frm->addSubmitButton('', 'btn_submit', '');
        return $frm;
    }

    public static function getNewsLetterForm($langId)
    {
        $frm = new Form('frmNewsLetter');
        $frm->setRequiredStarWith('');
        $fld1 = $frm->addEmailField('', 'email');
        $fld1->requirements()->setRequired();
        $fld2 = $frm->addSubmitButton('', 'btnSubmit', Label::getLabel('LBL_Subscribe', $langId));
        $fld1->attachField($fld2);
        $frm->setJsErrorDisplay('afterfield');
        return $frm;
    }

    public static function doesStringStartWith(string $string, string $piece): bool
    {
        return substr($string, 0, strlen($piece)) == $piece;
    }

    public static function getUriFromPath($path)
    {
        return self::doesStringStartWith($path, CONF_WEBROOT_URL) ? rtrim(substr($path, strlen(CONF_WEBROOT_URL)), '/') : ltrim($path, '/');
    }

    public static function getCanonicalUrl()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url_components = parse_url($url);
        $path = $url_components['path'];
        $uri = Common::getUriFromPath($path);
        $row = UrlRewrite::getDataByOriginalUrl($uri);
        $rootUrl = $_SERVER['REQUEST_SCHEME'] . '://';
        if (!empty($_SERVER['HTTP_HOST'])) {
            $rootUrl .= $_SERVER['HTTP_HOST'];
        } else {
            $rootUrl .= $_SERVER['SERVER_NAME'];
        }
        return $rootUrl . CONF_WEBROOT_URL . (!empty($row) ? $row['urlrewrite_custom'] : $uri);
    }

}
