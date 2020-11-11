<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$activeMettingTool = FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_STRING, ApplicationConstants::MEETING_COMET_CHAT);

$isCometChatMeetingToolActive = $activeMettingTool==ApplicationConstants::MEETING_COMET_CHAT;
$isLessonSpaceMeetingToolActive = $activeMettingTool==ApplicationConstants::MEETING_LESSON_SPACE;
$isZoomMettingToolActive = $activeMettingTool==ApplicationConstants::MEETING_ZOOM;

if($isZoomMettingToolActive){ ?>
    <script src="<?php echo CONF_WEBROOT_URL ?>js/zoom_tool.js"></script>
<?php } ?>

<script >
var lDetailId = '<?php echo $lDetailId; ?>';
var lessonId = '<?php echo $lessonId; ?>';
var is_grpcls = '<?php echo $lessonRow['slesson_grpcls_id']>0 ?>';

var isCometChatMeetingToolActive = '<?php echo $isCometChatMeetingToolActive ?>';
var isZoomMettingToolActive = '<?php echo $isZoomMettingToolActive; ?>';
var isLessonSpaceMeetingToolActive = '<?php echo $isLessonSpaceMeetingToolActive; ?>';

var testTool = window.testTool;

const ZOOM_API_KEY = '<?php echo FatApp::getConfig('CONF_ZOOM_API_KEY') ?>';
</script>
<div class="box -padding-20">
	<div id="listItems"></div>
</div>