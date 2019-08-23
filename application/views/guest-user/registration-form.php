<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
	$frm->setFormTagAttribute( 'class', 'form' );
	$frm->developerTags['colClassPrefix'] = 'col-sm-';
	$frm->developerTags['fld_default_col'] = 12;
	$frm->setFormTagAttribute('onsubmit', 'setUpSignUp(this); return(false);');
	
	$fldFirstName = $frm->getField( 'user_first_name' );
	$fldFirstName->developerTags['col'] = 6;
	
	$fldLastName = $frm->getField( 'user_last_name' );
	$fldLastName->developerTags['col'] = 6;
	
	$fldPassword = $frm->getField( 'user_password' );
	$fldPassword->changeCaption( '' );
	$fldPassword->captionWrapper = ( array( Label::getLabel('LBL_Password') . '<a onClick="togglePassword(this)" href="javascript:void(0)" class="-link-underline -float-right" data-show-caption="'. Label::getLabel('LBL_Show_Password') .'" data-hide-caption="'. Label::getLabel('LBL_Hide_Password') .'">'.Label::getLabel('LBL_Show_Password'), '</a>') );
	
	/* [ */
	$fldAgree = $frm->getField('agree');
	$termLink ='';
	$termLink .= ' <a target="_blank" class = "-link-underline" href="'.$termsAndConditionsLinkHref.'">'.Label::getLabel('LBL_TERMS_AND_CONDITION').'</a>';
	$fldAgree->htmlAfterField = $termLink;
	/* ] */
?>
<section class="section section--page">
	<div class="container container--fixed">
		<div class="row justify-content-center">
			<div class="col-sm-9 col-lg-5 col-xl-5">
				<div class="box -skin">
					<div class="box__head -align-center">
						<h4 class="-border-title"><?php echo Label::getLabel('LBL_Register'); ?></h4>
					</div>
					<div class="box__body -padding-40">
					
						<?php $this->includeTemplate( 'guest-user/_partial/learner-social-media-signup.php' );	?>
					
						<?php echo $frm->getFormHtml(); ?>
						<a href="<?php echo CommonHelper::generateUrl('GuestUser','loginForm'); ?>" class="-link-underline"><?php echo Label::getLabel('LBL_Back_to_Login'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>