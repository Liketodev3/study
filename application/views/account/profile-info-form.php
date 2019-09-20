<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$profileFrm->setFormTagAttribute('id', 'profileInfoFrm');
$profileFrm->setFormTagAttribute('class','form');
$profileFrm->setFormTagAttribute('onsubmit', 'setUpProfileInfo(this); return(false);');

$user_url_name = $profileFrm->getField('user_url_name');
$user_url_name->developerTags['col'] = 12;
$user_url_name->htmlAfterField = '<p class="user_url_string">'. CommonHelper::generateFullUrl('teachers','') .'/<span class="user_url_name_span">'. $user_url_name->value .'</span></p>';

$profileFrm->developerTags['colClassPrefix'] = 'col-md-';
$profileFrm->developerTags['fld_default_col'] = 6;

$personal_information = $profileFrm->getField('personal_information');
$personal_information->value = "<h5>" . Label::getLabel('LBL_Personal_Information') . "</h5>";
$personal_information->developerTags['col'] = 12;

$user_profile_info = $profileFrm->getField('user_profile_info');
$user_profile_info->developerTags['col'] = 12;

$user_gender = $profileFrm->getField('user_gender');
$user_gender->setOptionListTagAttribute( 'class', 'list-inline list-inline--onehalf' );

$profileImgFrm->setFormTagAttribute('action', CommonHelper::generateUrl('Account','setUpProfileImage'));
?>
	 <div class="section-head">
		 <div class="d-flex justify-content-between align-items-center">
			 <div><h4 class="page-heading"><?php echo Label::getLabel('LBL_General'); ?></h4></div>
		 </div>
	 </div>
                                     <div id="langForm">
     <div class="tabs-small tabs-offset tabs-scroll-js">
                                    <ul>
                                        <li class="is-active"><a href="javascript::void(0)" onclick="profileInfoForm()" ><?php echo Label::getLabel('LBL_General'); ?></a></li>
                                        			<?php foreach( $languages as $langId => $language ){ ?>
                                                        <li><a href="javascript::void(0)" onclick="getLangProfileInfoForm(<?php echo $langId; ?>)" ><?php echo $language['language_name']; ?></a></li>
                                                    <?php } ?>
                                    </ul>
                                </div>
<div class="row">
	<div class="col-lg-4 -align-center order-md-2">
		<div class="preview preview--profile">
			<h5><?php echo Label::getLabel('LBL_Change_Avataar'); ?></h5>
			
			<div class="avtar avtar--large avtar--centered" data-text="<?php echo CommonHelper::getFirstChar($userRow['user_first_name']); ?>">
				<?php 
				if( true == User::isProfilePicUploaded() ){
					echo '<img src="'.CommonHelper::generateUrl('Image','user', array( $userRow['user_id'] )).'?'.time().'" />';
				}
				?>
			</div>
			
			<span class="-gap"></span>
			<div class="btngroup--fix">
				<?php echo $profileImgFrm->getFormTag();	?>
				<span class="btn btn--primary btn--sm btn--fileupload">	
					<?php 
					echo $profileImgFrm->getFieldHtml('user_profile_image'); 
					echo ( true == $isProfilePicUploaded ) ? Label::getLabel('LBL_Change'): Label::getLabel('LBL_Upload'); 
					?>
				</span>
				<?php 
				echo $profileImgFrm->getFieldHtml('update_profile_img'); 
				echo $profileImgFrm->getFieldHtml('rotate_left'); 
				echo $profileImgFrm->getFieldHtml('rotate_right'); 
				echo $profileImgFrm->getFieldHtml('remove_profile_img'); 
				echo $profileImgFrm->getFieldHtml('action'); 
				echo $profileImgFrm->getFieldHtml('img_data'); 				
				?>						
				</form>
				<?php echo $profileImgFrm->getExternalJS();?>
				 <?php if( true == $isProfilePicUploaded ){ ?>
					<a class="btn btn--secondary btn--sm" href="javascript:void(0)" onClick="removeProfileImage()"><?php echo Label::getLabel('LBL_Remove');?></a>
				 <?php } ?>
				<div id="dispMessage"></div> 
			</div>
		</div>
	</div>
	<div class="col-lg-8">
	 <?php echo $profileFrm->getFormHtml();?></div>
	</div>
</div>
<script language="javascript">
	/* $(document).ready(function(){
		getCountryStates($( "#user_country_id" ).val(),<?php echo $stateId ;?>,'#user_state_id');
	}); */
	
	$(document).ready(function(){
		$('input[name="user_url_name"]').on('keyup', function(){
			var user_name = $(this).val();
			$('.user_url_name_span').html(user_name);
		})
	})
</script>