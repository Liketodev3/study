<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$activeMettingTool = FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_STRING, ApplicationConstants::MEETING_COMET_CHAT);
?>
<script>
var userIsTeacher = <?php echo $userIsTeacher?:0; ?>;

var isCometChatMeetingToolActive = '<?php echo $activeMettingTool == ApplicationConstants::MEETING_COMET_CHAT ?>';
</script>

<section class="section section--grey section--page">
	<div class="container container--fixed">
		<div class="page-panel -clearfix">

			<!--panel left start here-->
			<div class="page-panel__left">
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
			</div>
            <!--panel left end here-->

			<div class="page-panel__right">

				<!--page-head start here-->
				<div class="page-head">
					<div class="d-flex justify-content-md-between align-items-md-center flex-column flex-md-row">
						<div>
							<h1><?php echo Label::getLabel('LBL_Settings'); ?></h1>
						</div>
						<div>

						</div>
						<?php if($userIsTeacher){ ?>
						<div class="progress-wrapper d-flex align-items-start flex-column">
							<div class="progress--top">
								<span class="profile-progress"><span class="spn_must_field">*</span> <?php echo Label::getLabel('LBL_Profile_progress');?></span>
								<span class="txt progress-count-js"></span>
							</div>
							<div class="progress">
								<div class="progress-bar active progress-bar-striped progress-bar-animated teacher-profile-progress-bar-js" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
							</div>
						</div>
					<?php } ?>
						<!--<div>
							<div class="tab-swticher tab-swticher-small">
								<a href="dashboard_list.html" class="btn btn--large">List</a>
								<a href="dashboard.html" class="btn btn--large is-active">Calnder</a>
							</div>
						</div>-->
					</div>
				</div>
				<!--page-head end here-->

				<div class="tabs-inline tabs-scroll-js">
					<ul>
						<li class="is-active">
							<a href="javascript:void(0);" class="profile-Info-js" onClick="profileInfoForm()">
								<?php echo Label::getLabel('LBL_General');?><span class="spn_must_field">*</span>
							</a>
						</li>

						<?php if( User::isTeacher() ){ ?>
							<li class="">
								<a href="javascript:void(0);" class="general-availability-js" onClick="teacherGeneralAvailability()">
									<?php echo Label::getLabel('LBL_Availability');?><span class="spn_must_field">*</span>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);"  onClick="teacherWeeklySchedule()">
								<?php echo Label::getLabel('LBL_Weekly_Schedule');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" class="teacher-tech-lang-price-js" id="teacher-tech-lang-price-js" onClick="teacherSettingsForm()">
								<?php echo Label::getLabel('LBL_Price');?><span class="spn_must_field">*</span>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" class="teacher-qualification-js" onClick="teacherQualification()">
								<?php echo Label::getLabel('LBL_Experience');?><span class="spn_must_field">*</span>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" class="teacher-preferences-js" onClick="teacherPreferencesForm()">
								<?php echo Label::getLabel('LBL_Skills');?><span class="spn_must_field">*</span>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" class="teacher-lang-form-js" onClick="teacherLanguagesForm()">
								<?php echo Label::getLabel('LBL_Languages');?><span class="spn_must_field">*</span>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="bankInfoForm()">
								<?php echo Label::getLabel('LBL_Payments');?>
								</a>
							</li>
							<?php } ?>
							<li class="">
								<a href="javascript:void(0);" onClick="changePasswordForm()">
									<?php echo Label::getLabel('LBL_Password_/_Email');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="getCookieConsentForm()">
									<?php echo Label::getLabel('LBL_cookie_consent');?>
								</a>
							</li>
					</ul>
				</div>

				<!--general tab start here-->
				<div class="box -padding-20 box--minheight">
					<div id="profileInfoFrmBlock">
						<?php echo Label::getLabel('LBL_Loading..'); ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
<div class="gap"></div>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/> 	