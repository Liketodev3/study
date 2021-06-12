<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
</div>
<footer class="footer">
    <section class="section section--footer">
        <div class="container container--narrow">
      
            <?php $this->includeTemplate('footer/footerRowOne.php',['siteLangId'=>$siteLangId]);  ?>
            <?php $this->includeTemplate('footer/footerRowSecond.php',['siteLangId'=>$siteLangId]);  ?>
            <?php $this->includeTemplate('footer/footerRowThird.php',['siteLangId'=>$siteLangId]);  ?>
        </div>
    </section>
    <?php $this->includeTemplate('footer/copyRightSection.php');  ?>

</footer>

<?php if (FatApp::getConfig('CONF_ENABLE_COOKIES', FatUtility::VAR_INT, 1) && !CommonHelper::getUserCookiesEnabled()) { ?>
<div class="cc-window cc-banner cc-type-info cc-theme-block cc-bottom cookie-alert no-print">
    <?php if (FatApp::getConfig('CONF_COOKIES_TEXT_'.$siteLangId, FatUtility::VAR_STRING, '')) { ?>
	<div class="box-cookies">
		<span id="cookieconsent:desc" class="cc-message">
		<?php echo FatUtility::decodeHtmlEntities(FatApp::getConfig('CONF_COOKIES_TEXT_'.$siteLangId, FatUtility::VAR_STRING, ''));?>
		<a href="<?php echo CommonHelper::generateUrl('cms', 'view', array(FatApp::getConfig('CONF_COOKIES_BUTTON_LINK', FatUtility::VAR_INT)));?>"><?php echo Label::getLabel('LBL_Read_More', $siteLangId);?></a></span>
		</span>
		<span class="cc-close cc-cookie-accept-js" ><?php echo Label::getLabel('LBL_Accept_Cookies', $siteLangId);?></span>
		<a href="javascript:void(0)" class="cc-close" onClick="getCookieConsentForm()"><?php echo Label::getLabel('LBL_Choose_Cookies', $siteLangId);?></a>
	</div>
	<?php } ?>
</div>
<?php }?>

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
</body>

</html>