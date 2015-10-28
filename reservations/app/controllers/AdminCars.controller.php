<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminCars extends Admin
{
/**
 * Create car
 *
 * @access public
 * @return void
 */
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', 'I18n');
				$I18nModel = new I18nModel();
				
				$err = NULL;
				if (isset($_POST['car_create']))
				{
					if ($this->isDemo())
					{
						$err = 7;
					} else {
					
						Object::import('Model', 'Car');
						$CarModel = new CarModel();
						$id = $CarModel->save($_POST);
						if ($id !== false && (int) $id > 0)
						{
							if (isset($_POST['i18n']))
							{
								Object::import('Model', 'I18n');
								$I18nModel = new I18nModel();
								$I18nModel->saveI18n($_POST['i18n'], $id, 'Car');
							}
							if (isset($_POST['type_id']))
							{
								Object::import('Model', 'CarType');
								$CarTypeModel = new CarTypeModel();
								Object::addLinked($CarTypeModel->getTable(), 'car_id', 'type_id', $id, $_POST['type_id']);
							}
							$err = 1;
						} else {
							$err = 2;
						}
					}
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=$err");
				} else {
					Object::import('Model', array('Location', 'Type'));
					$LocationModel = new LocationModel();
					$TypeModel = new TypeModel();
					$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['location_arr'] = $LocationModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
					
					$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['type_arr'] = $TypeModel->getAll(array('col_name' => 't1.size', 'direction' => 'asc'));
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'adminCars.js', 'path' => JS_PATH);
				}				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Delete car, support AJAX too
 *
 * @access public
 * @return void
 */
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if ($this->isDemo())
				{
					$_GET['err'] = 7;
					$this->index();
					return;
				}
				
				if ($this->isXHR())
				{
					$this->isAjax = true;
					$id = $_POST['id'];
				} else {
					$id = $_GET['id'];
				}
				
				Object::import('Model', 'Car');
				$CarModel = new CarModel();
					
				$arr = $CarModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 1;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=8");
					}
				}
				
				if ($CarModel->delete($id))
				{
					Object::import('Model', 'I18n');
					$I18nModel = new I18nModel();
					$I18nModel->delete(array('foreign_id' => $id, 'model' => 'Extra'));
					if ($this->isXHR())
					{
						Object::import('Model', 'CarType');
						$CarTypeModel = new CarTypeModel();
						$CarTypeModel->delete(array('car_id' => $id));
						
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=4");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * List of cars
 *
 * (non-PHPdoc)
 * @see app/controllers/Admin::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Car', 'Location', 'Type', 'CarType', 'I18n'));
				$CarModel = new CarModel();
				$LocationModel = new LocationModel();
				$CarTypeModel = new CarTypeModel();
				$TypeModel = new TypeModel();
				$I18nModel = new I18nModel();
				
				$opts = array();
				if (isset($_GET['type_id']) && (int) $_GET['type_id'] > 0)
				{
					$opts['t1.id'] = array(sprintf("(SELECT `car_id` FROM `%s` WHERE `type_id` = '%u')", $CarTypeModel->getTable(), $_GET['type_id']), 'IN', 'null');
				}
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $CarModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;

				$CarModel->addJoin($CarModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.location_id', 'i18n.model' => "'Location'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
				$CarModel->addJoin($CarModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.id', 'i18n_1.model' => "'Car'", 'i18n_1.locale' => "'1'", 'i18n_1.field' => "'make'"), array('i18n_1.content.make'));
				$CarModel->addJoin($CarModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.id', 'i18n_2.model' => "'Car'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'model'"), array('i18n_2.content.model'));
				$arr = $CarModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 'i18n_1.content ASC, i18n_2.content', 'direction' => 'asc')));
				foreach ($arr as $k => $v)
				{
					$CarTypeModel->addJoin($CarTypeModel->joins, $TypeModel->getTable(), 'TT', array('TT.id' => 't1.type_id'), array('TT.size'));
					$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.type_id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$arr[$k]['CarType'] = $CarTypeModel->getAll(array('t1.car_id' => $v['id'], 'col_name' => 't1.type_id', 'direction' => 'asc'));
				}
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = compact('pages', 'count', 'row_count');
									
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'adminCars.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Update car
 *
 * @param int $id
 * @access public
 * @return void
 */
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Car', 'CarType', 'I18n'));
				$CarModel = new CarModel();
				$CarTypeModel = new CarTypeModel();
				$I18nModel = new I18nModel();
				
				if (isset($_POST['car_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=7");
					}
				
					$data = array();
					$CarModel->update(array_merge($_POST, $data));
					$I18nModel->updateI18n($_POST['i18n'], $_POST['id'], 'Car');
					
					$CarTypeModel->delete(array('car_id' => $_POST['id']));
					if (isset($_POST['type_id']))
					{
						Object::import('Model', 'CarType');
						$CarTypeModel = new CarTypeModel();
						Object::addLinked($CarTypeModel->getTable(), 'car_id', 'type_id', $_POST['id'], $_POST['type_id']);
					}
					
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=5");

				} else {
					$arr = $CarModel->get($_GET['id']);
					if (count($arr) === 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCars&action=index&err=8");
					}
					$arr['i18n'] = $I18nModel->getI18n($arr['id'], 'Car');
					$this->tpl['arr'] = $arr;
					
					Object::import('Model', array('Location', 'Type'));
					$LocationModel = new LocationModel();
					$TypeModel = new TypeModel();

					$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['location_arr'] = $LocationModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
					
					$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['type_arr'] = $TypeModel->getAll(array('col_name' => 't1.size', 'direction' => 'asc'));
					$this->tpl['linked_types'] = Object::getLinked($CarTypeModel->getTable(), 'car_id', 'type_id', $arr['id']);
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				}
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminCars.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}