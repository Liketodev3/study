<?php
class applicationConstants
{
    const YES = 1;
    const NO = 0;

    const DAILY = 0;
    const WEEKLY = 1;
    const MONTHLY = 2;

    const ON = 1;
    const OFF = 0;

    const ACTIVE = 1;
    const INACTIVE = 0;

    const WEIGHT_GRAM = 1;
    const WEIGHT_KILOGRAM = 2;
    const WEIGHT_POUND = 3;

    const LENGTH_CENTIMETER = 1;
    const LENGTH_METER = 2;
    const LENGTH_INCH = 3;

    const NEWS_LETTER_SYSTEM_MAILCHIMP = 1;
    const NEWS_LETTER_SYSTEM_AWEBER = 2;

    const LINK_TARGET_CURRENT_WINDOW = "_self";
    const LINK_TARGET_BLANK_WINDOW = "_blank";

    const PERCENTAGE = 1;
    const FLAT = 2;

    const PUBLISHED = 1;
    const DRAFT = 0;

    const BLOG_CONTRIBUTION_PENDING = 0;
    const BLOG_CONTRIBUTION_APPROVED = 1;
    const BLOG_CONTRIBUTION_POSTED = 2;
    const BLOG_CONTRIBUTION_REJECTED = 3;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_OTHER = 3;

    const DISCOUNT_COUPON = 1;
    const DISCOUNT_REWARD_POINTS = 2;

    const SCREEN_DESKTOP = 1;
    const SCREEN_IPAD = 2;
    const SCREEN_MOBILE = 3;

    const CHECKOUT_PRODUCT = 1;
    const CHECKOUT_SUBSCRIPTION = 2;
    const CHECKOUT_PPC = 3;
    const CHECKOUT_ADD_MONEY_TO_WALLET = 4;

    const SMTP_TLS = 'tls';
    const SMTP_SSL = 'ssl';

    const LAYOUT_LTR = 'ltr';
    const LAYOUT_RTL = 'rtl';

    const SYSTEM_CATALOG = 0;
    const CUSTOM_CATALOG = 1;

    const DIGITAL_DOWNLOAD_FILE = 0;
    const DIGITAL_DOWNLOAD_LINK = 1;

    const MEETING_COMET_CHAT = 'comet_chat';
    const MEETING_ZOOM = 'zoom';
    const MEETING_LESSON_SPACE = 'lesson_space';

    const PHONE_NO_REGEX = "^.{4,16}$";
    const CREDIT_CARD_NO_REGEX = "^(?:(4[0-9]{12}(?:[0-9]{3})?)|(5[1-5][0-9]{14})|(6(?:011|5[0-9]{2})[0-9]{12})|(3[47][0-9]{13})|(3(?:0[0-5]|[68][0-9])[0-9]{11})|((?:2131|1800|35[0-9]{3})[0-9]{11}))$";
    const CVV_NO_REGEX = "^[0-9]{3,4}$";

    const CLASS_TYPE_GROUP = 'group';
    const CLASS_TYPE_1_TO_1 = '1to1';

