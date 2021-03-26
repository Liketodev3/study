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
        if(substr($dateTime, 0, 10) === '0000-00-00'){
            return $dateTime;
        }
        if ($timeZone == '') {
            $timeZone = self::getTimeZone();
        }

        $changedDate = self::format(date('Y-m-d H:i:s', strtotime($dateTime)), $showtime, true, $timeZone);
        return date($format, strtotime($changedDate));
    }

    public static function timezoneConvertedTime($format, $dateTime, $showtime, $timeZone)
    {
        return static::convertTimeFromSystemToUserTimezone($format, $dateTime, $showtime, $timeZone);
    }

    public static function getUserTimeZone($userId = 0)
    {
        $user_timezone = '';
        if ($userId > 0) {
            $userRow = User::getAttributesById($userId, array( 'user_timezone'));
            $user_timezone = $userRow['user_timezone'];
        } else {
            if (UserAuthentication::isUserLogged()) {
                $userRow = User::getAttributesById(UserAuthentication::getLoggedUserId(), array( 'user_timezone'));
                $user_timezone = $userRow['user_timezone'];
            } else {
                $user_timezone = $_COOKIE['user_timezone'] ?? self::getTimeZone();
            }
        }

        if (empty($user_timezone)) {
            $user_timezone = $_COOKIE['user_timezone'] ?? self::getTimeZone();
        }

        return $user_timezone;
    }

    public static function setUserTimeZone()
    {
        if (UserAuthentication::isUserLogged()) {
            $userDataRow = User::getAttributesById(UserAuthentication::getLoggedUserId(), array( 'user_timezone'));
            $user_timezone = $userDataRow['user_timezone'];
	    $cookieConsent = CommonHelper::getCookieConsent();
            $isActivePreferencesCookie =  (!empty($cookieConsent[UserCookieConsent::COOKIE_PREFERENCES_FIELD]));
            if (!empty($user_timezone) && $isActivePreferencesCookie) {
                CommonHelper::setCookie("user_timezone", $user_timezone, time() + 365*24*60*60, CONF_WEBROOT_URL, '', true);
            }
        }
    }

    public static function getDayNumber($date)
    {
        $number = date('N', strtotime($date));
        if (7 == $number) { //== Sunday is 0 in full-calendar
            $number = 0;
        }
        return $number;
    }

    public static function displayTimezoneString($echoTimeZone =  true)
    {
        $user_timezone = self::getUserTimeZone();
        $string =  sprintf(Label::getLabel("LBL_Timezone_:_UTC_%s"), CommonHelper::getDateOrTimeByTimeZone($user_timezone, ' P'));
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
    
    
    public static function timeDiff($date1, $date2)
    {
        $first =  new DateTime($date1);
        $second = new DateTime($date2);
        return $first->diff($second);
    }

    public static function getOffset(string $timeZone = 'UTC') : string
    {
        $dateTimeZone = new DateTimeZone($timeZone);
        
        $dateTime = new DateTime("now", $dateTimeZone);
        
       return $dateTime->format('P');
    }
    public static function timeZoneListing() : array
    {
        
        $timeZoneList = Timezone::getAllByLang(CommonHelper::getLangId());
        $finalArray = [];
        foreach ($timeZoneList as $key=>$timezone) {
            $finalArray[$key] = sprintf(Label::getLabel('LBL_(TIMEZONE_%s)_%s'), $timezone['timezone_offset'], $timezone['timezone_name']);
        }
       return $finalArray;
    }

    public static function getIdentifiers()
    {
        return Timezone::getAssocByLang(CommonHelper::getLangId());
    }

    public static function getWeekStartAndEndDate(DateTime $dateTime): array
    {
        // $dateTime = ($dateTime->format('w') == 0) ? $dateTime : $dateTime->modify('last Sunday');
        $dateTime = $dateTime->modify('last saturday')->modify('+1 day');
        return array(
            'weekStart' => $dateTime->format('Y-m-d'),
            'weekEnd' =>  $dateTime->modify('next saturday')->format('Y-m-d'),
        );
    }

    public static function changeWeekDaysToDate(array $weekDays) : array
	{
		$user_timezone = MyDate::getUserTimeZone();
		$systemTimeZone = MyDate::getTimeZone();
		$newWeekDayArray = [];
		foreach($weekDays as $key => $day){
			
			$dateTime = new DateTime();
			$dateTime->setISODate(2018, 2, $day);
			$day = $dateTime->format('d');
			$date = "2018-01-".$day;

			$dateStart = $date." 00:00:00";
			$dateStart = MyDate::changeDateTimezone($dateStart, $user_timezone, $systemTimeZone);
			
			$dateEnd = $date." 23:59:59";
			$dateEnd = MyDate::changeDateTimezone($dateEnd, $user_timezone, $systemTimeZone);
			
			$newWeekDayArray[$key]['startDate'] = $dateStart;
			$newWeekDayArray[$key]['endDate'] =$dateEnd;
		}
			// prx($newWeekDayArray);
		return $newWeekDayArray;

	}

}
