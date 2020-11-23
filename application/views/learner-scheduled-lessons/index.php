<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script>var statusUpcoming = <?php echo FatUtility::int(ScheduledLesson::STATUS_UPCOMING); ?></script>
<section class="section section--grey section--page">
    <div class="container container--fixed">

        <div class="page-panel -clearfix">

            <!--panel left start here-->
            <div class="page-panel__left">
                <?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
            </div>
            <!--panel left end here-->


            <!--panel right start here-->
            <div class="page-panel__right">

                <!--page-head start here-->
                <div class="page-head">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1><?php echo Label::getLabel('LBL_My_Lessons'); ?></h1>
                        </div>
                        <div>
                            <div class="tab-swticher tab-swticher-small calender-lessons-js">
                                <a href="<?php echo CommonHelper::generateUrl('LearnerScheduledLessons'); ?>" class="btn btn--large is-active list-js"><?php echo Label::getLabel('LBL_List'); ?></a>
                                <a onclick="viewCalendar();" href="javascript:void(0);" class="btn btn--large calender-js"><?php echo Label::getLabel('LBL_Calender'); ?></a>
                            </div>

                        </div>
                    </div>
                </div>
                <!--page-head end here-->

                <!--page filters start here-->
                <div class="page-filters">
                    <?php
                    $frmSrch->setFormTagAttribute('onsubmit', 'searchAllStatusLessons(this); return(false);');
                    $frmSrch->setFormTagAttribute('class', 'form form--small');

                    $frmSrch->developerTags['colClassPrefix'] = 'col-md-';
                    $frmSrch->developerTags['fld_default_col'] = 5;

                    $fldStatus = $frmSrch->getField('status');
                    $fldStatus->developerTags['col'] = 3;
                    $fldStatus->setWrapperAttribute('class', 'd-none');

                    $fldSubmit = $frmSrch->getField('btn_submit');
                    $fldSubmit->developerTags['col'] = 4;

                    $btnReset = $frmSrch->getField('btn_reset');
                    //$btnReset->addFieldTagAttribute( 'style', 'margin-left:10px' );
                    $btnReset->addFieldTagAttribute('onclick', 'clearSearch()');
                    echo $frmSrch->getFormHtml(); ?>

                </div>
                <!--page filters end here-->

                <div class="col-md-12 text-right">
                    <strong class="-color-primary span-right">
                        <span class="spn_must_field">*</span>
                        <?php $label =  Label::getLabel('LBL_All_times_listed_are_in_your_selected_{timezone}');
                        $getTimeZoneString = MyDate::displayTimezoneString(false);
                        $label = str_replace('{timezone}', $getTimeZoneString, $label);
                        echo $label; ?>
                    </strong>
                </div>

                <div class="-gap"></div>

                <div class="tabs-inline">
                    <ul class="lessons-list-tabs--js">
                        <li class="is-active">
                            <a href="javascript:;" onClick="getLessonsByStatus(this, '')">
                                <?php echo Label::getLabel('L_ALL'); ?>
                            </a>
                        </li>
                        <?php unset($lessonStatuses[ScheduledLesson::STATUS_RESCHEDULED]);
                        $lessonStatuses[ScheduledLesson::STATUS_SCHEDULED] = Label::getLabel('LBL_Scheduled/Rescheduled');
                        foreach ($lessonStatuses as $key => $status) : ?>
                            <li class="">
                                <a href="javascript:;" id="lesson-status<?php echo $key; ?>" onClick="getLessonsByStatus(this, <?php echo $key ?>)">
                                    <?php echo $status; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="-gap"></div>

                <!--Lessons list view start here-->
                <div class="col-list-group">
                    <!--h6>Today</h6-->
                    <div class="col-list-container" id="listItemsLessons">
                    </div>
                </div>
                <!--Lessons list view end here-->
            </div>
            <!--panel right end here-->
        </div>
    </div>
</section>