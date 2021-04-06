<?php

class TeacherStat extends FatModel
{
    private $userId;

    function __construct(int $userId)
    {
        parent::__construct();
        $this->userId = $userId;
    }

    /**
     * testat_ratings
     * testat_reviewes
     */
    public function setRatingReviewCount()
    {
        $srch = new SearchBase('tbl_teacher_lesson_reviews', 'tlreview');
        $srch->joinTable('tbl_teacher_lesson_rating', 'LEFT JOIN', 'tlrating.tlrating_tlreview_id = tlreview.tlreview_id', 'tlrating');
        $srch->addMultipleFields(['ROUND(AVG(tlrating.tlrating_rating), 2) as ratings', 'COUNT(DISTINCT tlrating.tlreview_id) as reviews']);
        $srch->addCondition('tlreview.tlreview_status', '=', TeacherLessonReview::STATUS_APPROVED);
        $srch->addCondition('tlreview.tlreview_teacher_user_id', '=', $this->userId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            return true;
        }
        $data = ['testat_ratings' => $row['ratings'], 'testat_reviewes' => $row['reviews']];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * testat_students
     * testat_lessions
     */
    public function setStudentLessionCount()
    {
        $srch = new SearchBase('tbl_scheduled_lessons', 'slesson');
        $srch->joinTable('tbl_scheduled_lesson_details', 'LEFT JOIN', 'sldetail.sldetail_slesson_id = slesson.slesson_id', 'sldetail');
        $srch->addMultipleFields(['COUNT(DISTINCT sldetail_learner_id) as students', 'COUNT(slesson.slesson_id) as lessons']);
        $srch->addCondition('tlreview.tlreview_teacher_user_id', '=', $this->userId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            return true;
        }
        $data = ['testat_students' => $row['students'], 'testat_lessions' => $row['lessons']];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * testat_teachlang
     * testat_minprice
     * testat_maxprice
     */
    public function setTeachLangPrices()
    {
        $srch = new SearchBase('tbl_user_teach_languages');
        $srch->addCondition('utl_us_user_id', '=', $this->userId);
        $srch->addFld('MIN(utl_bulk_lesson_amount) AS bulkMinPrice');
        $srch->addFld('MAX(utl_bulk_lesson_amount) AS bulkMaxPrice');
        $srch->addFld('MIN(utl_single_lesson_amount) AS signleMinPrice');
        $srch->addFld('MAX(utl_single_lesson_amount) AS signleMaxPrice');
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        $teachlang = 0;
        $minPrice = 0.0;
        $maxPrice = 0.0;
        if (!empty($row) && $row['bulkMinPrice'] !== null) {
            $teachlang = 1;
            $minPrice = FatUtility::float(min([$row['bulkMinPrice'], $row['signleMinPrice']]));
            $maxPrice = FatUtility::float(max([$row['bulkMaxPrice'], $row['signleMaxPrice']]));
        }
        $data = ['testat_teachlang' => $teachlang, 'testat_minprice' => $minPrice, 'testat_maxprice' => $maxPrice];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * testat_speaklang
     */
    public function setSpeakLang(int $speaklang)
    {
        $data =  ['testat_speaklang' => $speaklang];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * testat_preference
     */
    public function setPreference(int $preference)
    {
        $data = ['testat_preference' => $preference];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * testat_qualification
     */
    public function setQualification(int $qualification)
    {
        $data = ['testat_qualification' => $qualification];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * testat_gavailability
     */
    public function setGavailability(int $availability)
    {
        $data = ['testat_gavailability' => $availability];
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }
}


/***
 * $testat = new TeacherStat($userId);
 * $testat->setRatingReviewCount();
 * /
























