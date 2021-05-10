<?php

class TeachLangPrice extends MyAppModel
{

    const DB_TBL = 'tbl_user_teach_lang_prices';
    const DB_TBL_PREFIX = 'ustelgpr_';

    protected $slabId;
    protected $teachLangId;
    protected $slot;

    public function __construct(int $slabId = 0, int $teachLangId, int $slot = 0)
    {
        $this->slabId = $slabId;
        $this->teachLangId = $teachLangId;
        $this->slot = $slot;
    }

    public function saveTeachLangPrice(int $price) : bool
    {
        $data = [
                    'ustelgpr_prislab_id' => $this->slabId,
                    'ustelgpr_utl_id' => $this->teachLangId,
                    'ustelgpr_slot' => $this->slot,
                    'ustelgpr_price' => $price
                ];

        $record = new TableRecord('tbl_teacher_stats');
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;  
    }

    public function saveMutipleLangPrice($data)
    {
        $query = ' INSERT INTO ';
        foreach ($data['duration'] as $durationKey => $duration) {
            if(empty($duration) || $durationKey != $duration){
                continue;
            }

            foreach ($data['teach_lang_price'][$duration] as $durationKey => $slots) {
                foreach ($slots as $key => $price) {
                       
                }  
            }
        }
    }

}
