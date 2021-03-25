<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeacherSearch
 *
 * @author sher
 */
class TeacherSearch extends SearchBase
{

    private $langId;

    /**
     * Initialize Teacher Search
     * 
     * @param type $langId
     */
    public function __construct(int $langId)
    {
        $this->langId = $langId;
        parent::__construct('tbl_users', 'teacher');
        $this->joinTable('tbl_teacher_stats', 'INNER JOIN', 'testat.testat_user_id = teacher.user_id', 'testat');
        $this->doNotCalculateRecords();
    }

    /**
     * Add Search Listing Fields
     * 
     * @return void
     */
    public function addSearchListingFields(): void
    {
        $fields = static::getSearchListingFields();
        foreach ($fields as $key => $value) {
            $this->addFld($key . ' AS ' . $value);
        }
    }

    public function getSearchListingFields(): array
    {
        return [
            'teacher.user_id' => 'user_id',
            'teacher.user_url_name' => 'user_url_name',
            'teacher.user_first_name' => 'user_first_name',
            'teacher.user_last_name' => 'user_last_name',
            'teacher.user_country_id' => 'user_country_id',
            'testat.testat_students' => 'studentIdsCnt',
            'testat.testat_lessions' => 'teacherTotLessons',
            'testat.testat_ratings' => 'teacher_rating',
            'testat.testat_reviewes' => 'totReviews',
        ];
    }

    /**
     * Apply Primary Conditions
     * 
     * @return void
     */
    public function applyPrimaryConditions(): void
    {
        $this->addCondition('teacher.user_deleted', '=', 0);
        $this->addCondition('teacher.user_is_teacher', '=', 1);
        $this->addCondition('teacher.user_country_id', '>', 0);
        $this->addCondition('teacher.user_url_name', '!=', "");
        $this->addCondition('testat.testat_preference', '=', 1);
        $this->addCondition('testat.testat_qualification', '=', 1);
        $this->addCondition('testat.testat_valid_cred', '=', 1);
        $this->addCondition('testat.testat_teachlang', '=', 1);
        $this->addCondition('testat.testat_speaklang', '=', 1);
        $this->addCondition('testat.testat_gavailability', '=', 1);
    }

