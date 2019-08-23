<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box box--narrow">
	<h2 class="-align-center"><?php echo Label::getLabel('LBL_Register'); ?></h2>
	
	<?php $this->includeTemplate( 'guest-user/_partial/learner-social-media-signup.php' );	?>
	
	<?php 
	$frm->setFormTagAttribute( 'class', 'form' );
	$frm->developerTags['colClassPrefix'] = 'col-sm-';
	$frm->developerTags['fld_default_col'] = 12;
	$frm->setFormTagAttribute('onsubmit', 'setUpSignUp(this); return(false);');
	
	$fldFirstName = $frm->getField( 'user_first_name' );
	$fldFirstName->developerTags['col'] = 6;
	
	$fldLastName = $frm->getField( 'user_last_name' );
	$fldLastName->developerTags['col'] = 6;
	
	/* $fldUserName = $frm->getField( 'user_email' );
	$fldUserName->developerTags['col'] = 6;
	
	$fldPassword = $frm->getField( 'user_password' );
	$fldPassword->developerTags['col'] = 6; */
	
	$fldPassword = $frm->getField( 'user_password' );
	$fldPassword->changeCaption( '' );
	$fldPassword->captionWrapper = ( array( Label::getLabel('LBL_Password') . '<span class="spn_must_field">*</span><a onClick="togglePassword(this)" href="javascript:void(0)" class="-link-underline -float-right" data-show-caption="'. Label::getLabel('LBL_Show_Password') .'" data-hide-caption="'. Label::getLabel('LBL_Hide_Password') .'">'.Label::getLabel('LBL_Show_Password'), '</a>') );
	
	/* [ */
	$fldAgree = $frm->getField('agree');
	$termLink ='';
	$termLink .= ' <a target="_blank" class = "-link-underline" href="'.$termsAndConditionsLinkHref.'">'.Label::getLabel('LBL_TERMS_AND_CONDITION').'</a>';
	$fldAgree->htmlAfterField = $termLink;
	
	/* ] */
	
	
	//$fldAgree->changeCaption( '<a href="">terms</a>' );
	
	echo $frm->getFormHtml(); ?>
	
	<div class="-align-center">
		<p><?php echo Label::getLabel('LBL_Already_have_an_account?'); ?> <a href="javascript:void(0);" onClick="logInFormPopUp()" class="-link-underline"><?php echo Label::getLabel('LBL_Sign_In'); ?></a></p>
	</div>
</div>
