<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$totReviews = FatUtility::int($reviews['totReviews']);
$avgRating = FatUtility::convertToType($reviews['prod_rating'], FatUtility::VAR_FLOAT);

$pixelToFillRight = $avgRating / 5 * 160;
$pixelToFillRight = FatUtility::convertToType($pixelToFillRight, FatUtility::VAR_FLOAT);

$teacherLanguage = 0;
if (!empty($teacher['teachLanguages'])) {
	$teacherLanguage = key($teacher['teachLanguages']);
}
$langId = CommonHelper::getLangId();
$websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId, FatUtility::VAR_STRING, '');

$teacherLangPrices = [
	'single' => [],
	'bulk' => []
];
$bookingDuration = '';
foreach ($userTeachLangs as $userTeachLang) {

	$teacherLangPrices['single'][$userTeachLang['utl_booking_slot']][$userTeachLang['tlanguage_id']] = array(
		'lang_name' => $userTeachLang['tlanguage_name'],
		'price' => $userTeachLang['utl_single_lesson_amount']
	);
	$teacherLangPrices['bulk'][$userTeachLang['utl_booking_slot']][$userTeachLang['tlanguage_id']] = array(
		'lang_name' => $userTeachLang['tlanguage_name'],
		'price' => $userTeachLang['utl_bulk_lesson_amount']
	);
	$bookingDuration = $userTeachLang['utl_booking_slot'];
}
?>
<title><?php echo Label::getLabel('LBL_Learn') . " " . implode(', ', $teacher['teachLanguages']) . " " . Label::getLabel('LBL_from') . " " . $teacher['user_full_name'] . " " . Label::getLabel('LBL_on') . " " . $websiteName; ?></title>

