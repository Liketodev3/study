<?php
class UserToPreferenceSearch extends SearchBase{
	
	public function __construct(  $doNotCalculateRecords = true, $doNotLimitRecords = true ){
		
		parent::__construct(Preference::DB_TBL_USER_PREF, 'utp');
		
		if( true === $doNotCalculateRecords ){
			$this->doNotCalculateRecords();
		}
		
		if( true == $doNotLimitRecords ){
			$this->doNotLimitRecords();
		}
		
	}
	
	public function joinToPreference( $langId = 0 ){
		$langId = FatUtility::int( $langId );
		$this->joinTable(Preference::DB_TBL,'Left Outer Join','utpref_preference_id=preference_id');
		
		if( $langId > 0 ){
			$this->joinTable( Preference::DB_TBL.'_lang', 'LEFT JOIN', 'utpref_preference_id = preferencelang_preference_id AND preferencelang_lang_id = '.$langId );
		}
	}
	
}