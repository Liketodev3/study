<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script>
    const statusUpcoming = <?php echo FatUtility::int(ScheduledLesson::STATUS_UPCOMING); ?>;
    const statusScheduled = <?php echo FatUtility::int(ScheduledLesson::STATUS_SCHEDULED); ?>;
    const statusUnscheduled = <?php echo FatUtility::int(ScheduledLesson::STATUS_NEED_SCHEDULING); ?>;
    const statusCompleted = <?php echo FatUtility::int(ScheduledLesson::STATUS_COMPLETED); ?>;
    const statusCanceled = <?php echo FatUtility::int(ScheduledLesson::STATUS_CANCELLED); ?>;
    const statusIssueReported = <?php echo FatUtility::int(ScheduledLesson::STATUS_ISSUE_REPORTED); ?>;
</script>
<header class="header">
    <div class="header-primary">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header__left">
                    <a href="#" class="toggle toggle--nav toggle--nav-js">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 515.555 515.555"><path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"/><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"/><path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"/></svg>
                    </a>
                    <div class="header__logo">
                        <a href="#">
                            <img src="<?php echo CommonHelper::generateFullUrl('Image', 'siteLogo', array($siteLangId), CONF_WEBROOT_FRONT_URL); ?>" alt="">
                        </a>
                    </div>
                    <?php $this->includeTemplate('header/explore-subjects.php'); ?>
                </div>
                <div class="header__middle">                
                    <?php $this->includeTemplate('header/navigation.php'); ?>
                </div>
                <div class="header__right">
                    <?php $this->includeTemplate('header/right-section.php'); ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div id="body" class="body">