<section class="section section--gray section--details">
	<div class="container container--narrow">
		<div class="breadcrumb">
			<ul>
				<li><a href="<?php echo CommonHelper::generateUrl(); ?>"><?php echo Label::getLabel('LBL_Home'); ?></a></li>
				<li><a href="<?php echo CommonHelper::generateUrl('teachers'); ?>"><?php echo Label::getLabel('LBL_Tutors'); ?></a></li>
				<li><?php echo $teacher['user_full_name']; ?></li>
			</ul>
		</div>

		<div class="row">
			<div class="col-xl-8 col-lg-8">
				<?php
				if ($teacher['us_video_link'] != '') {
					$youTubeVideoArr = explode("?v=", $teacher['us_video_link']);
					if (count($youTubeVideoArr) > 1) {
				?>
						<div class="video">
							<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $youTubeVideoArr[1]; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
						</div>
				<?php }
				}
				?>
				<div class="box -padding-30">
					<div class="box__profile-head">
						<div class="row">

							<!-- Image[ -->
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 -align-center">
								<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($teacher['user_first_name']); ?>">
									<?php
									if (true == User::isProfilePicUploaded($teacher['user_id'])) {
										$img = FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'User', array($teacher['user_id'], 'MEDIUM')), CONF_DEF_CACHE_TIME, '.jpg');
										echo '<img src="' . $img . '" />';
									}
									?>
									<?php /* if( $teacher['is_online'] ){ ?>
									<span class="tag-online"></span>
									<?php } */ ?>
								</div>
							</div>
							<!-- ] -->

							<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5">
								<div class="teacher__info">
									<h3 class="-display-inline"><?php echo $teacher['user_full_name']; ?></h3>

									<?php if ($teacher['user_country_id'] > 0) { ?>
										<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image', 'countryFlag', array($teacher['user_country_id'], 'DEFAULT')); ?>" alt=""></span>
									<?php } ?>

									<!-- User Location[ -->
									<div class="location">
										<span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="11.625" height="15.969" viewBox="0 0 11.625 15.969">

												<path d="M462.04,225.016a5.805,5.805,0,0,0-5.81,5.788c0,3.96,5.2,9.774,5.421,10.02a0.524,0.524,0,0,0,.778,0c0.221-.246,5.42-6.06,5.42-10.02A5.8,5.8,0,0,0,462.04,225.016Zm0,8.7a2.912,2.912,0,1,1,2.923-2.912A2.921,2.921,0,0,1,462.04,233.716Z" transform="translate(-456.219 -225.031)" />
											</svg></span>
										<?php echo ($teacher['user_state_name'] != '') ? $teacher['user_state_name'] . ', ' : ''; ?> <?php echo $teacher['user_country_name']; ?>

									</div>

									<!-- ] -->
									<div class="box__price -margin-tb-15">
										<?php echo Label::getLabel('LBL_Hourly_Rate'); ?>
										<h6><?php echo CommonHelper::displayMoneyFormat($teacher['minPrice']); ?> - <?php echo CommonHelper::displayMoneyFormat($teacher['maxPrice']); ?></h6>
									</div>

									<!-- Reviews[ -->
									<div class="ratings -margin-b-15">
										<span class="ratings__star -display-inline">
											<?php // if(round($avgRating)){
											for ($i = 0; $i < round($avgRating); $i++) { ?>
												<img src="<?php echo CONF_WEBROOT_URL; ?>images/star-filled.svg" alt="">
											<?php }
											for ($i = 0; $i < 5 - round($avgRating); $i++) { ?>
												<img src="<?php echo CONF_WEBROOT_URL; ?>images/star-empty.svg" alt="">
											<?php } //} 
											?>

										</span>
										<?php if ($totReviews) { ?>
											<span class="ratings__count -display-inline"><a href="#itemRatings" class="-link-underline"><?php echo $totReviews . ' ' . Label::getLabel('Lbl_Reviews'); ?></a></span>
										<?php } ?>
									</div>

									<!-- Favorite[ -->
									<a href="javascript:void(0)" onClick="toggleTeacherFavorite(<?php echo $teacher['user_id']; ?>,this)" class="btn btn--small btn--bordered btn--fav <?php echo ($teacher['uft_id']) ? 'is-active' : ''; ?>">
										<span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="12" viewBox="0 0 14 12">
												<path d="M1313.03,714.438l-4.53,4.367-4.54-4.375a4.123,4.123,0,0,1-1.46-2.774,3.455,3.455,0,0,1,.17-1.117,2.067,2.067,0,0,1,.43-0.769,1.972,1.972,0,0,1,.63-0.465,2.818,2.818,0,0,1,.74-0.242,4.378,4.378,0,0,1,.76-0.063,2.222,2.222,0,0,1,.88.2,3.987,3.987,0,0,1,.86.5,8.351,8.351,0,0,1,.68.563,6.435,6.435,0,0,1,.47.48,0.506,0.506,0,0,0,.76,0,6.435,6.435,0,0,1,.47-0.48,8.351,8.351,0,0,1,.68-0.563,3.987,3.987,0,0,1,.86-0.5,2.222,2.222,0,0,1,.88-0.2,4.378,4.378,0,0,1,.76.063,2.818,2.818,0,0,1,.74.242,1.972,1.972,0,0,1,.63.465,2.067,2.067,0,0,1,.43.769,3.455,3.455,0,0,1,.17,1.117,4.126,4.126,0,0,1-1.47,2.782h0Zm1.48-5.469a3.76,3.76,0,0,0-2.74-.969,3.064,3.064,0,0,0-.99.168,3.963,3.963,0,0,0-.94.453q-0.435.285-.75,0.535c-0.2.167-.4,0.344-0.59,0.532-0.19-.188-0.39-0.365-0.59-0.532s-0.46-.345-0.75-0.535a3.963,3.963,0,0,0-.94-0.453,3.064,3.064,0,0,0-.99-0.168,3.76,3.76,0,0,0-2.74.969,3.586,3.586,0,0,0-.99,2.687,3.479,3.479,0,0,0,.18,1.078,5.7,5.7,0,0,0,.42.946,7.129,7.129,0,0,0,.53.761c0.2,0.248.35,0.418,0.44,0.512a2.683,2.683,0,0,0,.21.2l4.88,4.7a0.48,0.48,0,0,0,.68,0l4.87-4.687a5.122,5.122,0,0,0,1.79-3.516A3.586,3.586,0,0,0,1314.51,708.969Z" transform="translate(-1301.5 -708)" />
											</svg></span>
										<?php echo Label::getLabel('LBL_Favorite'); ?>
									</a>
									<!-- ] -->
									<!-- Sharing[ -->
									<div class="toggle-dropdown">
										<a href="javascript:void(0)" class="btn btn--small btn--bordered toggle-dropdown__link-js">
											<span class="svg-icon">
												<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
													<path d="M591.3,313.672l-2.813-1.406c0.011-.115.016-0.2,0.016-0.266s0-.151-0.016-0.266l2.813-1.406a2.5,2.5,0,1,0-.8-1.828c0,0.063,0,.151.016,0.266l-2.813,1.406a2.5,2.5,0,1,0,0,3.656l2.813,1.406c-0.011.115-.016,0.2-0.016,0.266a2.5,2.5,0,1,0,.8-1.828h0Z" transform="translate(-583.5 -306)" />
												</svg>
											</span>
											<?php echo Label::getLabel('LBL_Share'); ?>
										</a>
										<div class="toggle-dropdown__target toggle-dropdown__target-js">
											<h6><?php echo Label::getLabel('LBL_Share_On'); ?></h6>
											<ul class="social--share clearfix">
												<li class="social--fb"><span class='st_facebook_large' displayText='Facebook'><img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/social_01.svg"></span></li>
												<li class="social--tw"><span class='st_twitter_large' displayText='Tweet'><img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/social_02.svg"></span></li>
												<li class="social--pt"><span class='st_pinterest_large' displayText='Pinterest'><img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/social_05.svg"></span></li>
												<li class="social--mail"><span class='st_email_large' displayText='Email'><img alt="" src="<?php echo CONF_WEBROOT_URL; ?>images/social_06.svg"></span></li>
											</ul>
										</div>
									</div>
								</div>
								<!-- ] -->
							</div>

							<!-- [-->
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
								<div class="box-highlighted box-language">
									<div class="row d-block justify-content-between">
										<div class="col"><strong><?php echo Label::getLabel('LBL_Teaches:'); ?></strong></div>
										<div class="col"><?php echo CommonHelper::getTeachLangs($teacher['utl_slanguage_ids'], '', 1); ?></div>
									</div>
								</div>
								<div class="box-highlighted">
									<div class="row justify-content-between">
										<div class="col"><strong><?php echo Label::getLabel('LBL_Students:'); ?></strong></div>
										<div class="col -align-right"><?php echo $teacher['studentIdsCnt']; ?></div>
									</div>
								</div>
								<div class="box-highlighted">
									<div class="row justify-content-between">
										<div class="col"><strong><?php echo Label::getLabel('LBL_Lessons:'); ?></strong></div>
										<div class="col -align-right"><?php echo $teacher['teacherTotLessons']; ?></div>
									</div>
								</div>
							</div>
							<!-- ] -->

						</div>
					</div>

					<hr>

					<div class="box__profile-body">
						<?php
						/* Spoken Languages[ */
						$this->includeTemplate('teachers/_partial/spokenLanguages.php', $teacher, false);
						/* ] */
						?>

						<div class="content-inline">
							<p class="-small-title"><strong><?php echo Label::getLabel('LBL_About_Me'); ?></strong></p>
							<p><?php echo nl2br($teacher['user_profile_info']); ?></p>
							<!--<a href="javascript:void(0)" class="btn btn--small btn--bordered btn--wide btn--arrow">Show More</a>-->
						</div>
					</div>
				</div>

				<?php $this->includeTemplate('teachers/_partial/preferencesSkills.php', $teacher, false); ?>

				<div class="box box--toggle">
					<div class="box__head box__head-trigger box__head-trigger-js">
						<h4><?php echo Label::getLabel('LBL_Resume'); ?></h4>
					</div>
					<div class="box__body box__body-target box__body-target-js -padding-30" id="qualificationsList">
					</div>
				</div>
				<?php if (round($avgRating) > 0) { ?>
					<div class="box box--toggle">
						<div class="box__head box__head-trigger box__head-trigger-js">
							<h4><?php echo Label::getLabel('Lbl_Ratings'); ?></h4>
						</div>
						<div class="box__body box__body-target box__body-target-js -padding-30">

							<div class="ratings -display-inline">
								<span class="ratings__star -display-inline">
									<?php for ($i = 0; $i < round($avgRating); $i++) { ?>
										<img src="<?php echo CONF_WEBROOT_URL; ?>images/star-filled.svg" alt="">
									<?php }
									for ($i = 0; $i < 5 - round($avgRating); $i++) { ?>
										<img src="<?php echo CONF_WEBROOT_URL; ?>images/star-empty.svg" alt="">
									<?php } ?>
								</span>
								<h6><?php echo $avgRating; ?> <?php echo Label::getLabel('Lbl_Average'); ?></h6>
								<p><?php echo $totReviews; ?> <?php echo Label::getLabel('Lbl_ratings'); ?>, <span id="reviewsTotal"><?php echo $reviews['totStudents']; ?></span> <?php echo Label::getLabel('Lbl_students'); ?></p>
							</div>
							<span class="-gap"></span>

							<?php echo $frmReviewSearch->getFormHtml(); ?>
							<div id="itemRatings"></div>
							<div id="loadMoreReviewsBtnDiv"></div>
						</div>
					</div>
				<?php } ?>
			</div>


			<div class="col-xl-4 col-lg-4">
				<?php
				if ($teacher['isFreeTrialEnabled']) {
					$onclick = "";
					$btnClass = "btn-secondary";
					$disabledText = "disabled";
					$btnText =  "LBL_You_already_have_availed_the_Trial";
					if (!$teacher['isAlreadyPurchasedFreeTrial'] && !empty($teacherLanguage)) {
						$disabledText = "";
						$onclick = "onclick=\"viewCalendar(" . $teacher['user_id'] . ",'free_trial'," . $teacherLanguage . ")\"";
						$btnClass = 'btn-primary';
						$btnText =  "LBL_Book_Free_Trial";
					}
					if ($loggedUserId == $teacher['user_id']) {
						$onclick = "";
						$disabledText = "disabled";
					}
				?>
					<div class="box box--cta -padding-30">
						<h4 class="-text-bold"><?php echo Label::getLabel('LBL_FREE_Trail'); ?></h4>
						<p><?php echo Label::getLabel('LBL_Book_your_trial_FREE_for_30_Mins_only'); ?></p>
						<button type="button" <?php echo $onclick; ?> class="btn <?php echo $btnClass . ' ' . $disabledText; ?> btn--large btn--block" <?php echo $disabledText; ?>><?php echo Label::getLabel($btnText); ?></button>
					</div>
				<?php } ?>

				<div class="box box--cta box--sticky">
					<div class="fat-tab">
						<?php if (!empty($bookingDuration)) { ?>
							<div class="box-head -padding-20">
								<h4 class="-text-bold"><?php echo Label::getLabel("LBL_Lesson_Prices"); ?></h4>
								<div class="tab-group tabs-scroll-js">
									<ul>
										<li class="is-active">
											<a href="javascript:;" class="tab-a" data-id="tab_single_price">
												<?php echo Label::getLabel("LBL_Single") ?>
											</a>
										</li>
										<li>
											<a href="javascript:;" class="tab-a" data-id="tab_bulk_price">
												<?php echo Label::getLabel("LBL_Bulk") ?>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="box-body">
								<div class="tab-body">
									<div class="tab tab-active" data-id="tab_single_price">
										<div class="scrollbar custom-scrollbar scrollbar-js">
											<div class="Lprice-cover" id="style-4">
												<?php
												$lessonPackages = $teacher['lessonPackages'];
												if (count($lessonPackages)) {
													$lessonPackage = $lessonPackages[0];
												}
												// prx($teacherLangPrices);
												foreach ($teacherLangPrices['single'] as $slot => $prices) : ?>
													<div class="Lprice__wrapper">
														<div class="Lprice-head">
															<b><?php echo sprintf(Label::getLabel('LBL_%d_min'), $slot) ?></b>
														</div>
														<div class="Lprice-body">
															<ul>
																<?php
																foreach ($prices as $lang_id => $price_info) :
																	$onclick = '';
																	if (!empty($lessonPackages) && $loggedUserId != $teacher['user_id']) {
																		$onclick = "cart.add('" . $teacher['user_id'] . "','" . $lessonPackage['lpackage_id'] . "','','','" . $lang_id . "', 0, '" . $slot . "')";
																	}
																?>
																	<li>
																		<a href="javascript:;" onClick="<?php echo $onclick; ?>">
																			<div class="Lprice-lang"><?php echo $price_info['lang_name'] ?></div>
																			<div class="Lprice-price"><?php echo CommonHelper::displayMoneyFormat($price_info['price']) ?></div>
																		</a>
																	</li>
																<?php endforeach; ?>
															</ul>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
									<div class="tab" data-id="tab_bulk_price">
										<div class="scrollbar custom-scrollbar scrollbar-js">
											<div class="Lprice-cover" id="style-4">
												<?php
												foreach ($teacherLangPrices['bulk'] as $slot => $prices) : ?>
													<div class="Lprice__wrapper">
														<div class="Lprice-head">
															<b><?php echo sprintf(Label::getLabel('LBL_%d_min'), $slot) ?></b>
														</div>
														<div class="Lprice-body">
															<ul>
																<?php
																foreach ($prices as $lang_id => $price_info) :
																	$onclick = '';
																	if (!empty($lessonPackages) && $loggedUserId != $teacher['user_id']) {
																		$onclick = "cart.add('" . $teacher['user_id'] . "','" . $lessonPackage['lpackage_id'] . "','','','" . $lang_id . "', 0, '" . $slot . "')";
																	}
																?>
																	<li>
																		<a href="javascript:;" onClick="<?php echo $onclick; ?>">
																			<div class="Lprice-lang"><?php echo $price_info['lang_name'] ?></div>
																			<div class="Lprice-price"><?php echo CommonHelper::displayMoneyFormat($price_info['price']) ?></div>
																		</a>
																	</li>
																<?php endforeach; ?>
															</ul>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php $this->includeTemplate('teachers/_partial/book_lesson.php', array('teacher' => $teacher, 'bookingDuration' => $bookingDuration, 'loggedUserId' => $loggedUserId), false); ?>

							</div>
					</div>

					<hr class="-no-margin">
					<div class="box box--cta -padding-30 -no-margin-top -align-center">
						<a href="javascript:void(0);" onclick="viewCalendar('<?php echo $teacher['user_id']; ?>','paid')" class="-link -color-secondary"><?php echo Label::getLabel('LBL_View_Calendar_Availability'); ?></a>
					</div>
				</div>

			</div>
		</div>

	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		searchQualifications(<?php echo $teacher['user_id']; ?>);
	});
</script>
<?php echo $this->includeTemplate('_partial/shareThisScript.php'); ?>