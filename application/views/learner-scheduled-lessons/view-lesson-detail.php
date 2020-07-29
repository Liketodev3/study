<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$user_timezone = MyDate::getUserTimeZone();
$curDate = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );

$startTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date($lessonData['slesson_date'] .' '. $lessonData['slesson_start_time']), true , $user_timezone );

$endTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date($lessonData['slesson_end_date'] .' '. $lessonData['slesson_end_time']), true , $user_timezone );

$chatId = UserAuthentication::getLoggedUserId();
$countReviews = TeacherLessonReview::getTeacherTotalReviews($lessonData['teacherId'],$lessonData['slesson_id'],$chatId);
$studentImage = '';

if( true == User::isProfilePicUploaded( $lessonData['learnerId'] ) ){
    $studentImage = CommonHelper::generateFullUrl('Image','user', array( $lessonData['learnerId'])).'?'.time();
}

$canEnd = ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED && $endTime<$curDate);
$chat_group_id = $lessonData['slesson_grpcls_id'] >0 ? $lessonData['grpcls_title'] : "LESSON-".$lessonData['slesson_id'];

$lessonsStatus = $statusArr[$lessonData['sldetail_learner_status']];
$lessonData['lessonReschedulelogId'] =  FatUtility::int($lessonData['lessonReschedulelogId']);

if($lessonData['lessonReschedulelogId'] > 0 && (
	$lessonData['sldetail_learner_status']== ScheduledLesson::STATUS_NEED_SCHEDULING ||
	$lessonData['sldetail_learner_status']== ScheduledLesson::STATUS_SCHEDULED ) ) {
	$lessonsStatus = Label::getLabel('LBL_Rescheduled');
	if($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
		$lessonsStatus = Label::getLabel('LBL_Pending_for_Reschedule');
	}
}


?>
<script>
var is_time_up = '<?php echo $endTime > 0 && $endTime<$curDate ?>';
var lesson_joined = '<?php echo $lessonData['sldetail_learner_join_time']>0 ?>';
var lesson_completed = '<?php echo $lessonData['sldetail_learner_end_time']>0 ?>';
var teacherId = '<?php echo $lessonData['teacherId'] ?>';
var canEnd = '<?php echo $canEnd ?>';

var chat_appid = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_APP_ID'); ?>';
var chat_auth = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_AUTH'); ?>';
var chat_id = '<?php echo $chatId; ?>';
var chat_group_id = '<?php echo $chat_group_id; ?>';
var chat_api_key = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_API_KEY'); ?>';
var chat_name = '<?php echo $lessonData['learnerFname']; ?>';
var chat_avatar = "<?php echo $studentImage; ?>";
var chat_friends = "<?php echo $lessonData['teacherId']; ?>";

if(!is_time_up && lesson_joined && !lesson_completed){
    joinLesson(chat_id, teacherId);
}

if(lesson_completed==1){
    $('.timer').hide();
}

if(canEnd){
    $("#endL").show();
}

jQuery(document).ready(function () {
    <?php if( $lessonData['sldetail_learner_status'] != ScheduledLesson::STATUS_SCHEDULED ){ ?>
    $("#lesson_actions").show();
    <?php }?>
});
function joinLessonButtonAction() {
    $("#lesson_actions").hide();
    $("#joinL").hide();
    $("#endL").show();
    checkEveryMinuteStatus();
    checkNewFlashCards();
    searchFlashCards(document.frmFlashCardSrch);
    $('.screen-chat-js').show();
    <?php if( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED ){ ?>
        $("#lesson_actions").hide();
        $("#end_lesson_time_div").show();
    <?php }?>
}

function endLessonButtonAction() {
    $("#joinL").show();
    $("#lesson_actions").show();
    $("#endL").hide();
    searchFlashCards(document.frmFlashCardSrch);
    if(typeof checkEveryMinuteStatusVar!="undefined"){
    clearInterval(checkEveryMinuteStatusVar);
    }
    if(typeof checkNewFlashCardsVar!="undefined"){
    clearInterval(checkNewFlashCardsVar);
    }
    $('.screen-chat-js').hide();
    $("#end_lesson_time_div").hide();
}

function checkEveryMinuteStatus() {
   checkEveryMinuteStatusVar = setInterval(function(){
        fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','checkEveryMinuteStatus',['<?php echo $lessonData['sldetail_id'] ?>']),'',function(t){
            var t = JSON.parse(t);
            if (!lesson_joined && !lesson_completed && t.has_teacher_joined == 1 && !t.has_learner_joined)
            {
                $.mbsmessage( '<?php echo Label::getLabel('LBL_Teacher_Has_Joined_Now_you_can_also_Join_The_Lesson!'); ?>',true, 'alert alert--success');
            }

            if(t.slesson_status>1 && t.sldetail_learner_status == 1) {
            $.confirm({
                title: langLbl.Confirm,
                content: '<?php echo Label::getLabel('LBL_Teacher_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also'); ?>',
                autoClose: langLbl.Quit+'|10000',
                buttons: {
                    Proceed: {
                        text: langLbl.Proceed,
                        btnClass: 'btn btn--primary',
                        keys: ['enter', 'shift'],
                        action: function(){
                            endLessonButtonAction();
                            viewLessonDetail();
                        }
                    },
                    Quit: {
                        text: langLbl.Quit,
                        btnClass: 'btn btn--secondary',
                        keys: ['enter', 'shift'],
                        action: function(){
                        }
                    }
                }
            });

            }

        });
    },60000);
}

function checkNewFlashCards(){
    checkNewFlashCardsVar = setInterval(function(){
        /*fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','searchFlashCards',['<?php echo $lessonData['sldetail_id'] ?>']),'',function(t){
            $('#flashCardListing').html(t);
        });*/
        searchFlashCards(document.frmFlashCardSrch);
    },30000)
}

