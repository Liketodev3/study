<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$lastIssue = end($issueDetail);
$endedBy = isset(User::getUserTypesArr($adminLangId)[$lastIssue['slesson_ended_by']]) ? User::getUserTypesArr($adminLangId)[$lastIssue['slesson_ended_by']] : "NA";
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_View_Issue_Detail', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
            <tbody>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Reported_By', $adminLangId); ?>:</strong> <?php echo $lastIssue['reporter_username']; ?><br>
                        <strong><?php echo Label::getLabel('LBL_Reported_Time', $adminLangId); ?>:</strong> <?php echo MyDate::format($lastIssue['issrep_added_on'], true, true, Admin::getAdminTimeZone()); ?><br>
                        <strong><?php echo Label::getLabel('LBL_Issue_Status', $adminLangId); ?>:</strong> <?php echo $statusArr[$lastIssue['issrep_status']] ?? 'NA'; ?>
                    </td>
                    <td>
                        <strong><?php echo Label::getLabel('LBL_Reason_by_Learner', $adminLangId); ?>:</strong>
                        <?php
                        foreach ($issueDetail as $details) {
                            $_reasonIds = explode(',', $details['issrep_issues_to_report']);
                            echo $details['issrep_comment'] . '<br />';
                            echo '<strong>Date: ' . MyDate::format($details['issrep_added_on'], true, true, Admin::getAdminTimeZone()) . '</strong> <br /> <span>';
                            echo '<strong>Options:</strong> ';
                            foreach ($_reasonIds as $_ids) {
                                echo $issues_options[$_ids] . '<br />';
                            }
                            echo'</span><br />';
                        }
                        ?>
                    </td>
                <tr>
                    <?php if ($lastIssue['issrep_issues_resolve'] != '') { ?>
                    <tr>
                        <td width="50%"><strong><?php echo Label::getLabel('LBL_Reason_by_Teacher', $adminLangId); ?>:</strong>  </td>
                        <td>
                            <?php
                            foreach ($issueDetail as $details) {
                                echo $details['issrep_resolve_comments'] . '<br />';
                                $_reasonIds = explode(',', $details['issrep_issues_resolve']);
                                echo 'Date: <strong>' . MyDate::format($details['issrep_updated_on'], true, true, Admin::getAdminTimeZone()) . '</strong> <br /> <span>';
                                echo '<strong>Options:</strong> ';
                                foreach ($_reasonIds as $_ids) {
                                    echo $issues_options[$_ids] . '<br />';
                                }
                                echo'</span><br />';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Teacher_Resolve_by', $adminLangId); ?>:</strong></td>
                    <td>
                        <?php
                        if ($lastIssue['issrep_status'] < 1) {
                            echo Label::getLabel('LBL_NA');
                        } else {
                            foreach ($issueDetail as $details) {
                                echo IssuesReported::getResolveTypeArray()[$details['issrep_issues_resolve_type']] . '<br />';
                                echo '<strong>Date:' . MyDate::format($details['issrep_updated_on'], true, true, Admin::getAdminTimeZone()) . '</strong><br> ';
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php if (!empty($lastIssue['issrep_updated_by_admin']) && !empty($lastIssue['issrep_admin_comments'])) { ?>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Admin_Resolution', $adminLangId); ?>:</strong></td>
                        <td><?php echo IssuesReported::getResolveTypeArray()[$lastIssue['issrep_status']] ?? 'NA'; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Admin_comments', $adminLangId); ?>:</strong></td>
                        <td><?php echo $lastIssue['issrep_admin_comments']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_View_Lesson_Details', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
            <tbody>
                <tr>
                    <td ><strong><?php echo Label::getLabel('LBL_Language', $adminLangId); ?>:</strong>  <?php echo $lastIssue['tlanguage_name']; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Free_Trail', $adminLangId); ?>:</strong> <?php echo applicationConstants::getYesNoArr()[$lastIssue['op_lpackage_is_free_trial']]; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Order_Id', $adminLangId); ?>:</strong> <?php echo $lastIssue['sldetail_order_id']; ?></td>
                    <td ><strong><?php echo Label::getLabel('LBL_Lesson_Id', $adminLangId); ?>:</strong>  <?php echo $lastIssue['issrep_slesson_id']; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Total_Lesson', $adminLangId); ?>:</strong> <?php echo $lastIssue['op_qty']; ?></td>
                    <td ><strong><?php echo Label::getLabel('LBL_Lesson_Price', $adminLangId); ?>:</strong>   <?php echo CommonHelper::displayMoneyFormat($lastIssue['op_unit_price'], true, true); ?></td>
                    <td ><strong><?php echo Label::getLabel('LBL_Order_Net_Amount', $adminLangId); ?>:</strong>  <?php echo CommonHelper::displayMoneyFormat($lastIssue['order_net_amount'], true, true); ?></td>
                    <td ><strong><?php echo Label::getLabel('LBL_Order_Discount_Total', $adminLangId); ?>:</strong>   <?php echo CommonHelper::displayMoneyFormat($lastIssue['order_discount_total'], true, true); ?></td>
                    <td ></td>
                </tr>
                <tr>
                    <td ><strong><?php echo Label::getLabel('LBL_Teacher_Name', $adminLangId); ?>:</strong>  <?php echo $lastIssue['teacher_username']; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Teacher_Join_Time', $adminLangId); ?>:</strong> <?php echo MyDate::format($lastIssue['slesson_teacher_join_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td ><strong><?php echo Label::getLabel('LBL_Teacher_End_Time', $adminLangId); ?>:</strong>  <?php echo MyDate::format($lastIssue['slesson_teacher_end_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td ></td>
                </tr>
                <tr>
                    <td ><strong><?php echo Label::getLabel('LBL_Learner_Name', $adminLangId); ?>:</strong>  <?php echo $lastIssue['learner_username']; ?></td>
                    <td ><strong><?php echo Label::getLabel('LBL_Learner_Join_Time', $adminLangId); ?>:</strong>  <?php echo MyDate::format($lastIssue['sldetail_learner_join_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Learner_end_Time', $adminLangId); ?>:</strong> <?php echo MyDate::format($lastIssue['sldetail_learner_end_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td ></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