    /**
     * Apply Search Conditions
     * 
     * @param array $post
     * @return void
     */
    public function applySearchConditions(array $post): void
    {
        /* Keyword */
        $keyword = trim($post['keyword'] ?? '');
        if (!empty($keyword)) {
            $cond = $this->addCondition('teacher.user_first_name', 'LIKE', '%' . $keyword . '%');
            $cond->attachCondition('teacher.user_last_name', 'LIKE', '%' . $keyword . '%');
            $fullNameField = 'mysql_func_CONCAT(teacher.user_first_name, " ", teacher.user_last_name)';
            $cond->attachCondition($fullNameField, 'LIKE', '%' . $keyword . '%', 'OR', true);
        }

        /* From Country */
        $fromCountries = explode(",", $post['fromCountry'] ?? '');
        $fromCountries = array_filter(FatUtility::int($fromCountries));
        if (count($fromCountries)) {
            $this->addCondition('teacher.user_country_id', 'IN', $fromCountries);
        }

        /* Min & Max Price */
        $minPrice = FatUtility::float($post['minPriceRange'] ?? 0);
        $maxPrice = FatUtility::float($post['maxPriceRange'] ?? 0);
        $minPrice = CommonHelper::getDefaultCurrencyValue($minPrice, false, false);
        $maxPrice = CommonHelper::getDefaultCurrencyValue($maxPrice, false, false);
        $this->addCondition('testat.testat_minprice', '>=', $minPrice);
        $this->addCondition('testat.testat_maxprice', '<=', $maxPrice);

        /* Preferences Filter (Teacher’s accent, Teaches level, Subjects, Test preparations, Lesson includes, Learner’s age group) */
        $preferences = explode(",", $post['preferenceFilter'] ?? '');
        $preferences = array_filter(FatUtility::int($preferences));
        if (count($preferences)) {
            $srch = new SearchBase('tbl_user_to_preference');
            $srch->addFld('DISTINCT utpref_user_id as utpref_user_id');
            $srch->addCondition('utpref_preference_id', 'IN', $preferences);
            $srch->doNotCalculateRecords();
            $srch->doNotLimitRecords();
            $subTable = '(' . $srch->getQuery() . ')';
            $this->joinTable($subTable, 'INNER JOIN', 'utpref.utpref_user_id = teacher.user_id', 'utpref');
        }

        /* Tutor Gender */
        $genders = FatUtility::int(explode(",", $post['gender'] ?? ''));
        if (count($genders) == 1) {
            $this->addCondition('teacher.user_gender', '=', current($genders));
        }


        $spokenLanguage = FatApp::getPostedData('spokenLanguage', FatUtility::VAR_STRING, NULL);
        if (!empty($spokenLanguage)) {
            $srch->addDirectCondition('spoken_language_ids IN (' . $spokenLanguage . ')');
        }

        /* Language Teach [ */
        $langTeach = FatApp::getPostedData('teach_language_id', FatUtility::VAR_STRING, NULL);
        if ($langTeach > 0) {
            if (is_numeric($langTeach)) {
                //$srch->addCondition( 'us.us_teach_slanguage_id', '=', $langTeach );
                $srch->addDirectCondition('FIND_IN_SET(' . $langTeach . ', utl_slanguage_ids)');
            }
        }
        /* ] */
        /* Week Day [ */
        $weekDays = FatApp::getPostedData('filterWeekDays', FatUtility::VAR_STRING, array());
        if ($weekDays) {
            $srch->addCondition('ta.tgavl_day', 'IN', $weekDays);
        }
        /* ] */
        /* Time Slot [ */
        $timeSlots = FatApp::getPostedData('filterTimeSlots', FatUtility::VAR_STRING, array());

        $systemTimeZone = MyDate::getTimeZone();
        $user_timezone = MyDate::getUserTimeZone();

        if ($timeSlots) {
            $formatedArr = CommonHelper::formatTimeSlotArr($timeSlots);
            if ($formatedArr) {
                foreach ($formatedArr as $key => $formatedVal) {
                    $startTime = date('Y-m-d') . ' ' . $formatedVal['startTime'];
                    $endTime = date('Y-m-d') . ' ' . $formatedVal['endTime'];
                    $startTime = date('H:i:s', strtotime(MyDate::changeDateTimezone($startTime, $user_timezone, $systemTimeZone)));
                    $endTime = date('H:i:s', strtotime(MyDate::changeDateTimezone($endTime, $user_timezone, $systemTimeZone)));
                    if ($key == 0) {
                        $cnd = $srch->addCondition('tgavl_start_time', '<=', $startTime, 'AND');
                        $cnd->attachCondition('tgavl_end_time', '>=', $startTime, 'AND');
                    } else {
                        $newSrch = $cnd->attachCondition('tgavl_start_time', '<=', $endTime, 'OR');
                        $newSrch->attachCondition('tgavl_end_time', '>=', $endTime, 'AND');
                    }
                }
            }
        }


        /* ] */
        if (isset($postedData['keyword']) && !empty($postedData['keyword'])) {
            $cond = $srch->addCondition('user_first_name', 'LIKE', '%' . $postedData['keyword'] . '%');
            $cond->attachCondition('user_last_name', 'LIKE', '%' . $postedData['keyword'] . '%');
            $cond->attachCondition('mysql_func_CONCAT(user_first_name, " ", user_last_name)', 'LIKE', '%' . $postedData['keyword'] . '%', 'OR', true);
        }
    }

    /**
     * Apply Order By
     * 
     * @param string $sortOrder
     * @return void
     */
    public function applyOrderBy(string $sortOrder): void
    {
        switch ($sortOrder) {
            case 'price_asc':
                $this->addOrder('testat.testat_minprice', 'ASC');
                break;
            case 'price_desc':
                $this->addOrder('testat.testat_minprice', 'DESC');
                break;
            case 'popularity_desc':
                $this->addOrder('testat.testat_students', 'DESC');
                $this->addOrder('testat.testat_lessions', 'DESC');
                $this->addOrder('testat.testat_reviewes', 'DESC');
                $this->addOrder('testat.testat_ratings', 'DESC');
                break;
            default:
                $this->addOrder('testat.testat_ratings', 'DESC');
                $this->addOrder('teacher.user_id', 'ASC');
                break;
        }
    }

