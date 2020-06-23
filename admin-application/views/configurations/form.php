<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form form_horizontal layout--'.$formLayout);
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = '12';

if($lang_id > 0){
	$frm->setFormTagAttribute('onsubmit', 'setupLang(this); return(false);');
}
else{
	$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
}
	$tbid = isset($tabId)?$tabId:'tabs_'.$frmType;
switch ($frmType){
	case Configurations::FORM_GENERAL:
		/* if( $lang_id == 0 ){
			$adminLogoFld = $frm->getField('admin_logo');
			$desktopLogoFld = $frm->getField('front_logo');
			$emailLogoFld = $frm->getField('email_logo');
			$faviconFld = $frm->getField('favicon');

			$adminLogoFld->htmlAfterField =  '<span class = "uploadimage--info" >Dimensions 142*45</span>';
			$desktopLogoFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 168*37</span>';
			$emailLogoFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 168*37</span>';
			if( isset($adminLogo) && !empty($adminLogo) ){
				$adminLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.CommonHelper::generateFullUrl('Image','siteAdminLogo',array('THUMB')).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeSiteAdminLogo()" ><i class="ion-close-round"></i></a></div>';
			}

			if( isset($desktopLogo) && !empty($desktopLogo) ){
				$desktopLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.CommonHelper::generateFullUrl('Image','siteLogo',array(''), CONF_WEBROOT_FRONT_URL).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeDesktopLogo()" ><i class="ion-close-round"></i></a></div>';
			}

			if( isset($emailLogo) && !empty($emailLogo) ){
				$emailLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.CommonHelper::generateFullUrl('Image','emailLogo',array(''), CONF_WEBROOT_FRONT_URL).'?'.time().'"><a  class="remove--img" href="javascript:void(0);" onclick="removeEmailLogo()" ><i class="ion-close-round"></i></a></div>';
			}

			if( isset($favicon) && !empty($favicon) ){
				$faviconFld->htmlAfterField = '<div class="uploaded--image"><img src="'.CommonHelper::generateFullUrl('Image','favicon',array(''), CONF_WEBROOT_FRONT_URL).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeFavicon()" ><i class="ion-close-round"></i></a></div>';
			}
		} */
	break;
	case Configurations::FORM_MEDIA:
        $frm->developerTags['fld_default_col'] = '6';
		$adminLogoFld = $frm->getField('admin_logo');
		$desktopLogoFld = $frm->getField('front_logo');
		$desktopWhiteLogoFld = $frm->getField('front_white_logo');
		$watermarkFld = $frm->getField('watermark');
		$emailLogoFld = $frm->getField('email_logo');
		$socialFeedImgFld = $frm->getField('social_feed_image');
		$faviconFld = $frm->getField('favicon');
		$paymentPageLogo= $frm->getField('payment_page_logo');
		$appleTouchIcon= $frm->getField('apple_touch_icon');
		$mobileLogo= $frm->getField('mobile_logo');

		$adminLogoFld->htmlAfterField =  '<span class = "uploadimage--info" >Dimensions 142*45</span>';
		$desktopLogoFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 168*37</span>';
		$desktopWhiteLogoFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 168*37</span>';

		$emailLogoFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 168*37</span>';
		$socialFeedImgFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 160*240</span>';
		$faviconFld->htmlAfterField = '<span class = "uploadimage--info" >Dimensions 16*16</span>';
		$mobileLogo->htmlAfterField = '<span class = "uploadimage--info" >&nbsp;</span>';
		$appleTouchIcon->htmlAfterField = '<span class = "uploadimage--info" >&nbsp;</span>';
		$paymentPageLogo->htmlAfterField = '<span class = "uploadimage--info" >&nbsp;</span>';
		$watermarkFld->htmlAfterField = '<span class = "uploadimage--info" >&nbsp;</span>';

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_ADMIN_LOGO, 0, 0, $lang_id ) ){
			$adminLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','siteAdminLogo',array($lang_id)).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeSiteAdminLogo('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_FRONT_WHITE_LOGO, 0, 0, $lang_id ) ){
			$desktopWhiteLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','siteWhiteLogo',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeDesktopLogo('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_FRONT_LOGO, 0, 0, $lang_id ) ){
			$desktopLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','siteLogo',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeDesktopLogo('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_PAYMENT_PAGE_LOGO, 0, 0, $lang_id ) ){
			$paymentPageLogo->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','paymentPageLogo',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"><a  class="remove--img" href="javascript:void(0);" onclick="removePaymentPageLogo('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_WATERMARK_IMAGE, 0, 0, $lang_id ) ){
			$watermarkFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','watermarkImage',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeWatermarkImage('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_EMAIL_LOGO, 0, 0, $lang_id ) ){
			$emailLogoFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','emailLogo',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"><a  class="remove--img" href="javascript:void(0);" onclick="removeEmailLogo('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_FAVICON, 0, 0, $lang_id ) ){
			$faviconFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','favicon',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"> <a  class="remove--img" href="javascript:void(0);" onclick="removeFavicon('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment( AttachedFile::FILETYPE_SOCIAL_FEED_IMAGE, 0, 0, $lang_id ) ){
			$socialFeedImgFld->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','socialFeed',array($lang_id , 'THUMB'), CONF_WEBROOT_FRONT_URL).'?'.time().'"><a  class="remove--img" href="javascript:void(0);" onclick="removeSocialFeedImage('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment(AttachedFile::FILETYPE_APPLE_TOUCH_ICON, 0, 0, $lang_id ) ){
			$appleTouchIcon->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','appleTouchIcon',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"><a  class="remove--img" href="javascript:void(0);" onclick="removeAppleTouchIcon('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}

		if( AttachedFile::getAttachment(AttachedFile::FILETYPE_MOBILE_LOGO, 0, 0, $lang_id ) ){
			$mobileLogo->htmlAfterField .= '<div class="uploaded--image"><img src="'.FatUtility::generateFullUrl('Image','mobileLogo',array($lang_id), CONF_WEBROOT_FRONT_URL).'?'.time().'"><a  class="remove--img" href="javascript:void(0);" onclick="removeMobileLogo('.$lang_id.')" ><i class="ion-close-round"></i></a></div><br>';
		}
	break;


}

?>
<ul class="tabs_nav innerul">
	<?php if( $frmType != Configurations::FORM_MEDIA ){ ?>
	<li><a href="javascript:void(0)" class="<?php echo ($lang_id == 0) ? 'active' : ''; ?>" onClick="getForm(<?php echo $frmType;?>,'<?php echo $tbid;?>')">Basic</a></li>
	<?php } ?>
	<?php
	if( $dispLangTab ){
		foreach( $languages as $langId => $langName ){ ?>
			<li><a href="javascript:void(0);" class="<?php echo ($lang_id == $langId) ? 'active' : '' ; ?>" onClick="getLangForm(<?php echo $frmType;?>,<?php echo $langId;?>,'<?php echo $tbid; ?>')"><?php echo $langName; ?></a></li>
		<?php }
	} ?>
</ul>
<div class="tabs_panel_wrap">
	<?php echo $frm->getFormHtml();?>
</div>
