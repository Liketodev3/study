<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if ($controllerName != 'GuestUser' && $controllerName != 'Error' && $controllerName != 'Teach') {
	$_SESSION['referer_page_url'] = CommonHelper::getCurrUrl();
}
?>
<!doctype html>
<html lang="en">
<head>
<!-- Basic Page Needs ======================== -->
<meta charset="utf-8">
<?php echo $this->writeMetaTags(); ?>
<!-- MOBILE SPECIFIC METAS ===================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- FONTS ================================================== -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet"> 
<link rel="canonical" href="<?php echo $canonicalUrl;?>" />

<!-- FAVICON ================================================== -->
<link rel="shortcut icon" href="<?php echo CommonHelper::generateUrl('Image', 'favicon', array($siteLangId), CONF_WEBROOT_FRONTEND); ?>">
<link rel="apple-touch-icon" href="<?php echo CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId), CONF_WEBROOT_FRONTEND); ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/apple-touch-icon-114x114.png">

<!-- CSS/JS ================================================== -->
<?php
$jsVariables = CommonHelper::htmlEntitiesDecode($jsVariables);
$sslUsed = (FatApp::getConfig('CONF_USE_SSL', FatUtility::VAR_BOOLEAN, false)) ? 1 : 0;
$closeSystemMessages = FatApp::getConfig("CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 3);
$closeSystemMessages = ($closeSystemMessages <= 0) ? 3 : $closeSystemMessages;
?>
<script type="text/javascript">
	
	var langLbl = <?php echo json_encode(CommonHelper::htmlEntitiesDecode($jsVariables)) ?>;
		var layoutDirection ='<?php echo CommonHelper::getLayoutDirection(); ?>';
		var currencySymbolLeft = '<?php echo $currencySymbolLeft; ?>';
		var currencySymbolRight = '<?php echo $currencySymbolRight; ?>';
		var SslUsed = '<?php $sslUsed; ?>';
		var cookieConsent = <?php echo json_encode($cookieConsent); ?>;
	
		const CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = '<?php echo $closeSystemMessages; ?>';
		const CONF_AUTO_CLOSE_SYSTEM_MESSAGES = '<?php echo FatApp::getConfig("CONF_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 0); ?>';

		const confWebRootUrl = '<?php echo  CONF_WEBROOT_URL; ?>';
		const confFrontEndUrl =  '<?php echo CONF_WEBROOT_FRONTEND; ?>';
		const statusUpcoming = <?php echo FatUtility::int(ScheduledLesson::STATUS_UPCOMING); ?>;
		const statusScheduled = <?php echo FatUtility::int(ScheduledLesson::STATUS_SCHEDULED); ?>;
		const statusUnscheduled = <?php echo FatUtility::int(ScheduledLesson::STATUS_NEED_SCHEDULING); ?>;
		const statusCompleted = <?php echo FatUtility::int(ScheduledLesson::STATUS_COMPLETED); ?>;
		const statusCanceled = <?php echo FatUtility::int(ScheduledLesson::STATUS_CANCELLED); ?>;
		const statusIssueReported = <?php echo FatUtility::int(ScheduledLesson::STATUS_ISSUE_REPORTED); ?>;
	
	</script>;
<?php 
	echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE);

if (isset($includeEditor) && $includeEditor) { ?>
	<script   src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/innovaeditor.js"></script>
	<script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/common/webfont.js" ></script>
<?php }

if (FatApp::getConfig('CONF_ENABLE_PWA', FatUtility::VAR_BOOLEAN, false)) { ?>
<link rel="manifest" href="<?php echo CommonHelper::generateUrl('MyApp', 'PwaManifest'); ?>">
<script>
	if ("serviceWorker" in navigator) {
		navigator.serviceWorker.register("<?php echo CONF_WEBROOT_URL; ?>sw.js");
	}
</script>
<?php } ?>
<script>
	
	
</script>


</head>

