<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section section--grey section--page">
	<div class="container container--fixed">
		<div class="page-panel -clearfix">

			<!--panel left start here-->
			<div class="page-panel__left">
				<?php
				$studentIds = explode(',', $userDetails['studentIds']);
				$this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
			</div>
			<!--panel left end here-->

			<!--panel right start here-->
			<div class="page-panel__right">

				<div class="page-panel__inner-l">

					<!--page-head start here-->
					<div class="page-head">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<h1><?php echo Label::getLabel('LBL_Dashboard'); ?></h1>
							</div>

							<!--<div>
								<div class="select-box toggle-group">
								<a href="javascript:void(0)" class="select-box__value toggle__trigger-js">
								Calender</a>
								<div class="select-box__target -skin toggle__target-js" style="display: none;">
								<div class="listing listing--vertical">
								<ul>
								<li><a href="dashboard.html">Calender</a></li>
								<li><a href="dashboard_list.html">List</a></li>
								</ul>
								</div>
								</div>
								</div>-->
							<div>
								<div class="tab-swticher tab-swticher-small">
									<a href="<?php echo CommonHelper::generateUrl('Teacher'); ?>" class="btn btn--large is-active"><?php echo Label::getLabel('LBL_List'); ?></a>
									<a onclick="viewCalendar();" href="javascript:void(0);" class="btn btn--large"><?php echo Label::getLabel('LBL_Calendar'); ?></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-list-group">
						<div style="display:none">
							<?php
							$frmSrch->setFormTagAttribute('onsubmit', 'searchLessons(this); return(false);');
							echo $frmSrch->getFormHtml();
							?>
						</div>
						<!--h6>Today</h6-->
						<span class="-gap"></span>
						<div class="col-list-container" id="listItemsLessons">
						</div>
					</div>
				</div>

				<div class="page-panel__inner-r">
					<div class="box-group">
						<div class="box -align-center" style="margin-bottom: 30px;">

							<div class="-padding-20">
								<div class="avtar avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($userDetails['user_first_name']); ?>">
									<?php
									if (true == User::isProfilePicUploaded()) {
										$img = FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'user', array($userDetails['user_id'], 'MEDIUM'), CONF_WEBROOT_FRONT_URL), CONF_DEF_CACHE_TIME, '.jpg');
										echo '<img src="' . $img . '" />';
									}
									?>
									<!--<span class="tag-online"></span>-->
								</div>

								<span class="-gap"></span>
								<h3 class="-display-inline">
									<h3 class="-display-inline"><?php echo $userDetails['user_first_name']; ?></h3>
								</h3>

								<?php if ($userDetails['user_country_id'] > 0) { ?>
									<span class="flag -display-inline"><img src="<?php echo CommonHelper::generateUrl('Image', 'countryFlag', array($userDetails['user_country_id'], 'DEFAULT'), CONF_WEBROOT_FRONT_URL); ?>" alt=""></span>
								<?php } ?>

								<p class="-no-margin-bottom"><?php
																echo $userDetails['countryName'] . "<br>";
																echo CommonHelper::getDateOrTimeByTimeZone($userDetails['user_timezone'], 'h:i A');
																echo " (" . Label::getLabel('LBL_TIMEZONE_STRING') . " " . CommonHelper::getDateOrTimeByTimeZone($userDetails['user_timezone'], ' P') . ")";

																?></p>
								<?php if ($viewProfile) { ?>
									<p><a href="<?php echo CommonHelper::generateUrl('Teachers', 'profile') . '/' . $userDetails['user_url_name'] ?>" class="-link-underline link-color"> <?php echo Label::getLabel('LBL_View_Profile'); ?> </a></p>
								<?php } ?>
							</div>

							<div class="tabled">
								<div class="tabled__cell">
									<h3 class="-color-secondary"><?php echo $userDetails['teacherSchLessons']; ?></h3> <?php echo Label::getLabel('LBL_Scheduled'); ?>
								</div>
								<div class="tabled__cell">
									<h3 class="-color-primary"><?php echo $userDetails['teacherTotLessons']; ?></h3> <?php echo Label::getLabel('LBL_Lesson(s)'); ?>
								</div>
							</div>
						</div>

						<div class="box -padding-20 box--earning" style="margin-bottom: 30px;">

							<div class="row justify-content-between align-items-center">
								<div class="col-12 ">
									<h6><?php echo Label::getLabel('LBL_Earnings'); ?></h6>
								</div>
								<div class="col-12">
									<div class="form--small">
										<select id="earningMonth" onchange="getStatisticalData(<?php echo Statistics::REPORT_EARNING; ?>)">
											<?php foreach ($durationArr as $key => $duration) { ?>
												<option value="<?php echo $key; ?>"><?php echo $duration; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<span class="-gap-10"></span>
							<div id="earningContent"> </div>

						</div>

						<?php if ($userDetails['studentIds']) { ?>
							<div class="box -padding-20 -align-center" style="margin-bottom: 30px;">
								<h4><?php echo Label::getLabel('LBL_My_Students'); ?></h4>
								<div class="avtars-list">
									<ul>
										<?php foreach ($studentIds as $studentId) { ?>
											<li>
												<a href="javascript:void(0)">
													<figure class="avtar avtar--small" data-text="A">
														<img src="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('image', 'user', array($studentId, 'ExtraSmall'), CONF_WEBROOT_FRONT_URL), CONF_DEF_CACHE_TIME, '.jpg') ?>" alt="">
													</figure>
												</a>
											</li>
										<?php } ?>
									</ul>
								</div>
								<a href="/teacher-students" class="-link-underline"><?php echo Label::getLabel('LBL_See_all_Students'); ?></a>
							</div>
						<?php } ?>
					</div>
				</div>
				<!--page-head end here-->
			</div>
		</div>
		<!--panel right end here-->

	</div>
	</div>
</section>
<div class="gap"></div>
<script>
	$(document).ready(function() {
		getStatisticalData(<?php echo Statistics::REPORT_EARNING; ?>);
	})
</script>