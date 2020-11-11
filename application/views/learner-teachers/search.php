<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20">
	<?php if( !empty( $teachers ) ){ ?>
	<div class="table-scroll">
	 <table class="table myTeacherTable">
		 <tbody><tr class="-hide-mobile">
			
			<th><?php echo Label::getLabel('LBL_Teacher'); ?></th>
			<th><?php echo Label::getLabel('LBL_Average_Rating'); ?></th>
			<th><?php echo Label::getLabel('LBL_Lock_(Single/Bulk_Price)'); ?></th>
			<th><?php echo Label::getLabel('LBL_Scheduled'); ?></th>
			<th><?php echo Label::getLabel('LBL_Past'); ?></th>
			<th><?php echo Label::getLabel('LBL_Unscheduled'); ?></th>
			<th><?php echo Label::getLabel('LBL_Actions'); ?></th>
		</tr>
		<?php foreach( $teachers as $teacher ){
			
				$teacherDetailPageUrl = CommonHelper::generateUrl('Learner', 'my-teacher', array($teacher['user_url_name']) ); ?>
		<tr>
			<td width="25%">
				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Teacher');?></span>
				<span class="td__data">
					<div class="profile-info align-items-center">
						<a title = "<?php echo $teacher['teacherFullName']; ?>" href="<?php echo $teacherDetailPageUrl; ?>">
							<div class="avtar avtar--small" data-text="<?php echo CommonHelper::getFirstChar($teacher['teacherFname']); ?>">
								<?php 
									if( true == User::isProfilePicUploaded( $teacher['teacherId'] ) ){
										$img = CommonHelper::generateUrl('Image','user', array( $teacher['teacherId'], 'normal', 1 )).'?'.time(); 
										echo '<img src="'.$img.'" />';
									}
								?>
							</div>
						</a>

						<div class="profile-info__right">
							<h6><a href="<?php echo $teacherDetailPageUrl; ?>"><?php echo $teacher['teacherFname']; ?></a></h6>
							<p><?php echo $teacher['teacherCountryName']; ?></p>
							
							<div class="my_teacher_langauges">
							
							<?php 
							if( $teacher['teacherTeachLanguageName'] !='' && !empty( $teacher['teacherTeachLanguageName'] ) ) { 
								$teachLangs = explode(',',$teacher['teacherTeachLanguageName']);
							
								if( count ( $teachLangs ) > 1 ) {
									$first_array = array_slice($teachLangs,0, 1);
									$second_array = array_slice($teachLangs,1, count( $teachLangs ));
							?>
							<div class="language">
							<p class="my_teacher_lang_heading"><span><?php echo Label::getLabel('LBL_Teaches:'); ?></span> </p>
							<?php  foreach( $first_array as $teachLang) {  ?>
									<span class="main-language"><?php echo $teachLang; ?></span>
								<?php } ?>
								<ul>
									<li><span class="plus">+</span>			   
											<div class="more_listing">				
												<ul>
												<?php  foreach( $second_array as $teachLang) {  ?>
													<li><a><?php echo $teachLang; ?></a></li>	
												<?php } ?>
												</ul>
											</div>
									</li>
								</ul>
							</div> 
						<?php 
		
							} else {
								echo ' <p><span>'. Label::getLabel('LBL_Teaches:') . '</span> '. $teacher['teacherTeachLanguageName'] .' </p>';  
							}
						}
						?>
							</div>
						</div>
					</div>
				</span>
			</td>
			<td>
   				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Average_Rating');?></span>            
				<span class="td__data"><?php echo $teacher['teacher_rating']; ?></span>                
            </td>

			<td>
				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Lock_(Single/Bulk_Price)');?></span>				
				
				<span class="td__data">
				<span class="-display-inline"><?php echo CommonHelper::displayMoneyFormat($teacher['singleLessonAmount']); ?> / <?php echo CommonHelper::displayMoneyFormat($teacher['bulkLessonAmount']); ?></span>
				<?php if( $teacher['isSetUpOfferPrice'] ){ ?>
					<span class="inline-icon -display-inline -color-fill">
						<span class="svg-icon" title="<?php echo Label::getLabel('LBL_These_prices_are_locked');?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 520 520">
							<path d="M265,9A130.148,130.148,0,0,0,135,139v92h30V139a100,100,0,0,1,200,0v92h30V139A130.147,130.147,0,0,0,265,9ZM85,231V521H445V231H85ZM280,384.42V446H250V384.42A45,45,0,1,1,280,384.42ZM265,327a15,15,0,1,0,15,15A15.017,15.017,0,0,0,265,327Z" transform="translate(-5 -5)"></path>
						</svg>
						</span>
					</span>
				<?php } else { ?>
					<span class="inline-icon -display-inline">
						<span class="svg-icon" title="<?php echo Label::getLabel('LBL_These_prices_are_Unlocked'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 520 520">
							  <path d="M85,521V231H365V139A99.972,99.972,0,0,0,182.193,83h-34.5A129.991,129.991,0,0,1,395,139v92h50V521H85ZM265,297a45,45,0,0,0-15,87.42V446h30V384.42A45,45,0,0,0,265,297Zm0,30a15,15,0,1,0,15,15A15.017,15.017,0,0,0,265,327Z" transform="translate(-5 -5)"></path>
							</svg>
						</span>
					</span>
				<?php } ?>
				</span>
			</td>
			
			<td>
				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Scheduled');?></span>
				<span class="td__data"><?php echo $teacher['scheduledLessonCount']; ?></span>
			</td>
			
			<td>
				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Past');?></span>
				<span class="td__data"><?php echo $teacher['pastLessonCount']; ?></span>
			</td>
			
			<td>
				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Unscheduled');?></span>
				<span class="td__data"><?php echo $teacher['unScheduledLessonCount']; ?></span>
			</td>
			<td>
				<span class="td__caption -hide-desktop -show-mobile"><?php echo Label::getLabel('LBL_Actions');?></span>
				<span class="td__data">
						<a href="javascript:void(0)" class="btn btn--small btn--secondary" onClick="cart.add( '<?php echo $teacher['teacherId']; ?>', '<?php echo $lessonPackages[0]['lpackage_id'] ?>','','', <?php echo $teacher['languageID'];?> )"> <?php echo Label::getLabel('LBL_Buy_Now');?>
                        </a>
					<?php /*<a href="javascript:void(0)" onClick="generateThread(<?php echo $teacher['teacherId']; ?>)" class="btn btn--small btn--secondary"><?php echo Label::getLabel('LBL_Message');?></a>*/ ?>
					
					<?php /*<a href="javascript:void(0);" onclick="removeTeacherConfirmation('<?php echo $teacher['teacherId']; ?>');" class="btn btn--small"><?php echo Label::getLabel('LBL_Remove');?></a>*/ ?>
				</span>
			</td>
		</tr>
		<?php } ?>
		</tbody>
	</table>
	 </div>
	 
	<?php
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
	'name' => 'frmLearnerTeachersSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	} else { 

		$this->includeTemplate('_partial/no-record-found.php');	

	} ?>
</div>