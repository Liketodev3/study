<?php 
class UserQualificationSearch extends SearchBase{
	
	public function __construct( $doNotCalculateRecords = true, $doNotLimitRecords = true ) {
		parent::__construct( UserQualification::DB_TBL, 'uq');
		
		if( true === $doNotCalculateRecords ){
			$this->doNotCalculateRecords();
		}
		
		if( true === $doNotLimitRecords ){
			$this->doNotLimitRecords();
		}
	}
	
}