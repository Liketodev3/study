<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('id', 'coursesFrm');
$frm->setFormTagAttribute('enctype','multipart/form-data');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
$lesson_plan = $frm->getField('lesson_plan');
$lesson_plan->value = '<div class="field-set"><div class="caption-wraper"><label class="field_label">'.Label::getLabel('LBL_Select_Lesson_Plan').'<span class="spn_must_field">*</span></label></div><div class="field-wraper"><div class="field_cover"><a href="javascript:void(0);" class="btn btn--primary" onclick="getListingLessonPlans('.$courseId.');">'.Label::getLabel('LBL_Select_Lesson_Plans').'</a></div></div></div>';
$submit = $frm->getField('submit');
$submit->developerTags['col'] =2;
$submit = $frm->getField('submit_exit');
$submit->developerTags['col'] =2;
?>
<div class="box -padding-20">
	<!--page-head start here-->
	 
	    <div class="d-flex justify-content-between align-items-center">
			 <div><h4>Add Course</h4></div>
			 <div><a class="btn btn--small" href="javascript:void(0);" onclick="searchCourses();">Cancel</a></div>
		</div>
		<span class="-gap"></span>
	 <!--page-head end here-->


	<?php echo $frm->getFormHtml(); ?>
</div>
<script >
	$(document).ready(function(){
		$('#tcourse_tags').tagsInput();
	});
</script>
