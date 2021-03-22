<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setupCourseCategory(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12; 	


?>

<section class="section">
<div class="sectionhead">
   
    <h4><?php echo Label::getLabel('LBL_Course_Category_Setup',$adminLangId); ?></h4>
</div>
<div class="sectionbody space">
<div class="row">	

<div class="col-sm-12">
	<h1><?php //echo Label::getLabel('LBL_Testimonial_Setup',$adminLangId); ?></h1>
	<div class="tabs_nav_container responsive flat">
		<ul class="tabs_nav">
			<li><a class="active" href="javascript:void(0)" onclick="editCourseCategoryForm(<?php echo $cCategoryId ?>);"><?php echo Label::getLabel('LBL_General',$adminLangId); ?></a></li>
			<?php 
			$inactive=($cCategoryId==0)?'fat-inactive':'';	
			foreach($languages as $langId=>$langName){?>
				<li class="<?php echo $inactive;?>"><a href="javascript:void(0);" <?php if($cCategoryId>0){?> onclick="editCourseCategoryLangForm(<?php echo $cCategoryId ?>, <?php echo $langId;?>);" <?php }?>><?php echo $langName;?></a></li>
			<?php } ?>
			<!--li class="<?php echo $inactive;?>"><a href="javascript:void(0);" <?php if($cCategoryId>0){?> onclick="CourseCategoryMediaForm(<?php echo $cCategoryId ?>);" <?php }?>><?php echo Label::getLabel('LBL_Media',$adminLangId);?></a></li-->
		</ul>
		<div class="tabs_panel_wrap">
			<div class="tabs_panel">
				<?php echo $frm->getFormHtml(); ?>
			</div>
		</div>
	</div>
</div>
