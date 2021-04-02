<?php
class TeacherGeneralAvailability extends MyAppModel
{
    const DB_TBL = 'tbl_teachers_general_availability';
    const DB_TBL_PREFIX = 'teacher_avl_';
    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getGenaralAvailabilityJsonArr($userId, $post = [])
    {
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            trigger_error(Label::getLabel('LBL_Invalid_Request'));
        }
        $srch = new TeacherGeneralAvailabilitySearch();
        $srch->addMultipleFields(array('tgavl_day','tgavl_start_time','tgavl_end_time','tgavl_user_id','tgavl_id', 'tgavl_date'));
        $srch->addCondition('tgavl_user_id', '=', $userId);

        $srch->addOrder('tgavl_date', 'ASC');

        $rs = $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $cssClassNamesArr = TeacherWeeklySchedule::getWeeklySchCssClsNameArr();
        $jsonArr = array();

        $user_timezone = MyDate::getUserTimeZone();

        if (!empty($post)) {
            $nowDate = $post['WeekEnd'];
            $weekStartDate = $post['WeekStart'];
            $weekEndDate = $post['WeekEnd'];
        } else {
            $weekStartAndEndDate = MyDate::getWeekStartAndEndDate(new DateTime());
            $weekStartDate = $weekStartAndEndDate['weekStart'];
            $weekEndDate = $weekStartAndEndDate['weekEnd'];
        }

