<?php 
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSrch->setFormTagAttribute ( 'onsubmit', 'searchLessons(this); return(false);');
$frmSrch->setFormTagAttribute ( 'class', 'd-none');
?>
<div class="container container--fixed">
	<div class="dashboard">
		<div class="dashboard__primary">
			<div class="page__head">
				<h1><?php echo Label::getLabel('LBL_Dashboard'); ?></h1>
			</div>
			<div class="page__body">	
				<div class="stats-row margin-bottom-6">
					<div class="row align-items-center">
						<div class="col-lg-4 col-md-6 col-sm-6">
							<div class="stat">
								<div class="stat__amount">
									<span><?php echo Label::getLabel('LBL_Lessons_scheduled'); ?></span>
									<h5><?php echo $userDetails['learnerSchLessons']; ?></h5>
								</div>
								<div class="stat__media bg-yellow">
									<svg class="icon icon--money icon--40 color-white">
										<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#planning'; ?>"></use>
									</svg>
								</div>
								<a href="<?php echo CommonHelper::generateUrl('LearnerScheduledLessons')."#".ScheduledLesson::STATUS_SCHEDULED; ?>" class="stat__action"></a>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-6">
							<div class="stat">
								<div class="stat__amount">
									<span><?php echo Label::getLabel('LBL_TOTAL_LESSONS'); ?></span>
									<h5><?php echo $userDetails['learnerTotLessons']; ?></h5>
								</div>
								<div class="stat__media bg-secondary">
									<svg class="icon icon--money icon--40 color-white">
										<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#stats_1'; ?>"></use>
									</svg>
								</div>
								<a href="<?php echo CommonHelper::generateUrl('LearnerScheduledLessons'); ?>" class="stat__action"></a>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-6">
							<div class="stat">
								<div class="stat__amount">
									<span><?php echo Label::getLabel('LBL_TOTAL_Wallet'); ?></span>
									<h5><?php echo $userTotalWalletBalance; ?></h5>
								</div>
								<div class="stat__media bg-primary">
									<svg class="icon icon--money icon--40 color-white">
										<use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#stats_2'; ?>"></use>
									</svg>
								</div>
								<a href="<?php echo CommonHelper::generateUrl('Wallet'); ?>" class="stat__action"></a>
							</div>
						</div>
					</div>
				</div>
				<?php echo $frmSrch->getFormHtml(); ?>
				<div class="page-content">
					<div class="results" id="listItemsLessons">

						<!-- [ LESSON GROUP ========= -->
						<div class="lessons-group margin-top-10">

							<div class="upcoming">
								<h6><?php echo label::getLabel('lbl_Upcoming_Lessons').' ('.$userDetails['learnerSchLessonsExcPast'].')'; ?></h6>
								<a href="<?php echo CommonHelper::generateUrl('LearnerScheduledLessons')."#".ScheduledLesson::STATUS_UPCOMING; ?>" class="color-secondary underline padding-top-3 padding-bottom-3"><?php echo Label::getLabel('LBL_VIEW_ALL') ?></a>
							</div>
							
							<!-- [ LESSON CARD ========= -->
							<div class="card-landscape">

								<div class="card-landscape__colum card-landscape__colum--first">

									<div class="card-landscape__head">
										<time class="card-landscape__time">08:00 PM</time>
										<date class="card-landscape__date">Monday, March 15, 2021</date>
									</div>


									<div class="timer">
										<div class="timer__media"><span><svg class="icon icon--clock icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#clock"></use>
												</svg></span></div>
										<div class="timer__content">
											<div class="timer__controls timer-js">
												<div class="timer__digit">00</div>
												<div class="timer__digit">06</div>
												<div class="timer__digit">33</div>
												<div class="timer__digit">16</div>
											</div>
										</div>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--second">
									<div class="card-landscape__head">
										<span class="card-landscape__title">French, 60 Minutes Of Lesson</span>
										<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
									</div>

									<div class="card-landscape__docs">
										<div class="d-flex align-items-center">
											<a href="#" class="attachment-file">
												<svg class="icon icon--issue icon--attachement icon--xsmall color-black">
													<use xlink:href="images/sprite.yo-coach.svg#attach"></use>
												</svg>
												Basic Words &amp; Numeracy in French
											</a>
										</div>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--third">

									<div class="card-landscape__actions">

										<div class="profile-meta">
											<div class="profile-meta__media">
												<span class="avtar" data-title="M"><img src="images/emp_6.jpg" alt=""></span>
											</div>
											<div class="profile-meta__details">
												<p class="bold-600 color-black">Mark Boucher</p>
												<p class="small">South Africa</p>
											</div>
										</div>

										<div class="actions-group">

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--enter icon--18">
													<use xlink:href="images/sprite.yo-coach.svg#enter"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--cancel icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#cancel"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Cancel</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--reschedule icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#reschedule"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Reschedule</div>
											</a>

										</div>

									</div>

								</div>



							</div>
							<!-- ] ========= -->
							<!-- [ LESSON CARD ========= -->
							<div class="card-landscape">

								<div class="card-landscape__colum card-landscape__colum--first">

									<div class="card-landscape__head">
										<time class="card-landscape__time">09:00 PM</time>
										<date class="card-landscape__date">Monday, March 15, 2021</date>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--second">
									<div class="card-landscape__head">
										<span class="card-landscape__title">German, 60 Minutes Of Lesson</span>
										<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
									</div>



									<div class="card-landscape__docs">
										<div class="d-flex align-items-center">
											<a href="#" class="attachment-file">
												<svg class="icon icon--issue icon--attachement icon--xsmall color-black">
													<use xlink:href="images/sprite.yo-coach.svg#attach"></use>
												</svg>
												Basic Words &amp; Numeracy in French
											</a>



										</div>

									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--third">

									<div class="card-landscape__actions">

										<div class="profile-meta">
											<div class="profile-meta__media">
												<span class="avtar" data-title="M"><img src="images/emp_1.jpg" alt=""></span>
											</div>
											<div class="profile-meta__details">
												<p class="bold-600 color-black">James Anderson</p>
												<p class="small">Newzeland</p>
											</div>
										</div>

										<div class="actions-group">

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--enter icon--18">
													<use xlink:href="images/sprite.yo-coach.svg#enter"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--view icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Lesson View</div>
											</a>


											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--reschedule icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#reschedule"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Reschedule</div>
											</a>

										</div>

									</div>

								</div>


							</div>
							<!-- ] ========= -->

							<!-- [ LESSON CARD ========= -->
							<div class="card-landscape">

								<div class="card-landscape__colum card-landscape__colum--first">

									<div class="card-landscape__head">
										<time class="card-landscape__time">11:00 PM</time>
										<date class="card-landscape__date">Tuesday, March 16, 2021</date>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--second">
									<div class="card-landscape__head">
										<span class="card-landscape__title">Spanish, 60 Minutes Of Lesson</span>
										<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
									</div>

									<div class="card-landscape__docs">
										<div class="d-flex align-items-center">
											<a href="#" class="attachment-file">
												<svg class="icon icon--issue icon--attachement icon--xsmall color-black">
													<use xlink:href="images/sprite.yo-coach.svg#attach"></use>
												</svg>
												Basic Words &amp; Numeracy in French
											</a>
										</div>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--third">
									<div class="card-landscape__actions">
										<div class="profile-meta">
											<div class="profile-meta__media">
												<span class="avtar" data-title="M"><img src="images/320x320_4.jpg" alt=""></span>
											</div>
											<div class="profile-meta__details">
												<p class="bold-600 color-black">Nathan Astle</p>
												<p class="small">Australia</p>
											</div>
										</div>


										<div class="actions-group">

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--enter icon--18">
													<use xlink:href="images/sprite.yo-coach.svg#enter"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--cancel icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#cancel"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Cancel</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--reschedule icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#reschedule"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Reschedule</div>
											</a>

										</div>
									</div>

								</div>

							</div>
							<!-- ] ========= -->

							<!-- [ LESSON CARD ========= -->
							<div class="card-landscape">

								<div class="card-landscape__colum card-landscape__colum--first">

									<div class="card-landscape__head">
										<time class="card-landscape__time">08:00 PM</time>
										<date class="card-landscape__date">Monday, March 15, 2021</date>
									</div>


									<div class="timer">
										<div class="timer__media"><span><svg class="icon icon--clock icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#clock"></use>
												</svg></span></div>
										<div class="timer__content">
											<div class="timer__controls timer-js">
												<div class="timer__digit">00</div>
												<div class="timer__digit">06</div>
												<div class="timer__digit">33</div>
												<div class="timer__digit">16</div>
											</div>
										</div>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--second">
									<div class="card-landscape__head">
										<span class="card-landscape__title">French, 60 Minutes Of Lesson</span>
										<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
									</div>

									<div class="card-landscape__docs">
										<div class="d-flex align-items-center">
											<a href="#" class="attachment-file">
												<svg class="icon icon--issue icon--attachement icon--xsmall color-black">
													<use xlink:href="images/sprite.yo-coach.svg#attach"></use>
												</svg>
												Basic Words &amp; Numeracy in French
											</a>
										</div>
									</div>

								</div>

								<div class="card-landscape__colum card-landscape__colum--third">
									<div class="card-landscape__actions">
										<div class="profile-meta">
											<div class="profile-meta__media">
												<span class="avtar" data-title="M"><img src="images/emp_6.jpg" alt=""></span>
											</div>
											<div class="profile-meta__details">
												<p class="bold-600 color-black">Mark Boucher</p>
												<p class="small">South Africa</p>
											</div>
										</div>
										<div class="actions-group">
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--enter icon--18">
													<use xlink:href="images/sprite.yo-coach.svg#enter"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
											</a>
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--cancel icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#cancel"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Cancel</div>
											</a>
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--reschedule icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#reschedule"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Reschedule</div>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- ] ========= -->
							<!-- [ LESSON CARD ========= -->
							<div class="card-landscape">
								<div class="card-landscape__colum card-landscape__colum--first">
									<div class="card-landscape__head">
										<time class="card-landscape__time">09:00 PM</time>
										<date class="card-landscape__date">Monday, March 15, 2021</date>
									</div>
								</div>
								<div class="card-landscape__colum card-landscape__colum--second">
									<div class="card-landscape__head">
										<span class="card-landscape__title">German, 60 Minutes Of Lesson</span>
										<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
									</div>
									<div class="card-landscape__docs">
										<div class="d-flex align-items-center">
											<a href="#" class="attachment-file">
												<svg class="icon icon--issue icon--attachement icon--xsmall color-black">
													<use xlink:href="images/sprite.yo-coach.svg#attach"></use>
												</svg>
												Basic Words &amp; Numeracy in French
											</a>
										</div>
									</div>
								</div>
								<div class="card-landscape__colum card-landscape__colum--third">
									<div class="card-landscape__actions">
										<div class="profile-meta">
											<div class="profile-meta__media">
												<span class="avtar" data-title="M"><img src="images/emp_1.jpg" alt=""></span>
											</div>
											<div class="profile-meta__details">
												<p class="bold-600 color-black">James Anderson</p>
												<p class="small">Newzeland</p>
											</div>
										</div>
										<div class="actions-group">
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--enter icon--18">
													<use xlink:href="images/sprite.yo-coach.svg#enter"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
											</a>
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--view icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Lesson View</div>
											</a>
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--reschedule icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#reschedule"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Reschedule</div>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- ] ========= -->
							<!-- [ LESSON CARD ========= -->
							<div class="card-landscape">
								<div class="card-landscape__colum card-landscape__colum--first">
									<div class="card-landscape__head">
										<time class="card-landscape__time">11:00 PM</time>
										<date class="card-landscape__date">Tuesday, March 16, 2021</date>
									</div>
								</div>
								<div class="card-landscape__colum card-landscape__colum--second">
									<div class="card-landscape__head">
										<span class="card-landscape__title">Spanish, 60 Minutes Of Lesson</span>
										<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
									</div>

									<div class="card-landscape__docs">
										<div class="d-flex align-items-center">
											<a href="#" class="attachment-file">
												<svg class="icon icon--issue icon--attachement icon--xsmall color-black">
													<use xlink:href="images/sprite.yo-coach.svg#attach"></use>
												</svg>
												Basic Words &amp; Numeracy in French
											</a>
										</div>
									</div>
								</div>
								<div class="card-landscape__colum card-landscape__colum--third">
									<div class="card-landscape__actions">
										<div class="profile-meta">
											<div class="profile-meta__media">
												<span class="avtar" data-title="M"><img src="images/320x320_4.jpg" alt=""></span>
											</div>
											<div class="profile-meta__details">
												<p class="bold-600 color-black">Nathan Astle</p>
												<p class="small">Australia</p>
											</div>
										</div>
										<div class="actions-group">
											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--enter icon--18">
													<use xlink:href="images/sprite.yo-coach.svg#enter"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--cancel icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#cancel"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Cancel</div>
											</a>

											<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
												<svg class="icon icon--reschedule icon--small">
													<use xlink:href="images/sprite.yo-coach.svg#reschedule"></use>
												</svg>
												<div class="tooltip tooltip--top bg-black">Reschedule</div>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- ] ========= -->
						</div>
						<!-- ] ========= -->
					</div>
				</div>
			</div>