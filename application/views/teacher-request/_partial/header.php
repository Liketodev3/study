<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>


	<header class="header">
		<div class="container container--narrow">
			<div class="header-primary">
				<div class="d-flex justify-content-between">
					<div class="header__left">
						<div class="header__logo">
						<a href="<?php echo CommonHelper::generateUrl(); ?>">
                            <img src="<?php echo CommonHelper::generateFullUrl('Image', 'siteLogo', array($siteLangId), CONF_WEBROOT_FRONT_URL); ?>" alt="">
                        </a>
						</div>
					</div>

					<div class="header__right">

						<div class="head__action">
							<a class="" href="#">
								<svg class="icon icon--logout">
									<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#logout' ?>"></use>
								</svg>
								<span><?php echo Label::getLabel('LBL_Logout',$siteLangId); ?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	

	<!-- ] -->



