<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section">
	<div class="sectionhead">
		<h4><?php echo Label::getLabel('LBL_View_Lesson_Detail',$adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">
		<div class="tabs_nav_container responsive flat">
			<div class="tabs_panel_wrap">
				<div class="tabs_panel">
                        <?php if($lessonRow['grpcls_title']): ?>
						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Class_Title', $adminLangId); ?>
										</label>
										: <strong><?php echo CommonHelper::displayNotApplicable($lessonRow['grpcls_title']); ?></strong>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Class_Description',$adminLangId); ?>
										</label>
										: <strong><?php echo CommonHelper::displayNotApplicable($lessonRow['grpcls_description']); ?></strong>
									</div>
								</div>
							</div>
						</div>
                        <?php endif; ?>
                        
						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Language',$adminLangId); ?>
										</label>
										: <strong><?php echo $lessonRow['teacherTeachLanguageName']; ?></strong>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Session_Date',$adminLangId); ?>
										</label> :
										<strong>
											<?php echo ($lessonRow['slesson_date'] == "0000-00-00") ?  Label::getLabel('LBL_N/A') : FatDate::format($lessonRow['slesson_date']); ?>
										</strong>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Scheduled_Start_Time',$adminLangId); ?>
										</label>
										:
										<strong>
											<?php echo ($lessonRow['slesson_start_time'] == "00:00:00" && $lessonRow['slesson_date'] == "0000-00-00") ?  Label::getLabel('LBL_N/A') : date('h:i A',strtotime($lessonRow['slesson_start_time'])); ?>
										</strong>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Scheduled_End_Time',$adminLangId); ?>
										</label>
										:
										<strong>
											<?php echo ($lessonRow['slesson_end_time'] == "00:00:00" && $lessonRow['slesson_date'] == "0000-00-00") ?  Label::getLabel('LBL_N/A') : date('h:i A',strtotime($lessonRow['slesson_end_time'])) ; ?>
										</strong>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="field-set">
									<div class="caption-wraper">
										<label class="field_label">
											<?php echo Label::getLabel('LBL_Lesson_Status',$adminLangId); ?>
										</label>
										: <strong><?php echo $statusArr[$lessonRow['slesson_status']]; ?></strong>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</section>
