<?php
class UserToLanguage extends MyAppModel
{
    const DB_TBL = 'tbl_user_to_spoken_languages';
    const DB_TBL_PREFIX = 'utsl_';

    const DB_TBL_TEACH = 'tbl_user_teach_languages';
    const DB_TBL_TEACH_PREFIX = 'utl_';


    public function __construct($userId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'user_id', $userId);
    }

    public static function getUserTeachlanguages($useId, $joinTeachLangLang = false)
    {
        $useId = FatUtility::int($useId);
        $langId = CommonHelper::getLangId();
        $srch = new SearchBase(static::DB_TBL_TEACH, 'utl');
        $srch->addCondition('utl_us_user_id','=',$useId);
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage_id = utl_slanguage_id','tl');
        if($joinTeachLangLang){
            $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT JOIN', 'tlanguage_id = tlanguagelang_tlanguage_id and tlanguagelang_lang_id ='.$langId,'tll');
        }
        return $srch;
    }
    
    public static function getTeachingAssoc($teacherId, $langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }

        $TeachingLangSrch = new SearchBase(self::DB_TBL_TEACH, 'tt');
        
        $TeachingLangSrch->joinTable(
            TeachingLanguage::DB_TBL,
            'LEFT OUTER JOIN',
            'tl.tlanguage_id = tt.utl_slanguage_id',
            'tl'
        );
        
        $TeachingLangSrch->joinTable(
            TeachingLanguage::DB_TBL.'_lang',
            'LEFT OUTER JOIN',
            'tl_l.tlanguagelang_tlanguage_id = tl.tlanguage_id AND tl_l.tlanguagelang_lang_id = '.$langId,
            'tl_l'
        );
        $TeachingLangSrch->addCondition('utl_us_user_id', '=', $teacherId);
        
        $TeachingLangSrch->doNotCalculateRecords();
        $TeachingLangSrch->addMultiplefields(array('tlanguage_id', 'IFNULL(tlanguage_name, tlanguage_identifier) as tlanguage_name'));
        $rs = $TeachingLangSrch->getResultSet();
        $teachingLanguagesArr = FatApp::getDb()->fetchAllAssoc($rs);
        return $teachingLanguagesArr;
    }
    
    public static function getAttributesByUserAndLangId($recordId, $langId, $attr = null)
    {
        $recordId = FatUtility::convertToType($recordId, FatUtility::VAR_INT);
        $db = FatApp::getDb();

        $srch = new SearchBase(static::DB_TBL_TEACH);
        $srch->addCondition(static::DB_TBL_TEACH_PREFIX . 'us_user_id', '=', $recordId);
        $srch->addCondition(static::DB_TBL_TEACH_PREFIX . 'slanguage_id', '=', $langId);

        if (null != $attr) {
            if (is_array($attr)) {
                $srch->addMultipleFields($attr);
            } elseif (is_string($attr)) {
                $srch->addFld($attr);
            }
        }

        $rs = $srch->getResultSet();
        $row = $db->fetch($rs);

        if (!is_array($row)) {
            return false;
        }

        if (is_string($attr)) {
            return $row[$attr];
        }

        return $row;
    }

    public function getTeachingSettings($langId)
    {
        $srch = new SearchBase(static::DB_TBL_TEACH, 'utl');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tlanguage_id = utl.utl_slanguage_id');
        $srch->joinTable(TeachingLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'tlanguagelang_tlanguage_id = utl.utl_slanguage_id AND tlanguagelang_lang_id = '. $langId, 'sl_lang');
        $srch->addMultipleFields(array(
            'tlanguage_id',
            'tlanguage_name',
            'utl_single_lesson_amount',
            'utl_bulk_lesson_amount',
            'utl_booking_slot',
        ));
        $srch->addCondition('utl_single_lesson_amount', '>', 0);
        $srch->addCondition('utl_bulk_lesson_amount', '>', 0);
        $srch->addCondition('utl_slanguage_id', '>', 0);
        $srch->addCondition('tlanguage_active', '=', '1');
        $srch->addCondition('utl_us_user_id', '=', $this->getMainTableRecordId());
        $rs = $srch->getResultSet();
		return FatApp::getDb()->fetchAll($rs);
    }

    public function getTeachingSlots()
    {
        $srch = new SearchBase(static::DB_TBL_TEACH, 'utl');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tlanguage_id = utl.utl_slanguage_id');
        $srch->addFld(array('utl_id', 'utl_booking_slot'));
        $srch->addCondition('utl_single_lesson_amount', '>', 0);
        $srch->addCondition('utl_bulk_lesson_amount', '>', 0);
        $srch->addCondition('utl_slanguage_id', '>', 0);
        $srch->addCondition('tlanguage_active', '=', 1);
        $srch->addCondition('utl_us_user_id', '=', $this->getMainTableRecordId());
        $srch->addGroupBy('utl_booking_slot');
        $rs = $srch->getResultSet();
		return FatApp::getDb()->fetchAllAssoc($rs);
    }

    public function getAttributesByLangAndSlot($langId, $slot, $attr = null)
    {
        $srch = new SearchBase(static::DB_TBL_TEACH);
        $srch->addCondition(static::DB_TBL_TEACH_PREFIX . 'us_user_id', '=', $this->mainTableRecordId);
        $srch->addCondition(static::DB_TBL_TEACH_PREFIX . 'slanguage_id', '=', $langId);
        $srch->addCondition(static::DB_TBL_TEACH_PREFIX . 'booking_slot', '=', $slot);

        if (null != $attr) {
            if (is_array($attr)) {
                $srch->addMultipleFields($attr);
            } elseif (is_string($attr)) {
                $srch->addFld($attr);
            }
        }

        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        if (!is_array($row)) {
            return false;
        }

        if (is_string($attr)) {
            return $row[$attr];
        }

        return $row;
    }

    public function saveTeachLang($langData)
    {
        $langData['utl_us_user_id'] = $this->mainTableRecordId;
        parent::__construct(self::DB_TBL_TEACH, self::DB_TBL_TEACH_PREFIX . 'user_id', $this->mainTableRecordId);
        $this->assignValues($langData);
        return $this->objMainTableRecord->addNew(array(), array('utl_single_lesson_amount' => $langData['utl_single_lesson_amount'], 'utl_bulk_lesson_amount' => $langData['utl_bulk_lesson_amount']));
    }
    
    public function getTeacherPricesForLearner($langId, $learnerId)
    {
        $srch = new SearchBase(static::DB_TBL_TEACH, 'utl');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tlanguage_id = utl.utl_slanguage_id');
        $srch->joinTable(TeachingLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'tlanguagelang_tlanguage_id = utl.utl_slanguage_id AND tlanguagelang_lang_id = '. $langId, 'sl_lang');
        $srch->joinTable(TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'utl_booking_slot = top_lesson_duration AND top_teacher_id = utl_us_user_id AND top_learner_id = '.$learnerId, 'top');
        $srch->addMultipleFields(array(
            'tlanguage_id',
            'tlanguage_name',
            'IFNULL(top_single_lesson_price, utl_single_lesson_amount) utl_single_lesson_amount',
            'IFNULL(top_bulk_lesson_price, utl_bulk_lesson_amount) utl_single_lesson_amount',
            'utl_booking_slot',
        ));
        $srch->addCondition('utl_single_lesson_amount', '>', 0);
        $srch->addCondition('utl_bulk_lesson_amount', '>', 0);
        $srch->addCondition('utl_slanguage_id', '>', 0);
        $srch->addCondition('tlanguage_active', '=', '1');
        $srch->addCondition('utl_us_user_id', '=', $this->getMainTableRecordId());
        $rs = $srch->getResultSet();
		return FatApp::getDb()->fetchAll($rs);
    }
}
