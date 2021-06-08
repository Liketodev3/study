<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="row footer--row">
    <div class="footer-group toggle-group">
        <div class="footer__group-title toggle-trigger-js">
            <h5><?php echo Label::getLabel('LBL_Languages'); ?></h5>
        </div>
        <div class="footer__group-content toggle-target-js">
            <div class="footer__group-tag">
                <?php foreach ($teachLangs as $teachLangId => $langName) { ?>
                    <div class="tags-inline__item"><a href="<?php echo CommonHelper::generateUrl('teachers', 'index', [$teachLangId]); ?>"><?php echo $langName; ?></a></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>