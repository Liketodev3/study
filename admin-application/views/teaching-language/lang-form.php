<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$langFrm->setFormTagAttribute('class', 'web_form form_horizontal layout--'.$formLayout);
$langFrm->setFormTagAttribute('onsubmit', 'setupLangTeachingLanguage(this); return(false);');
$langFrm->developerTags['colClassPrefix'] = 'col-md-';
$langFrm->developerTags['fld_default_col'] = 12; 	

?>
<section class="section">
<div class="sectionhead">
   
    <h4><?php echo Label::getLabel('LBL_Teaching_language_Setup',$adminLangId); ?></h4>
</div>
<div class="sectionbody space">
<div class="row">	

<div class="col-sm-12">
	<h1><?php //echo Label::getLabel('LBL_TeachingLanguage_Setup',$adminLangId); ?></h1>
	<div class="tabs_nav_container responsive flat">
		<ul class="tabs_nav">
			<li><a href="javascript:void(0);" onclick="editTeachingLanguageForm(<?php echo $tLangId ?>);"><?php echo Label::getLabel('LBL_General',$adminLangId); ?></a></li>
			<?php 
			if ($tLangId > 0) {
				foreach($languages as $langId=>$langName){?>
					<li><a class="<?php echo ($lang_id == $langId)?'active':''?>" href="javascript:void(0);" onclick="editTeachingLanguageLangForm(<?php echo $tLangId ?>, <?php echo $langId;?>);"><?php echo Label::getLabel('LBL_'.$langName,$adminLangId);?></a></li>
				<?php }
				}
			?>
			<li><a href="javascript:void(0);" <?php if( $tLangId > 0 ){?> onclick="mediaForm(<?php echo $tLangId ?>);" <?php }?>><?php echo Label::getLabel('LBL_Media',$adminLangId);?></a></li>
		</ul>
		<div class="tabs_panel_wrap">
			<div class="tabs_panel">
				<?php echo $langFrm->getFormHtml(); ?>
			</div>
		</div>
	</div>	
</div>
</div>
</div>
</section>