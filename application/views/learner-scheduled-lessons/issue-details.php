<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$lastIssue = end($issueDeatils);
?> 
<div class="box">
	<div class="box__head">
		<h6><?php echo Label::getLabel('LBL_Reported_Issue_By_Learner'); ?></h6>
	</div>
	<div class="box__body -padding-20">
		<div class="content-repeated-container">
		<?php if (!empty($issueDeatils)) {
		foreach ($issueDeatils as $_issue) {
			$user_timezone = MyDate::getUserTimeZone();
			$issue_date = MyDate::convertTimeFromSystemToUserTimezone( 'F d, Y H:i A', date($_issue['issrep_added_on']), true , $user_timezone );
		?>
			<div class="content-repeated">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-sm-4">
						<p class="-small-title"><strong><?php echo $issue_date; ?></strong>
						</p>
					</div>
					<div class="col-xl-8 col-lg-8 col-sm-8">
						<p><strong><?php
						if ( $_issue['issrep_issues_to_report'] != '' ) {
							$_issues = explode( ',' , $_issue['issrep_issues_to_report'] );
							foreach ( $_issues as $issue ) {
								echo $issues_options[$issue].' <br />';
							}
							
						}
						?> </strong> 
						<?php echo nl2br($_issue['issrep_comment']); ?></p>
					</div>
				</div>
			</div>
		<?php 
		}
		} ?>	
		</div>
	</div>
</div>
<div class="box">
	<div class="box__head">
		<h6><?php echo Label::getLabel('LBL_Reported_Issue_Updates_By_Teacher'); ?></h6>
	</div>
	<div class="box__body  -padding-20">
		<div class="content-repeated-container">
		<?php if (!empty($issueDeatils)) { 
		foreach ($issueDeatils as $_issue) {
		if( $_issue['issrep_updated_on'] != '0000-00-00 00:00:00' ) {
			$user_timezone = MyDate::getUserTimeZone();
			$issue_date = MyDate::convertTimeFromSystemToUserTimezone( 'F d, Y H:i A', date($_issue['issrep_updated_on']), true , $user_timezone );
		?>
			<div class="content-repeated">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-sm-4">
						<p class="-small-title"><strong><?php echo $issue_date; ?></strong>
						</p>
					</div>
					<div class="col-xl-8 col-lg-8 col-sm-8">
						<p><strong><?php
						if ($_issue['issrep_issues_resolve'] != '') {
							$_issues = explode( ',' , $_issue['issrep_issues_resolve'] );
							foreach ($_issues as $issue) {
								echo $issues_options[$issue].' <br />';
							}
						}
						?> </strong> 
						<?php echo '<strong>'. Label::getLabel('LBL_Comment_:') .'</strong> '. nl2br($_issue['issrep_resolve_comments']); 
						$resolved_by = $_issue['issrep_issues_resolve_type'];
						if ($resolved_by > 0  && isset($resolve_type_options[$resolved_by])) { 
							echo '<br /><strong>'. Label::getLabel('LBL_Resolved_by_:') .'</strong> '. $resolve_type_options[$resolved_by]; 
						} ?>
						</p>
					</div>
				</div>
			</div>
		<?php 
		}
		}
		}
		?>	
		</div>
	</div>
<?php
$_time_valid_for_support = date('Y-m-d H:i:s');
if( $lastIssue['issrep_updated_on'] != '0000-00-00 00:00:00' ) {
	$resolve_datetime = $lastIssue['issrep_updated_on'];
	$_time_valid_for_support = date('Y-m-d H:i:s', strtotime('+ 48 Hours', strtotime($resolve_datetime)));
}

if ( strtotime($_time_valid_for_support )  > strtotime(date('Y-m-d H:i:s')) && $lastIssue['issrep_is_for_admin'] < 1 ) {
?>
	<div class="-padding-20 -no-padding-top">
		<p>
			<span class="-display-inline"><?php echo Label::getLabel('LBL_Not_Happy_with_teacher_solution?'); ?> &nbsp; </span><a href="javascript:void(0);" class="-link-underline -color-secondary" onclick="reportIssueToAdmin('<?php echo $lastIssue['issrep_id'];?>', '<?php echo $lastIssue['issrep_slesson_id']; ?>', '<?php echo USER::USER_TYPE_LEANER?>');"><?php echo Label::getLabel('LBL_Report_Issue_to_Support_Team'); ?></a>
		</p>
	</div>
<?php } ?>
</div>