<?php

define('CONF_FORM_ERROR_DISPLAY_TYPE', Form::FORM_ERROR_TYPE_AFTER_FIELD);
define('CONF_FORM_REQUIRED_STAR_WITH', Form::FORM_REQUIRED_STAR_WITH_CAPTION);
define('CONF_FORM_REQUIRED_STAR_POSITION', Form::FORM_REQUIRED_STAR_POSITION_AFTER);
define('CONF_STATIC_FILE_CONTROLLERS', ['fonts', 'images', 'js', 'img', 'innovas', 'assetmanager', 'cache']);
FatApplication::getInstance()->setControllersForStaticFileServer(CONF_STATIC_FILE_CONTROLLERS);
$innova_settings = [
    'width' => '730', 'height' => '400', 'arrStyle' => '[["body",false,"","min-height:250px;"]]', 'groups' => ' [
        ["group1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
        ["group2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
        ["group3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog","YoutubeDialog"]],
		["group4", "", ["CharsDialog", "Line", "BRK", "ImageDialog", "MyCustomButton"]],
        ["group5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo"]]]',
    'fileBrowser' => '"' . CONF_WEBROOT_URL . 'innova/assetmanager/asset.php"'];
FatApp::setViewDataProvider('home/_partial/homePageSlides.php', ['Common', 'homePageSlides']);
FatApp::setViewDataProvider('home/_partial/homePageHowItWorks.php', ['Common', 'homePageHowItWorks']);
FatApp::setViewDataProvider('home/_partial/homePageSlidesAboveFooter.php', ['Common', 'homePageSlidesAboveFooter']);
FatApp::setViewDataProvider('header/languageArea.php', ['Common', 'headerLanguageArea']);
FatApp::setViewDataProvider('header/userLoginArea.php', ['Common', 'headerUserLoginArea']);
FatApp::setViewDataProvider('footer/languageCurrencySection.php', ['Common', 'languageCurrencySection']);
FatApp::setViewDataProvider('header/currencySection.php', ['Common', 'languageCurrencySection']);
FatApp::setViewDataProvider('header/languageSection.php', ['Common', 'languageCurrencySection']);
FatApp::setViewDataProvider('footer/socialMedia.php', ['Common', 'footerSocialMedia']);
FatApp::setViewDataProvider('guest-user/_partial/learner-social-media-signup.php', ['Common', 'learnerSocialMediaSignUp']);
FatApp::setViewDataProvider('teachers/_partial/teacherLeftFilters.php', ['Common', 'teacherLeftFilters']);
FatApp::setViewDataProvider('teachers/_partial/teacherTopFilters.php', ['Common', 'teacherLeftFilters']);
FatApp::setViewDataProvider('_partial/blogSidePanel.php', ['Common', 'blogSidePanelArea']);
FatApp::setViewDataProvider('_partial/blogTopFeaturedCategories.php', ['Common', 'blogTopFeaturedCategories']);
FatApp::setViewDataProvider('header/navigation.php', ['Navigation', 'headerNavigation']);
FatApp::setViewDataProvider('footer/navigation.php', ['Navigation', 'footerNavigation']);
FatApp::setViewDataProvider('navigationMore.php', ['Navigation', 'headerMoreNavigation']);
FatApp::setViewDataProvider('account/_partial/dashboardNavigation.php', ['Navigation', 'dashboardNavigation']);
FatApp::setViewDataProvider('_partial/dashboardRightNavigation.php', ['Navigation', 'dashboardRightNavigation']);
FatApp::setViewDataProvider('_partial/tutorListNavigation.php', ['Navigation', 'tutorListNavigation']);
FatApp::setViewDataProvider('footer/rightNavigation.php', ['Navigation', 'footerRightNavigation']);
FatApp::setViewDataProvider('footer/bottomNavigation.php', ['Navigation', 'footerBottomNavigation']);
FatApp::setViewDataProvider('home/_partial/languagesWithTeachersCount.php', ['Common', 'languagesWithTeachersCount']);
FatApp::setViewDataProvider('home/_partial/topRatedTeachers.php', ['Common', 'topRatedTeachers']);
FatApp::setViewDataProvider('home/_partial/upcomingScheduledLessons.php', ['Common', 'upcomingScheduledLessons']);
