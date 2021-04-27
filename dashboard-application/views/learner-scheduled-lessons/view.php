<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$activeMettingTool = FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_STRING, ApplicationConstants::MEETING_COMET_CHAT);

$isCometChatMeetingToolActive = $activeMettingTool==ApplicationConstants::MEETING_COMET_CHAT;
$isLessonSpaceMeetingToolActive = $activeMettingTool==ApplicationConstants::MEETING_LESSON_SPACE;
$isZoomMettingToolActive = $activeMettingTool==ApplicationConstants::MEETING_ZOOM;

$lessonTitle = $lesson['grpcls_title'];
if ($lesson['slesson_grpcls_id'] <= 0) {
    $str = Label::getLabel('LBL_{teach-lang},{n}_minutes_of_Lesson');
    echo  str_replace(['{teach-lang}','{n}'], [$teachLang, $lesson['op_lesson_duration']], $str);
}

if($isZoomMettingToolActive){ ?>
    <script src="<?php echo CONF_WEBROOT_FRONTEND ?>js/zoom_tool.js"></script>
<?php } ?>

<script >
var lDetailId = '<?php echo $lDetailId; ?>';
var lessonId = '<?php echo $lessonId; ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';

var isCometChatMeetingToolActive = '<?php echo $isCometChatMeetingToolActive ?>';
var isZoomMettingToolActive = '<?php echo $isZoomMettingToolActive; ?>';
var isLessonSpaceMeetingToolActive = '<?php echo $isLessonSpaceMeetingToolActive; ?>';

var testTool = window.testTool;
var isConfirmpopOpen = false;
const ZOOM_API_KEY = '<?php echo FatApp::getConfig('CONF_ZOOM_API_KEY', FatUtility::VAR_STRING, '') ?>';
</script>
 <!-- [ PAGE ========= -->
 <main class="page">

<div class="session">
    <div class="session__head">
        <div class="session-infobar">
            <div class="row justify-content-between align-items-center">
                <div class="col-xl-8 col-lg-8 col-sm-12">
                    <div class="session-infobar__top">
                        <h4>30 Minutes Of Trial Lesson <span class="color-primary">Scheduled</span> with</h4>
                        <div class="profile-meta">
                            <div class="profile-meta__media">
                                <span class="avtar avtar--xsmall" data-title="M"><img src="images/emp_6.jpg" alt=""></span>
                            </div>
                            <div class="profile-meta__details">
                                <h4 class="bold-600">Mark Boucher</h4>
                                
                            </div>
                        </div>
                    </div>
                    <div class="session-infobar__bottom">
                        <div class="session-time">
                            <p><span>04:30 PM - 05:00 PM,</span> Friday, March 12, 2021</p>
                        </div>
                        <div class="session-resource">
                            <a href="#" class="btn btn--transparent btn--addition color-primary btn--small">Add Lesson Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-sm-12">
                    <div class="session-infobar__action">
                        <button class="btn btn--bordered color-third">Cancel</button>
                        <button class="btn btn--third">Reschedule</button>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div class="session__body">
        <div class="sesson-window"  style="background-image:url(<?php echo CommonHelper::generateUrl('Image', 'lesson', array($siteLangId), CONF_WEBROOT_FRONT_URL) ?>">
            <div class="sesson-window__content">
                <div class="start-lesson-timer">
                    <h5 class="timer-title">Your Class Starts in</h5>
                    <div class="countdown-timer">
                        <ul>
                            <li>00</li>
                            <li>01</li>
                            <li>26</li>
                            <li>42</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</main>
<!-- ] -->
<div class="box -padding-20">
	<!-- <div id="listItems"></div> -->
</div>