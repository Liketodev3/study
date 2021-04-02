<?php

class TeacherGeneralAvailabilitySearch extends SearchBase
{

    public function __construct($doNotCalculateRecords = true)
    {
        $this->db = FatApp::getDb();
        parent::__construct(TeacherGeneralAvailability::DB_TBL, 'tga');
        if (true === $doNotCalculateRecords) {
            $this->doNotCalculateRecords();
        }
    }

}
