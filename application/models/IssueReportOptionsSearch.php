<?php
class IssueReportOptionsSearch extends SearchBase
{
    public function __construct($langId = 0, $doNotCalculateRecords = true)
    {
        $langId = FatUtility::int($langId);

        parent::__construct(IssueReportOptions::DB_TBL, 'iropt');

        if ($langId > 0) {
            $this->joinTable(
                IssueReportOptions::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'iroptLang.'. IssueReportOptions::DB_TBL_LANG_PREFIX .'tissueopt_id = iropt.'. IssueReportOptions::DB_TBL_PREFIX .'id
			AND iroptLang.'. IssueReportOptions::DB_TBL_LANG_PREFIX .'lang_id = ' . $langId,
                'iroptLang'
            );
        }
        
        if (true === $doNotCalculateRecords) {
            $this->doNotCalculateRecords();
        }
    }

    public function findByCriteria(array $criteria = []): array
    {
        if(isset($criteria['tissueopt_user_type'])){
            $this->addCondition('tissueopt_user_type', 'in', FatUtility::int($criteria['tissueopt_user_type']));
        }
        return FatApp::getDb()->fetchAll($this->getResultSet());
    }
}
