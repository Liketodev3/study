<?php
class AdminPrivilege
{
    const SECTION_USERS = 1;
    const SECTION_TEACHER_APPROVAL_FORM = 2;
    const SECTION_TEACHER_APPROVAL_REQUESTS = 3;
    const SECTION_LANGUAGE = 4;
    const SECTION_NOTIFICATION = 5;
    const SECTION_ADMIN_DASHBOARD = 6;
    const SECTION_CONTENT_PAGES = 7;
    const SECTION_CONTENT_BLOCKS = 8;
    const SECTION_NAVIGATION_MANAGEMENT = 9;
    const SECTION_COUNTRIES = 10;
    const SECTION_STATES = 11;
    const SECTION_SOCIALPLATFORM = 12;
    const SECTION_DISCOUNT_COUPONS = 13;
    const SECTION_GENERAL_SETTINGS = 14;
    const SECTION_LANGUAGE_LABELS = 15;
    const SECTION_PAYMENT_METHODS = 16;
    const SECTION_CURRENCY_MANAGEMENT = 17;
    const SECTION_EMAIL_TEMPLATES = 18;
    const SECTION_META_TAGS = 19;
    const SECTION_URL_REWRITE = 20;
    const SECTION_ADMIN_USERS = 21;
    const SECTION_EMAIL_ARCHIVES = 22;
    const SECTION_SLIDES = 23;
    const SECTION_BANNERS = 24;
    const SECTION_TESTIMONIAL = 25;
    const SECTION_ADMIN_PERMISSIONS = 26;
    const SECTION_TEACHER_PREFFERENCES = 27;
    const SECTION_LESSON_PACKAGES = 28;
    const SECTION_COURSE_CATEGORY = 29;
    const SECTION_SPOKEN_LANGUAGES = 30;
    const SECTION_TEACHING_LANGUAGES = 44;
    const SECTION_FAQ = 31;
    const SECTION_FAQ_CATEGORY = 45;
    const SECTION_BLOG_POST_CATEGORIES = 32;
    const SECTION_BLOG_POSTS = 33;
    const SECTION_BLOG_CONTRIBUTIONS = 34;
    const SECTION_BLOG_COMMENTS = 35;
    const SECTION_BIBLE_CONTENT = 36;
    const SECTION_MANAGE_PURCHASED_LESSONS = 37;
    const SECTION_ISSUES_REPORTED = 38;
    const SECTION_GIFTCARDS = 39;
    const SECTION_WITHDRAW_REQUESTS = 40;
    const SECTION_TEACHER_REVIEWS = 41;
    const SECTION_COMMISSION = 42;
    const SECTION_SALES_REPORT = 43;
    const SECTION_GROUP_CLASSES = 44;
    const SECTION_TIMEZONES = 45;

    const PRIVILEGE_NONE = 0;
    const PRIVILEGE_READ = 1;
    const PRIVILEGE_WRITE = 2;


    const SECTION_TOP_LANGUAGES_REPORT = 46;
    const SECTION_TEACHER_PERFORMANCE_REPORT = 47;
    const SECTION_RESCHEDULE_REPORT = 47;

    private static $instance = null;
    private $loadedPermissions = array();

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function isAdminSuperAdmin($adminId)
    {
        return (1 == $adminId);
    }
    public static function getPermissionArr()
    {
        $arr = array(
            static::PRIVILEGE_NONE => Label::getLabel('MSG_None', CommonHelper::getLangId()),
            static::PRIVILEGE_READ => Label::getLabel('MSG_Read_Only', CommonHelper::getLangId()),
            static::PRIVILEGE_WRITE => Label::getLabel('MSG_Read_and_Write', CommonHelper::getLangId())
        );
        return $arr;
    }

