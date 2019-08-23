<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section">
	<div class="sectionhead">
		<h4><?php echo Label::getLabel('LBL_Teacher_Request_Detail', $adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">
	<div class="add border-box border-box--space">
		<div class="repeatedrow">
			<form class="web_form form_horizontal">
				<div class="row">
					<div class="col-md-12">
						<h3><i class="ion-person icon"></i> <?php echo Label::getLabel('LBL_Request_Information',$adminLangId); ?></h3>
					</div>
				</div>
				<div class="rowbody">
					<div class="listview">
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Reference_Number',$adminLangId); ?></dt>
							<dd><?php echo $row['utrequest_reference'];?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Requested_On',$adminLangId); ?></dt>
							<dd><?php echo FatDate::format( $row['utrequest_date'], true );?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Status',$adminLangId); ?></dt>
							<dd><?php echo TeacherRequest::getStatusArr($adminLangId)[$row['utrequest_status']];?></dd>
						</dl>	
						<?php if($row['utrequest_comments']!=''){?>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Comments/Reason',$adminLangId); ?></dt>
							<dd><?php echo nl2br($row['utrequest_comments']);?></dd>
						</dl>			
						<?php }?>						
					</div>
				</div>           
			</form>  
		</div>
		<div class="repeatedrow">
			<form class="web_form form_horizontal">
				<div class="row">
					<div class="col-md-12">
						<h3><i class="ion-person icon"></i> <?php echo Label::getLabel('LBL_Profile_Information',$adminLangId); ?></h3>
					</div>
				</div>
				<div class="rowbody">
					<div class="listview">
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Profile_Picture',$adminLangId); ?></dt>
							<dd><img src="<?php echo CommonHelper::generateUrl('TeacherRequests','viewProfilePic', array($row['utrequest_user_id']) ); ?>" /></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Photo_Id',$adminLangId); ?></dt>
							<dd><?php if( $photo_id_row['afile_physical_path'] != "" ){
								//echo '<a href="javascript:alert(\'Pending\')">' . $photo_id_row['afile_name']. '</a>';
                                echo '<a target="_blank" href="'.CommonHelper::generateFullUrl('TeacherRequests','photoIdFile',array($photo_id_row['afile_record_id'])).'" download>'.$photo_id_row['afile_name'].'</a>';
							} else { echo "-"; } ?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_First_Name',$adminLangId); ?></dt>
							<dd><?php echo $row['utrvalue_user_first_name'];?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Last_Name',$adminLangId); ?></dt>
							<dd><?php echo $row['utrvalue_user_last_name'];?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Gender',$adminLangId); ?></dt>
							<dd><?php echo User::getGenderArr($adminLangId)[$row['utrvalue_user_gender']];?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Phone_Number',$adminLangId); ?></dt>
							<dd><?php echo $row['utrvalue_user_phone'];?></dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_You_Tube_Video_Link',$adminLangId); ?></dt>
							<dd><?php echo $row['utrvalue_user_video_link'];?> &nbsp;</dd>
						</dl>
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Profile_info',$adminLangId); ?></dt>
							<dd><?php echo $row['utrvalue_user_profile_info'];?> &nbsp; </dd>
						</dl>
						<!--<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Teaching_Language',$adminLangId); ?></dt>
							<dd><?php echo $spokenLanguagesArr[$row['utrvalue_user_teach_slanguage_id']];?></dd>
						</dl> -->
						
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Teaching_Language',$adminLangId); ?></dt>
							<dd>
								<?php 
								//print_r( $row['utrvalue_user_teach_slanguage_id'] );
								if( isset($row['utrvalue_user_teach_slanguage_id']) && !empty($row['utrvalue_user_teach_slanguage_id']) && is_array( $row['utrvalue_user_teach_slanguage_id'] )) {
									foreach($row['utrvalue_user_teach_slanguage_id'] as $key=>$val ){ 
										echo $TeachingLanguagesArr[$val] .', ' ;
									}
								} else{
									 echo $TeachingLanguagesArr[$row['utrvalue_user_teach_slanguage_id']] .', ' ;
								} ?> &nbsp;
							</dd>
						</dl>
						
						<dl class="list">
							<dt><?php echo Label::getLabel('LBL_Spoken_Language',$adminLangId); ?></dt>
							<dd>
								<?php foreach($row['utrvalue_user_language_speak'] as $key=>$val ){ ?>
								<?php echo $spokenLanguagesArr[$val].' : '.$spokenLanguageProfArr[$row['utrvalue_user_language_speak_proficiency'][$key]].'<br/>' ; ?>
								<?php } ?>
							</dd>
						</dl>
						
					</div>
				</div>           
			</form>  
		</div>
		</div>		
	</div>
</section>