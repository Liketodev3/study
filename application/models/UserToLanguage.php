<?php
class UserToLanguage extends MyAppModel
{
    const DB_TBL = 'tbl_user_to_spoken_languages';
    const DB_TBL_PREFIX = 'utsl_';

    const DB_TBL_TEACH = 'tbl_user_teach_languages';
    const DB_TBL_TEACH_PREFIX = 'utl_';


    public function __construct($userId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'user_id', $userId);
    }

    public static function getUserTeachlanguages($useId, $joinTeachLangLang = false)
    {
        $useId = FatUtility::int($useId);
        $langId = CommonHelper::getLangId();
        $srch = new SearchBase(static::DB_TBL_TEACH, 'utl');
        $srch->addCondition('utl_us_user_id','=',$useId);
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage_id = utl_slanguage_id','tl');
        if($joinTeachLangLang){
            $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT JOIN', 'tlanguage_id = tlanguagelang_tlanguage_id and tlanguagelang_lang_id ='.$langId,'tll');
        }
        return $srch;
    }
}
