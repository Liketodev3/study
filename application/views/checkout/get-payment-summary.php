<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box box--checkout">
	<div class="box__head">
		<a href="#" class="btn btn--bordered color-black btn--back">
			<svg class="icon icon--back">
				<use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#back'; ?>"></use>
			</svg>
			<?php echo Label::getLabel('LBL_BACK'); ?>
		</a>
		<h4><?php echo Label::getLabel('LBL_Select_Payment_Method'); ?></h4>
		<a href="#" class="btn btn--bordered color-black btn--close">
			<svg class="icon icon--close">
				<use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#close'; ?>"></use>
			</svg>
		</a>
	</div>
	<div class="box__body">

		<div class="step-nav">
			<ul>
				<li class="step-nav_item is-completed"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_1'); ?></a> <span class="step-icon"></span></li>
				<li class="step-nav_item is-completed"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_2'); ?></a><span class="step-icon"></span></li>
				<li class="step-nav_item is-completed"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_3'); ?></a><span class="step-icon"></span></li>
				<li class="step-nav_item is-process"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_4'); ?></a></li>
			</ul>
		</div>

		<div class="selection-tabs selection--checkout selection--payment">

			<div class="row">
				<div class="col-md-6 col-xl-6">
					<div class="selection-title">
						<p><?php echo Label::getLabel('LBL_Select a Payment Method'); ?></p>
					</div>
					<div class="payment-wrapper">
						<?php if($userWalletBalance > 0){ ?>
						<label class="selection-tabs__label selection--wallet">
							<input type="radio" class="selection-tabs__input" name="1">
							<div class="selection-tabs__title">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
									<g>
										<path d="M12,22A10,10,0,1,1,22,12,10,10,0,0,1,12,22Zm-1-6,7.07-7.071L16.659,7.515,11,13.172,8.174,10.343,6.76,11.757Z" transform="translate(-2 -2)" />
									</g>
								</svg>
								<div class="payment-type">
									<p><?php echo sprintf(Label::getLabel('LBL_Wallet_Credits_(%s)'),CommonHelper::displayMoneyFormat($lessonQtyPrice)); ?></p>
									<p class="is-selected"><?php echo Label::getLabel('LBL_Sufficient_balance_in_your_wallet'); ?></p>
								</div>
								<div class="balance-payment">
									<ul>
										<li>
											<p><?php echo Label::getLabel('LBL_Payment_To_Be_Made'); ?></p>
											<div class="space"></div>
											<b>$130.00</b>
										</li>
										<li>
											<p><?php echo Label::getLabel('LBL_Amount_In_Your_Wallet'); ?></p>
											<div class="space"></div>
											<b><?php CommonHelper::displayMoneyFormat($userWalletBalance); ?></b>
										</li>
										<li>
											<p><?php echo Label::getLabel('LBL_Remaining_Wallet_Balance'); ?></p>
											<div class="space"></div>
											<b>$470.00</b>
										</li>
									</ul>
								</div>
							</div>
						</label>
						<?php } ?>
						<?php foreach ($paymentMethods as $key => $value) { ?>
							<label class="selection-tabs__label payment-method-js">
							<input type="radio" class="selection-tabs__input" name="payment_method">
							<div class="selection-tabs__title">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
									<g>
										<path d="M12,22A10,10,0,1,1,22,12,10,10,0,0,1,12,22Zm-1-6,7.07-7.071L16.659,7.515,11,13.172,8.174,10.343,6.76,11.757Z" transform="translate(-2 -2)" />
									</g>
								</svg>
								<div class="payment-type">
									<p><?php echo $value['pmethod_name']; ?></p>
								</div>
							</div>
						</label>

						<?php } ?>
					</div>
				</div>

				<div class="col-md-6 col-xl-5 offset-xl-1">
					<div class="selection-title">
						<p><?php echo Label::getLabel('LBL_Have_a_Coupon?'); ?></p>
					</div>

					<div class="apply-coupon">
						<svg class="icon icon--price-tag">
							<use xlink:href="images/sprite.yo-coach.svg#price-tag"></use>
						</svg>
						<input type="text" placeholder="Enter Coupon Code">
						<a href="#" class="btn btn--secondary btn--small color-white"><?php echo Label::getLabel('LBL_APPLY'); ?></a>
					</div>

					<div class="selection-title">
						<p><?php echo Label::getLabel('LBL_Summary'); ?></p>
					</div>

					<div class="payment-summary">
						<div class="payment__row">
							<div>
								<b><?php echo sprintf(Label::getLabel('LBL_%s_LESSON(s)'),$postedData['lessonQty']); ?></b>
								<p><?php echo str_replace(['{lessonQty}', '{duration}' ],[$postedData['lessonQty'], $postedData['slot']],Label::getLabel('LBL_Lesson_Count:_{lessonQty}_Lesson(s)_Duration:_{duration}_Mins/lesson')); ?></p>
							</div>
							<div>
								<b>$130.00</b>
							</div>
						</div>
						<div class="payment__row">
							<div>
								<b class="color-primary"><?php echo Label::getLabel('LBL_Total'); ?></b>
							</div>
							<div>
								<b class="color-primary">$130.00</b>
							</div>
						</div>
					</div>
					<button href="#" class="btn btn--primary btn--large btn--block color-white"><?php echo Label::getLabel('LBL_CONFIRM_PAYMENT'); ?></button>
					<p class="payment-note">
						<?php 
							$labelstr =  Label::getLabel('LBL_*_All_Purchases_are_in_{default-currency-code}._Foreign_transaction_fees_might_apply,_according_to_your_bank\'s_policies');
							echo  str_replace("{default-currency-code}", CommonHelper::getSystemCurrencyData()['currency_code'], $labelstr);
						?>
					</p>
				</div>
			</div>

		</div>


	</div>

</div>