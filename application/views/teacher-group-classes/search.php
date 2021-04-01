<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
MyDate::setUserTimeZone();
$user_timezone = MyDate::getUserTimeZone();
$date = new DateTime("now", new DateTimeZone($user_timezone));
$curDate = $date->format('Y-m-d');
$nextDate = date('Y-m-d', strtotime('+1 days', strtotime($curDate))); ?>
<div class="box -padding-20">
    <div class="table-scroll">
        <table class="table ">
            <tbody>
                <tr class="-hide-mobile">
                    <th><?php echo Label::getLabel('LBL_Sr_No.'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Title'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Start_At'); ?></th>
                    <th><?php echo Label::getLabel('LBL_End_At'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Status'); ?></th>
                    <th><?php echo Label::getLabel('LBL_Actions'); ?></th>
                </tr>
                <?php foreach ($classes as $i => $class) { ?>
                    <?php $sr_no = $pagingArr['page'] == 1 ? 0 : $pagingArr['pageSize'] * ($pagingArr['page'] - 1); ?>
                    <tr>
                        <td>
                            <span class="td__data"><?php echo $sr_no + $i + 1; ?></span>
                        </td>
                        <td>
                            <span class="td__data">
                                <p>
                                    <?php echo $class['grpcls_title']; ?><br>

                                    <strong><?php echo Label::getLabel('LBL_Booked_Seats'); ?>:</strong>
                                    <?php echo $class['total_learners']; ?>&nbsp;&nbsp;&nbsp;

                                    <strong><?php echo Label::getLabel('LBL_Entry_fee'); ?>:</strong>
                                    <?php echo $class['grpcls_entry_fee']; ?>
                                </p>
                            </span>
                        </td>

                        <td>
                            <span class="td__data"><?php echo MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i', $class['grpcls_start_datetime'], true, $user_timezone); ?></span>
                        </td>
                        <td>
                            <span class="td__data"><?php echo MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i', $class['grpcls_end_datetime'], true, $user_timezone); ?></span>
                        </td>

                        <td>
                            <span class="td__data"><?php echo $classStatusArr[$class['grpcls_status']] ?></span>
                        </td>

                        <td width="21%">
                            <?php if ($class['slesson_id']) : ?>
                                <a class="btn btn--primary btn--small" href="<?php echo CommonHelper::generateUrl('TeacherScheduledLessons', 'view', array($class['slesson_id'])) ?>"><?php echo ($class['is_joined'] ? Label::getLabel("LBL_View_Class") : Label::getLabel("LBL_Start_Class")) ?></a>
                                <?php if ($class['issrep_id'] > 0) : ?>
                                    <a class="btn btn--small" href="javascript:void(0);" onclick="resolveIssue('<?php echo $class['slesson_id']; ?>')"><?php echo Label::getLabel("LBL_Class_Issue_details") ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($class['grpcls_status'] == TeacherGroupClasses::STATUS_ACTIVE) : ?>
                                <?php if ($class['grpcls_start_datetime'] > date('Y-m-d H:i:s')) : ?>
                                    <a class="btn btn--primary btn--small" href="javascript:void(0);" onclick="form('<?php echo $class['grpcls_id'];  ?>');"><?php echo Label::getLabel("LBL_Edit") ?></a>
                                <?php else : ?>
                                    <a class="btn btn--gray btn--disabled btn--small" href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Can_not_edit_old_classes') ?>"><?php echo Label::getLabel("LBL_Edit") ?></a>
                                <?php endif; 
                                    if (empty($class['issrep_id']) && $class['grpcls_status'] != TeacherGroupClasses::STATUS_COMPLETED){ ?>
                                     <a class="btn btn--secondary btn--small" href="javascript:void(0);" onclick="cancelClass('<?php echo $class['grpcls_id'];  ?>');"><?php echo Label::getLabel("LBL_Cancel") ?></a>
                                <?php } ?>
                                <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if (empty($classes)) {
    $this->includeTemplate('_partial/no-record-found.php');
} else {
    echo FatUtility::createHiddenFormFromData($postedData, array(
        'name' => 'frmSearchPaging'
    ));

    $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
}
?>