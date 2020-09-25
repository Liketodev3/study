<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script >
var lDetailId = '<?php echo $lDetailId; ?>';
var lessonId = '<?php echo $lessonId; ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';
var activeMeetingTool =  '<?php echo CommonHelper::getActiveMeetingTool(); ?>';
var cometChatMeetingTool = '<?php echo  CommonHelper::getCometChatMeetingTool(); ?>';
var lessonspaceMeetingTool = '<?php echo CommonHelper::getLessonspaceMeetingTool(); ?>';
</script>
<div class="box -padding-20">
	<div id="listItems"></div>
</div>