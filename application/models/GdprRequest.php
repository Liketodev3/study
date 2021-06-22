<?php
class GdprRequest extends MyAppModel
{
    const DB_TBL = 'tbl_gdpr_data_requests';
    const DB_TBL_PREFIX = 'gdprdatareq_';

    const TRUNCATE_DATA = 1;
    const ANONYMIZE_DATA = 2;

    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_DELETED_DATA = 3;
    const STATUS_DELETED_REQUEST = 4;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getRequestFromUserId(int $userId)
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition('gdprdatareq_user_id', '=', $userId);
        $srch->addCondition('gdprdatareq_status', '=', static::STATUS_PENDING);
        $rs = $srch->getResultSet();
        $requestData = FatApp::getDb()->fetch($rs);
        return $requestData;
    }

    public static function getGdprRequestTypeArr($langId)
    {
        $arr = [
            static::TRUNCATE_DATA => Label::getLabel('LBL_Erase_Data', $langId),
            static::ANONYMIZE_DATA => Label::getLabel('LBL_Anonymize_Data', $langId)
        ];
        return $arr;
    }

    public static function getStatusArr(int $langId)
    {
        return [
            static::STATUS_PENDING => Label::getLabel('LBL_PENDING', $langId),
            static::STATUS_COMPLETED => Label::getLabel('LBL_COMPLETED', $langId),
            static::STATUS_DELETED_DATA => Label::getLabel('LBL_DELETED_DATA', $langId),
            static::STATUS_DELETED_REQUEST => Label::getLabel('LBL_DELETED_REQUESTED', $langId),
        ];
    }

    public function attachedFileTypeArr()
    {
        return [
            AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_PROFILE_IMAGE,
            AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF,
            AttachedFile::FILETYPE_USER_PROFILE_IMAGE,
            AttachedFile::FILETYPE_USER_PROFILE_CROPED_IMAGE,
            AttachedFile::FILETYPE_USER_QUALIFICATION_FILE,
        ];
    }

    public static function getRequestStatus()
    {
        $getReqStatus = new SearchBase(static::DB_TBL);
        $getReqStatus->addMultipleFields(['gdprdatareq_user_id', 'gdprdatareq_id', 'gdprdatareq_status']);
        return $getReqStatus;
    }

    public function truncateUserPersonalData(int $userId)
    {
        $attchedFile = new AttachedFile();
        foreach ($this->attachedFileTypeArr() as $user_file_type_value) {
            if (!$attchedFile->deleteFile($user_file_type_value, $userId)) {
                continue;
            }
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $user = new user($userId);
        if(!$user->truncateUserData() || !$user->truncateUserCredentials() || !$user->truncateUsersLangData() || !$user->deleteUserBankInfoData() || !$user->deleteUserEmailVerificationData() || !$user->truncateUserWithdrawalRequestsData() || !$user->deleteUserSetting() || !$user->deleteUserQualifications() || !$user->deleteUserEmailChangeRequests()){
            $this->error = $user->getError();
            $db->rollbackTransaction();
            return false;
        }
        if(!$db->updateFromArray(User::DB_TBL, ['user_deleted' => applicationConstants::YES], ['smt' => 'user_id=?', 'vals' => [$userId]])){
            $this->error = $user->getError();
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        return true;
    }
}
