<?php

class TeachLangPrice extends MyAppModel
{

    const DB_TBL = 'tbl_user_teach_lang_prices';
    const DB_TBL_PREFIX = 'ustelgpr_';

    protected $slabId;
    protected $userTeachLangId;
    protected $slot;

    public function __construct(int $slabId = 0, int $userTeachLangId = 0, int $slot = 0)
    {
        $this->slabId = $slabId;
        $this->userTeachLangId = $userTeachLangId;
        $this->slot = $slot;
    }

    public function saveTeachLangPrice(int $price) : bool
    {
        $data = [
                    'ustelgpr_prislab_id' => $this->slabId,
                    'ustelgpr_utl_id' => $this->userTeachLangId,
                    'ustelgpr_slot' => $this->slot,
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

}
