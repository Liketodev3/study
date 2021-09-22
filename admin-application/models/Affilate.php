<?php

class Affilate extends MyAppModel
{

    const DB_TBL = 'tbl_affilate_settings';
    const DB_TBL_PREFIX = 'conf_';

    public function __construct($id = 0)
    {
        parent::__construct(self::DB_TBL,"id", $id);
    }

    public function save()
    {
        $broken = false;
        if (!($this->getMainTableRecordId() > 0)) {
            $this->setFldValue('user_added_on', date('Y-m-d H:i:s'));
        }
        return parent::save();
    }

}
