<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
 $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<?php 
$validityType = $sCartSummary['subscription']['spackage_validity_type'];

$intervalTypeString =  SubscriptionPackage::INTERVAL_TYPE_STRINGS;

$validity = $sCartSummary['subscription']['spackage_validity'];

$userTimezone = MyDate::getUserTimeZone();
$currentDateTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', date('Y-m-d H:i:s'), true , $userTimezone);

$endDateUnixTimeStamp =  strtotime('+'.$validity.' '.$intervalTypeString[$validityType]);
$endDateTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', date('Y-m-d H:i:s',$endDateUnixTimeStamp), true , $userTimezone);

?>
<section class="section section--grey section--page" >
	<div class="container container--narrow">
		<div class="section__head">
			<h2><?php echo Label::getLabel('LBL_Subscription_Checkout'); ?></h2>
		</div>

		<div class="section__body">
			<div class="row d-block -clearfix">

				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right">
					<div class="box -align-center" style="margin-bottom: 30px;">
						<div class="-padding-30">
							<h3 class="-display-inline"><?php echo $sCartSummary['subscription']['spackage_identifier']; ?></h3>
						</div>
                        <div class="tabled">
                            <div class="tabled__cell">
								<span class="-color-light"><?php echo Label::getLabel('LBL_Duration'); ?></span><br>
								<span class="cart-lang-id-js">
									<?php echo $sCartSummary['subscription']['spackage_validity'].' '.$validityTypeArray[$validityType]; ?>
								</span>
                            </div>
                            <div class="tabled__cell">
								<span class="-color-light"><?php echo Label::getLabel('LBL_End_On'); ?></span><br>
								<span class="cart-lang-id-js">
									<?php echo $currentDateTime.' - '.$endDateTime; ?>
								</span>
                            </div>
                        </div>
					</div>
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
				<!-- <div class="col-xl-8 col-lg-8 col-md-12" >
				<span class="-gap"></span>
				</div> -->
				<div class="col-xl-8 col-lg-8 col-md-12">
					<div class="box" style="margin-bottom: 30px;" id="checkout-left-side">
                        <div id="paymentDiv">
                        </div>
					</div>
				</div>
				
				

			</div>
		</div>
</section>
