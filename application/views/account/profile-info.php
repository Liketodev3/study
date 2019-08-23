<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
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
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h1><?php echo Label::getLabel('LBL_Dashboard'); ?></h1>
						</div>
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
							<a href="javascript:void(0);" onClick="profileInfoForm()">
								<?php echo Label::getLabel('LBL_General');?>
							</a>
						</li>

						<?php if( User::isTeacher() ){ ?>
							<li class="">
								<a href="javascript:void(0);" onClick="teacherGeneralAvailability()">
									<?php echo Label::getLabel('LBL_Availability');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="teacherWeeklySchedule()">
								<?php echo Label::getLabel('LBL_Weekly_Schedule');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="teacherSettingsForm()">
								<?php echo Label::getLabel('LBL_Price');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="teacherQualification()">
								<?php echo Label::getLabel('LBL_Experience');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="teacherPreferencesForm()">
								<?php echo Label::getLabel('LBL_Skills');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="teacherLanguagesForm()">
								<?php echo Label::getLabel('LBL_Languages');?>
								</a>
							</li>
							<li class="">
								<a href="javascript:void(0);" onClick="paypalEmailAddressForm()">
								<?php echo Label::getLabel('LBL_Payments');?>
								</a>
							</li>
							<?php } ?>
							<li class="">
								<a href="javascript:void(0);" onClick="changePasswordForm()">
									<?php echo Label::getLabel('LBL_Password_/_Email');?>
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

				<?php 
				/* <div class="cols--group">
					<!--div class="panel__head">
					<h2><?php echo Label::getLabel('LBL_Profile');?></h2>
					<?php  if( $showTeacherActivateButton ){ ?>
					<a href="<?php echo CommonHelper::generateUrl('Teacher');?>" class="btn btn--secondary btn--sm panel__head_action" title="<?php echo Label::getLabel('LBL_Activate_Teacher_Account'); ?>" ><strong> <?php echo Label::getLabel('LBL_Activate_Teacher_Account'); ?></strong> </a>
					<?php  } ?>
					</div-->
				</div> */
				?>
				
			</div>
		</div>
	</div>
</section>
<div class="gap"></div>