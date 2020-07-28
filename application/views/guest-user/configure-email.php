<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section section--page">
	<div class="container container--fixed">
		<div class="row justify-content-center">
			<div class="col-sm-9 col-lg-5 col-xl-5">
				Please contact wemaster <a href="mailto:<?php echo FatApp::getConfig('conf_site_owner_email') ?>"><?php echo FatApp::getConfig('conf_site_owner_email') ?></a>
			</div>
		</div>
	</div>
</section>
<h6><?php echo Label::getLabel('LBL_UPDATE_EMAIL', $siteLangId);?></h6>
<div id="changeEmailFrmBlock"><?php echo Label::getLabel('LBL_Loading..', $siteLangId); ?></div>
