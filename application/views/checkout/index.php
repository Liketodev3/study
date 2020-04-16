<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<section class="section section--grey section--page" >
	<div class="container container--narrow">

		<div class="section__head">
			<h2><?php echo Label::getLabel('LBL_Checkout'); ?></h2>
		</div>
		<div class="section__body">
			<div class="row d-block <?php echo (strtolower($layoutDirection) !="rtl")? "d-block" : ""; ?> -clearfix">

				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right">
					<div class="box -align-center" style="margin-bottom: 30px;">
						<div class="-padding-30">
							<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($cartData['user_first_name']); ?>">

								<?php
								if( true == User::isProfilePicUploaded( $cartData['user_id'] ) ){
									$img = CommonHelper::generateUrl('Image','User', array( $cartData['user_id'] ));
									echo '<img src="'.$img.'" />';
								}
								?>

								<?php /* if( $cartData['is_online'] ){ ?>
								<span class="tag-online"></span>
								<?php } */ ?>
							</div>

							<span class="-gap"></span>
							<h3 class="-display-inline"><?php echo $cartData['user_first_name']; ?></h3>

							<?php if( $cartData['user_country_id'] > 0 ){ ?>
							<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image','countryFlag', array($cartData['user_country_id'], 'DEFAULT') ); ?>" alt=""></span>
							<?php } ?>

							<p class="-no-margin-bottom"><?php echo ($cartData['user_state_name'] != ''  ) ? $cartData['user_state_name'].', ' : ''; echo $cartData['user_country_name']; ?>
							<br><?php
							/* echo CommonHelper::getDateOrTimeByTimeZone( $cartData['user_timezone'], 'h:i A'  );
							echo " (GMT ".CommonHelper::getDateOrTimeByTimeZone( $cartData['user_timezone'], ' P' ).")"; */
							?> </p>
						</div>
                        <div class="tabled">
                            <div class="tabled__cell">
                            <span class="-color-light"><?php echo Label::getLabel('LBL_Language'); ?></span><br>
							<?php if(isset($teachLanguages[$cartData['languageId']])) { 
								echo $teachLanguages[$cartData['languageId']]; 
							} ?>

                            </div>
                            <div class="tabled__cell">
                            <span class="-color-light"><?php echo Label::getLabel('LBL_Duration'); ?></span><br>
                            <?php echo ($cartData['lpackage_is_free_trial'])?FatApp::getConfig( 'conf_trial_lesson_duration', FatUtility::VAR_INT, 30 ):FatApp::getConfig('conf_paid_lesson_duration', FatUtility::VAR_INT, 60); ?> Mins/Lesson
                            </div>
                        </div>
					</div>
				</div>


				<?php if( $cartData['lpackage_is_free_trial'] == 0 && count($teachLanguages) ){ ?>
				<div class="col-xl-8 col-lg-8 col-md-12">
					<div class="box -padding-20" style="margin-bottom:30px;">
						<h3><?php echo Label::getLabel('LBL_Languages_Offered'); ?></h3>
						<p><?php echo Label::getLabel('LBL_Choose_Among_Languages_Offered!'); ?></p>

						<div class="selection-list">
							<ul>
								<?php 

                                foreach($teachLanguages as $key=>$teachLanguage){ ?>
								<li class="<?php echo ($cartData['languageId'] == $key) ? 'is-active' : ''; ?>">
									<label class="selection">
										<span class="radio">
											<input onClick="getLangPackages('<?php echo $cartData['user_id'] ?>', '<?php echo $key; ?>');" type="radio"  name="language" value="<?php echo $key; ?>" <?php echo ($cartData['languageId'] == $key) ? 'checked="checked"' : ''; ?>><i class="input-helper"></i>
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
				<div class="col-xl-8 col-lg-8 col-md-12" id="lsn-pckgs">
				</div>
				<div class="col-xl-4 col-lg-4 col-md-12 -clear-right" >
					<div class="box" style="margin-bottom: 30px;" id="financialSummaryListing">
					</div>
				</div>

				<div class="col-xl-8 col-lg-8 col-md-12">
					<div class="box" style="margin-bottom: 30px;" id="checkout-left-side">
					<?php
					if( $cartData['orderNetAmount'] > 0 ){ ?>
						<div id="paymentDiv">
						</div>
						<?php
					} else {
						$this->includeTemplate('checkout/_partial/processWithNoPayment.php', $this->variables);
					}
					?>
					</div>
				</div>
		</div>

	</div>
</section>

<script type="text/javascript">
<?php 
	if( $cartData['orderNetAmount'] > 0 ){
		echo 'loadPaymentSummary();';
	}
	if( $cartData['lpackage_is_free_trial'] == 0 AND $cartData['user_id'] > 0 AND $cartData['languageId'] > 0 ){
?>
    getLangPackages('<?php echo $cartData['user_id']; ?>','<?php echo $cartData['languageId']; ?>');
    <?php } ?>
</script>
