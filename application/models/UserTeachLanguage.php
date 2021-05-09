<?php

class UserTeachLanguage extends MyAppModel
{

    const DB_TBL = 'tbl_user_teach_languages';
    const DB_TBL_PREFIX = '	utl_';

    protected $userId;
    protected $teachLangId;
    protected $slot;

    public function __construct(int $userId = 0, $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->userId = $userId;
    }

    public function saveTeachLang(int $teachLangId) : bool
    {
        $data = [
                    'utl_tlanguage_id' => $this->teachLangId,
                    'utl_user_id' => $this->userId
                ];

        $record = new TableRecord('tbl_teacher_stats');
        $record->assignValues($data);
        if (!$record->addNew([], $data)) {
            $this->error = $record->getError();
            return false;
        }
        return true;  
    }

}
