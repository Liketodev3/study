<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php if ($classes) { ?>
 
<section class="section section--upcoming-class">
    <div class="container container--narrow">
        <div class="section__head d-flex justify-content-between align-items-center">
            <h2><?php echo Label::getLabel('LBL_Upcoming_Group_Classes', $siteLangId); ?></h2>
            <a class="view-all" href="<?php echo CommonHelper::generateUrl('GroupClasses'); ?>"><?php echo Label::getLabel("LBL_View_all",commonHelper::getLangId()); ?></a>
        </div>

        <div class="section__body">
            <div class="slider slider--onethird slider-onethird-js">

            <?php foreach($classes as $classesDetails){ ?>

                <div>
                    <div class="slider__item">
                        <div class="card card--bg">
                            <div class="card__head">
                                <h3><?php echo $classesDetails['grpcls_title']; ?></h3>
                            </div>
                            <div class="card__body">
                                <div class="card__row">
                                    <span><?php echo Label::getLabel('LBL_Date_&_Time',commonHelper::getLangId()) ?></span>
                                    <?php                                    
                                    $user_timezone = MyDate::getUserTimeZone();
                                    $date_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('M d, Y',  $classesDetails['grpcls_start_datetime'], true, $user_timezone);                                 
                                    $from_time_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A',  $classesDetails['grpcls_start_datetime'], true, $user_timezone);
                                    $to_time_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A',  $classesDetails['grpcls_end_datetime'], true, $user_timezone);                
                                    ?>
                                    <p><?php echo $date_by_user_timezone.','.$from_time_by_user_timezone.'-'. $to_time_by_user_timezone; ?></p>
                                </div>
                                <div class="card__row">
                                    <span><?php echo Label::getLabel('LBL_Tutor',commonHelper::getLangId()); ?></span>
                                    <p><?php echo  $classesDetails['user_full_name']; ?></p>
                                </div>
                                <div class="card__row">
                                    <span><?php echo Label::getLabel("LBL_Price") ?></span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="class-price"><?php echo CommonHelper::displayMoneyFormat($classesDetails['grpcls_entry_fee']) ?></p>
                                        <div class="timer">
                                            <div class="timer__media">
                                                <span> <svg class="icon icon--clock"><use xlink:href="images/sprite.yo-coach.svg#clock"></use></svg></span>
                                            </div>
                                            <div class="timer__content">
                                                <div class="timer__controls">
                                                    <div class="timer__digit">00</div>
                                                    <div class="timer__digit">06</div>
                                                    <div class="timer__digit">33</div>
                                                    <div class="timer__digit">16</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card__row--action">
                                    <a href="<?php echo CommonHelper::generateUrl('GroupClasses', 'view', array($classesDetails['grpcls_id'])); ?>" class="btn btn--bordered color-primary"><?php echo Label::getLabel('LBL_View_Details',commonHelper::getLangId()); ?></a>
                                    <a href="javascript:void(0);"  onClick="cart.add( '<?php echo $classesDetails['grpcls_teacher_id']; ?>', '0', '','','<?php echo $classesDetails['grpcls_slanguage_id']; ?>', '<?php echo $classesDetails['grpcls_id'] ?>' )" class="btn btn--primary"><?php echo Label::getLabel("LBL_Book_Now"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php  }
