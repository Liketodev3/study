<?php
define('CONF_FORM_ERROR_DISPLAY_TYPE', Form::FORM_ERROR_TYPE_AFTER_FIELD);
define('CONF_FORM_REQUIRED_STAR_WITH', Form::FORM_REQUIRED_STAR_WITH_CAPTION);
define('CONF_FORM_REQUIRED_STAR_POSITION', Form::FORM_REQUIRED_STAR_POSITION_AFTER);
define('CONF_STATIC_FILE_CONTROLLERS', ['fonts', 'images', 'js', 'img', 'innovas', 'assetmanager', 'cache']);
define('LANG_CODES_ARR', Language::getAllCodesAssoc());

FatApplication::getInstance()->setControllersForStaticFileServer(CONF_STATIC_FILE_CONTROLLERS);

$innova_settings  = array(
    'width' => '730', 'height' => '400', 'arrStyle' => '[["body",false,"","min-height:250px;"]]',  'groups' => ' [
        ["group1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
        ["group2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
        ["group3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog","YoutubeDialog"]],
		["group4", "", ["CharsDialog", "Line", "BRK", "ImageDialog", "MyCustomButton"]],
        ["group5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo"]]]',
    'fileBrowser' => '"' . CONF_WEBROOT_URL . 'innova/assetmanager/asset.php"'
);

FatApp::setViewDataProvider('home/_partial/homePageSlides.php', array('Common', 'homePageSlides'));
FatApp::setViewDataProvider('home/_partial/homePageHowItWorks.php', array('Common', 'homePageHowItWorks'));
FatApp::setViewDataProvider('home/_partial/homePageSlidesAboveFooter.php', array('Common', 'homePageSlidesAboveFooter'));

FatApp::setViewDataProvider('_partial/header/headerLanguageArea.php', array('Common', 'headerLanguageArea'));
FatApp::setViewDataProvider('_partial/header/headerUserLoginArea.php', array('Common', 'headerUserLoginArea'));

FatApp::setViewDataProvider('_partial/footer/footerLanguageCurrencySection.php', array('Common', 'footerLanguageCurrencySection'));

FatApp::setViewDataProvider('_partial/header/headerLanguageSection.php', array('Common', 'footerLanguageCurrencySection'));
FatApp::setViewDataProvider('_partial/header/headerCurrencySection.php', array('Common', 'footerLanguageCurrencySection'));

FatApp::setViewDataProvider('_partial/footer/footerSocialMedia.php', array('Common', 'footerSocialMedia'));

FatApp::setViewDataProvider('guest-user/_partial/learner-social-media-signup.php', array('Common', 'learnerSocialMediaSignUp'));

FatApp::setViewDataProvider('teachers/_partial/teacherLeftFilters.php', array('Common', 'teacherLeftFilters'));
FatApp::setViewDataProvider('teachers/_partial/teacherTopFilters.php', array('Common', 'teacherLeftFilters'));
FatApp::setViewDataProvider('_partial/blogSidePanel.php', array('Common', 'blogSidePanelArea'));
FatApp::setViewDataProvider('_partial/blogTopFeaturedCategories.php', array('Common', 'blogTopFeaturedCategories'));
FatApp::setViewDataProvider('_partial/headerNavigation.php', array('Navigation', 'headerNavigation'));
FatApp::setViewDataProvider('_partial/footerNavigation.php', array('Navigation', 'footerNavigation'));
FatApp::setViewDataProvider('_partial/headerNavigationMore.php', array('Navigation', 'headerMoreNavigation'));

FatApp::setViewDataProvider('account/_partial/dashboardNavigation.php', array('Navigation', 'dashboardNavigation'));
FatApp::setViewDataProvider('_partial/dashboardRightNavigation.php', array('Navigation', 'dashboardRightNavigation'));
FatApp::setViewDataProvider('_partial/tutorListNavigation.php', array('Navigation', 'tutorListNavigation'));
FatApp::setViewDataProvider('_partial/footerRightNavigation.php', array('Navigation', 'footerRightNavigation'));
FatApp::setViewDataProvider('_partial/footerBottomNavigation.php', array('Navigation', 'footerBottomNavigation'));
FatApp::setViewDataProvider('home/_partial/languagesWithTeachersCount.php', array('Common', 'languagesWithTeachersCount'));
FatApp::setViewDataProvider('home/_partial/topRatedTeachers.php', array('Common', 'topRatedTeachers'));
FatApp::setViewDataProvider('home/_partial/upcomingScheduledLessons.php', array('Common', 'upcomingScheduledLessons'));
