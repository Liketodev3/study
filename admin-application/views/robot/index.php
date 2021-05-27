<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('class', 'web_form form_horizontal layout--');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = '12'; 
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');

?>

<div class='page'>
	<div class='fixed_container'>
		<div class="row">
			<div class="space">
				<div class="page__title">
					<div class="row">
						<div class="col--first col-lg-6">
							<span class="page__icon"><i class="ion-android-star"></i></span>
							<h5><?php echo Label::getLabel('LBL_Robots_File_Content', $adminLangId); ?> </h5>
							<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
						</div>
					</div>
				</div>
				<section class="section">
					<div class="sectionbody">
					<div class="tabs_panel_wrap">
	<?php echo $frm->getFormHtml();?>
					</div>
					</div>
				</section>
	
			</div>
		</div>
	</div>
</div>