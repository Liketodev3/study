<?php
class UserCookieConsent extends MyAppModel
{
    const DB_TBL = 'tbl_user_cookie_consent';
    const DB_TBL_PREFIX = 'usercc_';

    const COOKIE_NECESSARY_FIELD = 'necessary';
    const COOKIE_PREFERENCES_FIELD = 'preferences';
    const COOKIE_STATISTICS_FIELD = 'statistics';
   // const COOKIE_MARKETING_FIELD = 'marketing';

    const COOKIE_NAME =  'CookieConsent';
   
    private $userId;

    public function __construct(int $userId = 0)
    {
        $this->userId = $userId;
    }

    public static function getCookieExpireTime()
    {
        return time() + 3600 * 24 * 30;
    }

    public static function getSearchObject(bool $joinUser = true) :  object
    {
        $search = new SearchBase(static::DB_TBL, 'usercc');

        if ($joinUser) {
            $search->joinTable(User::DB_TBL,'INNER JOIN','usercc_user_id = user_id','user');
        }

        return $search;
    }

    public static function fieldsArrayWithDefultValue() : array
    {
    
        return array(
                      self::COOKIE_NECESSARY_FIELD =>  applicationConstants::YES,
                      self::COOKIE_PREFERENCES_FIELD => applicationConstants::YES,
                      self::COOKIE_STATISTICS_FIELD => applicationConstants::YES,
                    //   self::COOKIE_MARKETING_FIELD => applicationConstants::YES,
        );
    }

    public function saveOrUpdateSetting(array $settings = array(), $setSettingCookie =  true)
    {
        $settings =  array_merge(self::fieldsArrayWithDefultValue(), $settings);
        $tableRecor =  new TableRecord(self::DB_TBL);
        $settings =  json_encode($settings);
        $fields = array(
                'usercc_user_id' => $this->userId,
                'usercc_settings' => $settings,
                'usercc_added_on' => date('Y-m-d H:i:s'),
            );
        $tableRecor->setFlds($fields);

        if($tableRecor->addNew(array(),$fields) === false){
            $this->error =  $tableRecor->getError();
            return false;
        }
        if( $setSettingCookie ){
            CommonHelper::setCookieConsent($settings);
        }
        return true;
    }

    public function getCookieSettings() : string
    {
        $search = self::getSearchObject(true);
        $search->addCondition('usercc_user_id', '=', $this->userId);
        $search->addMultipleFields(['usercc_settings']);
        $resultSet = $search->getResultSet();
        $sttings =  FatApp::getDb()->fetch($resultSet);
        if(empty($sttings['usercc_settings'])) {
            return '';
        }
        return $sttings['usercc_settings'];
    }

    
}
