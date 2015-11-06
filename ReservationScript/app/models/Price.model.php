<?php
require_once MODELS_PATH . 'App.model.php';
class PriceModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = 'id';
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'car_rental_prices';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'time_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'time_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'length_1_4', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'length_1_2', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'price_1_4', 'type' => 'decimal', 'default' => ':NULL'),
                array('name' => 'price_1_3', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'price_1_2', 'type' => 'decimal', 'default' => ':NULL'),
                array('name' => 'price_1_E', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL')
	);
}
?>