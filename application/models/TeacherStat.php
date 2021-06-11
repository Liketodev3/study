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
        $srch->addMultipleFields(['ROUND(AVG(tlrating.tlrating_rating), 2) as ratings', 'COUNT(DISTINCT tlreview.tlreview_id) as reviews']);
        $srch->addCondition('tlreview.tlreview_status', '=', TeacherLessonReview::STATUS_APPROVED);
        $srch->addCondition('tlreview.tlreview_teacher_user_id', '=', $this->userId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        $data = ['testat_ratings' => 0, 'testat_reviewes' => 0];
        if (!empty($row)) {
            $data = ['testat_ratings' => FatUtility::float($row['ratings']), 'testat_reviewes' => $row['reviews']];
        }
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
        $srch->addCondition('slesson.slesson_teacher_id', '=', $this->userId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
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
        $srch = new SearchBase(UserTeachLanguage::DB_TBL, 'utl');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage.tlanguage_id = utl.utl_tlanguage_id', 'tlanguage');
        $srch->joinTable(TeachLangPrice::DB_TBL, 'LEFT JOIN', 'ustelgpr.ustelgpr_utl_id = utl.utl_id', 'ustelgpr');
        $srch->addCondition('utl.utl_user_id', '=', $this->userId);
        $srch->addFld('MIN(ustelgpr_price) AS minPrice');
        $srch->addFld('MAX(ustelgpr_price) AS maxPrice');
        $srch->addFld('tlanguage_id');
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        $data = [
            'testat_teachlang' => empty($row) ? 0 : 1,
            'testat_minprice' => FatUtility::float($row['minPrice'] ?? 0),
            'testat_maxprice' => FatUtility::float($row['maxPrice'] ?? 0)
        ];
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
    public function setSpeakLang()
    {
        $srch = new SearchBase('tbl_user_to_spoken_languages');
        $srch->addCondition('utsl_user_id', '=', $this->userId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        $speaklang = $srch->recordCount() > 0 ? 1 : 0;
        $data = ['testat_speaklang' => $speaklang];
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
    public function setQualification()
    {
        $srch = new SearchBase('tbl_user_qualifications');
        $srch->addCondition('uqualification_user_id', '=', $this->userId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        $qualification = $srch->recordCount() > 0 ? 1 : 0;
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
     * testat_availability
     */
    public function setAvailability(array $post)
    {
        $availability = json_decode($post['data'] ?? '', true);
        $emptySlots = CommonHelper::getEmptyDaySlots();
        $data = ['testat_availability' => 0, 'testat_timeslots' => json_encode($emptySlots)];
        if (!empty($availability)) {
            $srch = new SearchBase(TeacherGeneralAvailability::DB_TBL);
            $srch->addMultipleFields([
                'tgavl_day',
                'CONCAT(tgavl_date, " ", tgavl_start_time) as startdate',
                'CONCAT(tgavl_end_date, " ", tgavl_end_time) as enddate'
            ]);
            $srch->addCondition('tgavl_user_id', '=', $this->userId);
            $rows = FatApp::getDb()->fetchAll($srch->getResultSet());
            $records = [];
            $currentDate = date('Y-m-d H:i:s');
            $systemTimezone = MyDate::getTimeZone();
            $userTimezone = MyDate::getUserTimeZone();
            $userDate = MyDate::changeDateTimezone($currentDate, $userTimezone, $systemTimezone);
            $hourDiff = MyDate::hoursDiff($currentDate, $userDate);
            foreach ($rows as $key => $row) {
                $row['tgavl_day'] = $row['tgavl_day'] % 6;
                $row['startdate'] = date('Y-m-d H:i:s', strtotime($row['startdate'] . ' ' . $hourDiff . ' hour'));
                $row['enddate'] = date('Y-m-d H:i:s', strtotime($row['enddate'] . ' ' . $hourDiff . ' hour'));
                $tmpRecords = $this->breakIntoDays($row);
                foreach ($tmpRecords as $tmpRecord) {
                    array_push($records, $tmpRecord);
                }
            }
            $timeSlots = [
                ['2018-01-07 00:00:00', '2018-01-07 04:00:00'], ['2018-01-07 04:00:00', '2018-01-07 08:00:00'],
                ['2018-01-07 08:00:00', '2018-01-07 12:00:00'], ['2018-01-07 12:00:00', '2018-01-07 16:00:00'],
                ['2018-01-07 16:00:00', '2018-01-07 20:00:00'], ['2018-01-07 20:00:00', '2018-01-08 00:00:00'],
            ];
            $daySlots = [];
            foreach ($records as $row) {
                $daySlot = [];
                $startdate = strtotime($row['startdate']);
                $enddate = strtotime($row['enddate']);
                foreach ($timeSlots as $index => $slotDates) {
                    $slotStart = strtotime($slotDates[0] . ' +' . $row['tgavl_day'] . ' day');
                    $slotEnd = strtotime($slotDates[1] . ' +' . $row['tgavl_day'] . ' day');
                    if ($startdate >= $slotStart && $enddate >= $slotStart && $startdate <= $slotEnd && $enddate <= $slotEnd) {
                        $daySlot[$index] = ceil(abs($startdate - $enddate) / 3600);
                    } elseif ($startdate >= $slotStart && $enddate >= $slotStart && $startdate <= $slotEnd && $enddate >= $slotEnd) {
                        $daySlot[$index] = ceil(abs($startdate - $slotEnd) / 3600);
                    } elseif ($startdate <= $slotStart && $enddate >= $slotStart && $enddate <= $slotEnd) {
                        $daySlot[$index] = ceil(abs($slotStart - $enddate) / 3600);
                    } elseif ($startdate <= $slotStart && $enddate >= $slotEnd) {
                        $daySlot[$index] = ceil(abs($slotStart - $slotEnd) / 3600);
                    } else {
                        $daySlot[$index] = 0;
                    }
                }
                $daySlots[$row['tgavl_day']][] = $daySlot;
            }
            $filledSlots = [];
            foreach ($daySlots as $day => $slots) {
                $arr = [0, 0, 0, 0, 0, 0];
                foreach ($slots as $slot) {
                    $arr[0] += $slot[0];
                    $arr[1] += $slot[1];
                    $arr[2] += $slot[2];
                    $arr[3] += $slot[3];
                    $arr[4] += $slot[4];
                    $arr[5] += $slot[5];
                }
                $filledSlots['d' . $day] = $arr;
            }
            $flvs = [];
            foreach ($emptySlots as $day => $esv) {
                $flvs[$day] = $filledSlots[$day] ?? $esv;
            }
            $data = ['testat_availability' => 1, 'testat_timeslots' => json_encode($flvs)];
        }
        $record = new TableRecord('tbl_teacher_stats');
        $record->setFldValue('testat_user_id', $this->userId);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    private function breakIntoDays(array $row, array $records = []): array
    {
        if (date('Y-m-d', strtotime($row['startdate'])) != date('Y-m-d', strtotime($row['enddate']))) {
            array_push($records, [
                'tgavl_day' => $row['tgavl_day'],
                'startdate' => $row['startdate'],
                'enddate' => date('Y-m-d', strtotime($row['startdate'])) . ' 23:59:59'
            ]);
            $newStartDate = date('Y-m-d', strtotime($row['startdate'] . ' +1 day')) . ' 00:00:00';
            $newRow = ['tgavl_day' => $row['tgavl_day'] + 1, 'startdate' => $newStartDate, 'enddate' => $row['enddate']];
            return $this->breakIntoDays($newRow, $records);
        } else {
            array_push($records, $row);
            return $records;
        }
    }

    public function setTeachLangPricesBulk()
    {
        FatApp::getDb()->query("UPDATE `tbl_teacher_stats` LEFT JOIN (SELECT IF(COUNT(userTeachLang.`utl_id`) > 0, 1, 0) AS teachLangCount, userTeachLang.`utl_user_id` AS teachLangUserId, MIN(langPrice.`ustelgpr_price`) AS minPrice, MAX(langPrice.`ustelgpr_price`) AS maxPrice FROM `tbl_user_teach_languages` AS userTeachLang INNER JOIN `tbl_teaching_languages` AS teachLang ON teachLang.tlanguage_id = userTeachLang.utl_tlanguage_id LEFT JOIN `tbl_user_teach_lang_prices` AS langPrice ON langPrice.ustelgpr_utl_id = userTeachLang.utl_id GROUP BY userTeachLang.`utl_user_id`) utl ON  utl.teachLangUserId = `testat_user_id` SET `testat_teachlang` = IFNULL(utl.teachLangCount, 0), `testat_minprice` = IFNULL(utl.minPrice, 0), `testat_maxprice` = IFNULL(utl.maxPrice, 0)");
    }

    public function setPreferenceBulk()
    {
        FatApp::getDb()->query("UPDATE `tbl_teacher_stats` LEFT JOIN (SELECT userPre.`utpref_user_id` AS tPreUserId,  IF(COUNT(userPre.`utpref_user_id`) > 0, 1, 0) AS userPreCount FROM `tbl_user_to_preference` AS userPre INNER JOIN `tbl_preferences` AS prefer ON prefer.preference_id = userPre.utpref_preference_id GROUP BY userPre.`utpref_user_id`) teacherPre ON teacherPre.tPreUserId = `testat_user_id` SET `testat_preference` = IFNULL(teacherPre.userPreCount,0)");
    }

    public function setSpeakLangBulk()
    {
        FatApp::getDb()->query("UPDATE `tbl_teacher_stats` LEFT JOIN (SELECT userSpokenLang.`utsl_user_id` AS spokenUserId,  IF(COUNT(userSpokenLang.`utsl_user_id`) > 0, 1, 0) AS userSpokenCount FROM `tbl_user_to_spoken_languages` AS userSpokenLang INNER JOIN `tbl_spoken_languages` AS slanguage ON slanguage.slanguage_id = userSpokenLang.utsl_slanguage_id GROUP BY userSpokenLang.`utsl_user_id`) teacherSpoken ON teacherSpoken.spokenUserId = `testat_user_id` SET `testat_speaklang` = IFNULL(teacherSpoken.userSpokenCount,0)");
    }
}
