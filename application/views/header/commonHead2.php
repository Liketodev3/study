<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if (isset($includeEditor) && $includeEditor) { ?>
    <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/innovaeditor.js"></script>
    <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/common/webfont.js"></script>
<?php } ?>
<?php if (FatApp::getConfig('CONF_ENABLE_PWA', FatUtility::VAR_BOOLEAN, false)) { ?>
    <link rel="manifest" href="<?php echo CommonHelper::generateUrl('MyApp', 'PwaManifest'); ?>">
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("<?php echo CONF_WEBROOT_URL; ?>sw.js");
        }
    </script>
<?php } ?>
</head>
<?php
$layoutDirection = CommonHelper::getLayoutDirection();
?>

<body class="<?php echo $htmlBodyClassesString; ?>" <?php echo (strtolower($layoutDirection) == 'rtl') ? 'dir="rtl"' : ""; ?>>
    <?php $errorClass = '';
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
    <div class="system_message alert--positioned-top-full alert <?php echo $errorClass; ?>" style="display:none;">
        <a class="closeMsg" href="javascript:void(0)"></a>
        <div class="content">
            <?php
            $haveMsg = false;
            if (Message::getMessageCount() || Message::getErrorCount() || Message::getDialogCount() || Message::getInfoCount()) {
                $haveMsg = true;
                echo html_entity_decode(Message::getHtml());
            } ?>
        </div>
    </div>
    <?php /* if( $haveMsg ){ */ ?>
    <script>
        $("document").ready(function() {
            if (CONF_AUTO_CLOSE_SYSTEM_MESSAGES == 1) {
                var time = CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES * 1000;
                setTimeout(function() {
                    $.systemMessage.close();
                }, time);
            }
        });
    </script>
    <?php /* } */ ?>