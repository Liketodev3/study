<?php
class IssueReportOptions extends MyAppModel{
	const DB_TBL = 'tbl_issue_report_options';
	const DB_TBL_PREFIX = 'tissueopt_';	
	const DB_TBL_LANG = 'tbl_issue_report_options_lang';
	const DB_TBL_LANG_PREFIX = 'otplang_tissueopt_';
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}
		
	public function getPreferencesArr( $langId ){
		$srch = new IssueReportOptionsSearch($langId);
		$srch->addMultipleFields( array(
			self::DB_TBL_PREFIX .'id', 
			self::DB_TBL_PREFIX .'title', 
		) );
		$srch->addOrder(self::DB_TBL_PREFIX . 'display_order', 'asc');
		
		return $srch;
	}
	
	/*public function deletePreference($preference_id){
		$db=FatApp::getDb();
		$langDelete=$db->deleteRecords(static::DB_TBL_LANG,array('smt'=>'preferencelang_preference_id = ?','vals'=>array($preference_id)));
		if(!$db->deleteRecords(static::DB_TBL,array('smt'=>'preference_id = ?','vals'=>array($preference_id))) && !$langDelete)
		{
			$this->error=$db->getError();
			return false;
		}
		return true;
		
	}*/
	
	
}