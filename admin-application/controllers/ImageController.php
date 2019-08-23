<?php
class ImageController extends FatController
{
    public function default_action()
    {
        exit(Label::getLabel('LBL_Invalid_Request!!', CommonHelper::getLangId()));
    }
    public function siteAdminLogo($lang_id = 0, $sizeType = '')
    {
        $lang_id       = FatUtility::int($lang_id);
        $file_row      = AttachedFile::getAttachment(AttachedFile::FILETYPE_ADMIN_LOGO, 0, 0, $lang_id);
        $image_name    = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
        $default_image = '';
        AttachedFile::displayImage($image_name, 0, 0, $default_image, '', ImageResize::IMG_RESIZE_RESET_DIMENSIONS);
        /* switch( strtoupper($sizeType) ){
        case 'THUMB':
        $w = 142;
        $h = 45;
        AttachedFile::displayImage( $image_name, $w, $h, $default_image );
        break;
        case 'SMALL':
        $w = 200;
        $h = 200;
        AttachedFile::displayImage( $image_name, $w, $h, $default_image );
        break;
        default:
        $h = 400;
        $w = 400;
        AttachedFile::displayImage( $image_name, $w, $h, $default_image );
        break;
        } */
    }
	
}