<?php
require_once MODELS_PATH . 'App.model.php';
class TypeExtraModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = null;
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'car_rental_types_extras';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'type_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'extra_id', 'type' => 'int', 'default' => ':NULL')
	);
}
?>