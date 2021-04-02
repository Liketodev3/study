<?php

class TeacherRequestValue extends MyAppModel
{

    const DB_TBL = 'tbl_user_teacher_request_values';
    const DB_TBL_PREFIX = 'utrvalue_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

}
