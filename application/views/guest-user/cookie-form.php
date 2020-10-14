<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$cookieForm->setFormTagAttribute('id', 'cookieForm');
$cookieForm->setFormTagAttribute('class','form');
$cookieForm->developerTags['colClassPrefix'] = 'col-md-';
$cookieForm->developerTags['fld_default_col'] = 7;
$cookieForm->setFormTagAttribute('autocomplete', 'off');
$cookieForm->setFormTagAttribute('onsubmit', 'saveCookieSetting(this); return(false);');
?>
<section class="section section--gray section--page">
	<div class="container container--fixed">
		<div class="row justify-content-center">
			<div class="col-sm-12">
				<div class="box -skin">
					<div class="box__head -align-center">
						<h4 class="-border-title"><?php echo Label::getLabel('LBL_Privacy_Preference_Centre_for_cookies'); ?></h4>
					</div>
					<div class="box__body -padding-40">
						<div class="tabs-small tabs-offset tabs-scroll-js">
							<ul>
								<li class="is-active"><a href="javascript:void(0)" ><?php echo Label::getLabel('LBL_Your_Privacy'); ?></a></li>
								<li><a href="javascript:void(0)" ><?php echo Label::getLabel('LBL_Strictly_Necessary_Cookies'); ?></a></li>
								<li><a href="javascript:void(0)" ><?php echo Label::getLabel('LBL_Performance_Cookies'); ?></a></li>
								<li><a href="javascript:void(0)" ><?php echo Label::getLabel('LBL_Functional_Cookies'); ?></a></li>
								<li><a href="javascript:void(0)" ><?php echo Label::getLabel('LBL_Targeting_Cookies'); ?></a></li>
							</ul>
						</div>
						
						<?php echo $cookieForm->getFormHtml(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php //echo $cookieForm->getFormHtml();