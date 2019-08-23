<?php
class State extends MyAppModel{
	const DB_TBL = 'tbl_states';
	const DB_TBL_PREFIX = 'state_';
	
	const DB_TBL_LANG = 'tbl_states_lang';
	const DB_TBL_LANG_PREFIX = 'statelang_';
	
	public function __construct($id = 0) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );		
		$this->db=FatApp::getDb();
	}
	
	public static function getSearchObject( $isActive = true , $langId = 0) {
		$langId = FatUtility::int($langId);
		$srch = new SearchBase(static::DB_TBL, 'st');
		
		if($isActive==true){
			$srch->addCondition('st.'.static::DB_TBL_PREFIX.'active', '=', applicationConstants::ACTIVE);
		}		
		
		if($langId > 0){
			$srch->joinTable(static::DB_TBL_LANG,'LEFT OUTER JOIN',
			'st_l.'.static::DB_TBL_LANG_PREFIX.'state_id = st.'.static::tblFld('id').' and 
			st_l.'.static::DB_TBL_LANG_PREFIX.'lang_id = '.$langId,'st_l');
		}
		
		return $srch;
	}
	
	
	
	public static function getStatesByCountryId($countryId, $langId , $isActive = true){
		$langId = FatUtility::int($langId);
		$countryId = FatUtility::int($countryId);		
		
		$srch = static::getSearchObject($isActive , $langId);
		$srch->addCondition('state_country_id','=',$countryId);
		$srch->doNotCalculateRecords();
		$srch->doNotLimitRecords();	
		$srch->addOrder('state_name','ASC');		
		$srch->addMultipleFields(
			array(
				'state_id',
				'IFNULL(state_name, state_identifier) as state_name'
				)
			);			
		
		$rs = $srch->getResultSet();
		$row = FatApp::getDb()->fetchAllAssoc($rs);
		
		if (!is_array($row)) {
			return false;
		}
		return $row;		
	}
	
}