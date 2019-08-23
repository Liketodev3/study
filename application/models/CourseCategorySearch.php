<?php
class CourseCategorySearch extends SearchBase {
	
	public function __construct( $langId = 0 ) {
        parent::__construct(CourseCategory::DB_TBL, 'cc');
		$langId = FatUtility::int($langId);
		if( $langId > 0 ){
			$this->addCondition('cc.ccategory_active','=',applicationConstants::ACTIVE);
			$this->addCondition('cc.ccategory_deleted','=',applicationConstants::NO);
			$this->joinTable(CourseCategory::DB_TBL_LANG,'LEFT OUTER JOIN',
			'cc_l.'.CourseCategory::DB_LANG_TBL_PREFIX.'ccategory_id = cc.ccategory_id 
			AND cc_l.'.CourseCategory::DB_LANG_TBL_PREFIX.'lang_id = '.$langId,'cc_l');
		}
		
    }
	
}	
	
	