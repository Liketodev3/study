<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="section-copyright">
    <div class="container container--narrow">
        <div class="copyright">
            <div class="footer__logo">
                <a href="#">
                    <img src="<?php echo CommonHelper::generateFullUrl('Image', 'siteLogo', array(CommonHelper::getLangId()), CONF_WEBROOT_FRONT_URL); ?>" alt="">
                </a>
            </div>
            <p>
                <?php
                if (CommonHelper::demoUrl()) {
                    echo CommonHelper::replaceStringData(Label::getLabel('LBL_COPYRIGHT_TEXT', CommonHelper::getLangId()), ['{YEAR}' => '&copy; ' . date("Y"), '{PRODUCT}' => '<a target="_blank"  href="https://yo-coach.com">Yo!Coach</a>', '{OWNER}' => '<a target="_blank"  class="underline color-primary" href="https://www.fatbit.com/">FATbit Technologies</a>']);
                } else {
                    echo Label::getLabel('LBL_COPYRIGHT', CommonHelper::getLangId()) . ' &copy; ' . date("Y ") . FatApp::getConfig("CONF_WEBSITE_NAME_" . CommonHelper::getLangId(), FatUtility::VAR_STRING);
                }
                ?></p>
        </div>
    </div>
</div>