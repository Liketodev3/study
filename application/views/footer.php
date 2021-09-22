<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
</div>
<footer class="footer">
    <section class="section section--footer">
        <div class="container container--narrow">
            <?php $this->includeTemplate('footer/footerRowOne.php', ['siteLangId' => $siteLangId]);  ?>
            <?php $this->includeTemplate('footer/footerRowSecond.php', ['siteLangId' => $siteLangId]);  ?>
            <?php $this->includeTemplate('footer/footerRowThird.php', ['siteLangId' => $siteLangId]);  ?>
        </div>
    </section>
    <?php $this->includeTemplate('footer/copyRightSection.php');  ?>
</footer>

<?php if (FatApp::getConfig('CONF_ENABLE_COOKIES', FatUtility::VAR_INT, 1) && !CommonHelper::getUserCookiesEnabled()) { ?>
    <div class="cc-window cc-banner cc-type-info cc-theme-block cc-bottom cookie-alert no-print">
        <?php if (FatApp::getConfig('CONF_COOKIES_TEXT_' . $siteLangId, FatUtility::VAR_STRING, '')) { ?>
            <div class="box-cookies">
                <span id="cookieconsent:desc" class="cc-message">
                    <?php echo FatUtility::decodeHtmlEntities(FatApp::getConfig('CONF_COOKIES_TEXT_' . $siteLangId, FatUtility::VAR_STRING, '')); ?>
                    <?php  $readMorePage =  FatApp::getConfig('CONF_COOKIES_BUTTON_LINK', FatUtility::VAR_INT);
                    if ($readMorePage) { ?>
                        <a href="<?php echo CommonHelper::generateUrl('cms', 'view', [$readMorePage]); ?>"><?php echo Label::getLabel('LBL_Read_More', $siteLangId); ?></a></span>
            <?php } ?>
            </span>
            <span class="cc-close cc-cookie-accept-js"><?php echo Label::getLabel('LBL_Accept_Cookies', $siteLangId); ?></span>
            <a href="javascript:void(0)" class="cc-close" onClick="getCookieConsentForm()"><?php echo Label::getLabel('LBL_Choose_Cookies', $siteLangId); ?></a>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<?php if (FatApp::getConfig('CONF_ENABLE_LIVECHAT', FatUtility::VAR_STRING, '')) {
    echo FatApp::getConfig('CONF_LIVE_CHAT_CODE', FatUtility::VAR_STRING, '');
}
if (FatApp::getConfig('CONF_SITE_TRACKER_CODE', FatUtility::VAR_STRING, '') && !empty($cookieConsent[UserCookieConsent::COOKIE_STATISTICS_FIELD])) {
    echo FatApp::getConfig('CONF_SITE_TRACKER_CODE', FatUtility::VAR_STRING, '');
} ?>
<div class="loading-wrapper" style="display: none;">
    <div class="loading">
        <div class="inner rotate-one"></div>
        <div class="inner rotate-two"></div>
        <div class="inner rotate-three"></div>
    </div>
</div>
<?php
$errorClass = '';
if (Message::getMessageCount() > 0) {
    $errorClass = " alert--success";
}

if (Message::getErrorCount() > 0) {
    $errorClass = " alert--danger";
}

if (Message::getDialogCount() > 0) {
    $errorClass = " alert--info";
}

if (Message::getInfoCount() > 0) {
    $errorClass = " alert--warning";
}
?>
<?php if (!empty($errorClass)) { ?>
    <div id="mbsmessage" class="alert--positioned-top-full alert <?php echo $errorClass; ?>">
        <div class="close" src="<?php echo CONF_WEBROOT_URL . 'img/mbsmessage/close.gif'; ?>"></div>
        <div>
            <div class="content">
                <?php echo html_entity_decode(Message::getHtml()); ?>
            </div>
        </div>
    </div>
    <script>
        $.mbsmessage.settings.initialized = true;
        $("document").ready(function() {
            if (CONF_AUTO_CLOSE_SYSTEM_MESSAGES == 1) {
                var time = CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES * 1000;
                setTimeout(function() {
                    $(document).trigger('close.mbsmessage');
                }, time);
            }
            $("#mbsmessage .close").click(function() {
                $(document).trigger('close.mbsmessage');
            });
        });
    </script>
<?php } ?>

</body>

</html>