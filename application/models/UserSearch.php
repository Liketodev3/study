<?php

use Stripe\ApplicationFee;

class UserSearch extends SearchBase
{
    private $isCredentialsJoined;
    private $isUserSettingsJoined;
    private $isUserTeachLanguageJoined;
    private $isUserCountryJoined;
    private $isUserStateJoined;

    public function __construct($doNotCalculateRecords = true, $skipDeleted = true)
    {
        $this->isCredentialsJoined = false;
        $this->isUserSettingsJoined = false;
        $this->isUserTeachLanguageJoined = false;
        $this->isUserCountryJoined = false;
        $this->isUserStateJoined = false;

        parent::__construct(User::DB_TBL, 'u');

        if (true === $doNotCalculateRecords) {
            $this->doNotCalculateRecords();
        }

        if (true === $skipDeleted) {
            $this->addCondition('user_deleted', '=', 0);
        }

        /* if( true === $joinCredentials ){
            $this->joinCredentials( $isActive, $isEmailVerified );
        } */
    }

    public function setTeacherDefinedCriteria($langCheck = true, $addUserSettingJoin = true)
    {
        $this->joinCredentials();

        $this->addCondition('user_is_teacher', '=', 1);
        $this->addCondition('user_country_id', '>', 0);

        /* $this->addCondition('credential_active', '=', 1);
        $this->addCondition('credential_verified', '=', 1); */

        /* additional conditions[ */
        if($addUserSettingJoin) {
            $this->joinUserSettings();
        }
       
        /* teachLanguage[ */
        if ($langCheck) {
            $tlangSrch = $this->getMyTeachLangQry();
            $this->joinTable("(" . $tlangSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utl_us_user_id', 'utls');
        }
        /* ] */
        /* qualification/experience[ */
        /* $qSrch = new UserQualificationSearch();
        $qSrch->addMultipleFields(array('uqualification_user_id'));
        $qSrch->addCondition('uqualification_active', '=', 1);
        $qSrch->addGroupBy('uqualification_user_id');
        $this->joinTable("(" . $qSrch->getQuery() . ")", 'INNER JOIN', 'user_id = uqualification_user_id', 'utqual'); */
        $this->joinTable(UserQualification::DB_TBL, 'INNER JOIN', 'user_id = uqualification_user_id AND uqualification_active = '.ApplicationConstants::ACTIVE, 'utqual');
        /* ] */

        /* user preferences/skills[ */
        /* $skillSrch = new UserToPreferenceSearch();
        $skillSrch->addMultipleFields(array('utpref_user_id','GROUP_CONCAT(utpref_preference_id) as utpref_preference_ids'));
        $skillSrch->addGroupBy('utpref_user_id');
        $this->joinTable("(" . $skillSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utpref_user_id', 'utpref'); */
        $this->joinTable(Preference::DB_TBL_USER_PREF, 'INNER JOIN', 'user_id = utpref_user_id', 'utpref');
        // $this->addFld('GROUP_CONCAT(utpref_preference_id) as utpref_preference_ids');
        /* ] */
    }

    public function joinCredentials($isActive = true, $isEmailVerified = true)
    {
        if (true === $this->isCredentialsJoined) {
            return;
        }
        $this->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'u.user_id = cred.credential_user_id', 'cred');

        if (true === $isActive) {
            $this->addCondition('cred.credential_active', '=', 1);
        }

        if (true === $isEmailVerified) {
            $this->addCondition('cred.credential_verified', '=', 1);
        }

        $this->isCredentialsJoined = true;
    }

