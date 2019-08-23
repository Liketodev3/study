<?php
class MyDate extends FatDate{
	
	public static function format( $date, $showTime = false, $useTimeZone = true, $timeZone = '' )
	{
		if( '' == $timeZone ){
			$timeZone = static::getTimeZone();
		}
		return parent::format( $date, $showTime, $useTimeZone, $timeZone );
	}
	
	public static function getDateAndTimeDisclaimer(){
		$str = Label::getLabel('LBL_All_Date_&_Times_are_showing_in_{time-zone-abbr},_Current_Date_&_Time:_{current-date-time}');
		
		$arr = array(
			"{time-zone-abbr}"	=>	date('T'),
			"{current-date-time}"	=>	date('d-M-Y H:i:s A T'),
		);
		
		foreach( $arr  as $key => $val ){
			$str = str_replace( $key, $val, $str );
		}
		echo $str;
	}
	
	public static function getTimeZone(){
		return FatApp::getConfig('CONF_TIMEZONE', FatUtility::VAR_STRING, date_default_timezone_get());
	}
}