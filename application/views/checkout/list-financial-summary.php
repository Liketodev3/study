<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="-padding-20 -no-padding-bottom">
	<h3><?php echo Label::getLabel('LBL_Cart'); ?></h3>
</div>

<!-- coupon area[ -->
<?php //if( $cart['orderNetAmount'] > 0 ){ 
?>
<div class="-padding-20">
	<div class="apply-coupon">
		<a href="javascript:void(0)" class="coupon-input btn btn--gray btn--block btn--large apply-coupon__trigger-js"><?php echo Label::getLabel('LBL_Apply_Coupon') ?></a>
		<div class="cpn-frm">
			<!--<div class="apply-coupon__target apply-coupon__target-js" style="display: none;">
				<div class="-padding-20">
					<form class="form form--small">
						<div class="d-flex">
							<div class="col-md-8">
								<input type="text">
							</div>
							<div class="col-md-4">
								<input type="submit" value="apply">
							</div>
						</div>
					</form>
				</div>
				<div class="-padding-20 -no-padding-top">
					<div class="applied-coupon">
						<p>Coupon <strong>"NEW10"</strong> Applied</p>
						<a href="#" class="btn btn--small btn--secondary">Remove</a>
					</div>
				</div>
			</div>-->
		</div>
	</div>
</div>
<?php //} 
?>
<!-- ] -->


<div class="table-total">
	<table>
		<tr>
			<td>
				<h6><strong><?php echo $cart['itemName']; ?></strong><br><small>

						<?php
						if (!empty($cart['lpackage_lessons'])) {
							echo Label::getLabel('LBL_Lesson_Count') . ': ' . sprintf(Label::getLabel('LBL_%s_Lesson(s)'), $cart['lpackage_lessons']) . '<br>';
						}
						if (!empty($cart['lessonDuration'])) {
							echo Label::getLabel('LBL_Duration') . ': ' . sprintf(Label::getLabel('LBL_%s_Mins/Lesson'), $cart['lessonDuration']) . '<br>';
						}

						if ($cart['startDateTime'] != '' && $cart['endDateTime']) {
							echo date("M d, Y h:i A", strtotime($cart['startDateTime'])) . '-' . date("h:i A", strtotime($cart['endDateTime']));
						} ?>
					</small></h6>
			</td>
			<td>
				<h6 class="-color-secondary"><?php echo CommonHelper::displayMoneyFormat($cart['cartTotal']); ?></h6>
			</td>
		</tr>

		<?php if ($cart['siteCommission'] > 0) { ?>
			<tr>
				<td>
					<h6><strong><?php echo Label::getLabel('LBL_Fee/Commission'); ?></strong></h6>
				</td>
				<td>
					<h6 class="-color-secondary"><?php echo CommonHelper::displayMoneyFormat($cart['siteCommission']); ?></h6>
				</td>
			</tr>
		<?php } ?>

		<?php if (!empty($cart['cartDiscounts'])) { ?>
			<tr>
				<td>
					<h6><strong><?php echo Label::getLabel('LBL_Coupon_Discount'); ?></strong></h6>
				</td>
				<td>
					<h6 class="-color-secondary"><?php echo '-' . CommonHelper::displayMoneyFormat($cart['cartDiscounts']['coupon_discount_total']); ?></h6>
				</td>
			</tr>
		<?php } ?>
		<?php
		$wallet = 0;
		if ($cart['cartWalletSelected'] > 0) {
			if ($cart['orderNetAmount'] < $userWalletBalance) {
				$userWalletBalance = $cart['orderNetAmount'];
				$wallet = $cart['orderNetAmount'];
			} else {
				$wallet = $userWalletBalance;
			}
		?>
			<tr>
				<td>
					<h6><strong><?php echo Label::getLabel('LBL_Wallet_Deduction'); ?></strong></h6>
				</td>
				<td>
					<h6 class="-color-secondary"><?php echo '-' . CommonHelper::displayMoneyFormat($userWalletBalance); ?></h6>
				</td>
			</tr>
		<?php }
		if ($wallet > $cart['orderNetAmount']) {
			$cart['orderNetAmount']  = $wallet;
		} ?>
		<tr class="last">
			<td>
				<h6><strong><?php echo Label::getLabel('LBL_Total'); ?></strong></h6>
			</td>
			<td>
				<h6 class="-color-secondary"><?php echo CommonHelper::displayMoneyFormat($cart['orderNetAmount'] - $wallet); ?></h6>
			</td>
		</tr>
	</table>
</div>

<span class="-gap -hide-mobile"></span>