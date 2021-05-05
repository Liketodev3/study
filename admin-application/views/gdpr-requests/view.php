<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="repeatedrow">
	<?php /* if($data['tlreview_status'] !== TeacherLessonReview::STATUS_PENDING){ */ ?>
    <br>
	<h3>Change Status</h3>
	<div class="rowbody space">
		<div class="listview">
			<?php 
			$frm->setFormTagAttribute('class', 'web_form form_horizontal');
			$frm->setFormTagAttribute('onsubmit', 'updateStatus(this); return(false);');
			$frm->developerTags['colClassPrefix'] = 'col-sm-';
			$frm->developerTags['fld_default_col'] = '10';
			echo $frm->getFormHtml();?>
		</div>
	</div>	
	<?php /* } */ ?>
</div>