<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $layoutDirection = CommonHelper::getLayoutDirection(); ?>
<section class="banner banner--main">
	<div class="caraousel caraousel--single caraousel--single-js" <?php echo (strtolower($layoutDirection) == 'rtl') ? 'dir="rtl"': ""; ?>>
		<?php foreach($slides as $slide){ 
            $desktop_url = '';
            $tablet_url = '';
            $mobile_url = '';
            $haveUrl = ( $slide['slide_url'] != '' ) ? true : false;
            $defaultUrl = '';
            $slideArr = AttachedFile::getMultipleAttachments( AttachedFile::FILETYPE_HOME_PAGE_BANNER, $slide['slide_id'], 0, $siteLangId );
            if( !$slideArr ){
                continue;
            }else{
                
                foreach($slideArr as $slideScreen){
                //    $uploadedTime = AttachedFile::setTimeParam($slideScreen['afile_updated_at']);
                    switch($slideScreen['afile_screen']){
                        case applicationConstants::SCREEN_MOBILE:
                            $mobile_url = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','slide',array($slide['slide_id'], applicationConstants::SCREEN_MOBILE, $siteLangId, 'MOBILE')),CONF_IMG_CACHE_TIME, '.jpg');
                            break;
                        case applicationConstants::SCREEN_IPAD:
                            $tablet_url = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','slide',array($slide['slide_id'], applicationConstants::SCREEN_IPAD, $siteLangId, 'TABLET')),
                            CONF_IMG_CACHE_TIME, '.jpg');
                            break;
                        case applicationConstants::SCREEN_DESKTOP:
                            $defaultUrl =  FatCache::getCachedUrl(CommonHelper::generateUrl('Image','slide',array($slide['slide_id'], applicationConstants::SCREEN_DESKTOP, $siteLangId, 'DESKTOP')),CONF_IMG_CACHE_TIME, '.jpg');
                            $desktop_url = $defaultUrl;
                            break;
                    }
                }
            }

            if($defaultUrl == ''){
                $defaultUrl = FatCache::getCachedUrl(CommonHelper::generateUrl('Image','slide',array($slide['slide_id'], applicationConstants::SCREEN_DESKTOP, $siteLangId, 'DESKTOP')),CONF_IMG_CACHE_TIME, '.jpg');
            }

            $out = '<div><div class="caraousel__item">';
            if($haveUrl){
                $slideUrl = CommonHelper::processUrlString($slide['slide_url']);
            }

            if( $haveUrl ){
                $out .= '<a target="'.$slide['slide_target'].'" href="'.$slideUrl.'">';
            }

            $out .= '<div class="caraousel__item">
                <picture>
                    <source data-aspect-ratio="4:3" srcset="'. $mobile_url .'" media="(max-width: 767px)">
                    <source data-aspect-ratio="4:3" srcset="'. $tablet_url .'" media="(max-width: 1024px)">
                    <source data-aspect-ratio="10:3" srcset="'. $desktop_url .'">
                    <img data-aspect-ratio="10:3" srcset="'. $desktop_url .'" alt="">
                </picture>
            </div></div>';

            if( $haveUrl ){
                $out .= '</a>';
            }

            $out .= '</div>';
            echo $out;
        } ?>
	</div>

	<div class="banner__content">
		<h1><?php echo Label::getLabel('LBL_Slider_Title_Text'); ?></h1>
		<p><?php echo Label::getLabel('LBL_Slider_Description_Text'); ?></p>
		<div class="search-form">
			<form method="POST" class="form" action="<?php echo CommonHelper::generateFullUrl('Teachers'); ?>" name="homeSearchForm" id="homeSearchForm" >
                <input type="text" name="language" placeholder="<?php echo Label::getLabel('LBL_I_am_learning...'); ?>">
                <input type="hidden" name="teachLangId">
                <input type="submit" value="<?php echo Label::getLabel('LBL_Search_for_teachers'); ?>">
			</form>
		</div>
        <a href="#" class="banner-link banner_link_how_works"><?php echo Label::getLabel('LBL_How_it_Works?'); ?></a>
	</div>
</section>
