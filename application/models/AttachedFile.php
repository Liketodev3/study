<?php
class AttachedFile extends MyAppModel
{
    const DB_TBL = 'tbl_attached_files';
    const DB_TBL_TEMP = 'tbl_attached_files_temp';
    const DB_TBL_PREFIX = 'afile_';
    const FILETYPE_TEACHER_APPROVAL_USER_PROFILE_IMAGE = 1;
    const FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF = 2;
    const FILETYPE_PAYMENT_METHOD = 3;
    const FILETYPE_USER_PROFILE_IMAGE = 4;
    const FILETYPE_USER_PROFILE_CROPED_IMAGE = 5;
    const FILETYPE_FRONT_LOGO = 6;
    const FILETYPE_HOME_PAGE_BANNER = 7;
    const FILETYPE_SOCIAL_PLATFORM_IMAGE = 8;
    const FILETYPE_BANNER = 9;
    const FILETYPE_ADMIN_LOGO = 10;
    const FILETYPE_EMAIL_LOGO = 11;
    const FILETYPE_FAVICON = 12;
    const FILETYPE_DISCOUNT_COUPON_IMAGE = 13;
    const FILETYPE_PAYMENT_PAGE_LOGO = 14;
    const FILETYPE_ADMIN_PROFILE_IMAGE = 15;
    const FILETYPE_ADMIN_PROFILE_CROPED_IMAGE = 16;
    const FILETYPE_WATERMARK_IMAGE = 17;
    const FILETYPE_APPLE_TOUCH_ICON = 18;
    const FILETYPE_MOBILE_LOGO = 19;
    const FILETYPE_PRODCAT_IMAGE = 20;
    const FILETYPE_PRODUCT_IMAGE = 21;
    const FILETYPE_CUSTOM_PRODUCT_IMAGE = 22;
    const FILETYPE_BLOG_POST_IMAGE = 23;
    const FILETYPE_SOCIAL_FEED_IMAGE = 24;
    const FILETYPE_CATEGORY_COLLECTION_BG_IMAGE = 25;
    const FILETYPE_TESTIMONIAL_IMAGE = 26;
    const FILETYPE_CPAGE_BACKGROUND_IMAGE = 27;
    const FILETYPE_TEACHER_PAGE_SLOGAN_BG_IMAGE = 28;
    const FILETYPE_LEARNER_PAGE_SLOGAN_BG_IMAGE = 29;
    const FILETYPE_USER_QUALIFICATION_FILE = 30;
    const FILETYPE_COUNTRY_FLAG = 31;
    const FILETYPE_LESSON_PACKAGE_IMAGE = 32;
    const FILETYPE_LESSON_PLAN_FILE = 33;
    const FILETYPE_LESSON_PLAN_IMAGE = 34;
    const FILETYPE_TEACHER_COURSE_IMAGE = 35;
    const FILETYPE_BLOG_CONTRIBUTION = 36;
    const FILETYPE_FRONT_WHITE_LOGO = 37;
    const FILETYPE_BANNER_SECOND_IMAGE = 38;
    const FILETYPE_SPOKEN_LANGUAGES = 39;
    const FILETYPE_FLAG_SPOKEN_LANGUAGES = 40;
    const FILETYPE_TEACHING_LANGUAGES = 41;
    const FILETYPE_FLAG_TEACHING_LANGUAGES = 42;
    const FILETYPE_BLOG_POST_IMAGE_PATH = 'blog-post/';
    const FILETYPE_BLOG_PAGE_IMAGE = 43;
    const FILETYPE_LESSON_PAGE_IMAGE = 44;
    const FILETYPE_ALLOWED_PAYMENT_GATEWAYS_IMAGE = 45;

    const FILETYPE_PWA_APP_ICON = 46;
    const FILETYPE_PWA_SPLASH_ICON = 47;

