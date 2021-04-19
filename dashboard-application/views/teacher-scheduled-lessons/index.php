<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

 <!-- [ PAGE ========= -->
 <main class="page">
	<div class="container container--fixed">
		<div class="page__head">
			<h1><?php echo Label::getLabel('LBL_Manage_Lessons'); ?></h1>
		</div>
		<div class="page__body">
			<!-- [ INFO BAR ========= -->
			<div class="infobar infobar--primary">
				<div class="row justify-content-between align-items-center">
					<div class="col-lg-8 col-sm-6">
						<div class="d-flex align-items-lg-center">
							<div class="infobar__media margin-right-5">
								<div class="infobar__media-icon infobar__media-icon--vcamera ">
									<svg class="icon icon--vcamera"><use xlink:href="images/sprite.yo-coach.svg#video-camera"></use></svg>
								</div>
							</div>
							<div class="infobar__content">
								<div class="upcoming-lesson display-inline">
									Next Lesson: <date class=" bold-600"> Feb 10, 2021 </date> at <time class=". bold-600">07:00 PM</time> with 
									<div class="avtar-meta display-inline"  >
										<span class="avtar avtar--xsmall display-inline" data-title="M"><img src="images/emp_6.jpg" alt=""></span> Stephen Fleming 
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="col-lg-4 col-sm-6">
						
						<div class="upcoming-lesson-action d-flex align-items-center justify-content-between justify-content-sm-end">

							<div class="timer margin-right-4">
								<div class="timer__media"><span><svg class="icon icon--clock icon--small"><use xlink:href="images/sprite.yo-coach.svg#clock"></use></svg></span></div>
								<div class="timer__content">
									<div class="timer__controls timer-js">
										<div class="timer__digit">00</div>
										<div class="timer__digit">01</div>
										<div class="timer__digit">24</div>
										<div class="timer__digit">47</div>
									</div>
								</div>
							</div>
							<a href="#" class="btn bg-secondary">Enter Classroom</a>

						</div>

					</div>

				</div>
			</div>
			<!-- ] -->


			<!-- [ PAGE PANEL ========= -->
			<div class="page-filter">
				
				<div class="row justify-content-between align-items-center">
					<div class="col-xl-9 col-6">

							<!-- [ FILTERS ========= -->
							<?php
								$frmSrch->setFormTagAttribute ( 'onsubmit', 'searchAllStatusLessons(this); return(false);');
								$frmSrch->setFormTagAttribute ( 'class', 'form' );
								$fldStatus = $frmSrch->getField( 'status');
								$fldStatus->addFieldTagAttribute('onChange','getLessonsByStatus(this.value)');
                                $fldStatus->addFieldTagAttribute('class', 'd-none');
                                $statusOptions =   $fldStatus->options;
								$fldSubmit = $frmSrch->getField( 'btn_submit' );
								$btnReset = $frmSrch->getField( 'btn_reset' );
								$btnReset->addFieldTagAttribute('onclick','clearSearch()');
							?>
						<div class="filter-responsive slide-target-js">
							<div class="form-inline">
								<div class="form-inline__item">
									<select onChange='getLessonsByStatus(this.value);'>
										<option value=''><?php echo Label::getLabel('L_ALL'); ?></option>
										<?php 
										   unset($statusOptions[ScheduledLesson::STATUS_RESCHEDULED]);
										   $statusOptions[ScheduledLesson::STATUS_SCHEDULED] = Label::getLabel('LBL_Scheduled/Rescheduled');
											foreach ($statusOptions as $key => $value) {
										?>
											<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-inline__item">
									<?php echo  $frmSrch->getFormTag(); ?>
										<div class="search-form">
											<div class="search-form__field">
												<?php 
													echo $frmSrch->getFieldHTML('keyword');
												 	echo $frmSrch->getFieldHTML('status'); 
												 	echo $frmSrch->getFieldHTML('page'); 
												?>
											</div>
											<div class="search-form__action search-form__action--submit">
												<?php echo $frmSrch->getFieldHTML('btn_submit'); ?>
												<span class="btn btn--equal btn--transparent color-black">
													<svg class="icon icon--search icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#search'; ?>"></use></svg>
												</span>
											</div>
											<div class="search-form__action search-form__action--reset">
												<?php echo $frmSrch->getFieldHTML('btn_reset'); ?>
												<span class="close"></span>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- ] ========= -->

						<a href="javascript:void(0)" class="btn bg-yellow btn--filters slide-toggle-js">
							<svg class="icon icon--clock icon--small margin-right-2"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#filter'; ?>"></use></svg>
							<?php echo Label::getLabel('LBL_Filters') ?>
						</a>

					</div>
					<div class="col-auto">
						<div class="tab-switch tab-switch--icons">
							<a href="<?php echo CommonHelper::generateUrl('TeacherScheduledLessons'); ?>" class="tab-switch__item is-active list-js">
								<svg class="icon icon--view icon--small"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#lesson-view'; ?>"></use></svg>
								<?php echo Label::getLabel('LBL_List'); ?></a>
							<a href="javascript:void(0);" onclick="viewCalendar();" class="tab-switch__item calender-js">
								<svg class="icon icon--calendar"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#calendar'; ?>"></use></svg>
								<?php echo Label::getLabel('LBL_Calendar'); ?></a>
						</div>
					</div>
				</div>

			</div>


			<div class="page-content">
				<div class="results" id="listItemsLessons">

					<!-- [ LESSON GROUP ========= -->
					<div class="lessons-group margin-top-10">

						<date class="date uppercase small bold-600">Monday, March 15, 2021</date>

						<!-- [ LESSON CARD ========= -->
						<div class="card-landscape">
							
							<div class="card-landscape__colum card-landscape__colum--first">

								<div class="card-landscape__head">
									<time class="card-landscape__time">08:00 PM</time>
									<date class="card-landscape__date">Monday, March 15, 2021</date>
								</div>
								

								<div class="timer">
									<div class="timer__media"><span><svg class="icon icon--clock icon--small"><use xlink:href="images/sprite.yo-coach.svg#clock"></use></svg></span></div>
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
									<a href="#" class="btn btn--transparent btn--addition color-black btn--small">Add Lesson Plan</a>
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
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--cancel icon--small"><use xlink:href="images/sprite.yo-coach.svg#cancel"></use></svg>
											<div class="tooltip tooltip--top bg-black">Cancel</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
											<svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="images/sprite.yo-coach.svg#attach"></use></svg>
											Basic Words & Numeracy in French
										</a>

										<a href="#" class="underline color-black  btn btn--transparent btn--small">Change</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Remove</a>

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
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--view icon--small"><use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use></svg>
											<div class="tooltip tooltip--top bg-black">Lesson View</div>
										</a>


										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
											<div class="tooltip tooltip--top bg-black">Reschedule</div>
										</a>
									</div>
								</div>
							</div>
						</div>
						<!-- ] ========= -->
					</div>
					<!-- ] ========= -->

					<!-- [ LESSON GROUP ========= -->
					<div class="lessons-group margin-top-10">

						<date class="date uppercase small bold-600">Tuesday, March 16, 2021</date>

						<!-- [ LESSON CARD ========= -->
						<div class="card-landscape">
							
							<div class="card-landscape__colum card-landscape__colum--first">

								<div class="card-landscape__head">
									<time class="card-landscape__time">08:00 PM</time>
									<date class="card-landscape__date">Tuesday, March 16, 2021</date>
								</div>
								
							</div>

							<div class="card-landscape__colum card-landscape__colum--second">
								<div class="card-landscape__head">
									<span class="card-landscape__title">Dutch, 60 Minutes Of Lesson</span>
									<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
								</div>

								<div class="card-landscape__docs">
									<a href="#" class="btn btn--transparent btn--addition color-black btn--small">Add Lesson Plan</a>
								</div>

							</div>

							<div class="card-landscape__colum card-landscape__colum--third">
								<div class="card-landscape__actions">
									<div class="profile-meta">
										<div class="profile-meta__media">
											<span class="avtar" data-title="M"><img src="images/320x320_2.jpg" alt=""></span>
										</div>
										<div class="profile-meta__details">
											<p class="bold-600 color-black">Mark Boucher</p>
											<p class="small">South Africa</p>
										</div>
									</div>
									<div class="actions-group">

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--cancel icon--small"><use xlink:href="images/sprite.yo-coach.svg#cancel"></use></svg>
											<div class="tooltip tooltip--top bg-black">Cancel</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
									<date class="card-landscape__date">Monday, March 16, 2021</date>
								</div>
								
								<div class="timer color-red">
									<div class="timer__media"><span><svg class="icon icon--clock icon--small"><use xlink:href="images/sprite.yo-coach.svg#clock"></use></svg></span></div>
									<div class="timer__content">
										<span>Lesson time has passed</span>
									</div>
								</div>
							
							</div>
							<div class="card-landscape__colum card-landscape__colum--second">
								<div class="card-landscape__head">
									<span class="card-landscape__title">Italian, 60 Minutes Of Lesson</span>
									<span class="card-landscape__status badge color-yellow badge--curve badge--small margin-left-0">Need to be Rescheduled</span>
								</div>

								<div class="card-landscape__docs">
									<div class="d-flex align-items-center">
										<a href="#" class="attachment-file">
											<svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="images/sprite.yo-coach.svg#attach"></use></svg>
											Words & Numeracy in Italian
										</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Change</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Remove</a>
									</div>
								</div>
							</div>

							<div class="card-landscape__colum card-landscape__colum--third">
								<div class="card-landscape__actions">
									<div class="profile-meta">
										<div class="profile-meta__media">
											<span class="avtar" data-title="M"><img src="images/320x320_3.jpg" alt=""></span>
										</div>
										<div class="profile-meta__details">
											<p class="bold-600 color-black">James Anderson</p>
											<p class="small">Newzeland</p>
										</div>
									</div>
									<div class="actions-group">

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--view icon--small"><use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use></svg>
											<div class="tooltip tooltip--top bg-black">Lesson View</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
									<time class="card-landscape__time">10:00 PM</time>
									<date class="card-landscape__date">Tuesday, March 16, 2021</date>
								</div>
								
							</div>

							<div class="card-landscape__colum card-landscape__colum--second">
								<div class="card-landscape__head">
									<span class="card-landscape__title">Chinese, 60 Minutes Of Lesson</span>
									<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
								</div>

								<div class="card-landscape__docs">
									<a href="#" class="btn btn--transparent btn--addition color-black btn--small">Add Lesson Plan</a>
								</div>

							</div>

							<div class="card-landscape__colum card-landscape__colum--third">
								<div class="card-landscape__actions">
									<div class="profile-meta">
										<div class="profile-meta__media">
											<span class="avtar" data-title="M"><img src="images/320x320_2.jpg" alt=""></span>
										</div>
										<div class="profile-meta__details">
											<p class="bold-600 color-black">Mark Boucher</p>
											<p class="small">South Africa</p>
										</div>
									</div>
									<div class="actions-group">

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--cancel icon--small"><use xlink:href="images/sprite.yo-coach.svg#cancel"></use></svg>
											<div class="tooltip tooltip--top bg-black">Cancel</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
											<div class="tooltip tooltip--top bg-black">Reschedule</div>
										</a>

									</div>
								</div>
							</div>
						</div>
						<!-- ] ========= -->
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
									<a href="#" class="btn btn--transparent btn--addition color-black btn--small">Add Lesson Plan</a>
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
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--cancel icon--small"><use xlink:href="images/sprite.yo-coach.svg#cancel"></use></svg>
											<div class="tooltip tooltip--top bg-black">Cancel</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
											<div class="tooltip tooltip--top bg-black">Reschedule</div>
										</a>
									</div>
								</div>
							</div>
						</div>
						<!-- ] ========= -->
					</div>
					<!-- ] ========= -->
					<!-- [ LESSON GROUP ========= -->
					<div class="lessons-group margin-top-10">

						<date class="date uppercase small bold-600">Wednesday, March 17, 2021</date>

						<!-- [ LESSON CARD ========= -->
						<div class="card-landscape">
							
							<div class="card-landscape__colum card-landscape__colum--first">

								<div class="card-landscape__head">
									<time class="card-landscape__time">08:00 PM</time>
									<date class="card-landscape__date">Wednesday, March 17, 2021</date>
								</div>                                  
							</div>
							<div class="card-landscape__colum card-landscape__colum--second">
								<div class="card-landscape__head">
									<span class="card-landscape__title">French, 60 Minutes Of Lesson</span>
									<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
								</div>
								<div class="card-landscape__docs">
									<a href="#" class="btn btn--transparent btn--addition color-black btn--small">Add Lesson Plan</a>
								</div>
							</div>
							<div class="card-landscape__colum card-landscape__colum--third">
								<div class="card-landscape__actions">
									<div class="profile-meta">
										<div class="profile-meta__media">
											<span class="avtar" data-title="M"><img src="images/emp_1.jpg" alt=""></span>
										</div>
										<div class="profile-meta__details">
											<p class="bold-600 color-black">Mark Boucher</p>
											<p class="small">South Africa</p>
										</div>
									</div>
									<div class="actions-group">
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--cancel icon--small"><use xlink:href="images/sprite.yo-coach.svg#cancel"></use></svg>
											<div class="tooltip tooltip--top bg-black">Cancel</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
									<date class="card-landscape__date">Wednesday, March 17, 2021</date>
								</div>
								
							</div>

							<div class="card-landscape__colum card-landscape__colum--second">
								<div class="card-landscape__head">
									<span class="card-landscape__title">Italian, 60 Minutes Of Lesson</span>
									<span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">Scheduled</span>
								</div>

								<div class="card-landscape__docs">
									<div class="d-flex align-items-center">
										<a href="#" class="attachment-file">
											<svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="images/sprite.yo-coach.svg#attach"></use></svg>
											Basic Words & Numeracy in Italian
										</a>

										<a href="#" class="underline color-black  btn btn--transparent btn--small">Change</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Remove</a>
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
											<p class="bold-600 color-black">James Anderson</p>
											<p class="small">Newzeland</p>
										</div>
									</div>
									<div class="actions-group">
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--view icon--small"><use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use></svg>
											<div class="tooltip tooltip--top bg-black">Lesson View</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
									<time class="card-landscape__time">10:00 PM</time>
									<date class="card-landscape__date">Wednesday, March 17, 2021</date>
								</div>
							</div>
							<div class="card-landscape__colum card-landscape__colum--second">
								<div class="card-landscape__head">
									<span class="card-landscape__title">German, 60 Minutes Of Lesson</span>
									<span class="card-landscape__status badge color-red badge--curve badge--small margin-left-0">Cancelled</span>
								</div>
								<div class="card-landscape__docs">
									<div class="d-flex align-items-center">
										<a href="#" class="attachment-file">
											<svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="images/sprite.yo-coach.svg#attach"></use></svg>
											Basic Words & Numeracy in German
										</a>

										<a href="#" class="underline color-black  btn btn--transparent btn--small">Change</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Remove</a>
									</div>
								</div>
							</div>

							<div class="card-landscape__colum card-landscape__colum--third">
								<div class="card-landscape__actions">
									<div class="profile-meta">
										<div class="profile-meta__media">
											<span class="avtar" data-title="M"><img src="images/emp_3.jpg" alt=""></span>
										</div>
										<div class="profile-meta__details">
											<p class="bold-600 color-black">James Anderson</p>
											<p class="small">Newzeland</p>
										</div>
									</div>
									<div class="actions-group">

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--view icon--small"><use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use></svg>
											<div class="tooltip tooltip--top bg-black">Lesson View</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
									<date class="card-landscape__date">Wednesday, March 18, 2021</date>
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
											<svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="images/sprite.yo-coach.svg#attach"></use></svg>
											Basic Words & Numeracy in German
										</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Change</a>
										<a href="#" class="underline color-black  btn btn--transparent btn--small">Remove</a>
									</div>
								</div>
							</div>

							<div class="card-landscape__colum card-landscape__colum--third">
								<div class="card-landscape__actions">
									<div class="profile-meta">
										<div class="profile-meta__media">
											<span class="avtar " data-title="M"><img src="images/emp_3.jpg" alt=""></span>
										</div>
										<div class="profile-meta__details">
											<p class="bold-600 color-black">James Anderson</p>
											<p class="small">Newzeland</p>
										</div>
									</div>
									<div class="actions-group">

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--enter icon--18"><use xlink:href="images/sprite.yo-coach.svg#enter"></use></svg>
											<div class="tooltip tooltip--top bg-black">Enter Classroom</div>
										</a>

										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--view icon--small"><use xlink:href="images/sprite.yo-coach.svg#lesson-view"></use></svg>
											<div class="tooltip tooltip--top bg-black">Lesson View</div>
										</a>
										<a href="#" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
											<svg class="icon icon--reschedule icon--small"><use xlink:href="images/sprite.yo-coach.svg#reschedule"></use></svg>
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
			<!-- ] -->
		</div>
		<div class="page__footer align-center">
			<p class="small">Copyright © 2021 Yo!Coach Developed by <a href="#" class="underline color-primary">FATbit Technologies</a> . </p>
		</div>
	</div>
</main>
<!-- ] -->
