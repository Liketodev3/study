<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); //print_r($paymentMethods); die; ?>
<section class="section section--grey section--page" >
	<div class="container container--narrow">

		<div class="section__head">
			<h2><?php echo Label::getLabel('LBL_Checkout'); ?></h2>
		</div>
		<div class="section__body">
			<div class="row d-block -clearfix">

				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right">
					<div class="box -align-center" style="margin-bottom: 30px;">
						<div class="-padding-30">
							<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($userDetails['user_first_name']); ?>">

								<?php
								if( true == User::isProfilePicUploaded( $userDetails['user_id'] ) ){
									$img = CommonHelper::generateUrl('Image','User', array( $userDetails['user_id'] ));
									echo '<img src="'.$img.'" />';
								}
								?>

								<?php /* if( $cartData['is_online'] ){ ?>
								<span class="tag-online"></span>
								<?php } */ ?>
							</div>

							<span class="-gap"></span>
							<h3 class="-display-inline"><?php echo $userDetails['user_first_name']; ?></h3>

							<?php if( $userDetails['user_country_id'] > 0 ){ ?>
							<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($userDetails['user_country_id'], 'DEFAULT') ); ?>" alt=""></span>
							<?php } ?>
							<br><?php
							/* echo CommonHelper::getDateOrTimeByTimeZone( $userDetails['user_timezone'], 'h:i A'  );
							echo " (GMT ".CommonHelper::getDateOrTimeByTimeZone( $userDetails['user_timezone'], ' P' ).")"; */
							?> </p>
						</div>
					</div>

				</div>

				<div class="col-xl-8 col-lg-8 col-md-12">
                            <div class="box" style="margin-bottom: 30px;">
                                <div class="-padding-20">
                                    <h3>Payment</h3>
                                    <p class="-no-margin-bottom">Pick a payment method.</p>
                                </div>

                                <div class="payments-container payments-container-js">

                                    <div class="-padding-20">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-4">
											<?php if($paymentMethods){ ?>
                                            <div class="tabs-gray tabs-js">
                                                <ul>
												<?php foreach($paymentMethods as $k=>$paymentMethod){ ?>
                                                    <li class="<?php if($k==0){ echo 'is-active'; }?>"><a href="#tab_<?php echo $k;?>"><?php echo $paymentMethod['pmethod_name']; ?></a></li>
												<?php } ?>
                                                </ul>
                                            </div>
											<?php } ?>
                                        </div>
                                            <div class="col-xl-8 col-lg-8 col-md-8">
                             				<?php foreach($paymentMethods as $k=>$paymentMethod){ ?>
                                                 <div id="tab_<?php echo $k;?>" class="tabs-content-js">
                                                     <div>
                                                       <?php /*<div class="icon-payment"><img src="images/paypal.png" alt="" ></div>*/ ?>
                                                        <h5>Proceed With <?php echo $paymentMethod['pmethod_name']; ?></h5><br>
                                                        <p><?php echo $paymentMethod['pmethod_description']; ?><br><br></p>
                                                        <h6>Net Payable : <?php echo CommonHelper::displayMoneyFormat($orderInfo['order_net_amount']); ?> </h6>
                                                        <span class="-gap"></span>
                                                        <a href="<?php echo CommonHelper::generateUrl($paymentMethod['pmethod_code'].'Pay', 'charge', array($orderInfo['order_id']) ); ?>" class="btn btn--secondary btn--large">Make Payment</a>
                                                     </div>
                                                </div>
												<?php } ?>

                                            </div>
                                        </div>
                                    </div>

                                    <span class="-gap"></span>

                                </div>

                            </div>
                        </div>

		</div>

	</div>
</section>
