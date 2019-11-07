<?php
class ImageController extends FatController
{
    public function __construct()
    {
        CommonHelper::initCommonVariables();
    }

    public function siteLogo($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_FRONT_LOGO, $recordId, 0, $lang_id, false);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';

        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $h = 37;
                $w = 168;
                AttachedFile::displayOriginalImage($image_name, $default_image);
            break;
        }
    }

    public function siteWhiteLogo($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_FRONT_WHITE_LOGO, $recordId, 0, $lang_id, false);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $h = 37;
                $w = 168;
                AttachedFile::displayOriginalImage($image_name, $default_image);
            break;
        }
    }

    public function emailLogo($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_EMAIL_LOGO, $recordId, 0, $lang_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = 'no_image.jpg';

        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $w = 100;
                $h = 100;
                if ($image_name=='' || empty($image_name)) {
                    AttachedFile::displayImage($image_name, $w, $h, $default_image);
                } else {
                    /* echo $image_name; die; */
                    AttachedFile::displayOriginalImage($image_name, $default_image);
                }
            break;
        }
    }

    public function socialFeed($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_SOCIAL_FEED_IMAGE, $recordId, 0, $lang_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 120;
                $h = 80;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $h = 240;
                $w = 160;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
        }
    }

    public function SocialPlatform($splatform_id, $sizeType = '')
    {
        $default_image = '';
        $splatform_id = FatUtility::int($splatform_id);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_SOCIAL_PLATFORM_IMAGE, $splatform_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 200;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $w = 30;
                $h = 30;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
        }
    }

    public function watermarkImage($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_WATERMARK_IMAGE, $recordId, 0, $lang_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';

        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                AttachedFile::displayOriginalImage($image_name, $default_image);
            break;
        }
    }

    public function appleTouchIcon($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_APPLE_TOUCH_ICON, $recordId, 0, $lang_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';

        switch (strtoupper($sizeType)) {
            case 'MINI':
                $w = 72;
                $h = 72;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            case 'SMALL':
                $w = 114;
                $h = 114;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                AttachedFile::displayOriginalImage($image_name, $default_image);
            break;
        }
    }

    public function mobileLogo($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_MOBILE_LOGO, $recordId, 0, $lang_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';

        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $h = 82;
                $w = 268;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
        }
    }

    public function paymentPageLogo($lang_id = 0, $sizeType = '')
    {
        $lang_id = FatUtility::int($lang_id);
        $recordId = 0;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_PAYMENT_PAGE_LOGO, $recordId, 0, $lang_id);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $w = 268;
                $h = 82;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
        }
    }

    public function favicon($lang_id = 0, $sizeType = '')
    {
        /* $recordId = 0;
        $file_row = AttachedFile::getAttachment( AttachedFile::FILETYPE_FAVICON, $recordId );
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $default_image = '';

        $uploadedFilePath = $file_row['afile_physical_path'];
        echo $uploadedFilePath; die();
        return $uploadedFilePath; */

        /* switch( strtoupper($sizeType) ){
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage( $image_name, $w, $h, $default_image );
            break;
            default:
                $h = 0;
                $w = 0;
                AttachedFile::displayImage( $image_name, $w, $h, $default_image );
            break;
        } */

        if ($file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_FAVICON, 0, 0, $lang_id, false)) {
            $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
            AttachedFile::displayOriginalImage($image_name);
        }
    }

    public function user($userId, $sizeType = 'default', $requestedForCroppedImage = 1)
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            trigger_error("User Id is not passed", E_USER_ERROR);
        }
        $default_image = 'no_image.jpg';
        $fileRow = false;
        if (1 === $requestedForCroppedImage || true === $requestedForCroppedImage) {
            $fileRow = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE, $userId);
        }

        if (false === $fileRow  || null == $fileRow) {
            $fileRow = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_PROFILE_IMAGE, $userId);
        }

        if (false == $fileRow || $fileRow['afile_physical_path'] == "") {
            AttachedFile::displayImage('', '', '', $default_image);
        }

        switch ($sizeType) {
            case 'normal':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($fileRow['afile_physical_path'], $w, $h);
            break;
            case 'small':
                $w = 60;
                $h = 60;
                AttachedFile::displayImage($fileRow['afile_physical_path'], $w, $h);
            break;
            case 'extrasmall':
                $w = 60;
                $h = 60;
            break;
            default:
                AttachedFile::displayOriginalImage($fileRow['afile_physical_path']);
            break;
        }

        //AttachedFile::displayImage( $fileRow['afile_physical_path'], $w, $h);
    }

    public function countryFlag($countryId, $sizeType = 'default')
    {
        $countryId = FatUtility::int($countryId);
        $fileRow = AttachedFile::getAttachment(AttachedFile::FILETYPE_COUNTRY_FLAG, $countryId);
        $imageName = isset($fileRow['afile_physical_path']) ? $fileRow['afile_physical_path'] : '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($imageName, $w, $h);
            break;
            case 'DEFAULT':
                $w = 30;
                $h = 20;
                AttachedFile::displayImage($imageName, $w, $h);
            break;
            default:
                AttachedFile::displayOriginalImage($imageName);
            break;
        }
    }

    public function blogPostAdmin($postId, $langId = 0, $size_type = '', $subRecordId = 0, $afile_id = 0)
    {
        $this->blogPost($postId, $langId, $size_type, $subRecordId, $afile_id, false);
    }

    public function blogPostFront($postId, $langId = 0, $size_type = '', $subRecordId = 0, $afile_id = 0)
    {
        $this->blogPost($postId, $langId, $size_type, $subRecordId, $afile_id);
    }

    public function blogPost($postId, $langId = 0, $size_type = '', $subRecordId = 0, $afile_id = 0, $displayUniversalImage = true)
    {
        $default_image = 'post_default_image.jpg';
        $langId = FatUtility::int($langId);
        $afile_id = FatUtility::int($afile_id);
        $postId = FatUtility::int($postId);
        $subRecordId = FatUtility::int($subRecordId);
        if ($afile_id > 0) {
            $res = AttachedFile::getAttributesById($afile_id);
            if (!false == $res && $res['afile_type'] == AttachedFile::FILETYPE_BLOG_POST_IMAGE) {
                $file_row = $res;
            }
        } else {
            $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_BLOG_POST_IMAGE, $postId, $subRecordId, $langId, $displayUniversalImage);
        }
        $image_name = isset($file_row['afile_physical_path']) ? AttachedFile::FILETYPE_BLOG_POST_IMAGE_PATH . $file_row['afile_physical_path'] : '';

        switch (strtoupper($size_type)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            case 'SMALL':
                $w = 200;
                $h = 200;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            case 'BANNER':
                $w = 945;
                $h = 535;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $h = 400;
                $w = 400;
                AttachedFile::displayOriginalImage($image_name);
            break;
        }
    }

    public function slide($slide_id, $screen=0, $lang_id, $sizeType = '', $displayUniversalImage = true)
    {
        $default_image = 'brand_deafult_image.jpg';
        $slide_id = FatUtility::int($slide_id);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_HOME_PAGE_BANNER, $slide_id, 0, $lang_id, $displayUniversalImage, $screen);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        $cacheKey = $_SERVER['REQUEST_URI'];
        $str = FatCache::get($cacheKey, null, '.jpg');
        if (false == $str && !CONF_USE_FAT_CACHE) {
            $cacheKey = false;
        }
        if ($sizeType) {
            switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 200;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
                break;
            default:
                $w = 2000;
                $h = 360;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
                break;
            }
        } else {
            AttachedFile::displayOriginalImage($image_name, $default_image);
        }
    }

    public function cpageBackgroundImage($cpageId, $langId = 0, $sizeType = '')
    {
        $cpageId = FatUtility::int($cpageId);
        $langId = FatUtility::int($langId);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_CPAGE_BACKGROUND_IMAGE, $cpageId, 0, $langId);
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';
        switch (strtoupper($sizeType)) {
        case 'THUMB':
            $w = 100;
            $h = 100;
            AttachedFile::displayImage($image_name, $w, $h);
            break;
        case 'COLLECTION_PAGE':
            $w = 45;
            $h = 41;
            AttachedFile::displayImage($image_name, $w, $h);
            break;
        default:
            AttachedFile::displayOriginalImage($image_name);
            break;
        }
    }

    public function testimonial($recordId, $langId = 0, $sizeType = '', $afile_id = 0, $displayUniversalImage = true)
    {
        $default_image = 'user_deafult_image.jpg';
        $recordId = FatUtility::int($recordId);
        $afile_id = FatUtility::int($afile_id);
        $langId = FatUtility::int($langId);
        if ($afile_id > 0) {
            $res = AttachedFile::getAttributesById($afile_id);
            if (!false == $res && $res['afile_type'] == AttachedFile::FILETYPE_TESTIMONIAL_IMAGE) {
                $file_row = $res;
            }
        } else {
            $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_TESTIMONIAL_IMAGE, $recordId, 0, $langId, $displayUniversalImage);
        }
        $image_name = isset($file_row['afile_physical_path']) ?  $file_row['afile_physical_path'] : '';

        switch (strtoupper($sizeType)) {
            case 'MINITHUMB':
                $w = 42;
                $h = 52;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            case 'THUMB':
                $w = 61;
                $h = 61;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
            default:
                $h = 500;
                $w = 800;
                AttachedFile::displayImage($image_name, $w, $h, $default_image);
            break;
        }
    }

    public function showBanner($bannerId, $langId, $type = 1, $secondary = false)
    {
        $bannerId = FatUtility::int($bannerId);
        $langId = FatUtility::int($langId);
        switch ($type) {
            case BannerLocation::BLOCK_FIRST_AFTER_HOMESLIDER:
                $w = 470;
                $h = 367;
            break;
            case BannerLocation::BLOCK_SECOND_AFTER_HOMESLIDER:
                $w = 800;
                $h = 500;
            break;
            case BannerLocation::BLOCK_HOW_IT_WORKS:
                $w = 800;
                $h = 600;
            break;
        }
        if ($secondary) {
            $imgType = AttachedFile::FILETYPE_BANNER_SECOND_IMAGE;
        } else {
            $imgType = AttachedFile::FILETYPE_BANNER;
        }
        $fileRow = AttachedFile::getAttachment($imgType, $bannerId, 0, $langId, true, 0);
        $image_name = isset($fileRow['afile_physical_path']) ?  $fileRow['afile_physical_path'] : '';
        AttachedFile::displayImage($image_name, $w, $h, '', '', ImageResize::IMG_RESIZE_EXTRA_ADDSPACE, false, true);
    }

    public function showLanguageImage($sLanguageId)
    {
        $sLanguageId = FatUtility::int($sLanguageId);
        $langId = FatUtility::int($langId);
        $w = 470;
        $h = 367;
        $default_image = 'no_image_user.jpg';
        $imgType = AttachedFile::FILETYPE_TEACHING_LANGUAGES;
        $fileRow = AttachedFile::getAttachment($imgType, $sLanguageId, 0, $langId, true, 0);
        $image_name = isset($fileRow['afile_physical_path']) ?  $fileRow['afile_physical_path'] : '';
        AttachedFile::displayImage($image_name, $w, $h, $default_image);
    }

    public function showLanguageFlagImage($sLanguageId)
    {
        $sLanguageId = FatUtility::int($sLanguageId);
        $langId = FatUtility::int($langId);
        //$w = 470;
        //$h = 367;
        $default_image = 'no_image_user.jpg';
        $imgType = AttachedFile::FILETYPE_FLAG_TEACHING_LANGUAGES;
        $fileRow = AttachedFile::getAttachment($imgType, $sLanguageId, 0, $langId, true, 0);
        $image_name = isset($fileRow['afile_physical_path']) ?  $fileRow['afile_physical_path'] : '';
        //AttachedFile::displayImage( $image_name, $w, $h,$default_image);
        AttachedFile::displayOriginalImage($image_name, $default_image);
    }

    public function editorImage($fileNamewithPath)
    {
        AttachedFile::displayOriginalImage('editor/'. $fileNamewithPath);
    }
    public function editorImages($dirPath, $fileNamewithPath)
    {
        AttachedFile::displayOriginalImage('editor/'. $fileNamewithPath);
    }
}
