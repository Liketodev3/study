<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="message-details message-details-js">
                                    <div class="message-body">
                                        <div class="message-head">
                                        <div class="row">
                                            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10">
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
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                <a href="javascript:void(0)" onclick = 'closethread()' class="-link-close msg-close-js"></a>
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
    $(document).ready(function(){
        $('textarea[name=message_text]').keydown(function(event) {
            // enter has keyCode = 13, change it if you want to use another button
            if (event.keyCode == 13 && !event.shiftKey) {
                $('#frm_fat_id_frmSendMessage').submit();
            }
        });        
    })

</script>                                