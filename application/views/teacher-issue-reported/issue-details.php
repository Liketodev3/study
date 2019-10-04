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
						<?php echo $resolve_type_options[$issueDeatils['issrep_issues_resolve_type']]; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>