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
								$frmSrch->setFormTagAttribute ( 'id', 'frmSrch' );
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
									<select name="<?php echo $fldStatus->getName(); ?>" onChange='getLessonsByStatus(this.value);' form="<?php echo $frmSrch->getFormTagAttribute('id'); ?>">
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
												 	// echo $frmSrch->getFieldHTML('status'); 
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
