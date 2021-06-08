<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
if (!empty($teacher['teachLanguages'])) {
    $teacherLanguage = key($teacher['teachLanguages']);
}
$langId = CommonHelper::getLangId();
$websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId, FatUtility::VAR_STRING, '');
$teacherLangPrices = [];
$bookingDuration = '';
foreach ($userTeachLangs as $key => $value) {
    if (!array_key_exists($value['utl_tlanguage_id'], $teacherLangPrices)) {
        $teacherLangPrices[$value['utl_tlanguage_id']] = [];
    }
    $slotSlabKey = $value['ustelgpr_min_slab'] . '-' . $value['ustelgpr_max_slab'];
    if (!array_key_exists($slotSlabKey, $teacherLangPrices[$value['utl_tlanguage_id']])) {
        $teacherLangPrices[$value['utl_tlanguage_id']][$slotSlabKey] = [
            'title' => sprintf(Label::getLabel('LBL_[%s_-_%s]_Lessons'), $value['ustelgpr_min_slab'], $value['ustelgpr_max_slab']),
            'lang_name' => $value['teachLangName'],
            'langPrices' => []
        ];
    }
    $price = FatUtility::float($value['ustelgpr_price']);
    $percentage = CommonHelper::getPercentValue($value['top_percentage'], $price);
    $price = $price - $percentage;
    $teacherLangPrices[$value['utl_tlanguage_id']][$slotSlabKey]['langPrices'][] = [
        'teachLangName' => $value['teachLangName'],
        'ustelgpr_slot' => $value['ustelgpr_slot'],
        'ustelgpr_max_slab' => $value['ustelgpr_max_slab'],
        'ustelgpr_min_slab' => $value['ustelgpr_min_slab'],
        'teachLangName' => $value['teachLangName'],
        'utl_tlanguage_id' => $value['utl_tlanguage_id'],
        'ustelgpr_price' => $price,
        'top_percentage' => $value['top_percentage'],
    ];
}
?>
<section class="section section--profile">
    <div class="container container--fixed">
        <div class="profile-cover">
            <div class="profile-head">
                <div class="detail-wrapper">
                    <?php if (true == User::isProfilePicUploaded($teacher['user_id'])) { ?>
                        <div class="profile__media">
                            <div class="avatar avatar-xlarge ratio ratio--1by1">
                                <img src="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'User', array($teacher['user_id'], 'MEDIUM')), CONF_DEF_CACHE_TIME, '.jpg'); ?>" alt="">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="profile-detail">
                        <div class="profile-detail__head">
                            <div href="#" class="tutor-name">
                                <h4><?php echo $teacher['user_full_name']; ?></h4>
                                <div class="flag">
                                    <img src="<?php echo CONF_WEBROOT_URL; ?>images/flag-new/flag-uk.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="profile-detail__body">
                            <div class="info-wrapper">
                                <div class="info-tag location">
                                    <svg class="icon icon--location">
                                    <use xlink:href=" <?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#location' ?>"></use>
                                    </svg>
                                    <span class="lacation__name"><?php echo ($teacher['user_state_name'] != '') ? $teacher['user_state_name'] . ', ' : ''; ?> <?php echo $teacher['user_country_name']; ?></span>
                                </div>
                                <div class="info-tag ratings">
                                    <svg class="icon icon--rating">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating' ?>"></use>
                                    </svg>
                                    <span class="value"><?php echo FatUtility::convertToType($reviews['prod_rating'], FatUtility::VAR_FLOAT); ?></span>
                                    <span class="count"><?php echo '(' . FatUtility::int($reviews['totReviews']) . ')'; ?></span>
                                </div>
                                <div class="info-tag list-count">
                                    <div class="total-count"><span class="value"><?php echo $teacher['studentIdsCnt']; ?></span><?php echo Label::getLabel('LBL_Students', $siteLangId) ?></div> - <div class="total-count"><span class="value"><?php echo $teacher['teacherTotLessons']; ?></span><?php echo Label::getLabel('LBL_Lessons', $siteLangId); ?></div>
                                </div>
                            </div>
                            <div class="har-rate"><?php echo Label::getLabel('LBL_Hourly_Rate'); ?><b><?php echo CommonHelper::displayMoneyFormat($teacher['minPrice']); ?> - <?php echo CommonHelper::displayMoneyFormat($teacher['maxPrice']); ?></b></div>
                            <div class="tutor-lang"><b><?php echo Label::getLabel('LBL_Teaches:'); ?></b> <?php echo implode(', ', $teacher['teachLanguages']); ?></div>
                            <div class="detail-actions">
                                <a href="javascript:void(0)" onClick="toggleTeacherFavorite(<?php echo $teacher['user_id']; ?>, this)" class="btn btn--bordered color-black">
                                    <svg class="icon icon--heart">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#heart'; ?>"></use>
                                    </svg>
                                    <?php echo Label::getLabel('LBL_Favorite'); ?>
                                </a>
                                <div class="toggle-dropdown">
                                    <a href="#" class="btn btn--bordered color-black toggle-dropdown__link-js">
                                        <svg class="icon icon--share">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#share'; ?>"></use>
                                        </svg>
                                        <?php echo Label::getLabel('LBL_Share', $siteLangId); ?>
                                    </a>
                                    <div class="toggle-dropdown__target toggle-dropdown__target-js">
                                        <h6><?php echo Label::getLabel('LBL_Share_On', $siteLangId); ?></h6>
                                        <ul class="social--share clearfix">
                                            <li class="social--fb"><span class="st_facebook_large" displaytext="Facebook" st_processed="yes"><img alt="" src="/images/social_01.svg"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;https://ws.sharethis.com/images/2017/facebook_32.png&quot;);"></span></span></span></li>
                                            <li class="social--tw"><span class="st_twitter_large" displaytext="Tweet" st_processed="yes"><img alt="" src="/images/social_02.svg"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;https://ws.sharethis.com/images/2017/twitter_32.png&quot;);"></span></span></span></li>
                                            <li class="social--pt"><span class="st_pinterest_large" displaytext="Pinterest" st_processed="yes"><img alt="" src="/images/social_05.svg"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;https://ws.sharethis.com/images/2017/pinterest_32.png&quot;);"></span></span></span></li>
                                            <li class="social--mail"><span class="st_email_large" displaytext="Email" st_processed="yes"><img alt="" src="/images/social_06.svg"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;https://ws.sharethis.com/images/2017/email_32.png&quot;);"></span></span></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <a href="#lessons-prices" class="color-primary btn--link scroll"><?php Label::getLabel('LBL_View_Lessons_Packages', $siteLangId); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-primary">
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_About', $siteLangId); ?> <?php echo $teacher['user_full_name']; ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="content__row">
                            <p><?php echo nl2br($teacher['user_profile_info']); ?></p>
                        </div>
                        <div class="content__row">
                            <h4><?php echo Label::getLabel('LBL_Speaks', $siteLangId); ?></h4>
                            <p><?php $this->includeTemplate('teachers/_partial/spokenLanguages.php', $teacher, false); ?></p>
                        </div>
                    </div>
                </div>
                <div class="panel-cover" id="lessons-prices">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Lessons_Prices', $siteLangId); ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="panel-head__right">
                            <label><?php echo Label::getLabel('LBL_Select_Language'); ?></label>
                            <div class="select--box">
                                <select name="teachLanguages" id="teachLang">
                                    <?php foreach ($teacher['teachLanguages'] as $langId => $langName) { ?>
                                        <option value="<?php echo $langId; ?>"><?php echo $langName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php $i = 1; ?>
                        <?php foreach ($teacherLangPrices as $teachLangId => $teachLangPriceSlabs) { ?>
                            <div <?php echo (($i != 1) ? "style='display:none'" : "") ?> data-lang-id="<?php echo $teachLangId; ?>" class="slider slider--onethird slider--prices slider-onethird-js">
                                <?php foreach ($teachLangPriceSlabs as $slab => $slabDetails) { ?>
                                    <div>
                                        <div class="slider__item">
                                            <div class="card">
                                                <div class="card__head">
                                                    <div class="card__title">
                                                        <h4 class="color-primary"><?php echo $slabDetails['title']; ?></h4>
                                                    </div>
                                                </div>
                                                <div class="card__body">
                                                    <div class="lesson-list">
                                                        <ul>
                                                            <?php foreach ($slabDetails['langPrices'] as $priceDetails) { ?>
                                                                <li>
                                                                    <a href="#">
                                                                        <div class="lesson lesson--time"><?php echo $priceDetails['ustelgpr_slot'] . ' ' . Label::getLabel('LBL_Mins', $siteLangId); ?></div>
                                                                        <div class="lesson lesson--price"><?php echo CommonHelper::displayMoneyFormat($priceDetails['ustelgpr_price']); ?></div>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>
                <div class="panel-cover" id="availbility">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Schdule', $siteLangId) ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="calendar-wrapper">
                            <div class="calendar-wrapper__body">
                                <div class="calender__media">
                                    <img src="images/calendar_new.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="-gap"></div>
                        <div class="alert alert--attention alert--small alert--note" role="alert">
                            <b><?php echo Label::getLabel('LBL_Note', $siteLangId) ?></b><?php echo Label::getLabel('LBL_Not_finding_your_ideal_time', $siteLangId); ?> 
                            <a onClick="generateThread(<?php echo $teacher['user_id']; ?>)" href="javascript:void(0)"><?php echo Label::getLabel('LBL_Contact', $siteLangId); ?></a>
                            this teacher to request a slot outside of their current schedule
                        </div>
                    </div>
                </div>
                <?php if (count($groupClasses) > 0) { ?>
                    <div class="panel-cover">
                        <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                            <h3><?php echo Label::getLabel('LBL_Group_Classes', $siteLangId); ?></h3>
                        </div>
                        <div class="panel-cover__body panel__body-target panel__body-target-js">
                            <div class="slider slider--onethird silder--group-class slider-onethird-js">
                                <?php
                                foreach ($groupClasses as $key => $classDetails) {
                                    $user_timezone = MyDate::getUserTimeZone();
                                    $startTime = MyDate::convertTimeFromSystemToUserTimezone('M-d-Y H:i:s', $classDetails['grpcls_start_datetime'], true, $user_timezone);
                                    $curDateTime = MyDate::convertTimeFromSystemToUserTimezone('Y/m/d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
                                    $startUnixTime = strtotime($startTime);
                                    $currentUnixTime = strtotime($curDateTime);
                                    ?>
                                    <div>
                                        <div class="slider__item">
                                            <div class="card card--bg color-primary">
                                                <div class="card__head">
                                                    <h3><?php echo $classDetails['grpcls_title']; ?></h3>
                                                </div>
                                                <div class="card__body">
                                                    <div class="card__row">
                                                        <?php
                                                        $user_timezone = MyDate::getUserTimeZone();
                                                        $date_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('M d, Y', $classDetails['grpcls_start_datetime'], true, $user_timezone);
                                                        $from_time_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A', $classDetails['grpcls_start_datetime'], true, $user_timezone);
                                                        $to_time_by_user_timezone = MyDate::convertTimeFromSystemToUserTimezone('h:i A', $classDetails['grpcls_end_datetime'], true, $user_timezone);
                                                        ?>
                                                        <span><?php echo Label::getLabel('LBL_Date_&_Time', $siteLangId) ?></span>
                                                        <p><?php echo $date_by_user_timezone . ',' . $from_time_by_user_timezone . '-' . $to_time_by_user_timezone; ?></p>
                                                    </div>
                                                    <div class="card__row">
                                                        <span><?php echo Label::getLabel('LBL_Tutor', $siteLangId); ?></span>
                                                        <p><?php echo $classDetails['user_full_name']; ?></p>
                                                    </div>
                                                    <div class="card__row">
                                                        <span><?php echo Label::getLabel("LBL_Price", $siteLangId) ?></span>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="class-price"><?php echo CommonHelper::displayMoneyFormat($classDetails['grpcls_entry_fee']) ?></p>
                                                            <div class="timer">
                                                                <?php if ($startUnixTime > $currentUnixTime) { ?>
                                                                    <div class="timer__media">
                                                                        <span> <svg class="icon icon--clock">
                                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#clock' ?>"></use>
                                                                            </svg></span>
                                                                    </div>
                                                                    <div class="timer__controls countdowntimer timer-js" id="grup-class_<?php echo $key; ?>" data-startTime="<?php echo $curDateTime; ?>" data-endTime="<?php echo date('Y/m/d H:i:s', $startUnixTime); ?>"></div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card__row--action">
                                                        <a href="<?php echo CommonHelper::generateUrl('GroupClasses', 'view', array($classDetails['grpcls_id'])); ?>" class="btn btn--bordered color-primary"><?php echo Label::getLabel('LBL_View_Details', commonHelper::getLangId()); ?></a>
                                                        <a href="javascript:void(0);" onClick="cart.add('<?php echo $classesDetails['grpcls_teacher_id']; ?>', '0', '', '', '<?php echo $classDetails['grpcls_slanguage_id']; ?>', '<?php echo $classesDetails['grpcls_id'] ?>')" class="btn btn--primary"><?php echo Label::getLabel("LBL_Book_Now"); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Teaching_Expertise', $siteLangId) ?></h3>
                    </div>
                    <?php foreach ($teacher['preferences'] as $prefDetail) { ?>
                        <div class="panel-cover__body panel__body-target panel__body-target-js">
                            <div class="content-wrapper content--tick">
                                <div class="content__head">
                                    <h4><?php echo $preferencesTypeArr[$prefDetail['preference_type']] ?></h4>
                                </div>
                                <div class="content__body">
                                    <div class="tick-listing tick-listing--onethird">
                                        <ul>
                                            <?php foreach (explode(',', $prefDetail['preference_titles']) as $prefName) { ?>
                                                <li><?php echo $prefName ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Teaching_Qualifications', $siteLangId); ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js" id="qualificationsList">
                    </div>
                </div>
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Reviews', $siteLangId); ?></h3>
                    </div>
                    <?php echo $frmReviewSearch->getFormHtml(); ?>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="rating-details">
                            <div class="rating__count">
                                <h1><?php echo FatUtility::convertToType($reviews['prod_rating'], FatUtility::VAR_FLOAT); ?></h1>
                            </div>
                            <div class="rating__info">
                                <b><?php echo Label::getLabel('LBL_Overall_Ratings', $siteLangId); ?></b>
                            </div>
                        </div>
                        <div class="reviews-wrapper">
                            <div class="reviews-wrapper__head">
                                <p id="recordToDisplay"></p>
                                <div class="review__shorting">
                                    <select name="orderBy" id="sort">
                                        <?php foreach ($sortArr as $sortKey => $sortValue) { ?>
                                            <option value="<?php echo $sortKey; ?>"><?php echo $sortValue; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="itemRatings" class="reviews-wrapper__body">
                            </div>
                            <div class="reviews-wrapper__foot">
                                <div class="show-more">
                                    <a href="javascript:void(0);" class="btn btn--show"><?php echo Label::getLabel('Lbl_SHOW_MORE'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-secondary">
                <div class="right-panel">
                    <?php
                    if ($teacher['us_video_link'] != '') {
                        $youTubeVideoArr = explode("?v=", $teacher['us_video_link']);
                        if (count($youTubeVideoArr) > 1) {
                            ?>
                            <div class="dummy-video">
                                <div class="video-media ratio ratio--16by9">
                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $youTubeVideoArr[1]; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="-gap"></div>
                            <?php
                        }
                    }
                    ?>
                    <div class="box box--book">
                        <div class="book__actions">
                            <a href="javascript:void(0);" class="btn btn--primary btn--xlarge btn--block color-white " onclick="cart.getTeachLangues(<?php echo $teacher['user_id']; ?>);"><?php echo Label::getLabel('LBL_Book_Now', $siteLangId); ?></a>
                            <a href="#" class="btn btn--bordered btn--xlarge btn--block btn--contact color-primary">
                                <svg class="icon icon--envelope">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#envelope' ?>"></use>
                                </svg>
                                <?php echo Label::getLabel('LBL_Contact', $siteLangId); ?>
                            </a>
                            <a href="#availbility" class="color-primary btn--link scroll"><?php echo Label::getLabel('LBL_View_Full_Availbility', $siteLangId); ?></a>
                            <div class="-gap"></div>
                            <a href="#" class="btn btn--secondary btn--trial btn--block color-white "><span><?php echo Label::getLabel('LBL_BOOK_FREE', $siteLangId) ?></span> <?php echo Label::getLabel('LBL_Trial_Class', $siteLangId); ?></a>
                            <p><?php echo Label::getLabel('LBL_Trial_Lesson_One_Time', $siteLangId); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        searchQualifications(<?php echo $teacher['user_id']; ?>);
        viewCalendar(<?php echo $teacher['user_id'] . ',' . $siteLangId . ', "paid"'; ?>);
    });
</script>
