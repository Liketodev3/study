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
        $string =  Label::getLabel("LBL_Timezone_:").Label::getLabel("LBL_TIMEZONE_STRING").' '.CommonHelper::getDateOrTimeByTimeZone($user_timezone, ' P');
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
        $timeZoneList = self::getIdentifiers();
        $finalArray = [];
      
        foreach ($timeZoneList as $key => $val) {
            $offset = self::getOffset($key);
            
            $timeZoneName =  $val;

            $finalArray[$key] = "(".Label::getLabel('LBL_TIMEZONE_STRING').$offset.") ".$timeZoneName;
        }
       return $finalArray;
    }

   public static function getIdentifiers()
    {
        return  array(
            "Africa/Cairo" => " Cairo",
            "Africa/Casablanca" => " Casablanca",
            "Africa/Harare" => " Harare",
            "Africa/Johannesburg" => " Pretoria",
            "Africa/Lagos" => " West Central Africa",
            "Africa/Monrovia" => " Monrovia",
            "Africa/Nairobi" => " Nairobi",
            "America/Argentina/Buenos_Aires" => " Buenos Aires",
            "America/Argentina/Buenos_Aires" => " Georgetown",
            "America/Bogota" => " Quito",
            "America/Bogota" => " Bogota",
            "America/Caracas" => " Caracas",
            "America/Chihuahua" => " La Paz",
            "America/Chihuahua" => " Chihuahua",
            "America/Godthab" => " Greenland",
            "America/La_Paz" => " La Paz",
            "America/Lima" => " Lima",
            "America/Los_Angeles" => " Pacific Time (US & Canada)",
            "America/Managua" => " Central America",
            "America/Mazatlan" => " Mazatlan",
            "America/Mexico_City" => " Mexico City",
            "America/Mexico_City" => " Guadalajara",
            "America/Monterrey" => " Monterrey",
            "America/Noronha" => " Mid-Atlantic",
            "America/Santiago" => " Santiago",
            "America/Sao_Paulo" => " Brasilia",
            "America/Tijuana" => " Tijuana",
            "Asia/Almaty" => " Almaty",
            "Asia/Baghdad" => " Baghdad",
            "Asia/Baku" => " Baku",
            "Asia/Bangkok" => " Hanoi",
            "Asia/Bangkok" => " Bangkok",
            "Asia/Calcutta" => " Chennai",
            "Asia/Calcutta" => " Mumbai",
            "Asia/Calcutta" => " New Delhi",
            "Asia/Calcutta" => " Sri Jayawardenepura",
            "Asia/Chongqing" => " Chongqing",
            "Asia/Dhaka" => " Dhaka",
            "Asia/Dhaka" => " Astana",
            "Asia/Hong_Kong" => " Beijing",
            "Asia/Hong_Kong" => " Hong Kong",
            "Asia/Irkutsk" => " Irkutsk",
            "Asia/Jakarta" => " Jakarta",
            "Asia/Jerusalem" => " Jerusalem",
            "Asia/Kabul" => " Kabul",
            "Asia/Kamchatka" => " Kamchatka",
            "Asia/Karachi" => " Karachi",
            "Asia/Karachi" => " Islamabad",
            "Asia/Katmandu" => " Kathmandu",
            "Asia/Kolkata" => " Kolkata",
            "Asia/Krasnoyarsk" => " Krasnoyarsk",
            "Asia/Kuala_Lumpur" => " Kuala Lumpur",
            "Asia/Kuwait" => " Kuwait",
            "Asia/Magadan" => " Solomon Is.",
            "Asia/Magadan" => " Magadan",
            "Asia/Magadan" => " New Caledonia",
            "Asia/Muscat" => " Abu Dhabi",
            "Asia/Muscat" => " Muscat",
            "Asia/Novosibirsk" => " Novosibirsk",
            "Asia/Rangoon" => " Rangoon",
            "Asia/Riyadh" => " Riyadh",
            "Asia/Seoul" => " Seoul",
            "Asia/Singapore" => " Singapore",
            "Asia/Taipei" => " Taipei",
            "Asia/Tashkent" => " Tashkent",
            "Asia/Tbilisi" => " Tbilisi",
            "Asia/Tehran" => " Tehran",
            "Asia/Tokyo" => " Osaka",
            "Asia/Tokyo" => " Tokyo",
            "Asia/Tokyo" => " Sapporo",
            "Asia/Ulan_Bator" => " Ulaan Bataar",
            "Asia/Urumqi" => " Urumqi",
            "Asia/Vladivostok" => " Vladivostok",
            "Asia/Yakutsk" => " Yakutsk",
            "Asia/Yekaterinburg" => " Ekaterinburg",
            "Asia/Yerevan" => " Yerevan",
            "Atlantic/Azores" => " Azores",
            "Atlantic/Cape_Verde" => " Cape Verde Is.",
            "Australia/Adelaide" => " Adelaide",
            "Australia/Brisbane" => " Brisbane",
            "Australia/Canberra" => " Canberra",
            "Australia/Darwin" => " Darwin",
            "Australia/Hobart" => " Hobart",
            "Australia/Melbourne" => " Melbourne",
            "Australia/Perth" => " Perth",
            "Australia/Sydney" => " Sydney",
            "Canada/Atlantic" => " Atlantic Time (Canada)",
            "Canada/Newfoundland" => " Newfoundland",
            "Canada/Saskatchewan" => " Saskatchewan",
            "Etc/Greenwich" => " Greenwich Mean Time : Dublin",
            "Europe/Amsterdam" => " Amsterdam",
            "Europe/Athens" => " Athens",
            "Europe/Belgrade" => " Belgrade",
            "Europe/Berlin" => " Berlin",
            "Europe/Berlin" => " Bern",
            "Europe/Bratislava" => " Bratislava",
            "Europe/Brussels" => " Brussels",
            "Europe/Bucharest" => " Bucharest",
            "Europe/Budapest" => " Budapest",
            "Europe/Copenhagen" => " Copenhagen",
            "Europe/Helsinki" => " Helsinki",
            "Europe/Helsinki" => " Kyiv",
            "Europe/Istanbul" => " Istanbul",
            "Europe/Lisbon" => " Lisbon",
            "Europe/Ljubljana" => " Ljubljana",
            "Europe/London" => " Edinburgh",
            "Europe/London" => " London",
            "Europe/Madrid" => " Madrid",
            "Europe/Minsk" => " Minsk",
            "Europe/Moscow" => " St. Petersburg",
            "Europe/Moscow" => " Moscow",
            "Europe/Paris" => " Paris",
            "Europe/Prague" => " Prague",
            "Europe/Riga" => " Riga",
            "Europe/Rome" => " Rome",
            "Europe/Sarajevo" => " Sarajevo",
            "Europe/Skopje" => " Skopje",
            "Europe/Sofia" => " Sofia",
            "Europe/Stockholm" => " Stockholm",
            "Europe/Tallinn" => " Tallinn",
            "Europe/Vienna" => " Vienna",
            "Europe/Vilnius" => " Vilnius",
            "Europe/Volgograd" => " Volgograd",
            "Europe/Warsaw" => " Warsaw",
            "Europe/Zagreb" => " Zagreb",
            "Pacific/Auckland" => " Wellington",
            "Pacific/Auckland" => " Auckland",
            "Pacific/Fiji" => " Fiji",
            "Pacific/Fiji" => " Marshall Is.",
            "Pacific/Guam" => " Guam",
            "Pacific/Honolulu" => " Hawaii",
            "Pacific/Kwajalein" => " International Date Line West",
            "Pacific/Midway" => " Midway Island",
            "Pacific/Port_Moresby" => " Port Moresby",
            "Pacific/Samoa" => " Samoa",
            "Pacific/Tongatapu" => " Nuku'alofa",
            "US/Alaska" => " Alaska",
            "US/Arizona" => " Arizona",
            "US/Central" => " Central Time (US & Canada)",
            "US/East-Indiana" => " Indiana (East)",
            "US/Eastern" => " Eastern Time (US & Canada)",
            "US/Mountain" => " Mountain Time (US & Canada)",
            "UTC" => " UTC"
        );
    
    }

}
