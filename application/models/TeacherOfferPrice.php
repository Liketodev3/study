<?php
class TeacherOfferPrice extends MyAppModel
{
    const DB_TBL = 'tbl_teacher_offer_price';
    const DB_TBL_PREFIX = 'top_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public function saveData($data)
    {
        if (empty($data)) {
            $this->error = trigger_error("Empty data passed", E_USER_ERROR);
            return false;
        }

        $this->assignValues($data);
        return $this->addNew(array(), array('top_single_lesson_price' => $data['top_single_lesson_price'], 'top_bulk_lesson_price' => $data['top_bulk_lesson_price']));
    }

    public function removeOffer(int $learnerId, int $teacherId)
    {
        if (0 > $learnerId || 0 > $teacherId) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }

        if (!FatApp::getDb()->deleteRecords(static::DB_TBL, array(
            "smt"=>"top_learner_id = ? and top_teacher_id = ?",
            "vals"=>array($learnerId, $teacherId )
        ))) {
            FatUtility::dieWithError(FatApp::getDb()->getError());
        }
        return true;
    }

    public function getOffer(int $learnerId, int $teacherId, int $lessonDuration ) : object
    {
		// $learnerId = FatUtility::int( $learnerId );
		// $teacherId = FatUtility::int( $teacherId );
		if( 0 > $learnerId || 0 > $teacherId ){
			$this->error = Label::getLabel( 'LBL_Invalid_Request' );
			return false;
		}

		$srch = new SearchBase(self::DB_TBL, 'us');
		$srch->addCondition( 'top_learner_id','=',$learnerId );
		$srch->addCondition( 'top_teacher_id','=',$teacherId );
		$srch->addCondition( 'top_lesson_duration','=',$lessonDuration );
		return $srch;
	}

    public function getTeacherOfferPrices(int $learnerId, int $teacherId)
    {
        $srch = new SearchBase(self::DB_TBL, 'us');
		$srch->addCondition( 'top_learner_id', '=', $learnerId );
		$srch->addCondition( 'top_teacher_id', '=', $teacherId );
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }
}
