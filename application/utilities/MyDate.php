<?php
class MyDate extends FatDate
{
    public static function format($date, $showTime = false, $useTimeZone = true, $timeZone = '')
    {
        if ('' == $timeZone) {
            $timeZone = static::getTimeZone();
        }
        return parent::format($date, $showTime, $useTimeZone, $timeZone);
    }

    public static function getDateAndTimeDisclaimer()
    {
        $str = Label::getLabel('LBL_All_Date_&_Times_are_showing_in_{time-zone-abbr},_Current_Date_&_Time:_{current-date-time}');

        $arr = array(
            "{time-zone-abbr}"	=>	date('T'),
            "{current-date-time}"	=>	date('d-M-Y H:i:s A T'),
        );

        foreach ($arr  as $key => $val) {
            $str = str_replace($key, $val, $str);
        }
        echo $str;
    }

    public static function getTimeZone()
    {
        return FatApp::getConfig('CONF_TIMEZONE', FatUtility::VAR_STRING, date_default_timezone_get());
    }

    /** custom function for change according to timezone **/

    public static function changeDateTimezone($date, $fromTimezone, $toTimezone)
    {
        return parent::changeDateTimezone($date, $fromTimezone, $toTimezone);
    }

    public static function convertTimeFromSystemToUserTimezone($format, $dateTime, $showtime, $timeZone)
    {
        if ($timeZone == '') {
            $timeZone = self::getTimeZone();
        }

        $changedDate = self::format(date('Y-m-d H:i:s', strtotime($dateTime)), $showtime, true, $timeZone);
        return date($format, strtotime($changedDate));
    }

    public static function getUserTimeZone($userId = 0)
    {
        if ($userId > 0) {
            $userRow = User::getAttributesById($userId, array( 'user_timezone'));
            $user_timezone = $userRow['user_timezone'];
        } else {
            if (UserAuthentication::isUserLogged()) {
                $userRow = User::getAttributesById(UserAuthentication::getLoggedUserId(), array( 'user_timezone'));
                $user_timezone = $userRow['user_timezone'];
            } else {
                $user_timezone = $_COOKIE['user_timezone'];
            }
        }

        if (empty($user_timezone)) {
            $user_timezone = $_COOKIE['user_timezone'];
        }

        return $user_timezone;
    }

    public static function setUserTimeZone()
    {
        if (UserAuthentication::isUserLogged()) {
            $userDataRow = User::getAttributesById(UserAuthentication::getLoggedUserId(), array( 'user_timezone'));
            $user_timezone = $userDataRow['user_timezone'];
            if (!empty($user_timezone)) {
                setcookie("user_timezone", $user_timezone, time() + 365*24*60*60, "/");
            }
        }
    }

    public static function getDayNumber($date)
    {
        $number = date('N', strtotime(date($date)));
        if (7 == $number) { //== Sunday is 0 in full-calendar
            $number = 0;
        }
        return $number;
    }

    public static function displayTimezoneString($echoTimeZone =  true)
    {
        $user_timezone = self::getUserTimeZone();
        $string =  Label::getLabel("LBL_Timezone_:").' GMT'.CommonHelper::getDateOrTimeByTimeZone($user_timezone, ' P')."";
        if($echoTimeZone) {
            echo $string;
            return;
        }
        return $string;
    }

    public static function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {
        // Declare an empty array
        $array = array();
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach ($period as $date) {
            $array[] = $date->format($format);
        }
        return $array;
    }

    public static function week_between_two_dates($date1, $date2)
    {
        $first =  new DateTime($date1);
        $second = new DateTime($date2);
        if ($date1 > $date2) {
            return self::week_between_two_dates($date2, $date1);
        }
        return floor($first->diff($second)->days/7);
        //return round($first->diff($second)->days/7);
    }

    public static function getOffset(string $timeZone = 'UTC') : string
    {
        $dateTimeZone = new DateTimeZone($timeZone);
        
        $dateTime = new DateTime("now", $dateTimeZone);
        
       return $dateTime->format('P');
    }
}
