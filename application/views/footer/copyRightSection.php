<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="section-copyright">
            <div class="container container--narrow">
                <div class="copyright">
                    <div class="footer__logo">
                        <a href="#">
                            <img src="<?php echo CommonHelper::generateFullUrl('Image','siteLogo',array(CommonHelper::getLangId()), CONF_WEBROOT_FRONT_URL); ?>" alt="">
                        </a>
                    </div>
                    <p><?php echo FatApp::getConfig("CONF_WEBSITE_NAME_" . CommonHelper::getLangId(), FatUtility::VAR_STRING, 'Copyright &copy; ' . date('Y') . ' <a href="javascript:void(0);">FATbit.com'); ?></p>
                </div>
            </div>
        </div>
