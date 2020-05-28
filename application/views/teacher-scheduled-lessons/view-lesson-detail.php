<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$user_timezone = MyDate::getUserTimeZone();
$curDate = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date('Y-m-d H:i:s'), true , $user_timezone );

$startTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date($lessonData['slesson_date'] .' '. $lessonData['slesson_start_time']), true , $user_timezone );

$endTime = MyDate::convertTimeFromSystemToUserTimezone( 'Y/m/d H:i:s', date($lessonData['slesson_end_date'] .' '. $lessonData['slesson_end_time']), true , $user_timezone );
$chatId = UserAuthentication::getLoggedUserId();
$teacherImageTag = '';
$teacherImage = '';
$studentImageTag = '';
$studentImage = '';
$baseSeoUrl = CommonHelper::generateUrl('Teachers').'/';
if( true == User::isProfilePicUploaded( $lessonData['learnerId'] ) ){
    $studentImage = CommonHelper::generateFullUrl('Image','user', array( $lessonData['learnerId'])).'?'.time();
    $studentImageTag =  '<img src="'.$studentImage.'" />';
}

if( true == User::isProfilePicUploaded( $lessonData['teacherId'] ) ){
    $teacherImage = CommonHelper::generateFullUrl('Image','user', array( $lessonData['teacherId'] )).'?'.time();
    $teacherImageTag  = '<img src="'.$teacherImage.'" />';
}
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        if( sessionStorage.getItem('cometChatUserExists') != null){
            if(sessionStorage.getItem('cometChatUserExists')  != '<?php echo "LESSON-".$lessonData['slesson_id']; ?>'){
                sessionStorage.removeItem('cometChatUserExists');
            }
        }
    });
	function joinLessonButtonAction(){
		$("#joinL").hide();
		$("#endL").show();
		$('.screen-chat-js').show();
		checkEveryMinuteStatus();
		searchFlashCards(document.frmFlashCardSrch);
		checkNewFlashCards();
<?php if( $lessonData['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED ){ ?>
		$("#end_lesson_time_div").show();
<?php }?>
	}

	function endLessonButtonAction(){
		$("#joinL").show();
		$("#endL").hide();
		$('.screen-chat-js').hide();
		searchFlashCards(document.frmFlashCardSrch);
		clearInterval(checkEveryMinuteStatusVar);
		clearInterval(checkNewFlashCardsVar);
		sessionStorage.removeItem('cometChatUserExists');
		sessionStorage.removeItem('showEndLessonNotification');
		$("#end_lesson_time_div").hide();
	}

	var chat_appid = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_APP_ID'); ?>';
    var chat_auth = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_AUTH'); ?>';
	var chat_id = '<?php echo $chatId; ?>';
	var chat_group_id = '<?php echo "LESSON-".$lessonData['slesson_id']; ?>';
	var chat_name = '<?php echo $lessonData['teacherFname']; ?>';
	var chat_api_key = '<?php echo FatApp::getConfig('CONF_COMET_CHAT_API_KEY'); ?>';
    var chat_avatar = "<?php echo $teacherImage; ?>";

	var CometJsonTeacherData = [{"userId":"<?php echo $chatId; ?>","fname":"<?php echo $lessonData['teacherFname']; ?>","avatarURL":"<?php echo $teacherImage; ?>","profileURL":"<?php echo $baseSeoUrl.$lessonData['teacherUrlName']; ?>", "role":"<?php echo User::getUserTypesArr()[User::USER_TYPE_TEACHER]; ?>"}];

	var CometJsonLearnerData = [{"userId":"<?php echo $lessonData['learnerId']; ?>","fname":"<?php echo $lessonData['learnerFname']; ?>","avatarURL":"<?php echo $studentImage; ?>","profileURL":"<?php echo $baseSeoUrl.$lessonData['learnerUrlName']; ?>","role":"<?php echo User::getUserTypesArr()[User::USER_TYPE_LEANER]; ?>"}];

	var CometJsonData = CometJsonTeacherData.concat(CometJsonLearnerData);

	var CometJsonFriendData = {"lessonId":"<?php echo $lessonData['slesson_id'] ?>","userId":"<?php echo $chatId;?>","friendId":"<?php echo $lessonData['learnerId']; ?>"};

	function checkEveryMinuteStatus(){
	   checkEveryMinuteStatusVar = setInterval(function(){
			fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','checkEveryMinuteStatus',['<?php echo $lessonData['slesson_id'] ?>']),'',function(t){
				var t = JSON.parse(t);
				if(t.slesson_status > 1){

                $.confirm({
                    title: langLbl.Confirm,
                    content: '<?php echo Label::getLabel('LBL_Learner_Ends_The_Lesson_Do_Yoy_Want_To_End_It_From_Your_End_Also!'); ?>',
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
			/*fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','searchFlashCards'),'',function(t){
				var t = JSON.parse(t);
				$('#flashCardListing').html(t.html);
			});*/
			searchFlashCards(document.frmFlashCardSrch);
		},30000)
	}

	$(function(){
		<?php if( $lessonData['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED ){ ?>
		$('#start_lesson_timer').countdowntimer({
			startDate : "<?php echo $curDate; ?>",
			dateAndTime : "<?php echo $startTime; ?>",
			size : "lg",
			timeUp : function(){
				fcom.ajax(fcom.makeUrl('TeacherScheduledLessons','startLessonAuthentication',[CometJsonFriendData.lessonId]),'',function(t){
					if(t != 0){
						$(".join_lesson_now").show();
						$("#lesson_actions").hide();
					}
				});
				$("#start_lesson_timer").hide();
			}
		});
		<?php } ?>
$('#end_lesson_timer').countdowntimer({
			startDate : "<?php echo $curDate; ?>",
			dateAndTime : "<?php echo $endTime; ?>",
			size : "lg",
			timeUp : function(){
				if(sessionStorage.getItem('cometChatUserExists')!=null)
				{
                        $('.jconfirm-closeIcon').trigger('click');
                        $.confirm({
                            closeIcon: true,
                            title: langLbl.Confirm,
                            content: '<?php echo Label::getLabel('LBL_Duration_assigned_to_this_lesson_is_completed_now_do_you_want_to_continue?'); ?>',
                            autoClose: langLbl.Quit+'|8000',
                            buttons: {
                                Proceed: {
                                    text: '<?php echo Label::getLabel('LBL_End_Lesson'); ?>',
                                    btnClass: 'btn btn--primary',
                                    keys: ['enter', 'shift'],
                                    action: function(){
                                        endLessonSetup('<?php echo $lessonData['slesson_id']; ?>');
                                    }
                                },
                                Quit: {
                                    text: '<?php echo Label::getLabel('LBL_Continue'); ?>',
                                    btnClass: 'btn btn--secondary',
                                    keys: ['enter', 'shift'],
                                    action: function(){
                                        sessionStorage.setItem('showEndLessonNotification',0);
                                    }
                                }
                            }
                        });
				}
				$("#end_lesson_time_div").hide();
			}
		});
	});

/*$( window ).load(function() {
$('#end_lesson_timer').countdowntimer({
			startDate : "<?php echo $curDate; ?>",
			dateAndTime : "<?php echo $endTime; ?>",
			size : "lg",
			timeUp : function(){
				if(sessionStorage.getItem('cometChatUserExists')!=null)
				{
                        $.confirm({
                            title: 'Confirm!',
                            content: '<?php echo Label::getLabel('LBL_Duration_assigned_to_this_lesson_is_completed_now_do_you_want_to_continue?'); ?>',
                            autoClose: 'Quit|8000',
                            buttons: {
                                Proceed: {
                                    text: '<?php echo Label::getLabel('LBL_End_Lesson'); ?>',
                                    btnClass: 'btn btn--primary',
                                    keys: ['enter', 'shift'],
                                    action: function(){
                                        endLessonSetup('<?php echo $lessonData['slesson_id']; ?>');
                                    }
                                },
                                Quit: {
                                    text: '<?php echo Label::getLabel('LBL_Continue'); ?>',
                                    btnClass: 'btn btn--secondary',
                                    keys: ['enter', 'shift'],
                                    action: function(){
                                        sessionStorage.setItem('showEndLessonNotification',0);
                                    }
                                }
                            }
                        });
				}
				$("#end_lesson_time_div").hide();
			}
		});
});    */


</script>

<section class="section section--grey section--page">
	<div class="screen">
      <div class="screen__left" style="background-image:url(<?php echo CONF_WEBROOT_URL ?>images/2000x900_1.jpg">
         <div class="screen__center-content">

            <?php if( $lessonData['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING ) { ?>
            <div class="alert alert--info" role="alert">
               <a class="close" href="javascript:void(0)"></a>
               <p><?php echo Label::getLabel('LBL_Note'); ?>:<?php echo Label::getLabel('LBL_This_lesson_is_Unscheduled._Encourage_your_student_to_schedule_it.'); ?> </p>
            </div>
            <span class="-gap"></span>
            <?php } ?>

            <?php if($lessonData['slesson_status'] == ScheduledLesson::STATUS_COMPLETED) { ?>
            <div class="alert alert--info" role="alert">
               <a class="close" href="javascript:void(0)"></a>
               <p><?php echo Label::getLabel('LBL_Note'); ?>:<?php echo Label::getLabel('LBL_This_lesson_is_completed._Encourage_your_learner_to_rate_it.'); ?> </p>
            </div>
            <span class="-gap"></span>
            <?php } ?>

			<?php /*if($lessonData['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED) { ?>
            <div class="alert alert--info" role="alert">
               <a class="close" href="javascript:void(0)"></a>
               <p><?php echo Label::getLabel('LBL_Note'); ?>:<?php echo Label::getLabel('LBL_This_lesson_is_completed._But_An_Issue_Is_Reported.'); ?> </p>
            </div>
            <span class="-gap"></span>
            <?php }*/ ?>

            <?php if($lessonData['slesson_status'] == ScheduledLesson::STATUS_CANCELLED) { ?>
            <div class="alert alert--info" role="alert">
               <a class="close" href="javascript:void(0)"></a>
               <p><?php echo Label::getLabel('LBL_Note'); ?>:<?php echo Label::getLabel('LBL_This_Lesson_has_been_cancelled._Schedule_more_lessons.'); ?> </p>
            </div>
            <span class="-gap"></span>
            <?php } ?>

            <a href="javascript:void(0);" style="display:none;" class="btn btn--secondary btn--xlarge join_lesson_now" id="joinL" onclick="createUserCometChat(CometJsonData,CometJsonFriendData);"><?php echo Label::getLabel('LBL_Join_Lesson'); ?></a>
                <?php if( $lessonData['slesson_status'] != ScheduledLesson::STATUS_SCHEDULED ) { ?>
                    <a href="<?php echo CommonHelper::generateUrl('teacher'); ?>" class="btn btn--secondary btn--large"><?php echo Label::getLabel('LBL_Go_to_Dashboard.'); ?></a>
                <?php } ?>
            <div class="timer">
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
               <li class="is-active"><a href="#tab1"><?php echo Label::getLabel('LBL_Info'); ?></a></li>
               <li><a href="#tab2" id="li_tab2"><?php echo Label::getLabel('LBL_Flashcards'); ?></a></li>
            </ul>
         </div>
         <div class="tab-data-container">
            <div id="tab1" class="tabs-content-js">
               <div class="col-list col-list--full -no-padding">
                  <div class="">

					 <div class="col-xl-12">

                        <h6><?php echo Label::getLabel('LBL_Learner_Details'); ?></h6>
						<div class="d-flex align-items-center">
						 <div>
							<div class="avtar avtar--small" data-text="<?php echo CommonHelper::getFirstChar($lessonData['learnerFname']); ?>">
                           <?php
                                echo $studentImageTag;
                              ?>
                        </div>
						 </div>
						 <div>
						 <h6><?php echo $lessonData['learnerFullName']; ?></h6>
                        <p><?php echo $lessonData['learnerCountryName']; ?> <br>
                           <?php
                              /* echo $lessonData['learnerTimeZone']."<br />";
                              echo CommonHelper::getDateOrTimeByTimeZone($lessonData['learnerTimeZone'],'H:i A P'); */ ?>
                        </p>
						 </div>


						 </div>
                     </div>


                     <hr>

                     <div class="col-xl-12">
                        <h6><?php echo Label::getLabel('LBL_Teacher_Details'); ?></h6>
						<div class="d-flex align-items-center">
						 <div>
						<div class="avtar avtar--small" data-text="<?php echo CommonHelper::getFirstChar($lessonData['teacherFname']); ?>">
                           <?php
							echo $teacherImageTag;
                              ?>
                        </div>
                        </div>
						<div>
                        <h6><?php echo $lessonData['teacherFullName']; ?></h6>
                        <p><?php echo $lessonData['teacherCountryName']; ?> <br>
                           <?php /* echo $lessonData['teacherTimeZone']."<br />";
                              echo CommonHelper::getDateOrTimeByTimeZone($lessonData['teacherTimeZone'],'H:i A P'); */ ?>
                        </p>
                     </div>
                     </div>
                     </div>

                     <hr>

                     <div class="col-xl-12">
                        <h6><?php echo Label::getLabel('LBL_Lesson_Details'); ?></h6>
                        <div class="schedule-list">
                           <ul>
                              <?php
                                $sdate = MyDate::convertTimeFromSystemToUserTimezone( 'Y-m-d', date($lessonData['slesson_date'] .' '. $lessonData['slesson_start_time']), true , $user_timezone );
                                $date = DateTime::createFromFormat('Y-m-d', $sdate);
								if ($date && ($date->format('Y-m-d') === $sdate )) {
								?>
								<li>
									<span class="span-left"><?php echo Label::getLabel('LBL_Schedule'); ?></span>
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
									<span class="span-left"><?php echo Label::getLabel('LBL_Status'); ?></span>
									<span class="span-right"><?php echo $statusArr[$lessonData['slesson_status']]; ?></span>
								</li>

                              <li>
                                 <span class="span-left"><?php echo Label::getLabel('LBL_Details'); ?></span>
                                 <span class="span-right">
                                 <?php //echo $lessonData['teacherTeachLanguageName'];
									echo TeachingLanguage::getLangById($lessonData['slesson_slanguage_id']);
								 ?><br>
                                 <?php
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
                        <h6><?php echo Label::getLabel('LBL_Issue_Status'); ?></h6>
                        <div class="schedule-list">
                           <ul>
                              <li>
                                 <span class="span-left"><?php echo Label::getLabel('LBL_Status'); ?></span>
                                 <span class="span-right"><?php echo IssuesReported::getStatusArr()[$lessonData['issrep_status']]; ?></span>
                              </li>
						   </ul>
						</div>
					 </div>
					<?php } ?>

                     <hr>

					  <div class="col-xl-12">
					   <div class="d-sm-flex align-items-center justify-content-between">
                           <div id="end_lesson_time_div" style="display:none;">
                            <div class="timer timer--small">
                                <span id="end_lesson_timer"></span>

                            </div>
                            </div>
                        <div class="select-box select-box--up toggle-group" id="lesson_actions">
							<div class="buttons-toggle">
								<a class="btn btn--large btn--secondary" href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?></a>
								<a href="javascript:void(0)" class="btn btn--large btn--secondary btn--dropdown toggle__trigger-js"></a>
							</div>
							<div class="select-box__target -skin toggle__target-js" style="display:none;" >
								<div class="listing listing--vertical">
									<ul>
										<?php /* if($lessonData['slesson_status'] == ScheduledLesson::STATUS_NEED_SCHEDULING) { ?>
										<!--li><a href="javascript:void(0);" onclick="viewBookingCalendar('<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Schedule'); ?></a></li>
										<li><a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lessonData['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Cancel'); ?></a></li-->
										<?php } */ ?>

										<?php if($lessonData['slesson_status'] == ScheduledLesson::STATUS_COMPLETED) { ?>
										<!--<li><a href="javascript:void(0);" onclick="issueReported('<?php echo $lessonData['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Issue_Reported'); ?></a></li> -->
										<?php } ?>

										<?php if($lessonData['slesson_status'] == ScheduledLesson::STATUS_SCHEDULED) { ?>
										<li><a href="javascript:void(0);" onclick="requestReschedule('<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Reschedule'); ?></a></li>
										<li><a href="javascript:void(0);" onclick="cancelLesson('<?php echo $lessonData['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Cancel'); ?></a></li>
										<?php } ?>

										<?php
										$countRel=ScheduledLessonSearch::countPlansRelation($lessonData['slesson_id']);
										if($countRel > 0){
										?>
										<li><a href="javascript:void(0);" onclick="viewAssignedLessonPlan('<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_View_Lesson_Plan'); ?></a></li>
										<li><a href="javascript:void(0);" onclick="changeLessonPlan('<?php echo $lessonData['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Change_Plan'); ?></a></li>
										<li><a href="javascript:void(0);" onclick="removeAssignedLessonPlan('<?php echo $lessonData['slesson_id']; ?>')" ><?php echo Label::getLabel('LBL_Remove_Plan'); ?></a></li>
										<?php }else{ ?>
										<li><a href="javascript:void(0);" onclick="listLessonPlans('<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Attach_Lesson_Plan'); ?></a></li>
										<?php } ?>
										<?php if ($lessonData['issrep_id'] > 0) { ?>
											<li>
												<a href="javascript:void(0);" onclick="issueReportedDetails('<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Issue_Details'); ?></a>
											</li>
										<?php } ?>

										<?php if($lessonData['slesson_status'] == ScheduledLesson::STATUS_ISSUE_REPORTED) { ?>
										<?php if( $lessonData['issrep_status'] == 0 ) { ?>
											<li>
												<a href="javascript:void(0);" onclick="resolveIssue('<?php echo $lessonData['issrep_id']; ?>', '<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></a>
											</li>
										<?php } ?>
										<?php if( $lessonData['issrep_status'] == 1 && $lessonData['issrep_issues_resolve_type'] < 1 ) { ?>
											<li>
												<a href="javascript:void(0);" onclick="issueResolveStepTwo('<?php echo $lessonData['issrep_id']; ?>', '<?php echo $lessonData['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></a>
											</li>
										<?php } ?>

										<?php }?>


									</ul>
								</div>
							</div>
						</div>
						 <div>
						 <a href="javascript:void(0);" style="display:none;" class="btn btn--primary btn--large btn--sticky end_lesson_now" id="endL" onclick="endLesson(<?php echo $lessonData['slesson_id']; ?>);"><?php echo Label::getLabel('LBL_End_Lesson'); ?></a>

					</div>
					</div>
					</div>
					<br><br><br>
				</div>
               </div>
            </div>

            <div id="tab2" class="tabs-content-js">
			   <div class="box" >
                  <div class="box-head">
                     <div class="page-head">
                        <div class="d-flex justify-content-between align-items-center">
                           <div>
                              <h5><?php echo Label::getLabel('LBL_Flashcards'); ?></h5>
                           </div>
                           <div>
                              <a class="btn btn--secondary btn--small" href="javascript:void(0)" onclick="flashCardForm(<?php echo $lessonData['slesson_id'] ?>, 0)"><?php echo Label::getLabel('LBL_Add_New'); ?></a>
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
										<svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="14.843" viewBox="0 0 14.844 14.843">
										<path d="M251.286,196.714a4.008,4.008,0,1,1,2.826-1.174A3.849,3.849,0,0,1,251.286,196.714Zm8.241,2.625-3.063-3.062a6.116,6.116,0,0,0,1.107-3.563,6.184,6.184,0,0,0-.5-2.442,6.152,6.152,0,0,0-3.348-3.348,6.271,6.271,0,0,0-4.884,0,6.152,6.152,0,0,0-3.348,3.348,6.259,6.259,0,0,0,0,4.884,6.152,6.152,0,0,0,3.348,3.348,6.274,6.274,0,0,0,6-.611l3.063,3.053a1.058,1.058,0,0,0,.8.34,1.143,1.143,0,0,0,.813-1.947h0Z" transform="translate(-245 -186.438)"></path>
										</svg>
									</span>
								</span>
							</div>
                        </form>
                     </div>

					<div class="box-body" id="flashCardListing"></div>

                  </div>
			  </div>
			</div>

			</div>

            </div>
         </div>
      </div>
   </div>
</section>

<script type="text/javascript">
jQuery(document).ready(function (e) {
	if( sessionStorage.getItem('cometChatUserExists') == chat_group_id ){
	   joinLessonButtonAction();
	   createChatBox();
	}

	/*function t(t) {
		e(t).bind("click", function (t) {
			t.preventDefault();
			e(this).parent().fadeOut()
		})
	}

	e(".toggle__trigger-js").click(function () {
		var t = e(this).parents(".toggle-group").children(".toggle__target-js").is(":hidden");
		e(".toggle-group .toggle__target-js").hide();
		e(".toggle-group .toggle__trigger-js").removeClass("is-active");
		if (t) {
			e(this).parents(".toggle-group").children(".toggle__target-js").toggle().parents(".toggle-group").children(".toggle__trigger-js").addClass("is-active")
		}
	});

	e(document).bind("click", function (t) {
		var n = e(t.target);
		if (!n.parents().hasClass("toggle-group")) e(".toggle-group .toggle__target-js").hide();
	});

	e(document).bind("click", function (t) {
		var n = e(t.target);
		if (!n.parents().hasClass("toggle-group")) e(".toggle-group .toggle__trigger-js").removeClass("is-active");
	});*/

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
