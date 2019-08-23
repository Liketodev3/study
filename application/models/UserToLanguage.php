<?php
class UserToLanguage extends MyAppModel{
	
	const DB_TBL = 'tbl_user_to_spoken_languages';
	const DB_TBL_PREFIX = 'utsl_';

    const DB_TBL_TEACH = 'tbl_user_teach_languages';
    const DB_TBL_TEACH_PREFIX = 'utl_';
	
	public function __construct( $userId = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'user_id', $userId );
	}

}