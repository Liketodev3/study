<?php 

defined('SYSTEM_INIT') or die('Invalid Usage.');
$form->setFormTagAttribute('class', 'web_form form_horizontal');
$form->setFormTagAttribute('onsubmit', 'setupPriceSlap(this); return(false);');
$form->developerTags['colClassPrefix'] = 'col-md-';
$form->developerTags['fld_default_col'] = 12; 	

$minField = $form->getField('prislab_min');
$maxField = $form->getField('prislab_max');

?>

<section class="section">
<div class="sectionhead">
    <h5><?php echo Label::getLabel('LBL_Price_Slab_Setup',$adminLangId); ?></h5>
</div>
<div class="sectionbody space">
<div class="row">	

<div class="col-sm-12">
	<h1><?php //echo Label::getLabel('LBL_Testimonial_Setup',$adminLangId); ?></h1>
	<div class="tabs_nav_container responsive flat">
		<div class="tabs_panel_wrap">
			<div class="tabs_panel">
				<?php echo $form->getFormHtml(); ?>
			</div>
		</div>
	</div>
</div>
