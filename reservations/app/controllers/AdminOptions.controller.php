<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminOptions extends Admin
{
/**
 * (non-PHPdoc)
 * @see app/controllers/Admin::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				$opts = array();
				
				Object::import('Model', 'Option');
				$OptionModel = new OptionModel();
								
				$arr = $OptionModel->getAll(array_merge($opts, array('col_name' => 't1.group ASC, t1.order', 'direction' => 'asc')));
				
				$_arr = array();
				foreach ($arr as $i => $v)
				{
					if (!array_key_exists($v['group'], $_arr))
		            {
		                $_arr[$v['group']] = array();
		            }
		            $_arr[$v['group']][] = $v;
				}

				$this->tpl['arr'] = $_arr;
				if (isset($_GET['tab']) && (int) $_GET['tab'] == 2)
				{
					Object::import('Model', array('Price', 'Type', 'I18n'));
					$PriceModel = new PriceModel();
					$TypeModel = new TypeModel();
					$I18nModel = new I18nModel();
					$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['type_arr'] = $TypeModel->getAll(array('col_name' => 't1.size ASC, i18n.content', 'direction' => 'asc'));
					$this->tpl['price_arr'] = $PriceModel->getAll(array('col_name' => 't1.type_id ASC, t1.date_from ASC, t1.date_to', 'direction' => 'asc'));
				}
				
				if (isset($_GET['tab']) && in_array((int) $_GET['tab'], array(4, 6)))
				{
					$terms_id = NULL;
					$ecs_id = NULL;
					$eps_id = NULL;
					$ecm_id = NULL;
					$epm_id = NULL;
					foreach ($arr as $v)
					{
						if ($v['key'] == 'terms')
						{
							$terms_id = $v['id'];
						}
						if ($v['key'] == 'email_confirmation_subject')
						{
							$ecs_id = $v['id'];
						}
						if ($v['key'] == 'email_payment_subject')
						{
							$eps_id = $v['id'];
						}
						if ($v['key'] == 'email_confirmation_message')
						{
							$ecm_id = $v['id'];
						}
						if ($v['key'] == 'email_payment_message')
						{
							$epm_id = $v['id'];
						}
					}
					
					Object::import('Model', 'I18n');
					$I18nModel = new I18nModel();
					switch ($_GET['tab'])
					{
						case 6:
							$this->tpl['i18n_'.$terms_id] = $I18nModel->getI18n($terms_id, 'Option');
							break;
						case 4:
							$this->tpl['i18n_'.$ecm_id] = $I18nModel->getI18n($ecm_id, 'Option');
							$this->tpl['i18n_'.$ecs_id] = $I18nModel->getI18n($ecs_id, 'Option');
							$this->tpl['i18n_'.$epm_id] = $I18nModel->getI18n($epm_id, 'Option');
							$this->tpl['i18n_'.$eps_id] = $I18nModel->getI18n($eps_id, 'Option');
							break;
					}
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				}
				
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminOptions.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Install
 *
 * @access public
 * @return void
 */
	function install()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				$this->js[] = array('file' => 'adminOptions.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function deletePrices()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			Object::import('Model', 'Price');
			$PriceModel = new PriceModel();
			$PriceModel->delete(array('id' => array(0, '>', 'int')));
		}
	}
	
	function setPrices()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			Object::import('Model', 'Price');
			$PriceModel = new PriceModel();
			if (isset($_POST['type_id']))
			{
				$data = array();
				$cnt = count($_POST['type_id']);
				foreach ($_POST['type_id'] as $k => $type)
				{
					$data['type_id'] = $_POST['type_id'][$k];
					$from_date = Object::escapeString(Util::formatDate($_POST['date_from'][$k], $this->option_arr['date_format']));
					$to_date = Object::escapeString(Util::formatDate($_POST['date_to'][$k], $this->option_arr['date_format']));
					$data['date_from'] = $from_date;
					$data['date_to'] = $to_date;
					
					$length_1_4 = round(($_POST['time_to'][$k] - $_POST['time_from'][$k])/4);
					$length_1_2 = round(($_POST['time_to'][$k] - $_POST['time_from'][$k])/2);
					
					$data['time_from'] = $_POST['time_from'][$k] .":00:00";
					$data['time_to'] = $_POST['time_to'][$k].":00:00";
					$data['length_1_4'] = $_POST['length_1_4'][$k];;
					$data['length_1_2'] = $_POST['length_1_2'][$k];;
					$data['price_1_4'] = $_POST['price_1_4'][$k];
                                        $data['price_1_3'] = $_POST['price_1_3'][$k];
					$data['price_1_2'] = $_POST['price_1_2'][$k];
                                        $data['price_1_E'] = $_POST['price_1_E'][$k];
					$data['price'] = $_POST['price'][$k];
						
                                        var_dump($data);
					$PriceModel->save($data);
				}
			}
		}
	}
