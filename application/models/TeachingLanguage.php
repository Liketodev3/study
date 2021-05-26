<?php

class TeachingLanguage extends MyAppModel
{

    const DB_TBL = 'tbl_teaching_languages';
    const DB_TBL_LANG = 'tbl_teaching_languages_lang';
    const DB_TBL_PREFIX = 'tlanguage_';
    const PROFICIENCY_TOTAL_BEGINNER = 1;
    const PROFICIENCY_BEGINNER = 2;
    const PROFICIENCY_UPPER_BEGINNER = 3;
    const PROFICIENCY_INTERMEDIATE = 4;
    const PROFICIENCY_UPPER_INTERMEDIATE = 5;
    const PROFICIENCY_ADVANCED = 6;
    const PROFICIENCY_UPPER_ADVANCED = 7;
    const PROFICIENCY_NATIVE = 8;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getSearchObject($langId = 0,$active = true)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 't');
        if ($langId > 0) {
            $srch->joinTable(static::DB_TBL_LANG, 'LEFT OUTER JOIN', 't_l.tlanguagelang_tlanguage_id = t.tlanguage_id AND tlanguagelang_lang_id = ' . $langId, 't_l');
        }
        if ($active == true) {
            $srch->addCondition('t.tlanguage_active', '=', applicationConstants::ACTIVE);
        }
        return $srch;
    }

    public static function getProficiencyArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        return [
            static::PROFICIENCY_TOTAL_BEGINNER => Label::getLabel('LBL_Total_Beginner', $langId),
            static::PROFICIENCY_BEGINNER => Label::getLabel('LBL_Beginner', $langId),
            static::PROFICIENCY_UPPER_BEGINNER => Label::getLabel('LBL_Upper_Beginner', $langId),
            static::PROFICIENCY_INTERMEDIATE => Label::getLabel('LBL_Intermediate', $langId),
            static::PROFICIENCY_UPPER_INTERMEDIATE => Label::getLabel('LBL_Upper_Intermediate', $langId),
            static::PROFICIENCY_ADVANCED => Label::getLabel('LBL_Advanced', $langId),
            static::PROFICIENCY_UPPER_ADVANCED => Label::getLabel('LBL_Upper_Advanced', $langId),
            static::PROFICIENCY_NATIVE => Label::getLabel('LBL_Native', $langId),
        ];
    }

    public static function getAllLangs($langId = 0, $active = false)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        $TeachingLangSrch = new TeachingLanguageSearch($langId);
        if ($active) {
            $TeachingLangSrch->addChecks();
        }
        $TeachingLangSrch->doNotCalculateRecords();
        $TeachingLangSrch->addMultiplefields(['tlanguage_id', 'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name']);
        $TeachingLangSrch->addOrder('tlanguage_display_order');
        $rs = $TeachingLangSrch->getResultSet();
        $teachingLanguagesArr = FatApp::getDb()->fetchAllAssoc($rs);
        return $teachingLanguagesArr;
    }

    public static function getAllLangsWithUserCount($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        $teachingLangSrch = new TeachingLanguageSearch($langId);
        $teachingLangSrch->addChecks();
        $teachingLangSrch->joinActiveTeachers();
        $teachingLangSrch->addMultiplefields(['tlanguage_id', 'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name', 'count(DISTINCT utl_us_user_id) as teacherCount']);
        $teachingLangSrch->addGroupBy('utl_slanguage_id');
        $teachingLangSrch->addCondition('user_is_teacher', '=', 1);
        $teachingLangSrch->addCondition('user_country_id', '>', 0);
        $teachingLangSrch->addCondition('credential_active', '=', 1);
        $teachingLangSrch->addCondition('credential_verified', '=', 1);
        $teachingLangSrch->addCondition('utl_single_lesson_amount', '>', 0);
        $teachingLangSrch->addCondition('utl_bulk_lesson_amount', '>', 0);
        $teachingLangSrch->addCondition('utl_slanguage_id', '>', 0);
        /* qualification/experience[ */
        $qSrch = new UserQualificationSearch();
        $qSrch->addMultipleFields(['uqualification_user_id']);
        $qSrch->addCondition('uqualification_active', '=', 1);
        $qSrch->addGroupBy('uqualification_user_id');
        $teachingLangSrch->joinTable("(" . $qSrch->getQuery() . ")", 'INNER JOIN', 'user_id = uqualification_user_id', 'utqual');
        /* ] */
        /* user preferences/skills[ */
        $skillSrch = new UserToPreferenceSearch();
        $skillSrch->addMultipleFields(['utpref_user_id', 'GROUP_CONCAT(utpref_preference_id) as utpref_preference_ids']);
        $skillSrch->addGroupBy('utpref_user_id');
        $teachingLangSrch->joinTable("(" . $skillSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utpref_user_id', 'utpref');
        /* ] */
        $teachingLangSrch->doNotCalculateRecords();
        $teachingLangSrch->setPageSize(6);
        $teachingLangSrch->addOrder('teacherCount', 'desc');
        $teachingLangSrch->addOrder('tlanguage_display_order', 'asc');
        $rs = $teachingLangSrch->getResultSet();
        $teachingLanguagesArr = FatApp::getDb()->fetchAll($rs);
        return $teachingLanguagesArr;
    }

    public static function getAllLangsWithOrderCount($langId = 0)
    {
        $pagesize = 5;
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        $teachingLangSrch = new TeachingLanguageSearch($langId);
        $teachingLangSrch->addChecks();
        $teachingLangSrch->joinOrderProduct();
        $teachingLangSrch->addMultiplefields(['tlanguage_id', 'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name', 'count(o.order_id) as orderCount']);
        $teachingLangSrch->addGroupBy('tlanguage_id');
        $teachingLangSrch->addOrder('count(o.order_id)','desc');    
        $teachingLangSrch->setPageSize($pagesize);
        $teachingLanguagesArr = FatApp::getDb()->fetchAll($teachingLangSrch->getResultSet());
        return $teachingLanguagesArr;
    }

    public static function getLangById($tLangId)
    {
        $tLangId = FatUtility::int($tLangId);
        $langId = CommonHelper::getLangId();
        $teachingLangSrch = new TeachingLanguageSearch($langId);
        $teachingLangSrch->doNotCalculateRecords();
        $teachingLangSrch->addMultipleFields(['GROUP_CONCAT( DISTINCT IFNULL(tlanguage_name, tlanguage_identifier) ) as teacherTeachLanguageName']);
        $teachingLangSrch->addCondition('tlanguage_id', '=', $tLangId);
        $teachingLangSrch->addOrder('tlanguage_display_order');
        $rs = $teachingLangSrch->getResultSet();
        $teachingLanguagesArr = FatApp::getDb()->fetch($rs);
        return $teachingLanguagesArr['teacherTeachLanguageName'];
    }

}