    public function joinUserSettings()
    {
        if (true === $this->isUserSettingsJoined) {
            return;
        }
        $this->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'u.user_id = us_user_id', 'us');
        $this->isUserSettingsJoined = true;
    }

    public function joinUserTeachLanguage($langId = 0)
    {
        if (true === $this->isUserTeachLanguageJoined) {
            return;
        }

        if (false === $this->isUserSettingsJoined) {
            trigger_error("Please join user settings table first to join user teacher language", E_USER_ERROR);
        }

        $this->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'us.us_teach_slanguage_id = tlanguage_id', 'teachl');

        $langId = FatUtility::int($langId);
        if ($langId > 0) {
            $this->joinTable(TeachingLanguage::DB_TBL.'_lang', 'LEFT JOIN', 'teachl.tlanguage_id = teachl_lang.tlanguagelang_tlanguage_id AND teachl_lang.tlanguagelang_lang_id = ' . $langId, 'teachl_lang');
        }

        $this->isUserTeachLanguageJoined = true;
    }

    public function joinUserCountry($langId = 0)
    {
        if (true === $this->isUserCountryJoined) {
            return;
        }

        /* this join can be skipped, but kept only to fetch country_code from this table[ */
        $this->joinTable(Country::DB_TBL, 'LEFT JOIN', 'user_country_id = country_id', 'c');
        /* ] */

        $langId = FatUtility::int($langId);
        if ($langId > 0) {
            $this->joinTable(Country::DB_TBL.'_lang', 'LEFT JOIN', 'user_country_id = countrylang_country_id AND countrylang_lang_id = ' . $langId, 'cl');
        }
        $this->isUserCountryJoined = true;
    }

    public function joinUserState($langId = 0)
    {
        if (true == $this->isUserStateJoined) {
            return;
        }

        $this->joinTable(State::DB_TBL, 'LEFT JOIN', 'user_state_id = state_id', 'state');

        $langId = FatUtility::int($langId);
        if ($langId > 0) {
            $this->joinTable(State::DB_TBL.'_lang', 'LEFT JOIN', 'user_state_id = statelang_state_id', 'state_lang');
        }
        $this->isUserStateJoined = true;
    }

    public function joinUserSpokenLanguages($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        /* $slSrch = new searchBase(UserToLanguage::DB_TBL);
        $slSrch->joinTable(SpokenLanguage::DB_TBL, 'LEFT JOIN', 'slanguage_id = utsl_slanguage_id');
        $slSrch->joinTable(SpokenLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'slanguagelang_slanguage_id = utsl_slanguage_id AND slanguagelang_lang_id = '. $langId, 'sl_lang');
        $slSrch->doNotCalculateRecords();
        $slSrch->doNotLimitRecords();
        $slSrch->addMultipleFields(array('utsl_user_id', 'GROUP_CONCAT( IFNULL(slanguage_name, slanguage_identifier) ORDER BY slanguage_name,slanguage_identifier ) as spoken_language_names', 'GROUP_CONCAT(utsl_slanguage_id) as spoken_language_ids',  'GROUP_CONCAT(utsl_proficiency) as spoken_languages_proficiency'));
        $slSrch->addGroupBy('utsl_user_id');
        $slSrch->addCondition('slanguage_active', '=', 1);

        $this->joinTable("(" . $slSrch->getQuery() . ")", 'INNER JOIN', 'user_id = utsl.utsl_user_id', 'utsl'); */

        $this->addMultipleFields(array('utsl_user_id', 'GROUP_CONCAT( DISTINCT IFNULL(slanguage_name, slanguage_identifier) ORDER BY slanguage_name,slanguage_identifier ) as spoken_language_names', 'GROUP_CONCAT(DISTINCT utsl_slanguage_id) as spoken_language_ids',  'GROUP_CONCAT(DISTINCT utsl_proficiency) as spoken_languages_proficiency'));
        $this->joinTable(UserToLanguage::DB_TBL, 'INNER JOIN', 'user_id = utsl.utsl_user_id', 'utsl');
        $this->joinTable(SpokenLanguage::DB_TBL, 'LEFT JOIN', 'slanguage_id = utsl_slanguage_id AND slanguage_active ='. applicationConstants::ACTIVE);
        $this->joinTable(SpokenLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'slanguagelang_slanguage_id = utsl_slanguage_id AND slanguagelang_lang_id = '. $langId, 'sl_lang');
        
    }

    public function joinFavouriteTeachers($user_id)
    {
        $this->joinTable(User::DB_TBL_TEACHER_FAVORITE, 'LEFT OUTER JOIN', 'uft.uft_teacher_id = u.user_id and uft.uft_user_id = '.$user_id, 'uft');
    }

    public function joinUserAvailibility()
    {
        $this->joinTable(TeacherGeneralAvailability::DB_TBL, 'INNER JOIN', 'u.user_id = tgavl_user_id', 'ta');
    }

    public function joinTeacherLessonData($userId = 0, $getCompletedScheduledLesson = true, $getCanCelledScheduledLesson = true)
    {
        $scheduledLessonDetailsSrch = new ScheduledLessonDetailsSearch();
        $scheduledLessonDetailsSrch->addGroupBy('sld.sldetail_slesson_id');        
        if ($userId) {
            $this->joinTable(ScheduledLesson::DB_TBL, 'LEFT JOIN', 'u.user_id = sl.slesson_teacher_id AND sl.slesson_teacher_id = '.$userId, 'sl');
            $this->joinTable('('.$scheduledLessonDetailsSrch->getQuery().')', 'LEFT OUTER JOIN', 'sld.sldetail_slesson_id = sl.slesson_id', 'sld');
        } else {
            $this->joinTable(ScheduledLesson::DB_TBL, 'LEFT JOIN', 'u.user_id = sl.slesson_teacher_id', 'sl');
            $this->joinTable(ScheduledLessonDetails::DB_TBL, 'LEFT OUTER JOIN', 'sld.sldetail_slesson_id = sl.slesson_id', 'sld');
            $this->addFld('count(DISTINCT sldetail_learner_id) as studentIdsCnt');
        }
        $this->addGroupBy('sl.slesson_teacher_id');
        $this->addFld('count(DISTINCT sl.slesson_id) as teacherTotLessons');
        //$this->addFld( 'SUM(CASE WHEN sl.slesson_status = '.ScheduledLesson::STATUS_SCHEDULED.' THEN 1 ELSE 0 END) AS teacherSchLessons' );
        if($getCompletedScheduledLesson) {
            $this->addFld('(select COUNT(IF(slesson_status="'.ScheduledLesson::STATUS_COMPLETED .'",1,null)) from '. ScheduledLesson::DB_TBL . ' WHERE slesson_teacher_id = u.user_id ) as teacherSchLessons');
        }

        if($getCanCelledScheduledLesson) {
            $this->addFld('(select COUNT(IF(slesson_status="'.ScheduledLesson::STATUS_CANCELLED .'",1,null)) from '. ScheduledLesson::DB_TBL . ' WHERE slesson_teacher_id = u.user_id ) as teacherCancelledLessons');
        }
        
        $this->addFld('GROUP_CONCAT(DISTINCT sldetail_learner_id) as studentIds');
    }

    public function joinLearnerLessonData($userId)
    {
        if ($userId) {
            $this->joinTable(ScheduledLessonDetails::DB_TBL, 'LEFT OUTER JOIN', 'u.user_id = sld.sldetail_learner_id AND sld.sldetail_learner_id = '.$userId, 'sld');
            $this->addGroupBy('sld.sldetail_learner_id');
        } else {
            $this->joinTable(ScheduledLessonDetails::DB_TBL, 'LEFT OUTER JOIN', 'u.user_id = sld.sldetail_learner_id', 'sld');
            $this->addGroupBy('sld.sldetail_learner_id');
            $this->addFld('count(DISTINCT slesson_teacher_id) as teacherIdsCnt');
        }
        $this->joinTable(ScheduledLesson::DB_TBL, 'LEFT OUTER JOIN', 'sld.sldetail_slesson_id = sl.slesson_id', 'sl');
        $this->addGroupBy('sldetail_learner_id');
        $this->addFld('count(sldetail_id) as learnerTotLessons');
        $this->addFld('SUM(CASE WHEN sldetail_learner_status = '.ScheduledLesson::STATUS_SCHEDULED.' THEN 1 ELSE 0 END) AS learnerSchLessons');
        $this->addFld('GROUP_CONCAT(DISTINCT slesson_teacher_id) as teacherIds');
    }

    public function joinRatingReview()
    {
        $this->joinTable(TeacherLessonReview::DB_TBL, 'LEFT OUTER JOIN', 'u.user_id = tlr.tlreview_teacher_user_id AND tlr.tlreview_status = '. TeacherLessonReview::STATUS_APPROVED, 'tlr');
        $this->joinTable(TeacherLessonRating::DB_TBL, 'LEFT OUTER JOIN', 'tlrating.tlrating_tlreview_id = tlr.tlreview_id', 'tlrating');
        $this->addMultipleFields(array("ROUND(AVG(tlrating_rating),2) as teacher_rating","count(DISTINCT tlreview_id) as totReviews"));
    }

    public function joinUserLang($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        $this->joinTable(User::DB_TBL_LANG, 'LEFT OUTER JOIN', 'ulg.'.User::DB_TBL_LANG_PREFIX.'user_id = u.user_id and ulg.'.User::DB_TBL_LANG_PREFIX.'lang_id = '.$langId, 'ulg');
    }

    public function getMyTeachLangQry($addJoinTeachLangTable = false, $langId = 0, $keyword = '')
    {
        $tlangSrch = new SearchBase(UserToLanguage::DB_TBL_TEACH, 'utl');
        if($addJoinTeachLangTable) {

            $langId = FatUtility::int($langId);
            if ($langId < 1) {
                $langId = CommonHelper::getLangId();
            }
            $tlangSrch->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tlanguage_id = utl.utl_slanguage_id');
            $tlangSrch->joinTable(TeachingLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'tlanguagelang_tlanguage_id = utl.utl_slanguage_id AND tlanguagelang_lang_id = '. $langId, 'sl_lang');
            $tlangSrch->addCondition('tlanguage_active', '=', '1');
            $tlangSrch->addMultipleFields(array('GROUP_CONCAT( DISTINCT IFNULL(tlanguage_name, tlanguage_identifier) ORDER BY tlanguage_name,tlanguage_identifier ) as teacherTeachLanguageName'));
            if (!empty($keyword)) {
                $cnd = $tlangSrch->addCondition('tlanguage_name', 'LIKE', '%' . $keyword . '%');
                $cnd->attachCondition('tlanguage_identifier', 'LIKE', '%' . $keyword . '%');
            }
            $tlangSrch->addOrder('tlanguage_display_order');
        }
        $tlangSrch->addMultipleFields(array('utl_us_user_id','GROUP_CONCAT(utl_id) as utl_ids','max(utl_single_lesson_amount) as maxPrice','min(utl_bulk_lesson_amount) as minPrice','GROUP_CONCAT(utl_slanguage_id) as utl_slanguage_ids'));
        $tlangSrch->doNotCalculateRecords();
        $tlangSrch->doNotLimitRecords();
        $tlangSrch->addCondition('utl_single_lesson_amount', '>', 0);
        $tlangSrch->addCondition('utl_bulk_lesson_amount', '>', 0);
        $tlangSrch->addCondition('utl_slanguage_id', '>', 0);
        $tlangSrch->addGroupBy('utl_us_user_id');
        return $tlangSrch;
    }

    public function getTopRatedTeachers()
    {
        $this->addMultipleFields(array('u.*','utls.*','cl.*'));
        $this->setTeacherDefinedCriteria();
        $this->addGroupBy('u.user_id');
        $this->joinRatingReview();
        $this->joinUserCountry(CommonHelper::getLangId());
        //$this->addHaving('teacher_rating','>',0);
        $this->addOrder('teacher_rating', 'DESC');
        $this->setPageSize(6);
        $db = FatApp::getDb();
        $rs = $this->getResultSet();
        //echo $this->getQuery();
        return $teachersList = $db->fetchAll($rs);
    }

    public function joinUserTeachingLanguages($langId = 0, $keyword = '')
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }

        $this->joinTable(UserToLanguage::DB_TBL_TEACH, 'INNER  JOIN', 'u.user_id = utsl1.utl_us_user_id AND utl_single_lesson_amount>0 AND utl_bulk_lesson_amount>0', 'utsl1');
        $this->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage_id = utsl1.utl_slanguage_id AND tlanguage_active = '.applicationConstants::ACTIVE);
        $this->joinTable(TeachingLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'tlanguagelang_tlanguage_id = utsl1.utl_slanguage_id AND tlanguagelang_lang_id = '. $langId, 'tl_lang');
        $this->addMultipleFields(array(
            'utsl1.utl_us_user_id', 
            'GROUP_CONCAT( DISTINCT IFNULL(tlanguage_name, tlanguage_identifier) ) as teacherTeachLanguageName',
            'GROUP_CONCAT(DISTINCT utl_id) as utl_ids',
            'max(utl_single_lesson_amount) as maxPrice',
            'min(utl_bulk_lesson_amount) as minPrice',
            'GROUP_CONCAT(DISTINCT utl_slanguage_id) as utl_slanguage_ids'
        ));

        // $this->addCondition('tlanguage_active', '=', '1');

        if (!empty($keyword)) {
            $cnd = $this->addCondition('tlanguage_name', 'LIKE', '%' . $keyword . '%');
            $cnd->attachCondition('tlanguage_identifier', 'LIKE', '%' . $keyword . '%');
        }
    }
}
