<?php defined('SYSTEM_INIT') or die('Invalid Usage'); ?>

<?php if ($cPage['cpage_layout'] == Contentpage::CONTENT_PAGE_LAYOUT1_TYPE) { ?>

    <section class="banner banner--main">

        <div class="banner__media"><img src="<?php echo CommonHelper::generateUrl('image', 'cpageBackgroundImage', array($cPage['cpage_id'], $siteLangId, '', 0, false), CONF_WEBROOT_URL); ?>" alt="" /></div>
        <div class="banner__content banner__content--centered">
            <h1><?php echo $cPage['cpage_image_title']; ?></h1>
            <p><?php echo $cPage['cpage_image_content']; ?></p>
            <?php if (($teacherRequestStatus == null || $teacherRequestStatus == TeacherRequest::STATUS_CANCELLED)) { ?>
                <a href="javascript:void(0)" onclick="signUpFormPopUp('teacher')" class="btn btn--primary btn--large"><?php echo Label::getLabel('LBL_Start_Teaching'); ?></a>
            <?php } ?>
        </div>
    </section>
    <?php
    if ($blockData) { ?>
        <div class="container--cms">
            <?php if (isset($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_1])  && $blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_1]['cpblocklang_text']) { ?>
                <section class="space">
                    <div class="fixed-container">
                        <?php echo FatUtility::decodeHtmlEntities($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_1]['cpblocklang_text']); ?>
                    </div>
                </section>
            <?php }
            if (isset($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_2])  && $blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_2]['cpblocklang_text']) { ?>
                <section class="space bg--gray">
                    <div class="fixed-container">
                        <?php echo FatUtility::decodeHtmlEntities($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_2]['cpblocklang_text']); ?>
                    </div>
                </section>
            <?php }
            if (isset($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_3])  && $blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_3]['cpblocklang_text']) { ?>
                <section class="space bg--second">
                    <div class="fixed-container">
                        <?php echo FatUtility::decodeHtmlEntities($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_3]['cpblocklang_text']); ?>
                    </div>
                </section>
            <?php }
            if (isset($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_4])  && $blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_4]['cpblocklang_text']) { ?>
                <section class="space">
                    <div class="fixed-container">
                        <?php echo FatUtility::decodeHtmlEntities($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_4]['cpblocklang_text']); ?>
                    </div>
                </section>
            <?php }
            if (isset($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_5])  &&  $blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_5]['cpblocklang_text']) { ?>
                <section class="space">
                    <div class="divider"></div>
                    <div class="fixed-container">

                        <?php echo FatUtility::decodeHtmlEntities($blockData[Contentpage::CONTENT_PAGE_LAYOUT1_BLOCK_5]['cpblocklang_text']); ?>
                    </div>
                </section>
            <?php } ?>



        </div>
    <?php
    }
    ?>
<?php } else { ?>

    <?php /*if(!$isAppUser){?>
<div class="breadcrumb">
    <?php //$this->includeTemplate('_partial/custom/header-breadcrumb.php'); ?>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="heading3"><?php echo $cPage['cpage_title']; ?></div>
    </div>
</div><?php }*/ ?>

    <section class="section section--gray section--page">
        <div class="container container--narrow">
            <div class="section__head">
                <h2><?php echo $cPage['cpage_title']; ?></h2>
            </div>
            <div class="section__body">
                <div class="box -padding-30">
                    <div class="cms-container">
                        <?php echo FatUtility::decodeHtmlEntities($cPage['cpage_content']) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<script>
    /* for faq toggles */
    $(".accordian__body-js").hide();
    $(".accordian__body-js:first").show();

    $(".accordian__title-js").click(function() {
        if ($(this).parents('.accordian-js').hasClass('is-active')) {
            $(this).siblings('.accordian__body-js').slideUp();
            $('.accordian-js').removeClass('is-active');
        } else {
            $('.accordian-js').removeClass('is-active');
            $(this).parents('.accordian-js').addClass('is-active');
            $('.accordian__body-js').slideUp();
            $(this).siblings('.accordian__body-js').slideDown();
        }
    });
</script>
</div><!-- div id=body class=body ends here -->