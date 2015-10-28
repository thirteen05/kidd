<?php
require_once MODELS_PATH . 'App.model.php';
class I18nModel extends AppModel
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
	var $table = 'car_rental_i18n';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'model', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'locale', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'field', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'content', 'type' => 'text', 'default' => ':NULL')
	);
	
	function saveI18n($data, $foreign_id, $model)
	{
		foreach ($data as $locale => $locale_arr)
		{
			foreach ($locale_arr as $field => $content)
			{
				$this->save(array(
					'foreign_id' => $foreign_id,
					'model' => $model,
					'locale' => $locale,
					'field' => $field,
					'content' => $content
				));
			}
		}
	}
	
	function updateI18n($data, $foreign_id, $model)
	{
		$limit = 1;
		foreach ($data as $locale => $locale_arr)
		{
			foreach ($locale_arr as $field => $content)
			{
				$sql = sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`) 
					VALUES (NULL, '%2\$u', '%3\$s', '%4\$u', '%5\$s', '%6\$s')
					ON DUPLICATE KEY UPDATE `content` = '%6\$s';", 
					$this->getTable(), intval($foreign_id), Object::escapeString($model), intval($locale), Object::escapeString($field), Object::escapeString($content)
				);
				$this->execute($sql);
			}
		}
	}
	
	function getI18n($foreign_id, $model)
	{
		$arr = array();
		$_arr = $this->getAll(array('foreign_id' => $foreign_id, 'model' => $model));
		foreach ($_arr as $_k => $_v)
		{
			$arr[$_v['locale']][$_v['field']] = $_v['content'];			
		}
		return $arr;
	}
}
?>