    public static function getPermissionModulesArr()
    {
        $arr = array(
            static::SECTION_ADMIN_DASHBOARD => Label::getLabel('MSG_Admin_Dashboard', CommonHelper::getLangId()),
            static::SECTION_TEACHER_APPROVAL_FORM => Label::getLabel('MSG_Teacher_Approval_Form', CommonHelper::getLangId()),
            static::SECTION_TEACHER_APPROVAL_REQUESTS => Label::getLabel('MSG_Teacher_Approval_Requests', CommonHelper::getLangId()),
            static::SECTION_USERS => Label::getLabel('MSG_Users', CommonHelper::getLangId()),
            static::SECTION_CONTENT_PAGES => Label::getLabel('MSG_Content_Pages', CommonHelper::getLangId()),
            static::SECTION_CONTENT_BLOCKS => Label::getLabel('MSG_Content_Blocks', CommonHelper::getLangId()),
            static::SECTION_NAVIGATION_MANAGEMENT => Label::getLabel('MSG_Navigation_Management', CommonHelper::getLangId()),
            static::SECTION_COUNTRIES => Label::getLabel('MSG_Countries', CommonHelper::getLangId()),
            static::SECTION_STATES => Label::getLabel('MSG_States', CommonHelper::getLangId()),
            static::SECTION_SOCIALPLATFORM => Label::getLabel('MSG_Social_Platform', CommonHelper::getLangId()),
            static::SECTION_DISCOUNT_COUPONS => Label::getLabel('MSG_Discount_Coupons', CommonHelper::getLangId()),
            static::SECTION_LANGUAGE_LABELS => Label::getLabel('MSG_Language_Labels', CommonHelper::getLangId()),
            static::SECTION_SLIDES => Label::getLabel('MSG_Home_Page_Slide_Management', CommonHelper::getLangId()),
            static::SECTION_BANNERS => Label::getLabel('MSG_Banners', CommonHelper::getLangId()),
            static::SECTION_TEACHER_PREFFERENCES => Label::getLabel('MSG_Teacher_Preferences', CommonHelper::getLangId()),
            static::SECTION_SPOKEN_LANGUAGES => Label::getLabel('MSG_Spoken_Languages', CommonHelper::getLangId()),
            static::SECTION_TEACHING_LANGUAGES => Label::getLabel('MSG_Teaching_Languages', CommonHelper::getLangId()),
            static::SECTION_GENERAL_SETTINGS => Label::getLabel('MSG_General_Settings', CommonHelper::getLangId()),
            static::SECTION_PAYMENT_METHODS => Label::getLabel('MSG_Payment_Methods', CommonHelper::getLangId()),
            static::SECTION_CURRENCY_MANAGEMENT => Label::getLabel('MSG_Currency_Management', CommonHelper::getLangId()),
            static::SECTION_EMAIL_TEMPLATES => Label::getLabel('MSG_Email_Templates', CommonHelper::getLangId()),
            static::SECTION_META_TAGS => Label::getLabel('MSG_Meta_Tags', CommonHelper::getLangId()),
            static::SECTION_URL_REWRITE => Label::getLabel('MSG_Url_Rewriting', CommonHelper::getLangId()),
            static::SECTION_ADMIN_USERS => Label::getLabel('MSG_Admin_Users', CommonHelper::getLangId()),
            static::SECTION_SLIDES => Label::getLabel('MSG_Home_Page_Slide_Management', CommonHelper::getLangId()),
            static::SECTION_TESTIMONIAL => Label::getLabel('MSG_Testimonial', CommonHelper::getLangId()),
            static::SECTION_BLOG_POST_CATEGORIES => Label::getLabel('MSG_Blog_Categories', CommonHelper::getLangId()),
            static::SECTION_BLOG_POSTS => Label::getLabel('MSG_Blog_Posts', CommonHelper::getLangId()),
            static::SECTION_BLOG_CONTRIBUTIONS => Label::getLabel('MSG_Blog_Contributions', CommonHelper::getLangId()),
            static::SECTION_BLOG_COMMENTS => Label::getLabel('MSG_Blog_Comments', CommonHelper::getLangId()),
            static::SECTION_LANGUAGE_LABELS => Label::getLabel('MSG_Language_Labels', CommonHelper::getLangId()),
            static::SECTION_BIBLE_CONTENT => Label::getLabel('MSG_Bible_Content', CommonHelper::getLangId()),
            static::SECTION_MANAGE_PURCHASED_LESSONS => Label::getLabel('MSG_Manage_Purchased_lessons', CommonHelper::getLangId()),
            static::SECTION_ISSUES_REPORTED => Label::getLabel('MSG_Manage_Issues_Reported', CommonHelper::getLangId()),
            static::SECTION_GIFTCARDS	 		=> Label::getLabel('MSG_GIFTCARDS', CommonHelper::getLangId()),
            static::SECTION_WITHDRAW_REQUESTS => Label::getLabel('MSG_Withdraw_Requests', CommonHelper::getLangId()),
            static::SECTION_TEACHER_REVIEWS => Label::getLabel('MSG_Teacher_Reviews', CommonHelper::getLangId()),
            static::SECTION_COMMISSION => Label::getLabel('MSG_Commission', CommonHelper::getLangId()),
            static::SECTION_SALES_REPORT => Label::getLabel('MSG_Sales_Report', CommonHelper::getLangId()),
            static::SECTION_FAQ => Label::getLabel('MSG_Manage_faqs', CommonHelper::getLangId()),
            static::SECTION_TOP_LANGUAGES_REPORT => Label::getLabel('MSG_Top_Languages_Report', CommonHelper::getLangId()),
            static::SECTION_TEACHER_PERFORMANCE_REPORT => Label::getLabel('MSG_Teacher_Performance_Report', CommonHelper::getLangId()),
            static::SECTION_FAQ => Label::getLabel('MSG_Manage_faqs', CommonHelper::getLangId()),
            static::SECTION_FAQ_CATEGORY => Label::getLabel('MSG_Manage_faq_Categories', CommonHelper::getLangId()),
            static::SECTION_GROUP_CLASSES => Label::getLabel('MSG_Manage_GROUP_CLASSES', CommonHelper::getLangId()),
            static::SECTION_TIMEZONES => Label::getLabel('MSG_Manage_Timezones', CommonHelper::getLangId()),
        );
        return $arr;
    }

