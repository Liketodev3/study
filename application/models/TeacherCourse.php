<?php
class TeacherCourse extends MyAppModel
{
    const DB_TBL = 'tbl_teacher_courses';
    const DB_TBL_PREFIX = 'tcourse_';

    const LEVEL_BEGINNER = 1;
    const LEVEL_UPPER_BEGINNER = 2;
    const LEVEL_INTERMEDIATE = 3;
    const LEVEL_UPPER_INTERMEDIATE = 4;
    const LEVEL_ADVANCED = 5;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getDifficultyArr()
    {
        return array(
            static::LEVEL_BEGINNER	=>	Label::getLabel('LBL_Beginner'),
            static::LEVEL_UPPER_BEGINNER	=>	Label::getLabel('LBL_Upper_Beginner'),
            static::LEVEL_INTERMEDIATE	=>	Label::getLabel('LBL_Intermediate'),
            static::LEVEL_UPPER_INTERMEDIATE	=>	Label::getLabel('LBL_Upper_Intermediate'),
            static::LEVEL_ADVANCED	=>	Label::getLabel('LBL_Advanced'),
        );
    }
}
