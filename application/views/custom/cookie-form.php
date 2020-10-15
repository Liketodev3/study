<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$cookieForm->setFormTagAttribute('id', 'cookieForm');
$cookieForm->setFormTagAttribute('class', 'form');
$cookieForm->developerTags['colClassPrefix'] = 'col-md-';
$cookieForm->developerTags['fld_default_col'] = 7;
$cookieForm->setFormTagAttribute('autocomplete', 'off');
$cookieForm->setFormTagAttribute('onsubmit', 'saveCookieSetting(this); return false;');
?>

<div class="tab-container cookie-consent">

	<div class="tabs-small tabs-offset tabs-scroll-js">
		<ul>
			<li><a href="#" class="tab-a is-active" data-id="tab_necessary"><?php echo Label::getLabel('LBL_Necessary'); ?></a></li>
			<li><a href="#" class="tab-a" data-id="tab_preferences"><?php echo Label::getLabel('LBL_Preferences'); ?></a></li>
			<li><a href="#" class="tab-a" data-id="tab_statistics"><?php echo Label::getLabel('LBL_Statistics'); ?></a></li>
		</ul>
	</div>
	<!--end of tab-menu-->
	<?php echo $cookieForm->getFormTag(); ?>
	<div class="tab tab-active" data-id="tab_necessary">
	    <div class="tab-heading">
		 	<h2><?php echo Label::getLabel('LBL_Necessary'); ?></h2>
			<div class="field_cover">
				<?php echo $cookieForm->getFieldHtml(UserCookieConsent::COOKIE_NECESSARY_FIELD); ?>
			</div>
		</div>
		<p><?php echo Label::getLabel('LBL_NECESSARY_COOKIE_DESCRIPTION_TEXT'); ?></p>
	</div>
	<div class="tab " data-id="tab_preferences">
	    <div class="tab-heading">
		 	<h2><?php echo Label::getLabel('LBL_Preferences'); ?></h2>
			<div class="field_cover">
				<?php echo $cookieForm->getFieldHtml(UserCookieConsent::COOKIE_PREFERENCES_FIELD); ?>
			</div>
		</div>
		<p><?php echo Label::getLabel('LBL_PREFERENCES_COOKIE_DESCRIPTION_TEXT'); ?></p>
	</div>
	<div class="tab " data-id="tab_statistics">
	    <div class="tab-heading">
		 	<h2><?php echo Label::getLabel('LBL_Statistics'); ?></h2>
			<div class="field_cover">
				<?php echo $cookieForm->getFieldHtml(UserCookieConsent::COOKIE_STATISTICS_FIELD); ?>
			</div>
		</div>
		<p><?php echo Label::getLabel('LBL_STATISTICS_COOKIE_DESCRIPTION_TEXT'); ?></p>
	</div>

	<div class="coookie-popup-footer">
		<?php echo $cookieForm->getFieldHtml('btn_submit'); ?>
	</div>
	</form>
</div>
<?php echo $cookieForm->getExternalJS(); ?>
<!--end of container-->
<script>
	$(document).ready(function() {
		
		$('.cookie-consent .field_cover label').each(function( index ) {
					let inputHtml = $(this).html()
				$(this).html('<span class="checkbox">'+inputHtml+'<i class="input-helper"></i></span>');
		});

		$('.tab-a').click(function() {
			$(".tab").removeClass('tab-active');
			$(".tab[data-id='" + $(this).attr('data-id') + "']").addClass("tab-active");
			$(".tab-a").removeClass('is-active');
			$(this).parent().find(".tab-a").addClass('is-active');
		});
	});
</script>