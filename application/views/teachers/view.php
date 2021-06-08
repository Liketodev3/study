<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
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
                    <?php  } ?>
                    <div class="profile-detail">
                        <div class="profile-detail__head">
                            <div href="#" class="tutor-name">
                                <h4><?php echo $teacher['user_full_name']; ?></h4>
                                <div class="flag">
                                    <img src="<?php echo CONF_WEBROOT_URL . 'images/flag-new/flag-uk.png'; ?>" alt="">
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
                                <a href="javascript:void(0);"="toggleTeacherFavorite(<?php echo $teacher['user_id']; ?>,this)" class="btn btn--bordered color-black">
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
                                    <?php foreach ($teacher['teachLanguages'] as $langId => $langName) {  ?>
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
                                                            <?php foreach ($slabDetails['langPrices'] as $priceDetails) {
                                                                $onclick = '';
                                                                if ($loggedUserId != $teacher['user_id']) {
                                                                    $onclick = "cart.proceedToStep({teacherId: " . $teacher['user_id'] . ",languageId: " . $priceDetails['utl_tlanguage_id'] . ",lessonDuration: " . $priceDetails['ustelgpr_slot'] . "},'getTeacherPriceSlabs')";
                                                                }
                                                            ?>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="<?php echo $onclick; ?>">
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
                        } ?>
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
                            <b>Note:</b> Not finding your ideal time? <a href="#">Contact</a> this teacher to request a slot outside of their current schedule
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
                                <!-- [ CARAOUSEL ITEM ========= -->
                                <div>
                                    <div class="slider__item">
                                        <div class="card card--bg color-primary">
                                            <div class="card__head">
                                                <h3>Days of the Week and Months of the Year in German</h3>
                                            </div>
                                            <div class="card__body">
                                                <div class="card__row">
                                                    <span>Date & Time</span>
                                                    <p>16 April 2021, 01:00 PM - 02:00 PM</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Tutor</span>
                                                    <p>James Anderson</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Price</span>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="class-price">$22.00</p>
                                                        <div class="timer">
                                                            <div class="timer__media">
                                                                <span> <svg class="icon icon--clock">
                                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#clock'; ?>"></use>
                                                                    </svg></span>
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
                                                    <a href="#" class="btn btn--bordered color-primary">View Details</a>
                                                    <a href="#" class="btn btn--primary">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ] ========= -->

                                <!-- [ CARAOUSEL ITEM ========= -->
                                <div>
                                    <div class="slider__item">
                                        <div class="card card--bg color-primary">
                                            <div class="card__head">
                                                <h3>German for Beginners (Lower-Elementary A1.1) - Lesson 05/10</h3>
                                            </div>
                                            <div class="card__body">
                                                <div class="card__row">
                                                    <span>Date & Time</span>
                                                    <p>18 April 2021, 02:00 PM - 03:00 PM</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Tutor</span>
                                                    <p>Nathan Astle</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Price</span>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="class-price">$24.00</p>
                                                    </div>
                                                </div>
                                                <div class="card__row--action">
                                                    <a href="#" class="btn btn--bordered color-primary">View Details</a>
                                                    <a href="#" class="btn btn--primary">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ] ========= -->

                                <!-- [ CARAOUSEL ITEM ========= -->
                                <div>
                                    <div class="slider__item">
                                        <div class="card card--bg color-primary">
                                            <div class="card__head">
                                                <h3>Learning upper and lower case of the Alphabet</h3>
                                            </div>
                                            <div class="card__body">
                                                <div class="card__row">
                                                    <span>Date & Time</span>
                                                    <p>20 April 2021, 05:00 PM - 06:00 PM</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Tutor</span>
                                                    <p>Kevin Peterson</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Price</span>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="class-price">$26.00</p>
                                                    </div>
                                                </div>
                                                <div class="card__row--action">
                                                    <a href="#" class="btn btn--bordered color-primary">View Details</a>
                                                    <a href="#" class="btn btn--primary">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ] ========= -->

                                <!-- [ CARAOUSEL ITEM ========= -->
                                <div>
                                    <div class="slider__item">
                                        <div class="card card--bg color-primary">
                                            <div class="card__head">
                                                <h3>Days of the Week and Months of the Year in German</h3>
                                            </div>
                                            <div class="card__body">
                                                <div class="card__row">
                                                    <span>Date & Time</span>
                                                    <p>16 April 2021, 01:00 PM - 02:00 PM</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Tutor</span>
                                                    <p>James Anderson</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Price</span>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="class-price">$22.00</p>
                                                        <div class="timer">
                                                            <div class="timer__media">
                                                                <span> <svg class="icon icon--clock">
                                                                        <use xlink:href="images/sprite.yo-coach.svg#clock"></use>
                                                                    </svg></span>
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
                                                    <a href="#" class="btn btn--bordered color-primary">View Details</a>
                                                    <a href="#" class="btn btn--primary">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ] ========= -->

                                <!-- [ CARAOUSEL ITEM ========= -->
                                <div>
                                    <div class="slider__item">
                                        <div class="card card--bg color-primary">
                                            <div class="card__head">
                                                <h3>German for Beginners (Lower-Elementary A1.1) - Lesson 05/10</h3>
                                            </div>
                                            <div class="card__body">
                                                <div class="card__row">
                                                    <span>Date & Time</span>
                                                    <p>18 April 2021, 02:00 PM - 03:00 PM</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Tutor</span>
                                                    <p>Nathan Astle</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Price</span>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="class-price">$24.00</p>
                                                    </div>
                                                </div>
                                                <div class="card__row--action">
                                                    <a href="#" class="btn btn--bordered color-primary">View Details</a>
                                                    <a href="#" class="btn btn--primary">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ] ========= -->

                                <!-- [ CARAOUSEL ITEM ========= -->
                                <div>
                                    <div class="slider__item">
                                        <div class="card card--bg color-primary">
                                            <div class="card__head">
                                                <h3>Learning upper and lower case of the Alphabet</h3>
                                            </div>
                                            <div class="card__body">
                                                <div class="card__row">
                                                    <span>Date & Time</span>
                                                    <p>20 April 2021, 05:00 PM - 06:00 PM</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Tutor</span>
                                                    <p>Kevin Peterson</p>
                                                </div>
                                                <div class="card__row">
                                                    <span>Price</span>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="class-price">$26.00</p>
                                                    </div>
                                                </div>
                                                <div class="card__row--action">
                                                    <a href="#" class="btn btn--bordered color-primary">View Details</a>
                                                    <a href="#" class="btn btn--primary">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ] ========= -->
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3>Teaching Expertise</h3>
                    </div>

                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="content-wrapper content--tick">
                            <div class="content__head">
                                <h4>Teacher’s Accent</h4>
                            </div>
                            <div class="content__body">
                                <div class="tick-listing tick-listing--onethird">
                                    <ul>
                                        <li>Maghrebi French</li>
                                        <li>Swiss French</li>
                                        <li>Belgian French</li>
                                        <li>Indian English</li>
                                        <li>Quebec French</li>
                                        <li>Swiss Polish</li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="content-wrapper content--tick">
                            <div class="content__head">
                                <h4>Teaches Level</h4>
                            </div>
                            <div class="content__body">
                                <div class="tick-listing tick-listing--onethird">
                                    <ul>
                                        <li>(C2) Upper Advanced</li>
                                        <li>(C2) Advanced</li>
                                        <li>(C1) Expert</li>
                                        <li>(C2) Advanced</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="content-wrapper content--tick">
                            <div class="content__head">
                                <h4>Learner’s Age Group</h4>
                            </div>
                            <div class="content__body">
                                <div class="tick-listing tick-listing--onethird">
                                    <ul>
                                        <li>12 yrs - 18 yrs</li>
                                        <li>18+ yrs</li>
                                        <li>24+ yrs</li>
                                        <li>30+ yrs</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="content-wrapper content--tick">
                            <div class="content__head">
                                <h4>Lesson Includes</h4>
                            </div>
                            <div class="content__body">
                                <div class="tick-listing tick-listing--onethird">
                                    <ul>
                                        <li>Curriculum</li>
                                        <li>Quizzes /Tests</li>
                                        <li>Writing Exercises</li>
                                        <li>Proficiency Assessment</li>
                                        <li>Learning Materials</li>
                                        <li>Homework</li>
                                        <li>Lesson Plans</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="content-wrapper content--tick">
                            <div class="content__head">
                                <h4>Subjects</h4>
                            </div>
                            <div class="content__body">
                                <div class="tick-listing tick-listing--onethird">
                                    <ul>
                                        <li>Reading Comprehension</li>
                                        <li>Writing Correction</li>
                                        <li>Business French</li>
                                        <li>Work Experience</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3>Teaching Expertise</h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="row row--resume">
                            <div class="col-xl-4 col-lg-4 col-sm-4">
                                <h4 class="color-dark">Education</h4>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-sm-8">
                                <div class="resume-wrapper">
                                    <div class="resume__primary"><b>2008 - 2010</b></div>
                                    <div class="resume__secondary">
                                        <b>M.A. in French Studies </b>
                                        <p>Smith College - Northampton, MA, USA</p>
                                    </div>
                                </div>
                                <div class="resume-wrapper">
                                    <div class="resume__primary"><b>2005 - 2008</b></div>
                                    <div class="resume__secondary">
                                        <b>B.A. in French Studies </b>
                                        <p>Smith College - Northampton, MA, USA</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row--resume">
                            <div class="col-xl-4 col-lg-4 col-sm-4">
                                <h4 class="color-dark">Work Experience</h4>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-sm-8">
                                <div class="resume-wrapper">
                                    <div class="resume__primary"><b>2013 - 2015</b></div>
                                    <div class="resume__secondary">
                                        <b>English Teacher </b>
                                        <p>Centro Educacional Leonardo da Vinci - Vitoria, Brazil</p>
                                    </div>
                                </div>
                                <div class="resume-wrapper">
                                    <div class="resume__primary"><b>2010 - 2013</b></div>
                                    <div class="resume__secondary">
                                        <b>French Teacher </b>
                                        <p>Centro Educacional Leonardo da Vinci - Vitoria, Brazil</p>
                                    </div>
                                </div>
                                <div class="resume-wrapper">
                                    <div class="resume__primary"><b>2005 - 2008</b></div>
                                    <div class="resume__secondary">
                                        <b>German Teacher and Associate </b>
                                        <p>Centro Educacional Leonardo da Vinci - Vitoria, Brazil</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3>Reviews</h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="rating-details">
                            <div class="rating__count">
                                <h1>4.5</h1>
                            </div>
                            <div class="rating__info">
                                <b>Overall Ratings</b>
                                <p>120 Ratings & 115 Reviews</p>
                            </div>
                        </div>

                        <div class="reviews-wrapper">
                            <div class="reviews-wrapper__head">
                                <p>Displaying Reviews 15 of 160</p>
                                <div class="review__shorting">
                                    <select name="sort" id="sort">
                                        <option value="volvo">Sort by Newest</option>
                                        <option value="saab">Lorem</option>
                                        <option value="opel">Lorem</option>
                                        <option value="audi">Lorem</option>
                                    </select>
                                </div>

                            </div>
                            <div class="reviews-wrapper__body">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <div class="review-profile">
                                            <div class="avatar avatar-md">
                                                <img src="images/48x48.png" alt="">
                                            </div>
                                            <div class="user-info">
                                                <b>Nathan Astle</b>
                                                <p>10 April 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="review-content">
                                            <div class="review-content__head">
                                                <h6>Spanish <span>(4 Lessons)</span></h6>
                                                <div class="info-wrapper">
                                                    <div class="info-tag ratings">
                                                        <svg class="icon icon--rating">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                                        </svg>
                                                        <span class="value">4.5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content__body">
                                                <p>Steven is well prepared, attentive and professional teacher. She provides many materials and she cares a lot about her students. I receive not only very useful and well-organized lessons, but also a possibility to talk to such a nice and smart person. I would highly recommend Steven as a teacher to anybody and they can learn from anywhere.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <div class="review-profile">
                                            <div class="avatar avatar-md">
                                                <img src="images/48x48_1.png" alt="">
                                            </div>
                                            <div class="user-info">
                                                <b>James Anderson</b>
                                                <p>09 April 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="review-content">
                                            <div class="review-content__head">
                                                <h6>English<span>(6 Lessons )</span></h6>
                                                <div class="info-wrapper">
                                                    <div class="info-tag ratings">
                                                        <svg class="icon icon--rating">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                                        </svg>
                                                        <span class="value">4.3</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content__body">
                                                <p>Steven is a very patient teacher, very open to questions and ready to explain in depth to make sure you understand and create a good foundation for the language learning process. Classes are nicely structured, interactive and example-based.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <div class="review-profile">
                                            <div class="avatar avatar-md">
                                                <img src="images/48x48_2.png" alt="">
                                            </div>
                                            <div class="user-info">
                                                <b>Kevin Peterson</b>
                                                <p>08 April 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="review-content">
                                            <div class="review-content__head">
                                                <h6>German and Spanish<span>(9 Lessons)</span></h6>
                                                <div class="info-wrapper">
                                                    <div class="info-tag ratings">
                                                        <svg class="icon icon--rating">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                                        </svg>
                                                        <span class="value">4.4</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content__body">
                                                <p>Today we talked about inventions throughout history and practiced using the past tense. Steven's lessons are always interesting and I've learnt a lot since starting lessons with him.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <div class="review-profile">
                                            <div class="avatar avatar-md">
                                                <img src="images/48x48_3.png" alt="">
                                            </div>
                                            <div class="user-info">
                                                <b>Mark Boucher</b>
                                                <p>05 April 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="review-content">
                                            <div class="review-content__head">
                                                <h6>Italian <span>(8 Lessons )</span></h6>
                                                <div class="info-wrapper">
                                                    <div class="info-tag ratings">
                                                        <svg class="icon icon--rating">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                                        </svg>
                                                        <span class="value">4.5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content__body">
                                                <p>Steven was professional and friendly and instantly made me feel at ease. our first lesson was so helpful and enjoyable and I'm looking forward to having more with him in the future.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <div class="review-profile">
                                            <div class="avatar avatar-md">
                                                <img src="images/48x48_4.png" alt="">
                                            </div>
                                            <div class="user-info">
                                                <b>Damien Martyn</b>
                                                <p>02 April 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="review-content">
                                            <div class="review-content__head">
                                                <h6>Turkish and English<span>(10 Lessons )</span></h6>
                                                <div class="info-wrapper">
                                                    <div class="info-tag ratings">
                                                        <svg class="icon icon--rating">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                                        </svg>
                                                        <span class="value">4.4</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content__body">
                                                <p>I was very happy with the first lesson, Steven tested my reading comprehension, listening and spoken abilities which will help him to establish how best to help me improve my Turkish-language skills. He was patient and very friendly. I will certainly continue learning with him if He is also happy to do so.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <div class="review-profile">
                                            <div class="avatar avatar-md">
                                                <img src="images/48x48.png" alt="">
                                            </div>
                                            <div class="user-info">
                                                <b>Nathan Astle</b>
                                                <p>10 April 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="review-content">
                                            <div class="review-content__head">
                                                <h6>Spanish <span>(4 Lessons)</span></h6>
                                                <div class="info-wrapper">
                                                    <div class="info-tag ratings">
                                                        <svg class="icon icon--rating">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                                        </svg>
                                                        <span class="value">4.5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content__body">
                                                <p>Steven is well prepared, attentive and professional teacher. She provides many materials and she cares a lot about her students. I receive not only very useful and well-organized lessons, but also a possibility to talk to such a nice and smart person. I would highly recommend Steven as a teacher to anybody and they can learn from anywhere.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="reviews-wrapper__foot">
                                <div class="show-more">
                                    <a href="#" class="btn btn--show">Show More</a>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>

            </div>

            <div class="profile-secondary">
                <div class="right-panel">
                    <?php if ($teacher['us_video_link'] != '') {
                        $youTubeVideoArr = explode("?v=", $teacher['us_video_link']);
                        if (count($youTubeVideoArr) > 1) { ?>
                            <div class="dummy-video">
                                <div class="video-media ratio ratio--16by9">
                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $youTubeVideoArr[1]; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>

                            </div>
                            <div class="-gap"></div>
                    <?php }
                    } ?>


                    <div class="box box--book">
                        <div class="book__actions">
                            <a href="javascript:void(0);" class="btn btn--primary btn--xlarge btn--block color-white " onclick="cart.proceedToStep({teacherId: <?php echo $teacher['user_id']; ?>},'getUserTeachLangues');"><?php echo Label::getLabel('LBL_Book_Now', $siteLangId); ?></a>
                            <a href="#" class="btn btn--bordered btn--xlarge btn--block btn--contact color-primary">
                                <svg class="icon icon--envelope">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#envelope' ?>"></use>
                                </svg>
                                <?php echo Label::getLabel('LBL_Contact', $siteLangId); ?>
                            </a>
                            <a href="#availbility" class="color-primary btn--link scroll"><?php echo Label::getLabel('LBL_View_Full_Availbility', $siteLangId); ?></a>

                            <div class="-gap"></div>
                            <a href="#" class="btn btn--secondary btn--trial btn--block color-white "><span><?php echo Label::getLabel('LBL_BOOK_FREE', $siteLangId) ?></span> <?php echo Label::getLabel('LBL_Trial_Class', $siteLangId);  ?></a>
                            <p><?php echo Label::getLabel('LBL_Trial_Lesson_One_Time', $siteLangId); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>