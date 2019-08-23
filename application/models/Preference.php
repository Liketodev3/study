<?php
class Preference extends MyAppModel{
	const DB_TBL = 'tbl_preferences';
	const DB_TBL_PREFIX = 'preference_';	
	const DB_TBL_LANG = 'tbl_preferences_lang';
	const DB_TBL_USER_PREF = 'tbl_user_to_preference';
	
	const TYPE_ACCENTS=1;
	const TYPE_TEACHES_LEVEL=2;
	const TYPE_LEARNER_AGES=3;
	const TYPE_LESSONS=4;
	const TYPE_SUBJECTS=5;
	const TYPE_TEST_PREPARATIONS=6;
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}
		
	public static function getPreferenceTypeArr( $langId = 0 ){
		$langId = FatUtility::int($langId);
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();
		}
		return array(
			static::TYPE_ACCENTS	=>	Label::getLabel('LBL_Accents', $langId),	
			static::TYPE_TEACHES_LEVEL	=>	Label::getLabel('LBL_Teaches_Level', $langId),
			static::TYPE_LEARNER_AGES	=>	Label::getLabel('LBL_Learner_Ages', $langId),
			static::TYPE_LESSONS	=>	Label::getLabel('LBL_Lessons', $langId),
			static::TYPE_SUBJECTS	=>	Label::getLabel('LBL_Subjects', $langId),
			static::TYPE_TEST_PREPARATIONS	=>	Label::getLabel('LBL_Test_Preparations', $langId),
			
		);
	}
	
	public function deletePreference($preference_id){
		$db=FatApp::getDb();
		$langDelete=$db->deleteRecords(static::DB_TBL_LANG,array('smt'=>'preferencelang_preference_id = ?','vals'=>array($preference_id)));
		if(!$db->deleteRecords(static::DB_TBL,array('smt'=>'preference_id = ?','vals'=>array($preference_id))) && !$langDelete)
		{
			$this->error=$db->getError();
			return false;
		}
		return true;
		
	}
	
	public static function getPreferencesArr($langId){
		$srch = new PreferenceSearch($langId);
		$srch->addMultipleFields( array(
			'preference_id', 
			'preference_title', 
			'preference_type') );
		$srch->addOrder('preference_display_order', 'asc');
		$rs = $srch->getResultSet();
		$rows = FatApp::getDb()->fetchAll($rs);
		
		$tempRows = array();
		foreach($rows as $row){
			$tempRows[$row['preference_type']][] = $row;
		}
		ksort($tempRows);
		return $tempRows;
	}
}