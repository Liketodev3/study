<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<section class="banner banner--main">
	<div class="caraousel caraousel--single caraousel--single-js" <?php echo (strtolower($layoutDirection) == 'rtl') ? 'dir="rtl"': ""; ?>>
		<?php foreach($slides as $slide){ ?>
		<div>
			<div class="caraousel__item"><a href="<?php echo $slide['slide_url']?>" target="<?php echo $slide['slide_target']?>"><img src="<?php echo CommonHelper::generateUrl('Image','slide',array($slide['slide_id'], 0, $siteLangId)); ?>" alt=""></a></div>
		</div>
        <?php } ?>
	</div>

	<div class="banner__content">
		<h1><?php echo Label::getLabel('LBL_Slider_Title_Text'); ?></h1>
		<p><?php echo Label::getLabel('LBL_Slider_Description_Text'); ?></p>
		<div class="search-form">
			<form method="POST" class="form" action="/teachers" name="homeSearchForm" id="homeSearchForm" >
                <input type="text" name="language" placeholder="<?php echo Label::getLabel('LBL_I_am_learning...'); ?>">
                <input type="submit" value="<?php echo Label::getLabel('LBL_Get_Started?'); ?>">
			</form>
		</div>
        <a href="#" class="banner-link banner_link_how_works"><?php echo Label::getLabel('LBL_How_it_Works?'); ?></a>        
	</div>
</section>