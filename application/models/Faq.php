<?php
class Faq extends MyAppModel{
	const DB_TBL = 'tbl_faq';
	const DB_TBL_PREFIX = 'faq_';
	const DB_TBL_LANG = 'tbl_faq_lang';
	
	const CATEGORY_GENERAL_QUERIES = 1;
	const CATEGORY_APPLICATION = 2;
	const CATEGORY_PAYMENTS = 3;
	
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
		$this->db = FatApp::getDb();
	}
	
	public static function getFaqCategoryArr( ){
		return array(
			static::CATEGORY_GENERAL_QUERIES	=>	Label::getLabel('LBL_General_Queries'),
			static::CATEGORY_APPLICATION	=>	Label::getLabel('LBL_Application_/_Requirements'),
			static::CATEGORY_PAYMENTS	=>	Label::getLabel('LBL_Payments'),
		);
	}
	public static function getSearchObject( $langId = 0 , $active =  true) {
		$langId = FatUtility::int($langId);
		$srch = new SearchBase(static::DB_TBL, 't');

		if ( $langId > 0) {
			$srch->joinTable( static::DB_TBL_LANG, 'LEFT OUTER JOIN',
			't_l.faqlang_faq_id = t.faq_id
			AND faqlang_lang_id = ' . $langId, 't_l');
		}
		if($active == true)
		{
			$srch->addCondition('t.faq_active','=',applicationConstants::ACTIVE);
		}
		return $srch;
	}
}