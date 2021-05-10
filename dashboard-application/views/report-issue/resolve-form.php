<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
MyDate::setUserTimeZone();
$userTimezone = MyDate::getUserTimeZone();
?>
<div class="box">
    <div class="box__head">
        <h4><?php echo Label::getLabel('LBL_Issue_Detail'); ?></h4>
    </div>
    <div class="box__body -padding-20">
        <div class="content-repeated-container">
            <table class="table table--details">
                <tbody>
                    <tr><td><h4><?php echo $issue['repiss_title']; ?></h4></td></tr>
                    <tr><td><strong><?php echo Label::getLabel('LBL_Detail'); ?>:</strong> <?php echo nl2br($issue['repiss_comment']); ?></td></tr>
                    <tr>
                        <td>
                            <strong><?php echo Label::getLabel('LBL_Reported_By'); ?>:</strong> <?php echo $issue['reporter_username']; ?>, 
                            <strong><?php echo Label::getLabel('LBL_Reported_Time'); ?>:</strong> <?php echo MyDate::format($issue['repiss_reported_on'], true, true, $userTimezone); ?>,
                            <strong><?php echo Label::getLabel('LBL_Issue_Status'); ?>:</strong> <?php echo ReportedIssue::getStatusArr($issue['repiss_status']); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('id', 'actionForm');
$frm->setFormTagAttribute('class', 'form form--horizontal');
$frm->setFormTagAttribute('onsubmit', 'resolveSetup(this); return(false);');
?>
<div class="box">
    <div class="box__head">
        <h4><?php echo Label::getLabel('LBL_RESOLUTION_FORM'); ?></h4>
    </div>
    <div class="box__body -padding-20">
        <div class="content-repeated-container">
            <?php echo $frm->getFormHtml(); ?>
        </div>
    </div>
</div>