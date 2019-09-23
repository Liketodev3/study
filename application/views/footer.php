<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
</div><!-- div id=body class=body ends here -->
<footer id="footer" class="footer">
	<section class="section footer-bottom section--black">
		<div class="container container--fixed">
			<div class="row">
				<div class="col-xl-3 col-lg-3 col-md-6">
					<?php $this->includeTemplate( '_partial/footer/footerSocialMedia.php' ); ?>
					
					<?php $this->includeTemplate( '_partial/footer/footerLanguageCurrencySection.php' ); 
					?>
				</div>            
				<div class="col-xl-2 col-lg-2 col-md-3">
					<div class="toggle-group">
						<h5 class="toggle__trigger toggle__trigger-js"><?php echo FatApp::getConfig('CONF_WEBSITE_NAME_'.CommonHelper::getLangId());?></h5>
						<div class="toggle__target toggle__target-js">
							<ul class="links--vertical">
								<?php $this->includeTemplate( '_partial/footerNavigation.php'); ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-xl-2 col-lg-2 col-md-3">
					<div class="toggle-group">
						<h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_Teachers'); ?></h5>
						<div class="toggle__target toggle__target-js">
							<ul class="links--vertical">
								<!--<li><a href="#">English Tutors</a></li>
								<li><a href="#">Spanish Tutors</a></li>
								<li><a href="#">French Tutors</a></li>
								<li><a href="#">Japanese Tutors</a></li>
								<li><a href="#">Arabic Tutors</a></li>
								<li><a href="#">All Tutors</a></li>-->
								<?php $this->includeTemplate( '_partial/tutorListNavigation.php'); ?>								
							</ul>
						</div>
					</div>
				</div>
				
				<div class="col-xl-2 col-lg-2 col-md-3">
					<div class="toggle-group">
						<h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_More_Links'); ?></h5>
						<div class="toggle__target toggle__target-js">
							<ul class="links--vertical">
								<!--<li><a href="#">Learn English</a></li>
								<li><a href="#">Learn Chinese (Mandarin)</a></li>
								<li><a href="#">Learn French</a></li>
								<li><a href="#">Learn Spanish</a></li>
								<li><a href="#">Learn German</a></li>
								<li><a href="#">More Languages</a></li>-->
								<?php $this->includeTemplate( '_partial/footerRightNavigation.php'); ?>
							</ul>
						</div>
					</div>
				</div>
                <div class="col-xl-3 col-lg-3 col-md-8">
                    <div class="toggle-group">
                        <h5 class="toggle__trigger toggle__trigger-js"><?php echo Label::getLabel('LBL_Contact_Info'); ?></h5>
                        <div class="toggle__target toggle__target-js">
                            <ul class="links--vertical">
                                <li>
                                    <a href="#">
                                        <img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/contact-icon01.svg">
                                        <?php echo FatApp::getConfig('CONF_CONTACT_EMAIL');?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/contact-icon02.svg">
                                        <?php echo Label::getLabel('LBL_Call_Us'); ?>: <?php echo FatApp::getConfig('CONF_SITE_PHONE');?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/contact-icon03.svg">
                                        <?php echo FatApp::getConfig('CONF_ADDRESS_'.CommonHelper::getLangId());?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>				
			</div>
		</div>
            <div class="footer-inline -singleTopBorder">
            <div class="container container--fixed">
                <ul class="inline-listing">
								<?php $this->includeTemplate( '_partial/footerBottomNavigation.php'); ?>
                </ul>

                <ul class="fineprint-listing">
                    <li>
                        <p>&copy;<?php echo date("Y"); ?> <?php echo FatApp::getConfig('CONF_WEBSITE_NAME_'.CommonHelper::getLangId()); ?></p>
                    </li>
                    <li>
                        <p><?php echo Label::getLabel('LBL_All_Rights_Reserved'); ?></p>
                    </li>
                    <li>
                        <p><?php //echo MyDate::getDateAndTimeDisclaimer(); 
						?></p>
                    </li>
                </ul>
            </div>                        
            </div>        
	</section>
	
</footer>
<!--footer end here-->

<a href="javascript:void(0)" class="scroll-top-js gototop" title="Back to Top"></a>
</body>
</html>