<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$langId = CommonHelper::getLangId();
$websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_'.$langId, FatUtility::VAR_STRING, '');
?>
<title><?php echo Label::getLabel('LBL_Group_Class'). $class['grpcls_title'] ." ". Label::getLabel('LBL_on')." ". $websiteName; ?></title>

<section class="section section--gray section--details">
	 <div class="container container--narrow">
		<div class="breadcrumb">
			<ul>
				<li><a href="<?php echo CommonHelper::generateUrl(); ?>"><?php echo Label::getLabel('LBL_Home'); ?></a></li>
				<li><a href="<?php echo CommonHelper::generateUrl('GroupClasses'); ?>"><?php echo Label::getLabel('LBL_Group_Classes'); ?></a></li>
				<li><?php echo $class['grpcls_title']; ?></li>
			</ul>
		</div>

		<div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <div class="web-class-card web-class-detail">
                    <div class="top-card">
                        <div class="topic-wrap inline-card highlight">
                            <h4 class="topic-title"><?php echo $class['grpcls_title']; ?></h4>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-md-9">
                                <ul class="card-listing" id="cls_<?php echo $class['grpcls_id'] ?>">
                                    <li>
                                        <div class="card-type">
                                        <img src="<?php echo CONF_WEBROOT_URL ?>images/retina/bookico.svg">
                                            <span class="card-lable"><?php echo Label::getLabel("LBL_Language") ?>:</span>
                                            <span class="lable-txt"><?php echo $class['teacher_language'] ?></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="card-type">
                                            <img src="<?php echo CONF_WEBROOT_URL ?>images/retina/seats.svg">
                                            <span class="card-lable"><?php echo Label::getLabel("LBL_Available_Seats") ?>:</span>
                                            <span class="lable-txt"><?php echo $class['grpcls_max_learner']-$class['total_learners'] ?></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="card-type">
                                            <img src="<?php echo CONF_WEBROOT_URL ?>images/retina/total-seats.svg">
                                            <span class="card-lable"><?php echo Label::getLabel("LBL_Total_Seats") ?>:</span>
                                            <span class="lable-txt"><?php echo $class['grpcls_max_learner'] ?></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="card-type">
                                        <img src="<?php echo CONF_WEBROOT_URL ?>images/retina/price-ico.svg">
                                            <span class="card-lable"><?php echo Label::getLabel("LBL_Price") ?>:</span>
                                            <span class="lable-txt"><?php echo CommonHelper::displayMoneyFormat($class['grpcls_entry_fee']) ?></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="card-type">
                                        <img src="<?php echo CONF_WEBROOT_URL ?>images/retina/cal-ico.svg">
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
                                        <img src="<?php echo CONF_WEBROOT_URL ?>images/retina/time-ico.svg">
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
                            <div class="col-md-3">
                                <a href="<?php echo CommonHelper::generateUrl('Teachers', 'view', array( $class['user_url_name'])) ?>" class="teacher-card">
                                    <span class="avtar" data-text="<?php echo CommonHelper::getFirstChar($class['user_first_name']); ?>">
                                        <?php if( true == User::isProfilePicUploaded( $class['user_id'] ) ){ ?>
                                        <img src="<?php echo CommonHelper::generateUrl('Image','User', array( $class['user_id'] )) ?>" alt="">
                                        <?php } ?>
                                    </span>
                                    <span class="name"><?php echo $class['user_full_name'] ?></span>
                                </a>
                                <div class="twobtn-actions btn-wrap -align-center">
                                    <!--<a href="javascript:void(0)" class="btn btn--primary btn--medium">Interest List..Suggest New Time</a>-->
                                    <?php if($class['is_in_class']): ?>
                                    <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_ALREADY_IN_CLASS') ?>" class="btn btn--gray btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php elseif($class['total_learners']>=$class['grpcls_max_learner']): ?>
                                    <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_CLASS_FULL') ?>" class="btn disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
                                    <?php elseif($class['grpcls_start_datetime']<date('Y-m-d H:i:s', strtotime('+'.$min_booking_time. ' minutes'))): ?>
                                    <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_Booking_Close_For_This_Class') ?>" class="btn btn--disabled"><?php echo Label::getLabel("LBL_Book_Now") ?></a>
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
                </div>

                <div class="web-class-detail-desc">
                    <h4><?php echo Label::getLabel("LBL_Course_Description") ?></h4>
                    <p><?php echo $class['grpcls_description'] ?></p>
                </div>
            </div>
		</div>
	 </div>
</section>