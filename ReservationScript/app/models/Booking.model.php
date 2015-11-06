<?php
require_once MODELS_PATH . 'App.model.php';
class BookingModel extends AppModel
{
	var $primaryKey = 'id';
	
	var $table = 'car_rental_bookings';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'uuid', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'car_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'pickup_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'return_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'from', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'to', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'total', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'deposit', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'tax', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'payment_method', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'txn_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'processed_on', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'c_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_fname', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_lname', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_phone', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_company', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_address_1', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_address_2', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_address_3', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_state', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_country', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'cc_type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_num', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_exp', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'locale_id', 'type' => 'tinyint', 'default' => ':NULL')
	);
}
?>