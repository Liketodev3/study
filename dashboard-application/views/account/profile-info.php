<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$activeMettingTool = FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_STRING, ApplicationConstants::MEETING_COMET_CHAT);
?>
<script>
	var userIsTeacher = <?php echo $userIsTeacher ?: 0; ?>;

	var isCometChatMeetingToolActive = '<?php echo $activeMettingTool == ApplicationConstants::MEETING_COMET_CHAT ?>';
</script>

<!-- [ PAGE ========= -->
<main class="page">
	<div class="container container--fixed">

		<div class="page__head">
			<h1><?php echo Label::getLabel('LBL_Account_Settings'); ?><< /h1>
		</div>

		<div class="page__body">
			<!-- [ INFO BAR ========= -->
			<div class="infobar">
				<div class="row justify-content-between align-items-start">
					<div class="col-lg-8 col-sm-8">
						<div class="d-flex">
							<div class="infobar__media margin-right-5">
								<div class="infobar__media-icon infobar__media-icon--alert">!</div>
							</div>
							<div class="infobar__content">
								<h6 class="margin-bottom-1">Complete Your profile</h6>
								<p class="margin-0">To successfully register your profile as an expert and to you available in search results.
									<a href="javascript:void(0)" class="color-secondary underline padding-top-3 padding-bottom-3 expand-js">Learn More</a>
								</p>

								<div class="infobar__content-more margin-top-3 expand-target-js" style="display: none;">
									<div class="infobar__list-content">
										<ol>
											<li>Profile needs to be 80% completed</li>
											<li>You have to complete lorem ipsum dolar summit text</li>
											<li>After verify all the details you have to mark availbility in calendar section.</li>
										</ol>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-4">
						<div class="profile-progress margin-top-2">
							<div class="profile-progress__meta margin-bottom-2">
								<div class="d-flex align-items-center justify-content-between">
									<div><span class="small"> <?php echo Label::getLabel('LBL_Profile_progress'); ?></span></div>
									<div><span class="small bold-700 progress-count-js"></span></div>
								</div>
							</div>
							<div class="profile-progress__bar">
								<div class="progress progress--small progress--round">
									<!-- <div class="progress__bar bg-green" role="progressbar" style="width:60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div> -->
									<div class="progress-bar">
										<div class="progress__step is-active"></div>
										<div class="progress__step"></div>
										<div class="progress__step"></div>
										<div class="progress__step"></div>
										<div class="progress__step"></div>
										<div class="progress__step"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- ] -->
			<!-- [ PAGE PANEL ========= -->
			<div class="page-panel page-panel--flex min-height-500">
				<div class="page-panel__small">
					<nav class="menu menu--vertical menu--steps tabs-scrollable-js">
						<ul>
							<li class="menu__item is-active">
								<a href="javascript:void(0);" class="profile-Info-js" onClick="profileInfoForm()">
									<?php echo Label::getLabel('LBL_Personal_Info'); ?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a href="javascript:void(0);" class="teacher-lang-form-js" onClick="teacherLanguagesForm()">
									<?php echo Label::getLabel('LBL_Languages'); ?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a href="javascript:void(0);" class="teacher-tech-lang-price-js" id="teacher-tech-lang-price-js" onClick="teacherSettingsForm()">
									<?php echo Label::getLabel('LBL_Price'); ?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a  href="javascript:void(0);" class="teacher-qualification-js" onClick="teacherQualification()">
									<?php echo Label::getLabel('LBL_Experience'); ?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a href="javascript:void(0);" class="teacher-preferences-js" onClick="teacherPreferencesForm()">
									<?php echo Label::getLabel('LBL_Skills'); ?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a  href="javascript:void(0);" onClick="bankInfoForm()">
									<?php echo Label::getLabel('LBL_Payments'); ?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a href="javascript:void(0);" onClick="changePasswordForm()">
									<?php echo Label::getLabel('LBL_Password_/_Email');?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a href="javascript:void(0);" onClick="getCookieConsentForm()">
									<?php echo Label::getLabel('LBL_cookie_consent');?>
									<span class="menu__icon"></span>
								</a>
							</li>
							<li class="menu__item">
								<a href="teacher_settings_deactivate_account.html">
									
									<?php echo Label::getLabel('LBL_Deactivate_Account');?>
									<span class="menu__icon"></span>
								</a>
							</li>
						</ul>
					</nav>
				</div>

				<div class="page-panel__large">

					<div class="content-panel">
						<div class="content-panel__head">
							<div class="d-flex align-items-center justify-content-between">
								<div>
									<h5>Manage Profile</h5>
								</div>
								<div></div>
							</div>
						</div>

						<div class="content-panel__body">

							<div class="form">
								<div class="form__body padding-0">
									<nav class="tabs tabs--line padding-left-6 padding-right-6">
										<ul>
											<li class="is-active"><a href="teacher_settings.html">General</a></li>
											<li><a href="teacher_settings_photos_videos.html">Photos & Videos</a></li>
											<li><a href="teacher_settings_bio_eng.html">English</a></li>
											<li><a href="teacher_settings_bio_arabic.html">Arabic</a></li>
										</ul>
									</nav>
									<div class="tabs-data">

										<div id="profileInfoFrmBlock">

											<div class="action-bar border-top-0">
												<div class="row justify-content-between align-items-center">
													<div class="col-sm-6">
														<div class="d-flex align-items-center">

															<div class="action-bar__media margin-right-5">
																<div class="g-circle">
																	<svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31">
																		<path fill="#fbbb00" d="M6.87,148.63l-1.079,4.028-3.944.083a15.527,15.527,0,0,1-.114-14.474h0l3.511.644,1.538,3.49a9.25,9.25,0,0,0,.087,6.228Z" transform="translate(0 -129.896)" />
																		<path fill="#518ef8" d="M276.516,208.176a15.494,15.494,0,0,1-5.525,14.983h0l-4.423-.226-.626-3.907a9.238,9.238,0,0,0,3.975-4.717h-8.288v-6.132h14.888Z" transform="translate(-245.787 -195.572)" />
																		<path fill="#28b446" d="M53.865,318.262h0a15.5,15.5,0,0,1-23.356-4.742l5.023-4.112a9.219,9.219,0,0,0,13.284,4.72Z" transform="translate(-28.662 -290.675)" />
																		<path fill="#f14336" d="M52.285,3.568,47.263,7.679a9.217,9.217,0,0,0-13.589,4.826L28.625,8.372h0a15.5,15.5,0,0,1,23.661-4.8Z" transform="translate(-26.891)" />
																	</svg>
																</div>
															</div>
															<div class="action-bar__content">
																<p class="margin-bottom-0">Connect your Google Calendar and synchronize all your Yo!Coach lessons with your personal schedule</p>
															</div>

														</div>
													</div>
													<div class="col-sm-auto">
														<a href="#" class="btn social-button social-button--google">
															<span class="social-button__media"><img src="images/google.svg" alt="Connect Google Calendar"></span>
															<span class="social-button__label">Connect Google Calendar</span>
														</a>
													</div>
												</div>
											</div>
											<div class="padding-6">
												<div class="max-width-80">
													<form class="form form--horizontal">

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Username<span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<input type="text" name="user_url_name" value="betty">
																			<small class="user_url_string margin-bottom-0">https://www.teach.yo-coach.com/teachers/profile/<span class="user_url_name_span">James</span>
																			</small>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Name<span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<div class="custom-cols custom-cols--onehal">
																				<ul>
																					<li><input type="text" name="user_url_name" placeholder="First Name"></li>
																					<li><input type="text" name="user_url_name" placeholder="Last Name"></li>
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Gender<span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<div class="custom-cols custom-cols--onehal">
																				<ul class="list-inline list-inline--onehalf">
																					<li><label><span class="radio"><input type="radio" name="user_gender" value="1"><i class="input-helper"></i></span>Male</label></li>
																					<li class="is-active"><label><span class="radio"><input type="radio" name="user_gender" value="2" checked="checked"><i class="input-helper"></i></span>Female</label></li>
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Phone <span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<div class="custom-cols custom-cols--onethird">
																				<ul>
																					<li><input type="text" placeholder="+91"></li>
																					<li><input type="text" placeholder="012456-102-200"></li>
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Country <span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<select>
																				<option>Select</option>
																				<option>India</option>
																				<option>Australia</option>
																				<option>USA</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Time Zone <span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<select>
																				<option>(UTC+01:00) Amsterdam</option>
																				<option>India</option>
																				<option>Australia</option>
																				<option>USA</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Booking Before <span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<select>
																				<option>12 hours</option>
																				<option>India</option>
																				<option>Australia</option>
																				<option>USA</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div class="field-set">
																	<div class="caption-wraper">
																		<label class="field_label">Trial Lesson <span class="spn_must_field">*</span></label>
																	</div>

																	<div class="field-wraper">
																		<div class="field_cover">
																			<label class="switch-group d-flex align-items-center justify-content-between">
																				<span class="switch-group__label">Active</span>
																				<span class="switch switch--small">
																					<input class="switch__label" type="checkbox" checked="">
																					<i class="switch__handle bg-green"></i>
																				</span>

																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="row submit-row">
															<div class="col-sm-auto">
																<div class="field-set">
																	<div class="field-wraper">
																		<div class="field_cover">
																			<input type="submit" value="Save">
																			<input type="button" value="Next">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</form>
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
			<!-- ] -->
		</div>
		<div class="page__footer align-center">
			<p class="small">Copyright © 2021 Yo!Coach Developed by <a href="#" class="underline color-primary">FATbit Technologies</a> . </p>
		</div>
	</div>
</main>
<!-- ] -->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />