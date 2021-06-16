<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$applyTeachFrm->setFormTagAttribute('class', 'form');
$applyTeachFrm->setFormTagAttribute('onsubmit', 'setUpSignUp(this); return(false);');
$applyTeachFrm->getField('user_email')->setFieldTagAttribute('placeholder', Label::getLabel('LBL_Email', $siteLangId));
$applyTeachFrm->getField('user_password')->setFieldTagAttribute('placeholder', Label::getLabel('LBL_Password', $siteLangId));
$applyTeachFrm->getField('btn_submit')->setFieldTagAttribute('class', 'btn btn--secondary btn--large btn--block ');
$applyTeachFrm->developerTags['colClassPrefix'] = 'col-md-';
$applyTeachFrm->developerTags['fld_default_col'] = 12;
?>
<section class="section padding-0">

    <div class="slideshow full-view-banner">
        <picture class="hero-img">
            <img src="<?php echo CommonHelper::generateUrl('image', 'applyToTeachBanner', [$siteLangId], CONF_WEBROOT_URL); ?>" alt="">
        </picture>
    </div>
    <?php if (UserAuthentication::getLoggedUserId(true) && User::isTeacher()) { ?>
        <div class="slideshow-content">
            <h1><?php echo Label::getLabel('LBL_Apply_To_Teach', $siteLangId); ?></h1>
            <p><?php echo Label::getLabel('LBL_Apply_to_Teach_Descritpion', $siteLangId);  ?></p>

            <div class="row justify-content-center margin-top-4">
                <p><?php echo Label::getLabel('LBL_Faqs_Description', $siteLangId); ?></p>
            </div>
            <div class="row">
                <div class="col-6">
                    <a href="#faq-area" class="btn btn--block btn--white scroll">
                        <?php echo Label::getLabel('LBL_FAQS', $siteLangId); ?>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#how-it-works" class="btn btn--block btn--white scroll ">
                        <?php echo Label::getLabel('LBL_How_IT_Works', $siteLangId); ?>
                    </a>
                </div>
            </div>

        </div>
    <?php } elseif (UserAuthentication::getLoggedUserId(true) && User::isLearner()) { ?>
        <div class="slideshow-content">
            <h1><?php echo Label::getLabel('LBL_Apply_To_Teach', $siteLangId); ?></h1>
            <p><?php echo Label::getLabel('LBL_Apply_to_Teach_Descritpion', $siteLangId);  ?></p>
            <a href="<?php echo CommonHelper::generateUrl('TeacherRequest', 'form'); ?>" class="btn btn--secondary btn--large btn--block "><?php echo Label::getLabel('LBL_BECOME_A_TUTOR', $siteLangId); ?></a>
            <div class="row justify-content-center margin-top-4">
                <p><?php echo Label::getLabel('LBL_Faqs_Description', $siteLangId); ?></p>
            </div>
            <div class="row">
                <div class="col-6">
                    <a href="#faq-area" class="btn btn--block btn--white scroll">
                        <?php echo Label::getLabel('LBL_FAQS', $siteLangId); ?>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#how-it-works" class="btn btn--block btn--white scroll ">
                        <?php echo Label::getLabel('LBL_How_IT_Works', $siteLangId); ?>
                    </a>
                </div>
            </div>

        </div>

    <?php } else { ?>
        <div class="slideshow-content">
            <h1><?php echo Label::getLabel('LBL_Apply_To_Teach', $siteLangId); ?></h1>
            <p><?php echo Label::getLabel('LBL_Apply_to_Teach_Descritpion', $siteLangId);  ?></p>
            <div class="form-register">
                <?php echo $applyTeachFrm->getFormHtml(); ?>
            </div>
        </div>
    <?php }  ?>

</section>
<?php echo FatUtility::decodeHtmlEntities($sectionAfterBanner); ?>
<?php echo FatUtility::decodeHtmlEntities($featuresSection); ?>
<?php echo FatUtility::decodeHtmlEntities($becometutorSection); ?>
<?php echo FatUtility::decodeHtmlEntities($staticBannerSection); ?>
<?php if (!empty($faqs)) { ?>
    <section class="section section--faq" id="faq-area">
        <div class="container container--narrow">
            <div class="section__head">
                <h2><?php echo Label::getLabel('LBL_faq_title_second', $siteLangId); ?></h2>
            </div>
            <div class="faq-cover">
                <div class="faq-container">
                    <?php foreach ($faqs as $ques) { ?>
                        <div class="faq-row faq-group-js">
                            <a href="javascript:void(0)" class="faq-title faq__trigger faq__trigger-js">
                                <h5><?php echo $ques['faq_title']; ?></h5>
                            </a>
                            <div class="faq-answer faq__target faq__target-js">
                                <p><?php echo $ques['faq_description']; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php $this->includeTemplate('_partial/contact-us-section.php', ['siteLangId' => $siteLangId]); ?>
<?php } ?>
<script>
    $(".faq__trigger-js").click(function(e) {
        e.preventDefault();
        if ($(this).parents('.faq-group-js').hasClass('is-active')) {
            $(this).siblings('.faq__target-js').slideUp();
            $('.faq-group-js').removeClass('is-active');
        } else {
            $('.faq-group-js').removeClass('is-active');
            $(this).parents('.faq-group-js').addClass('is-active');
            $('.faq__target-js').slideUp();
            $(this).siblings('.faq__target-js').slideDown();
        }
    });
</script>