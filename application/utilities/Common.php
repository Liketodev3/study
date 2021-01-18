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

    public static function upcomingScheduledLessons($template)
    {
        $srch = new ScheduledLessonSearch(false);
        $srch->addGroupBy('slesson_id');
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->joinTeacher();
        $srch->joinLearner();
        $srch->joinTeacherCountry(CommonHelper::getLangId());
        $srch->addCondition('order_is_paid', ' = ', Order::ORDER_IS_PAID);

        $srch->joinTeacherSettings();
        $srch->joinTeacherTeachLanguageView(CommonHelper::getLangId());
        $srch->addOrder('slesson_date', 'ASC');
        $srch->addOrder('slesson_status', 'ASC');
        $srch->addMultipleFields(array(
            'slns.slesson_id',
            'slns.slesson_slanguage_id',
            'sld.sldetail_learner_id as learnerId',
            'slns.slesson_teacher_id as teacherId',
            'ut.user_first_name as teacherFname',
            'ut.user_url_name as user_url_name',
            'ut.user_last_name as teacherLname',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) as teacherFullName',
            /* 'ut.user_timezone as teacherTimeZone', */
            'IFNULL(teachercountry_lang.country_name, teachercountry.country_code) as teacherCountryName',
            'slns.slesson_date',
            'slns.slesson_start_time',
            'slns.slesson_end_time',
            'slns.slesson_status',
            'slns.slesson_is_teacher_paid',
            //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
            'op_lpackage_is_free_trial as is_trial',
            'op_lesson_duration'
        ));
        $srch->addCondition('slesson_status', ' = ', ScheduledLesson::STATUS_SCHEDULED);
        $srch->addCondition('mysql_func_CONCAT(slns.slesson_date," ",slns.slesson_start_time )', '>=', date('Y-m-d H:i:s'), 'AND', true);
        $srch->setPageSize(10);
        $srch->setPageNumber(1);

        $rs = $srch->getResultSet();
        $lessons = FatApp::getDb()->fetchAll($rs);

        $template->set("lessons", $lessons);
    }

    public static function topRatedTeachers($template)
    {
        $userObj = new UserSearch();
        $topRatedTeachers = $userObj->getTopRatedTeachers();
        if ($topRatedTeachers) {
            foreach ($topRatedTeachers as $k=>$topRatedTeacher) {
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
        //$bannerSrch->addCondition('blocation_id','=',BannerLocation::BLOCK_HOW_IT_WORKS);
        $rs = $bannerSrch->getResultSet();
        $bannerLocation = $db->fetchAll($rs, 'blocation_key');
        $banners = $bannerLocation;
        foreach ($bannerLocation as $val) {
            $srch = new BannerSearch(CommonHelper::getLangId(), true);
            $srch->doNotCalculateRecords();
            $srch->joinAttachedFile();
            $srch->addCondition('banner_blocation_id', '=', $val['blocation_id']);

            /*if($val['blocation_banner_count'] > 0){
                $srch->setPageSize($val['blocation_banner_count']);
            }*/

            //$srch->addOrder('', 'rand()');

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
        $srch->addMultipleFields(array('t.*' , 't_l.testimonial_title' , 't_l.testimonial_text'));
        $srch->addCondition('testimoniallang_testimonial_id', 'is not', 'mysql_func_null', 'and', true);
        $srch->addOrder('testimonial_added_on', 'desc');
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $template->set("testimonials", $records);
    }

    public static function headerLanguageArea($template)
    {
        $template->set('siteLangId', CommonHelper::getLangId());
        $template->set('languages', Language::getAllNames(false));
    }

    public static function headerUserLoginArea($template)
    {
        if (UserAuthentication::isUserLogged()) {
            $template->set('userName', UserAuthentication::getLoggedUserAttribute('user_first_name'));
        }

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
        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $template->set('rows', $rows);
        $template->set('siteLangId', $siteLangId);
    }

    public static function learnerSocialMediaSignUp($template)
    {
    }

    public static function teacherLeftFilters($template)
    {
        $frmFilters = new Form('teacherFilters');
        $filterSortBy = array(
            'popularity_desc'=>	Label::getLabel('LBL_By_Popularity'),
            'price_asc'	=>	Label::getLabel('LBL_By_Price_Low_to_High'),
            'price_desc'=>	Label::getLabel('LBL_By_Price_High_to_Low'),
        );
        $frmFilters->addSelectBox('', 'filterSortBy', $filterSortBy, 'popularity_desc', array(), '');

        $teacherSrchObj = new UserSearch();
        $teacherSrchObj->setTeacherDefinedCriteria(true);
        $teacherSrchObj->doNotLimitRecords();
        // echo "<pre>";
        // echo $teacherSrchObj->getQuery();
        // die;
        /* preferences/skills[ */
        $prefSrch = clone $teacherSrchObj;
        $prefSrch->joinTable(Preference::DB_TBL_USER_PREF, 'INNER JOIN', 'u.user_id = utp.utpref_user_id', 'utp');
        $prefSrch->joinTable(Preference::DB_TBL, 'INNER JOIN', 'utp.utpref_preference_id = p.preference_id', 'p');
        $prefSrch->joinTable(Preference::DB_TBL_LANG, 'LEFT OUTER JOIN', 'pl.preferencelang_preference_id = p.preference_id AND pl.preferencelang_lang_id = ' . CommonHelper::getLangId(), 'pl');
        $prefSrch->addGroupBy('preference_id');
        $prefSrch->addOrder('preference_display_order');
        $prefSrch->addMultipleFields(array('preference_id', 'preference_type', 'IFNULL(preference_title, preference_identifier) as preference_titles'));
        $prefRs = $prefSrch->getResultSet();
        $teacherPreferences = FatApp::getDb()->fetchAll($prefRs);
        $allPreferences = array();
        foreach ($teacherPreferences as $teacherPreference) {
            $allPreferences[$teacherPreference['preference_type']][] = $teacherPreference;
        }
        $template->set('allPreferences', $allPreferences);
        /* ] */

        /* spoken languages[ */
        $spokenLangSrch = clone $teacherSrchObj;
        $spokenLangSrch->joinTable(UserToLanguage::DB_TBL, 'INNER JOIN', 'u.user_id = utsl2.utsl_user_id', 'utsl2');
        $spokenLangSrch->joinTable(SpokenLanguage::DB_TBL, 'INNER JOIN', 'utsl_slanguage_id = slanguage_id AND slanguage_active = 1');
        $spokenLangSrch->joinTable(SpokenLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'slanguagelang_slanguage_id = utsl_slanguage_id AND slanguagelang_lang_id = '. CommonHelper::getLangId(), 'sl_lang');
        $spokenLangSrch->addGroupBy('utsl_slanguage_id');
        $spokenLangSrch->addMultipleFields(array( 'slanguage_id', 'IFNULL(slanguage_name, slanguage_identifier) as slanguage_name'));
        $spokenLangSrch->addOrder('slanguage_display_order');
        $spokenLangRs = $spokenLangSrch->getResultSet();
        $spokenLangsArr = FatApp::getDb()->fetchAllAssoc($spokenLangRs);
        $template->set('spokenLangsArr', $spokenLangsArr);
        //$frmFilters->addCheckBoxes( '', 'filterSpokenLanguage', $spokenLangsArr );
        /* ] */

        /* [ */
        $priceSrch = clone $teacherSrchObj;

        //$priceSrch->addMultipleFields( array('MIN(us_bulk_lesson_amount) as minPrice', 'MAX(us_bulk_lesson_amount) as maxPrice') );
        $priceRs = $priceSrch->getResultSet();
        $priceArr = FatApp::getDb()->fetchAll($priceRs);
        // echo "<pre>";
        // echo $teacherSrchObj->getQuery();
        // die;
        if ($priceArr) {
            $newArr = array();
            $newArr['minPrice'] = min(array_column($priceArr, 'minPrice'));
            $newArr['maxPrice'] = max(array_column($priceArr, 'maxPrice'));
            $priceArr = $newArr;
        }
        //echo CommonHelper::getCurrencyId(); die;
        if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) {
            $priceArr['minPrice'] = CommonHelper::displayMoneyFormat(($priceArr['minPrice'])??0, false, false, false);
            $priceArr['maxPrice'] = CommonHelper::displayMoneyFormat(($priceArr['maxPrice'])??0, false, false, false);
        }
        $filterDefaultMinValue = ($priceArr['minPrice'])??0;
        $filterDefaultMaxValue = ($priceArr['maxPrice'])??0;
        $template->set('filterDefaultMinValue', $filterDefaultMinValue);
        $template->set('filterDefaultMaxValue', $filterDefaultMaxValue);
        $template->set('priceArr', $priceArr);
        $template->set('currencySymbolLeft', CommonHelper::getCurrencySymbolLeft());
        $template->set('currencySymbolRight', CommonHelper::getCurrencySymbolRight());
        /* echo $priceSrch->getQuery();
        die(); */
        /* ] */

        /* from countries[ */
        $fromSrch = clone $teacherSrchObj;
        $fromSrch->joinUserCountry(CommonHelper::getLangId());
        $fromSrch->addMultipleFields(array('user_country_id', 'IFNULL(country_name, country_code) as country_name'));
        $fromSrch->addGroupBy('user_country_id');
        $fromRs = $fromSrch->getResultSet();
        $fromArr = FatApp::getDb()->fetchAll($fromRs);
        $template->set('fromArr', $fromArr);
        /* ] */

        /* gender[ */
        // $genderSrch = clone $teacherSrchObj;
        // $genderSrch->addGroupBy('user_gender');
        // $genderSrch->addMultipleFields(array('user_gender'));
        // $genderRs = $genderSrch->getResultSet();
        // $genderArr = FatApp::getDb()->fetchAll($genderRs);
        $template->set('genderArr',  User::getGenderArr());
        /* ] */

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
        $frm->addTextBox('', 'keyword', '', array('placeholder'=>Label::getLabel('Lbl_Search', CommonHelper::getLangId())));
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

    public static function doesStringStartWith(string $string, string $piece) : bool
    {
        return substr($string, 0, strlen($piece)) == $piece;
    }

    public static function getUriFromPath($path)
    {
        return self::doesStringStartWith($path, CONF_WEBROOT_URL) ? substr($path, strlen(CONF_WEBROOT_URL)) : ltrim($path, '/');
    }
}