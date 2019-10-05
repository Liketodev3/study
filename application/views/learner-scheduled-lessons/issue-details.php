<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?> 
<div class="box -padding-20">
	<h6><?php echo Label::getLabel('LBL_Reported_Issue_By_Learner'); ?></h6>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td><?php echo Label::getLabel('LBL_Reported_Issue_Reason'); ?></td>
					<td>
					<?php
					if ( $issueDeatils['issrep_issues_to_report'] != '' ) {
						$_issues = explode( ',' , $issueDeatils['issrep_issues_to_report'] );
						echo'<ul>';
						foreach ( $_issues as $_issue ) {
							echo '<li>'. $issues_options[$_issue] .'</li>';
						}
						echo'</ul>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td><?php echo Label::getLabel('LBL_Reported_Issue_Comment'); ?></td>
					<td>
						<?php echo nl2br($issueDeatils['issrep_comment']); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="box -padding-20">
	<h6><?php echo Label::getLabel('LBL_Reported_Issue_Updates_By_Teacher'); ?></h6>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td><?php echo Label::getLabel('LBL_Reported_Issue_Reason'); ?></td>
					<td>
					<?php
					if ( $issueDeatils['issrep_issues_resolve'] != '' ) {
						$_issues = explode( ',' , $issueDeatils['issrep_issues_resolve'] );
						echo'<ul>';
						foreach ( $_issues as $_issue ) {
							echo '<li>'. $issues_options[$_issue] .'</li>';
						}
						echo'</ul>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td><?php echo Label::getLabel('LBL_Reported_Issue_Resolve_Comment'); ?></td>
					<td>
						<?php echo nl2br($issueDeatils['issrep_resolve_comments']); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo Label::getLabel('LBL_Reported_Issue_Resolve_Type'); ?></td> 
					<td>
						<?php if( $issueDeatils['issrep_issues_resolve_type'] > 0 ) : echo $resolve_type_options[$issueDeatils['issrep_issues_resolve_type']]; endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
<?php
$_time_valid_for_support = date('Y-m-d H:i:s');
if( $issueDeatils['issrep_updated_on'] != '0000-00-00 00:00:00' ) {
	$resolve_datetime = $issueDeatils['issrep_updated_on'];
	$_time_valid_for_support = date('Y-m-d H:i:s', strtotime('+ 48 Hours', strtotime($resolve_datetime)));
}

if ( strtotime($_time_valid_for_support )  > strtotime(date('Y-m-d H:i:s')) && $issueDeatils['issrep_is_for_admin'] < 1 ) {
?><br />
	<p class="text-right">
		<span class="-display-inline"><?php echo Label::getLabel('LBL_Not_Happy_with_teacher_solution?'); ?> &nbsp; </span><a href="javascript:void(0);" class="btn btn--secondary -display-inline" onclick="return confirm('<?php echo Label::getLabel('LBL_Are_you_sure_want_to_report_the_issue_To_support_team'); ?>')?reportIssueToAdmin('<?php echo $issueDeatils['issrep_id'];?>'):'';"><?php echo Label::getLabel('LBL_Report_Issue_to_Support_Team'); ?></a>
	</p>
<?php } ?>
	
</div>