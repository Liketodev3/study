<?php
class OrderStatus extends MyAppModel {
	const DB_TBL = 'tbl_order_statuses';
	const DB_TBL_PREFIX = 'orderstatus_';
	
	public function __construct( $id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}
}