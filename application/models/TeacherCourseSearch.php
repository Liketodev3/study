<?php
class TeacherCourseSearch extends SearchBase
{
    public function __construct($doNotCalculateRecords = true)
    {
        parent::__construct(TeacherCourse::DB_TBL, 'tcourse');
        if (true === $doNotCalculateRecords) {
            $this->doNotCalculateRecords();
        }
    }
}
