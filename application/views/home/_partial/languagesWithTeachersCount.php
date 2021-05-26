<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if($allLanguages){ ?>

<section class="section section--language">
    <div class="container container--narrow">
        <div class="section__head">
            <h2><?php echo Label::getLabel('Lbl_What_Language_You_want_to_learn?',$siteLangId); ?></h2>
        </div>

        <div class="section__body">
            <div class="flag-wrapper">
            <?php foreach($allLanguages as $language){ ?>
                <div class="flag__box">
                    <div class="flag__media">
                        <img src="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image','showLanguageFlagImage',array($language['tlanguage_id'],'SMALL')),CONF_IMG_CACHE_TIME, '.jpg'); ?>" alt="">
                    </div>
                    <div class="flag__name">
                        <span><?php echo $language['tlanguage_name'] ?></span>
                        <div class="lesson-count"><?php echo $language['teacherCount'].' '.Label::getLabel('Lbl_Teachers',$siteLangId); ?></div>
                    </div>
                    <a class="flag__action" href="<?php echo CommonHelper::generateUrl('Teachers','Index', array( $language['tlanguage_id'])); ?>"></a>
                </div>
                <?php } ?>
        
            </div>
            <div class="more-info align-center">
                <p><?php echo Label::getLabel("LBL_different_language_note",$siteLangId); ?> <a href="<?php echo CommonHelper::generateUrl('teachers','index'); ?>"><?php echo Label::getLabel('LBL_Browse_them_now',$siteLangId); ?></a></p>
            </div>
            </div>
    
        </div>
    </section>
<?php } ?>
