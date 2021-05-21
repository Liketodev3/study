<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
    $hWorksdata = isset($banners['BLOCK_HOW_IT_WORKS']) ? $banners['BLOCK_HOW_IT_WORKS'] : '';
    $secondBlockdata = isset($banners['BLOCK_SECOND_AFTER_HOMESLIDER']) ? $banners['BLOCK_SECOND_AFTER_HOMESLIDER'] : '';
?>

<?php if (!empty($secondBlockdata['banners'])) { //print_r($secondBlockdata); die; 
?>
    <section class="section section--services">
        <div class="container container--narrow">
            <div class="section__head">
                <h2><?php echo Label::getLabel('LBL_Why_Us?'); ?></h2>
            </div>

            <div class="section__body">
                <div class="row">
               <?php foreach ($secondBlockdata['banners'] as $banners) { ?>
                    <div class="col-md-6">
                        <div class="service">
                            <div class="service__media">
                                <img src="<?php echo CommonHelper::generateUrl('Image', 'showBanner', array($banners['banner_id'], 0, BannerLocation::BLOCK_SECOND_AFTER_HOMESLIDER)); ?>">
                            </div>
                            <div class="service__content">
                                <h3><?php echo $banners['banner_title']; ?></h3>
                                <p><?php echo $banners['banner_description']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php } ?>                  
                </div>
            </div>
        </div>
    </section>
<?php }  ?>
<?php $this->includeTemplate('home/_partial/languagesWithTeachersCount.php'); ?>
<?php if (!empty($hWorksdata)) {
?>
    <section class="section section--gray" id="how-it-works">
        <div class="container container--fixed">
            <div class="section-title">
                <h2><?php echo $hWorksdata['blocation_name']; ?></h2>
            </div>

            <?php if ($hWorksdata['banners']) { ?>
                <div class="row justify-content-between">
                    <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12">
                        <div class="tabs-vertical tabs-js">

                            <ul>
                                <?php
                                $i = 1;
                                foreach ($hWorksdata['banners'] as $banners) {
                                ?>
                                    <li class="<?php echo ($i == 1) ? 'is-active' : ''; ?>" data-href="#tab<?php echo $i; ?>">
                                        <div class="tab-wrap">
                                            <span class="counter"></span>
                                            <div class="tab-info">
                                                <h3><?php echo $banners['banner_title']; ?></h3>
                                                <p><?php echo $banners['banner_description']; ?></p>
                                                <a href="<?php echo CommonHelper::getBannerUrl($banners['banner_btn_url']); ?>"
                                                    class="btn btn--primary"><?php echo $banners['banner_btn_caption']; ?></a>
                                            </div>
                                        </div>
                                    </li>
                                <?php $i++;
                                } ?>
                            </ul>

                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-7 col-md-12  col-sm-12 col__content">
                        <?php $i = 1;
                        foreach ($hWorksdata['banners'] as $banners) { ?>
                            <div id="tab<?php echo $i; ?>" class="tabs-content-js">
                                <div class="media"><a href="<?php echo $banners['banner_url']; ?>" target="<?php echo $banners['banner_target']; ?>"><img src="<?php echo CommonHelper::generateUrl('Image', 'showBanner', array($banners['banner_id'], 0, BannerLocation::BLOCK_HOW_IT_WORKS)); ?>" alt=""></a></div>
                            </div>
                        <?php $i++;
                        } ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </section>
<?php } ?>