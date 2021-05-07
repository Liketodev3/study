<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$user_timezone = MyDate::getUserTimeZone();
$curDate = MyDate::convertTimeFromSystemToUserTimezone('Y/m/d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
$currentUnixTime = strtotime($curDate);

$startTime = MyDate::convertTimeFromSystemToUserTimezone('Y/m/d H:i:s', date($lessonData['slesson_date'] . ' ' . $lessonData['slesson_start_time']), true, $user_timezone);
$startDateTimeUnixtime = strtotime($startTime);

$endTime = MyDate::convertTimeFromSystemToUserTimezone('Y/m/d H:i:s', date($lessonData['slesson_end_date'] . ' ' . $lessonData['slesson_end_time']), true, $user_timezone);
$endDateTimeUnixtime = strtotime($endTime);

$chatId = UserAuthentication::getLoggedUserId();
$countReviews = TeacherLessonReview::getTeacherTotalReviews($lessonData['teacherId'], $lessonData['slesson_id'], $chatId);
$studentImage = '';

if (true == User::isProfilePicUploaded($lessonData['learnerId'])) {
    $studentImage = CommonHelper::generateFullUrl('Image', 'user', array($lessonData['learnerId'], 'normal', 1)) . '?' . time();
}

$canEnd = ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED && $startDateTimeUnixtime < $currentUnixTime);

$teachLang = Label::getLabel('LBL_Trial');
if ($lessonData['is_trial'] == applicationConstants::NO) {
    $teachLang = TeachingLanguage::getLangById($lessonData['slesson_slanguage_id']);
}

$chat_group_id = '';
$lessonTitle = $lessonData['grpcls_title'];
if($lessonData['slesson_grpcls_id'] <= 0)
{
    $chat_group_id = "LESSON-" . $lessonData['slesson_id'];
    $lessonTitleLabel = Label::getLabel('LBL_{teach-lang},{n}_minutes_of_Lesson');
    $lessonTitle =  str_replace(['{teach-lang}','{n}'], [$teachLang, $lessonData['op_lesson_duration']], $lessonTitleLabel);
}

$scheduledLessonAction = ($lessonData['is_trial']) ? 'free_trial' : '';

$lessonsStatus = $statusArr[$lessonData['sldetail_learner_status']];
$lessonData['lessonReschedulelogId'] =  FatUtility::int($lessonData['lessonReschedulelogId']);

if ($lessonData['lessonReschedulelogId'] > 0 && ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING ||
    $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED)) {
    $lessonsStatus = Label::getLabel('LBL_Rescheduled');
    if ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) {
        $lessonsStatus = Label::getLabel('LBL_Pending_for_Reschedule');
    }
}
$isScheduled = $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED;
$isJoined = $lessonData['sldetail_learner_join_time'] > 0;

