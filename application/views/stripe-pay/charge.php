<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (!empty($stripe['secret_key']) && !empty($stripe['publishable_key'])) { 
?>
<script  src="https://js.stripe.com/v3/"></script>
<script >
var stripe = Stripe('<?php echo $stripe['publishable_key']; ?>');
</script>
<?php } ?>
<section class="section section--grey section--page -pattern">
	<div class="container container--fixed">
		<div class="page-panel -clearfix">
			<div class="page__panel-narrow">
				<div class="row justify-content-center">
					<div class="col-xl-6 col-lg-8 col-md-10">
						<div class="box -padding-30 -skin">
							<div class="box__data">
								<div class="loader"></div>
								<div class="-align-center">
								<h1 class="-color-secondary">We're redirecting you!!</h1>
								<h4>Please wait...</h4>
								</div>
							</div>
							<div class="message-display">
								<p class=""><?php echo Label::getLabel('LBL_Payable_Amount',$siteLangId);?> : <strong><?php echo CommonHelper::displayMoneyFormat($paymentAmount)?></strong> </p>
								<p class="">
									<?php echo Label::getLabel('LBL_Order_Invoice',$siteLangId);?>: <strong><?php echo $orderInfo["order_id"] ;/* displayNotApplicable($orderInfo["order_id"]) */?></strong>
								</p>
								<?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
									<p class="-color-secondary"><?php echo CommonHelper::currencyDisclaimer($siteLangId, $paymentAmount); ?></p>
								<?php } ?>
							</div>
							<div class="-align-center">
								<div class="payment-from">
									<?php  if (isset($error)): ?>
									<div class="alert alert--danger">
										<?php echo $error?>
										<div>
										<?php  endif;?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script >
$(function(){
    stripe.redirectToCheckout({ sessionId: '<?php echo $stripeSessionId ?>' });
});
</script>