<body class="dashboard-teacher">

    <div class="site">

        <!-- [ SIDE BAR ========= -->
        <aside class="sidebar">

            <!-- [ SIDE BAR SECONDARY ========= -->
            <div class="sidebar__secondary">

                <nav class="menu menu--secondary">
                    <ul>
                        <li class="menu__item menu__item-toggle">
                            <a href="#primary-nav" class="menu__item-trigger trigger-js for-responsive" title="<?php echo Label::getLabel('LBL_Menu'); ?>">
                                <span class="icon icon--menu">
                                    <span class="toggle"><span></span></span>
                                </span>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Menu'); ?></span>
                            </a>
                            <a href="#sidebar__primary" class="menu__item-trigger fullview-js for-desktop" title="<?php echo Label::getLabel('LBL_Menu'); ?>">
                                <span class="icon icon--menu">
                                    <span class="toggle"><span></span></span>
                                </span>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Menu'); ?></span>
                            </a>
                        </li>


                        <li class="menu__item menu__item-home">
                            <a href="#" class="menu__item-trigger" title="<?php echo Label::getLabel('LBL_Home'); ?>">
                                <svg class="icon icon--home"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#home'; ?>"></use></svg>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Home'); ?></span>
                            </a>
                        </li>

                        <li class="menu__item menu__item-messaging">
                            <a href="<?php echo CommonHelper::generateUrl('Messages'); ?>" class="menu__item-trigger" title="<?php echo Label::getLabel('LBL_Messaging'); ?>" >  <!-- add  data-count="{count}" if any unread message -->
                                <svg class="icon icon--messaging"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#message'; ?>"></use></svg>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Messaging'); ?></span>
                            </a>
                        </li>

                        <li class="menu__item menu__item-notifications">
                            <a href="<?php echo CommonHelper::generateUrl('Notifications'); ?>" class="menu__item-trigger" title="<?php echo Label::getLabel('LBL_Notificatons'); ?>" > <!-- add  data-count="{count}" if any unread Notificatons -->
                                <svg class="icon icon--notificatons"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#notification'; ?>"></use></svg>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Notificatons'); ?></span>
                            </a>
                        </li>
						<?php if(!empty($websiteLangues) || !empty($currencyDat)) { ?>
                        <li class="menu__item menu__item-languages">
                            <a href="#languages-nav" class="menu__item-trigger trigger-js" title="<?php echo Label::getLabel('LBL_Languages/Currencies'); ?>">
                                <svg class="icon icon--lang"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#language'; ?>"></use></svg>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Languages/Currencies'); ?></span>
                            </a>

                            <div id="languages-nav" class="menu__dropdown">
                                <div class="menu__dropdown-head">
                                    <span class="uppercase small bold-600"><?php echo Label::getLabel('LBL_Change_Languages'); ?></span>
                                </div>
                                <div class="menu__dropdown-body">
                                    <nav class="menu menu--inline">
										<ul>
											<?php foreach ($websiteLangues as  $language) { ?>
													<li class="menu__item <?php echo ($siteLangId == $language['language_id']) ? 'is-active' : ''; ?>"><a href="javascript:void(0)" onClick="setSiteDefaultLang(<?php echo $language['language_id'];?>)"><?php echo $language['language_name']; ?></a></li>
											<?php } ?>
										</ul>
                                        <hr>
										<ul>
											<?php foreach ($currencyData as $key => $currency) { ?>
												<li class="menu__item <?php echo ($siteCurrencyId == $key) ? 'is-active' : ''; ?>"><a  onClick="setSiteDefaultCurrency(<?php echo $currencyId;?>)" href="javascript:void(0)"><?php echo  $currency; ?></a></li>
											<?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </li>
						<?php } ?>

                        <li class="menu__item menu__item-favorites">
                            <a href="#" class="menu__item-trigger" title="<?php echo Label::getLabel('LBL_Favorites'); ?>">
                                <svg class="icon icon--favorites"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#favorite'; ?>"></use></svg>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Favorites'); ?></span>
                            </a>
                        </li>



                        <li class="menu__item menu__item-logout">
                            <a href="<?php echo CommonHelper::generateUrl('GuestUser','logout',[],CONF_WEBROOT_FRONT_URL); ?>" class="menu__item-trigger" title="<?php echo Label::getLabel('LBL_Logout'); ?>">
                                <svg class="icon icon--logout"><use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#logout'; ?>"></use></svg>
                                <span class="sr-only"><?php echo Label::getLabel('LBL_Logout'); ?></span>
                            </a>
                        </li>

                    </ul>
                </nav>

            </div>
            <!-- ] -->


            <!-- [ SIDE BAR PRIMARY ========= -->
            <div id="sidebar__primary" class="sidebar__primary">

                <div class="sidebar__head">

                    <figure class="logo"><a href="#"><img src="images/yocoach-logo.svg" alt="Yo!Cocach"></a></figure>
                    

                        <!-- [ PROFILE ========= -->
                        <div class="profile">

                            <a href="#profile-target" class="trigger-js profile__trigger">
                                <div class="profile__meta d-flex align-items-center">
                                    <div class="profile__media margin-right-4">
                                        <div class="avtar" data-title="J"><img src="images/320x320_1.jpg" alt=""></div>
                                    </div>
                                    <div class="profile__details">
                                        <h6 class="profile__title">James Anderson</h6>
                                        <small class="color-black">Logged in as a <span>Teacher</span></small>
                                    </div>
                                </div>
                             </a>
                            
                            <div id="profile-target" class="profile__target">
                                <div class="profile__target-details">
                                    <table>
                                        <tr>
                                            <th>Location</th>
                                            <td>France</td>
                                        </tr>
                                        <tr>
                                            <th>Time Zone</th>
                                            <td>12:20 PM (UTC +01:00)</td>
                                        </tr>
                                    </table>
                                    <span class="-gap-10"></span>
                                    <div class="btns-group">
                                        <a href="#" class="btn btn--bordered color-third btn--block margin-top-2">View Public Profile</a>
                                        <a href="#" class="btn bg-third btn--block margin-top-4">Switch to Student Profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ] -->


                </div>


                <div class="sidebar__body">
                    <div class="sidebar__scroll">

                        <div id="primary-nav" class="menu-offset">

                            <div class="menu-group">
                                <h6 class="heading-6">Profile</h6>
                                <nav class="menu menu--primary">
                                    <ul>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--dashboard margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#dashboard"></use></svg>
                                                <span>Dashboard</span>
                                            </a>
                                        </li>
                                        <li class="menu__item is-active">
                                            <a href="teacher_settings.html">
                                                <svg class="icon icon--settings margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#settings"></use></svg>
                                                <span>Account Settings</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--calendar margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#calendar"></use></svg>
                                                <span>Calendar</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            <div class="menu-group">
                                <h6 class="heading-6">Bookings</h6>
                                <nav class="menu menu--primary">
                                    <ul>
                                        <li class="menu__item">
                                            <a href="teacher_lessons.html">
                                                <svg class="icon icon--lesson margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#lessons"></use></svg>
                                                <span>Lessons</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--lessons margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#lessons-plan"></use></svg>
                                                <span>Lesson Plan</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--group-classes margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#group-classes"></use></svg>
                                                <span>Group Classes</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--students margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#students"></use></svg>
                                                <span>Students</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            <div class="menu-group">
                                <h6 class="heading-6">History</h6>
                                <nav class="menu menu--primary">
                                    <ul>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--orders margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#orders"></use></svg>
                                                <span>Orders</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--wallet margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#wallet"></use></svg>
                                                <span>Wallet <span>($250.00)</span></span>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </nav>
                            </div>

                            <div class="menu-group">
                                <h6 class="heading-6">Others</h6>
                                <nav class="menu menu--primary">
                                    <ul>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--flash-cards margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#flashcards"></use></svg>
                                                <span>Flash Cards</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--gifts-cards margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#giftcards"></use></svg>
                                                <span>Gift Cards</span>
                                            </a>
                                        </li>
                                        <li class="menu__item">
                                            <a href="#">
                                                <svg class="icon icon--issue margin-right-2"><use xlink:href="images/sprite.yo-coach.svg#issue"></use></svg>
                                                <span>Report an Issue</span>
                                            </a>
                                        </li>
                                        
                                        
                                    </ul>
                                </nav>
                            </div>
                            
                        </div>

                    </div>
                </div>

            </div>
            <!-- ] -->


        </aside>
        <!-- ] -->