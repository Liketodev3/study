<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php 
if( $languages && count($languages) > 1 ){ ?>
<a href="javascript:void(0)"  class="nav__dropdown-trigger nav__dropdown-trigger-js"><?php echo Label::getLabel("LBL_Site_Language"); ?></a>
<div class="nav__dropdown-target nav__dropdown-target-js -skin">
   <nav class="nav nav--vertical">
		<ul>
			<?php foreach( $languages as $langId => $language ){ ?>
			<li <?php echo ( $siteLangId == $langId ) ? 'class="is-active"' : '';?>><a onClick="setSiteDefaultLang(<?php echo $langId;?>)" href="javascript:void(0)"><?php echo $language['language_name']; ?></a></li>
			<?php } ?>
		</ul>
	</nav>
</div>
<?php } ?>