$activeMettingTool = FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_STRING, ApplicationConstants::MEETING_COMET_CHAT);
?>
<div class="session__head">
    <div class="session-infobar">
        <div class="row justify-content-between align-items-center">
            <div class="col-xl-8 col-lg-8 col-sm-12">
                <div class="session-infobar__top">
                    <h4>
                        <?php 
                            echo $lessonTitle;
                            if($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
                            <span class="color-primary"><?php echo $lessonsStatus; ?></span> 
                        <?php } 
                        echo ' '.Label::getLabel('LBL_with');
                    ?>
                    </h4>
                    <div class="profile-meta">
                        <div class="profile-meta__media">
                            <span class="avtar avtar--xsmall" data-title="<?php echo CommonHelper::getFirstChar($lessonData['teacherFullName']); ?>">
                                <?php
                                    if (true == User::isProfilePicUploaded($lessonData['teacherId'])) {
                                        $img = CommonHelper::generateUrl('Image', 'user', array($lessonData['teacherId'], 'normal', 1), CONF_WEBROOT_FRONT_URL) . '?' . time();
                                        echo '<img src="' . $img . '"  alt="'.$lessonData['teacherFullName'].'" />';
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="profile-meta__details">
                            <h4 class="bold-600"><?php echo $lessonData['teacherFullName']; ?></h4>
                            
                        </div>
                    </div>
                    <span class="badge color-red badge--round lesson-status-badge margin-left-2"><?php echo $lessonsStatus; ?></span>
                </div>
                <div class="session-infobar__bottom">
                        <?php if($lessonData['slesson_date'] != '0000-00-00') { ?>
                            <div class="session-time">
                                    <p>
                                        <span><?php echo date('h:i A', $startDateTimeUnixtime).' - '.date('h:i A', $endDateTimeUnixtime);  ?>,</span> 
                                        <?php echo date('l, F d, Y', $startDateTimeUnixtime); ?>
                                </p>
                            </div>
                        <?php } ?>
                    <?php if ($lessonData['slesson_status'] != ScheduledLesson::STATUS_CANCELLED && $lessonData['isLessonPlanAttach'] > 0) {  ?>
                        <div class="session-resource">
                            <a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lessonData['sldetail_id']; ?>')" class="attachment-file">
                                <svg class="icon icon--issue icon--attachement icon--xsmall color-black"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#attach'; ?>"></use></svg>
                                <?php echo $lessonData['tlpn_title'] ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-sm-12">
            <div class="session-infobar__action">
                    <span class="btn btn--live" id="end_lesson_timer" style="display:none;"> </span>
                    <button class="btn bg-red end_lesson_now" <?php echo !$canEnd || !$isJoined ? 'style="display:none;"' : '' ?> id="endL" onclick="endLesson(<?php echo $lessonData['slesson_id']; ?>);"><?php echo Label::getLabel('LBL_End_Lesson'); ?>
                    </button>
                <?php if ($lessonData['sldetail_learner_status'] != ScheduledLesson::STATUS_CANCELLED) { ?>
                    <?php if ($lessonData['slesson_grpcls_id'] <= 0 && $lessonData['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED && $currentUnixTime < $startDateTimeUnixtime) { ?>
                        <button class="btn btn--third reschedule-lesson--js" onclick="requestReschedule('<?php echo $lessonData['sldetail_id']; ?>');"><?php echo Label::getLabel('LBL_Reschedule'); ?></button>
                    <?php } ?>
                    <?php if (($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING || $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) && $currentUnixTime < $startDateTimeUnixtime) { ?>
                        <button onclick="cancelLesson('<?php echo $lessonData['sldetail_id']; ?>');"  class="btn btn--bordered color-third cancel-lesson--js"><?php echo Label::getLabel('LBL_Cancel'); ?></button>
                    <?php } ?>
                    <?php if ($lessonData['slesson_grpcls_id'] <= 0 && $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) { ?>
                        <button class="btn btn--third" onclick="viewBookingCalendar('<?php echo $lessonData['sldetail_id']; ?>','<?php echo $scheduledLessonAction; ?>');"><?php echo Label::getLabel('LBL_Schedule'); ?></button>
                    <?php } ?>
                    <?php if (($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED || ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED && $currentUnixTime > $endDateTimeUnixtime && $lessonData['slesson_teacher_join_time'] == 0)) && ($lessonData['issrep_id'] < 1 || $lessonData['slesson_has_issue'] == applicationConstants::NO)) { ?>
                        <button class="btn btn--third" onclick="issueReported('<?php echo $lessonData['sldetail_id']; ?>');"><?php echo Label::getLabel('LBL_Issue_Reported'); ?></button>
                    <?php } ?>
                    <?php if ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED || $lessonData['issrep_id'] > 0) { ?>
                        <button class="btn btn--third" onclick="issueDetails('<?php echo $lessonData['sldetail_id']; ?>');"><?php echo Label::getLabel('LBL_Issue_Details'); ?></button>
                    <?php } ?>
                    <?php if ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_COMPLETED &&  $countReviews == 0) { ?>
                        <button class="btn btn--third"onclick="lessonFeedback('<?php echo $lessonData['sldetail_id'];  ?>');"><?php echo Label::getLabel('LBL_Rate_Lesson'); ?></button>
                    <?php } ?>
                <?php } ?>
                </div>
            </div>
        </div>  
    </div>
