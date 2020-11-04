<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$cookieForm->setFormTagAttribute('id', 'cookieForm');
$cookieForm->setFormTagAttribute('class', 'form');
$cookieForm->developerTags['colClassPrefix'] = 'col-md-';
$cookieForm->developerTags['fld_default_col'] = 7;
$cookieForm->setFormTagAttribute('autocomplete', 'off');
$cookieForm->setFormTagAttribute('onsubmit', 'saveCookieSetting(this); return false;');
$necessary = $cookieForm->getField(UserCookieConsent::COOKIE_NECESSARY_FIELD);
$necessary->addFieldTagAttribute('disabled',true);
$submitButton = $cookieForm->getField('btn_submit');
$submitButton->addFieldTagAttribute('form','cookieForm');
?>

<div class="tab-container cookie-consent">
    <div class="coookie-popup-head">
	  <div class="popup-heading">
	     <h3><?php echo Label::getLabel('LBL_COOKIE_CONSENT_HEADING'); ?></h3>
	  </div>
	</div>
	<div class="coookie-popup-body">
		<div class="tabs-small tabs-offset tabs-scroll-js">
			<ul>
				<li><a href="javascript:void(0);" class="tab-a is-active" data-id="tab_necessary"><?php echo Label::getLabel('LBL_Necessary'); ?></a></li>
				<li><a href="javascript:void(0);" class="tab-a" data-id="tab_preferences"><?php echo Label::getLabel('LBL_Preferences'); ?></a></li>
				<li><a href="javascript:void(0);" class="tab-a" data-id="tab_statistics"><?php echo Label::getLabel('LBL_Statistics'); ?></a></li>
			</ul>
		</div>

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
	</form>
	</div>
	<!--end of tab-menu-->

	<div class="coookie-popup-footer">
		<?php echo $cookieForm->getFieldHtml('btn_submit'); ?>
	</div>
</div>
<?php echo $cookieForm->getExternalJS(); ?>
<!--end of container-->
<script>
	var necessaryField = '<?php echo UserCookieConsent::COOKIE_NECESSARY_FIELD ?>';
	$(document).ready(function() {
		
		$('.cookie-consent .field_cover label').each(function( index ) {
			let inputHtml = $(this).html()
			spanClass = ($(this).find('input').attr('name') == necessaryField) ? 'disabled' : '';
			$(this).html('<span class="checkbox '+spanClass+'">'+inputHtml+'<i class="input-helper"></i></span>');
		});

		$('.tab-a').click(function() {
			$(".tab").removeClass('tab-active');
			$(".tab[data-id='" + $(this).attr('data-id') + "']").addClass("tab-active");
			$(".tab-a").removeClass('is-active');
			$(this).parent().find(".tab-a").addClass('is-active');
		});
	});
</script>