    public static function getAdminPermissionLevel($adminId, $sectionId = 0)
    {
        $db = FatApp::getDb();
        $adminId = FatUtility::int($adminId);
        /* Are you looking for permissions of administrator [ */
        if (static::isAdminSuperAdmin($adminId)) {
            $arrLevels = array();
            if ($sectionId > 0) {
                $arrLevels[$sectionId] = static::PRIVILEGE_WRITE;
            } else {
                for ($i = 0; $i <= 2; $i++) {
                    $arrLevels[$i] = static::PRIVILEGE_WRITE;
                }
            }
            return $arrLevels;
        }
        /* ] */
        $srch = new SearchBase('tbl_admin_permissions');
        $srch->addCondition('admperm_admin_id', '=', $adminId);
        if (0 < $sectionId) {
            $srch->addCondition('admperm_section_id', '=', $sectionId);
        }
        $srch->addMultipleFields(array(
            'admperm_section_id',
            'admperm_value'
        ));
        $rs = $srch->getResultSet();
        $arr = $db->fetchAllAssoc($rs);
        return $arr;
    }

    private function cacheLoadedPermission($adminId, $secId, $level)
    {
        if (!isset($this->loadedPermissions[$adminId])) {
            $this->loadedPermissions[$adminId] = array();
        }
        $this->loadedPermissions[$adminId][$secId] = $level;
    }

