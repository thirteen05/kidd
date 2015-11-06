<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminLocations extends Admin
{
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$err = NULL;
				if (isset($_POST['location_create']))
				{
					if ($this->isDemo())
					{
						$err = 7;
					} else {
					
						Object::import('Model', 'Location');
						$LocationModel = new LocationModel();
						$data = $this->geocode(array_merge($_POST, $_POST['i18n'][1]));
						$id = $LocationModel->save(array_merge($_POST, $data));
						
						if ($id !== false && (int) $id > 0)
						{
							if (isset($_POST['i18n']))
							{
								Object::import('Model', 'I18n');
								$I18nModel = new I18nModel();
								$I18nModel->saveI18n($_POST['i18n'], $id, 'Location');
							}
							$err = 1;
						} else {
							$err = 2;
						}
					}
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=$err");
				} else {
					Object::import('Model', 'Country');
					$CountryModel = new CountryModel();
					$this->tpl['country_arr'] = $CountryModel->getAll(array('col_name' => 't1.country_title', 'direction' => 'asc'));
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'adminLocations.js', 'path' => JS_PATH);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}

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
				
				Object::import('Model', 'Location');
				$LocationModel = new LocationModel();
					
				$arr = $LocationModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 1;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=8");
					}
				}
				
				if ($LocationModel->delete($id))
				{
					Object::import('Model', 'I18n');
					$I18nModel = new I18nModel();
					$I18nModel->delete(array('foreign_id' => $id, 'model' => 'Location'));
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=4");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function geocode($post)
	{
		$address = array();
		$address[] = $post['zip'];
		$address[] = $post['address_1'];
		$address[] = $post['city'];
		$address[] = $post['state'];

		foreach ($address as $k => $v)
		{
			$tmp = preg_replace('/\s+/', '+', $v);
			$address[$k] = $tmp;
		}
		$_address = join(",+", $address);
							
		//http://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&sensor=false
		$gfile = "http://maps.googleapis.com/maps/api/geocode/json?address=$_address&sensor=false";
		$response = @file_get_contents($gfile);
		
		Object::import('Component', 'Services_JSON');
		$json = new Services_JSON();
		$geoObj = $json->decode($response);
		
		$data = array();
		if ($geoObj->status == 'OK')
		{
			$data['lat'] = $geoObj->results[0]->geometry->location->lat;
			$data['lng'] = $geoObj->results[0]->geometry->location->lng;
		} else {
			$data['lat'] = array('NULL');
			$data['lng'] = array('NULL');
		}
		return $data;
	}

	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Location', 'Car', 'I18n'));
				$LocationModel = new LocationModel();
				$CarModel = new CarModel();
				$I18nModel = new I18nModel();
				
				$opts = array();
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $LocationModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$LocationModel->addSubQuery($LocationModel->subqueries, sprintf("SELECT COUNT(*) FROM `%s` WHERE `location_id` = `t1`.`id` LIMIT 1", $CarModel->getTable()), 'num');
				$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
				$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.id', 'i18n_2.model' => "'Location'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'city'"), array('i18n_2.content.city'));
				$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n_3', array('i18n_3.foreign_id' => 't1.id', 'i18n_3.model' => "'Location'", 'i18n_3.locale' => "'1'", 'i18n_3.field' => "'address_1'"), array('i18n_3.content.address_1'));
				$arr = $LocationModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 'i18n.content', 'direction' => 'asc')));
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = compact('pages', 'count', 'row_count');
									
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'adminLocations.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}

	function update($id=null)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Location', 'I18n'));
				$LocationModel = new LocationModel();
				$I18nModel = new I18nModel();
				
				if (isset($_POST['location_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=7");
					}
				
					$data = $this->geocode(array_merge($_POST, $_POST['i18n'][1]));					
					
					$LocationModel->update(array_merge($_POST, $data));
					$I18nModel->updateI18n($_POST['i18n'], $_POST['id'], 'Location');
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=5");

				} else {
					$arr = $LocationModel->get($id);
					if (count($arr) === 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminLocations&action=index&err=8");
					}
					$arr['i18n'] = $I18nModel->getI18n($arr['id'], 'Location');
					$this->tpl['arr'] = $arr;
					
					Object::import('Model', 'Country');
					$CountryModel = new CountryModel();
					$this->tpl['country_arr'] = $CountryModel->getAll(array('col_name' => 't1.country_title', 'direction' => 'asc'));
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				}
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminLocations.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}