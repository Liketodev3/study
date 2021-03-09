<?php
class LessonPackage extends MyAppModel
{
    const DB_TBL = 'tbl_lesson_packages';
    const DB_TBL_PREFIX = 'lpackage_';

    const DB_TBL_LANG = 'tbl_lesson_packages_lang';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(array(
            'lpackage_is_free_trial'
        ));
    }

    public static function getSearchObject($langId = 0, $active =  true)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 't');

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                't_l.lpackagelang_lpackage_id = t.lpackage_id
			AND lpackagelang_lang_id = ' . $langId,
                't_l'
            );
        }
        if ($active == true) {
            $srch->addCondition('t.lpackage_active', '=', applicationConstants::ACTIVE);
        }
        return $srch;
    }

    public static function getFreeTrialPackage($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        $srch = static::getSearchObject($langId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition('lpackage_is_free_trial', '=', applicationConstants::YES);
        $srch->addMultipleFields(array('lpackage_id', 'IFNULL(lpackage_title, lpackage_identifier) as lpackage_title', 'lpackage_lessons', 'lpackage_active' ));
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if ($row) {
            $row['lpackage_lessons'] = $row['lpackage_lessons'] * 1;
        }
        return $row;
    }

    public static function isAlreadyPurchasedFreeTrial($learnerId, $teacherId)
    {
        $learnerId = FatUtility::int($learnerId);
        $teacherId = FatUtility::int($teacherId);

        if ($learnerId < 1 || $teacherId < 1) {
            trigger_error("Invalid Request", E_USER_ERROR);
        }

        $srch = new OrderProductSearch(0, true, false);
        $srch->joinOrders();
        $srch->joinScheduleLessonDetails();
        $srch->joinScheduleLesson(false);
        $srch->setPageSize(1);
        $srch->addCondition('order_user_id', '=', $learnerId);
        $srch->addCondition('op_teacher_id', '=', $teacherId);
        $srch->addCondition('op_lpackage_is_free_trial', '=', 1);
        $srch->addCondition('slesson_status', '!=', ScheduledLesson::STATUS_CANCELLED);
        $srch->addMultipleFields(array('op_id'));
        $rs = $srch->getResultSet();
        if (empty(FatApp::getDb()->fetch($rs))) {
            return false;
        }
        return true;
    }

    public function canRecordMarkDelete($lPackageId) : bool
    {
        $srch = static::getSearchObject();
        $srch->addCondition('lpackage_id', '=', $lPackageId);
        $srch->addFld('lpackage_id');
        $srch->addFld('lpackage_is_free_trial');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        if (!empty($row) && $row['lpackage_id'] == $lPackageId && $row['lpackage_is_free_trial'] == applicationConstants::NO) {
            return true;
        }
        return false;
    }

    public static function getPackagesWithoutTrial($langId)
    {
        $srch = self::getSearchObject($langId);
		$srch->addCondition('lpackage_is_free_trial', '=', 0);
		$srch->addMultipleFields(array(
			'lpackage_id',
			'IFNULL(lpackage_title, lpackage_identifier) as lpackage_title',
			'lpackage_lessons'
        ));
		$rs = $srch->getResultSet();
		return FatApp::getDb()->fetchAll($rs);
    }
}
