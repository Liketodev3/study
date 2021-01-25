<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class='page'>
    <div class='fixed_container'>
        <div class="row">
            <div class="space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon">
                                <i class="ion-android-star"></i></span>
                            <h5><?php echo Label::getLabel('LBL_PWA_SETTINGS', $adminLangId); ?> </h5>
                            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                    </div>
                </div>

                <section class="section">

                    <div class="sectionbody space">
                        <?php
                        $frm->setFormTagAttribute('class', 'web_form');
                        $frm->setFormTagAttribute('enctype', 'multipart/form-data');
                        $frm->setFormTagAttribute('action', CommonHelper::generateUrl('Pwa', 'setup'));

                        $frm->developerTags = [
                            'colClassPrefix' => 'col-md-',
                            'fld_default_col' => 3
                        ];

                        $frm->getField('pwa_settings[description]')->developerTags['col'] = 6;
                        $background_color_fld = $frm->getField('pwa_settings[background_color]');
                        $theme_color_fld = $frm->getField('pwa_settings[theme_color]');
                        $background_color_fld->overrideFldType('color');
                        $theme_color_fld->overrideFldType('color');
                        
                        $background_color_fld->developerTags['col'] = $theme_color_fld->developerTags['col'] = 2;

                        ?>
                        <div class="box -padding-20">
                            <?php echo $frm->getFormHtml(); ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>