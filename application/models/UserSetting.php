<?php
class UserSetting extends MyAppModel
{
    const DB_TBL = 'tbl_user_settings';
    const DB_TBL_PREFIX = 'us_';

    public function __construct($userId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'user_id', $userId);
    }

    public function saveData($data = array())
    {
        if (($this->getMainTableRecordId() < 1)) {
            $this->error = Label::getLabel('ERR_INVALID_REQUEST_USER_NOT_INITIALIZED', $this->commonLangId);
            return false;
        }
        $db = FatApp::getDb();
        $data['us_user_id']	= $this->getMainTableRecordId();
        if (!$db->insertFromArray(static::DB_TBL, $data, false, array(), $data)) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }
    public function getUserSiteLang() : array
    {
       $userSetting =  new UserSettingSearch();
       $userSetting->doNotCalculateRecords();
        $userSetting->setPagesize(1);
       $userSetting->getUserSiteLang($this->mainTableRecordId);
       $resultSet = $userSetting->getResultSet();
       $data =  FatApp::getDb()->fetch($resultSet);
      
       if(empty($data)) {
           return array();
       }

       return $data;
    }

    public static function getUserSettings($userId, $tlangId = null, $duration = 0)
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            trigger_error("User Id is not passed", E_USER_ERROR);
        }

        $srch = new SearchBase(UserSetting::DB_TBL, 'us');
        $srch->addMultipleFields(
            array(
                'us_is_trial_lesson_enabled',
                'us_notice_number',
                'us_video_link',
                'us_booking_before', //== code added on 23-08-2019
                'us_teach_slanguage_id',
                'us_google_access_token',
                'us_google_access_token_expiry',
                'us_site_lang',
                'utl.*'
                )
        );
        $srch->joinTable("tbl_user_teach_languages", 'LEFT JOIN', 'utl_us_user_id = us_user_id', 'utl');
        $srch->addCondition('us_user_id', '=', $userId);
        $srch->addOrder('utl_booking_slot', 'DESC');
        // if greater than 0, then specific otherwise all
        if($duration>0){
            $srch->addCondition('utl_booking_slot', '=', $duration);
        }
        if ($tlangId) {
            $srch->addCondition('utl_slanguage_id', '=', $tlangId);
        }
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }

    public static function truncateUserSettingsDataByUserId($userId)
    {
        $db = FatApp::getDb();
        $tbl_user_settings_data = [
            'us_video_link' => '',
            'us_google_access_token' => '',
        ];

        if ($db->updateFromArray(static::DB_TBL, $tbl_user_settings_data, array(
            'smt' => 'us_user_id=?',
            'vals' => array(
                $userId
            )
        ))){
            return true;
        }
    }
}
