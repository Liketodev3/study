<?php
class TeacherLessonReviewSearch extends SearchBase {
	private $langId;
	private $commonLangId;
	public function __construct( $langId = 0  ) {
		$langId = FatUtility::int($langId);
		$this->langId = $langId;
		parent::__construct(TeacherLessonReview::DB_TBL, 'tlr');
	}
	
	public function joinTeacher($langId = 0){
		$langId = FatUtility::int($langId);
		$this->commonLangId = CommonHelper::getLangId(); 
		if( $this->langId ){
			$langId = $this->langId;
		}
		
		$this->joinTable( User::DB_TBL, 'LEFT OUTER JOIN', 'ut.user_id = tlr.tlreview_teacher_user_id', 'ut' );
		$this->joinTable( User::DB_TBL_CRED, 'LEFT OUTER JOIN', 'usc.credential_user_id = ut.user_id', 'usc' );		
	}
	
	public function joinLearner(){
		$this->joinTable( User::DB_TBL, 'LEFT OUTER JOIN', 'ul.user_id = tlr.tlreview_postedby_user_id', 'ul' );
		$this->joinTable( User::DB_TBL_CRED, 'LEFT OUTER JOIN', 'uc.credential_user_id = ul.user_id', 'uc' );
	}
	
	public function joinTeacherLessonRating(){
		$this->joinTable( TeacherLessonRating::DB_TBL, 'LEFT OUTER JOIN', 'tlrating.tlrating_tlreview_id = tlr.tlreview_id', 'tlrating' );
	}

	public function joinScheduledLesson(){
		$this->joinTable( ScheduledLesson::DB_TBL, 'LEFT OUTER JOIN', 'tlr.tlreview_lesson_id = sl.slesson_id', 'sl' );
	}
	
}