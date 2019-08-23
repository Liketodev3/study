<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="banner banner--main">
	<div class="caraousel caraousel--single caraousel--single-js">
    		<?php foreach($slides as $slide){ ?>
		<div>
			<div class="caraousel__item"><img src="<?php echo CommonHelper::generateUrl('Image','slide',array($slide['slide_id'], 0, $siteLangId)); ?>" alt=""></div>
		</div>
            <?php } ?>
	</div>

	<div class="banner__content">
		<h1><?php echo Label::getLabel('LBL_Slider_Title_Text'); ?></h1>
		<p><?php echo Label::getLabel('LBL_Slider_Description_Text'); ?></p>
		<div class="search-form">
			<form method="POST" class="form" action="/teachers" >
                <input type="text" name="language" placeholder="<?php echo Label::getLabel('LBL_I_am_learning...'); ?>">
                <input type="submit" value="<?php echo Label::getLabel('LBL_Get_Started?'); ?>">
			</form>
		</div>
        <a href="#" class="banner-link"><?php echo Label::getLabel('LBL_How_it_Works?'); ?></a>        
	</div>
</section>