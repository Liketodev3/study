<?php
class ScheduledLessonSearch extends SearchBase {
	private $isTeacherSettingsJoined;
	private $isOrderJoined;
	
	public function __construct( $doNotCalculateRecords = true ){
		parent::__construct(ScheduledLesson::DB_TBL, 'slns');
		
		$this->isTeacherSettingsJoined = false;
		$this->isOrderJoined = false;
		
		if( true === $doNotCalculateRecords ){
			$this->doNotCalculateRecords();
		}
	}
	
	public function joinTeacher(){
		$this->joinTable( User::DB_TBL, 'INNER JOIN', 'ut.user_id = slns.slesson_teacher_id', 'ut' );
	}
	
	public function joinLearner(){
		$this->joinTable( User::DB_TBL, 'INNER JOIN', 'ul.user_id = slns.slesson_learner_id', 'ul' );
	}
	
	public function joinTeacherSettings(){
		if( true === $this->isTeacherSettingsJoined ){
			return;
		}
		$this->joinTable( UserSetting::DB_TBL, 'INNER JOIN', 'ts.us_user_id = slns.slesson_teacher_id', 'ts' );
		$this->isTeacherSettingsJoined = true;
	}
	
	public function joinIssueReported($userType) {
		$this->joinTable( ' ( SELECT MAX(issrep_id) max_id, issrep_slesson_id FROM '. IssuesReported::DB_TBL .' GROUP BY issrep_slesson_id )', 'LEFT JOIN', 'i_max.issrep_slesson_id = slns.slesson_id', 'i_max' );
		$this->joinTable(IssuesReported::DB_TBL, 'LEFT JOIN', 'iss.issrep_id = i_max.max_id', 'iss' );
	}
	
	public function joinTeacherCountry( $langId = 0 ){
		$langId = FatUtility::int($langId);
		
		$this->joinTable( Country::DB_TBL, 'LEFT JOIN', 'ut.user_country_id = teachercountry.country_id', 'teachercountry' );
		
		if( $langId > 0 ){
			$this->joinTable( Country::DB_TBL_LANG, 'LEFT JOIN', 'teachercountry.country_id = teachercountry_lang.countrylang_country_id AND teachercountry_lang.countrylang_lang_id = '.$langId, 'teachercountry_lang' );
		}
	}
	
	public function joinLearnerCountry( $langId = 0 ){
		$langId = FatUtility::int($langId);
		$this->joinTable( Country::DB_TBL, 'LEFT JOIN', 'ul.user_country_id = learnercountry.country_id', 'learnercountry' );
		
		if( $langId > 0 ){
			$this->joinTable( Country::DB_TBL_LANG, 'LEFT JOIN', 'learnercountry.country_id = learnercountry_lang.countrylang_country_id AND learnercountry_lang.countrylang_lang_id = '.$langId, 'learnercountry_lang' );
		}
	}
	
	public function joinTeacherTeachLanguageView( $langId = 0 ){
		$langId = FatUtility::int($langId);
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();
		}
		//$this->joinTable( UserToLanguage::DB_TBL_TEACH, 'LEFT  JOIN', 'ut.user_id = utsl.utl_us_user_id', 'utsl' );
		
		$this->joinTable( TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tlanguage_id = slns.slesson_slanguage_id' );
		
		$this->joinTable( TeachingLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'tlanguagelang_tlanguage_id = slns.slesson_slanguage_id AND tlanguagelang_lang_id = '. $langId , 'sl_lang' );
		
		$this->addMultipleFields( array('GROUP_CONCAT( DISTINCT IFNULL(tlanguage_name, tlanguage_identifier) ) as teacherTeachLanguageName') );
	}
	
	public function joinTeacherTeachLanguage( $langId = 0 ){
		if( false === $this->isTeacherSettingsJoined ){
			trigger_error( "First use 'joinTeacherSettings' before joining 'joinTeacherTeachLanguage'", E_USER_ERROR );
		}
		
		$langId = FatUtility::int($langId);
		$this->joinTable( SpokenLanguage::DB_TBL, 'INNER JOIN', 't_sl.slanguage_id = ts.us_teach_slanguage_id', 't_sl' );
		
		if( $langId > 0 ){
			$this->joinTable( SpokenLanguage::DB_TBL_LANG, 'LEFT JOIN', 't_sl.slanguage_id = t_sl_l.slanguagelang_slanguage_id AND slanguagelang_lang_id = '.$langId, 't_sl_l' );
		}
	}
	
