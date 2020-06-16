<?php
class TeacherGeneralAvailability extends MyAppModel
{
    const DB_TBL = 'tbl_teachers_general_availability';
    const DB_TBL_PREFIX = 'teacher_avl_';
    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getGenaralAvailabilityJsonArr($userId, $post ='', $requestBtTeacher = false)
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
        $i = 7;

        $user_timezone = MyDate::getUserTimeZone();


        if (!empty($post)) {
            $nowDate = $post['WeekEnd'];
            $startDate = $post['WeekStart'];
            $endDate = $post['WeekEnd'];
        } else {
            $nowDate = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', date('Y-m-d H:i:s'), true, $user_timezone);
            $startDate = $nowDate;
        }

        if (!empty($rows)) {
            $weekStartDateDB = '2018-01-07';
            $weekDiff = MyDate::week_between_two_dates($weekStartDateDB, $startDate);

            foreach ($rows as $row) {
                if (!empty($post)) {
                    $date = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] .' '. $row['tgavl_start_time']));

                    if ($row['tgavl_end_time'] == "00:00:00" ||  $row['tgavl_end_time'] <= $row['tgavl_start_time']) {
                        $date1 = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] .' '. $row['tgavl_end_time']));
                        $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($date1)));
                    } else {
                        $endDate = date('Y-m-d H:i:s', strtotime($row['tgavl_date'] .' '. $row['tgavl_end_time']));
                    }

                    $date = date('Y-m-d H:i:s', strtotime('+ '. $weekDiff .' weeks', strtotime($date)));
                    $endDate = date('Y-m-d H:i:s', strtotime('+ '. $weekDiff .' weeks', strtotime($endDate)));
                } else {
                    $weekNumber = date('W', strtotime($nowDate));
                    $Year = date('Y', strtotime($nowDate));
                    $gendate = new DateTime($nowDate);

                    $gendate->setISODate($Year, $weekNumber, $row['tgavl_day']);
                    $date = $gendate->format('Y-m-d '. $row['tgavl_start_time']);

                    if ($row['tgavl_end_time'] == "00:00:00" ||  $row['tgavl_end_time'] <= $row['tgavl_start_time']) {
                        $date1 = $gendate->format('Y-m-d '. $row['tgavl_end_time']);
                        $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($date1)));
                    } else {
                        $endDate = $gendate->format('Y-m-d '. $row['tgavl_end_time']);
                    }
                }

                $tgavl_start_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $date, true, $user_timezone);
                $tgavl_end_time = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $endDate, true, $user_timezone);


                //$tgavl_day = MyDate::getDayNumber( $tgavl_start_time );
                if (true == $requestBtTeacher) {
                    $gendate = new DateTime();
                    $gendate->setISODate(2018, 2, MyDate::getDayNumber($tgavl_start_time));
                    $day = $gendate->format('d');
                    $dayNum = $day;
                    $startDate = "2018-01-".$dayNum." ". date('H:i:s', strtotime($tgavl_start_time));
                    $endDate = "2018-01-".$dayNum." ". date('H:i:s', strtotime($tgavl_end_time));
                    if (strtotime($endDate) <=  strtotime($startDate)) {
                        $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($endDate)));
                    }

                    $jsonArr[] = array(
                "title"=>"",
                "endW"=> date('H:i:s', strtotime($tgavl_end_time)),
                "startW"=> date('H:i:s', strtotime($tgavl_start_time)),
                "end"=>$endDate,
                "start"=>$startDate,
                '_id'=>$row['tgavl_id'],
                "classType"=>1,
                "day"=> MyDate::getDayNumber($tgavl_start_time),
                'className'=>"slot_available"
            );
                } else {
                    $jsonArr[] = array(
                "title"=>"",
                "endW"=> date('H:i:s', strtotime($tgavl_end_time)),
                "startW"=> date('H:i:s', strtotime($tgavl_start_time)),
                "end"=>$tgavl_end_time,
                "start"=>$tgavl_start_time,
                '_id'=>$row['tgavl_id'],
                "classType"=>1,
                "day"=> MyDate::getDayNumber($tgavl_start_time),
                'className'=>"slot_available"
            );
                }
                $i++;
            }

            return $jsonArr;
        } else {
            return;
        }
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


        foreach ($postJson as $k=>$postObj) {
            /*[ Clubbing the continuous timeslots */
            if ($k>0 and ($postJson[$k-1]->day == $postObj->day) and ($postJson[$k-1]->endTime == $postObj->startTime)) {
                $postJsonArr[count($postJsonArr)-1]->endTime = $postObj->endTime;
                continue;
            }
            /* ] */
            $postJsonArr[] = $postObj;
        }
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
                $day = $gendate->format('d');
                $dayNum = $day;
                $startDate = "2018-01-".$dayNum." ". date('H:i:s', strtotime($val->startTime));
                $endDate = "2018-01-".$dayNum." ". date('H:i:s', strtotime($val->endTime));
                //$startDate = $gendate->setISODate($Year, $weekNumber, $val->day);
                //$date = $gendate->format('Y-m-d '. $val->startTime );

                if ($val->endTime == "00:00") {
                    $endDate = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($endDate)));
                }
                /* code added  on 12-07-2019 */
                $tgavl_start = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);
                $tgavl_end = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);
                $tgavl_start_time = date('H:i:00', strtotime($tgavl_start));
                $tgavl_end_time = date('H:i:00', strtotime($tgavl_end));

                $day = MyDate::getDayNumber($tgavl_start);

                $insertArr = array('tgavl_day'=>$day,'tgavl_user_id'=>$userId,'tgavl_start_time'=>$tgavl_start_time,'tgavl_end_time'=>$tgavl_end_time, 'tgavl_date' => $tgavl_start );

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
}