        if (!empty($rows)) {

            $weekStartDateDB = '2018-01-07';
            $weekDiff = MyDate::week_between_two_dates($weekStartDateDB, $weekStartDate);

            foreach ($rows as $row) {
              
                    $date = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] .' '. $row['tgavl_start_time']));

                    if ($row['tgavl_end_time'] == "00:00:00" ||  $row['tgavl_end_time'] <= $row['tgavl_start_time']) {
                        $date1 = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] .' '. $row['tgavl_end_time']));
                        $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($date1)));
                    } else {
                        $endDate = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] .' '. $row['tgavl_end_time']));
                    }

                    $date = date('Y-m-d H:i:s', strtotime('+ '. $weekDiff .' weeks', strtotime($date)));
                    $endDate = date('Y-m-d H:i:s', strtotime('+ '. $weekDiff .' weeks', strtotime($endDate)));
                

                $tgavl_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $date, true, $user_timezone);
                $tgavl_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $endDate, true, $user_timezone);
            
                $jsonArr[] = array(
                    "title" => "",
                    "endW"  => date('H:i:s', strtotime($tgavl_end_time)),
                    "startW"=> date('H:i:s', strtotime($tgavl_start_time)),
                    "end"   => $tgavl_end_time,
                    "start" => $tgavl_start_time,
                    '_id'   => $row['tgavl_id'],
                    "classType"=> 1,
                    'action' => 'fromGeneralAvailability',
                    "day"   => MyDate::getDayNumber($tgavl_start_time),
                    'className'=>"slot_available"
                );
            }
        }
        return $jsonArr;
    }

    public function deleteTeacherGeneralAvailability($tgavl_id, $userId)
    {
        $userId = FatUtility::int($userId);
        $tgavl_id = FatUtility::int($tgavl_id);

        if ($userId < 1 || $tgavl_id < 1) {
            $this->error =  Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        $db = FatApp::getDb();

        //$weekendDate = date('Y-m-d', strtotime('next Saturday +1 day'));
        //$deleteWeeklyFutrureWeeksRecords = $db->deleteRecords(TeacherWeeklySchedule::DB_TBL,array('smt'=>'twsch_user_id = ? and (twsch_start_time > ?)','vals'=>array($userId,$weekendDate)));

        $deleteRecords = $db->deleteRecords(TeacherGeneralAvailability::DB_TBL, array('smt'=>'tgavl_user_id = ? and tgavl_id = ?','vals'=>array($userId,$tgavl_id)));
        if (!$deleteRecords) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }

    public function addTeacherGeneralAvailability($post, $userId)
    {
        if (false === $post) {
            $this->error =  Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        $userId = FatUtility::int($userId);
        if ($userId < 1) {
            $this->error =  Label::getLabel('LBL_Invalid_Request');
            return false;
        }

        $postJson = json_decode($post['data']);

        $db = FatApp::getDb();
        $weekendDate = date('Y-m-d', strtotime('next Saturday +1 day'));
        //$deleteWeeklyFutrureWeeksRecords = $db->deleteRecords(TeacherWeeklySchedule::DB_TBL,array('smt'=>'twsch_user_id = ? and (twsch_start_time > ?)','vals'=>array($userId,$weekendDate)));
        //$deleteRecords = true;
        $deleteRecords = $db->deleteRecords(TeacherGeneralAvailability::DB_TBL, array('smt'=>'tgavl_user_id = ?','vals'=>array($userId)));
        if (empty($postJson)) {
            return true;
        }
        $postJsonArr = array();

        $sort = array();

        /*[ Sorting the Array By Date and StartTime */

        foreach ($postJson as $k=>$v) {
            $sort['day'][$k] = $v->day;
            $sort['start'][$k] = $v->start;
        }
        # sort by event_type desc and then title asc
        array_multisort($sort['day'], SORT_ASC, $sort['start'], SORT_ASC, $postJson);
        /* ] */


        /*[ Clubbing the continuous timeslots */
        foreach ($postJson as $k=>$postObj) {
            if ($k>0 and ($postJson[$k-1]->day == $postObj->day) and ($postJson[$k-1]->endTime == $postObj->startTime)) {
                $postJsonArr[count($postJsonArr)-1]->endTime = $postObj->endTime;
                continue;
            }
            $postJsonArr[] = $postObj;
        }
        /* ] */
        if ($deleteRecords) {
            /* code added  on 12-07-2019 */
            $user_timezone = MyDate::getUserTimeZone();
            $systemTimeZone = MyDate::getTimeZone();

            $nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);

            //$weekNumber = date('W', strtotime($nowDate));
            //$Year = date('Y', strtotime($nowDate));
            //$gendate = new DateTime( $nowDate );
            
            foreach ($postJsonArr as $val) {
                $gendate = new DateTime();
              
                $gendate->setISODate(2018, 2, $val->day);
                $dayNum = $gendate->format('d');
                $startDate = "2018-01-".$dayNum." ". date('H:i:s', strtotime($val->startTime));
                
                $custom_tgavl_start = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);


                $gendate = new DateTime();
                $gendate->setISODate(2018, 2, $val->dayEnd);
                $dayNum = $gendate->format('d');
                $endDate = "2018-01-".$dayNum." ". date('H:i:s', strtotime($val->endTime));
                $custom_tgavl_end = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);
                
                $startDate = date('Y-m-d H:i:s', strtotime($val->startTime));
                $endDate = date('Y-m-d H:i:s', strtotime($val->endTime));

                if ($val->endTime == "00:00") {
                    $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($endDate)));
                }
                /* code added  on 12-07-2019 */
                $tgavl_start = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);
                $tgavl_end = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);
                $tgavl_start_time = date('H:i:00', strtotime($tgavl_start));
                $tgavl_end_time = date('H:i:00', strtotime($tgavl_end));

                $day = MyDate::getDayNumber($custom_tgavl_start);
                
                $insertArr = array(
                    'tgavl_day'         => $day,
                    'tgavl_user_id'     => $userId,
                    'tgavl_start_time'  => $tgavl_start_time,
                    'tgavl_end_time'    => $tgavl_end_time,
                    'tgavl_date'        => $custom_tgavl_start,
                    'tgavl_end_date'    => $custom_tgavl_end,
                );

                if (!$db->insertFromArray(TeacherGeneralAvailability::DB_TBL, $insertArr)) {
                    $this->error = $db->getError();
                    return false;
                }
            }
        }
        return true;
    }

    public static function timeSlotArr()
    {
        return array(
            0	=>	'00:00-02:59',
            1	=>	'03:00-05:59',
            2	=>	'06:00-08:59',
            3	=>	'09:00-11:59',
            4	=>	'12:00-14:59',
            5	=>	'15:00-17:59',
            6	=>	'18:00-20:59',
            7	=>	'21:00-23:59',
        );
    }

    private static function mergeDates(array $datesArray = []): array
    {

       foreach ($datesArray as $key => &$date) {

           foreach ($datesArray as $index => $value) {
             
               $mergeDates =  false;

               if ($date['startDateTime'] == $value['endDateTime']) {
                   $mergeDates =  true;
                   $date['startDateTime'] = $value['startDateTime'];
                   $date['startTime'] = $value['startTime'];
                   $date['day'] = $value['day'];
                   
               }

               if ($date['endDateTime'] == $value['startDateTime']) {
                   $mergeDates =  true;
                   $date['endDateTime'] = $value['endDateTime'];
                   $date['endTime'] = $value['endTime'];
               }

               if ($mergeDates) {
                   unset($datesArray[$index]);
                   $datesArray =  self::mergeDates($datesArray);
               }
           }
       }
       return $datesArray;
    }
}
