<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
	<?php if (!empty($arr_listing) && is_array($arr_listing) ){ ?>
	<?php foreach ($arr_listing as $sn => $row){ 
		if($row['message_from_user_id'] == UserAuthentication::getLoggedUserId()){
			$imgUserId = $row['message_to_user_id'];
			$imgUserName = $row['message_to_name'];
		}else{
			$imgUserId = $row['message_from_user_id'];
			$imgUserName = $row['message_from_name'];	
		}		
		$liClass = 'is-read';
		if($row['message_is_unread'] == Thread::MESSAGE_IS_UNREAD && $row['message_to'] == $loggedUserId) {
			$liClass = '';
		}		
	?>	
<div class="msg-list <?php echo $liClass; echo ($isActive == $row['thread_id'])?' is-active':''; ?>">
                                           <div class="msg-list__left">
                                               <div class="avtar avtar--small avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($imgUserName); ?>">
<?php													if( true == User::isProfilePicUploaded($imgUserId) ){
													echo '<img src="'.CommonHelper::generateUrl('Image','user', array( $imgUserId )).'?'.time().'" />';
													}
?>
                                                </div>
                                           </div>
                                           <div class="msg-list__right">
                                               <h6><?php echo $imgUserName;?></h6>
                                               <p><?php echo CommonHelper::truncateCharacters($row['message_text'],280);?></p>
                                               <small class="-color-light -style-uppercase"><?php echo FatDate::format($row['message_date']); ?></small>
                                           </div>
                                           <!--span class="msg-count">2</span-->
                                           <a href="javascript:void(0)" onclick= "getThread(<?php echo $row['thread_id']; ?>)" class="msg-list__action"></a>
                                       </div>
	<?php } ?>
	<?php } ?>