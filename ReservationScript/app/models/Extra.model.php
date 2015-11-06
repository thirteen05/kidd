<?php
require_once MODELS_PATH . 'App.model.php';
class ExtraModel extends AppModel
{
	var $primaryKey = 'id';
	
	var $table = 'car_rental_extras';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'per', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'count', 'type' => 'smallint', 'default' => ':NULL')
	);
}
?>