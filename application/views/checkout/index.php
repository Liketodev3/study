<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$layoutDirection = CommonHelper::getLayoutDirection(); ?>
<script>
	var teachLanguages = <?php echo FatUtility::convertToJson($teachLanguages); ?>
</script>
<section class="section section--grey section--page">
	<div class="container container--narrow">

		<div class="section__head">
			<h2><?php echo Label::getLabel('LBL_Checkout'); ?></h2>
		</div>
		<div class="section__body">
			<div class="row d-block -clearfix">
				<div id="lessonDetails" class="col-xl-4 col-lg-4 col-md-12 -clear-right">
					<div class="box -align-center" style="margin-bottom: 30px;">
						<div class="-padding-30">
							<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($cartData['user_first_name']); ?>">

								<?php
								if (true == User::isProfilePicUploaded($cartData['user_id'])) {
									$img = CommonHelper::generateUrl('Image', 'User', array($cartData['user_id']));
									echo '<img src="' . $img . '" />';
								}
								?>
							</div>

							<span class="-gap"></span>
							<h3 class="-display-inline"><?php echo $cartData['user_first_name']; ?></h3>

							<?php if ($cartData['user_country_id'] > 0) { ?>
								<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image', 'countryFlag', array($cartData['user_country_id'], 'DEFAULT')); ?>" alt=""></span>
							<?php } ?>

							<p class="-no-margin-bottom"><?php echo ($cartData['user_state_name'] != '') ? $cartData['user_state_name'] . ', ' : '';
															echo $cartData['user_country_name']; ?></p>
						</div>
						<div class="tabled">
							<?php if ($cartData['lpackage_is_free_trial'] == applicationConstants::NO) { ?>
								<div class="tabled__cell">
									<span class="-color-light"><?php echo Label::getLabel('LBL_Language'); ?></span><br>
									<span class="cart-lang-id-js">
										<?php if (isset($teachLanguages[$cartData['languageId']])) {
											echo $teachLanguages[$cartData['languageId']];
										} ?>
									</span>
								</div>
							<?php } ?>
							<div class="tabled__cell">
								<span class="-color-light"><?php echo Label::getLabel('LBL_Duration'); ?></span><br>
								<span class="cart-lesson-duration"> <?php echo sprintf(Label::getLabel('LBL_%s_Mins/Lesson'), $cartData['lessonDuration']); ?></span> </div>
						</div>
					</div>
				</div>
				<?php if ($cartData['lpackage_is_free_trial'] == 0 && count($teachLanguages)) { ?>
					<div class="col-xl-8 col-lg-8 col-md-12">
						<div class="box -padding-20" style="margin-bottom:30px;">
							<h3><?php echo Label::getLabel('LBL_Languages_Offered'); ?></h3>
							<p><?php echo Label::getLabel('LBL_Choose_Among_Languages_Offered!'); ?></p>

							<div class="selection-list">
								<ul>
									<?php foreach ($teachLanguages as $key => $teachLanguage) { ?>
										<?php if (isset($cartData['grpcls_id']) && $cartData['grpcls_id'] > 0 && $cartData['languageId'] != $key) continue; ?>
										<li class="<?php echo ($cartData['languageId'] == $key) ? 'is-active' : ''; ?>">
											<label class="selection">
												<span class="radio">
													<input onClick="addToCart('<?php echo $cartData['user_id'] ?>',2,'<?php echo $key; ?>', '', '', '<?php echo isset($cartData['grpcls_id']) ? $cartData['grpcls_id'] : 0 ?>');" type="radio" name="language" value="<?php echo $key; ?>" <?php echo ($cartData['languageId'] == $key) ? 'checked="checked"' : ''; ?>><i class="input-helper"></i>
												</span>
												<span class="selection__item">
													<?php echo $teachLanguage; ?> <small class="-float-right"> </small>
												</span>
											</label>
										</li>
									<?php } ?>
								</ul>
							</div>

							<?php /* <p><strong>NOTE:</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna tempor incididunt ut labore et dolore magna aliqua.</p> */ ?>

						</div>
					</div>
				<?php } ?>

				<div class="col-xl-8 col-lg-8 col-md-12" id="booking-durations-js"></div>

				<div class="col-xl-8 col-lg-8 col-md-12" id="lsn-pckgs"></div>

				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right">
					<div class="box" style="margin-bottom: 30px;" id="financialSummaryListing">
					</div>
					<p class="-color-secondary">
						<?php
						$labelstr =  Label::getLabel('LBL_*_All_Purchases_are_in_{default-currency-code}._Foreign_transaction_fees_might_apply,_according_to_your_bank\'s_policies');
						echo  str_replace("{default-currency-code}", CommonHelper::getDefaultCurrencyData()['currency_code'], $labelstr);
						?>
					</p>
					<div class="">
						<img src="<?php echo CONF_WEBROOT_URL; ?>images/PayGate-Card-Brand-Logos.jpg">
					</div>
				</div>

				<div class="col-xl-8 col-lg-8 col-md-12">
					<div class="box" style="margin-bottom: 30px;" id="checkout-left-side">
						<div id="paymentDiv">
							<?php
							$cartData['orderNetAmount']  = FatUtility::float($cartData['orderNetAmount']);
							if (0 >= $cartData['orderNetAmount']) {
								$this->includeTemplate('checkout/_partial/processWithNoPayment.php', $this->variables);
							}
							?>
						</div>
					</div>
				</div>
			</div>

		</div>
</section>

<script>
	<?php
	if ($cartData['orderNetAmount'] > 0) {
		echo 'loadPaymentSummary();';
	}
	if ($cartData['lpackage_is_free_trial'] == 0 and $cartData['user_id'] > 0 and $cartData['languageId'] > 0) {
	?>
		getLangPackages('<?php echo $cartData['user_id']; ?>', <?php echo $cartData['languageId'] . ',' . $cartData['lessonDuration']; ?>);
	<?php } ?>
</script>