<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$lastIssue = end($issueDetail);
$endedBy = isset(User::getUserTypesArr($adminLangId)[$lastIssue['slesson_ended_by']])?User::getUserTypesArr($adminLangId)[$lastIssue['slesson_ended_by']]:"NA";
//echo "<pre>"; print_r( $issueDetail ); echo "</pre>"; exit;

?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_View_Issue_Detail',$adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
			<tbody>
			<tr>
				<td width="50%"><strong><?php echo Label::getLabel('LBL_Reason_by_Learner',$adminLangId); ?>:</strong>  </td>
				<td>
					<?php
						foreach ( $issueDetail as $details ) {
							$_reasonIds = explode(',', $details['issrep_issues_to_report']);
							echo '<strong>'. date('Y-m-d H:i A', strtotime( $details['issrep_added_on'] )) .'</strong> <br /> <span>';
							foreach( $_reasonIds as $_ids ) {
								echo $issues_options[$_ids]. '<br />';
							}
							echo'</span><br />';

						}
					?>
				</td>
            </tr>
			<tr>
				<td width="50%"><strong><?php echo Label::getLabel('LBL_Comment',$adminLangId); ?>:</strong>  <br />
					<?php
						foreach ( $issueDetail as $details ) {
							echo '<strong>'. date('Y-m-d H:i A', strtotime( $details['issrep_added_on'] )) .'</strong> : ';
							echo $details['issrep_comment'] .'<br />';
						}
					?>
				</td>
				<td><strong><?php echo Label::getLabel('LBL_Reported_By',$adminLangId); ?>:</strong> <?php echo $lastIssue['reporter_username'].' ('.User::getUserTypesArr($adminLangId)[$lastIssue['issrep_reported_by']].')'; ?></td>

            </tr>
            <tr>
				<td><strong><?php echo Label::getLabel('LBL_Reported_Time',$adminLangId); ?>:</strong> <?php echo $lastIssue['issrep_added_on']; ?></td>
				<td><strong><?php echo Label::getLabel('LBL_Issue_Status',$adminLangId); ?>:</strong> <?php echo $statusArr[$lastIssue['issrep_status']]; ?></td>
            </tr>
			<?php if($lastIssue['issrep_issues_resolve'] !='') { ?>
			<tr>
				<td width="50%"><strong><?php echo Label::getLabel('LBL_Reason_by_Teacher',$adminLangId); ?>:</strong>  </td>
				<td>
					<?php
						foreach ( $issueDetail as $details ) {
							$_reasonIds = explode(',', $details['issrep_issues_resolve']);
							echo '<strong>'. date('Y-m-d H:i A', strtotime( $details['issrep_updated_on'] )) .'</strong> <br /> <span>';
							foreach( $_reasonIds as $_ids ) {
								echo $issues_options[$_ids]. '<br />';
							}
							echo'</span><br />';

						}
					?>
				</td>
            </tr>
			<?php } ?>
			<tr>
				<td width="50%"><strong><?php echo Label::getLabel('LBL_Teacher_Comment',$adminLangId); ?>:</strong><br />
					<?php
						foreach ( $issueDetail as $details ) {
							echo '<strong>'. date('Y-m-d H:i A', strtotime( $details['issrep_updated_on'] )) .'</strong> : ';
							echo $details['issrep_resolve_comments'] .'<br />';
						}
					?>
				</td>
				<td><strong><?php echo Label::getLabel('LBL_Teacher_Resolve_by',$adminLangId); ?>:</strong> <br />
					<?php
						foreach ( $issueDetail as $details ) {
							echo '<strong>'. date('Y-m-d H:i A', strtotime( $details['issrep_updated_on'] )) .'</strong> : ';
							echo IssuesReported::RESOLVE_TYPE[$details['issrep_issues_resolve_type']] .'<br />';
						}
					?>

				</td>
            </tr>
			</tbody>
		</table>
    </div>
</section>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_View_Lesson_Details',$adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">

            <tbody><tr>
              <td ><strong><?php echo Label::getLabel('LBL_Lesson_Id',$adminLangId); ?>:</strong>  <?php echo $lastIssue['issrep_slesson_id']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Order_Id',$adminLangId); ?>:</strong> <?php echo $lastIssue['sldetail_order_id']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Teacher_Name',$adminLangId); ?>:</strong>  <?php echo $lastIssue['teacher_username']; ?></td>
            </tr>
            <tr>
              <td ><strong><?php echo Label::getLabel('LBL_Learner_Name',$adminLangId); ?>:</strong>  <?php echo $lastIssue['learner_username']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Teacher_Join_Time',$adminLangId); ?>:</strong> <?php echo $lastIssue['slesson_teacher_join_time']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Teacher_End_Time',$adminLangId); ?>:</strong>  <?php echo $lastIssue['slesson_teacher_end_time']; ?></td>
            </tr>
            <tr>
              <td ><strong><?php echo Label::getLabel('LBL_Learner_Join_Time',$adminLangId); ?>:</strong>  <?php echo $lastIssue['slesson_learner_join_time']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Learner_end_Time',$adminLangId); ?>:</strong> <?php echo $lastIssue['slesson_learner_end_time']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Language',$adminLangId); ?>:</strong>  <?php echo $lastIssue['tlanguage_name']; ?></td>
            </tr>
            <tr>
              <td ><strong><?php echo Label::getLabel('LBL_Lesson_Ended_By',$adminLangId); ?>:</strong>  <?php echo $endedBy; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Completed_On',$adminLangId); ?>:</strong> <?php echo $lastIssue['slesson_ended_on']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Lesson_Call_History',$adminLangId); ?>:</strong>  <?php foreach($callHistory as $call){ if($call->GUID != "LESSON-".$lastIssue['issrep_slesson_id']) { continue; } ?>
													<strong><?php echo 'Call Type: '.$call->call_type.' Duration: '.$call->duration.' From: '.date('h:i:s', $call->start_time).' To: '.date('h:i:s', $call->end_time); ?></strong> <br>
												<?php } ?></td>
            </tr>
        </tbody></table>
    </div>
</section>
