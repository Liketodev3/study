<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script >
lessonId = '<?php echo $lessonId; ?>';
var canStartAlertLabel = '<?php echo Label::getLabel('LBL_Cannot_Start_The_lesson_Now!'); ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';
const STATUS_SCHEDULED =  <?php echo ScheduledLesson::STATUS_SCHEDULED; ?>;
const STATUS_COMPLETED =  <?php echo ScheduledLesson::STATUS_COMPLETED; ?>;
const STATUS_ISSUE_REPORTED =  <?php echo ScheduledLesson::STATUS_ISSUE_REPORTED; ?>;

var activeMeetingTool =  '<?php echo CommonHelper::getActiveMeetingTool(); ?>';
var cometChatMeetingTool = '<?php echo  CommonHelper::getCometChatMeetingTool(); ?>';
var lessonspaceMeetingTool = '<?php echo CommonHelper::getLessonspaceMeetingTool(); ?>';
</script>
<div id="listItems"></div>
