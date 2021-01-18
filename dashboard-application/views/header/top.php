<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script>
const statusUpcoming = <?php echo FatUtility::int(ScheduledLesson::STATUS_UPCOMING); ?>;
const statusScheduled = <?php echo FatUtility::int(ScheduledLesson::STATUS_SCHEDULED); ?>;
const statusUnscheduled = <?php echo FatUtility::int(ScheduledLesson::STATUS_NEED_SCHEDULING); ?>;
const statusCompleted = <?php echo FatUtility::int(ScheduledLesson::STATUS_COMPLETED); ?>;
const statusCanceled = <?php echo FatUtility::int(ScheduledLesson::STATUS_CANCELLED); ?>;
const statusIssueReported = <?php echo FatUtility::int(ScheduledLesson::STATUS_ISSUE_REPORTED); ?>;
</script>

<header id="header" class="header">

<?php
if(FatApp::getConfig('conf_auto_restore_on', FatUtility::VAR_INT, 1) && CommonHelper::demoUrl()) {
    $this->includeTemplate( 'restore-system/header-bar.php');
}
?>
<div class="main-bar">
    <div class="container container--fixed">

        <div class="d-flex justify-content-between align-items-center">
            <div class="header__left">
                <a href="javascript:void(0)" class="toggle toggle--nav toggle--nav-js"><span></span></a>
                <div class="header__logo -display-inline"><a href="<?php echo CommonHelper::generateUrl('', '', [], CONF_WEBROOT_FRONT_URL); ?>"><img src="<?php echo CommonHelper::generateFullUrl('Image','siteLogo',array($siteLangId), CONF_WEBROOT_FRONT_URL); ?>" alt=""></a>
                </div>
                <span class="overlay overlay--nav toggle--nav-js"></span>
                <nav class="nav nav--primary  nav--primary-offset -display-inline">
                    <ul>
                        <?php $this->includeTemplate( 'header/navigation.php'); ?>

                        <?php $this->includeTemplate( 'header/navigationMore.php'); ?>  
					</ul>
                </nav>

            </div>
            <div class="header__right">
                <div class="header__actionList -display-inline">
                    <nav class="nav nav--primary nav--actions -display-inline">
                        <ul>
							<li class="nav__dropdown -hide-mobile">
								<?php $this->includeTemplate( 'header/languageArea.php' );	?>
							</li>
							<?php if(UserAuthentication::isUserLogged()){
								$msgCnt = CommonHelper::getUnreadMsgCount();
								$unreadNotifications = CommonHelper::getUnreadNotifications(true);
								$unreadNotificationsCnt = CommonHelper::getUnreadNotifications();
							?>
                            <li>
                                <a href="<?php echo CommonHelper::generateUrl('Messages'); ?>" class="nav__dropdown-action nav__dropdown-msg">
                                    <span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 18">
                                            <path d="M1219.07,134h-18.14a2.918,2.918,0,0,0-2.93,2.892v12.216a2.918,2.918,0,0,0,2.93,2.892h18.13a2.92,2.92,0,0,0,2.94-2.892V136.9A2.916,2.916,0,0,0,1219.07,134Zm1.59,15.108a1.58,1.58,0,0,1-1.59,1.571h-18.14a1.587,1.587,0,0,1-1.59-1.571V136.9a1.587,1.587,0,0,1,1.59-1.571h18.13a1.589,1.589,0,0,1,1.6,1.571v12.211h0Zm-7.52-6.26,5.87-5.187a0.658,0.658,0,0,0,.04-0.935,0.67,0.67,0,0,0-.94-0.049l-8.1,7.16-1.58-1.39s-0.01-.01-0.01-0.015a0.728,0.728,0,0,0-.11-0.093l-6.42-5.667a0.678,0.678,0,0,0-.95.054,0.659,0.659,0,0,0,.05.935l5.94,5.231-5.91,5.457a0.658,0.658,0,0,0-.03.935,0.678,0.678,0,0,0,.49.21,0.691,0.691,0,0,0,.46-0.176l6-5.535,1.63,1.434a0.68,0.68,0,0,0,.45.166,0.642,0.642,0,0,0,.44-0.171l1.68-1.478,5.97,5.589a0.659,0.659,0,0,0,.46.181,0.662,0.662,0,0,0,.46-1.14Z" transform="translate(-1198 -134)" />
                                        </svg></span><span class="unrdMsgCnt">
                                        <?php if($msgCnt){ ?><span class="count"><?php echo $msgCnt; ?></span> <?php } ?></span>
                                </a>
                            </li>
                            <li class="nav__dropdown nav__dropdown--notification">
                                <a href="<?php echo CommonHelper::generateUrl('Notifications'); ?>" class="nav__dropdown-action nav__dropdown-msg nav__dropdown-trigger-js">
                                    <span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 22">
                                            <path d="M1294,152.24h-21v-0.462a4,4,0,0,1,3.09-3.88v-5.379a7.41,7.41,0,1,1,14.82,0V147.9a4,4,0,0,1,3.09,3.88v0.462Zm-20.05-.919h19.1a3.09,3.09,0,0,0-2.66-2.581l-0.4-.049v-6.172a6.49,6.49,0,1,0-12.98,0v6.172l-0.4.049A3.09,3.09,0,0,0,1273.95,151.321Zm8.27-15.567h-0.91V135.2a2.19,2.19,0,1,1,4.38,0v0.541h-0.91V135.2a1.28,1.28,0,1,0-2.56,0v0.55ZM1283.49,155a3.825,3.825,0,0,1-3.73-3.061l0.9-.184a2.89,2.89,0,0,0,5.68-.057l0.9,0.166A3.815,3.815,0,0,1,1283.49,155Zm-6.94-7.176h1.68v0.92h-1.68v-0.92Zm4.32,0h9.58v0.92h-9.58v-0.92Z" transform="translate(-1273 -133)" />
                                        </svg></span>
                                    <?php if(count($unreadNotificationsCnt)){ ?><span class="count"><?php echo count($unreadNotificationsCnt); ?></span> <?php } ?>
                                </a>
                                <?php if(count($unreadNotificationsCnt)){ ?>
                                <div class="nav__dropdown-target nav__dropdown-target-js -skin">
                                    <a href="javascript:void(0)" class="-link-close nav__dropdown-trigger-js -hide-desktop -show-mobile"></a>
                                    <div class="list-container">
                                        <div class="list-container__head"><?php echo Label::getLabel("Label_Notifications", CommonHelper::getLangId()); ?></div>
                                        <div class="list-container__body">
                                            <div class="list-group">
                                                <?php foreach($unreadNotifications as $notifications){ ?>
                                                <div class="list">
                                                    <div class="list__media">
                                                        <!--<div class="avtar avtar--xsmall -display-inline" data-text="L">
                                                            <img src="images/150x150.jpg" alt="">
                                                       </div>-->

                                                        <?php 	if( $notifications['noti_type'] == UserNotifications::NOTICATION_FOR_TEACHER_APPROVAL ){

echo '<div class="avtar avtar--xsmall -display-inline" data-text="A"></div>';
			}else{ ?>
                                                        <div class="avtar avtar--xsmall -display-inline" data-text="A">
                                                            <?php
				if( true == User::isProfilePicUploaded($notifications['noti_sub_record_id']) ){
                $picId = ($notifications['noti_sub_record_id']==0)?UserAuthentication::getLoggedUserId():$notifications['noti_sub_record_id'];
?>

                                                            <img src="<?php echo CommonHelper::generateUrl('Image','user',array($picId,'MINI',true),CONF_WEBROOT_FRONT_URL); ?>" alt="">
                                                            <?php 				} ?> </div>
                                                        <?php			} ?>

                                                    </div>
                                                    <div class="list__content">
                                                        <h6><?php echo $notifications['noti_title']; ?></h6>
                                                        <p><?php echo $notifications['noti_desc']; ?></p>
                                                    </div>
                                                    <a href="<?php echo CommonHelper::generateUrl('notifications','readNotification',array($notifications['noti_id'])); ?>" class="list__action"></a>
                                                </div>
                                                <?php } ?>
                                            </div>

                                        </div>
                                        <div class="list-container__footer">
                                            <a href="<?php echo CommonHelper::generateUrl('Notifications'); ?>" class="btn btn--secondary btn--block"><?php echo Label::getLabel("LBL_View_all"); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </li>
                            <?php } ?>
							<?php $this->includeTemplate( 'header/userLoginArea.php' );	?>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    </div>
</header>


<div id="body" class="body">
