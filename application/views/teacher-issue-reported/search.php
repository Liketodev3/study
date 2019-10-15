<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if( !empty( $issuesReported ) ) { 
$user_timezone = MyDate::getUserTimeZone();
?>
    <div class="col-list-group">
		<div class="col-list-container">
		<?php 
		foreach( $issuesReported as $_issue ){ 
		?>
	<div class="col-list">   
		<div class="d-lg-flex align-items-center">
			<div class="col-xl-4 col-lg-4 col-md-12">
				<div class="avtar avtar--normal" data-text="<?php echo CommonHelper::getFirstChar($_issue['user_first_name']); ?>">
					<?php 
					if( true == User::isProfilePicUploaded( $_issue['slesson_learner_id'] ) ){
						$img = CommonHelper::generateUrl('Image','user', array( $_issue['slesson_learner_id'] )).'?'.time(); 
						echo '<img src="'.$img.'" />'; 
					} ?>
				</div>
				<h6><?php echo $_issue['user_first_name']; ?></h6>
			</div>
			
			<div class="col-xl-6 col-lg-6 col-md-12">
				<div class="schedule-list">
					<ul>
						<?php 
						$date = DateTime::createFromFormat('Y-m-d', $_issue['slesson_date']);
						if($date && ($date->format('Y-m-d') === $_issue['slesson_date'])){ ?>
							<li>
								<span class="span-left"><?php echo Label::getLabel('LBL_Schedule'); ?></span>
								<span class="span-right">
									<h4>
									<?php 
										echo MyDate::convertTimeFromSystemToUserTimezone( 'h:i A', $_issue['slesson_start_time'], true , $user_timezone );
									?>  
									</h4>
									<?php 
										echo MyDate::convertTimeFromSystemToUserTimezone( 'l, F d, Y', $_issue['slesson_date'].' '. $_issue['slesson_start_time'], true , $user_timezone );
									?>
								</span>
							</li>
						<?php } ?>
						<li>
							<span class="span-left"><?php echo Label::getLabel('LBL_Status'); ?></span>
							<span class="span-right"><?php echo $statusArr[$_issue['slesson_status']]; ?></span>
						</li>
                       	<li>
                            <span class="span-left"><?php echo Label::getLabel('LBL_Issue_Status'); ?></span>
                            <span class="span-right"><?php echo IssuesReported::getStatusArr()[$_issue['issrep_status']]; ?></span>
                        </li>                        
                    </ul>
				</div>
			</div>
			
			<div class="col-xl-2 col-lg-2 col-md-4 col-positioned">
				<div class="select-box toggle-group">
					<div class="buttons-toggle">
						<a href="javascript:void(0);" onclick="issueReportedDetails('<?php echo $_issue['slesson_id']; ?>')" class="btn btn--secondary"><?php echo Label::getLabel('LBL_View'); ?></a>
						<?php if( $_issue['issrep_status'] == 0 || ( $_issue['issrep_status'] == 1 && $_issue['issrep_issues_resolve_type'] < 1 )) { ?>
							<a href="javascript:void(0)" class="btn btn--secondary btn--dropdown toggle__trigger-js"></a>
						<?php } ?>	
					</div>

					<div class="select-box__target -skin toggle__target-js" style="display: none;">
						<div class="listing listing--vertical">
							<ul>
							<?php if( $_issue['issrep_status'] == 0 ) { ?>
								<li>
									<a href="javascript:void(0);" onclick="resolveIssue('<?php echo $_issue['issrep_id']; ?>', '<?php echo $_issue['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></a>
								</li>
							<?php } ?>	
							<?php if( $_issue['issrep_status'] == 1 && $_issue['issrep_issues_resolve_type'] < 1 ) { ?>
								<li>
									<a href="javascript:void(0);" onclick="issueResolveStepTwo('<?php echo $_issue['issrep_id']; ?>', '<?php echo $_issue['slesson_id']; ?>')"><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></a>
								</li>
							<?php } ?>	
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
        </div>    
    </div>
	
	<?php
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
	'name' => 'frmTeacherStudentsSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	} else { 
		$this->includeTemplate('_partial/no-record-found.php');
	} ?>