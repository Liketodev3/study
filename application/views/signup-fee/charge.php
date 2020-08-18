<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
 $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<section class="section section--grey section--page" >
	<div class="container container--narrow">
		<div class="section__head">
			<h2><?php echo Label::getLabel('LBL_Pay_Signup_Fee'); ?></h2>
		</div>
		<div class="section__body">
			<div class="row d-block -clearfix">
				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right">
					<div class="box -align-center" style="margin-bottom: 30px;">
						<div class="-padding-30">
							<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($userDetails['user_first_name']); ?>">
								<?php
								if( true == User::isProfilePicUploaded( $userDetails['user_id'] ) ) {
									$img = CommonHelper::generateUrl('Image','User', array( $userDetails['user_id'] ));
									echo '<img src="'.$img.'" />';
								}
								?>
							</div>
							<span class="-gap"></span>
							<h3 class="-display-inline"><?php echo  $userDetails['user_first_name']; ?></h3>
							<?php if( $userDetails['user_country_id'] > 0 ){ ?>
							<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($userDetails['user_country_id'], 'DEFAULT') ); ?>" alt=""></span>
							<?php } ?>

						</div>
					</div>
				</div>
				<div class="col-xl-8 col-lg-8 col-md-12">
					<?php if(!empty( $orderDetails['order_id'] )) { ?>
						<p class="-color-secondary">
							<?php $labelstr = Label::getLabel('LBL_SIGNUP_PENDING_PAYMENT_MSG_{PAYMENT_ID}_{SUPPORT_LINK}'); 
							$contactLink = "<a class='-link-underline -color-primary' href='".CommonHelper::generateUrl('Contact')."'> ".Label::getLabel('LBL__Click_Here_To_Contact')." </a>";
							$orderId = "<span class='-link-underline -color-primary'>".$orderDetails['order_id']."</span>" ;
							echo str_replace(["{PAYMENT_ID}","{SUPPORT_LINK}"],[$orderId,$contactLink],$labelstr);
							?>
						</p>
					<?php } ?>
				   
				</div>
				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right" >
					<div class="box" style="margin-bottom: 30px;" id="financialSummaryListing">
					</div>
					<p class="-color-secondary">
						<?php
						 $labelstr =  Label::getLabel('LBL_*_All_Purchases_are_in_{default-currency-code}._Foreign_transaction_fees_might_apply,_according_to_your_bank\'s_policies');
						  echo  str_replace("{default-currency-code}", CommonHelper::getDefaultCurrencyData()['currency_code'],$labelstr);
						?>
					</p>
					<div class="">
							<img src="<?php echo CONF_WEBROOT_URL; ?>images/PayGate-Card-Brand-Logos.jpg">
               		 </div>
				</div>
				<div class="col-xl-8 col-lg-8 col-md-12">
					<div class="box" style="margin-bottom: 30px;" id="checkout-left-side">
                        <div id="paymentDiv">
                        </div>
					</div>
				</div>
		</div>

	</div>
</section>