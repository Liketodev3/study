<?php

class TeachLangPrice extends MyAppModel
{

    const DB_TBL = 'tbl_user_teach_lang_prices';
    const DB_TBL_PREFIX = 'ustelgpr_';

    protected $userTeachLangId;
    protected $slot;

    public function __construct(int $slot = 0, int $userTeachLangId = 0)
    {
        $this->userTeachLangId = $userTeachLangId;
        $this->slot = $slot;
    }

    public function saveTeachLangPrice(int $minSlab, int $maxSlab, float $price) : bool
    {
        $data = [
                    'ustelgpr_utl_id' => $this->userTeachLangId,
                    'ustelgpr_slot' => $this->slot,
                    'ustelgpr_min_slab' => $minSlab,
                    'ustelgpr_max_slab' => $maxSlab,
                    'ustelgpr_price' => $price
                ];

        $record = new TableRecord(self::DB_TBL);
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;  
    }

    
    public function deleteTeachSlots(array $slots): bool
    {
        if(empty($slots)){
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
        }

        $slots = implode(",", $slots);
        $db = FatApp::getDb();
        $db->query('DELETE  FROM ' . self::DB_TBL . ' WHERE ustelgpr_price IN (' . $slots . ')');
        if ($db->getError()) {
            return false;
        }
        return true;
    }


    public function getTeachingSlots(int $teacherId)
    {
        $srch = new SearchBase(static::DB_TBL, 'ustelgpr');
        $srch->joinTable(UserTeachLanguage::DB_TBL, 'INNER JOIN', 'utl.utl_id = ustelgpr_utl_id', 'utl');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage.tlanguage_id = utl.utl_tlanguage_id', 'tlanguage');
        $srch->addFld(['ustelgpr_slot as slot', 'ustelgpr_slot']);
        $srch->addCondition('ustelgpr_price', '>', 0);
        $srch->addCondition('utl_tlanguage_id', '>', 0);
        $srch->addCondition('tlanguage_active', '=', applicationConstants::YES);
        $srch->addCondition('utl_user_id', '=', $teacherId);
        $srch->addGroupBy('ustelgpr_slot');
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($rs);
    }

}
