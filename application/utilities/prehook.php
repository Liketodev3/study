<?php
define('CONF_FORM_ERROR_DISPLAY_TYPE', Form::FORM_ERROR_TYPE_AFTER_FIELD);
define('CONF_FORM_REQUIRED_STAR_WITH', Form::FORM_REQUIRED_STAR_WITH_CAPTION);
define('CONF_FORM_REQUIRED_STAR_POSITION', Form::FORM_REQUIRED_STAR_POSITION_AFTER);
define('CONF_STATIC_FILE_CONTROLLERS', ['fonts', 'images', 'js', 'img', 'innovas', 'assetmanager', 'cache']);

FatApplication::getInstance()->setControllersForStaticFileServer(CONF_STATIC_FILE_CONTROLLERS);

$innova_settings  = array('width'=>'730', 'height'=>'400','arrStyle'=>'[["body",false,"","min-height:250px;"]]',  'groups'=>' [
        ["group1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
        ["group2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
        ["group3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog","YoutubeDialog"]],
		["group4", "", ["CharsDialog", "Line", "BRK", "ImageDialog", "MyCustomButton"]],
        ["group5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo"]]]',
        'fileBrowser'=> '"'.CONF_WEBROOT_URL.'innova/assetmanager/asset.php"');

FatApp::setViewDataProvider('home/_partial/homePageSlides.php', array('Common', 'homePageSlides'));
FatApp::setViewDataProvider('home/_partial/homePageHowItWorks.php', array('Common', 'homePageHowItWorks'));
FatApp::setViewDataProvider('home/_partial/homePageSlidesAboveFooter.php', array('Common', 'homePageSlidesAboveFooter'));

FatApp::setViewDataProvider('header/languageArea.php', array('Common', 'headerLanguageArea'));
FatApp::setViewDataProvider('header/userLoginArea.php', array('Common', 'headerUserLoginArea'));

FatApp::setViewDataProvider('footer/languageCurrencySection.php', array('Common', 'languageCurrencySection'));

FatApp::setViewDataProvider('header/currencySection.php', array('Common', 'languageCurrencySection'));
FatApp::setViewDataProvider('header/languageSection.php', array('Common', 'languageCurrencySection'));

FatApp::setViewDataProvider('footer/socialMedia.php', array('Common', 'footerSocialMedia'));

FatApp::setViewDataProvider('guest-user/_partial/learner-social-media-signup.php', array('Common', 'learnerSocialMediaSignUp'));

FatApp::setViewDataProvider('teachers/_partial/teacherLeftFilters.php', array('Common', 'teacherLeftFilters'));
FatApp::setViewDataProvider('teachers/_partial/teacherTopFilters.php', array('Common', 'teacherLeftFilters'));
FatApp::setViewDataProvider('_partial/blogSidePanel.php', array('Common', 'blogSidePanelArea'));
FatApp::setViewDataProvider('_partial/blogTopFeaturedCategories.php', array('Common', 'blogTopFeaturedCategories'));
FatApp::setViewDataProvider('header/navigation.php', array('Navigation', 'headerNavigation'));
FatApp::setViewDataProvider('footer/navigation.php', array('Navigation', 'footerNavigation'));
FatApp::setViewDataProvider('navigationMore.php', array('Navigation', 'headerMoreNavigation'));

FatApp::setViewDataProvider('account/_partial/dashboardNavigation.php', array('Navigation', 'dashboardNavigation'));
FatApp::setViewDataProvider('_partial/dashboardRightNavigation.php', array('Navigation', 'dashboardRightNavigation'));
FatApp::setViewDataProvider('_partial/tutorListNavigation.php', array('Navigation', 'tutorListNavigation'));
FatApp::setViewDataProvider('footer/rightNavigation.php', array('Navigation', 'footerRightNavigation'));
FatApp::setViewDataProvider('footer/bottomNavigation.php', array('Navigation', 'footerBottomNavigation'));
FatApp::setViewDataProvider('home/_partial/languagesWithTeachersCount.php', array('Common', 'languagesWithTeachersCount'));
FatApp::setViewDataProvider('home/_partial/topRatedTeachers.php', array('Common', 'topRatedTeachers'));
FatApp::setViewDataProvider('home/_partial/upcomingScheduledLessons.php', array('Common', 'upcomingScheduledLessons'));
