<?php

class CourseCategory extends MyAppModel
{

    const DB_TBL = 'tbl_courses_categories';
    const DB_TBL_PREFIX = 'ccategory_';
    const DB_TBL_LANG = 'tbl_courses_categories_lang';
    const DB_LANG_TBL_PREFIX = 'ccategorylang_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getSearchObject($langId = 0, $active = true)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 't');
        if ($langId > 0) {
            $srch->joinTable(static::DB_TBL_LANG, 'LEFT OUTER JOIN', 't_l.ccategorylang_ccategory_id = t.ccategory_id AND ccategorylang_lang_id = ' . $langId, 't_l');
        }
        if ($active == true) {
            $srch->addCondition('t.ccategory_active', '=', applicationConstants::ACTIVE);
        }
        $srch->addCondition('t.ccategory_deleted', '=', applicationConstants::NO);
        return $srch;
    }

    public function canRecordMarkDelete($lPackageId)
    {
        $srch = static::getSearchObject();
        $srch->addCondition('ccategory_deleted', '=', applicationConstants::NO);
        $srch->addCondition('ccategory_id', '=', $lPackageId);
        $srch->addFld('ccategory_id');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row) && $row['ccategory_id'] == $lPackageId) {
            return true;
        }
        return false;
    }

}