/**
 * Update
 *
 * @access public
 * @return void
 */
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				if (isset($_POST['options_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminOptions&action=index&err=7");
					}
					Object::import('Model', 'Option');
					$OptionModel = new OptionModel();
						
					if (in_array($_POST['tab'], array(4,6)))
					{
						Object::import('Model', 'I18n');
						$I18nModel = new I18nModel();
						switch ($_POST['tab'])
						{
							case 6:
								$arr = $OptionModel->getAll(array('t1.key' => 'terms', 'offset' => 0, 'row_count' => 1));
								if (count($arr) === 1)
								{
									$I18nModel->updateI18n($_POST['i18n'], $arr[0]['id'], 'Option');
								}
								break;
							case 4:
								$arr = $OptionModel->getAll(array('t1.key' => 'email_confirmation_subject', 'offset' => 0, 'row_count' => 1));
								if (count($arr) === 1)
								{
									$I18nModel->updateI18n($_POST['i18n'], $arr[0]['id'], 'Option');
								}
								$arr = $OptionModel->getAll(array('t1.key' => 'email_confirmation_message', 'offset' => 0, 'row_count' => 1));
								if (count($arr) === 1)
								{
									$I18nModel->updateI18n($_POST['i18n'], $arr[0]['id'], 'Option');
								}
								$arr = $OptionModel->getAll(array('t1.key' => 'email_payment_subject', 'offset' => 0, 'row_count' => 1));
								if (count($arr) === 1)
								{
									$I18nModel->updateI18n($_POST['i18n'], $arr[0]['id'], 'Option');
								}
								$arr = $OptionModel->getAll(array('t1.key' => 'email_payment_message', 'offset' => 0, 'row_count' => 1));
								if (count($arr) === 1)
								{
									$I18nModel->updateI18n($_POST['i18n'], $arr[0]['id'], 'Option');
								}
								break;
						}
					}
					
					if ($_POST['tab'] == 2)
					{
						Object::import('Model', 'Price');
						$PriceModel = new PriceModel();
						
						$PriceModel->delete(array('id' => array(0, '>', 'int')));
						if (isset($_POST['type_id']))
						{
							$data = array();
							$cnt = count($_POST['type_id']);
							foreach ($_POST['type_id'] as $k => $type)
							{
								if ($k === $cnt - 1)
								{
									break;
								}
								$data['type_id'] = $_POST['type_id'][$k];
								
								$from_date = Object::escapeString(Util::formatDate($_POST['date_from'][$k], $this->option_arr['date_format']));
								$to_date = Object::escapeString(Util::formatDate($_POST['date_to'][$k], $this->option_arr['date_format']));
								$data['date_from'] = $from_date;
								$data['date_to'] = $to_date;
								
								$length_1_4 = round(($_POST['time_to'] - $_POST['time_from'])/4);
								$length_1_2 = round(($_POST['time_to'] - $_POST['time_from'])/2);
								
								$data['time_from'] = $_POST['time_from'][$k];
								$data['time_to'] = $_POST['time_to'][$k];
								$data['length_1_4'] = $length_1_4;
								$data['length_1_2'] = $length_1_2;
								$data['price_1_4'] = $_POST['price_1_4'][$k];
                                                                $data['price_1_3'] = $_POST['price_1_3'][$k];
								$data['price_1_2'] = $_POST['price_1_2'][$k];
                                                                $data['price_1_E'] = $_POST['price_1_E'][$k];
								$data['price'] = $_POST['price'][$k];
								
								$PriceModel->save($data);
							}
						}
					} else {
						foreach ($_POST as $key => $value)
						{
							if (preg_match('/value-(string|text|int|float|enum|color)-(.*)/', $key) === 1)
							{
								list(, $type, $k) = explode("-", $key);
								if (!empty($k))
								{
									$sql_value = $OptionModel->escape($value, null, $type);
									$k = $OptionModel->escape($k, null, 'string');
									mysql_query("UPDATE `".$OptionModel->getTable()."` SET `value` = '$sql_value' WHERE `key` = '$k' LIMIT 1") or die(mysql_error());
								}
							}
						}
						
						if (isset($_POST['username']) && isset($_POST['password']))
						{
							Object::import('Model', 'User');
							$UserModel = new UserModel();
							$data['username'] = $_POST['username'];
							$data['password'] = $_POST['password'];
							$data['id'] = $this->getUserId();
							if ($UserModel->update($data))
							{
								$_SESSION[$this->default_user]['password'] = $_POST['password'];
							}
						}
					}
					
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminOptions&action=index&err=5&tab=" . $_POST['tab']);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}