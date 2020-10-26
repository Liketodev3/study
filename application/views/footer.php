<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
</div><!-- div id=body class=body ends here -->
<footer id="footer" class="footer">
    <section class="section footer-bottom section--black">
        <div class="container container--fixed">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <?php $this->includeTemplate('_partial/footer/footerSocialMedia.php'); ?>

                    <?php $this->includeTemplate('_partial/footer/footerLanguageCurrencySection.php');
                    ?>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-3">
                    <div class="toggle-group">
                        <h5 class="toggle__trigger toggle__trigger-js"><?php echo FatApp::getConfig('CONF_WEBSITE_NAME_' . CommonHelper::getLangId(), null, ''); ?></h5>
                        <div class="toggle__target toggle__target-js">
                            <ul class="links--vertical">
                                <?php $this->includeTemplate('_partial/footerNavigation.php'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-3">
                    <div class="toggle-group">
                        <h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_Teachers'); ?></h5>
                        <div class="toggle__target toggle__target-js">
                            <ul class="links--vertical">
                                <!--<li><a href="#">English Tutors</a></li>
                                <li><a href="#">Spanish Tutors</a></li>
                                <li><a href="#">French Tutors</a></li>
                                <li><a href="#">Japanese Tutors</a></li>
                                <li><a href="#">Arabic Tutors</a></li>
                                <li><a href="#">All Tutors</a></li>-->
                                <?php $this->includeTemplate('_partial/tutorListNavigation.php'); ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-3">
                    <div class="toggle-group">
                        <h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_More_Links'); ?></h5>
                        <div class="toggle__target toggle__target-js">
                            <ul class="links--vertical">
                                <!--<li><a href="#">Learn English</a></li>
                                <li><a href="#">Learn Chinese (Mandarin)</a></li>
                                <li><a href="#">Learn French</a></li>
                                <li><a href="#">Learn Spanish</a></li>
                                <li><a href="#">Learn German</a></li>
                                <li><a href="#">More Languages</a></li>-->
                                <?php $this->includeTemplate('_partial/footerRightNavigation.php'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-8">
                    <div class="toggle-group">
                        <h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_Contact_Info'); ?></h5>
                        <div class="toggle__target toggle__target-js">
                            <ul class="links--vertical footer_contact_details">
                                <?php if (FatApp::getConfig('CONF_CONTACT_EMAIL', null, '')) : ?>
                                    <li>
                                        <img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/retina/contact-icon01.svg">
                                        <?php echo FatApp::getConfig('CONF_CONTACT_EMAIL', null, ''); ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (FatApp::getConfig('CONF_SITE_PHONE', null, '')) : ?>
                                    <li>
                                        <img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/retina/contact-icon02.svg">
                                        <?php echo Label::getLabel('LBL_Call_Us'); ?>: <?php echo FatApp::getConfig('CONF_SITE_PHONE', null, ''); ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (FatApp::getConfig('CONF_ADDRESS_' . CommonHelper::getLangId(), null, '')) : ?>
                                    <li>
                                        <img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/retina/contact-icon03.svg">
                                        <?php echo FatApp::getConfig('CONF_ADDRESS_' . CommonHelper::getLangId(), null, ''); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-inline -singleTopBorder">
            <div class="container container--fixed">
                <ul class="inline-listing">
                    <?php $this->includeTemplate('_partial/footerBottomNavigation.php'); ?>
                </ul>

                <ul class="fineprint-listing">
                    <li>
                        <p>
                            <?php if (CommonHelper::demoUrl()) {
                                echo CommonHelper::replaceStringData(Label::getLabel('LBL_COPYRIGHT_TEXT', CommonHelper::getLangId()), ['{YEAR}' => '&copy; ' . date("Y"), '{PRODUCT}' => '<a target="_blank" href="https://yo-coach.com">Yo!Coach</a>', '{OWNER}' => '<a target="_blank" href="https://www.fatbit.com/">FATbit Technologies</a>']);
                            } else {
                                echo Label::getLabel('LBL_COPYRIGHT', CommonHelper::getLangId()) . ' &copy; ' . date("Y ") . FatApp::getConfig("CONF_WEBSITE_NAME_" . CommonHelper::getLangId(), FatUtility::VAR_STRING);
                            } ?>
                        </p>
                    </li>
                    <li>
                        <p><?php //echo MyDate::getDateAndTimeDisclaimer();
                            ?></p>
                    </li>
                </ul>
            </div>
        </div>
    </section>

</footer>
<?php if (FatApp::getConfig('CONF_ENABLE_COOKIES', FatUtility::VAR_INT, 1) && !CommonHelper::getUserCookiesEnabled()) { ?>
    <div class="cc-window cc-banner cc-type-info cc-theme-block cc-bottom cookie-alert no-print">
        <?php if (FatApp::getConfig('CONF_COOKIES_TEXT_' . $siteLangId, FatUtility::VAR_STRING, '')) { ?>
            <div class="box-cookies">
                <span id="cookieconsent:desc" class="cc-message">
                    <?php echo FatUtility::decodeHtmlEntities(FatApp::getConfig('CONF_COOKIES_TEXT_' . $siteLangId, FatUtility::VAR_STRING, '')); ?>
                    <a href="<?php echo CommonHelper::generateUrl('cms', 'view', array(FatApp::getConfig('CONF_COOKIES_BUTTON_LINK', FatUtility::VAR_INT))); ?>"><?php echo Label::getLabel('LBL_Read_More', $siteLangId); ?></a></span>
                <span class="cc-close cc-cookie-accept-js"><?php echo Label::getLabel('LBL_Accept_Cookies', $siteLangId); ?></span>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<!--footer end here-->

<div class="loading-wrapper" style="display: none;">
    <div class="loading">
        <div class="inner rotate-one"></div>
        <div class="inner rotate-two"></div>
        <div class="inner rotate-three"></div>
    </div>
</div>

<a href="javascript:void(0)" class="scroll-top-js gototop" title="Back to Top"></a>
</body>

</html>
<?php

if (FatApp::getConfig('CONF_ENABLE_LIVECHAT', FatUtility::VAR_STRING, '')) {
    echo FatApp::getConfig('CONF_LIVE_CHAT_CODE', FatUtility::VAR_STRING, '');
}
if (FatApp::getConfig('CONF_SITE_TRACKER_CODE', FatUtility::VAR_STRING, '')) {
    echo FatApp::getConfig('CONF_SITE_TRACKER_CODE', FatUtility::VAR_STRING, '');
}

/* $autoRestartOn =  FatApp::getConfig('conf_auto_restore_on', FatUtility::VAR_INT, 1);
if($autoRestartOn == applicationConstants::YES && CommonHelper::demoUrl()) {
    $this->includeTemplate( 'restore-system/page-content.php');
} */

?>