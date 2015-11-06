<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminBookings extends Admin
{
	function availability()
	{
		$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
		$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
		$this->js[] = array('file' => 'adminBookings.js', 'path' => JS_PATH);
	}
	
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$err = NULL;
				if (isset($_POST['booking_create']))
				{
					if ($this->isDemo())
					{
						$err = 7;
					} else {
					
						Object::import('Model', array('Booking', 'BookingExtra'));
						$BookingModel = new BookingModel();
						$data = array();
						if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'creditcard')
						{
							$data['cc_exp'] = $_POST['cc_exp_year'] . '-' . $_POST['cc_exp_month'];
						}
						$data['uuid'] = time();
						$id = $BookingModel->save(array_merge($_POST, $data));
						
						if ($id !== false && (int) $id > 0)
						{
							$BookingExtraModel = new BookingExtraModel();
							if (isset($_POST['extra_id']) && count($_POST['extra_id']) > 0)
							{
								$data = array();
								$data['booking_id'] = $id;
								foreach ($_POST['extra_id'] as $k => $v)
								{
									$data['extra_id'] = $k;
									$data['price'] = $v;
									$BookingExtraModel->save($data);
								}
							}
							$err = 1;
						} else {
							$err = 2;
						}
					}
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=$err");
				} else {
					Object::import('Model', array('Country', 'Location', 'Type', 'I18n'));
					$CountryModel = new CountryModel();
					$LocationModel = new LocationModel();
					$TypeModel = new TypeModel();
					$I18nModel = new I18nModel();
					$this->tpl['country_arr'] = $CountryModel->getAll(array('col_name' => 't1.country_title', 'direction' => 'asc'));
					$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['location_arr'] = $LocationModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
					$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['type_arr'] = $TypeModel->getAll(array('col_name' => 't1.size', 'direction' => 'asc'));
					
					# Timepicker
					$this->js[] = array('file' => 'jquery.ui.mouse.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.slider.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery-ui-timepicker-addon.min.js', 'path' => LIBS_PATH . 'jquery/plugins/timepicker/');
					
					$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					$this->css[] = array('file' => 'jquery.ui.slider.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					
					#$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					#$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'adminBookings.js', 'path' => JS_PATH);
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
				
				Object::import('Model', 'Booking');
				$BookingModel = new BookingModel();
					
				$arr = $BookingModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 1;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=8");
					}
				}
				
				if ($BookingModel->delete($id))
				{
					Object::import('Model', 'BookingExtra');
					$BookingExtraModel = new BookingExtraModel();
					$BookingExtraModel->delete(array('booking_id' => $id));
					
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=4");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function getAvailability()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			$date = Util::formatDate($_GET['date'], $this->option_arr['date_format']);
			
			Object::import('Model', array('Location', 'Car', 'Booking', 'I18n'));
			$LocationModel = new LocationModel();
			$CarModel = new CarModel();
			$BookingModel = new BookingModel();
			$I18nModel = new I18nModel();
			
			#$BookingModel->debug=1;
			$BookingModel->addJoin($BookingModel->joins, $CarModel->getTable(), 'TC', array('TC.id' => 't1.car_id'), array('TC.registration_number'));
			$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.car_id', 'i18n_1.model' => "'Car'", 'i18n_1.locale' => "'1'", 'i18n_1.field' => "'make'"), array('i18n_1.content.make'));
			$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.car_id', 'i18n_2.model' => "'Car'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'model'"), array('i18n_2.content.model'));
			
			$CarModel->addJoin($CarModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.id', 'i18n_1.model' => "'Car'", 'i18n_1.locale' => "'1'", 'i18n_1.field' => "'make'"), array('i18n_1.content.make'));
			$CarModel->addJoin($CarModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.id', 'i18n_2.model' => "'Car'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'model'"), array('i18n_2.content.model'));
			
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));			
			$location_arr = $LocationModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
			$ids = array();
			foreach ($location_arr as $k => $location)
			{
				$location_arr[$k]['booking_arr'] = $BookingModel->getAll(array(
					't1.pickup_id' => $location['id'],
					't1.status' => 'confirmed',
					"t1.id > 0 AND ('$date'" => array("DATE(t1.from) AND DATE(t1.to))", 'BETWEEN', 'null'), 
					'col_name' => 't1.from DESC, t1.to', 'direction' => 'desc'));
				foreach ($location_arr[$k]['booking_arr'] as $booking)
				{
					$ids[] = $booking['car_id'];
				}
			}
			foreach ($location_arr as $k => $location)
			{
				$aopt = array();
				$aopt = count($ids) > 0 ? array('t1.id' => array("(".join(",", $ids).")", 'NOT IN', 'null')) : array();
				$location_arr[$k]['available_arr'] = $CarModel->getAll(array_merge($aopt, array(
					't1.location_id' => $location['id'],
					'col_name' => 'i18n_1.content ASC, i18n_2.content', 'direction' => 'asc')));
			}
			$this->tpl['location_arr'] = $location_arr;
		}
	}
	
	function getCars()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			$arr = array();
			if ((int) $_GET['type_id'] > 0)
			{
				Object::import('Model', array('Car', 'CarType', 'I18n'));
				$CarModel = new CarModel();
				$CarTypeModel = new CarTypeModel();
				$I18nModel = new I18nModel();
				
				$CarTypeModel->addJoin($CarTypeModel->joins, $CarModel->getTable(), 'TC', array('TC.id' => 't1.car_id'), array('TC.registration_number'));
				$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.car_id', 'i18n_1.model' => "'Car'", 'i18n_1.locale' => "'1'", 'i18n_1.field' => "'make'"), array('i18n_1.content.make'));
				$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.car_id', 'i18n_2.model' => "'Car'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'model'"), array('i18n_2.content.model'));
				$arr = $CarTypeModel->getAll(array('t1.type_id' => $_GET['type_id'], 'col_name' => 'i18n_1.content ASC, i18n_2.content', 'direction' => 'asc'));
			}
			$this->tpl['car_arr'] = $arr;
		}
	}
	
	function getExtras()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			Object::import('Model', array('Extra', 'TypeExtra', 'I18n'));
			$TypeExtraModel = new TypeExtraModel();
			$ExtraModel = new ExtraModel();
			$I18nModel = new I18nModel();
			$arr = array();
			if ((int) $_GET['type_id'] > 0)
			{
				$TypeExtraModel->addJoin($TypeExtraModel->joins, $ExtraModel->getTable(), 'TE', array('TE.id' => 't1.extra_id'), array('TE.price', 'TE.per', 'TE.count'), 'inner');
				$TypeExtraModel->addJoin($TypeExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'), 'inner');
				$arr = $TypeExtraModel->getAll(array('t1.type_id' => $_GET['type_id'], 'col_name' => 'i18n.content', 'direction' => 'asc'));
			}
			$this->tpl['extra_arr'] = $arr;
		}
	}
	
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Booking', 'Car', 'Type', 'I18n', 'Location'));
				$BookingModel = new BookingModel();
				$CarModel = new CarModel();
				$TypeModel = new TypeModel();
				$I18nModel = new I18nModel();
				
                                $sql = "SELECT locations.id AS id, trans.content AS name
                                        FROM car_rental_locations AS locations
                                        JOIN (SELECT * FROM car_rental_i18n WHERE model = 'Location' AND field = 'name' AND locale = 1) AS trans 
                                                ON trans.foreign_id = locations.id";
                                $result = mysql_query($sql);
                                $locations_list = array();
                                while($row = mysql_fetch_assoc($result))
                                {
                                    $locations_list[$row['id']] = $row['name'];
                                }
                                $this->tpl['locations'] = $locations_list;
                                
				$opts = array();
				if (isset($_GET['car_id']) && (int) $_GET['car_id'] > 0)
				{
					$opts['t1.car_id'] = $_GET['car_id'];
				}
				if (isset($_GET['p_date']) && !empty($_GET['p_date']))
				{
					$p_date = Object::escapeString(Util::formatDate($_GET['p_date'], $this->option_arr['date_format']));
					$opts['t1.id > 0 AND (DATE(t1.from)'] = array("'$p_date' OR DATE(t1.to) = '$p_date')", '=', 'null');
				}
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $BookingModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				//$BookingModel->addSubQuery($BookingModel->subqueries, sprintf("SELECT COUNT(*) FROM `%s` WHERE `booking_id` = `t1`.`id` LIMIT 1", $CarModel->getTable()), 'num');
				$BookingModel->addJoin($BookingModel->joins, $TypeModel->getTable(), 'TT', array('TT.id' => 't1.type_id'), array('TT.size'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.type_id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.type_title'));
				$BookingModel->addJoin($BookingModel->joins, $CarModel->getTable(), 'TC', array('TC.id' => 't1.car_id'), array('TC.registration_number'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.car_id', 'i18n_2.model' => "'Car'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'make'"), array('i18n_2.content.make'));
				$arr = $BookingModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.created', 'direction' => 'desc')));
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = compact('pages', 'count', 'row_count');
									
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					
				$this->js[] = array('file' => 'adminBookings.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}

	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Booking', 'BookingExtra'));
				$BookingModel = new BookingModel();
				$BookingExtraModel = new BookingExtraModel();
				
				if (isset($_POST['booking_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=7");
					}
					$data = array();
					if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'creditcard')
					{
						$data['cc_exp'] = $_POST['cc_exp_year'] . '-' . $_POST['cc_exp_month'];
					}
					$BookingModel->update(array_merge($_POST, $data));
					
					$BookingExtraModel->delete(array('booking_id' => $_POST['id']));
					if (isset($_POST['extra_id']) && count($_POST['extra_id']) > 0)
					{
						$data = array();
						$data['booking_id'] = $_POST['id'];
						foreach ($_POST['extra_id'] as $k => $v)
						{
							$data['extra_id'] = $k;
							$data['price'] = $v;
							$BookingExtraModel->save($data);
						}
					}
					
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=5");

				} else {
					$arr = $BookingModel->get($_GET['id']);
					if (count($arr) === 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=8");
					}
					$this->tpl['arr'] = $arr;
					
					Object::import('Model', array('Country', 'Location', 'Type', 'I18n'));
					$CountryModel = new CountryModel();
					$LocationModel = new LocationModel();
					$TypeModel = new TypeModel();
					$I18nModel = new I18nModel();
					$this->tpl['country_arr'] = $CountryModel->getAll(array('col_name' => 't1.country_title', 'direction' => 'asc'));
					$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['location_arr'] = $LocationModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
					$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['type_arr'] = $TypeModel->getAll(array('col_name' => 't1.size', 'direction' => 'asc'));
					
					$car_arr = array();
					if ((int) $arr['type_id'] > 0)
					{
						Object::import('Model', array('Car', 'CarType'));
						$CarModel = new CarModel();
						$CarTypeModel = new CarTypeModel();
						
						$CarTypeModel->addJoin($CarTypeModel->joins, $CarModel->getTable(), 'TC', array('TC.id' => 't1.car_id'), array('TC.registration_number'));
						$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.car_id', 'i18n_1.model' => "'Car'", 'i18n_1.locale' => "'1'", 'i18n_1.field' => "'make'"), array('i18n_1.content.make'));
						$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.car_id', 'i18n_2.model' => "'Car'", 'i18n_2.locale' => "'1'", 'i18n_2.field' => "'model'"), array('i18n_2.content.model'));
						$car_arr = $CarTypeModel->getAll(array('t1.type_id' => $arr['type_id'], 'col_name' => 'i18n_1.content ASC, i18n_2.content', 'direction' => 'asc'));
					}
					$this->tpl['car_arr'] = $car_arr;
					
					Object::import('Model', array('Extra', 'TypeExtra'));
					$TypeExtraModel = new TypeExtraModel();
					$ExtraModel = new ExtraModel();
					
					$TypeExtraModel->addJoin($TypeExtraModel->joins, $ExtraModel->getTable(), 'TE', array('TE.id' => 't1.extra_id'), array('TE.price', 'TE.per', 'TE.count'), 'inner');
					$TypeExtraModel->addJoin($TypeExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'), 'inner');
					$this->tpl['extra_arr'] = $TypeExtraModel->getAll(array('t1.type_id' => $arr['type_id'], 'col_name' => 'i18n.content', 'direction' => 'asc'));
					$this->tpl['be_arr'] = Object::getLinked($BookingExtraModel->getTable(), 'booking_id', 'extra_id', $arr['id']);
					
					# Timepicker
					$this->js[] = array('file' => 'jquery.ui.mouse.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.slider.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery-ui-timepicker-addon.min.js', 'path' => LIBS_PATH . 'jquery/plugins/timepicker/');
					
					$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					$this->css[] = array('file' => 'jquery.ui.slider.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					
					#$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					#$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'adminBookings.js', 'path' => JS_PATH);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}