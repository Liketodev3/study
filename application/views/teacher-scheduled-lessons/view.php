<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script type="text/javascript">
lessonId = '<?php echo $lessonId; ?>';
var canStartAlertLabel = '<?php echo Label::getLabel('LBL_Cannot_Start_The_lesson_Now!'); ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';
var activeMeatingTool =  '<?php FatApp::getConfig('CONF_ACTIVE_MEATING_TOOL', FatUtility::VAR_INT, 1); ?>';
var cometChatMeatingTool = '<?php FatApp::getConfig('CONF_MEATING_TOOL_COMET_CHAT', FatUtility::VAR_INT, 1); ?>';
var lessonspaceMeatingTool = '<?php FatApp::getConfig('CONF_MEATING_TOOL_LESSONSPACE', FatUtility::VAR_INT, 2); ?>';
</script>
<div id="listItems"></div>
