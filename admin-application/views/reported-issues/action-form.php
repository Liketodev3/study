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
<?php if (count($logs)) { ?>
    <section class="section">
        <div class="sectionhead">
            <h4><?php echo Label::getLabel('LBL_Issue_Log', $adminLangId); ?></h4>
        </div>
        <div class="sectionbody">
            <table class="table table--details">
                <thead>
                    <tr>
                        <th><?php echo Label::getLabel('LBL_SR_NO'); ?></th>
                        <th><?php echo Label::getLabel('LBL_ACTION'); ?></th>
                        <th><?php echo Label::getLabel('LBL_COMMENT'); ?></th>
                        <th><?php echo Label::getLabel('LBL_ACTION_BY'); ?></th>
                        <th><?php echo Label::getLabel('LBL_ACTION_ON'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $srNo = 1;
                    foreach ($logs as $log) {
                        ?>
                        <tr>
                            <td><?php echo $srNo++; ?></td>
                            <td><?php echo $actionArr[$log['reislo_action']]; ?></td>
                            <td><?php echo nl2br($log['reislo_comment']); ?></td>
                            <td>
                                <?php echo $log['user_fullname']; ?>
                                <?php echo '(' . ReportedIssue::getUserTypeArr($log['reislo_added_by_type']) . ')'; ?>
                            </td>
                            <td><?php echo $log['reislo_added_on']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>
<?php
if ($issue['repiss_status'] == ReportedIssue::STATUS_ESCLATED) {
    $frm->developerTags['colClassPrefix'] = 'col-md-';
    $frm->developerTags['fld_default_col'] = 12;
    $frm->setFormTagAttribute('id', 'actionForm');
    $frm->setFormTagAttribute('class', 'web_form');
    $frm->setFormTagAttribute('onsubmit', 'setupAction(this); return(false);');
    ?>
    <section class="section">
        <div class="sectionhead">
            <h4><?php echo Label::getLabel('LBL_ACTION_FORM'); ?></h4>
        </div>
        <div class="sectionbody">
            <?php echo $frm->getFormHtml(); ?>
        </div>
    </section>
<?php } ?>