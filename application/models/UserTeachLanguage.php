<?php

class UserTeachLanguage extends MyAppModel
{

    const DB_TBL = 'tbl_user_teach_languages';
    const DB_TBL_PREFIX = 'utl_';

    protected $userId;
    protected $slot;

    public function __construct(int $userId = 0, $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->userId = $userId;
    }

    public function saveTeachLang(int $teachLangId) : bool
    {
        if(empty($this->userId)){
            $this->error = Label::getLabel('lbl_invalid_request');
            return false;
        }
        $data = [
                    'utl_tlanguage_id' => $teachLangId,
                    'utl_user_id' => $this->userId
                ];
        $this->assignValues($data);
        if (!$this->addNew([], $data)) {
            return false;
        }
        return true;  
    }

    public function getUserTeachlanguages(int $langId = 0, $withPrice = false) : SearchBase
    {
        $searchBase = new SearchBase(static::DB_TBL, 'utl');
        $searchBase->addCondition('utl_user_id', '=', $this->userId);
        $searchBase->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'tlanguage_id = utl_tlanguage_id', 'tl');
        if ($langId > 0) {
            $searchBase->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT JOIN', 'tlanguage_id = tlanguagelang_tlanguage_id and tlanguagelang_lang_id =' . $langId, 'tll');
        }
        if($withPrice){
            $searchBase->joinTable(TeachLangPrice::DB_TBL, 'LEFT JOIN', 'ustelgpr.ustelgpr_utl_id = utl.utl_id', 'ustelgpr');
        }
        return $searchBase;
    }

 

}
