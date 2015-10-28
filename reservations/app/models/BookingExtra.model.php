<?php
require_once MODELS_PATH . 'App.model.php';
class BookingExtraModel extends AppModel
{
	var $primaryKey = 'id';
	
	var $table = 'car_rental_bookings_extras';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'extra_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL')
	);
}
?>