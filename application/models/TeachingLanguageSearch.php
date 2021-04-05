<?php

class TeachingLanguageSearch extends SearchBase
{

    public function __construct($langId = 0)
    {
        parent::__construct(TeachingLanguage::DB_TBL, 'tl');
        $langId = FatUtility::int($langId);
        if ($langId > 0) {
            $this->joinTable(TeachingLanguage::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'tl_l.tlanguagelang_tlanguage_id = tl.tlanguage_id AND tl_l.tlanguagelang_lang_id = ' . $langId, 'tl_l');
        }
    }

    public function addChecks()
    {
        $this->addCondition('tlanguage_active', '=', applicationConstants::ACTIVE);
        $this->doNotCalculateRecords();
        $this->addMultipleFields(['tlanguage_id', 'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name']);
    }

    public function joinActiveTeachers()
    {
        $this->joinTable(UserToLanguage::DB_TBL_TEACH, 'INNER JOIN', 'tl.tlanguage_id = utl.utl_slanguage_id', 'utl');
        $this->joinTable(User::DB_TBL, 'INNER JOIN', 'u.user_id = utl.utl_us_user_id', 'u');
        $this->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'u.user_id = cred.credential_user_id', 'cred');
    }

}
