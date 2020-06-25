<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$hWorksdata = isset($banners['BLOCK_HOW_IT_WORKS'])?$banners['BLOCK_HOW_IT_WORKS']:'';
$secondBlockdata = isset($banners['BLOCK_SECOND_AFTER_HOMESLIDER'])?$banners['BLOCK_SECOND_AFTER_HOMESLIDER']:'';
$firstBlockdata = isset($banners['BLOCK_FIRST_AFTER_HOMESLIDER'])?$banners['BLOCK_FIRST_AFTER_HOMESLIDER']:''; ?>
<?php /* if(!empty($firstBlockdata['banners'])){ //print_r($secondBlockdata); die;?>
<section class="section section--content">
	<div class="container container--narrow">
                        <?php  $i = 1; foreach($firstBlockdata['banners'] as $banners) {
                        $cls = ($i%2==1)?'order-md-1':'';
                        ?>
		<div class="row row--custom justify-content-between">
			<div class="col-xl-5 col-sm-7 col--first <?php echo $cls; ?>">
			<div class="icon"><img src="<?php echo CONF_WEBROOT_URL; ?>images/icon_1.svg" alt=""></div>
			<h2><?php echo $banners['banner_title']; ?></h2>
			<p><?php echo $banners['banner_description']; ?></p>
			</div>
			<div class="col-xl-5 col-sm-5 col--second">
			<div class="media-group">
			<div class="media"><a href="<?php echo $banners['banner_url']; ?>" target="<?php echo $banners['banner_target']; ?>" ><img src="<?php echo CommonHelper::generateUrl('Image','showBanner',array($banners['banner_id'], 0, BannerLocation::BLOCK_FIRST_AFTER_HOMESLIDER)); ?>" alt=""></a></div>
			<div class="media media--small"><a href="<?php echo $banners['banner_url']; ?>" target="<?php echo $banners['banner_target']; ?>" ><img src="<?php echo CommonHelper::generateUrl('Image','showBanner',array($banners['banner_id'], 0,BannerLocation::BLOCK_FIRST_AFTER_HOMESLIDER, true)); ?>" alt=""></a></div>
			</div>
			</div>
		</div>
                        <?php $i++; }?>
	</div>
</section>
<?php } */ ?>
<?php /* if(!empty($secondBlockdata['banners'])){ //print_r($secondBlockdata); die;
$bannerDet = current($secondBlockdata['banners']); ?>

<section class="section section--gray">
	<div class="container container--fixed">
		<div class="row align-items-center justify-content-between">
			<div class="col-xl-6 col-lg-6">
				<div class="media"><a href="<?php echo $bannerDet['banner_url']; ?>" target="<?php echo $bannerDet['banner_target']; ?>" ><img src="<?php echo CommonHelper::generateUrl('Image','showBanner',array($bannerDet['banner_id'], 0, BannerLocation::BLOCK_SECOND_AFTER_HOMESLIDER)); ?>" alt=""></a></div>
			</div>
			<div class="col-xl-5 col-lg-6">
				<h2><?php echo $bannerDet['banner_title']; ?></h2>
				<p><?php echo $bannerDet['banner_description']; ?></p>
                <?php if($bannerDet['banner_btn_caption']){ ?>
				<a href="<?php echo $bannerDet['banner_btn_url']; ?>" class="btn btn--primary btn--large"><?php echo $bannerDet['banner_btn_caption']; ?></a>
                <?php } ?>
			</div>
		</div>
	</div>
</section>
<?php } */ ?>

<?php  if(!empty($secondBlockdata['banners'])){ //print_r($secondBlockdata); die; ?>

<section class="section">
        <div class="container container--mdnarrow">
            <div class="section-title">
                <h2><?php echo Label::getLabel('LBL_Why_Us?'); ?></h2>
            </div>
            <div class="row justify-content-center align-items-center scroller--horizontal ">
                    <?php  $i = 1; foreach($secondBlockdata['banners'] as $banners) { ?>
                <div class="col-md-4 col-xl-4">
                    <div class="block align-center">
                        <div class="block__media">
                            <img src="<?php echo CommonHelper::generateUrl('Image','showBanner',array($banners['banner_id'], 0, BannerLocation::BLOCK_SECOND_AFTER_HOMESLIDER)); ?>" alt="">
                        </div>
                        <div class="block__content">
                            <h6><?php echo $banners['banner_title']; ?></h6>
                            <p><?php echo $banners['banner_description']; ?></p>
                            <?php
                            $bannerBtnCaption =  (!empty($banners['banner_btn_caption']))  ? $banners['banner_btn_caption'] : Label::getLabel('LBL_View');
                                if(!empty($banners['banner_btn_url'])) {
                                ?>
                                    <a href="<?php echo $banners['banner_btn_url']; ?>" target="<?php echo  $banners['banner_target']; ?>"  class="btn btn--primary banner-btn-url"><?php echo $bannerBtnCaption; ?></a>
                                <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                    <?php }?>
            </div>
        </div>
</section>
<?php }  ?>
<?php $this->includeTemplate( 'home/_partial/languagesWithTeachersCount.php' ); ?>
<?php if(!empty($hWorksdata)){
?>

<section class="section section--gray" id="how-it-works">
	<div class="container container--fixed">
		<div class="section-title"><h2><?php echo $hWorksdata['blocation_name']; ?></h2></div>

            <?php if($hWorksdata['banners']){ ?>
			<div class="row justify-content-between">
				<div class="col-xl-4 col-lg-5 col-md-12 col-sm-12">
					<div class="tabs-vertical tabs-js">

						<ul>
                    <?php  $i = 1; foreach($hWorksdata['banners'] as $banners) { ?>
							<li class="<?php if($i==1){ echo 'is-active'; }?>" data-href="#tab<?php echo $i; ?>">
                                    <div class="tab-wrap">
                                        <span class="counter"></span>
                                        <div class="tab-info">
                                            <h3><?php echo $banners['banner_title']; ?></h3>
                                            <p><?php echo $banners['banner_description']; ?></p>
                                            <a href="/teachers" class="btn btn--primary"><?php echo Label::getLabel('LBL_Find_a_Teacher'); ?></a>
                                        </div>
                                    </div>
							</li>
                    <?php $i++; } ?>
						</ul>

					</div>
				</div>

				<div class="col-xl-7 col-lg-7 col-md-12  col-sm-12 col__content">
                    <?php  $i = 1; foreach($hWorksdata['banners'] as $banners) { ?>
					<div id="tab<?php echo $i; ?>" class="tabs-content-js">
						<div class="media"><a href="<?php echo $banners['banner_url']; ?>" target="<?php echo $banners['banner_target']; ?>" ><img src="<?php echo CommonHelper::generateUrl('Image','showBanner',array($banners['banner_id'], 0, BannerLocation::BLOCK_HOW_IT_WORKS)); ?>" alt=""></a></div>
					</div>
                    <?php $i++; } ?>
				</div>
			</div>
            <?php } ?>

	</div>
</section>
<?php } ?>
