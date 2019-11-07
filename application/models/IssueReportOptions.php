<?php
class IssueReportOptions extends MyAppModel
{
    const DB_TBL = 'tbl_issue_report_options';
    const DB_TBL_PREFIX = 'tissueopt_';
    const DB_TBL_LANG = 'tbl_issue_report_options_lang';
    const DB_TBL_LANG_PREFIX = 'tissueoptlang_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public function getAllOptions($langId)
    {
        $srch = new IssueReportOptionsSearch($langId);
        $srch->addOrder(self::DB_TBL_PREFIX . 'display_order', 'asc');
        return $srch;
    }

    public static function getOptionsArray($langId)
    {
        $srch = new IssueReportOptionsSearch($langId);
        $srch->addMultipleFields(array(
            'tissueopt_id',
            'IFNULL(tissueoptlang_title, tissueopt_identifier) as optLabel',
        ));

        $rs = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }
        if (empty($records)) {
            return $records;
        }
        $optionArray = array();
        foreach ($records as $record) {
            $optionArray[$record['tissueopt_id']] = $record['optLabel'];
        }
        return $optionArray;
    }

    public function deleteOption($optId)
    {
        $db=FatApp::getDb();
        $langDelete=$db->deleteRecords(static::DB_TBL_LANG, array('smt'=>'tissueoptlang_tissueopt_id = ?','vals'=>array($optId)));
        if (!$db->deleteRecords(static::DB_TBL, array('smt'=>'tissueopt_id = ?','vals'=>array($optId))) && !$langDelete) {
            $this->error=$db->getError();
            return false;
        }
        return true;
    }
}
