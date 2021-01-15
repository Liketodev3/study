<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if( $classes ){ ?>
    <div class="row justify-content-center align-items-center">
	<?php foreach( $classes as $class ){ ?>
    <div class="col-md-6 grpcls">
        <div class="web-class-card -hover-shadow -transition">
            <div class="top-card">
                <div class="topic-wrap inline-card highlight">
                    <a href="<?php echo CommonHelper::generateUrl('GroupClasses', 'view', array($class['grpcls_id'])); ?>" class="topic-title"><?php echo $class['grpcls_title']; ?></a>
                </div>
                <div class="row justify-content-between">
                    <div class="col-md-8 col-sm-9">
                        <ul class="card-listing" id="cls_<?php echo $class['grpcls_id'] ?>">
                            <li>
                                <div class="card-type">
                                <img alt="" src="<?php echo CONF_WEBROOT_URL ?>images/retina/bookico.svg">
                                    <span class="card-lable"><?php echo Label::getLabel("LBL_Language") ?>:</span>
                                    <span class="lable-txt"><?php echo $class['teacher_language'] ?></span>
                                </div>
                            </li>
                            <li>
                                <div class="card-type">
                                <img alt="" src="<?php echo CONF_WEBROOT_URL ?>images/retina/price-ico.svg">
                                    <span class="card-lable"><?php echo Label::getLabel("LBL_Price") ?>:</span>
                                    <span class="lable-txt"><?php echo CommonHelper::displayMoneyFormat($class['grpcls_entry_fee']) ?></span>
                                </div>
                            </li>
                            <li>
                                <div class="card-type">
                                <img alt="" src="<?php echo CONF_WEBROOT_URL ?>images/retina/cal-ico.svg">
                                    <span class="card-lable"><?php echo Label::getLabel("LBL_Date") ?>:</span>
                                    <?php
                                    $date_by_teach_timezone = MyDate::convertTimeFromSystemToUserTimezone('M d, Y', $class['grpcls_start_datetime'], true, $class['teacher_timezone']);
                                    $user_timezone = MyDate::getUserTimeZone();
                                    $date_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('M d, Y', $class['grpcls_start_datetime'], true, $user_timezone);
                                    ?>
                                    <span class="lable-txt cls_date" rev="<?php echo $date_by_user_timezone ?>"><?php echo $date_by_teach_timezone ?></span>
                                </div>
                            </li>
                            <li class="timezone">
                                <div class="card-type">
                                <img alt="" src="<?php echo CONF_WEBROOT_URL ?>images/retina/time-ico.svg">
                                    <span class="card-lable"><?php echo Label::getLabel("LBL_Time") ?>:</span>
                                    <?php
                                    $from_time_by_teach_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A', $class['grpcls_start_datetime'], true, $class['teacher_timezone']);
                                    $to_time_by_teach_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A', $class['grpcls_end_datetime'], true, $class['teacher_timezone']);
                                    $user_timezone = MyDate::getUserTimeZone();
                                    $from_time_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A', $class['grpcls_start_datetime'], true, $user_timezone);
                                    $to_time_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A', $class['grpcls_end_datetime'], true, $user_timezone);
                                    ?>
                                    <span class="lable-txt cls_time"><?php echo $from_time_by_user_timezone.' - '.$to_time_by_user_timezone ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-3">
                        <a href="<?php echo CommonHelper::generateUrl('Teachers', 'view', array( $class['user_url_name'])) ?>" class="teacher-card">
                            <span class="avtar" data-text="<?php echo CommonHelper::getFirstChar($class['user_first_name']); ?>">
                                <?php if( true == User::isProfilePicUploaded( $class['user_id'] ) ){ ?>
                                <img src="<?php echo CommonHelper::generateUrl('Image','User', array( $class['user_id'] )) ?>" alt="">
                                <?php } ?>
                            </span>
                            <span class="name"><?php echo $class['user_full_name'] ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bottom-card -align-center">
                <a href="<?php echo CommonHelper::generateUrl('GroupClasses', 'view', array($class['grpcls_id'])); ?>" class="arrow-link"><?php echo Label::getLabel("LBL_View_Detail") ?></a>
                <div class="twobtn-actions">
                <?php if($class['is_in_class']): ?>
                <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_ALREADY_IN_CLASS') ?>" class="btn btn--gray btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                <?php elseif($class['grpcls_max_learner']>0 && $class['total_learners']>=$class['grpcls_max_learner']): ?>
                <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_CLASS_FULL') ?>" class="btn btn--gray btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                <?php elseif($class['grpcls_start_datetime']<date('Y-m-d H:i:s', strtotime('+'.$min_booking_time. ' minutes'))): ?>
                <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Booking_Close_For_This_Class') ?>" class="btn btn--gray btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                <?php elseif(UserAuthentication::isUserLogged() && $class['grpcls_teacher_id']==UserAuthentication::getLoggedUserId()): ?>
                <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Can_not_join_own_classes') ?>" class="btn btn--gray btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                <?php elseif( $class['grpcls_status']!=TeacherGroupClasses::STATUS_ACTIVE): ?>
                <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Class_Not_active') ?>" class="btn btn--gray btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                <?php else: ?>
                <a href="javascript:void(0);"  onClick="cart.add( '<?php echo $class['grpcls_teacher_id']; ?>', '0', '','','<?php echo $class['grpcls_slanguage_id']; ?>', '<?php echo $class['grpcls_id'] ?>' )" class="btn btn--primary btn--medium"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
	<?php } ?>
    </div>
    <?php
	echo FatUtility::createHiddenFormFromData ( $postedData, array (
			'name' => 'frmSearchPaging'
	) );
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
    ?>
<?php } else { ?>
	<div class="box -padding-30" style="margin-bottom: 30px;">
		<div class="message-display">
			<div class="message-display__icon">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
				<path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
				</svg>
			</div>

			<h5><?php echo Label::getLabel('LBL_No_classes_found'); ?></h5>
		</div>
	</div>
<?php } ?>
