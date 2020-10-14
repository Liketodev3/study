<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="message-details message-details-js">
                                    <div class="message-body">
                                        <div class="message-head">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="">
                                                <div class="msg-list align-items-center">
                                                   <div class="msg-list__left">
                                                       <div class="avtar avtar--small avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($otherUserDetail['user_first_name']); ?>">
                                                           <?php
															if( true == User::isProfilePicUploaded($otherUserDetail['user_id']) ){
																echo '<img src="'.CommonHelper::generateUrl('Image','user', array( $otherUserDetail['user_id'] )).'?'.time().'" />';
															}
														?>
                                                        </div>
                                                   </div>
                                                   <div class="msg-list__right">
                                                       <h6><?php echo $otherUserDetail['user_first_name'].' '.$otherUserDetail['user_last_name']; ?></h6>
                                                   </div>
                                               </div>
                                            </div>

                                            <div class="">
                                                <?php if($otherUserDetail['user_is_teacher'] == applicationConstants::YES){
                                                        $teacherUrl = CommonHelper::generateUrl('Teachers', 'profile').'/'. $otherUserDetail['user_url_name'];
                                                ?>
                                                    <a href='<?php echo $teacherUrl; ?>' class="btn btn--small btn--secondary view-teacher-link"><?php echo Label::getLabel('LBL_View_Teacher') ?></a>
                                                <?php } ?>
                                                <a href="javascript:void(0)" onclick = 'closethread()' class="-link-close msg-close-js -top"></a>
                                            </div>
                                        </div>
                                    </div>

                                        <div class="message-container">
                                     <div class="scrollbar scrollbar-js">
<?php if ($arrListing){
	foreach($arrListing as $row){ ?>
                                          <div class="message-row <?php echo ($row['message_from_user_id']==$userId)?"my-message":"" ?>">
                                                <aside class="grid_1">
                                                    <div class="avtar avtar--small" data-text="<?php echo CommonHelper::getFirstChar($row['message_from_name']); ?>">
														<?php
															if( true == User::isProfilePicUploaded($row['message_from_user_id']) ){
																echo '<img src="'.CommonHelper::generateUrl('Image','user', array( $row['message_from_user_id'] )).'?'.time().'" />';
															}
														?>
													</div>
                                                </aside>
                                                <aside class="grid_2">
                                                    <div class="secionreviews">
                                                        <p class="reviewtxt"><?php echo nl2br($row['message_text']);?></p>
                                                    </div>
                                                    <span class="datetext"><?php echo FatDate::format($row['message_date'],true);?></span>
                                                </aside>
                                            </div>
<?php } }?>

                                    </div>
                                </div>

                                    <div class="message-reply">
<?php
												$frm->setFormTagAttribute('onSubmit','sendMessage(this); return false;');
												$frm->setFormTagAttribute('class', 'form');
												$frm->developerTags['colClassPrefix'] = 'col-md-';
												$frm->developerTags['fld_default_col'] = 12;
												$messageBox = $frm->getField('message_text');
												$messageBox->htmlAfterField = '<p class="messageCheckbox"><label class="field_label"><input type="checkbox" onChange="updatesessionStorage(this);"  name="is_enter" class="is_enter" value="false" > &nbsp; '. Label::getLabel('LBL_Send_Message_on_Enter_Press') .'</label></p>';

												echo $frm->getFormHtml(); ?>
                                    <!--form class="form">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label"></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><textarea placeholder="Type a message here"></textarea></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label"></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input value="Submit" class="-float-right" type="submit"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form-->
                                </div>
                                     </div>
                                </div>

<script>
    if($(window).width()>1199){
    $('.scrollbar-js').enscroll({
        verticalTrackClass: 'scrollbar-track',
        verticalHandleClass: 'scrollbar-handle'
    });
    }
    function updatesessionStorage(obj) {
        checked = false;
        if($(obj).is(":checked")) {
            checked = true;
        }
        localStorage.setItem('is_enter', checked);
    }
    $(document).ready(function(){
		if( localStorage.getItem('is_enter') == true || localStorage.getItem('is_enter') == "true") {
			$('input[name=is_enter]').prop('checked', true);
		} else {
			$('input[name=is_enter]').prop('checked', false);
		}

	    $('textarea[name=message_text]').keydown(function(event) {
                is_enter =  localStorage.getItem('is_enter');
			if (event.keyCode == 13 && !event.shiftKey && (is_enter== "true" || is_enter == true)) {
				$('#frm_fat_id_frmSendMessage').submit();
            }
		});
    })

</script>
