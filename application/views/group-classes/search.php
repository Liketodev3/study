<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$userTimezone = MyDate::getUserTimeZone();
$curDateTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $userTimezone);
$curDateTimeUnix =   strtotime($curDateTime);
$frm->getField('custom_filter')->addFieldTagAttribute('form', 'group-class-search');
?>

<div class="group-cover">
    <div class="sorting__head">
        <div class="sorting__title">
            <?php if (!empty($classes)) { ?>
                <h4><b><?php echo $pagingArr['recordCount']; ?> </b><?php echo Label::getLabel('LBL_GROUP_CLASSES_FOR_YOU.'); ?></h4>
            <?php } ?>
        </div>
        <div class="sorting__box">
            <!-- <b>Sort By:</b> -->
            <?php echo $frm->getFieldHtml('custom_filter'); ?>

        </div>
    </div>
    <?php if (!empty($classes)) { ?>
        <div class="group__list">
            <div class="row">
                <?php foreach ($classes as $class) {
                    $startDateTime =  MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $class['grpcls_start_datetime'], true, $userTimezone);
                    $endDateTime =  MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $class['grpcls_end_datetime'], true, $userTimezone);
                    $startDateTimeUnix =  strtotime($startDateTime);
                    $endDateTimeUnix =  strtotime($endDateTime);
                ?>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="card card--bg color-white">
                            <div class="card__head">
                                <a href="<?php echo CommonHelper::generateUrl('GroupClasses', 'view', array($class['grpcls_id'])); ?>">
                                    <h3><?php echo $class['grpcls_title']; ?></h3>
                                </a>
                            </div>
                            <div class="card__body">
                                <div class="card__row">
                                    <span><?php echo Label::getLabel("LBL_Date_&_Time"); ?></span>
                                    <p><?php echo date('d, M Y, h:i A', $startDateTimeUnix) . ' - ' . date('h:i A', $endDateTimeUnix); ?></p>
                                </div>
                                <div class="card__row">
                                    <span><?php echo Label::getLabel('LBL_TUTOR'); ?></span>
                                    <p><?php echo $class['user_full_name'] ?></p>
                                </div>
                                <div class="card__row">
                                    <span><?php echo Label::getLabel('LBL_PRICE'); ?></span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="class-price"><?php echo CommonHelper::displayMoneyFormat($class['grpcls_entry_fee']); ?></p>
                                        <div class="timer">
                                            <div class="timer__media">
                                                <span> <svg class="icon icon--clock">
                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.yo-coach.svg#clock"></use>
                                                    </svg></span>
                                            </div>
                                            <div class="timer__content">
                                                <div class="timer__controls countdowntimer-js timer-js" id="timer-<?php echo $class['grpcls_id']; ?>" data-startTime="<?php echo date('Y/m/d H:i:s', $curDateTimeUnix); ?>" data-endTime="<?php echo date('Y/m/d H:i:s', $startDateTimeUnix); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card__row--action">
                                    <a href="<?php echo CommonHelper::generateUrl('GroupClasses', 'view', array($class['grpcls_id'])); ?>" class="btn btn--bordered color-primary" tabindex="0"><?php echo Label::getLabel("LBL_VIEW_DETAILS") ?></a>

                                    <?php if ($class['is_in_class']) : ?>
                                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_ALREADY_IN_CLASS') ?>" tabindex="0" class="btn btn--primary btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php elseif ($class['grpcls_max_learner'] > 0 && $class['total_learners'] >= $class['grpcls_max_learner']) : ?>
                                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_CLASS_FULL') ?>" tabindex="0" class="btn btn--primary btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php elseif ($class['grpcls_start_datetime'] < date('Y-m-d H:i:s', strtotime('+' . $min_booking_time . ' minutes'))) : ?>
                                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Booking_Close_For_This_Class') ?>" class="btn btn--primary btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php elseif (UserAuthentication::isUserLogged() && $class['grpcls_teacher_id'] == UserAuthentication::getLoggedUserId()) : ?>
                                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Can_not_join_own_classes') ?>" class="btn btn--primary btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php elseif ($class['grpcls_status'] != TeacherGroupClasses::STATUS_ACTIVE) : ?>
                                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Class_Not_active') ?>" class="btn btn--primary btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php else : ?>
                                        <a href="javascript:void(0);" onClick="cart.proceedToStep({teacherId:<?php echo $class['grpcls_teacher_id']; ?>,grpclsId:<?php echo $class['grpcls_id'] ?>, languageId : <?php echo $class['grpcls_tlanguage_id'] ?>},'getPaymentSummary');" class="btn btn--primary btn--medium"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php
        echo FatUtility::createHiddenFormFromData($postedData, array(
            'name' => 'frmSearchPaging'
        ));
        $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
    } else { ?>
        <div class="message-display">
            <div class="message-display__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460.571 373.649">
                    <defs>
                        <style>
                            .a {
                                fill: #ccd0d9;
                            }

                            .b,
                            .e,
                            .f {
                                fill: none;
                            }

                            .b,
                            .d {
                                stroke: #b1b5c4;
                                stroke-width: 4px;
                            }

                            .b,
                            .d,
                            .e {
                                stroke-miterlimit: 10;
                            }

                            .c {
                                fill: #b1b5c4;
                            }

                            .d {
                                fill: #fff;
                            }

                            .e {
                                stroke: #fff;
                            }
                        </style>
                    </defs>
                    <g transform="translate(-700 -2490)">
                        <path class="a" d="M343.893,118.759v22.694l-232.8-1.631s-.7,1.347-1.691-13.153v-42s-2.234-11.96,11.822-11.96h72.411l17.676,28.748,119.086,1.386s13.5,2.26,13.5,15.914" transform="translate(688.518 2438.915)" />
                        <path class="b" d="M326.87,101.459H211.341L194.991,74.33a3.434,3.434,0,0,0-2.878-1.619H124.469c-5.39,0-9.523,1.619-12.038,4.761a13.312,13.312,0,0,0-2.7,10.152V257.415c0,6.2,1.709,10.78,5.3,13.925a15.764,15.764,0,0,0,10.421,3.593,10.868,10.868,0,0,0,1.889-.09h211.92a3.375,3.375,0,0,0,3.328-3.328V119.875C342.681,107.477,332.349,102.446,326.87,101.459Z" transform="translate(688.486 2438.915)" />
                        <path class="c" d="M394.855,358.35a1.151,1.151,0,0,0-1.627,1.627l3.446,3.447a1.123,1.123,0,0,0,.814.335.985.985,0,0,0,.813-.335,1.187,1.187,0,0,0,0-1.628Z" transform="translate(660.013 2410.24)" />
                        <path class="c" d="M405.619,354.513l-3.78-3.78a1.262,1.262,0,1,0-1.785,1.785l3.78,3.78a1.227,1.227,0,0,0,.892.368,1.154,1.154,0,0,0,.892-.368,1.3,1.3,0,0,0,0-1.785" transform="translate(659.33 2411.009)" />
                        <path class="d" d="M305.525,163.257A100.833,100.833,0,1,0,406.358,264.09,100.787,100.787,0,0,0,305.525,163.257Z" transform="translate(678.928 2429.814)" />
                        <path class="d" d="M307.49,182.811a83.244,83.244,0,1,0,83.244,83.244A83.207,83.207,0,0,0,307.49,182.811Z" transform="translate(676.963 2427.849)" />
                        <path class="c" d="M343.526,146.837a1.349,1.349,0,0,0-1.329-1.016H110.231a1.34,1.34,0,0,0-1.333,1.346c0,.033,0,.067,0,.1a1.387,1.387,0,0,0,1.446,1.25H342.237a1.367,1.367,0,0,0,1.094-.547,1.314,1.314,0,0,0,.2-1.133" transform="translate(688.556 2431.567)" />
                        <path class="e" d="M300.089,309.212s2.159-13.043,17.36-13.043c0,0,17.36,0,17.36,13.043" transform="translate(669.34 2416.456)" />
                        <g transform="translate(942.292 2665.979)">
                            <path class="c" d="M279.5,251.373l4.009-4.009a1.781,1.781,0,0,0-2.492-2.544l-.026.026-4.008,4.008-4.009-4.008a1.781,1.781,0,0,0-2.544,2.492l.026.025,4.008,4.009-4.008,4.008a1.833,1.833,0,0,0,0,2.518,1.672,1.672,0,0,0,1.233.515,1.592,1.592,0,0,0,1.233-.515l4.009-4.008,4.008,4.008a1.672,1.672,0,0,0,1.233.515,1.592,1.592,0,0,0,1.233-.515,1.833,1.833,0,0,0,0-2.518Z" transform="translate(-269.92 -244.312)" />
                            <path class="c" d="M357.146,251.373l4.009-4.009a1.781,1.781,0,0,0-2.492-2.544l-.026.026-4.008,4.008-4.009-4.008a1.781,1.781,0,0,0-2.544,2.492l.026.025,4.008,4.009-4.008,4.008a1.833,1.833,0,0,0,0,2.518,1.674,1.674,0,0,0,1.233.515,1.592,1.592,0,0,0,1.233-.515l4.009-4.008,4.008,4.008a1.674,1.674,0,0,0,1.233.515,1.592,1.592,0,0,0,1.233-.515,1.833,1.833,0,0,0,0-2.518Z" transform="translate(-277.724 -244.312)" />
                            <path class="c" d="M330.513,304.263c-1.517-7.948-8.372-9.1-12.316-9.1-9.465,0-12.074,6.067-12.8,8.677a1.994,1.994,0,0,0,.425,1.82,1.973,1.973,0,0,0,1.638.729h.121a2.143,2.143,0,0,0,1.82-1.456c.607-2.063,2.488-5.521,8.8-5.521.789,0,7.584.182,8.312,5.521a2,2,0,0,0,2.063,1.759,2.263,2.263,0,0,0,1.578-.729,2.1,2.1,0,0,0,.363-1.7" transform="translate(-273.479 -249.422)" />
                        </g>
                        <path class="d" d="M464.146,422.193h0a17.644,17.644,0,0,1-24.953.053l-.053-.053-37.059-37.059a17.682,17.682,0,1,1,24.839-25.173l.167.167,37.059,37.059a17.644,17.644,0,0,1,.053,24.953l-.053.053" transform="translate(659.618 2410.556)" />
                        <g transform="translate(723.249 2495.397)">
                            <path class="a" d="M454.055,204.269a5.667,5.667,0,1,0,5.667,5.667,5.667,5.667,0,0,0-5.667-5.667m2.768,5.667a2.768,2.768,0,1,1-2.768-2.768h0a2.786,2.786,0,0,1,2.768,2.768" transform="translate(-68.814 -69.705)" />
                            <path class="a" d="M239.2,54.669a4.408,4.408,0,1,0,4.408,4.408h0a4.419,4.419,0,0,0-4.408-4.408m2.152,4.408a2.152,2.152,0,1,1-2.152-2.152,2.153,2.153,0,0,1,2.152,2.152" transform="translate(-47.346 -54.669)" />
                            <path class="a" d="M74.523,124.658H69.851v-4.717A1.924,1.924,0,1,0,66,119.859c0,.027,0,.055,0,.083v4.718H61.236a1.924,1.924,0,0,0-.084,3.847h4.8v4.672a1.924,1.924,0,0,0,3.847.084v-4.71h4.626a1.906,1.906,0,0,0,1.924-1.888v-.036a1.817,1.817,0,0,0-1.658-1.964c-.058,0-.116-.007-.175-.006" transform="translate(-29.705 -61.032)" />
                            <path class="a" d="M417.46,101.665H412.82V97.052a1.116,1.116,0,0,0-2.228,0v4.614h-4.613a1.116,1.116,0,0,0,0,2.228h4.613v4.637a1.122,1.122,0,0,0,1.115,1.116,1.1,1.1,0,0,0,1.115-1.093v-4.636h4.641a1.127,1.127,0,1,0,0-2.253" transform="translate(-64.445 -58.823)" />
                            <path class="a" d="M170.939,359.05H164.88v-6.027a1.454,1.454,0,0,0-2.908,0v6.027h-6.027a1.454,1.454,0,0,0,0,2.908h6.027v6.059a1.463,1.463,0,0,0,1.454,1.454,1.44,1.44,0,0,0,1.454-1.424v-6.057h6.059a1.472,1.472,0,0,0,0-2.943" transform="translate(-39.279 -84.513)" />
                            <path class="a" d="M84.971,313.962,67.142,281.482a1.6,1.6,0,0,0-1.037-.908l-11.67-3.242a1.529,1.529,0,0,0-1.3.065L29.151,290.235a5.214,5.214,0,0,0-2.528,2.982,5.394,5.394,0,0,0,.324,3.89l19.514,37.407a4.833,4.833,0,0,0,3.047,2.528,5.88,5.88,0,0,0,1.491.2,8.278,8.278,0,0,0,2.2-.389l.259-.065L82.9,320.965a4.865,4.865,0,0,0,2.528-3.112,5.126,5.126,0,0,0-.454-3.89M58.908,285.178l-1.88-3.112,4.8,1.362ZM58.2,289.2h.13a1.2,1.2,0,0,0,.778-.324l6.159-3.7,16.6,30.276a1.657,1.657,0,0,1,.13,1.362,2,2,0,0,1-.842,1.037L51.712,333.671a1.511,1.511,0,0,1-1.3.13,2.162,2.162,0,0,1-1.1-.908L29.928,295.486a1.962,1.962,0,0,1,.713-2.464l21.978-11.735,4.279,7.131a1.917,1.917,0,0,0,.972.713c.065.065.26.065.325.065" transform="translate(-26.401 -77.037)" />
                        </g>
                        <path class="c" d="M39.272,304.441a1.153,1.153,0,0,1-1.037-.649,1.11,1.11,0,0,1,.428-1.51.939.939,0,0,1,.09-.045l15.755-8.04a1.167,1.167,0,0,1,1.556.519,1.111,1.111,0,0,1-.43,1.511c-.029.016-.058.031-.089.045l-15.689,8.039a1.882,1.882,0,0,1-.584.13" transform="translate(695.672 2416.666)" />
                        <path class="c" d="M47.993,320.528a1.155,1.155,0,0,1-1.037-.648,1.112,1.112,0,0,1,.43-1.512c.029-.016.058-.031.089-.044l19.255-9.855a1.16,1.16,0,0,1,1.037,2.075L48.512,320.4a1.222,1.222,0,0,1-.519.13" transform="translate(694.796 2415.231)" />
                        <path class="c" d="M52.1,328.261a1.157,1.157,0,0,1-1.038-.649,1.112,1.112,0,0,1,.43-1.512c.029-.016.058-.031.089-.044l20.163-10.308a1.162,1.162,0,0,1,1.555.518,1.11,1.11,0,0,1-.428,1.511c-.03.016-.059.031-.09.045L52.62,328.131a1.544,1.544,0,0,1-.518.13" transform="translate(694.383 2414.5)" />
                        <path class="c" d="M43.092,312.384a1.153,1.153,0,0,1-1.037-.648,1.111,1.111,0,0,1,.428-1.511c.03-.016.059-.031.09-.045l19.255-9.854a1.16,1.16,0,0,1,1.037,2.074l-19.255,9.855a.7.7,0,0,1-.519.129" transform="translate(695.288 2416.05)" />
                        <rect class="f" width="460.571" height="373.649" transform="translate(700 2490)" />
                    </g>
                </svg>
            </div>
            <h5> <?php echo (empty($msgHeading)) ? Label::getLabel('LBL_No_Result_Found!!') : $msgHeading;  ?></h5>
        <?php } ?>
        </div>
</div>
<script>
    jQuery(document).ready(function() {
        $('.countdowntimer-js').each(function(i) {
            $(this).countdowntimer({
                startDate: $(this).attr('data-startTime'),
                dateAndTime: $(this).attr('data-endTime'),
                size: "sm",
            });
        });
    });
</script>