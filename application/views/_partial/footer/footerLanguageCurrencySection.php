<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="settings-group">
	
	<?php if($currencies && count($currencies) > 1){ ?>
	
	<div class="settings toggle-group">
		<a href="javascript:void(0)" class="btn  btn--bordered btn--bordered-inverse btn--block btn--dropdown toggle__trigger-js is-active"><?php echo CommonHelper::getCurrencyCode();?></a>
		<div div-for="currency" class="settings__target -skin toggle__target-js">
			<nav class="nav nav--vertical">
				<ul>
					<?php foreach( $currencies as $currencyId => $currency ){ ?>
					<li <?php echo ( $siteCurrencyId == $currencyId ) ? 'class="is-active"' : ''; ?>><a href="javascript:void(0)" onClick="setSiteDefaultCurrency(<?php echo $currencyId;?>)"><?php echo $currency; ?></a></li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
	<?php } ?>
	<?php 
	if( $languages && count($languages) > 1 ){ ?>
	<div class="settings toggle-group">
		<a href="javascript:void(0)" class="btn  btn--bordered btn--bordered-inverse btn--block btn--dropdown toggle__trigger-js is-active"><?php echo $languages[$siteLangId]['language_name']; ?> <img  src="<?php echo CONF_WEBROOT_FRONT_URL;?>images/flags/<?php echo $languages[$siteLangId]['language_flag'];?>" title="<?php echo $flagName;?>" alt="<?php echo $flagName;?>"> </a>
		<div div-for="language" class="settings__target toggle__target-js -skin">
		   <nav class="nav nav--vertical">
				<ul>
					<?php foreach( $languages as $langId => $language ){ ?>
					<li <?php echo ( $siteLangId == $langId ) ? 'class="is-active"' : '';?>><a onClick="setSiteDefaultLang(<?php echo $langId;?>)" href="javascript:void(0)"><span><?php echo $language['language_name']; ?></span> <img  src="<?php echo CONF_WEBROOT_FRONT_URL;?>images/flags/<?php echo $language['language_flag'];?>" title="<?php echo $flagName;?>" alt="<?php echo $flagName;?>"> </a></li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
	<?php } ?>
</div>