</div>
<div class="session__body">
    <div class="sesson-window"  style="background-image:url(<?php echo CommonHelper::generateUrl('Image', 'lesson', array($siteLangId), CONF_WEBROOT_FRONT_URL) ?>">
        <div class="sesson-window__content lessonBox" id="lessonBox"> <!-- session-window__frame -->
        <div class="session-status">
                <?php 
                    $showGoToDashboardBtn = true;
                    $statusInfoLabel = '';
                    if ($currentUnixTime > $endDateTimeUnixtime && $lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) { 
                        $statusInfoLabel = Label::getLabel('LBL_Note_End_time_for_this_lesson_is_passed._Schedule_more_lessons.');   
                ?>
                <div class="status_media">
                    <svg class="icon"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#clock'; ?>"></use></svg>
                </div> 
             <?php }
                    switch ($lessonData['sldetail_learner_status']) {
                        case ScheduledLesson::STATUS_NEED_SCHEDULING:
                            $statusInfoLabel = Label::getLabel('LBL_Note_This_lesson_is_Unscheduled._schedule_it');
                        break;
                        case ScheduledLesson::STATUS_COMPLETED:
                            $statusInfoLabel = Label::getLabel('LBL_Note_This_lesson_is_completed');
                            if ($countReviews <= 0) {
                                $statusInfoLabel = Label::getLabel('LBL_Note_This_lesson_is_completed._rate_it.');
                            }
                        break;
                        case ScheduledLesson::STATUS_CANCELLED:
                            $statusInfoLabel = Label::getLabel('LBL_Note_This_Lesson_has_been_cancelled._Schedule_more_lessons.');
                        break;
                        case ScheduledLesson::STATUS_ISSUE_REPORTED:
                            $statusInfoLabel = Label::getLabel('LBL_Note_An_Issue_Is_Reported.');
                            $showGoToDashboardBtn = false;
                        break;
                        case ScheduledLesson::STATUS_SCHEDULED:
                            $showGoToDashboardBtn = false;
                            if($currentUnixTime > $endDateTimeUnixtime){
                                $showGoToDashboardBtn = true;
                            }
                        break;
                       
                    }
                    if(!empty($statusInfoLabel)) {
                        echo "<p>".$statusInfoLabel."</p>";
                    }
                    if($showGoToDashboardBtn){
                ?>
                     <a href="<?php echo CommonHelper::generateUrl('LearnerScheduledLessons'); ?>" class="btn bg-primary"><?php echo Label::getLabel('LBL_Go_to_Dashboard.'); ?></a>
                <?php } ?>
                <?php ?>
                <?php if ($lessonData['sldetail_learner_join_time'] == '0000-00-00 00:00:00' || $activeMettingTool == ApplicationConstants::MEETING_ZOOM) { ?>
                    <div class="join-btns join_lesson_now" id="joinL" <?php echo ($startDateTimeUnixtime > $currentUnixTime || $currentUnixTime > $endDateTimeUnixtime || !$isScheduled ? 'style="display:none;"' : '') ?>>
                        <?php if ($activeMettingTool == ApplicationConstants::MEETING_ZOOM){ ?>
                            <a href="javascript:void(0);" class="btn btn--primary" onclick="joinLesson('<?php echo $chatId; ?>','<?php echo $lessonData['teacherId']; ?>');"><?php echo Label::getLabel('LBL_Join_Lesson_From_Browser'); ?></a>
                            <a href="javascript:void(0);" class="btn btn--secondary" onclick="joinLessonFromApp('<?php echo $chatId; ?>','<?php echo $lessonData['teacherId']; ?>');"><?php echo Label::getLabel('LBL_Join_Lesson_From_App'); ?></a>
                        <?php }else{ ?>
                            <a href="javascript:void(0);" class="btn btn--secondary" id="joinL" onclick="joinLesson('<?php echo $chatId; ?>','<?php echo $lessonData['teacherId']; ?>');"><?php echo Label::getLabel('LBL_Join_Lesson'); ?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="start-lesson-timer timer" style="display:none;">
                <h5 class="timer-title"><?php echo Label::getLabel('LBL_Starts_In'); ?></h5>
                    <div class="countdown-timer size_lg" id="start_lesson_timer">
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    var flashCardEnabled = '<?php echo $flashCardEnabled ?: 0 ?>';
    var is_time_up = '<?php echo $endTime > 0 && $endDateTimeUnixtime < $currentUnixTime ?>';
    var learnerLessonStatus = '<?php echo $lessonData['sldetail_learner_status']; ?>';
    var lesson_joined = '<?php echo $isJoined ?>';
    var lesson_completed = '<?php echo $lessonData['sldetail_learner_end_time'] > 0 ?>';
    var teacherId = '<?php echo $lessonData['teacherId'] ?>';
    var canEnd = '<?php echo $canEnd ?>';
    var sldetail_id = "<?php echo $lessonData['sldetail_id']; ?>";
    var groupClassId = <?php echo $lessonData['slesson_grpcls_id']; ?>;
    var isGroupClass = (groupClassId > 0) ? true : false;

    var chat_appid = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_APP_ID'); ?>';
    var chat_auth = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_AUTH'); ?>';
    var chat_id = '<?php echo $chatId; ?>';
    var chat_group_id = '<?php echo $chat_group_id; ?>';
    var chat_api_key = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_API_KEY'); ?>';
    var chat_name = '<?php echo $lessonData['learnerFname']; ?>';
    var chat_avatar = "<?php echo $studentImage; ?>";
    var chat_friends = "<?php echo $lessonData['teacherId']; ?>";
    var worker = new Worker(siteConstants.webroot + 'js/worker-time-interval.js?');
    
    if (!isZoomMettingToolActive && !is_time_up && lesson_joined && !lesson_completed && learnerLessonStatus != '<?php echo ScheduledLesson::STATUS_CANCELLED ?>') {
        joinLesson(chat_id, teacherId);
    }

    function joinLessonButtonAction() {
        $("#joinL, .reschedule-lesson--js, .cancel-lesson--js").hide();
        $("#endL").show();
        checkEveryMinuteStatus();
        checkNewFlashCards();
        searchFlashCards(document.frmFlashCardSrch);
        $('.screen-chat-js').show();
        $('.lessonBox').removeClass('sesson-window__content').addClass('session-window__frame').show();
    }

    function endLessonButtonAction() {
        $("#end_lesson_time_div,#endL, .screen-chat-js").hide();
        $('.lessonBox').removeClass('session-window__frame').addClass('sesson-window__content').hide();
        searchFlashCards(document.frmFlashCardSrch);
        if (typeof checkEveryMinuteStatusVar != "undefined") {
            clearInterval(checkEveryMinuteStatusVar);
        }
        if (typeof checkNewFlashCardsVar != "undefined") {
            clearInterval(checkNewFlashCardsVar);
        }
        worker.terminate();
    }

    function checkEveryMinuteStatus() {
        if (typeof checkEveryMinuteStatusVar != "undefined") {
            return;
        }
        checkEveryMinuteStatusVar = setInterval(function() {
            fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'checkEveryMinuteStatus', ['<?php echo $lessonData['sldetail_id'] ?>']), '', function(t) {
                var t = JSON.parse(t);
                if (!lesson_joined && !lesson_completed && t.has_teacher_joined == 1 && t.has_learner_joined == 0) {
                    $.mbsmessage('<?php echo Label::getLabel('LBL_Teacher_Has_Joined_Now_you_can_also_Join_The_Lesson!'); ?>', true, 'alert alert--success');
                }

                if (t.slesson_status == '<?php echo ScheduledLesson::STATUS_CANCELLED ?>') {
                    location.reload();
                    return;
                }

                if (t.slesson_status == '<?php echo ScheduledLesson::STATUS_COMPLETED ?>' && learnerLessonStatus == '<?php echo ScheduledLesson::STATUS_SCHEDULED ?>') {
                    
                    if (!isGroupClass) {
                        $.alert({
                            title: '<?php echo Label::getLabel('LBL_End_Lesson'); ?>',
                            content: '<?php echo Label::getLabel('LBL_Teacher_Ends_The_Lesson!'); ?>',
                            useBootstrap: false,
                            boxWidth: '20%',
                            escapeKey: false,
                            onClose: function() {
                                $.mbsmessage.close();
                                location.reload();
                            },
                        });
                        setTimeout(function() {
                            $.mbsmessage.close();
                            location.reload();
                        }, 1500);
                        return;
                    } else {
                       if(isConfirmpopOpen){
                           return;
                       }
                       isConfirmpopOpen = true;
                        $.confirm({
                            title: langLbl.Confirm,
                            content: '<?php echo Label::getLabel('LBL_Teacher_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also'); ?>',
                            autoClose: 'Quit|10000',
                            buttons: {
                                Proceed: {
                                    text: langLbl.Proceed,
                                    btnClass: 'btn btn--primary',
                                    keys: ['enter', 'shift'],
                                    action: function() {
                                        endLessonSetup(sldetail_id);
                                    }
                                },
                                Quit: {
                                    text: langLbl.Quit,
                                    btnClass: 'btn btn--secondary',
                                    action: function() {
                                        isConfirmpopOpen = false;
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }, 60000);
    }

    function checkNewFlashCards() {
        if ((typeof flashCardEnabled !== typeof undefined) && !flashCardEnabled) {
            return;
        }
        checkNewFlashCardsVar = setInterval(function() {
            searchFlashCards(document.frmFlashCardSrch);
        }, 30000)
    }

    function countDownTimer(start, end, func) {
        if (end > start) {
            worker.postMessage({
                'start': start,
                end: end
            });
        }

        worker.onmessage = function(e) {
            func(e.data);
        };
    }

    function endLessonCountDownTimer(curDate, endTime) {
        countDownTimer(curDate, endTime, function(w_res_data) {
            if (w_res_data) {
                $('#end_lesson_timer').show();
                if (lesson_joined) {
                    $('#endL').show();
                }
            } else {
                $('#end_lesson_timer').hide();
            }
            $('#end_lesson_timer').html(w_res_data);
        });
    }

    $(function() {
        var curDate = "<?php echo $curDate; ?>";
        var startTime = "<?php echo $startTime; ?>";
        var endTime = "<?php echo $endTime; ?>";
        <?php if ($lessonData['sldetail_learner_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
            if (startTime && curDate < startTime) {
                countDownTimer(curDate, startTime, function(w_res_data) {
                    $('#start_lesson_timer').html(w_res_data);
                    if (w_res_data) {
                        $('.timer.start-lesson-timer').show();
                    } else {
                        $('.timer.start-lesson-timer,.reschedule-lesson--js,.cancel-lesson--js').hide();
                        fcom.ajax(fcom.makeUrl('LearnerScheduledLessons', 'startLessonAuthentication', ['<?php echo $lessonData['sldetail_id'] ?>']), '', function(t) {
                            if (t != 0) {
                                $(".join_lesson_now").show();
                                endLessonCountDownTimer(startTime, endTime);
                                checkEveryMinuteStatus();
                            }
                        });
                    }
                });
            } else if (endTime && curDate < endTime && canEnd && !lesson_completed) {
                endLessonCountDownTimer(curDate, endTime)
            }

            if ($('.timer.start-lesson-timer').is(":visible")) {
                $("#lesson_actions").show();
            }
        <?php } ?>

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