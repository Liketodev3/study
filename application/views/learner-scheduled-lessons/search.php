<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$referer = preg_replace("(^https?://)", "", $referer );

MyDate::setUserTimeZone(); 
$user_timezone = MyDate::getUserTimeZone();

foreach( $lessonArr as $key=>$lessons ){ ?>
<div class="col-list-group">
<?php if($key!='0000-00-00'){  ?>
<h6><?php  echo date('l, F d, Y',strtotime($key)); ?></h6>
<?php } ?>
<div class="col-list-container">
<?php
foreach( $lessons as $lesson ){ 
$action = '';
if ( $lesson['is_trial'] == 1 ) {
	$action = 'free_trial';
} 
?>
	<div class="col-list">
		<div class="d-lg-flex align-items-center">
			<div class="col-xl-4 col-lg-4 col-md-12">
				<div class="avtar avtar--normal" data-text="<?php echo CommonHelper::getFirstChar($lesson['teacherFname']); ?>">
					<?php 
					if( true == User::isProfilePicUploaded( $lesson['teacherId'] ) ){
						$img = CommonHelper::generateUrl('Image','user', array( $lesson['teacherId'] )).'?'.time(); 
						echo '<img src="'.$img.'" />'; 
					} ?>
				</div>
				<h6><?php echo $lesson['teacherFname']; ?></h6>
				<p><?php echo $lesson['teacherCountryName']; ?> <br>
				<?php /* echo CommonHelper::getDateOrTimeByTimeZone($lesson['teacherTimeZone'],'H:i A P'); */ ?></p>
			</div>
			
			<div class="col-xl-6 col-lg-6 col-md-12">
				<div class="schedule-list">
					<ul>
						<?php
						if( $lesson['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING  && $lesson['is_trial'] == 1 ) {
							?>
						<li>
							<h6 class="-color-secondary"><?php echo Label::getLabel('LBL_Trial_Lesson'); ?></h6>
						</li>	
						<?php	
						}
						$date = DateTime::createFromFormat('Y-m-d', $lesson['slesson_date']);
						if($date && ($date->format('Y-m-d') === $lesson['slesson_date'])){ ?>
							<li>
								<span class="span-left"><?php echo Label::getLabel('LBL_Schedule'); ?></span>
								<span class="span-right">
									<h4>
									<?php 
										echo MyDate::convertTimeFromSystemToUserTimezone( 'h:i A', $lesson['slesson_start_time'], true , $user_timezone );									
									?>  
									</h4>
									<?php 
										echo MyDate::convertTimeFromSystemToUserTimezone( 'l, F d, Y', $lesson['slesson_date']." ". $lesson['slesson_start_time'] , true , $user_timezone );
									?>
								</span>
							</li>
						<?php } ?>
						
						<li>
							<span class="span-left"><?php echo Label::getLabel('LBL_Status'); ?></span>
							<span class="span-right"><?php echo $statusArr[$lesson['slesson_status']]; ?></span>
						</li>
                        <?php if( $lesson['issrep_id'] ){ ?>					
                            <li>
                                <span class="span-left"><?php echo Label::getLabel('LBL_Issue_Status'); ?></span>
                                <span class="span-right"><?php echo IssuesReported::getStatusArr()[$lesson['issrep_status']]; ?></span>
                            </li>                        
                        <?php } ?>						
						<li>
							<span class="span-left"><?php echo Label::getLabel('LBL_Details'); ?></span>
							<span class="span-right">
							<?php echo empty($teachLanguages[$lesson['slesson_slanguage_id']])?'':$teachLanguages[$lesson['slesson_slanguage_id']] ; ?><br>
							<?php 
							if( $lesson['slesson_date'] != "0000-00-00" ){
								$str = Label::getLabel( 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson' );
								$arrReplacements = array(
									'{n}'	=>	$lesson['op_lesson_duration'],
									'{trial-or-paid}'	=>	($lesson['is_trial']) ? Label::getLabel('LBL_Trial') : '',
								);
								foreach( $arrReplacements as $key => $val ){
									$str = str_replace( $key, $val, $str );
								}
								echo $str;
							} ?>
							</span>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="col-xl-2 col-lg-2 col-md-4 col-positioned">
				<div class="select-box toggle-group">
					<div class="buttons-toggle">
						<a href="<?php echo CommonHelper::generateFullUrl('LearnerScheduledLessons','view',array($lesson['slesson_id'])); ?>" class="btn btn--secondary"><?php echo Label::getLabel('LBL_View'); ?></a>
						<?php if($lesson['slesson_status'] != ScheduledLesson::STATUS_CANCELLED AND $referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('learner-scheduled-lessons'))){ ?>
						<a href="javascript:void(0)" class="btn btn--secondary btn--dropdown toggle__trigger-js"></a>
						<?php }?>						
					</div>
					
					<div class="select-box__target -skin toggle__target-js" style="display: none;">
						<div class="listing listing--vertical">
							<ul>
								<?php if($lesson['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) { ?>
								<li><a href="javascript:void(0);" onclick="viewBookingCalendar('<?php echo $lesson['slesson_id']; ?>', '<?php echo $action; ?>')"><?php echo Label::getLabel('LBL_Schedule'); ?></a></li>
								<li><a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lesson['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Cancel'); ?></a></li>
								<?php }  ?>

								<?php if($lesson['slesson_status'] == ScheduledLesson::STATUS_COMPLETED ) { 
									if ( $lesson['issrep_id'] < 1 ) { ?>
										<li><a href="javascript:void(0);" onclick="issueReported('<?php echo $lesson['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Issue_Reported'); ?></a></li>
									<?php }?>
								
								<li><a href="javascript:void(0);" onclick="lessonFeedback('<?php echo $lesson['slesson_id'];  ?>');"><?php echo Label::getLabel('LBL_Rate_Lesson'); ?></a></li>
								<?php } ?>
								
								<?php if( $lesson['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED || $lesson['issrep_id'] > 0) { ?>
								<li><a href="javascript:void(0);" onclick="issueDetails('<?php echo $lesson['issrep_id']; ?>')" ><?php echo Label::getLabel('LBL_Issue_Details'); ?></a></li>
								<?php } ?>
								
								<?php if($lesson['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
								<li><a href="javascript:void(0);" onclick="requestReschedule('<?php echo $lesson['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Reschedule'); ?></a></li>
								<li><a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lesson['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Cancel'); ?></a></li>
								<?php } ?>
								<?php 
								$countRel=ScheduledLessonSearch::countPlansRelation($lesson['slesson_id']);
								if( $countRel > 0 ){
								?>
									<li><a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lesson['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?></a></li>
									<!--li><a href="javascript:void(0);" onclick="changeLessonPlan('<?php //echo $lessonData['slesson_id']; ?>')" ><?php //echo Label::getLabel('LBL_Change_Plan'); ?></a></li>
									<li><a href="javascript:void(0);" onclick="removeAssignedLessonPlan('<?php //echo $lessonData['slesson_id']; ?>')" ><?php //echo Label::getLabel('LBL_Remove_Plan'); ?></a></li-->
								<?php } ?>								
							</ul>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</div>
</div>
<?php }
if ( empty($lessons) ) { 
	$this->includeTemplate('_partial/no-record-found.php');
} else {
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmSLnsSearchPaging'
	) );
	if ( $referer == preg_replace("(^https?://)", "", CommonHelper::generateFullUrl('learner-scheduled-lessons'))){
		$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	} else {
		echo "<div class='load-more -align-center'><a href='".CommonHelper::generateFullUrl('learner-scheduled-lessons')."' class='btn btn--bordered btn--xlarge'>View all</a></div>";
	}
}
?>						
<script type="text/javascript">
jQuery(document).ready(function () {
	/*$(".toggle__trigger-js").click(function () {
        var t = $(this).parents(".toggle-group").children(".toggle__target-js").is(":hidden");
        $(".toggle-group .toggle__target-js").hide();
        $(".toggle-group .toggle__trigger-js").removeClass("is-active");
        if (t) {
            $(this).parents(".toggle-group").children(".toggle__target-js").toggle().parents(".toggle-group").children(".toggle__trigger-js").addClass("is-active")
        }
       
    });
    $(document).bind("click", function (t) {
        var n = $(t.target);
        if (!n.parents().hasClass("toggle-group")) $(".toggle-group .toggle__target-js").hide();
    });
    $(document).bind("click", function (t) {
        var n = $(t.target);
        if (!n.parents().hasClass("toggle-group")) $(".toggle-group .toggle__trigger-js").removeClass("is-active");
    })*/
});
</script>
	