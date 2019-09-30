<?php
class IssueReportOptionsSearch extends SearchBase {
	
	public function __construct( $langId = 0, $doNotCalculateRecords = true ){
		$langId = FatUtility::int($langId);
		
		parent::__construct(IssueReportOptions::DB_TBL, 'iropt');
		
		if ( $langId > 0 ) {
			$this->joinTable( IssueReportOptions::DB_TBL_LANG, 'LEFT OUTER JOIN',
			'iroptLang.'. IssueReportOptions::DB_TBL_LANG_PREFIX .'tissueopt_id = iropt.'. IssueReportOptions::DB_TBL_PREFIX .'_id
			AND iroptLang.'. IssueReportOptions::DB_TBL_LANG_PREFIX .'_lang_id = ' . $langId, 'iroptLang');
		}
		
		if( true === $doNotCalculateRecords ){
			$this->doNotCalculateRecords();
		}
	}
}	