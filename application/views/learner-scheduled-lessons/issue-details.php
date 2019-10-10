<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?> 
<div class="box">
	<div class="box__head">
		<h6><?php echo Label::getLabel('LBL_Reported_Issue_By_Learner'); ?></h6>
	</div>
	<div class="box__body -padding-20">
		
	
		<div class="table-responsive">
		
			<table class="table table-cols">
				<tbody>
					<tr>
						<td class="-style-bold"><?php echo Label::getLabel('LBL_Reported_Issue_Reason'); ?></td>
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
						<td class="-style-bold"><?php echo Label::getLabel('LBL_Reported_Issue_Comment'); ?></td>
						<td>
							<?php echo nl2br($issueDeatils['issrep_comment']); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="box">
	<div class="box__head">
		<h6><?php echo Label::getLabel('LBL_Reported_Issue_Updates_By_Teacher'); ?></h6>
	</div>

	
	<div class="box__body  -padding-20">
		<div class="table-responsive">
			<table class="table table-cols">
				<tbody>
					<tr>
						<td class="-style-bold"><?php echo Label::getLabel('LBL_Reported_Issue_Reason'); ?></td>
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
						<td class="-style-bold"><?php echo Label::getLabel('LBL_Reported_Issue_Resolve_Comment'); ?></td>
						<td>
							<?php echo nl2br($issueDeatils['issrep_resolve_comments']); ?>
						</td>
					</tr>
					<tr>
						<td class="-style-bold"><?php echo Label::getLabel('LBL_Reported_Issue_Resolve_Type'); ?></td> 
						<td>
							<?php if( $issueDeatils['issrep_issues_resolve_type'] > 0 ) : echo $resolve_type_options[$issueDeatils['issrep_issues_resolve_type']]; endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php
$_time_valid_for_support = date('Y-m-d H:i:s');
if( $issueDeatils['issrep_updated_on'] != '0000-00-00 00:00:00' ) {
	$resolve_datetime = $issueDeatils['issrep_updated_on'];
	$_time_valid_for_support = date('Y-m-d H:i:s', strtotime('+ 48 Hours', strtotime($resolve_datetime)));
}

if ( strtotime($_time_valid_for_support )  > strtotime(date('Y-m-d H:i:s')) && $issueDeatils['issrep_is_for_admin'] < 1 ) {
?>
<div class="-padding-20 -no-padding-top">
	<p>
		<span class="-display-inline"><?php echo Label::getLabel('LBL_Not_Happy_with_teacher_solution?'); ?> &nbsp; </span><a href="javascript:void(0);" class="-link-underline -color-secondary" onclick="reportIssueToAdmin('<?php echo $issueDeatils['issrep_id'];?>', '<?php echo $issueDeatils['issrep_slesson_id']; ?>');"><?php echo Label::getLabel('LBL_Report_Issue_to_Support_Team'); ?></a>
	</p>
	</div>
<?php } ?>
	
</div>