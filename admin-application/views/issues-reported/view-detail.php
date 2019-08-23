<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php 
$endedBy = isset(User::getUserTypesArr($adminLangId)[$issueDetail['slesson_ended_by']])?User::getUserTypesArr($adminLangId)[$issueDetail['slesson_ended_by']]:"NA";
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_View_Issue_Detail',$adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
          
            <tbody><tr>
              <td width="50%"><strong><?php echo Label::getLabel('LBL_Comment',$adminLangId); ?>:</strong>  <?php echo $issueDetail['issrep_comment']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Reported_By',$adminLangId); ?>:</strong> <?php echo $issueDetail['reporter_username'].' ('.User::getUserTypesArr($adminLangId)[$issueDetail['issrep_reported_by']].')'; ?></td>
              
            </tr>
            <tr>
            <td><strong><?php echo Label::getLabel('LBL_Reported_Time',$adminLangId); ?>:</strong> <?php echo $issueDetail['issrep_added_on']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Issue_Status',$adminLangId); ?>:</strong> <?php echo $statusArr[$issueDetail['issrep_status']]; ?></td>
            </tr>                                   
        </tbody></table>
    </div>
</section>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_View_Lesson_Details',$adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
          
            <tbody><tr>
              <td ><strong><?php echo Label::getLabel('LBL_Lesson_Id',$adminLangId); ?>:</strong>  <?php echo $issueDetail['issrep_slesson_id']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Order_Id',$adminLangId); ?>:</strong> <?php echo $issueDetail['slesson_order_id']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Teacher_Name',$adminLangId); ?>:</strong>  <?php echo $issueDetail['teacher_username']; ?></td>              
            </tr>
            <tr>
              <td ><strong><?php echo Label::getLabel('LBL_Learner_Name',$adminLangId); ?>:</strong>  <?php echo $issueDetail['learner_username']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Teacher_Join_Time',$adminLangId); ?>:</strong> <?php echo $issueDetail['slesson_teacher_join_time']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Teacher_End_Time',$adminLangId); ?>:</strong>  <?php echo $issueDetail['slesson_teacher_end_time']; ?></td>
            </tr>                                   
            <tr>
              <td ><strong><?php echo Label::getLabel('LBL_Learner_Join_Time',$adminLangId); ?>:</strong>  <?php echo $issueDetail['slesson_learner_join_time']; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Learner_end_Time',$adminLangId); ?>:</strong> <?php echo $issueDetail['slesson_learner_end_time']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Language',$adminLangId); ?>:</strong>  <?php echo $issueDetail['slanguage_name']; ?></td>              
            </tr>                                   
            <tr>
              <td ><strong><?php echo Label::getLabel('LBL_Lesson_Ended_By',$adminLangId); ?>:</strong>  <?php echo $endedBy; ?></td>
              <td><strong><?php echo Label::getLabel('LBL_Completed_On',$adminLangId); ?>:</strong> <?php echo $issueDetail['slesson_ended_on']; ?></td>
              <td ><strong><?php echo Label::getLabel('LBL_Lesson_Call_History',$adminLangId); ?>:</strong>  <?php foreach($callHistory as $call){ if($call->GUID != "LESSON-".$issueDetail['issrep_slesson_id']) { continue; } ?>
													<strong><?php echo 'Call Type: '.$call->call_type.' Duration: '.$call->duration.' From: '.date('h:i:s', $call->start_time).' To: '.date('h:i:s', $call->end_time); ?></strong> <br>
												<?php } ?></td>              
            </tr>                                               
        </tbody></table>
    </div>
</section>