    public static function getWeightUnitsArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        return array(
            static::WEIGHT_GRAM        =>    Label::getLabel('LBL_Gram', $langId),
            static::WEIGHT_KILOGRAM    =>    Label::getLabel('LBL_Kilogram', $langId),
            static::WEIGHT_POUND    =>    Label::getLabel('LBL_Pound', $langId),
        );
    }

    public static function bannerTypeArr()
    {
        $bannerTypeArr = Language::getAllNames();
        return array(0 => Label::getLabel('LBL_All_Languages', CommonHelper::getLangId())) + $bannerTypeArr;
    }

    public static function digitalDownloadTypeArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        return array(
            static::DIGITAL_DOWNLOAD_FILE    =>    Label::getLabel('LBL_Digital_download_file', $langId),
            static::DIGITAL_DOWNLOAD_LINK    =>    Label::getLabel('LBL_Digital_download_link', $langId),
        );
    }

    public static function getLengthUnitsArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        return array(
            static::LENGTH_CENTIMETER    =>    Label::getLabel('LBL_CentiMeter', $langId),
            static::LENGTH_METER        =>    Label::getLabel('LBL_Meter', $langId),
            static::LENGTH_INCH            =>    Label::getLabel('LBL_Inch', $langId),
        );
    }

    public static function getYesNoArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::YES => Label::getLabel('LBL_Yes', $langId),
            static::NO => Label::getLabel('LBL_No', $langId)
        );
    }

    public static function getActiveInactiveArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::ACTIVE => Label::getLabel('LBL_Active', $langId),
            static::INACTIVE => Label::getLabel('LBL_In-active', $langId)
        );
    }

    public static function getBooleanArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            1 => Label::getLabel('LBL_True', $langId),
            0 => Label::getLabel('LBL_False', $langId)
        );
    }

    public static function getOnOffArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::ON => Label::getLabel('LBL_On', $langId),
            static::OFF => Label::getLabel('LBL_Off', $langId)
        );
    }

    public static function getNewsLetterSystemArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::NEWS_LETTER_SYSTEM_MAILCHIMP => Label::getLabel('LBL_Mailchimp', $langId),
            static::NEWS_LETTER_SYSTEM_AWEBER => Label::getLabel('LBL_Aweber', $langId),
        );
    }

    public static function getLinkTargetsArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        return array(
            static::LINK_TARGET_CURRENT_WINDOW        =>    Label::getLabel('LBL_Same_Window', $langId),
            static::LINK_TARGET_BLANK_WINDOW    =>    Label::getLabel('LBL_New_Window', $langId)
        );
    }



    public static function getPercentageFlatArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        return array(
            static::PERCENTAGE    =>    Label::getLabel('LBL_Percentage', $langId),
            static::FLAT    =>    Label::getLabel('LBL_Flat', $langId)
        );
    }

    public static function allowedMimeTypes()
    {
        return array('text/plain', 'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/bmp', 'image/tiff', 'image/svg+xml', 'application/zip', 'application/x-zip', 'application/x-zip-compressed', 'application/rar', 'application/x-rar', 'application/x-rar-compressed', 'application/octet-stream', 'audio/mpeg', 'video/quicktime', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'text/plain', 'image/x-icon', 'image/svg+xml', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
    }

    public static function allowedFileExtensions()
    {
        return array('zip', 'txt', 'png', 'jpeg', 'jpg', 'gif', 'bmp', 'ico', 'tiff', 'tif', 'svg', 'svgz', 'rar', 'msi', 'cab', 'mp3', 'qt', 'mov', 'pdf', 'psd', 'ai', 'eps', 'ps', 'doc', 'docx', 'ppt', 'pptx');
    }

    public static function getBlogPostStatusArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::DRAFT => Label::getLabel('LBL_Draft', $langId),
            static::PUBLISHED => Label::getLabel('LBL_Published', $langId),
        );
    }

    public static function getBlogContributionStatusArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::BLOG_CONTRIBUTION_PENDING    => Label::getLabel('LBL_Pending', $langId),
            static::BLOG_CONTRIBUTION_APPROVED     => Label::getLabel('LBL_Approved', $langId),
            static::BLOG_CONTRIBUTION_POSTED     => Label::getLabel('LBL_Posted', $langId),
            static::BLOG_CONTRIBUTION_REJECTED     => Label::getLabel('LBL_Rejected', $langId),
        );
    }

    public static function getBlogCommentStatusArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::INACTIVE => Label::getLabel('LBL_Pending', $langId),
            static::ACTIVE    => Label::getLabel('LBL_Approved', $langId)
        );
    }

    public static function getGenderArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        return array(
            static::GENDER_MALE        =>    Label::getLabel('LBL_Male', $langId),
            static::GENDER_FEMALE    =>    Label::getLabel('LBL_Female', $langId),
            static::GENDER_OTHER    =>    Label::getLabel('LBL_Other', $langId),
        );
    }

    public static function getDisplaysArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return array(
            static::SCREEN_DESKTOP => Label::getLabel('LBL_Desktop', $langId),
            static::SCREEN_IPAD => Label::getLabel('LBL_Ipad', $langId),
            static::SCREEN_MOBILE => Label::getLabel('LBL_Mobile', $langId)
        );
    }

    public static function getExcludePaymentGatewayArr()
    {
        return array(
            static::CHECKOUT_PRODUCT => array(''),
            static::CHECKOUT_SUBSCRIPTION => array(
                'CashOnDelivery',
                'Transferbank'
            ),
            static::CHECKOUT_PPC => array(
                'CashOnDelivery',
                'Transferbank'
            ),
            static::CHECKOUT_ADD_MONEY_TO_WALLET => array(
                'CashOnDelivery',
                'Transferbank'
            )
        );
    }

    public static function getCatalogTypeArr($langId)
    {
        return array(
            static::CUSTOM_CATALOG => Label::getLabel('LBL_Custom_Products', $langId),
            static::SYSTEM_CATALOG => Label::getLabel('LBL_Catalog_Products', $langId)
        );
    }

    public static function getCatalogTypeArrForFrontEnd($langId)
    {
        return array(
            static::SYSTEM_CATALOG => Label::getLabel('LBL_Marketplace_Products', $langId),
            static::CUSTOM_CATALOG => Label::getLabel('LBL_My_Private_Products', $langId)
        );
    }

    /* static function getShopUrlRewriteLink($customLink ='',$id = 0){
        return array(
            Shop::SHOP_VIEW_ORGINAL_URL.$id           =>  $customLink,
            Shop::SHOP_TOP_PRODUCTS_ORGINAL_URL.$id   =>  $customLink.'/top-products',
            Shop::SHOP_REVIEWS_ORGINAL_URL.$id        =>  $customLink.'/reviews',
            Shop::SHOP_SEND_MESSAGE_ORGINAL_URL.$id   =>  $customLink.'/contact',
            Shop::SHOP_POLICY_ORGINAL_URL.$id         =>  $customLink.'/policy'
        );
    } */

    /* static function getProductUrlRewriteLink($customLink ='',$id = 0){
        return array(
            Product::PRODUCT_VIEW_ORGINAL_URL.$id           =>  $customLink.'/'.$id,
            Product::PRODUCT_REVIEWS_ORGINAL_URL.$id        =>  $customLink.'/reviews/'.$id,
            Product::PRODUCT_MORE_SELLERS_ORGINAL_URL.$id        =>  $customLink.'/sellers/'.$id,
        );
    } */

    public static function getSmtpSecureArr($langId)
    {
        return array(
            static::SMTP_TLS       =>  Label::getLabel('LBL_tls', $langId),
            static::SMTP_SSL       =>   Label::getLabel('LBL_ssl', $langId),
        );
    }

    public static function getSmtpSecureSettingsArr()
    {
        return array(
            static::SMTP_TLS       =>  'tls',
            static::SMTP_SSL       =>  'ssl',
        );
    }

    public static function getLgColsForPackages()
    {
        return array(
            '1' => 4,
            '2' => 6,
            '3' => 4,
            '4' => 3,
            '5' => 4,
            '6' => 4,
            '7' => 4,
            '8' => 4,
            '9' => 4,
            '10' => 4
        );
    }

    public static function getMdColsForPackages()
    {
        return array(
            '1' => 4,
            '2' => 6,
            '3' => 4,
            '4' => 3,
            '5' => 4,
            '6' => 4,
            '7' => 4,
            '8' => 4,
            '9' => 4,
            '10' => 4
        );
    }

    public static function getLayoutDirections($langId)
    {
        return array(
            static::LAYOUT_LTR => Label::getLabel('LBL_Left_To_Right', $langId),
            static::LAYOUT_RTL => Label::getLabel('LBL_Right_To_Left', $langId),
        );
    }

    public static function getMonthsArr($langId)
    {
        return array(
            '01' => Label::getLabel('LBL_January', $langId),
            '02' => Label::getLabel('LBL_Februry', $langId),
            '03' => Label::getLabel('LBL_March', $langId),
            '04' => Label::getLabel('LBL_April', $langId),
            '05' => Label::getLabel('LBL_May', $langId),
            '06' => Label::getLabel('LBL_June', $langId),
            '07' => Label::getLabel('LBL_July', $langId),
            '08' => Label::getLabel('LBL_August', $langId),
            '09' => Label::getLabel('LBL_September', $langId),
            '10' => Label::getLabel('LBL_October', $langId),
            '11' => Label::getLabel('LBL_November', $langId),
            '12' => Label::getLabel('LBL_December', $langId),
        );
    }

    public static function getClassTypes($langId)
    {
        return array(
            self::CLASS_TYPE_GROUP => Label::getLabel('LBL_Group_Class', $langId),
            self::CLASS_TYPE_1_TO_1 => Label::getLabel('LBL_One_to_One_Class', $langId)
        );
    }

    public static function getMettingTools()
    {
        return array(
            self::MEETING_COMET_CHAT => Label::getLabel('LBL_Comet_Chat'),
            self::MEETING_ZOOM => Label::getLabel('LBL_Zoom'),
            self::MEETING_LESSON_SPACE => Label::getLabel('LBL_Lesson_Space'),
        );
    }

    public static function getBookingSlots()
    {
        return array(30, 45, 60, 90, 120);
    }
}
