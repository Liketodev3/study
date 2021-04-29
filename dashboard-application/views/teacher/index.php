<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<!-- [ PAGE ========= -->
 <!-- <main class="page"> -->
		<div class="dashboard">
			<div class="dashboard__primary">
				<div class="page__head">
					<h1><?php echo Label::getLabel('LBL_Dashboard') ?></h1>
				</div>
				<div class="page__body">
					<?php if(false == $teacherProfileProgress['isProfileCompleted']){ ?>
					<!-- [ INFO BAR ========= -->
					<div class="infobar infobar--primary">
						<div class="row justify-content-between align-items-center">
							<div class="col-sm-8 col-lg-6 col-xl-8">
								<div class="d-flex">
									<div class="infobar__media margin-right-5">
										<div class="infobar__media-icon infobar__media-icon--tick"></div>
									</div>
									<div class="infobar__content">
										<h6 class="margin-bottom-1"><?php echo str_replace('{user-first-name}', $userDetails['user_first_name'], Label::getLabel('LBL_TEACHER_DASHBOARD_HEADING_{user-first-name}')); ?></h6>
										<p class="margin-0"><?php echo Label::getLabel('LBL_TEACHER_DASHBOARD_INFO_TEXT'); ?></p>
									</div>
								</div>
							</div>

							<div class="col-sm-4 col-lg-3  col-xl-4">
								<div class="-align-right">
									<a href="<?php echo CommonHelper::generateUrl('Account', 'ProfileInfo');?>" class="btn bg-secondary"><?php echo Label::getLabel('Lbl_Complete_Profile') ?></a>
								</div>
							</div>

						</div>
					</div>
					<!-- ] -->
					<?php } ?>
					<div class="stats-row margin-bottom-6">
						<div class="row align-items-center">
							<div class="col-lg-4 col-md-6 col-sm-6">
								<div class="stat">
									<div class="stat__amount">
										<span><?php echo Label::getLabel('Lbl_Earnings'); ?></span>
										<h5><?php echo CommonHelper::displayMoneyFormat($earningData); ?></h5>
									</div>
									<div class="stat__media bg-yellow">
										<svg class="icon icon--money icon--40 color-white"><use xlink:href="images/sprite.yo-coach.svg#stats"></use></svg>
									</div>
									<a href="#" class="stat__action"></a>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-6">
								<div class="stat">
									<div class="stat__amount">
										<span><?php echo Label::getLabel('LBL_Scheduled'); ?></span>
										<h5><?php echo $userDetails['teacherSchLessons']; ?></h5>
									</div>
									<div class="stat__media bg-secondary">
										<svg class="icon icon--money icon--40 color-white"><use xlink:href="images/sprite.yo-coach.svg#stats_1"></use></svg>
									</div>
									<a href="<?php echo CommonHelper::generateUrl('TeacherScheduledLessons'); ?>" class="stat__action"></a>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-6">
								<div class="stat">
									<div class="stat__amount">
										<span> <?php echo Label::getLabel('LBL_Wallet'); ?></span>
										<h5><?php echo $userTotalWalletBalance; ?></h5>
									</div>
									<div class="stat__media bg-primary">
										<svg class="icon icon--money icon--40 color-white"><use xlink:href="images/sprite.yo-coach.svg#stats_2"></use></svg>
									</div>
									<a href="<?php echo CommonHelper::generateUrl('Wallet'); ?>" class="stat__action"></a>
								</div>
							</div>
						</div>
					</div>

					<div class="page-panel">
						<div class="page-panel__head border-bottom-0">
							<div class="row">
								<div class="col-md-6">
									<h4><?php echo Label::getLabel('Lbl_Sale_Statistics'); ?></h4>
								</div>
							</div>
						</div>
						<div class="page-panel__body">
							<div class="row margin-bottom-6">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<div class="sale-stat sale-stat--primary color-yellow">
										<div class="sale-stat__count">
											<span><?php echo Label::getLabel('Lbl_Sales'); ?></span>
											<h5>$110.00</h5>
										</div>

										<div class="sale-stat__select">
											<div class="form-inline__item">
											<select id="earningMonth" onchange="getStatisticalData(1)">
												<?php foreach($durationArr as $key => $duration){?>
													<option value="<?php echo $key; ?>"><?php echo $duration; ?></option>
												<?php }?>
											</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<div class="sale-stat sale-stat--secondary color-secondary">
										<div class="sale-stat__count">
											<span><?php echo Label::getLabel('LBL_Lessons_sold'); ?></span>
											<h5>113</h5>
										</div>

										<div class="sale-stat__select">
											<div class="form-inline__item">
												<select id="lessonsMonth" onchange="getStatisticalData(2)">
													<?php foreach($durationArr as $key=>$duration){?>
														<option value="<?php echo $key; ?>"><?php echo $duration; ?></option>
													<?php }?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="graph-media">
								<img src="images/graph.png" alt="">
							</div>
						</div>

					</div>

				</div>

				<!-- <div class="page__footer align-center">
					<p class="small">Copyright © 2021 Yo!Coach Developed by <a href="#" class="underline color-primary">FATbit Technologies</a> . </p>
				</div> -->
			</div>

			<div class="dashboard__secondary">
				<div class="status-bar">
					<div class="status-bar__head">
						<h5><?php echo Label::getLabel('LBL_Upcoming_Lessons'); ?></h5>
						<a href="#" class="color-secondary underline padding-top-3 padding-bottom-3"><?php echo Label::getLabel('LBL_ViewAll'); ?></a>
					</div>
					
					<div class="status-bar__body">
						<div class="calendar">
							<img src="images/calendar.png" alt="">
						</div>

						<div class="listing-window">
							<div class="scrollbar scrollbar-js">
								<div class="lesson-list-container">
									<div class="date"><span>Friday, Feb 04, 2021</span></div>
									<div class="lesson-list">
										<div class="lesson-list__left">
											<div class="avtar avtar--small avtar--centered" data-title="M">
												<img src="images/320x320_1.jpg" alt="">
											</div>
										</div>
										<div class="lesson-list__right">
											<p>Mark Boucher</p>
											<p class="lesson-time"><span>09:00 PM</span>German, 60 Mins Of Lession</p>
										</div>
										<a href="javascript:void(0)" class="lesson-list__action"></a>
									</div>
									<div class="lesson-list">
										<div class="lesson-list__left">
											<div class="avtar avtar--small avtar--centered" data-title="M">
												<img src="images/320x320_2.jpg" alt="">
											</div>
										</div>
										<div class="lesson-list__right">
											<p>Stephen Fleming</p>
											<p class="lesson-time"><span>09:00 PM</span>German, 60 Mins Of Lession</p>
										</div>
										<a href="javascript:void(0)" class="lesson-list__action"></a>
									</div>
									<div class="lesson-list">
										<div class="lesson-list__left">
											<div class="avtar avtar--small avtar--centered" data-title="M">
												<img src="images/320x320_3.jpg" alt="">
											</div>
										</div>
										<div class="lesson-list__right">
											<p>James Maria</p>
											<p class="lesson-time"><span>09:00 PM</span>German, 60 Mins Of Lession</p>
										</div>
										<a href="javascript:void(0)" class="lesson-list__action"></a>
									</div>
								</div>

								<div class="lesson-list-container">
									<div class="date"><span>Friday, Feb 04, 2021</span></div>
									<div class="lesson-list">
										<div class="lesson-list__left">
											<div class="avtar avtar--small avtar--centered" data-title="M">
												<img src="images/320x320_1.jpg" alt="">
											</div>
										</div>
										<div class="lesson-list__right">
											<p>Mark Boucher</p>
											<p class="lesson-time"><span>09:00 PM</span>German, 60 Mins Of Lession</p>
										</div>
										<a href="javascript:void(0)" class="lesson-list__action"></a>
									</div>
									<div class="lesson-list">
										<div class="lesson-list__left">
											<div class="avtar avtar--small avtar--centered" data-title="M">
												<img src="images/320x320_2.jpg" alt="">
											</div>
										</div>
										<div class="lesson-list__right">
											<p>Stephen Fleming</p>
											<p class="lesson-time"><span>09:00 PM</span>German, 60 Mins Of Lession</p>
										</div>
										<a href="javascript:void(0)" class="lesson-list__action"></a>
									</div>
									<div class="lesson-list">
										<div class="lesson-list__left">
											<div class="avtar avtar--small avtar--centered" data-title="M">
												<img src="images/320x320_3.jpg" alt="">
											</div>
										</div>
										<div class="lesson-list__right">
											<p>James Maria</p>
											<p class="lesson-time"><span>09:00 PM</span>German, 60 Mins Of Lession</p>
										</div>
										<a href="javascript:void(0)" class="lesson-list__action"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<!-- ] -->
<script>
	$(document).ready(function() {
		getStatisticalData(<?php echo Statistics::REPORT_EARNING; ?>);
	})
</script>