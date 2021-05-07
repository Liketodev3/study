<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="box">
    <div class="box__head">
        <h6><?php echo Label::getLabel('LBL_Issue_Detail'); ?></h6>
    </div>
    <div class="box__body -padding-20">
        <div class="content-repeated-container">
            <table class="table table--details">
                <tbody>
                    <tr><td><h4><?php echo $issue['repiss_title']; ?></h4></td></tr>
                    <tr><td><strong><?php echo Label::getLabel('LBL_Detail', $siteLangId); ?>:</strong> <?php echo nl2br($issue['repiss_comment']); ?></td></tr>
                    <tr>
                        <td>
                            <strong><?php echo Label::getLabel('LBL_Reported_By', $siteLangId); ?>:</strong> <?php echo $issue['reporter_username']; ?>, 
                            <strong><?php echo Label::getLabel('LBL_Reported_Time', $siteLangId); ?>:</strong> <?php echo MyDate::format($issue['repiss_reported_on'], true, true, $userTimezone); ?>,
                            <strong><?php echo Label::getLabel('LBL_Issue_Status', $siteLangId); ?>:</strong> <?php echo ReportedIssue::getStatusArr($issue['repiss_status']); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box__head">
        <h6><?php echo Label::getLabel('LBL_Lesson_Details'); ?></h6>
    </div>
    <div class="box__body -padding-20">
        <div class="content-repeated-container">
            <table class="table table--details">
                <tbody>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Language', $siteLangId); ?>:</strong>  <?php echo $issue['tlanguage_name']; ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Free_Trail', $siteLangId); ?>:</strong> <?php echo applicationConstants::getYesNoArr()[$issue['op_lpackage_is_free_trial']]; ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Order_Id', $siteLangId); ?>:</strong> <?php echo $issue['sldetail_order_id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Lesson_Id', $siteLangId); ?>:</strong>  <?php echo $issue['repiss_slesson_id']; ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Total_Lesson', $siteLangId); ?>:</strong> <?php echo $issue['op_qty']; ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Lesson_Price', $siteLangId); ?>:</strong>   <?php echo CommonHelper::displayMoneyFormat($issue['op_unit_price'], true, true); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Order_Net_Amount', $siteLangId); ?>:</strong>  <?php echo CommonHelper::displayMoneyFormat($issue['order_net_amount'], true, true); ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Order_Discount_Total', $siteLangId); ?>:</strong>   <?php echo CommonHelper::displayMoneyFormat($issue['order_discount_total'], true, true); ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Teacher_Name', $siteLangId); ?>:</strong>  <?php echo $issue['teacher_username']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Teacher_Join_Time', $siteLangId); ?>:</strong> <?php echo MyDate::format($issue['slesson_teacher_join_time'], true, true, $userTimezone); ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Teacher_End_Time', $siteLangId); ?>:</strong>  <?php echo MyDate::format($issue['slesson_teacher_end_time'], true, true, $userTimezone); ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Learner_Name', $siteLangId); ?>:</strong>  <?php echo $issue['learner_username']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo Label::getLabel('LBL_Learner_Join_Time', $siteLangId); ?>:</strong>  <?php echo MyDate::format($issue['sldetail_learner_join_time'], true, true, $userTimezone); ?></td>
                        <td><strong><?php echo Label::getLabel('LBL_Learner_end_Time', $siteLangId); ?>:</strong> <?php echo MyDate::format($issue['sldetail_learner_end_time'], true, true, $userTimezone); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>