<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box -padding-20">
	<h4><?php echo Label::getLabel('LBL_Reported_Issue_Details'); ?></h4>
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