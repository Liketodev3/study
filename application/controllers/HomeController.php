<?php

class HomeController extends MyAppController
{

    public function index()
    {
        if (UserAuthentication::isUserLogged()) {
            // if (User::isTeacher()) {
            //     FatApp::redirectUser(CommonHelper::generateUrl('Account', '', [], CONF_WEBROOT_DASHBOARD));
            // }
            FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
        }
        $db = FatApp::getDb();
        /* Main Slides[ */
        $srchSlide = new SlideSearch($this->siteLangId);
        $srchSlide->doNotCalculateRecords();
        $srchSlide->joinAttachedFile();
        $srchSlide->addMultipleFields(['slide_id', 'slide_record_id', 'slide_type',
            'IFNULL(slide_title, slide_identifier) as slide_title', 'slide_target', 'slide_url']);
        $srchSlide->addOrder('slide_display_order');
        $totalSlidesPageSize = FatApp::getConfig('CONF_TOTAL_SLIDES_HOME_PAGE', FatUtility::VAR_INT, 4);
        $ppcSlides = [];
        $adminSlides = [];
        $slidesSrch = new SearchBase('(' . $srchSlide->getQuery() . ') as t');
        $slidesSrch->addMultipleFields(['slide_id', 'slide_type', 'slide_record_id', 'slide_url', 'slide_target', 'slide_title']);
        if ($totalSlidesPageSize > count($ppcSlides)) {
            $totalSlidesPageSize = $totalSlidesPageSize - count($ppcSlides);
            $adminSlideSrch = clone $slidesSrch;
            $adminSlideSrch->addCondition('slide_type', '=', Slides::TYPE_SLIDE);
            $adminSlideSrch->setPageSize($totalSlidesPageSize);
            $slideRs = $adminSlideSrch->getResultSet();
            $adminSlides = $db->fetchAll($slideRs, 'slide_id');
        }
        $slides = array_merge($ppcSlides, $adminSlides);
        $this->set('slides', $slides);
        $this->set('newsLetterForm', Common::getNewsLetterForm(CommonHelper::getLangId()));
        /* ] */
        $this->_template->render();
    }

    public function setSiteDefaultLang($langId = 0, $pathname = '')
    {
        $isActivePreferencesCookie = (!empty($this->cookieConsent[UserCookieConsent::COOKIE_PREFERENCES_FIELD]));
        if (!$isActivePreferencesCookie) {
            FatUtility::dieJsonError(Label::getLabel('LBL_PREFRENCES_COOKIES_ARE_DISABLED', $this->siteLangId));
        }

        $pathname = ltrim(FatApp::getPostedData('pathname', FatUtility::VAR_STRING, ''), '/');
        $redirectUrl = '';
        if (empty($pathname)) {
            $redirectUrl = CommonHelper::generateFullUrl();
        }
        $uriComponents = explode('/', $pathname);

        if (!empty($uriComponents)) {
            if (in_array(strtoupper($uriComponents[0]), LANG_CODES_ARR)) {
                $pathname = ltrim(substr(ltrim($pathname, '/'), strlen($uriComponents[0])), '/');
            } else {
                $pathname = ltrim($pathname, '/');
            }
        }
        $uriSegments = explode('/', $pathname);
        $uriSegmentCount = count($uriSegments);
        if ($uriSegmentCount > 2) {
            $urlwithoutparameter = array_slice($uriSegments, 0, 2);
            $lastParamArray = array_slice($uriSegments, (-$uriSegmentCount + 2), ($uriSegmentCount - 2), true);
            $last_param = '/' . implode('/', $lastParamArray);
            $replaceArray = array_fill(count($urlwithoutparameter) - 1, count($lastParamArray), 'urlparameter');
            $uriSegments = array_merge($urlwithoutparameter, $replaceArray);
        }

        $srch = UrlRewrite::getSearchObject();
        $srch->joinTable(UrlRewrite::DB_TBL, 'LEFT OUTER JOIN', 'temp.urlrewrite_original = ur.urlrewrite_original and temp.urlrewrite_lang_id = ' . $langId, 'temp');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addMultipleFields(array('ifnull(temp.urlrewrite_custom, ur.urlrewrite_custom) customurl'));
        $srch->addCondition('ur.' . UrlRewrite::DB_TBL_PREFIX . 'custom', '=', implode('/', $uriSegments));

        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);


        if (!empty($row)) {
            $redirectUrl = CommonHelper::generateFullUrl('', '', [], '', null, false, false, false);
            if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0) && count(LANG_CODES_ARR) > 1 && $langId != FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1)) {
                $redirectUrl .=  strtolower(LANG_CODES_ARR[$langId]) . '/';
            }

            if (strpos($row['customurl'], 'urlparameter') !== false) {
                $redirectUrl .= implode('/', array_slice(explode('/', $row['customurl']), 0, 2)) . $last_param;
            } else {
                $redirectUrl .= $row['customurl'];
            }
        }

        if (empty($redirectUrl)) {
            $redirectUrl = CommonHelper::generateFullUrl('', '', [], '', null, false, false, false);
            if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0) && count(LANG_CODES_ARR) > 1 && $langId != FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1)) {
                $redirectUrl .=  strtolower(LANG_CODES_ARR[$langId]) . '/';
            }
            $redirectUrl .=  ltrim($pathname, '/');
        }


        $langId = FatUtility::int($langId);
        if (0 < $langId) {
            $languages = Language::getAllNames();
            if (array_key_exists($langId, $languages)) {
                CommonHelper::setDefaultSiteLangCookie($langId);
            }
        }
        $this->set('redirectUrl', $redirectUrl);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setSiteDefaultCurrency($currencyId = 0)
    {
        $currencyId = FatUtility::int($currencyId);
        if (0 < $currencyId) {
            $currencies = Currency::getCurrencyAssoc($this->siteLangId);
            if (array_key_exists($currencyId, $currencies)) {
                if (isset($_SESSION['search_filters']['minPriceRange'])) {
                    unset($_SESSION['search_filters']['minPriceRange']);
                }
                if (isset($_SESSION['search_filters']['maxPriceRange'])) {
                    unset($_SESSION['search_filters']['maxPriceRange']);
                }
                $isActivePreferencesCookie = (!empty($this->cookieConsent[UserCookieConsent::COOKIE_PREFERENCES_FIELD]));

                if ($isActivePreferencesCookie) {
                    CommonHelper::setCookie('defaultSiteCurrency', $currencyId, time() + 3600 * 24 * 10, CONF_WEBROOT_FRONTEND, '', true);
                }
            }
        }
    }

}