    public function __construct($fileId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $fileId);
        $this->objMainTableRecord->setSensitiveFields(array());
    }

    public static function getFileTypeArray($langId)
    {
        return $arr = array();
        return $arr;
    }

    public static function setTimeParam($dateTime)
    {
        $time = strtotime($dateTime);
        return ($time > 0) ? '?t=' . $time : '';
    }
    
    public function checkSize($file, $compareSize)
    {
        $compareSize = FatUtility::convertToType($compareSize, FatUtility::VAR_FLOAT);
        if (filesize($file) > $compareSize) {
            $this->error = Label::getLabel('MSG_INVALID_SIZE', CommonHelper::getLangId());
            return false;
        }
        return true;
    }

    public static function getMultipleAttachments($fileType, $recordId, $recordSubid = 0, $langId = 0, $displayUniversalImage = true, $screen = 0, $size=0, $haveSubIdZero = false)
    {
        $fileType = FatUtility::int($fileType);
        $recordId = FatUtility::int($recordId);
        $recordSubid = FatUtility::int($recordSubid);
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition('afile_type', '=', $fileType);
        $srch->addCondition('afile_record_id', '=', $recordId);
        if ($recordSubid || $recordSubid == -1 || $haveSubIdZero) {
            if ($recordSubid == -1) {
                /* -1, becoz, needs to show, products universal image as well, in that case, value passed is as -1 */
                $recordSubid = 0;
            }
            $srch->addCondition('afile_record_subid', '=', $recordSubid);
        }
        if ($recordId == 0) {
            $srch->addOrder('afile_id', 'desc');
            $srch->addOrder('afile_display_order');
        } else {
            $srch->addOrder('afile_display_order');
        }
        if ($langId > 0) {
            $cnd = $srch->addCondition('afile_lang_id', '=', $langId);
            if ($displayUniversalImage) {
                $cnd->attachCondition('afile_lang_id', '=', '0');
                $srch->addOrder('afile_lang_id', 'DESC');
            }
        }
        if ($screen > 0) {
            $srch->addCondition('afile_screen', '=', $screen);
        }
        if ($langId == 0) {
            $srch->addCondition('afile_lang_id', '=', 0);
        }
        if ($size > 0) {
            $srch->setPageSize($size);
        }
        /* die($srch->getQuery()); */
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs, 'afile_id');
    }

    public static function getAttachment($fileType, $recordId, $recordSubid = 0, $langId = 0, $displayUniversalImage = true, $screen = 0)
    {
        $data = static::getMultipleAttachments($fileType, $recordId, $recordSubid, $langId, $displayUniversalImage, $screen);
        if (!empty($data)) {
            reset($data);
            return current($data);
        }
        return null;
    }

    public function saveAttachment($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder = 0, $uniqueRecord = false, $langId = 0, $screen = 0)
    {
        $defaultLangIdForErrors = ($langId == 0) ? $this->commonLangId : $langId;
        if (!empty($name) && !empty($fl)) {
            $fileExt = pathinfo($name, PATHINFO_EXTENSION);
            $fileExt = strtolower($fileExt);
            if (!in_array($fileExt, applicationConstants::allowedFileExtensions())) {
                $this->error = Label::getLabel('MSG_INVALID_FILE_EXTENSION', $defaultLangIdForErrors);
                return false;
            }
            $fileMimeType = mime_content_type($fl);
            if (!in_array($fileMimeType, applicationConstants::allowedMimeTypes())) {
                $this->error = Label::getLabel('MSG_INVALID_FILE_MIME_TYPE', $defaultLangIdForErrors);
                return false;
            }
        } else {
            $this->error = Label::getLabel('MSG_NO_FILE_UPLOADED', $defaultLangIdForErrors);
            return false;
        }
        $path = CONF_UPLOADS_PATH;
        /* files path[ */
        switch ($fileType) {
            case self::FILETYPE_BLOG_POST_IMAGE:
                $path .= self::FILETYPE_BLOG_POST_IMAGE_PATH;
            break;
        }
        /* ] */
        /* creation of folder date wise [ */
        $date_wise_path = date('Y') . '/' . date('m') . '/';
        /* ] */
        $path  = $path . $date_wise_path;
        $saveName = time() . '-' . preg_replace('/[^a-zA-Z0-9]/', '', $name);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        while (file_exists($path . $saveName)) {
            $saveName = rand(10, 99) . '-' . $saveName;
        }
        if (!move_uploaded_file($fl, $path . $saveName)) {
            $this->error = Label::getLabel('MSG_COULD_NOT_SAVE_FILE', $defaultLangIdForErrors);
            return false;
        }
        $this->assignValues(array(
            'afile_type' => $fileType,
            'afile_record_id' => $recordId,
            'afile_record_subid' => $recordSubid,
            'afile_physical_path' => $date_wise_path . $saveName,
            'afile_name' => $name,
            'afile_lang_id' => $langId,
            'afile_screen' => $screen
        ));
        $db = FatApp::getDb();
        if ($displayOrder == -1) {
            //@todo display order thing needs to be checked.
            $smt = $db->prepareStatement('SELECT MAX(afile_display_order) AS max_order FROM ' . static::DB_TBL . '
					WHERE afile_type = ? AND afile_record_id = ? AND afile_record_subid = ? AND afile_lang_id = ?');
            $smt->bindParameters('iii', $fileType, $recordId, $recordSubid, $langId);
            $smt->execute();
            $row = $smt->fetchAssoc();
            $displayOrder = FatUtility::int($row['max_order']) + 1;
        }
        $this->setFldValue('afile_display_order', $displayOrder);
        if (!$this->save()) {
            $this->error = Label::getLabel('MSG_COULD_NOT_SAVE_FILE', $defaultLangIdForErrors);
            return false;
        }
        if ($uniqueRecord) {
            $db->deleteRecords(static::DB_TBL, array(
                'smt' => 'afile_type = ? AND afile_record_id = ? AND afile_record_subid = ? AND afile_lang_id = ?  AND afile_id != ? AND afile_screen = ?',
                'vals' => array($fileType, $recordId, $recordSubid, $langId, $this->mainTableRecordId, $screen)
            ));
        }
        return $date_wise_path . $saveName;
    }

    public function saveImage($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder = 0, $uniqueRecord = false, $lang_id = 0, $mimeType='', $screen = 0)
    {
        if (getimagesize($fl) === false && $mimeType !='image/svg+xml') {
            $this->error = Label::getLabel('MSG_UNRECOGNISED_IMAGE_FILE', $this->commonLangId);
            return false;
        }
        $deg = CommonHelper::getCorrectImageOrientation($fl);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $ext = $ext!='jpg' ? $ext : 'jpeg';
        if($deg>0){            
            $src = call_user_func('imagecreatefrom'.$ext, $fl);
            $rotate = imagerotate($src, $deg, 0);
            call_user_func('image'.$ext, $rotate, $fl);            
        }
        return $this->saveAttachment($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder, $uniqueRecord, $lang_id, $screen);
    }

    public function saveDoc($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder = 0, $uniqueRecord = false, $lang_id = 0, $mimeType='', $screen = 0)
    {
        $mimtypeArr = array(
            'text/plain',
            'application/pdf',
            'application/msword',
            'application/rtf',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/octet-stream',
            'image/png',
            'image/jpeg',
            'image/jpg',
        );

        if(empty($mimeType)) {
            $mimeType = mime_content_type($fl);
        }

        if (!in_array($mimeType,$mimtypeArr)) {
            $this->error = Label::getLabel('MSG_UNRECOGNISED_FILE', $this->commonLangId);
            return false;
        }
        return $this->saveAttachment($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder, $uniqueRecord, $lang_id, $screen);
    }

    public function saveCert($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder = 0, $uniqueRecord = false, $lang_id = 0, $mimeType='', $screen = 0)
    {
        $mimtypeArr = array(
            'image/png',
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        );
        if (!in_array(mime_content_type($fl), $mimtypeArr)) {
            $this->error = Label::getLabel('MSG_UNRECOGNISED_FILE', $this->commonLangId);
            return false;
        }
        return $this->saveAttachment($fl, $fileType, $recordId, $recordSubid, $name, $displayOrder, $uniqueRecord, $lang_id, $screen);
    }
    /* always call this function using image controller and pass relavant arguments. */
    public static function displayImage($image_name, $w, $h, $no_image = '', $uploadedFilePath = '', $resizeType = ImageResize::IMG_RESIZE_EXTRA_ADDSPACE, $apply_watermark = false, $cache = false)
    {
        ob_end_clean();
        if ($no_image == '') {
            $no_image = CONF_THEME_PATH . 'img/no_image.jpg';
        } else {
            $no_image = CONF_UPLOADS_PATH . 'defaults/'. $no_image;
        }
        $originalImageName = $image_name;
        if (trim($uploadedFilePath) != '') {
            $uploadedFilePath = CONF_UPLOADS_PATH.$uploadedFilePath;
        } else {
            $uploadedFilePath = CONF_UPLOADS_PATH;
        }
        $fileMimeType = '';
        if (!empty($image_name) && file_exists($uploadedFilePath . $image_name)) {
            $fileMimeType = mime_content_type($uploadedFilePath . $image_name);
            $image_name = $uploadedFilePath . $image_name;
            $headers = FatApp::getApacheRequestHeaders();
            if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($image_name))) {
                // header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image_name)).' GMT', true, 304);
                // exit;
            }
            try {
                $img = new ImageResize($image_name);
                header('Cache-Control: public');
                header("Pragma: public");
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image_name)).' GMT', true, 200);
                header("Expires: " . date('r', strtotime("+30 Day")));
            } catch (Exception $e) {
                try {
                    $file_extension = substr($image_name, strlen($image_name)-3, strlen($image_name));
                    if ($file_extension=="svg") {
                        header("Content-type: image/svg+xml");
                        header('Cache-Control: public');
                        header("Pragma: public");
                        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image_name)).' GMT', true, 200);
                        header("Expires: " . date('r', strtotime("+30 Day")));
                        echo file_get_contents($image_name);
                        exit;
                    }
                    $img = new ImageResize($no_image);
                } catch (Exception $e) {
                    $img = new ImageResize($no_image);
                }
            }
        } else {
            $img = new ImageResize($no_image);
        }
        /* $w = max(1, FatUtility::int($w));
        $h = max(1, FatUtility::int($h)); */
        $w = FatUtility::int($w);
        $h = FatUtility::int($h);
        $img->setResizeMethod($resizeType);
        //$img->setResizeMethod(ImageResize::IMG_RESIZE_RESET_DIMENSIONS);
        if ($w && $h) {
            $img->setMaxDimensions($w, $h);
        }
        if ($apply_watermark && !empty($image_name)) {
            $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_WATERMARK_IMAGE, 0, 0, CommonHelper::getLangId());
            $wtrmrk_file = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
            if (!empty($wtrmrk_file)) {
                $wtrmrk_file = $uploadedFilePath.$wtrmrk_file;
                //$wtrmrkFileMimeType = mime_content_type($uploadedFilePath . $image_name);
                $ext_watermark = substr($wtrmrk_file, -3);
                $imageInfo = getimagesize($wtrmrk_file);
                $OriginalImageInfo = getimagesize($image_name);
                /* var_dump($OriginalImageInfo); die; */
                $img_w = $w;
                $img_h = $h;
                $wtrmrk_w = $imageInfo[0];
                $wtrmrk_h = $imageInfo[1];
                /* echo $img_h-$wtrmrk_h-20; die; 422,651 */
                $img->setWaterMark($wtrmrk_file, $img_w-$wtrmrk_w-20, $img_h-$wtrmrk_h-20);
                $fileMimeType = 'image/png';
            }
        }
        if ($cache) {
            $cacheKey = $_SERVER['REQUEST_URI'];
            // ob_get_clean();
            ob_start();
            if ($fileMimeType != '') {
                header("content-type: ".$fileMimeType);
            } else {
                header("content-type: image/jpeg");
            }
            $img->displayImage(80, false);
            $imgData = ob_get_clean();
            FatCache::set($cacheKey, $imgData, '.jpg');
            echo $imgData;
        } else {
            if ($fileMimeType != '') {
                header("content-type: ".$fileMimeType);
            } else {
                header("content-type: image/jpeg");
            }
            $img->displayImage(80, false);
        }
        /* $img->displayImage(); */
    }

    public static function downloadFile($fileName, $uploadedFilePath = '')
    {
        if (trim($uploadedFilePath) != '') {
            $uploadedFilePath = CONF_UPLOADS_PATH.$uploadedFilePath;
        } else {
            $uploadedFilePath = CONF_UPLOADS_PATH;
        }
        $nameOfFile = $fileName;
        if (file_exists($uploadedFilePath)) {
            ob_end_clean();
            header("content-type: text/plain");
            $fileSize = filesize($uploadedFilePath);
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="' . $nameOfFile . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Expires: 0');
            header('Content-Length: ' . $fileSize);
            echo file_get_contents($uploadedFilePath);
            die();
        }
        echo Label::getLabel("LBL_No_File_Found");
    }

    public static function displayOriginalImage($image_name, $no_image = '', $uploadedFilePath = '', $cache = false)
    {
        ob_end_clean();
        if ($no_image == '') {
            $no_image = CONF_THEME_PATH . 'img/no_image.jpg';
        } else {
            $no_image = CONF_UPLOADS_PATH . 'defaults/'. $no_image;
        }
        if (trim($uploadedFilePath)!='') {
            $uploadedFilePath = CONF_UPLOADS_PATH.$uploadedFilePath;
        } else {
            $uploadedFilePath = CONF_UPLOADS_PATH;
        }
        $fileMimeType = mime_content_type($uploadedFilePath . $image_name);
        if ($fileMimeType != '') {
            header("content-type: ".$fileMimeType);
        } else {
            header("content-type: image/jpeg");
        }
        $cacheKey = $_SERVER['REQUEST_URI'];
        if (!empty($image_name) && file_exists($uploadedFilePath . $image_name)) {
            $image_name = $uploadedFilePath . $image_name;
            $headers = FatApp::getApacheRequestHeaders();
            if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($image_name))) {
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image_name)).' GMT', true, 304);
                exit;
            }
            try {
                header('Cache-Control: public');
                header("Pragma: public");
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image_name)).' GMT', true, 200);
                header("Expires: " . date('r', strtotime("+30 Day")));
                echo file_get_contents($image_name);
                if ($cache) {
                    FatCache::set($cacheKey, file_get_contents($image_name), '.jpg');
                }
            } catch (Exception $e) {
                echo file_get_contents($no_image);
                FatCache::set($cacheKey, file_get_contents($no_image), '.jpg');
            }
        } else {
            echo file_get_contents($no_image);
            FatCache::set($cacheKey, file_get_contents($no_image), '.jpg');
        }
    }

    public static function getTempImages($limit = false)
    {
        $srch = new SearchBase(AttachedFile::DB_TBL_TEMP, 'aft');
        $srch->addCondition('aft.afile_downloaded', '=', applicationConstants::NO);
        $srch->addOrder('aft.afile_id', 'asc');
        if ($limit > 0) {
            $srch->setPageSize($limit);
        }
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetchAll($rs);
        if ($row == false) {
            return array();
        } else {
            return $row;
        }
    }

    public static function getImageName($url, $arr = array())
    {
        if (empty($arr)) {
            return ;
        }
        $imageName = '';
        $isUrlArr = parse_url($url);
        if (is_array($isUrlArr) && isset($isUrlArr['host'])) {
            if (static::isValidImageUrl($url)) {
                $imgFileContent = static::getRemoteFileContent($url);
                if ($imgFileContent) {
                    $imageName = static::uploadTempImage($imgFileContent, $url, $arr);
                }
            }
        } else {
            $imageName = $url;
        }
        return $imageName;
    }

    public static function uploadTempImage($imgFileContent, $url, $arr = array())
    {
        if (empty($arr)) {
            return ;
        }
        $name = preg_replace('/[^a-zA-Z0-9\/\-\_\.]/', '', basename($url));
        $path = CONF_UPLOADS_PATH;
        /* files path[ */
        switch ($arr['afile_type']) {
            case self::FILETYPE_BLOG_POST_IMAGE:
                $path .= self::FILETYPE_BLOG_POST_IMAGE_PATH;
            break;
        }
        /* ] */
        /* creation of folder date wise [ */
        $date_wise_path = date('Y') . '/' . date('m') . '/';
        /* ] */
        $path  = $path . $date_wise_path;
        $saveName = time() . '-' . preg_replace('/[^a-zA-Z0-9]/', '', $name);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        while (file_exists($path . $saveName)) {
            $saveName = rand(10, 99) . '-' . $saveName;
        }
        $localfile = $path . $saveName;
        $res = file_put_contents($localfile, $imgFileContent);
        if (!$res) {
            return ;
        }
        $fileType = $arr['afile_type'];
        $recordId = $arr['afile_record_id'];
        $recordSubid = $arr['afile_record_subid'];
        $langId = $arr['afile_lang_id'];
        $screen = $arr['afile_screen'];
        $data = array(
            'afile_type' => $fileType,
            'afile_record_id' => $recordId,
            'afile_record_subid' => $recordSubid,
            'afile_physical_path' => $date_wise_path . $saveName,
            'afile_lang_id' => $langId,
            'afile_screen' => $arr['afile_screen'],
            'afile_display_order' => $arr['afile_display_order'],
            'afile_name' => $name
        );
        $db = FatApp::getDb();
        if ($arr['afile_unique'] == applicationConstants::YES) {
            $db->deleteRecords(static::DB_TBL, array(
                'smt' => 'afile_type = ? AND afile_record_id = ? AND afile_record_subid = ? AND afile_lang_id = ?  AND afile_screen = ?',
                'vals' => array($fileType, $recordId, $recordSubid, $langId, $screen)
            ));
        }
        $db->insertFromArray(static::DB_TBL, $data);
        return $date_wise_path . $saveName;
    }

    public static function isValidImageUrl($url)
    {
        if (getimagesize($url)!== false) {
            return true;
        }
        return false;
    }

    public static function getRemoteFileContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $file = curl_exec($ch);
        if ($file === false) {
            return false;
        }
        curl_close($ch);
        return $file;
    }

    public function deleteFile($fileType, $recordId, $fileId = 0, $record_subid = 0, $langId = -1, $screen = 0)
    {
        $fileType = FatUtility::int($fileType);
        $recordId = FatUtility::int($recordId);
        $fileId = FatUtility::int($fileId);
        $record_subid = FatUtility::int($record_subid);
        $langId = FatUtility::int($langId);

        if (!in_array($fileType, array(AttachedFile::FILETYPE_ADMIN_LOGO, AttachedFile::FILETYPE_ALLOWED_PAYMENT_GATEWAYS_IMAGE, AttachedFile::FILETYPE_FRONT_LOGO, AttachedFile::FILETYPE_FRONT_WHITE_LOGO, AttachedFile::FILETYPE_EMAIL_LOGO, AttachedFile::FILETYPE_FAVICON, AttachedFile::FILETYPE_SOCIAL_FEED_IMAGE, AttachedFile::FILETYPE_PAYMENT_PAGE_LOGO, AttachedFile::FILETYPE_WATERMARK_IMAGE, AttachedFile::FILETYPE_APPLE_TOUCH_ICON, AttachedFile::FILETYPE_BLOG_PAGE_IMAGE, AttachedFile::FILETYPE_MOBILE_LOGO, AttachedFile::FILETYPE_CATEGORY_COLLECTION_BG_IMAGE, AttachedFile::FILETYPE_LESSON_PAGE_IMAGE)) && (!$fileType || !$recordId)) {
            $this->error = Label::getLabel('MSG_INVALID_REQUEST', $this->commonLangId);
            return false;
        }
        /* default will delete all files of requested recordId */
        $smt1 = 'afile_type = ? AND afile_record_id = ?';
        $dataArr1 = array($fileType, $recordId);
        $deleteStatementArr = array('smt'=>'afile_type = ? AND afile_record_id = ?', 'vals' => array($fileType, $recordId));
        if ($record_subid > 0) {
            $deleteStatementArr = array('smt' => 'afile_type = ? AND afile_record_id = ? AND afile_record_subid = ?', 'vals' => array($fileType, $recordId, $record_subid));
        }
        if ($langId != -1) {
            /* delete lang Specific file */
            $deleteStatementArr = array('smt' => 'afile_type = ? AND afile_record_id = ? AND afile_lang_id = ? AND afile_screen = ?', 'vals' => array($fileType, $recordId, $langId, $screen));
            if ($record_subid > 0) {
                $deleteStatementArr = array('smt'=>'afile_type = ? AND afile_record_id = ? AND afile_record_subid = ? AND afile_lang_id = ?', 'vals' => array($fileType, $recordId, $record_subid, $langId));
            }
        }
        if ($fileId) {
            /* delete single file */
            $deleteStatementArr = array('smt' => 'afile_type = ? AND afile_record_id = ? AND afile_id=?', 'vals' => array($fileType, $recordId, $fileId));
        }

        $db = FatApp::getDb();
        if (!$db->deleteRecords('tbl_attached_files', $deleteStatementArr)) {
            $this->error = $db->getError();
            return false;
        }
        //@todo:: not deleted physical file from the system.
        return true;
    }

    public static function downloadAttachment($image_name, $downloadFileName)
    {
        ob_end_clean();
        // die(CONF_UPLOADS_PATH . $image_name);
        if (!empty($image_name) && file_exists(CONF_UPLOADS_PATH . $image_name)) {
            $image_name = CONF_UPLOADS_PATH . $image_name;
            header("Content-type: application/octet-stream");
            header('Content-Disposition: attachement; filename="'.basename($downloadFileName).'"');
            header('Content-Length: ' . filesize($image_name));
            readfile($image_name);
        }
    }

    public function getErrMsgByErrCode($errorCode)
    {
        $phpFileUploadErrors = array(
            1 => sprintf(Label::getLabel('LBL_The_uploaded_file_can_not_exceed_the_filesize_%sB'), ini_get('upload_max_filesize')),
            2 => Label::getLabel('LBL_The_uploaded_file_can_not_exceed_the_filesize_%sB', ini_get('max_file_size')),
            3 => Label::getLabel('LBL_The_uploaded_file_was_only_partially_uploaded'),
            4 => Label::getLabel('LBL_No_file_was_uploaded'),
            6 => Label::getLabel('LBL_Missing_a_temporary_folder'),
            7 => Label::getLabel('LBL_Failed_to_write_file_to_disk.'),
            8 => Label::getLabel('LBL_A_PHP_extension_stopped_the_file_upload.'),
        );
        return $phpFileUploadErrors[$errorCode];
    }
    
    public function getMimeType(string $filepath): string
    {
        return mime_content_type($filepath);
    }
}