    /**
     * Format Search Data
     * 
     * @param array $records
     * @param int $userId
     * @return array
     */
    public static function formatSearchData(array $records, int $userId): array
    {
        $langId = CommonHelper::getLangId();
        $teacherIds = array_column($records, 'user_id');
        $countryIds = array_column($records, 'user_country_id');
        $countries = static::getCountryNames($langId, $countryIds);
        $favorites = static::getFavoriteTeachers($userId, $teacherIds);
        $langData = static::getTeachersLangData($langId, $teacherIds);

        foreach ($records as $key => $record) {
            $record['uft_id'] = $favorites[$record['user_id']] ?? 0;
            $record['user_country_name'] = $countries[$record['user_country_id']] ?? '';
            $record['userlang_user_profile_Info'] = $langData[$record['user_id']] ?? '';
            $records[$key] = $record;
        }
        return $records;
    }

    /**
     * Get Countries Names
     * 
     * @param int $langId
     * @param array $countryIds
     * @return array
     */
    public static function getCountryNames(int $langId, array $countryIds): array
    {
        if ($langId == 0 || count($countryIds) == 0) {
            return [];
        }
        $srch = new SearchBase('tbl_countries_lang', 'countrylang');
        $srch->addCondition('countrylang.countrylang_lang_id', '=', $langId);
        $srch->addCondition('countrylang.countrylang_country_id', 'IN', $countryIds);
        $srch->addMultipleFields(['countrylang_country_id', 'country_name']);
        $srch->doNotCalculateRecords();
        $result = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($result);
    }

    /**
     * Get Teachers LangData
     * 
     * @param int $langId
     * @param array $teacherIds
     * @return array
     */
    public static function getTeachersLangData(int $langId, array $teacherIds): array
    {
        if ($langId == 0 || count($teacherIds) == 0) {
            return [];
        }
        $srch = new SearchBase('tbl_users_lang', 'userlang');
        $srch->addCondition('userlang.userlang_lang_id', '=', $langId);
        $srch->addCondition('userlang.userlang_user_id', 'IN', $teacherIds);
        $srch->addMultipleFields(['userlang_user_id', 'userlang_user_profile_Info']);
        $srch->doNotCalculateRecords();
        $result = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($result);
    }

    /**
     * Get Favorite Teachers
     * 
     * @param int $userId
     * @param array $teacherIds
     * @return array
     */
    public static function getFavoriteTeachers(int $userId, array $teacherIds): array
    {
        if ($userId == 0 || count($teacherIds) == 0) {
            return [];
        }
        $srch = new SearchBase('tbl_user_favourite_teachers', 'uft');
        $srch->addCondition('uft.uft_teacher_id', 'IN', $teacherIds);
        $srch->addCondition('uft.uft_user_id', '=', $userId);
        $srch->addMultipleFields(['uft_teacher_id', 'uft_id']);
        $srch->doNotCalculateRecords();
        $result = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($result);
    }

    /**
     * Get Record Count
     * to be updated as per requirements
     * 
     * @return int
     */
    public function getRecordCount(): int
    {
        $db = FatApp::getDb();
        $order = $this->order;
        $page = $this->page;
        $pageSize = $this->pageSize;
        $this->limitRecords = false;
        $this->order = [];
        $maxCount = 1000;
        $qry = $this->getQuery() . ' LIMIT ' . $maxCount . ', 1';
        if ($db->totalRecords($db->query($qry)) > 0) {
            $recordCount = $maxCount;
        } else {
            if (empty($this->groupby) && empty($this->havings)) {
                $this->addFld('COUNT(user_id) AS total');
                $rs = $db->query($this->getQuery());
            } else {
                $this->addFld('user_id as user_id');
                $rs = $db->query('SELECT COUNT(user_id) AS total FROM (' . $this->getQuery() . ') t');
            }
            $recordCount = FatUtility::int($db->fetch($rs)['total'] ?? 0);
        }

        $this->order = $order;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->limitRecords = true;
        return $recordCount;
    }

    /**
     * Remove All Conditions
     * 
     * @return void
     */
    public function removeAllConditions(): void
    {
        $this->conditions = [];
    }

}
