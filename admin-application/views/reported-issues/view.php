<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_Issue_Detail', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
            <tbody>
                <tr><td><h3><?php echo $issue['repiss_title']; ?></h3></td></tr>
                <tr>
                    <td>
                        <strong><?php echo Label::getLabel('LBL_Reported_By', $adminLangId); ?>:</strong> <?php echo $issue['reporter_username']; ?>, 
                        <strong><?php echo Label::getLabel('LBL_Reported_Time', $adminLangId); ?>:</strong> <?php echo MyDate::format($issue['repiss_reported_on'], true, true, Admin::getAdminTimeZone()); ?>,
                        <strong><?php echo Label::getLabel('LBL_Issue_Status', $adminLangId); ?>:</strong> <?php echo ReportedIssue::getStatusArr($issue['repiss_status']); ?>
                    </td>
                </tr>
                <tr><td><p><?php echo nl2br($issue['repiss_comment']); ?></p></td></tr>
            </tbody>
        </table>
    </div>
</section>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_Lesson_Details', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
            <tbody>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Language', $adminLangId); ?>:</strong>  <?php echo $issue['tlanguage_name']; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Free_Trail', $adminLangId); ?>:</strong> <?php echo applicationConstants::getYesNoArr()[$issue['op_lpackage_is_free_trial']]; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Order_Id', $adminLangId); ?>:</strong> <?php echo $issue['sldetail_order_id']; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Lesson_Id', $adminLangId); ?>:</strong>  <?php echo $issue['repiss_slesson_id']; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Total_Lesson', $adminLangId); ?>:</strong> <?php echo $issue['op_qty']; ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Lesson_Price', $adminLangId); ?>:</strong>   <?php echo CommonHelper::displayMoneyFormat($issue['op_unit_price'], true, true); ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Order_Net_Amount', $adminLangId); ?>:</strong>  <?php echo CommonHelper::displayMoneyFormat($issue['order_net_amount'], true, true); ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Order_Discount_Total', $adminLangId); ?>:</strong>   <?php echo CommonHelper::displayMoneyFormat($issue['order_discount_total'], true, true); ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Teacher_Name', $adminLangId); ?>:</strong>  <?php echo $issue['teacher_username']; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Teacher_Join_Time', $adminLangId); ?>:</strong> <?php echo MyDate::format($issue['slesson_teacher_join_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Teacher_End_Time', $adminLangId); ?>:</strong>  <?php echo MyDate::format($issue['slesson_teacher_end_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Learner_Name', $adminLangId); ?>:</strong>  <?php echo $issue['learner_username']; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Label::getLabel('LBL_Learner_Join_Time', $adminLangId); ?>:</strong>  <?php echo MyDate::format($issue['sldetail_learner_join_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td><strong><?php echo Label::getLabel('LBL_Learner_end_Time', $adminLangId); ?>:</strong> <?php echo MyDate::format($issue['sldetail_learner_end_time'], true, true, Admin::getAdminTimeZone()); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
