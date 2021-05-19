<?php

class TeacherWeeklySchedule extends MyAppModel
{

    const DB_TBL = 'tbl_teachers_weekly_schedule';
    const DB_TBL_PREFIX = 'twsch_';
    const UNAVAILABLE = 0;
    const AVAILABLE = 1;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getWeeklySchCssClsNameArr()
    {
        return [
            static::UNAVAILABLE => 'slot_unavailable',
            static::AVAILABLE => 'slot_available',
        ];
    }

    public static function getWeeklyScheduleJsonArr($userId, $start, $end, $isAvailabel = false)
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            trigger_error(Label::getLabel('LBL_Invalid_Request'), E_USER_ERROR);
        }
        $srch = new TeacherWeeklyScheduleSearch();
        $srch->addMultipleFields(['twsch_id', 'twsch_user_id', 'twsch_date', 'twsch_end_date', 'twsch_start_time', 'twsch_end_time', 'twsch_weekyear', 'twsch_is_available']);
        $srch->addCondition('twsch_user_id', ' = ', $userId);
        $srch->addCondition('mysql_func_CONCAT(twsch_date," ",twsch_start_time)', '<', $end, 'AND', true);
        $srch->addCondition('mysql_func_CONCAT(twsch_end_date," ",twsch_end_time)', '>', $start, 'AND', true);
        if ($isAvailabel) {
            $srch->addCondition('twsch_is_available', '=', self::AVAILABLE);
        }
        $srch->addOrder('twsch_date', 'asc');
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }

    public function deleteTeacherWeeklySchedule($userId, $startTime, $endTime, $date, $day, $id)
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        /* code added on 17-07-2019 */
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        if (strtotime($date . ' ' . $endTime) <= strtotime($date . ' ' . $startTime)) {
            $_endDate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
        } else {
            $_endDate = $date;
        }
        $startDateTime = MyDate::changeDateTimezone($date . ' ' . $startTime, $user_timezone, $systemTimeZone);
        $endDateTime = MyDate::changeDateTimezone($_endDate . ' ' . $endTime, $user_timezone, $systemTimeZone);
        $_day = MyDate::getDayNumber($startDateTime);
        $date = date('Y-m-d', strtotime($startDateTime));
        $endDate = date('Y-m-d', strtotime($endDateTime));
        $startTime = date('H:i:s', strtotime($startDateTime));
        $endTime = date('H:i:s', strtotime($endDateTime));
        $db = FatApp::getDb();
        $srch = new TeacherWeeklyScheduleSearch();
        $srch->addMultipleFields(['twsch_user_id', 'twsch_is_available']);
        $srch->addCondition('twsch_user_id', '=', $userId);
        $srch->addCondition('mysql_func_CONCAT(twsch_date, " ", twsch_start_time )', '=', $startDateTime, 'AND', true);
        $srch->addCondition('mysql_func_CONCAT(twsch_end_date, " ", twsch_end_time )', '=', $endDateTime, 'AND', true);
        $rs = $srch->getResultSet();
        $weeklySchCount = $rs->totalRecords();
        $weeklyDate = FatApp::getDb()->fetch($rs);
        $gaSrch = new TeacherGeneralAvailabilitySearch();
        $gaSrch->addMultipleFields(['tgavl_user_id']);
        $gaSrch->addCondition('tgavl_user_id', '=', $userId);
        $gaSrch->addCondition('tgavl_day', '=', $_day);
        $gaSrch->addCondition('tgavl_start_time', '=', $startTime);
        $gaSrch->addCondition('tgavl_end_time', '=', $endTime);
        $gaRs = $gaSrch->getResultSet();
        $gaCount = $gaRs->totalRecords();
        if ($weeklySchCount > 0 && $gaCount > 0) {
            $weeklyDateAvailability = ($weeklyDate['twsch_is_available'] == TeacherWeeklySchedule::UNAVAILABLE) ? TeacherWeeklySchedule::AVAILABLE : TeacherWeeklySchedule::UNAVAILABLE;
            $db->updateFromArray(TeacherWeeklySchedule::DB_TBL, ['twsch_is_available' => $weeklyDateAvailability],
                    ['smt' => 'twsch_date = ? and twsch_end_date = ? and twsch_start_time = ? and twsch_end_time = ? and twsch_user_id = ?', 'vals' => [$date, $endDate, $startTime, $endTime, $userId]]);
            return true;
        }
        $deleteRecords = $db->deleteRecords(TeacherWeeklySchedule::DB_TBL, ['smt' => 'twsch_user_id = ? and twsch_id = ?', 'vals' => [$userId, $id]]);
        if ($db->getError()) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }

    public function addTeacherWeeklySchedule($post, int $userId)
    {
        if (empty($post) || $userId < 1) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }

        $postJson = json_decode($post['data']);
        if (empty($postJson)) {
            return true;
        }

        $postJsonArr = [];
        $dateArray = [];
        $postDataId = [];
        $needToDelete = [];
        /* code added  on 12-07-2019 */
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        foreach ($postJson as $k => $postObj) {
            $dateTime =  MyDate::changeDateTimezone($postObj->date. ' ' . $postObj->start, $user_timezone, $systemTimeZone);
            $date = date('Y-m-d', strtotime($dateTime));
            $dateArray[$date] = $date;
           
            if (!empty($postObj->_id) && $postObj->action != "fromGeneralAvailability") {
                $postDataId[$postObj->_id] = $postObj->_id;
            }
            
            $postJsonArr[] = $postObj;
        }
       
        if (!empty($dateArray)) {
            $db = FatApp::getDb();
            $srch = new TeacherWeeklyScheduleSearch();
            $srch->addMultipleFields(['twsch_id', 'twsch_is_available']);
            $srch->addCondition('twsch_user_id', '=', $userId);
            $srch->addCondition('twsch_date', 'IN', $dateArray);
            $srch->doNotLimitRecords();
            $srch->doNotCalculateRecords();
            $rs = $srch->getResultSet();
            $dateRecordId = $db->fetchAllAssoc($rs);
            $needToDelete = array_diff_key($dateRecordId, $postDataId);
        }
       
        $db->startTransaction();
        foreach ($postJsonArr as $val) {
            if(empty($val->date) && empty($val->start) && empty($val->endDate) && empty($val->end)){
                continue;
            }

            $val->classtype = (isset($val->classtype)) ? $val->classtype : 0;
            $val->action = (!empty($val->action)) ?  $val->action : '';

            $startDateTime = MyDate::changeDateTimezone($val->date . ' ' . $val->start, $user_timezone, $systemTimeZone);
            $endDateTime = MyDate::changeDateTimezone($val->endDate . ' ' . $val->end, $user_timezone, $systemTimeZone);
            
            $startDateTimeUnix = strtotime($startDateTime);
            $endDateTimeUnix = strtotime($endDateTime);

            $weekDates = MyDate::getWeekStartAndEndDate(new DateTime($startDateTime));
            $midPoint = (strtotime($weekDates['weekStart']) + strtotime($weekDates['weekEnd'])) / 2;
            $twschWeekYear = date('W-Y', $midPoint);

            $updateArr = [
                'twsch_start_time' => date('H:i:s', $startDateTimeUnix),
                'twsch_end_time' => date('H:i:s', $endDateTimeUnix),
                'twsch_weekyear' => $twschWeekYear,
                "twsch_is_available" => $val->classtype,
                'twsch_date' => date('Y-m-d', $startDateTimeUnix),
                'twsch_end_date' => date('Y-m-d', $endDateTimeUnix),
                'twsch_user_id' => $userId,
                'twsch_id' => 0,
            ];
            if (!empty($val->_id) && $val->action != "fromGeneralAvailability") {
                $updateArr['twsch_id'] = $val->_id;
            }
            $record = new TableRecord(self::DB_TBL);
            $record->assignValues($updateArr);
            if (!$record->addNew([], $updateArr)) {
                $db->rollbackTransaction();
                $this->error = $record->getError();
                return false;
            }
        }
        if (!empty($needToDelete)) {
            if (!$db->query('DELETE FROM ' . TeacherWeeklySchedule::DB_TBL . ' WHERE twsch_user_id = ' . $userId .
                            ' and twsch_id IN (' . implode(",", array_keys($needToDelete)) . ')')) {
                $db->rollbackTransaction();
                $this->error = $db->getError();
                return false;
            }
        }
        $db->commitTransaction();
        return true;
    }

    public function checkCalendarTimeSlotAvailability($userId, $startTime, $endTime)
    {
        $this->error = '';
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        if ($startTime > $endTime) {
            $this->error = Label::getLabel('LBL_START_TIME_IS_GREATER_THEN_END_TIME');
            return false;
        }
        $db = FatApp::getDb();
        $srch = new ScheduledLessonSearch(false);
        $userIds = [$userId, UserAuthentication::getLoggedUserId()];
        $srch->checkUserLessonBooking($userIds, $startTime, $endTime);
        $srch->setPageSize(1);
        $getResultSet = $srch->getResultSet();
        $scheduledLessonData = $db->fetch($getResultSet);
        if (!empty($scheduledLessonData)) {
            $this->error = Label::getLabel('LBL_Either_You_or_teacher_not_available_for_this_slot');
            return false;
        }
        $dateTime = new DateTime($startTime);
        $weekStartAndEndDate = MyDate::getWeekStartAndEndDate($dateTime);
        $weekStart = $weekStartAndEndDate['weekStart'];

        $tWsrchC = new TeacherWeeklyScheduleSearch(false, false);
        $tWsrchC->addCondition('twsch_user_id', '=', $userId);
        $tWsrchC->addMultipleFields(['twsch_is_available']);
        $tWsrchC->addCondition('mysql_func_DATE_FORMAT(twsch_end_date,"%U-%Y")', '=', 'mysql_func_DATE_FORMAT("' . $startTime . '","%U-%Y")', 'AND', true);
        $tWsrchC->setPageSize(1);
        $tWRsC = $tWsrchC->getResultSet();
        $tWcountC = $tWRsC->totalRecords();
       
        if ($tWcountC > 0) {
            $tWsrch = clone $tWsrchC;
            $tWsrch->addCondition('mysql_func_CONCAT(twsch_date," ", twsch_start_time)', '<=', $startTime, 'AND', true);
            $tWsrch->addCondition('mysql_func_CONCAT( twsch_end_date," ", twsch_end_time)', '>=', $endTime, 'AND', true);
            $tWRs = $tWsrch->getResultSet();
            $tWcount = 0;
            $tWcount = $tWRs->totalRecords();
            $tWRows = $db->fetch($tWRs);
            if ($tWcount > 0) {
                if ($tWRows['twsch_is_available'] == static::AVAILABLE) {
                    return true;
                }
            }
            $this->error = Label::getLabel('LBL_NO_SLOT_AVAILABEL_ON_THIS_TIME_RANGE');
            return false;
        }
        $gaSrch = new TeacherGeneralAvailabilitySearch();
        $gaSrch->addCondition('tgavl_user_id', '=', $userId);
        $gaSrch->addOrder('tgavl_date', 'ASC');
        $gaRs = $gaSrch->getResultSet();
        $gARows = $db->fetchAll($gaRs);
        $gaCount = $gaRs->totalRecords();
        $generalAvail = 0;
        if ($gaCount > 0) {
            $weekStartDateDB = '2018-01-07';
            $weekDiff = MyDate::week_between_two_dates($weekStartDateDB, $weekStart);
            foreach ($gARows as $row) {
                $date = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] . ' ' . $row['tgavl_start_time']));
                if ($row['tgavl_end_time'] == "00:00:00" || $row['tgavl_end_time'] <= $row['tgavl_start_time']) {
                    $date1 = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] . ' ' . $row['tgavl_end_time']));
                    $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($date1)));
                } else {
                    $endDate = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] . ' ' . $row['tgavl_end_time']));
                }
                $_startDate = date('Y-m-d H:i:s', strtotime('+ ' . $weekDiff . ' weeks', strtotime($date)));
                $_endDate = date('Y-m-d H:i:s', strtotime('+ ' . $weekDiff . ' weeks', strtotime($endDate)));
                if (strtotime($_endDate) <= strtotime($_startDate)) {
                    $_endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($_endDate)));
                }
                if (strtotime($_startDate) <= strtotime($startTime) && strtotime($_endDate) >= strtotime($endTime)) {
                    $generalAvail++;
                }
            }
        }
        if ($generalAvail > 0) {
            return true;
        }
        $this->error = Label::getLabel('LBL_NO_SLOT_AVAILABEL_ON_THIS_TIME_RANGE');
        return false;
    }

    public static function isSlotAvailable($teacherId, $startDateTime, $endDateTime, $weekStart = '')
    {
        $teacherId = FatUtility::int($teacherId);
        if ($teacherId < 1) {
            trigger_error(Label::getLabel("LBL_Invalid_Teacher_Id_Passed"), E_USER_ERROR);
        }
        $startDateTime = date('Y-m-d H:i:s', strtotime($startDateTime));
        if (!FatDate::validateDateString($startDateTime) || $startDateTime == "1970-01-01 05:30:00" || $startDateTime == "0000-00-00 00:00:00") {
            trigger_error(Label::getLabel('LBL_Invalid_Date_selected'), E_USER_ERROR);
        }
        $endDateTime = date('Y-m-d H:i:s', strtotime($endDateTime));
        if (!FatDate::validateDateString($endDateTime) || $endDateTime == "1970-01-01 05:30:00" || $endDateTime == "0000-00-00 00:00:00") {
            trigger_error(Label::getLabel('LBL_Invalid_Date_selected'), E_USER_ERROR);
        }
        $dateTime = new DateTime($startDateTime);
        $weekStartAndEndDate = MyDate::getWeekStartAndEndDate($dateTime);
        /* [ */
        $weeklySchSrchObj = new TeacherWeeklyScheduleSearch();
        $weeklySchSrchObj->addCondition('twsch_user_id', '=', $teacherId);
        /* ] */
        /* [ */
        $weeklySchSrch = clone $weeklySchSrchObj;
        $weeklySchSrch->addCondition('mysql_func_CONCAT( twsch_date," ",twsch_start_time)', '<=', $startDateTime, 'AND', true);
        $weeklySchSrch->addCondition('mysql_func_CONCAT(twsch_end_date," ",twsch_end_time)', '>=', $startDateTime, 'AND', true);
        $weeklySchSrch->setPageSize(1);
        $weeklySchSrch->addMultipleFields(['twsch_is_available']);
        $weeklySchRs = $weeklySchSrch->getResultSet();
        $weeklySchSelectedSlotRow = FatApp::getDb()->fetch($weeklySchRs);
        /* ] */
        /* [ */
        $weeklySchDataAddedSrch = clone $weeklySchSrchObj;
        $weeklySchDataAddedSrch->addCondition('mysql_func_DATE(twsch_date)', '>=', $weekStartAndEndDate['weekStart'], 'AND', true);
        $weeklySchDataAddedSrch->addCondition('mysql_func_DATE(twsch_end_date)', '<=', $weekStartAndEndDate['weekEnd'], 'AND', true);
        $weeklySchDataAddedRs = $weeklySchDataAddedSrch->getResultSet();
        $isWeeklySchDataAdded = $weeklySchDataAddedRs->totalRecords();
        /* ] */
        if ($isWeeklySchDataAdded > 0) {
            if (!$weeklySchSelectedSlotRow) {
                return false;
            }
            if ($weeklySchSelectedSlotRow['twsch_is_available'] == 1) {
                return true;
            }
            return false;
        }
        if (!$weekStart) {
            $weekStart = $weekStartAndEndDate['weekStart'];
        }
        /* Now, start checking in general Availablity[ */
        $gaSrch = new TeacherGeneralAvailabilitySearch();
        $gaSrch->addCondition('tgavl_user_id', '=', $teacherId);
        $gaRs = $gaSrch->getResultSet();
        $gARows = FatApp::getDb()->fetchAll($gaRs);
        $gaCount = $gaRs->totalRecords();
        $generalAvail = 0;
        if ($gaCount > 0) {
            $weekStartDateDB = '2018-01-07';
            $weekDiff = MyDate::week_between_two_dates($weekStartDateDB, $weekStart);
            foreach ($gARows as $row) {
                $date = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] . ' ' . $row['tgavl_start_time']));
                if ($row['tgavl_end_time'] == "00:00:00" || $row['tgavl_end_time'] <= $row['tgavl_start_time']) {
                    $date1 = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] . ' ' . $row['tgavl_end_time']));
                    $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($date1)));
                } else {
                    $endDate = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] . ' ' . $row['tgavl_end_time']));
                }
                $_startDate = date('Y-m-d H:i:s', strtotime('+ ' . $weekDiff . ' weeks', strtotime($date)));
                $_endDate = date('Y-m-d H:i:s', strtotime('+ ' . $weekDiff . ' weeks', strtotime($endDate)));
                if (strtotime($_endDate) <= strtotime($_startDate)) {
                    $_endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($_endDate)));
                }
                if (strtotime($_startDate) <= strtotime($startDateTime) && strtotime($_endDate) >= strtotime($endDateTime)) {
                    $generalAvail++;
                }
            }
        }
        if ($generalAvail <= 0) {
            return false;
        }
        return true;
    }

}
