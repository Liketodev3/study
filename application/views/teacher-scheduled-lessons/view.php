<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script >
lessonId = '<?php echo $lessonId; ?>';
var canStartAlertLabel = '<?php echo Label::getLabel('LBL_Cannot_Start_The_lesson_Now!'); ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';
</script>
<div id="listItems"></div>
