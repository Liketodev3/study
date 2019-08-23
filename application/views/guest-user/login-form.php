<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
	$frm->setFormTagAttribute( 'class', 'form' );
	$frm->developerTags['colClassPrefix'] = 'col-sm-';
	$frm->developerTags['fld_default_col'] = 12;
	$frm->setFormTagAttribute('onsubmit', 'setUpLogin(this); return(false);');
?>
<section class="section section--gray section--page">
	<div class="container container--fixed">
		<div class="row justify-content-center">
			<div class="col-sm-9 col-lg-5 col-xl-5">
				<div class="box -skin">
					<div class="box__head -align-center">
						<h4 class="-border-title"><?php echo Label::getLabel('LBL_Login'); ?></h4>
					</div>
					<div class="box__body -padding-40">
					
						<?php $this->includeTemplate( 'guest-user/_partial/learner-social-media-signup.php' );	?>
						<?php echo $frm->getFormHtml(); ?>
						
						<div class="-align-center">
						<a href="<?php echo CommonHelper::generateUrl('GuestUser','ForgotPasswordForm'); ?>" class="-link-underline"><?php echo Label::getLabel('LBL_Forgot_Password?'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
