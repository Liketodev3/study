<?php
class Gdpr extends MyAppModel
{
    const DB_TBL = 'tbl_gdpr_data_requests';
    const DB_TBL_PREFIX = 'gdprdatareq_';

    const TRUNCATE_DATA = 1;
    const ANONYMIZE_DATA = 2;

    const STATUS_DELETE_DATA = 1;
    const STATUS_COMPLETED = 2;

    // protected $userId;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getSearchObj()
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addOrder('gdprdatareq_added_on', 'DESC');
        return $srch;
    } 

    public static function getGdprReqSentOrNot($userId)
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addFld('gdprdatareq_request_sent'); 
        $srch->addCondition('gdprdatareq_user_id', '=', $userId);
        $rs = $srch->getResultSet();
        $request_sent = FatApp::getDb()->fetch($rs);
        return $request_sent['gdprdatareq_request_sent'];
    }

    public static function getGdprRequestTypeArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId == 0) {
            trigger_error(Label::getLabel('MSG_Language_Id_not_specified.', $this->commonLangId), E_USER_ERROR);
        }

        $arr=array(
            static::TRUNCATE_DATA => Label::getLabel('LBL_Erase_Data', $langId),
            static::ANONYMIZE_DATA => Label::getLabel('LBL_Anonymize_Data', $langId)
        );
        return $arr;
    }

    public function attachedFileTypeArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId == 0) {
            trigger_error(Label::getLabel('MSG_Language_Id_not_specified.', $this->commonLangId), E_USER_ERROR);
        }

        $arr=[
            AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_PROFILE_IMAGE,
            AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF, 
            AttachedFile::FILETYPE_USER_PROFILE_IMAGE, 
            AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE, 
            AttachedFile::FILETYPE_USER_QUALIFICATION_FILE,
            AttachedFile::FILETYPE_COUNTRY_FLAG,
        ];
        return $arr;
    }
    
    public function getGdprAdminStatusArr($langId)
    {
        $langId = FatUtility::int($langId);
        if($langId == 0) {
            trigger_error(Label::getLabel('MSG_Language_Id_not_specified.', $this->commonLangId), E_USER_ERROR);
        }

        $arr = array(
            static::STATUS_DELETE_DATA => Label::getLabel('LBL_Erase_Data'),
            static::STATUS_COMPLETED => Label::getLabel('LBL_Completed'),
        );
        return $arr; 
    }

    public static function getRequestStatus()
    {
        $getReqStatus = new SearchBase(static::DB_TBL);
        $getReqStatus->addMultipleFields(array('gdprdatareq_user_id', 'gdprdatareq_id', 'gdprdatareq_status'));
        return $getReqStatus;
    }

    public function truncateUserPersonalData($userId):bool
    {
        $is_deleted = 0;
        $userId = FatUtility::convertToType($userId, FatUtility::VAR_INT);
        if(1 > $userId)
        {
            $this->error = Label::getLabel('MSG_Invalid_Request', $this->commonLangId);
            return false;
        }
        
        $user_file_type_arr = $this->attachedFileTypeArr($this->commonLangId);
        foreach ($user_file_type_arr as $user_file_type_value) {
            $attchedFile = new AttachedFile();
            if( $attchedFile->deleteFile($user_file_type_value, $userId, $fileId = 0, $record_subid = 0, $langId = -1, $screen = 0) )
            {
                $is_deleted = 1;
            }
        }

        if( User::truncateUserData($userId) && User::truncateUserCredentialsData($userId) && User::truncateUsersLangDataByUserId($userId) && User::deleteUserBankInfoDataByUserId($userId) && User::deleteUserEmailVerificationDataByUserId($userId) && User::truncateUserWithdrawalRequestsDataByUserId($userId) && UserSetting::truncateUserSettingsDataByUserId($userId) && UserQualification::deleteUserQualificationsDataByUserId($userId) && UserEmailChangeRequest::deleteUserEmailChangeRequestDataByUserId($userId))
        {
            $db = FatApp::getDb();
            $updateDeletedField = [
                'user_deleted' => applicationConstants::YES
            ];  
            if ($db->updateFromArray(User::DB_TBL, $updateDeletedField, array(
                'smt' => 'user_id=?',
                'vals' => array(
                    $userId
                )
            ))){
                $is_deleted = 1;
            }
        }
        if($is_deleted == 1){
            return true;
        }else{
            return false;
        }
    }

}