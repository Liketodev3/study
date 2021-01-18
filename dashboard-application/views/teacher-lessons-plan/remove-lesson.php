<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20">
	<h6><?php echo Label::getLabel('LBL_NOTE:_Are_you_sure!_By_Removing_this_lesson_will_also_unlink_it_from_courses_and_scheduled_lessons!'); ?></h6>
	<a href="javascript:void(0);" class="btn btn--small btn--secondary" onclick="removeLessonSetup('<?php echo $lessonPlanId; ?>')"><?php echo Label::getLabel('LBL_OK'); ?></a>
	<a href="javascript:void(0);" class="btn btn--small" onclick="$.facebox.close();"><?php echo Label::getLabel('LBL_Cancel'); ?></a>
</div>
