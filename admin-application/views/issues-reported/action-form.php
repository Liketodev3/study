<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('class', 'web_form');
$frm->setFormTagAttribute('onsubmit', 'setupAction(this); return(false);');
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
                    <td><strong><?php echo Label::getLabel('LBL_Teacher_Resolve_by', $adminLangId); ?>:</strong>
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
                    <td></td>
                </tr>
                <?php if (!empty($lastIssue['issrep_updated_by_admin']) && !empty($lastIssue['issrep_admin_comments'])) { ?>
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
        <h4><?php echo Label::getLabel('LBL_Issue_Reported_action_form'); ?></h4>
    </div>
    <div class="sectionbody">
        <?php echo $frm->getFormHtml(); ?>
    </div>
</section>