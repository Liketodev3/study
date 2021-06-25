<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if (isset($includeEditor) && $includeEditor) { ?>
    <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/innovaeditor.js"></script>
    <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/common/webfont.js"></script>
<?php } ?>
<?php if (FatApp::getConfig('CONF_ENABLE_PWA', FatUtility::VAR_BOOLEAN, false)) { ?>
    <link rel="manifest" href="<?php echo CommonHelper::generateUrl('MyApp', 'PwaManifest'); ?>">
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("<?php echo CONF_WEBROOT_FRONTEND; ?>sw.js");
        }
    </script>
<?php } ?>
</head>
<?php
$layoutDirection = CommonHelper::getLayoutDirection();
?>

<body class="<?php echo $htmlBodyClassesString; ?>" <?php echo (strtolower($layoutDirection) == 'rtl') ? 'dir="rtl"' : ""; ?>>
<?php
$autoRestartOn =  FatApp::getConfig('conf_auto_restore_on', FatUtility::VAR_INT, 1);
if($autoRestartOn == applicationConstants::YES && CommonHelper::demoUrl()) {
    $this->includeTemplate( 'restore-system/header-bar.php');
}
