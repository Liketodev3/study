<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script type="text/javascript">
lessonId = '<?php echo $lessonId; ?>';
var canStartAlertLabel = '<?php echo Label::getLabel('LBL_Cannot_Start_The_lesson_Now!'); ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';
var activeMeetingTool =  '<?php echo FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_INT, 2); ?>';
var cometChatMeetingTool = '<?php echo FatApp::getConfig('CONF_MEETING_TOOL_COMET_CHAT', FatUtility::VAR_INT, 1); ?>';
var lessonspaceMeetingTool = '<?php echo FatApp::getConfig('CONF_MEETING_TOOL_LESSONSPACE', FatUtility::VAR_INT, 2); ?>';
</script>
<div id="listItems"></div>
