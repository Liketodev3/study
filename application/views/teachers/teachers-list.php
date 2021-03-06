<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$colorClass = [
    1 => 'cell-green-40',
    2 => 'cell-green-60',
    3 => 'cell-green-80',
    4 => 'cell-green-100',
];
$hourStringLabel = Label::getLabel('LBL_{hourstring}_HRS');
?>
<?php if ($teachers) { ?>
    <div class="sorting__head">
        <div class="sorting__title">
            <h4><?php echo sprintf(Label::getLabel('LBL_Found_the_best_%s_teachers_for_you', $siteLangId), $recordCount) ?></h4>
        </div>
        <div class="sorting__box">
            <!-- <b>Sort By:</b> -->
            <select name="filterSortBy" id="sort">
                <?php $sortBy = CommonHelper::getSortbyArr(); ?>
                <?php foreach ($sortBy as $filterVal => $filterLabel) { ?>
                    <option <?php echo ($postedData['sortOrder'] == $filterVal) ? "selected='selected'" : ''; ?> value="<?php echo $filterVal; ?>"><?php echo $filterLabel; ?></option>
                <?php } ?>
            </select>
            <div class="btn--filter">
                <a href="javascript:void(0)" class="btn btn--primary btn--filters-js">
                    <span class="svg-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 402.577 402.577" style="enable-background:new 0 0 402.577 402.577;" xml:space="preserve">
                            <g>
                                <path d="M400.858,11.427c-3.241-7.421-8.85-11.132-16.854-11.136H18.564c-7.993,0-13.61,3.715-16.846,11.136
                                      c-3.234,7.801-1.903,14.467,3.999,19.985l140.757,140.753v138.755c0,4.955,1.809,9.232,5.424,12.854l73.085,73.083
                                      c3.429,3.614,7.71,5.428,12.851,5.428c2.282,0,4.66-0.479,7.135-1.43c7.426-3.238,11.14-8.851,11.14-16.845V172.166L396.861,31.413
                                      C402.765,25.895,404.093,19.231,400.858,11.427z"></path>
                            </g>
                        </svg></span>
                    <?php echo Label::getLabel('LBL_Filters', $siteLangId) ?></a></a>
            </div>
        </div>
    </div>
    <div class="listing__body">
        <div class="box-wrapper" id="teachersListingContainer">
            <?php foreach ($teachers as $teacher) { ?>
                <div class="box box-list ">
                    <div class="box__primary">
                        <div class="list__head">
                            <div class="list__media ">
                                <div class="avtar avtar--centered" data-title="<?php echo CommonHelper::getFirstChar($teacher['user_first_name']); ?>">
                                    <?php if (User::isProfilePicUploaded($teacher['user_id'])) { ?>
                                        <a href="<?php echo CommonHelper::generateUrl('teachers', 'view', [$teacher['user_url_name']]) ?>"><img src="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'User', array($teacher['user_id'], 'MEDIUM')), CONF_DEF_CACHE_TIME, '.jpg'); ?>" alt=""></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="list__price">
                                <p><?php echo CommonHelper::displayMoneyFormat($teacher['minPrice']); ?></p>
                            </div>
                        </div>
                        <div class="list__body">
                            <div class="profile-detail">
                                <div class="profile-detail__head">
                                    <a href="<?php echo CommonHelper::generateUrl('teachers', 'view', [$teacher['user_url_name']]) ?>" class="tutor-name">
                                        <h4><?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?></h4>
                                        <div class="flag">
                                            <img src="<?php echo CommonHelper::generateUrl('Image', 'countryFlag', array($teacher['user_country_id'], 'DEFAULT')); ?>" alt="">
                                        </div>
                                    </a>
                                    <div class="follow ">
                                        <a class="<?php echo ($teacher['uft_id']) ? 'is--active' : ''; ?>" onClick="toggleTeacherFavorite(<?php echo $teacher['user_id']; ?>, this)" href="javascript:void(0)">
                                            <svg class="icon icon--heart">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#heart'; ?>"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="profile-detail__body">
                                    <div class="info-wrapper">
                                        <div class="info-tag location">
                                            <svg class="icon icon--location">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#location'; ?>"></use>
                                            </svg>
                                            <span class="lacation__name"><?php echo $teacher['user_country_name']; ?></span>
                                        </div>
                                        <div class="info-tag ratings">
                                            <svg class="icon icon--rating">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#rating'; ?>"></use>
                                            </svg>
                                            <span class="value"><?php echo FatUtility::convertToType($teacher['teacher_rating'], FatUtility::VAR_FLOAT); ?></span>
                                            <span class="count"><?php echo '(' . $teacher['totReviews'] . ')'; ?></span>
                                        </div>
                                        <div class="info-tag list-count">
                                            <div class="total-count"><span class="value"><?php echo $teacher['studentIdsCnt'];  ?></span><?php echo Label::getLabel('LBL_Students', $siteLangId); ?></div> - <div class="total-count"><span class="value"><?php echo $teacher['teacherTotLessons']; ?></span><?php echo Label::getLabel('LBL_Lessons', $siteLangId);  ?></div>
                                        </div>
                                    </div>
                                    <div class="tutor-info">
                                        <div class="tutor-info__inner">
                                            <div class="info__title">
                                                <h6><?php Label::getLabel('LBL_Teaches', $siteLangId); ?>Teaches</h6>
                                            </div>
                                            <div class="info__language">
                                                <?php echo $teacher['teacherTeachLanguageName']; ?>
                                            </div>
                                        </div>
                                        <div class="tutor-info__inner">
                                            <div class="info__title">
                                                <h6><?php echo Label::getLabel('LBL_Speaks', $siteLangId); ?></h6>
                                            </div>
                                            <div class="info__language">
                                                <?php echo $teacher['spoken_language_names']; ?>
                                            </div>
                                        </div>
                                        <div class="tutor-info__inner info--about">
                                            <div class="info__title">
                                                <h6><?php echo LABEL::getLabel('LBL_About', $siteLangId); ?></h6>
                                            </div>
                                            <div class="about__detail">
                                                <p><?php echo $teacher['user_profile_info'] ?></p>
                                                <a href="<?php echo CommonHelper::generateUrl('teachers', 'view', [$teacher['user_url_name']]) ?>"><?php echo Label::getLabel('LBL_View_Profile', $siteLangId) ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list__action">
                            <div class="list__action-btn">
                                <a href="javascript:void(0);" onclick="cart.proceedToStep({teacherId: <?php echo $teacher['user_id']; ?>},'getUserTeachLangues');" class="btn btn--primary color-white btn--block"><?php echo Label::getLabel('LBL_Book_Now', $siteLangId); ?></a>
                                <a href="javascript:void(0);"onclick="generateThread(<?php echo $teacher['user_id']; ?>);" class="btn btn--bordered color-primary btn--block">
                                    <svg class="icon icon--envelope">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.yo-coach.svg#envelope'; ?>"></use>
                                    </svg>
                                    <?php echo Label::getLabel('LBL_Contact', $siteLangId); ?>
                                </a>
                            </div>
                            <a href="javascript:void(0);" onclick="viewCalendar(<?php echo $teacher['user_id']; ?>,'paid')" class="link-detail"><?php echo Label::getLabel('LBL_View_Full_availability'); ?></a>
                        </div>
                    </div>
                    <div class="box__secondary">
                        <div class="panel-box">
                            <div class="panel-box__head">
                                <ul>
                                    <li class="is--active">
                                        <a class="panel-action" content="calender" href="javascript:void(0)"><?php echo Label::getLabel('LBL_Availability', $siteLangId); ?></a>
                                    </li>
                                    <?php if (!empty($teacher['us_video_link'])) { ?>
                                        <li>
                                            <a class="panel-action" content="video" href="javascript:void(0)"><?php echo Label::getLabel('LBL_Introduction', $siteLangId); ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <div class="panel-box__body">
                                <div class="panel-content calender">
                                    <div class="custom-calendar">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th><?php echo Label::getLabel('LBL_Sun', $siteLangId); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Mon', $siteLangId); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Tue', $siteLangId); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Wed', $siteLangId); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Thu', $siteLangId); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Fri', $siteLangId); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Sat', $siteLangId); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $timeslots = $teacher['testat_timeslots'] ?? CommonHelper::getEmptyDaySlots();
                                                    // prx($timeslots);
                                                 ?>
                                                <?php foreach ($slots as $index => $slot) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="cal-cell"><?php echo $slot; ?></div>
                                                        </td>
                                                        <?php 
                                                        // prx($timeslots);
                                                        foreach ($timeslots as $day => $hours) { ?>
                                                            <?php
                                                                if (!empty($hours[$index])) {
                                                                    $hourString = Mydate::getHoursMinutes($hours[$index]);
                                                                    $hour  = str_replace(":", '.', $hourString);
                                                                    $hour = (ceil(FatUtility::float($hour)));
                                                                    $hour = ($hour == 0) ? 1 : $hour;
                                                                    $hourString = str_replace('{hourstring}', $hourString, $hourStringLabel);
                                                                }
                                                            ?>
                                                            <td class="is-hover">
                                                                <?php if (!empty($hours[$index])) { ?>
                                                                    <div class="cal-cell <?php echo $colorClass[$hour]; ?>"></div>
                                                                    <div class="tooltip tooltip--top bg-black"><?php echo $hourString; ?></div>
                                                                <?php } else { ?>
                                                                    <div class="cal-cell"></div>
                                                                <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        <a href="javascript:void(0);" onclick="viewCalendar(<?php echo $teacher['user_id']; ?>,'paid')" class="link-detail"><?php echo Label::getLabel('LBL_View_Full_availability'); ?></a>

                                    </div>
                                </div>
                                <?php if (!empty($teacher['us_video_link'])) { ?>
                                    <div class="panel-content video" style="display:none;">
                                        <iframe width="100%" height="100%" src="<?php echo $teacher['us_video_link']; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php
    echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmTeacherSearchPaging'));
    $pagingArr = ['page' => $page, 'pageCount' => $pageCount, 'recordCount' => $recordCount];
    $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
} else {
?>
    <div class="box -padding-30" style="margin-bottom: 30px;">
        <div class="message-display">
            <div class="message-display__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
                    <path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
                </svg>
            </div>
            <h5><?php echo Label::getLabel('LBL_No_Result_found!!', $siteLangId); ?></h5>
        </div>
    </div>
<?php
}
