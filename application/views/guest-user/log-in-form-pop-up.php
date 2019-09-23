<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box box--narrow">
	<h2 class="-align-center"><?php echo Label::getLabel('LBL_Login'); ?></h2>
	
	<?php $this->includeTemplate( 'guest-user/_partial/learner-social-media-signup.php' );	?>

	<?php 
	$frm->setFormTagAttribute( 'class', 'form' );
	$frm->developerTags['colClassPrefix'] = 'col-sm-';
	$frm->developerTags['fld_default_col'] = 12;
	$frm->setFormTagAttribute('onsubmit', 'setUpLogin(this); return(false);');
	
	/* $fldUserName = $frm->getField( 'username' );
	$fldUserName->developerTags['col'] = 6;
	
	$fldPassword = $frm->getField( 'password' );
	$fldPassword->developerTags['col'] = 6; */
	
	echo $frm->getFormHtml(); ?>

	<div class="-align-center">
		<a href="<?php echo CommonHelper::generateUrl('GuestUser','ForgotPasswordForm'); ?>" class="-link-underline link-color"><?php echo Label::getLabel('LBL_Forgot_Password?'); ?></a>
	</div>

	<hr>
	
	<div class="-align-center">
		<p><?php echo Label::getLabel('LBL_Don\'t_have_an_account?'); ?> <a href="javascript:void(0)" onclick="signUpFormPopUp();" class="-link-underline link-color"><?php echo Label::getLabel('LBL_Sign_Up'); ?></a></p>
	</div>

</div>