    private function checkPermission($adminId, $secId, $level, $returnResult = false)
    {
        $db = FatApp::getDb();
        if (!in_array($level, array(
            1,
            2
        ))) {
            trigger_error(Label::getLabel('MSG_Invalid_permission_level_checked', CommonHelper::getLangId()) . ' ' . $level, E_USER_ERROR);
        }
        $adminId = FatUtility::convertToType($adminId, FatUtility::VAR_INT);
        if (0 == $adminId) {
            $adminId = AdminAuthentication::getLoggedAdminId();
        }
        if (isset($this->loadedPermissions[$adminId][$secId])) {
            if ($level <= $this->loadedPermissions[$adminId][$secId]) {
                return true;
            }
            return $this->returnFalseOrDie($returnResult);
        }
        if ($this->isAdminSuperAdmin($adminId)) {
            return true;
        }
        $row_admin = Admin::getAttributesById($adminId, array(
            'admin_active'
        ));
        if (empty($row_admin)) {
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Request', CommonHelper::getLangId()));
        }
        if ($row_admin['admin_active'] != applicationConstants::ACTIVE) {
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Request', CommonHelper::getLangId()));
        }
        $rs = $db->query("SELECT admperm_value FROM tbl_admin_permissions WHERE
				admperm_admin_id = " . $adminId . " AND admperm_section_id = " . $secId);
        if (!$row = $db->fetch($rs)) {
            $this->cacheLoadedPermission($adminId, $secId, static::PRIVILEGE_NONE);
            return $this->returnFalseOrDie($returnResult);
        }
        $permissionLevel = $row['admperm_value'];
        $this->cacheLoadedPermission($adminId, $secId, $permissionLevel);
        if ($level > $permissionLevel) {
            return $this->returnFalseOrDie($returnResult);
        }
        return (true);
    }

    private function returnFalseOrDie($returnResult, $msg = '')
    {
        if ($returnResult) {
            return (false);
        }
        Message::addErrorMessage(Label::getLabel('MSG_Unauthorized_Access!', CommonHelper::getLangId()));
        if ($msg == '') {
            $msg = Message::getHtml();
        }
        FatUtility::dieWithError($msg);
    }

    public function clearPermissionCache($adminId)
    {
        if (isset($this->loadedPermissions[$adminId])) {
            unset($this->loadedPermissions[$adminId]);
        }
    }

    public function canViewContentBlocks($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_CONTENT_BLOCKS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditContentBlocks($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_CONTENT_BLOCKS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewContentPages($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_CONTENT_PAGES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditContentPages($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_CONTENT_PAGES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewUsers($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_USERS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditUsers($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_USERS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTeacherApprovalForm($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_APPROVAL_FORM, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTeacherApprovalForm($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_APPROVAL_FORM, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTeacherApprovalRequests($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_APPROVAL_REQUESTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTeacherApprovalRequests($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_APPROVAL_REQUESTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewAdminDashboard($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ADMIN_DASHBOARD, static::PRIVILEGE_READ, $returnResult);
    }

    public function canViewNavigationManagement($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_NAVIGATION_MANAGEMENT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditNavigationManagement($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_NAVIGATION_MANAGEMENT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewCountries($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_COUNTRIES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canViewSlides($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SLIDES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSlides($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SLIDES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canEditCountries($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_COUNTRIES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewZones($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ZONES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditZones($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ZONES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewStates($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_STATES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditStates($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_STATES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewEmailTemplates($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_EMAIL_TEMPLATES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditEmailTemplates($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_EMAIL_TEMPLATES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewAdminUsers($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ADMIN_USERS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditAdminUsers($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ADMIN_USERS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewBanners($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BANNERS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBanners($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BANNERS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSocialPlatforms($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SOCIALPLATFORM, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSocialPlatforms($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SOCIALPLATFORM, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewDiscountCoupons($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_DISCOUNT_COUPONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditDiscountCoupons($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_DISCOUNT_COUPONS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewLanguageLabel($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_LANGUAGE_LABELS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditLanguageLabel($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_LANGUAGE_LABELS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewCurrencyManagement($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_CURRENCY_MANAGEMENT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditCurrencyManagement($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_CURRENCY_MANAGEMENT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewGeneralSettings($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_GENERAL_SETTINGS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditGeneralSettings($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_GENERAL_SETTINGS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewPaymentMethods($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_PAYMENT_METHODS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditPaymentMethods($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_PAYMENT_METHODS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewMetaTags($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_META_TAGS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditMetaTags($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_META_TAGS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewUrlRewrite($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_URL_REWRITE, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditUrlRewrite($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_URL_REWRITE, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewEmailArchives($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_EMAIL_ARCHIVES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canViewTestimonial($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TESTIMONIAL, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTestimonial($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TESTIMONIAL, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewLessonPackages($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_LESSON_PACKAGES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditLessonPackages($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_LESSON_PACKAGES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewFaq($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_FAQ, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditFaq($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_FAQ, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewFaqCategory($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_FAQ_CATEGORY, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditFaqCategory($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_FAQ_CATEGORY, static::PRIVILEGE_WRITE, $returnResult);
    }


    public function canViewCourseCategory($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_COURSE_CATEGORY, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditCourseCategory($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_COURSE_CATEGORY, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSpokenLanguage($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SPOKEN_LANGUAGES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSpokenLanguage($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SPOKEN_LANGUAGES, static::PRIVILEGE_WRITE, $returnResult);
    }
    /* code added on 30-07-2019 TEACHING LANGUAGES SEPERATE OPTION */
    public function canViewTeachingLanguage($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHING_LANGUAGES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTeachingLanguage($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHING_LANGUAGES, static::PRIVILEGE_WRITE, $returnResult);
    }

    /**/
    public function canViewPreferences($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_PREFFERENCES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditPreferences($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_PREFFERENCES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewAdminPermissions($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ADMIN_PERMISSIONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditAdminPermissions($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ADMIN_PERMISSIONS, static::PRIVILEGE_WRITE, $returnResult);
    }


    public function canViewBlogPostCategories($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_POST_CATEGORIES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBlogPostCategories($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_POST_CATEGORIES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewBlogPosts($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_POSTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBlogPosts($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_POSTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewBlogContributions($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_CONTRIBUTIONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBlogContributions($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_CONTRIBUTIONS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewBlogComments($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_COMMENTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBlogComments($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BLOG_COMMENTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewBibleContent($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BIBLE_CONTENT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBibleContent($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_BIBLE_CONTENT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewPurchasedLessons($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_MANAGE_PURCHASED_LESSONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditPurchasedLessons($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_MANAGE_PURCHASED_LESSONS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewIssuesReported($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ISSUES_REPORTED, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditIssuesReported($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_ISSUES_REPORTED, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewGiftcards($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_GIFTCARDS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditGiftcards($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_GIFTCARDS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewWithdrawRequests($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_WITHDRAW_REQUESTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditWithdrawRequests($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_WITHDRAW_REQUESTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTeacherReviews($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_REVIEWS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTeacherReviews($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_REVIEWS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewCommissionSettings($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_COMMISSION, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditCommissionSettings($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_COMMISSION, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canEditSalesReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SALES_REPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSalesReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_SALES_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditLanguage($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_LANGUAGE, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewLanguage($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_LANGUAGE, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTopLangReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TOP_LANGUAGES_REPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTopLangReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TOP_LANGUAGES_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTeacherPerformanceReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_PERFORMANCE_REPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTeacherPerformanceReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TEACHER_PERFORMANCE_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditRescheduleReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_RESCHEDULE_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canViewRescheduleReport($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_RESCHEDULE_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditGroupClasses($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_GROUP_CLASSES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewGroupClasses($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_GROUP_CLASSES, static::PRIVILEGE_READ, $returnResult);
    }
    public function canEditTimezones($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TIMEZONES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTimezones($adminId = 0, $returnResult = false)
    {
        return $this->checkPermission($adminId, static::SECTION_TIMEZONES, static::PRIVILEGE_READ, $returnResult);
    }
}