	public function joinLessonLanguage( $langId = 0 ){
		$langId = FatUtility::int($langId);
		$this->joinTable( TeachingLanguage::DB_TBL, 'INNER JOIN', 'slns.slesson_slanguage_id = tlang.tlanguage_id', 'tlang' );
		if ( $langId > 0) {
			$this->joinTable( TeachingLanguage::DB_TBL_LANG, 'LEFT OUTER JOIN','sl.tlanguagelang_tlanguage_id = tlang.tlanguage_id AND tlanguagelang_lang_id = ' . $langId, 'sl');
		}
	}
	
	
	public function joinOrder( ){
		if( true === $this->isOrderJoined ){
			return;
		}
		$this->joinTable( Order::DB_TBL, 'INNER JOIN', 'o.order_id = slns.slesson_order_id AND o.order_type = ' . Order::TYPE_LESSON_BOOKING, 'o' );
		$this->isOrderJoined = true;
	}
	
	public function joinOrderProducts(){
		if( false === $this->isOrderJoined ){
			trigger_error( "First Use Join Order, to Join OrderProducts", E_USER_ERROR );
		}
		$this->joinTable( OrderProduct::DB_TBL, 'INNER JOIN', 'o.order_id = op.op_order_id', 'op' );
	}
	
	public function joinTeacherCredentials(){
		$this->joinTable( User::DB_TBL_CRED, 'INNER JOIN', 'tcred.credential_user_id = slns.slesson_teacher_id', 'tcred' );
	}
	
	public function joinLearnerCredentials(){
		$this->joinTable( User::DB_TBL_CRED, 'INNER JOIN', 'lcred.credential_user_id = slns.slesson_learner_id', 'lcred' );
	}
	
	public function joinTeacherOfferPrice( $teacherId ) {
		$teacherId = FatUtility::int($teacherId);
		if( $teacherId < 1 ){
			trigger_error( "Invalid Request", E_USER_ERROR );
		}
		
		$this->joinTable( TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'slesson_learner_id = top_learner_id AND top_teacher_id = '.$teacherId, 'top' );
	}
	
	public function joinLearnerOfferPrice( $learnerId ){
		$learnerId = FatUtility::int($learnerId);
		if( $learnerId < 1 ){
			trigger_error( "Invalid Request", E_USER_ERROR );
		}
		
		$this->joinTable( TeacherOfferPrice::DB_TBL, 'LEFT JOIN', 'slesson_teacher_id = top_teacher_id AND top_learner_id = '.$learnerId, 'top' );
	}
	
	public static function countPlansRelation($lessonId){
		$lessonId = FatUtility::int($lessonId);
		//$planId = FatUtility::int($planId);
		$srchRelLsnToPln = new SearchBase('tbl_scheduled_lessons_to_teachers_lessons_plan');
		$srchRelLsnToPln->addMultipleFields(array(
		'ltp_tlpn_id',
		'ltp_slessonid',
		)
		);
		//$srchRelLsnToPln->addCondition('ltp_tlpn_id','=',$planId);
		$srchRelLsnToPln->addCondition('ltp_slessonid','=',$lessonId);
		$relRs = $srchRelLsnToPln->getResultSet();
		$count = $srchRelLsnToPln->recordCount();
		return $count;
	}
    public function joinRatingReview(){
        $this->joinTable( TeacherLessonReview::DB_TBL, 'LEFT OUTER JOIN', 'ut.user_id = tlr.tlreview_teacher_user_id AND tlr.tlreview_status = '. TeacherLessonReview::STATUS_APPROVED, 'tlr' );
        $this->joinTable( TeacherLessonRating::DB_TBL, 'LEFT OUTER JOIN', 'tlrating.tlrating_tlreview_id = tlr.tlreview_id', 'tlrating');
        $this->addMultipleFields(array("ROUND(AVG(tlrating_rating),2) as teacher_rating","count(DISTINCT tlreview_id) as totReviews"));
    }
	/***************/
	public function joinUserTeachLanguages( $langId = 0 ){
		$langId = FatUtility::int($langId);
		if( $langId < 1 ){
			$langId = CommonHelper::getLangId();
		}
		$this->joinTable( UserToLanguage::DB_TBL_TEACH, 'LEFT  JOIN', 'ut.user_id = utsl.utl_us_user_id', 'utsl' );
		
		$this->joinTable( TeachingLanguage::DB_TBL, 'LEFT JOIN', 'tlanguage_id = utsl.utl_slanguage_id' );
		
		$this->joinTable( TeachingLanguage::DB_TBL . '_lang', 'LEFT JOIN', 'tlanguagelang_tlanguage_id = utsl.utl_slanguage_id AND tlanguagelang_lang_id = '. $langId , 'sl_lang' );
		
		$this->addMultipleFields( array('utsl.utl_us_user_id', 'GROUP_CONCAT( DISTINCT IFNULL(tlanguage_name, tlanguage_identifier) ) as teacherTeachLanguageName') );
		
	}
	
	
	
	
}	
	
	