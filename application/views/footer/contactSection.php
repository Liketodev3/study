<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="col-md-6 col-lg-3">
    <div class="footer-group toggle-group">
        <div class="footer__group-title toggle-trigger-js">
            <h5><?php echo Label::getLabel('LBL_Contact',$siteLangId); ?></h5>
        </div>
        <div class="footer__group-content toggle-target-js">
            <div class="bullet-list">
                <ul class="footer_contact_details">
                    <li>
                        <svg class="icon icon--pin">
                            <use xlink:href="images/sprite.yo-coach.svg#pin"></use>
                        </svg>
                        <span><?php echo FatApp::getConfig('CONF_ADDRESS_' . CommonHelper::getLangId(), null, ''); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>