$(function(){
    <?php if( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED ){ ?>
    var showLessonBtn = true;
    $('#start_lesson_timer').countdowntimer({
        startDate : "<?php echo $curDate; ?>",
        dateAndTime : "<?php echo $startTime; ?>",
        size : "lg",
        timeUp : function(){
            fcom.ajax(fcom.makeUrl('LearnerScheduledLessons','startLessonAuthentication',['<?php echo $lessonData['sldetail_id'] ?>']),'',function(t){
                if(t != 0){
                    showLessonBtn = false;
                    $(".join_lesson_now").show();
                    $("#lesson_actions").hide();
                }else{
                        $("#lesson_actions").show();
                }
            });
            $("#start_lesson_timer").parent().hide();
        }
    });
    if(showLessonBtn) {
        if($('#start_lesson_timer').parent().is(":visible")){
            $("#lesson_actions").show();
        }
    }
    <?php } ?>

    $('#end_lesson_timer').countdowntimer({
        startDate : "<?php echo $curDate; ?>",
        dateAndTime : "<?php echo $endTime; ?>",
        size : "lg",
        timeUp : function(){
            if(lesson_completed) return;
            $("#end_lesson_time_div").hide();
        }
    });
});
</script>
<section class="section section--grey section--page">
	<div class="screen">
		<div class="screen__left" style="background-image:url(<?php echo CONF_WEBROOT_URL ?>images/2000x900_1.jpg">
			<div class="screen__center-content">
				<?php if( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING ) { ?>
				<div class="alert alert--info" role="alert">
					<a class="close" href="javascript:void(0)"></a>
					<p>
						<?php echo Label::getLabel('LBL_Note'); ?>:
						<?php echo Label::getLabel('LBL_This_lesson_is_Unscheduled._schedule_it'); ?>
					</p>
				</div>
				<span class="-gap"></span>
				<?php } ?>
				<?php if ( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED && $countReviews == 0 ) { ?>
				<div class="alert alert--info" role="alert">
					<p>
						<?php echo Label::getLabel('LBL_Note'); ?>:
						<?php echo Label::getLabel('LBL_This_lesson_is_completed._rate_it'); ?>
					</p>
				</div>
				<span class="-gap"></span>
				<a href="javascript:void(0);" onclick="lessonFeedback('<?php echo $lessonData['sldetail_id'];  ?>');" class="btn btn--primary btn--large">
					<?php echo Label::getLabel('LBL_Rate_it'); ?>
				</a>
				<?php } ?>
				<?php if( $countReviews > 0 ) { ?>
				<div class="alert alert--info" role="alert">
					<a class="close" href="javascript:void(0)"></a>
					<p>
						<?php echo Label::getLabel('LBL_Note'); ?>:
						<?php echo Label::getLabel('LBL_This_lesson_is_completed'); ?>
					</p>
				</div>
				<span class="-gap"></span>
				<?php }
				if ( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_CANCELLED ) { ?>
				<div class="alert alert--info" role="alert">
					<a class="close" href="javascript:void(0)"></a>
					<p>
						<?php echo Label::getLabel('LBL_Note'); ?>:
						<?php echo Label::getLabel('LBL_This_Lesson_has_been_cancelled._Schedule_more_lessons'); ?>
					</p>
				</div>
				<span class="-gap"></span>
				<?php } ?>
				<a href="javascript:void(0);" style="display:none;" class="btn btn--secondary btn--xlarge join_lesson_now" id="joinL" onclick="joinLesson('<?php echo $chatId; ?>','<?php echo $lessonData['teacherId']; ?>');">
					<?php echo Label::getLabel('LBL_Join_Lesson'); ?>
				</a>
				<?php if( $lessonData['sldetail_learner_status'] != ScheduledLesson::STATUS_SCHEDULED ) { ?>
				<a href="<?php echo CommonHelper::generateUrl('learner'); ?>" class="btn btn--secondary btn--large">
					<?php echo Label::getLabel('LBL_Go_to_Dashboard'); ?>
				</a>
				<?php } ?>
				<?php
				if ( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED &&  $lessonData['issrep_id'] < 1 ) { ?>
				<p class="issueReportLink"><a href="javascript:void(0);" onclick="issueReported('<?php echo $lessonData['sldetail_id']; ?>')" >
					<?php echo Label::getLabel('LBL_Click_here_to_report_an_Issue'); ?>
				</a></p>
				<?php } ?>
				<div class="timer">
                    <h4 class="timer-head"><?php echo Label::getLabel('LBL_Starts_In'); ?></h4>
					<span id="start_lesson_timer"></span>
				</div>
			</div>
			<div class="screen-chat screen-chat-js" style="display:none;">
				<div class="chat-container">
					<div id="cometChatBox" class="cometChatBox"></div>
				</div>
			</div>
		</div>
		<div class="screen__right">
			<div class="tab-horizontal tabs-js">
				<ul>
					<li class="is-active">
						<a href="#tab1">
							<?php echo Label::getLabel('LBL_Info'); ?>
						</a>
					</li>
					<li>
						<a href="#tab2">
							<?php echo Label::getLabel('LBL_Flashcards'); ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="tab-data-container">
				<div id="tab1" class="tabs-content-js">
					<div class="col-list col-list--full -no-padding">
						<div class="">
							<div class="col-xl-12">
								<h6>
									<?php echo Label::getLabel('LBL_Learner_Details'); ?>
								</h6>
								<div class="d-sm-flex align-items-center">
									<div>
										<div class="avtar avtar--small" data-text="
											<?php echo CommonHelper::getFirstChar($lessonData['learnerFname']); ?>">
											<?php
									if( true == User::isProfilePicUploaded( $lessonData['learnerId'] ) ){
										$img = CommonHelper::generateUrl('Image','user', array( $lessonData['learnerId'] )).'?'.time();
										echo '
											<img src="'.$img.'" />';
									}
									  ?>
										</div>
									</div>
									<div>
										<h6> <?php echo $lessonData['learnerFullName']; ?> </h6>
										<p> <?php echo $lessonData['learnerCountryName']; ?> </p>
									</div>
								</div>
							</div>
							<hr>
                            <div class="col-xl-12">
                                <h6>
                                    <?php echo Label::getLabel('LBL_Teacher_Details'); ?>
                                </h6>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="avtar avtar--small" data-text="
                                            <?php echo CommonHelper::getFirstChar($lessonData['teacherFname']); ?>">
                                            <?php
                            if( true == User::isProfilePicUploaded( $lessonData['teacherId'] ) ){
                                $img = CommonHelper::generateUrl('Image','user', array( $lessonData['teacherId'] )).'?'.time();
                                echo '
                                            <img src="'.$img.'" />';
                            }
                              ?>
                                        </div>
                                    </div>
                                    <div>
                                        <h6><?php echo $lessonData['teacherFullName']; ?></h6>
                                        <p> <?php echo $lessonData['teacherCountryName']; ?> </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-xl-12">
                                    <h6>
                                        <?php echo Label::getLabel('LBL_Lesson_Details'); ?>
                                    </h6>
                                    <div class="schedule-list">
                                        <ul>
                                            <?php
                    $sdate = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d', date($lessonData['slesson_date'] .' '. $lessonData['slesson_start_time']), true , $user_timezone );
                     $date = DateTime::createFromFormat('Y-m-d', $sdate);
                     //if($date && ($date->format('Y-m-d') === $lessonData['slesson_date'])){
                     if($date && ($date->format('Y-m-d') === $sdate )){ ?>
                                            <li>
                                                <span class="span-left">
                                                    <?php echo Label::getLabel('LBL_Schedule'); ?>
                                                </span>
                                                <span class="span-right">
                                                    <h4>
                                                        <?php echo date('h:i A',strtotime($startTime)); ?> -

                                                        <?php echo date('h:i A',strtotime($endTime)); ?>
                                                    </h4>
                                                    <?php echo date('l, F d, Y',strtotime($startTime)); ?>
                                                </span>
                                            </li>
                                            <?php } ?>
                                            <li>
                                                <span class="span-left">
                                                    <?php echo Label::getLabel('LBL_Status'); ?>
                                                </span>
                                                <span class="span-right">
                                                    <?php echo $lessonsStatus; ?>
                                                </span>
                                            </li>
                                            <li>
                                                <span class="span-left">
                                                    <?php echo Label::getLabel('LBL_Details'); ?>
                                                </span>
                                                <span class="span-right">
                                                <?php
                                                    if($lessonData['is_trial'] == applicationConstants::NO) {
                                                    //echo $lessonData['teacherTeachLanguageName'];
                                                    echo TeachingLanguage::getLangById($lessonData['slesson_slanguage_id']); ?>
                                                    <br>
                                                    <?php
                                                    }
                                                    if( date('Y-m-d', strtotime($startTime)) != "0000-00-00" ){
                                                        $str = Label::getLabel( 'LBL_{n}_minutes_of_{trial-or-paid}_Lesson' );
                                                        $arrReplacements = array(
                                                            '{n}'	=>	$lessonData['op_lesson_duration'],
                                                            '{trial-or-paid}'	=>	($lessonData['is_trial']) ? Label::getLabel('LBL_Trial') : '',
                                                        );
                                                        foreach( $arrReplacements as $key => $val ){
                                                            $str = str_replace( $key, $val, $str );
                                                        }
                                                        echo $str;
                                                    }
                                                   ?>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php if( $lessonData['issrep_id'] ){ ?>
                                <hr>
                                <div class="col-xl-12">
                                    <h6>
                                        <?php echo Label::getLabel('LBL_Issue_Status'); ?>
                                    </h6>
                                    <div class="schedule-list">
                                        <ul>
                                            <li>
                                                <span class="span-left">
                                                    <?php echo Label::getLabel('LBL_Status'); ?>
                                                </span>
                                                <span class="span-right">
                                                    <?php echo IssuesReported::getStatusArr()[$lessonData['issrep_status']]; ?>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php } ?>
                                <hr>
                                <div class="col-xl-12">
                                    <div class="timer-block d-sm-flex align-items-center justify-content-between">
                                        <div id="end_lesson_time_div" style="display:none;">
                                            <div class="timer timer--small">
                                                <span id="end_lesson_timer"></span>
                                            </div>
                                        </div>

                                        <div id="lesson_actions" style="display:none">
                                            <h6 class="pb-3"><?php echo Label::getLabel('LBL_Actions');?></h6>
                                            <ul class="actions">
                                                <?php  if($lessonData['slesson_grpcls_id'] == 0): ?>
                                                <li><a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lessonData['sldetail_id']; ?>')" title="<?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?>">
                                                    <svg width="35px" enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m454.808 33.134h-9.067v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067-13.271 0-24.067 10.796-24.067 24.067v9.067h-9.068c-22.405 0-40.632 18.228-40.632 40.632v183.537l-18.346-18.346c-9.384-9.384-24.652-9.383-34.036 0l-23.429 23.429c-9.384 9.383-9.384 24.652 0 34.035l75.81 75.81v99.136c0 22.405 18.228 40.633 40.632 40.633h255.136c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-255.135c-14.134 0-25.632-11.499-25.632-25.633v-84.136l61.184 61.184c.178.178.364.346.557.503.131.107.267.202.403.299.064.045.124.096.189.139.172.115.349.217.528.316.034.019.066.041.1.059.189.101.382.189.576.272.029.012.056.028.086.04.189.078.381.144.574.206.04.013.078.029.118.041.182.055.366.098.55.138.055.012.108.029.163.04.179.035.359.058.54.08.063.008.124.021.187.027.243.024.488.036.732.036h43.751l9.518 9.518c1.464 1.464 3.384 2.197 5.303 2.197s3.839-.733 5.303-2.197c2.929-2.929 2.929-7.678 0-10.607l-9.518-9.517v-43.749c0-.246-.012-.491-.036-.736-.005-.054-.017-.106-.023-.16-.023-.19-.048-.379-.085-.567-.01-.048-.024-.095-.035-.143-.042-.191-.087-.382-.143-.57-.01-.034-.024-.066-.035-.1-.063-.199-.132-.397-.212-.592-.009-.021-.02-.041-.029-.062-.086-.203-.179-.404-.283-.6-.014-.026-.03-.049-.044-.075-.103-.188-.211-.373-.331-.553-.037-.056-.081-.108-.12-.163-.102-.145-.204-.29-.317-.429-.158-.193-.325-.379-.503-.557l-61.185-61.185h258.087c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-2.203 0-4.179.956-5.551 2.469l-44.933-44.933v-141.333h366.034v51.151c0 4.142 3.358 7.5 7.5 7.5s7.5-3.358 7.5-7.5v-108.352c.001-22.405-18.227-40.632-40.632-40.632zm-234.556 402.478h-21.251l21.251-21.251zm-157.317-121.065 12.823-12.822 117.959 117.959-12.822 12.822zm141.388 94.53-117.959-117.959 12.823-12.822 117.959 117.959zm-170.12-136.084 23.429-23.429c3.535-3.535 9.288-3.536 12.823 0l18.126 18.126-36.252 36.25-18.125-18.125c-3.536-3.535-3.536-9.287-.001-12.822zm387.471-257.993c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c.001-5 4.068-9.067 9.067-9.067zm-82.833 0c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c0-5 4.067-9.067 9.067-9.067zm-91.9 9.067c0-5 4.067-9.067 9.067-9.067s9.067 4.067 9.067 9.067v16.557.01s0 .006 0 .01v16.556c0 5-4.067 9.067-9.067 9.067-4.999 0-9.067-4.067-9.067-9.067zm-73.767-9.067c4.999 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-33.133c0-5 4.067-9.067 9.067-9.067zm-58.767 100.967v-42.201c0-14.134 11.499-25.632 25.632-25.632h9.068v9.066c0 13.271 10.796 24.067 24.067 24.067 13.27 0 24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h9.067c14.134 0 25.633 11.499 25.633 25.632v42.201z"/><path d="m487.941 204.619c-4.142 0-7.5 3.358-7.5 7.5v259.248c0 14.134-11.499 25.633-25.633 25.633h-29.634c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h29.634c22.405 0 40.633-18.228 40.633-40.633v-259.248c0-4.142-3.358-7.5-7.5-7.5z"/><path d="m164.89 180.667h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 230.367h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 280.067h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m437.458 371.967c0-4.142-3.358-7.5-7.5-7.5h-173.95c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h173.95c4.142 0 7.5-3.358 7.5-7.5z"/></g></svg>
                                                </a></li>
                                                <?php endif; ?>

                                                <?php if($lessonData['sldetail_learner_status'] != ScheduledLesson::STATUS_CANCELLED) { ?>
                                                <?php if($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
                                                        $is_trail = ($lessonData['is_trial']) ? 'free_trial' : ''; ?>
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="viewBookingCalendar('<?php echo $lessonData['sldetail_id']; ?>','<?php echo $is_trail; ?>')" class="" title="<?php echo Label::getLabel('LBL_Schedule_Lesson'); ?>">
                                                            <svg id="Layer_1_1_" enable-background="new 0 0 64 64" width="30px" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m56 40.10529v-28.10529c0-2.75684-2.24316-5-5-5h-2v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-5v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-6v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-5v-2c0-1.6543-1.3457-3-3-3s-3 1.3457-3 3v2h-2c-2.75684 0-5 2.24316-5 5v40c0 2.75684 2.24316 5 5 5h33.62347c2.07868 3.58081 5.94617 6 10.37653 6 6.61719 0 12-5.38281 12-12 0-4.83142-2.87561-8.99408-7-10.89471zm-11-35.10529c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-11 0c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-12 0c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-11 0c0-.55176.44824-1 1-1s1 .44824 1 1v6c0 .55176-.44824 1-1 1s-1-.44824-1-1zm-4 4h2v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h5v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h6v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h5v2c0 1.6543 1.3457 3 3 3s3-1.3457 3-3v-2h2c1.6543 0 3 1.3457 3 3v5h-50v-5c0-1.6543 1.3457-3 3-3zm0 46c-1.6543 0-3-1.3457-3-3v-33h50v20.39484c-.96082-.24866-1.96246-.39484-3-.39484-.6828 0-1.34808.07056-2 .1806v-5.1806c0-.55273-.44727-1-1-1h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h2.38086c-3.23914 2.15106-5.38086 5.82843-5.38086 10 0 1.40411.25494 2.74664.70001 4zm40-16h-4v-4h4zm4 22c-5.51367 0-10-4.48633-10-10s4.48633-10 10-10 10 4.48633 10 10-4.48633 10-10 10z"/><path d="m52 49.2774v-6.2774h-2v6.2774c-.59528.34644-1 .98413-1 1.7226 0 .10126.01526.19836.02979.29553l-3.65479 2.92322 1.25 1.5625 3.65161-2.92133c.22492.08759.46753.14008.72339.14008 1.10455 0 2-.89545 2-2 0-.73846-.40472-1.37616-1-1.7226z"/><path d="m15 22h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m26 22h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m37 22h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m42 30h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1zm1-6h4v4h-4z"/><path d="m15 33h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m26 33h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m37 33h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m15 44h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m26 44h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/><path d="m37 44h-6c-.55273 0-1 .44727-1 1v6c0 .55273.44727 1 1 1h6c.55273 0 1-.44727 1-1v-6c0-.55273-.44727-1-1-1zm-1 6h-4v-4h4z"/></svg>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED && $lessonData['issrep_id'] < 1 || $lessonData['issrep_status'] == IssuesReported::STATUS_RESOLVED && ($lessonData['issrep_issues_resolve_by'] != 3 && $lessonData['issrep_issues_resolve_by'] != 4 )) { ?>
                                                    <li>
                            							<a href="javascript:void(0);" onclick="issueReported('<?php echo $lessonData['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Issue_Reported'); ?>">
                            								<svg width="20px" viewBox="0 0 60 60" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="070---Laptop-Message-Not-Sent" fill="rgb(0,0,0)" fill-rule="nonzero"><path id="Shape" d="m58 48h-2v-33c-.0033061-2.7600532-2.2399468-4.9966939-5-5h-1v-7c0-1.65685425-1.3431458-3-3-3h-34c-1.6568542 0-3 1.34314575-3 3v7h-1c-2.76005315.0033061-4.99669388 2.2399468-5 5v33h-2c-1.1045695 0-2 .8954305-2 2v3c.00495836 3.8639376 3.13606244 6.9950416 7 7h46c3.8639376-.0049584 6.9950416-3.1360624 7-7v-3c0-1.1045695-.8954305-2-2-2zm-46-45c0-.55228475.4477153-1 1-1h34c.5522847 0 1 .44771525 1 1v28c0 .5522847-.4477153 1-1 1h-24c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h.8l-5.8 4.01v-4.01h1c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6c-.5522847 0-1-.4477153-1-1zm-6 12c0-1.6568542 1.34314575-3 3-3h1v19c0 1.6568542 1.3431458 3 3 3h3v4.01c.0055034 1.1006623.899324 1.9900138 2 1.99.4068132-.0004921.803803-.1250311 1.138-.357l8.174-5.643h19.688c1.6568542 0 3-1.3431458 3-3v-19h1c1.6568542 0 3 1.3431458 3 3v33h-48zm15 35h18v1c0 1.1045695-.8954305 2-2 2h-14c-1.1045695 0-2-.8954305-2-2zm37 3c-.0033061 2.7600532-2.2399468 4.9966939-5 5h-46c-2.76005315-.0033061-4.99669388-2.2399468-5-5v-3h17v1c0 2.209139 1.790861 4 4 4h14c2.209139 0 4-1.790861 4-4v-1h17z"/><path id="Shape" d="m15 52h-2c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h2c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><circle id="Oval" cx="5" cy="53" r="1"/><circle id="Oval" cx="9" cy="53" r="1"/><path id="Shape" d="m30 30c7.1797017 0 13-5.8202983 13-13 0-7.17970175-5.8202983-13-13-13s-13 5.82029825-13 13c.008266 7.1762751 5.8237249 12.991734 13 13zm0-24c6.0751322 0 11 4.9248678 11 11s-4.9248678 11-11 11-11-4.9248678-11-11c.0071635-6.0721626 4.9278374-10.9928365 11-11z"/><path id="Shape" d="m25.7 24.019c.1510172.6758719.6385372 1.2268337 1.291 1.459.9655907.3454686 1.9834689.5220496 3.009.522.7093957-.0007512 1.4163592-.0829563 2.107-.245 2.4813348-.5949487 4.5927859-2.2166431 5.8076453-4.4605466 1.2148595-2.2439034 1.4185843-4.8984494.5603547-7.3014534-.2308809-.6525752-.7807135-1.1408537-1.456-1.293-.6678543-.1529236-1.367337.0497373-1.85.536l-8.933 8.933c-.4854126.4831671-.6879357 1.1821742-.536 1.85zm10.9-9.352c.4529815 1.2787496.527288 2.6610594.214 3.981-.4622316 1.9319167-1.7262636 3.5750969-3.4750127 4.5173479s-3.816293 1.0941715-5.6839873.4176521z"/><path id="Shape" d="m23.423 21.35c.5285959-.0002743 1.035296-.2111595 1.408-.586l8.933-8.933c.4854126-.4831671.6879357-1.1821742.536-1.85-.1510172-.67587187-.6385372-1.22683366-1.291-1.459-3.2707615-1.16856777-6.9220918-.34781853-9.3781538 2.1080297-2.4560621 2.4558483-3.2771292 6.1071071-2.1088462 9.3779703.2876055.8027571 1.0472816 1.3393283 1.9 1.342zm-.231-6c.60786-2.5542076 2.6019103-4.5486446 5.156-5.157.5387455-.1273922 1.0903984-.1921543 1.644-.193.804837-.00046099 1.6036627.1385976 2.361.411l-8.948 8.92c-.453213-1.2787345-.527181-2.6612067-.213-3.981z"/></g></g></svg>
                            							</a>
                            						</li>
                                                <?php } ?>
                                                <?php if($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED &&  $countReviews == 0) { ?>
                                                    <li>
                                                        <a width="20px" href="javascript:void(0);" onclick="lessonFeedback('<?php echo $lessonData['sldetail_id'];  ?>');" class="" title="<?php echo Label::getLabel('LBL_Rate_Lesson'); ?>">
                                                            <svg  enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="30px" xmlns="http://www.w3.org/2000/svg"><g><path d="m489.456 0h-99.735c-4.151 0-7.515 3.364-7.515 7.515s3.364 7.515 7.515 7.515h99.735c4.144 0 7.515 3.371 7.515 7.515v151.253c0 4.144-3.371 7.515-7.515 7.515h-206.769v-3.613c0-14.66-11.926-26.585-26.585-26.585s-26.585 11.926-26.585 26.585v3.613h-76.549c-4.15 0-7.515 3.364-7.515 7.515s3.365 7.515 7.515 7.515h76.549v132.432l-17.849-20.004c-11.018-12.348-26.828-19.43-43.376-19.43-6.167 0-11.73 3.165-14.881 8.467s-3.271 11.702-.323 17.119l29.928 54.979c7.419 13.629 16.882 25.966 28.126 36.665l31.894 30.347v16.098c-7.801 1.621-13.68 8.546-13.68 16.821v24.983c0 9.474 7.707 17.181 17.181 17.181h118.487c9.474 0 17.182-7.707 17.182-17.181v-24.983c0-8.096-5.635-14.884-13.184-16.693v-16.705c6.849-8.64 28.086-40.348 28.086-101.259v-45.9c0-14.659-11.926-26.584-26.585-26.584-4.899 0-9.493 1.332-13.439 3.653-3.917-9.807-13.513-16.755-24.702-16.755-4.899 0-9.492 1.332-13.438 3.653-3.917-9.807-13.513-16.755-24.702-16.755-4.139 0-8.06.95-11.556 2.645v-42.792h206.769c12.431 0 22.544-10.114 22.544-22.544v-151.257c.001-12.431-10.112-22.544-22.543-22.544zm-195.213 251.518c6.372 0 11.556 5.184 11.556 11.556v13.103c0 4.151 3.364 7.515 7.515 7.515s7.515-3.364 7.515-7.515c0-6.372 5.184-11.556 11.556-11.556 6.371 0 11.555 5.184 11.555 11.556v13.102c0 4.151 3.364 7.515 7.515 7.515s7.515-3.364 7.515-7.515c0-6.372 5.184-11.555 11.556-11.555s11.556 5.183 11.556 11.555v45.9c0 64.562-25.791 93.094-26.025 93.346-1.324 1.395-2.062 3.246-2.062 5.17v18.959h-18.155c-4.151 0-7.515 3.364-7.515 7.515s3.364 7.515 7.515 7.515h29.185c1.187 0 2.153.966 2.153 2.153v24.983c0 1.187-.966 2.152-2.153 2.152h-118.488c-1.187 0-2.152-.965-2.152-2.152v-24.983c0-1.187.965-2.153 2.152-2.153h59.244c4.151 0 7.515-3.364 7.515-7.515s-3.364-7.515-7.515-7.515h-47.715v-18.959c0-2.058-.844-4.025-2.335-5.444l-34.229-32.569c-10.109-9.619-18.616-20.709-25.286-32.963l-29.928-54.979c-.707-1.896-.001-3.085 2.003-3.371 12.27 0 23.992 5.25 32.161 14.406l30.685 34.39c4.354 5.122 13.617 1.855 13.407-5.003v-170.462c0-6.372 5.184-11.556 11.556-11.556 6.371 0 11.555 5.184 11.555 11.556v85.374c0 4.151 3.364 7.515 7.515 7.515s7.515-3.364 7.515-7.515c.002-6.372 5.186-11.556 11.558-11.556z"/><path d="m122.909 181.312h-100.365c-4.144 0-7.515-3.371-7.515-7.515v-151.253c0-4.144 3.371-7.515 7.515-7.515h337.118c4.151 0 7.515-3.364 7.515-7.515s-3.364-7.514-7.515-7.514h-337.118c-12.431 0-22.544 10.113-22.544 22.544v151.253c0 12.43 10.113 22.544 22.544 22.544h100.365c4.15 0 7.515-3.364 7.515-7.515s-3.364-7.514-7.515-7.514z"/><path d="m305.453 92.526c4.415-4.303 5.975-10.619 4.069-16.483-1.905-5.864-6.88-10.058-12.982-10.945l-17.497-2.542c-.343-.05-.64-.265-.794-.576l-7.826-15.856c-2.728-5.529-8.253-8.964-14.42-8.964-6.166 0-11.692 3.434-14.421 8.964l-7.826 15.857c-.154.31-.45.526-.791.575l-17.501 2.542c-6.102.887-11.076 5.081-12.981 10.945s-.346 12.18 4.069 16.483l12.663 12.344c.248.242.361.589.302.931l-2.989 17.428c-1.042 6.076 1.409 12.103 6.397 15.727 4.988 3.625 11.478 4.094 16.936 1.225l15.652-8.229c.306-.162.672-.162.978 0l15.653 8.229c2.374 1.247 4.942 1.864 7.498 1.864 3.32 0 6.619-1.041 9.438-3.089 4.987-3.624 7.438-9.651 6.395-15.727l-2.988-17.428c-.059-.341.054-.689.302-.931zm-23.154 1.583c-3.791 3.695-5.52 9.016-4.624 14.233l2.989 17.43c.029.176.11.644-.419 1.027-.527.384-.951.162-1.108.08l-15.651-8.228c-2.344-1.233-4.913-1.848-7.484-1.848-2.57 0-5.14.616-7.483 1.847l-15.651 8.229c-.158.082-.579.306-1.109-.08-.529-.384-.449-.852-.418-1.028l2.989-17.429c.895-5.218-.834-10.539-4.625-14.233l-12.662-12.343c-.128-.125-.468-.457-.267-1.078.202-.622.673-.691.849-.716l17.501-2.542c5.239-.762 9.764-4.052 12.106-8.797l7.826-15.856c.079-.161.289-.586.943-.586s.863.426.943.586l7.826 15.855c2.341 4.747 6.868 8.036 12.109 8.799l17.498 2.542c.177.026.648.094.849.716.202.621-.138.953-.266 1.078z"/><path d="m145.641 123.23-2.989-17.428c-.059-.341.054-.689.303-.931l12.662-12.344c4.415-4.303 5.974-10.619 4.069-16.483s-6.879-10.058-12.982-10.945l-17.498-2.542c-.343-.05-.639-.265-.793-.576l-7.826-15.856c-2.729-5.529-8.254-8.964-14.42-8.964s-11.692 3.434-14.421 8.964l-7.825 15.856c-.154.311-.451.526-.792.576l-17.5 2.542c-6.102.887-11.076 5.081-12.981 10.945s-.346 12.18 4.069 16.483l12.663 12.344c.248.242.361.589.302.931l-2.989 17.428c-1.042 6.077 1.409 12.104 6.397 15.727 4.989 3.626 11.478 4.093 16.936 1.225l15.652-8.229c.306-.161.672-.161.979 0l15.653 8.229c2.374 1.247 4.942 1.864 7.498 1.864 3.32 0 6.619-1.041 9.438-3.089 4.986-3.624 7.437-9.651 6.395-15.727zm-13.176-29.122c-3.792 3.695-5.521 9.016-4.626 14.234l2.989 17.429c.03.176.111.644-.418 1.028-.528.386-.95.162-1.108.08l-15.652-8.229c-2.343-1.232-4.913-1.847-7.483-1.847s-5.141.616-7.483 1.847l-15.651 8.229c-.158.082-.579.305-1.108-.08-.529-.383-.449-.852-.418-1.028l2.989-17.429c.895-5.218-.834-10.539-4.625-14.233l-12.662-12.344c-.128-.125-.468-.457-.267-1.078.202-.622.673-.691.849-.716l17.501-2.542c5.238-.762 9.764-4.051 12.107-8.797l7.826-15.856c.079-.161.289-.586.943-.586s.864.426.943.586l7.826 15.855c2.342 4.747 6.867 8.036 12.108 8.799l17.499 2.542c.177.026.647.094.849.716.202.621-.138.953-.266 1.078z"/><path d="m455.289 92.526c4.415-4.303 5.975-10.619 4.069-16.483-1.905-5.864-6.88-10.058-12.982-10.945l-17.497-2.542c-.344-.05-.64-.265-.794-.576l-7.826-15.856c-2.729-5.529-8.254-8.964-14.42-8.964s-11.692 3.434-14.421 8.964l-7.825 15.855c-.155.311-.451.527-.793.577l-17.5 2.542c-6.103.887-11.076 5.081-12.981 10.946-1.905 5.864-.345 12.18 4.069 16.482l12.663 12.344c.247.242.361.589.301.931l-2.987 17.428c-1.043 6.077 1.408 12.104 6.396 15.728 4.986 3.625 11.476 4.094 16.935 1.225l15.652-8.229c.305-.16.673-.162.978 0l15.653 8.229c2.374 1.247 4.942 1.864 7.498 1.864 3.32 0 6.619-1.041 9.438-3.089 4.987-3.624 7.438-9.651 6.395-15.727l-2.988-17.428c-.059-.341.054-.689.302-.931zm-23.154 1.583c-3.791 3.695-5.52 9.016-4.624 14.233l2.989 17.43c.029.176.11.644-.419 1.027-.527.384-.951.162-1.108.08l-15.651-8.228c-2.343-1.233-4.913-1.848-7.483-1.848s-5.141.616-7.484 1.847l-15.652 8.229c-.156.082-.578.305-1.107-.08-.528-.383-.448-.851-.418-1.028l2.987-17.426c.896-5.218-.832-10.54-4.623-14.236l-12.662-12.345c-.127-.124-.468-.456-.266-1.077.203-.622.673-.691.849-.716l17.501-2.542c5.237-.762 9.762-4.051 12.107-8.797l7.826-15.856c.079-.161.29-.586.943-.586s.863.426.942.586l7.826 15.855c2.341 4.747 6.868 8.036 12.109 8.799l17.498 2.542c.177.026.648.094.849.716.203.621-.138.953-.266 1.078z"/></g></svg>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if( $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED || $lessonData['issrep_id'] > 0 ) { ?>
                                                    <li>
                            							<a href="javascript:void(0);" onclick="issueDetails('<?php echo $lessonData['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Issue_Details'); ?>">
                            								<svg version="1.1"  width="35px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            									 viewBox="0 0 363 363" style="enable-background:new 0 0 363 363;" xml:space="preserve">
                            									 <path d="M277.73,94.123c0,10.997-8.006,17.685-13.852,22.593c-2.214,1.859-6.335,5.251-6.324,6.518
                            									c0.04,4.97-3.956,8.939-8.927,8.939c-0.025,0-0.05,0-0.075,0c-4.936,0-8.958-3.847-8.998-8.792
                            									c-0.079-9.747,7.034-15.584,12.75-20.383c4.485-3.766,7.426-6.416,7.426-8.841c0-4.909-3.994-8.903-8.903-8.903
                            									c-4.911,0-8.906,3.994-8.906,8.903c0,4.971-4.029,9-9,9s-9-4.029-9-9c0-14.834,12.069-26.903,26.904-26.903
                            									C265.661,67.253,277.73,79.288,277.73,94.123z M248.801,140.481c-4.971,0-8.801,4.029-8.801,9v0.069
                            									c0,4.971,3.831,8.966,8.801,8.966s9-4.064,9-9.035S253.772,140.481,248.801,140.481z M67.392,203.174c-4.971,0-9,4.029-9,9
                            									s4.029,9,9,9h0.75c4.971,0,9-4.029,9-9s-4.029-9-9-9H67.392z M98.671,203.174c-4.971,0-9,4.029-9,9s4.029,9,9,9h0.749
                            									c4.971,0,9-4.029,9-9s-4.029-9-9-9H98.671z M363,59.425v101.301c0,23.985-19.232,43.448-43.217,43.448H203.066
                            									c-2.282,0-4.161-0.013-5.733-0.046c-1.647-0.034-3.501-0.047-4.224,0.033c-0.753,0.5-2.599,2.191-4.378,3.83
                            									c-0.705,0.649-1.503,1.363-2.364,2.149l-33.022,30.098c-2.634,2.403-6.531,3.025-9.793,1.587c-3.262-1.439-5.552-4.669-5.552-8.234
                            									v-95.417H43.72c-14.062,0-25.72,11.523-25.72,25.583v101.301c0,14.061,11.659,25.116,25.72,25.116h130.374
                            									c2.245,0,4.345,1.031,6.003,2.545L207,317.523v-85.539c0-4.971,4.029-9,9-9s9,4.029,9,9v105.938c0,3.565-2.04,6.747-5.303,8.186
                            									c-1.167,0.515-2.339,0.718-3.566,0.718c-2.204,0-4.378-0.905-6.069-2.449l-39.457-36.204H43.72c-23.986,0-43.72-19.13-43.72-43.116
                            									V163.757c0-23.985,19.734-43.583,43.72-43.583H138V59.425c0-23.986,19.885-43.251,43.871-43.251h137.913
                            									C343.768,16.174,363,35.439,363,59.425z M345,59.425c0-14.061-11.157-25.251-25.217-25.251H181.871
                            									C167.81,34.174,156,45.364,156,59.425v69.833v83.934l18.095-16.353c0.838-0.765,1.777-1.465,2.462-2.097
                            									c8.263-7.614,10.377-8.831,21.155-8.609c1.47,0.031,3.221,0.042,5.354,0.042h116.717c14.06,0,25.217-11.388,25.217-25.448V59.425z"
                            									/>
                            								</svg>
                            							</a>
                            						</li>
                                                <?php } ?>
                                                <?php if($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
                                                    <li>
                            							<a href="javascript:void(0);" onclick="requestReschedule('<?php echo $lessonData['sldetail_id']; ?>')" title="<?php echo Label::getLabel('LBL_Reschedule_Lesson'); ?>">
                            								<svg version="1.1" width="30px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            								viewBox="0 0 460.801 460.801" style="enable-background:new 0 0 460.801 460.801;" xml:space="preserve">
                            									<g>
                            										<g>
                            											<path d="M231.298,17.068c-57.746-0.156-113.278,22.209-154.797,62.343V17.067C76.501,7.641,68.86,0,59.434,0
                            											S42.368,7.641,42.368,17.067v102.4c-0.002,7.349,4.701,13.874,11.674,16.196l102.4,34.133c8.954,2.979,18.628-1.866,21.606-10.82
                            											c2.979-8.954-1.866-18.628-10.82-21.606l-75.605-25.156c69.841-76.055,188.114-81.093,264.169-11.252
                            											s81.093,188.114,11.252,264.169s-188.114,81.093-264.169,11.252c-46.628-42.818-68.422-106.323-57.912-168.75
                            											c1.653-9.28-4.529-18.142-13.808-19.796s-18.142,4.529-19.796,13.808c-0.018,0.101-0.035,0.203-0.051,0.304
                            											c-2.043,12.222-3.071,24.592-3.072,36.983C8.375,361.408,107.626,460.659,230.101,460.8
                            											c122.533,0.331,222.134-98.734,222.465-221.267C452.896,117,353.832,17.399,231.298,17.068z"/>
                            										</g>
                            									</g>
                            								</svg>
                            							</a>
                            						</li>
                                                <?php } ?>
                                                <?php $countRel=ScheduledLessonSearch::countPlansRelation($lessonData['sldetail_id']);
                                                    if( $countRel > 0 ){
                                                ?>
                                                <li>
                            						<a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lessonData['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?>">
                            							<svg   width="35px" enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m454.808 33.134h-9.067v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c0-13.271-10.796-24.067-24.067-24.067s-24.067 10.796-24.067 24.067v9.067h-34.7v-9.067c.001-13.271-10.796-24.067-24.066-24.067-13.271 0-24.067 10.796-24.067 24.067v9.067h-9.068c-22.405 0-40.632 18.228-40.632 40.632v183.537l-18.346-18.346c-9.384-9.384-24.652-9.383-34.036 0l-23.429 23.429c-9.384 9.383-9.384 24.652 0 34.035l75.81 75.81v99.136c0 22.405 18.228 40.633 40.632 40.633h255.136c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-255.135c-14.134 0-25.632-11.499-25.632-25.633v-84.136l61.184 61.184c.178.178.364.346.557.503.131.107.267.202.403.299.064.045.124.096.189.139.172.115.349.217.528.316.034.019.066.041.1.059.189.101.382.189.576.272.029.012.056.028.086.04.189.078.381.144.574.206.04.013.078.029.118.041.182.055.366.098.55.138.055.012.108.029.163.04.179.035.359.058.54.08.063.008.124.021.187.027.243.024.488.036.732.036h43.751l9.518 9.518c1.464 1.464 3.384 2.197 5.303 2.197s3.839-.733 5.303-2.197c2.929-2.929 2.929-7.678 0-10.607l-9.518-9.517v-43.749c0-.246-.012-.491-.036-.736-.005-.054-.017-.106-.023-.16-.023-.19-.048-.379-.085-.567-.01-.048-.024-.095-.035-.143-.042-.191-.087-.382-.143-.57-.01-.034-.024-.066-.035-.1-.063-.199-.132-.397-.212-.592-.009-.021-.02-.041-.029-.062-.086-.203-.179-.404-.283-.6-.014-.026-.03-.049-.044-.075-.103-.188-.211-.373-.331-.553-.037-.056-.081-.108-.12-.163-.102-.145-.204-.29-.317-.429-.158-.193-.325-.379-.503-.557l-61.185-61.185h258.087c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-2.203 0-4.179.956-5.551 2.469l-44.933-44.933v-141.333h366.034v51.151c0 4.142 3.358 7.5 7.5 7.5s7.5-3.358 7.5-7.5v-108.352c.001-22.405-18.227-40.632-40.632-40.632zm-234.556 402.478h-21.251l21.251-21.251zm-157.317-121.065 12.823-12.822 117.959 117.959-12.822 12.822zm141.388 94.53-117.959-117.959 12.823-12.822 117.959 117.959zm-170.12-136.084 23.429-23.429c3.535-3.535 9.288-3.536 12.823 0l18.126 18.126-36.252 36.25-18.125-18.125c-3.536-3.535-3.536-9.287-.001-12.822zm387.471-257.993c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c.001-5 4.068-9.067 9.067-9.067zm-82.833 0c5 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-16.556c0-.003 0-.006 0-.01s0-.006 0-.01v-16.557c0-5 4.067-9.067 9.067-9.067zm-91.9 9.067c0-5 4.067-9.067 9.067-9.067s9.067 4.067 9.067 9.067v16.557.01s0 .006 0 .01v16.556c0 5-4.067 9.067-9.067 9.067-4.999 0-9.067-4.067-9.067-9.067zm-73.767-9.067c4.999 0 9.067 4.067 9.067 9.067v33.133c0 5-4.067 9.067-9.067 9.067s-9.067-4.067-9.067-9.067v-33.133c0-5 4.067-9.067 9.067-9.067zm-58.767 100.967v-42.201c0-14.134 11.499-25.632 25.632-25.632h9.068v9.066c0 13.271 10.796 24.067 24.067 24.067 13.27 0 24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h34.7v9.066c0 13.271 10.796 24.067 24.067 24.067s24.067-10.796 24.067-24.067v-9.066h9.067c14.134 0 25.633 11.499 25.633 25.632v42.201z"/><path d="m487.941 204.619c-4.142 0-7.5 3.358-7.5 7.5v259.248c0 14.134-11.499 25.633-25.633 25.633h-29.634c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h29.634c22.405 0 40.633-18.228 40.633-40.633v-259.248c0-4.142-3.358-7.5-7.5-7.5z"/><path d="m164.89 180.667h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 230.367h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m164.89 280.067h265.067c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5h-265.067c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5z"/><path d="m437.458 371.967c0-4.142-3.358-7.5-7.5-7.5h-173.95c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5h173.95c4.142 0 7.5-3.358 7.5-7.5z"/></g></svg>
                            						</a>
                            					</li>
                                                <?php } ?>
                                                <?php if($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING || $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lessonData['sldetail_id']; ?>')" class="" title="<?php echo Label::getLabel('LBL_Cancel_Lesson'); ?>">
                                                            <svg  width="14px" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <div>
                                            <a href="javascript:void(0);" style="display:none;" class="btn btn--primary btn--large btn--sticky end_lesson_now" id="endL" onclick="endLesson(<?php echo $lessonData['sldetail_id']; ?>);">
                                                <?php echo Label::getLabel('LBL_End_Lesson'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>

                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tabs-content-js">
                    <div class="box">
                        <div class="box-head">
                            <div class="page-head">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>
                                            <?php echo Label::getLabel('LBL_Flashcards'); ?>
                                        </h5>
                                    </div>
                                    <div>
                                        <a class="btn btn--secondary btn--small" href="javascript:void(0)" onclick="flashCardForm(<?php echo $lessonData['slesson_id'] ?>, 0, <?php echo $lessonData['learnerId'] ?>, <?php echo $lessonData['teacherId'] ?>)">
                                            <?php echo Label::getLabel('LBL_Add_New'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-search form-search--single">
                                <?php
$frmSrchFlashCard->addFormTagAttribute( 'onsubmit', 'searchFlashCards(this); return false;' );
$fldBtnSubmit = $frmSrchFlashCard->getField('btn_submit');
$fldBtnSubmit->addFieldTagAttribute( 'class', 'form__action' );

echo $frmSrchFlashCard->getFormTag();
echo $frmSrchFlashCard->getFieldHtml('lesson_id');
echo $frmSrchFlashCard->getFieldHtml('page');
?>
                                <div class="form__element">
                                    <?php echo $frmSrchFlashCard->getFieldHtml('keyword'); ?>
                                    <span class="form__action-wrap">
                                        <?php echo $frmSrchFlashCard->getFieldHtml('btn_submit'); ?>
                                        <span class="svg-icon">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg" width="14.844" height="14.843" viewBox="0 0 14.844 14.843">
                                                <path d="M251.286,196.714a4.008,4.008,0,1,1,2.826-1.174A3.849,3.849,0,0,1,251.286,196.714Zm8.241,2.625-3.063-3.062a6.116,6.116,0,0,0,1.107-3.563,6.184,6.184,0,0,0-.5-2.442,6.152,6.152,0,0,0-3.348-3.348,6.271,6.271,0,0,0-4.884,0,6.152,6.152,0,0,0-3.348,3.348,6.259,6.259,0,0,0,0,4.884,6.152,6.152,0,0,0,3.348,3.348,6.274,6.274,0,0,0,6-.611l3.063,3.053a1.058,1.058,0,0,0,.8.34,1.143,1.143,0,0,0,.813-1.947h0Z" transform="translate(-245 -186.438)"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="box-body" id="flashCardListing"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
	jQuery(document).ready(function (e) {
		$('body').addClass('is-screen-on');
		$(".tabs-content-js").hide();
		$(".tabs-js li:first").addClass("is-active").show();
		$(".tabs-content-js:first").show();

		$(".tabs-js li").click(function() {
			$(".tabs-js li").removeClass("is-active");
			$(this).addClass("is-active");
			$(".tabs-content-js").hide();
			var activeTab = $(this).find("a").attr("href");
			$(activeTab).fadeIn();
			return false;
		});
	});

</script>
