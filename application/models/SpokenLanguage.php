<?php
class SpokenLanguage extends MyAppModel
{
    const DB_TBL = 'tbl_spoken_languages';
    const DB_TBL_LANG = 'tbl_spoken_languages_lang';
    const DB_TBL_PREFIX = 'slanguage_';

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

    public static function getSearchObject($langId = 0, $active =  true)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 't');

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                't_l.slanguagelang_slanguage_id = t.slanguage_id
			AND slanguagelang_lang_id = ' . $langId,
                't_l'
            );
        }
        if ($active == true) {
            $srch->addCondition('t.slanguage_active', '=', applicationConstants::ACTIVE);
        }
        return $srch;
    }

    public static function getProficiencyArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }

        return array(
            static::PROFICIENCY_TOTAL_BEGINNER => Label::getLabel('LBL_Total_Beginner', $langId),
            static::PROFICIENCY_BEGINNER => Label::getLabel('LBL_Beginner', $langId),
            static::PROFICIENCY_UPPER_BEGINNER => Label::getLabel('LBL_Upper_Beginner', $langId),
            static::PROFICIENCY_INTERMEDIATE => Label::getLabel('LBL_Intermediate', $langId),
            static::PROFICIENCY_UPPER_INTERMEDIATE => Label::getLabel('LBL_Upper_Intermediate', $langId),
            static::PROFICIENCY_ADVANCED => Label::getLabel('LBL_Advanced', $langId),
            static::PROFICIENCY_UPPER_ADVANCED => Label::getLabel('LBL_Upper_Advanced', $langId),
            static::PROFICIENCY_NATIVE => Label::getLabel('LBL_Native', $langId),
        );
    }

    public static function getAllLangs($langId = 0, $active = false)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }

        $spokenLangSrch = new SpokenLanguageSearch($langId);
        if ($active) {
            $spokenLangSrch->addChecks();
        }
        $spokenLangSrch->doNotCalculateRecords();
        $spokenLangSrch->addMultiplefields(array('slanguage_id', 'IFNULL(slanguage_name, slanguage_identifier) as slanguage_name'));
        $spokenLangSrch->addOrder('slanguage_display_order');
        $rs = $spokenLangSrch->getResultSet();
        $spokenLanguagesArr = FatApp::getDb()->fetchAllAssoc($rs);
        return $spokenLanguagesArr;
    }

    public static function getAllLangsWithUserCount($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }

        $spokenLangSrch = new SpokenLanguageSearch($langId);

        $spokenLangSrch->addChecks();
        $spokenLangSrch->joinActiveTeachers();

        $spokenLangSrch->addMultiplefields(array('slanguage_id', 'IFNULL(slanguage_name, slanguage_identifier) as slanguage_name','count(DISTINCT utl_us_user_id) as teacherCount'));
        $spokenLangSrch->addGroupBy('utl_slanguage_id');
        $spokenLangSrch->addCondition('user_is_teacher', '=', 1);
        $spokenLangSrch->addCondition('user_country_id', '>', 0);

        $spokenLangSrch->addCondition('credential_active', '=', 1);
        $spokenLangSrch->addCondition('credential_verified', '=', 1);

        $spokenLangSrch->addCondition('utl_single_lesson_amount', '>', 0);
        $spokenLangSrch->addCondition('utl_bulk_lesson_amount', '>', 0);
        $spokenLangSrch->addCondition('utl_slanguage_id', '>', 0);

        /* qualification/experience[ */
        $qSrch = new UserQualificationSearch();
        $qSrch->addMultipleFields(array('uqualification_user_id'));
        $qSrch->addCondition('uqualification_active', '=', 1);
        $qSrch->addGroupBy('uqualification_user_id');
        $spokenLangSrch->joinTable("(" . $qSrch->getQuery() . ")", 'INNER JOIN', 'user_id = uqualification_user_id', 'utqual');
        /* ] */

        /* user preferences/skills[ */
        $skillSrch = new UserToPreferenceSearch();
        $skillSrch->addMultipleFields(array('utpref_user_id','GROUP_CONCAT(utpref_preference_id) as utpref_preference_ids'));
        $skillSrch->addGroupBy('utpref_user_id');
        $spokenLangSrch->joinTable("(" . $skillSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utpref_user_id', 'utpref');
        /* ] */
        $spokenLangSrch->addOrder('slanguage_display_order');
        $spokenLangSrch->doNotCalculateRecords();
        $spokenLangSrch->setPageSize(6);
        $rs = $spokenLangSrch->getResultSet();
        $spokenLanguagesArr = FatApp::getDb()->fetchAll($rs);
        return $spokenLanguagesArr;
    }

    /* public static function getLangById($sLangId  ){
        $sLangId = FatUtility::int($sLangId);
        $langId = CommonHelper::getLangId();
        $spokenLangSrch = new SpokenLanguageSearch( $langId );
        $spokenLangSrch->doNotCalculateRecords();
        $spokenLangSrch->addMultiplefields( array('slanguage_code') );
        $spokenLangSrch->addCondition('slanguage_id','=',$sLangId);
        $rs = $spokenLangSrch->getResultSet();
        $spokenLanguagesArr = FatApp::getDb()->fetch( $rs );
        return strtolower($spokenLanguagesArr['slanguage_code']);
    } */
}
