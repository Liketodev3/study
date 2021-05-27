<?php if($allLanguages){
    ?>
        <section class="section -no-padding-top section_course_card">
            <div class="container container--mdnarrow">
                <div class="section-title">
                    <h2><?php echo Label::getLabel('Lbl_What_Language_You_want_to_learn?'); ?></h2>
                </div>
                <div class="row justify-content-center scroller--horizontal">
                <?php foreach($allLanguages as $language){
                    $languageImage = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','showLanguageImage',array($language['tlanguage_id'],'NORMAL')),CONF_IMG_CACHE_TIME, '.jpg');
                    $languageFlagImage = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','showLanguageFlagImage',array($language['tlanguage_id'],'SMALL')),CONF_IMG_CACHE_TIME, '.jpg');
                    ?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5">
                        <div class="course_card_container">
                            <figure class="course-selector-image"><img alt="" src="<?php echo $languageImage; ?>" ></figure>
                            <div class="course_flag_card">
                                <img class="flag-icon"  alt="" src="<?php echo $languageFlagImage; ?>">
                                <div class="course-info">
                                    <h4><?php echo $language['tlanguage_name'] ?></h4>
                                    <p><?php echo $language['teacherCount'].' '.Label::getLabel('Lbl_Teachers'); ?></p>
                                </div>
                            </div>
                            <a href="<?php echo CommonHelper::generateUrl('Teachers','Index', array( $language['tlanguage_id'] )) ?>" class="whole-link">
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="align-center card-more-content">
                    <?php echo Label::getLabel('Lbl_We_have_teacher_in_different_languages!'); ?>
                    <a href="<?php echo CommonHelper::generateUrl('Teachers') ?>" class="arrow-link"><?php echo Label::getLabel('Lbl_Browse_them_now!'); ?></a>
                </div>
            </div>
        </section>
<?php } ?>
