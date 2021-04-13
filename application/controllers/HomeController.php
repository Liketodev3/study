<?php
class HomeController extends MyAppController
{
    public function index()
    {
        if (UserAuthentication::isUserLogged()) {
            if (User::isTeacher()) {
                FatApp::redirectUser(CommonHelper::generateUrl('Account'));
            }
            FatApp::redirectUser(CommonHelper::generateUrl('Teachers'));
        }
        $db = FatApp::getDb();
        /* Main Slides[ */
        $srchSlide = new SlideSearch($this->siteLangId);
        $srchSlide->doNotCalculateRecords();
        $srchSlide->joinAttachedFile();
        $srchSlide->addMultipleFields(
            array(
                'slide_id', 'slide_record_id', 'slide_type', 'IFNULL(slide_title, slide_identifier) as slide_title',
                'slide_target', 'slide_url'
            )
        );
        $srchSlide->addOrder('slide_display_order');

        $totalSlidesPageSize = FatApp::getConfig('CONF_TOTAL_SLIDES_HOME_PAGE', FatUtility::VAR_INT, 4);
        $ppcSlidesPageSize = FatApp::getConfig('CONF_PPC_SLIDES_HOME_PAGE', FatUtility::VAR_INT, 4);
        $ppcSlides = array();
        $adminSlides = array();
        $slidesSrch = new SearchBase('(' . $srchSlide->getQuery() . ') as t');
        $slidesSrch->addMultipleFields(array('slide_id', 'slide_type', 'slide_record_id', 'slide_url', 'slide_target', 'slide_title'));
        // $slidesSrch->addOrder('', 'rand()');
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

    // public function setSiteDefaultLang($langId = 0)
    // {
    //     $isActivePreferencesCookie =  (!empty($this->cookieConsent[UserCookieConsent::COOKIE_PREFERENCES_FIELD]));

    //     if (!$isActivePreferencesCookie) {
    //         return false;
    //     }

    //     $langId = FatUtility::int($langId);
    //     if (0 < $langId) {
    //         $languages = Language::getAllNames();
    //         if (array_key_exists($langId, $languages)) {
    //             CommonHelper::setDefaultSiteLangCookie($langId);
    //         }
    //     }
    // }
    public function setSiteDefaultLang($langId = 0, $pathname = '')
    {
        if (!FatUtility::isAjaxCall()) {
            die('Invalid Action.');
        }

        $pathname = FatApp::getPostedData('pathname', FatUtility::VAR_STRING, '');
        $redirectUrl = '';
        if (empty($pathname)) {
            $redirectUrl = CommonHelper::generateFullUrl();
        }

        $isDefaultLangId = false;
        if ($langId == FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) {
            $isDefaultLangId = true;
        }

        if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0) && count(LANG_CODES_ARR) > 1) {
            $langCodeArr = LANG_CODES_ARR;
            if (count($langCodeArr) > 1) {
                $langIds = array_flip($langCodeArr);

                if (!empty($pathname)) {
                    $existingUrlLangCode = strtoupper(substr(ltrim($pathname, '/'), 0, 2));
                } else {
                    $existingUrlLangCode = $langCodeArr[CommonHelper::getLangId()];
                }

                if (in_array($existingUrlLangCode, LANG_CODES_ARR)) {
                    // $existingUrlLangId = $langIds[$existingUrlLangCode];
                    $pathname = ltrim(substr(ltrim($pathname, '/'), 2), '/');
                } else {
                    // $existingUrlLangId = FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1);
                    $pathname = ltrim($pathname, '/');
                }

                $srch = UrlRewrite::getSearchObject();
                $srch->joinTable(UrlRewrite::DB_TBL, 'LEFT OUTER JOIN', 'temp.urlrewrite_original = ur.urlrewrite_original and temp.urlrewrite_lang_id = ' . $langId, 'temp');
                $srch->doNotCalculateRecords();
                $srch->setPageSize(1);
                $srch->addMultipleFields(array('ifnull(temp.urlrewrite_custom, ur.urlrewrite_custom) customurl'));
                $srch->addCondition('ur.' . UrlRewrite::DB_TBL_PREFIX . 'custom', '=', $pathname);
                // $srch->addCondition('ur.' . UrlRewrite::DB_TBL_PREFIX . 'lang_id', '=', $existingUrlLangId);

                $rs = $srch->getResultSet();
                $row = FatApp::getDb()->fetch($rs);

                if (!empty($row)) {
                    $redirectUrl = CommonHelper::generateFullUrl('', '', [], '', null, false, false, false);

                    if (false == $isDefaultLangId) {
                        $redirectUrl .=  strtolower($langCodeArr[$langId]) . '/';
                    }
                    $redirectUrl .=  $row['customurl'];
                }
            }

            if (empty($redirectUrl)) {
                $redirectUrl = CommonHelper::generateFullUrl('', '', [], '', null, false, false, false);
                if (false == $isDefaultLangId) {
                    $redirectUrl .=  strtolower($langCodeArr[$langId]) . '/';
                }
                $redirectUrl .=  ltrim($pathname, '/');
            }
        } else {
            if (empty($redirectUrl)) {
                $redirectUrl = CommonHelper::generateFullUrl('', '', [], '', null, false, false, false) . ltrim($pathname, '/');
            }
        }


        $langId = FatUtility::int($langId);
        if (0 < $langId) {
            $languages = Language::getAllNames();
            if (array_key_exists($langId, $languages)) {
                setcookie('defaultSiteLang', $langId, time() + 3600 * 24 * 10, CONF_WEBROOT_URL);
            }
        }
        $this->set('redirectUrl', $redirectUrl);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setSiteDefaultCurrency($currencyId = 0)
    {
        $currencyId = FatUtility::int($currencyId);
        $currencyObj = new Currency();
        if (0 < $currencyId) {
            $currencies = Currency::getCurrencyAssoc($this->siteLangId);
            if (array_key_exists($currencyId, $currencies)) {
                if (isset($_SESSION['search_filters']['minPriceRange'])) {
                    unset($_SESSION['search_filters']['minPriceRange']);
                }
                if (isset($_SESSION['search_filters']['maxPriceRange'])) {
                    unset($_SESSION['search_filters']['maxPriceRange']);
                }
                $isActivePreferencesCookie =  (!empty($this->cookieConsent[UserCookieConsent::COOKIE_PREFERENCES_FIELD]));

                if ($isActivePreferencesCookie) {
                    CommonHelper::setCookie('defaultSiteCurrency', $currencyId, time() + 3600 * 24 * 10, CONF_WEBROOT_URL, '', true);
                }
            }
        }
    }
}
