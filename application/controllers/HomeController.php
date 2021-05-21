<?php

class HomeController extends MyAppController
{

    public function index()
    {
        if (UserAuthentication::isUserLogged()) {
      
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

    public function setSiteDefaultLang($langId = 0)
    {
        $isActivePreferencesCookie = (!empty($this->cookieConsent[UserCookieConsent::COOKIE_PREFERENCES_FIELD]));
        if (!$isActivePreferencesCookie) {
            return false;
        }
        $langId = FatUtility::int($langId);
        if (0 < $langId) {
            $languages = Language::getAllNames();
            if (array_key_exists($langId, $languages)) {
                CommonHelper::setDefaultSiteLangCookie($langId);
            }
        }
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
