<?php
require_once MODELS_PATH . 'App.model.php';
class TypeModel extends AppModel
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
	var $table = 'car_rental_types';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'passengers', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'luggages', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'doors', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'size', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'transmission', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'thumb_path', 'type' => 'varchar', 'default' => ':NULL')
	);
}
?>