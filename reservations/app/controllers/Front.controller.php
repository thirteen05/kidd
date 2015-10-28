<?php
require_once CONTROLLERS_PATH . 'AppController.controller.php';
class Front extends AppController
{
	var $layout = 'front';
	var $default_captcha = 'CarRental_Captcha';
	var $default_order = 'CarRental_Order';
	var $default_language = 'CarRental_Language';
/**
 * Constructor
 */
	function Front()
	{
		
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeFilter()
 * @access public
 * @return void
 */
	function beforeFilter()
	{
		Object::import('Model', 'Option');
		$OptionModel = new OptionModel();
		$this->option_arr = $OptionModel->getPairs();
		$this->tpl['option_arr'] = $this->option_arr;
		
		if (isset($this->tpl['option_arr']['timezone']))
		{
			$offset = $this->option_arr['timezone'] / 3600;
			if ($offset > 0)
			{
				$offset = "-".$offset;
			} elseif ($offset < 0) {
				$offset = "+".abs($offset);
			} elseif ($offset === 0) {
				$offset = "+0";
			}
		
			AppController::setTimezone('Etc/GMT'.$offset);
			if (strpos($offset, '-') !== false)
			{
				$offset = str_replace('-', '+', $offset);
			} elseif (strpos($offset, '+') !== false) {
				$offset = str_replace('+', '-', $offset);
			}
			AppController::setMySQLServerTime($offset . ":00");
		}
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeRender()
 * @access public
 */
	function beforeRender()
	{
		
	}

	function addExtra()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$code = 100;
			if (!isset($_SESSION[$this->default_product][$this->default_order]))
			{
				$_SESSION[$this->default_product][$this->default_order] = array();
			}
			if (!isset($_SESSION[$this->default_product][$this->default_order]['extras']))
			{
				$_SESSION[$this->default_product][$this->default_order]['extras'] = array();
			}
			if (!array_key_exists($_GET['extra_id'], $_SESSION[$this->default_product][$this->default_order]['extras']))
			{
				Object::import('Model', 'Extra');
				$ExtraModel = new ExtraModel();
				$arr = $ExtraModel->get($_GET['extra_id']);
				if (count($arr) > 0)
				{
					$_SESSION[$this->default_product][$this->default_order]['extras'][$_GET['extra_id']] = $arr;
					$code = 200;
				}
			}
			
			header("Content-type: application/json; charset=utf-8");
			echo '{"code":'.$code.'}';
			exit;
		}
	}
	
	function removeExtra()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$code = 100;
			if (isset($_SESSION[$this->default_product][$this->default_order]) && is_array($_SESSION[$this->default_product][$this->default_order]) &&
				isset($_SESSION[$this->default_product][$this->default_order]['extras']) && is_array($_SESSION[$this->default_product][$this->default_order]['extras']) &&
				array_key_exists($_GET['extra_id'], $_SESSION[$this->default_product][$this->default_order]['extras']))
			{
				unset($_SESSION[$this->default_product][$this->default_order]['extras'][$_GET['extra_id']]);
				$code = 200;
			}
			
			header("Content-type: application/json; charset=utf-8");
			echo '{"code":'.$code.'}';
			exit;
		}
	}
	
	function loadSearch()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('Location', 'I18n'));
			$LocationModel = new LocationModel();
			$I18nModel = new I18nModel();
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
			$this->tpl['location_arr'] = $LocationModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
			
			#unset($_SESSION[$this->default_product][$this->default_order]);
			if (!isset($_SESSION[$this->default_product][$this->default_order]))
			{
				$_SESSION[$this->default_product][$this->default_order] = array();
				$_SESSION[$this->default_product][$this->default_order]['hour_from'] = "09";
				$_SESSION[$this->default_product][$this->default_order]['hour_to'] = "09";
				$_SESSION[$this->default_product][$this->default_order]['minutes_from'] = "00";
				$_SESSION[$this->default_product][$this->default_order]['minutes_to'] = "00";
				$_SESSION[$this->default_product][$this->default_order]['date_from'] = date($this->option_arr['date_format'], strtotime("+1 day"));
				$_SESSION[$this->default_product][$this->default_order]['date_to'] = $_SESSION[$this->default_product][$this->default_order]['date_from'];
				$_SESSION[$this->default_product][$this->default_order]['rental_days'] = 1;
			}
		}
	}

	function loadCars()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (isset($_POST['date_from']))
			{
				$df = Util::formatDate($_POST['date_from'], $this->option_arr['date_format']);
				$dt = $df;
				
				#$rental_days = ceil((strtotime($dt . " " . $_POST['hour_to'].":".$_POST['minutes_from']) - strtotime($df. " " . $_POST['hour_from'].":".$_POST['minutes_from'])) / 86400);
				$rental_days = 1;
                                
                                if( isset($_POST['ampm_from']) && $_POST['ampm_from'] == "PM" && $_POST['hour_from'] < 12 ){
                                    $_POST['hour_from'] += 12;
                                }
                                elseif( isset($_POST['ampm_from']) && $_POST['ampm_from'] == "AM" && $_POST['hour_from'] == 12 ){
                                    $_POST['hour_from'] = 0;
                                }
                               
				$_SESSION[$this->default_product][$this->default_order] = array_merge($_POST, compact('rental_days'));
			}
			
			Object::import('Model', array('CarType', 'Type', 'Price', 'Car', 'Booking', 'I18n'));
			$CarTypeModel = new CarTypeModel();
			$TypeModel = new TypeModel();
			$PriceModel = new PriceModel();
			$CarModel = new CarModel();
			$BookingModel = new BookingModel();
			$I18nModel = new I18nModel();
			
			$opts = array();
			if (isset($_GET['size']) && !empty($_GET['size']) && !in_array($_GET['size'], array('all', 'series')))
			{
				$opts['t1.size'] = $_GET['size'];
			}
			if (isset($_GET['transmission']) && !empty($_GET['transmission']))
			{
				$opts['t1.transmission'] = $_GET['transmission'];
			}
			$col_name = 't1.name';
			$direction = 'asc';
			if (isset($_GET['col_name']) && isset($_GET['direction']))
			{
				$col_name = $_GET['col_name'];
				$direction = in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')) ? $_GET['direction'] : 'ASC';
			}
			$col_name = $col_name == 't1.name' ? 'i18n.content' : $col_name;
			
			$_SESSION[$this->default_product][$this->default_order]['minutes_to'] = $_SESSION[$this->default_product][$this->default_order]['minutes_from'];
			$_SESSION[$this->default_product][$this->default_order]['date_to'] = $_SESSION[$this->default_product][$this->default_order]['date_from'];
			
                        
                        // handle input from When/Where form
                        if(isset($_POST['pickup_location']) && !empty($_POST['pickup_location'])){
                             $_SESSION[$this->default_product][$this->default_order]['pickup_id'] = $_POST['pickup_location'];
                        }
                        
			#$TypeModel->debug = 1;
			$TypeModel->addSubQuery($TypeModel->subqueries, sprintf("SELECT COUNT(*) FROM `%1\$s`
				WHERE `type_id` = `t1`.`id` AND `car_id` NOT IN (SELECT `car_id` FROM `%2\$s` WHERE `status` = 'confirmed'
					AND ( ('%3\$s' >= `from` AND '%3\$s' < `to`) OR (('%4\$s'> `from` AND '%4\$s' <= `to`)) ) 
						OR (`from` < '%3\$s' AND `to` > '%4\$s') OR (`from` > '%3\$s' AND `to` < '%4\$s')
					) LIMIT 1",
				$CarTypeModel->getTable(),
				$BookingModel->getTable(),
				Util::formatDate($_SESSION[$this->default_product][$this->default_order]['date_from'], $this->option_arr['date_format']) . " " . $_SESSION[$this->default_product][$this->default_order]['hour_from'] . ":" . $_SESSION[$this->default_product][$this->default_order]['minutes_from'],
				Util::formatDate($_SESSION[$this->default_product][$this->default_order]['date_to'], $this->option_arr['date_format']) . " " . $_SESSION[$this->default_product][$this->default_order]['hour_to'] . ":" . $_SESSION[$this->default_product][$this->default_order]['minutes_to']
				), "cnt_available");
				
				
			$price = 'price';
			if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_1'){
				$price = 'price';
			}
			else if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_2'){
				$price = 'price_1_2';
			}
			else if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_3'){
				$price = 'price_1_3';
			}
                        else if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_E'){
				$price = 'price_1_E';
			}
			
			$TypeModel->addSubQuery($TypeModel->subqueries, sprintf("SELECT `$price` FROM `%1\$s` WHERE `type_id` = `t1`.`id` AND ('%2\$s' BETWEEN `date_from` AND `date_to`) LIMIT 1", $PriceModel->getTable(), Util::formatDate($_SESSION[$this->default_product][$this->default_order]['date_from'], $this->option_arr['date_format']) , $_SESSION[$this->default_product][$this->default_order]['hour_to'] . ":" . $_SESSION[$this->default_product][$this->default_order]['minutes_to'].":00" ), "total_price");
			
			$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
			$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.id', 'i18n_2.model' => "'Type'", 'i18n_2.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_2.field' => "'description'"), array('i18n_2.content.description'));
			$arr = $TypeModel->getAll(array_merge($opts, compact('col_name', 'direction')));
			
			$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_3', array('i18n_3.foreign_id' => 't1.car_id', 'i18n_3.model' => "'Car'", 'i18n_3.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_3.field' => "'make'"), array('i18n_3.content.make'));
			$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_4', array('i18n_4.foreign_id' => 't1.car_id', 'i18n_4.model' => "'Car'", 'i18n_4.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_4.field' => "'model'"), array('i18n_4.content.model'));
			foreach ($arr as $k => $v)
			{
				$arr[$k]['example'] = $CarTypeModel->get(array('type_id' => $v['id']));
			}
			$this->tpl['arr'] = $arr;
		}
	}
	
	function loadExtras()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$_SESSION[$this->default_product][$this->default_order]['type_id'] = $_GET['type_id'];
			
			Object::import('Model', array('Extra', 'TypeExtra', 'Type', 'CarType', 'Car', 'Location', 'Price', 'I18n'));
			$ExtraModel = new ExtraModel();
			$TypeExtraModel = new TypeExtraModel();
			$TypeModel = new TypeModel();
			$CarTypeModel = new CarTypeModel();
			$CarModel = new CarModel();
			$LocationModel = new LocationModel();
			$PriceModel = new PriceModel();
			$I18nModel = new I18nModel();
			
			$TypeExtraModel->addJoin($TypeExtraModel->joins, $ExtraModel->getTable(), 'TE', array('TE.id' => 't1.extra_id'), array('TE.price', 'TE.per'));
			$TypeExtraModel->addJoin($TypeExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
			$this->tpl['arr'] = $TypeExtraModel->getAll(array('t1.type_id' => $_GET['type_id'], 'col_name' => 't1.extra_id', 'direction' => 'asc'));

			//$TypeModel->addSubQuery($TypeModel->subqueries, sprintf("SELECT CONCAT_WS(' ', `make`, `model`) FROM `%1\$s` WHERE `id` = (SELECT `car_id` FROM `%2\$s` WHERE `type_id` = `t1`.`id` ORDER BY RAND() LIMIT 1) LIMIT 1", $CarModel->getTable(), $CarTypeModel->getTable()), "example");
			$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
			$type_arr = $TypeModel->get($_GET['type_id']);
			
			$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_3', array('i18n_3.foreign_id' => 't1.car_id', 'i18n_3.model' => "'Car'", 'i18n_3.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_3.field' => "'make'"), array('i18n_3.content.make'));
			$CarTypeModel->addJoin($CarTypeModel->joins, $I18nModel->getTable(), 'i18n_4', array('i18n_4.foreign_id' => 't1.car_id', 'i18n_4.model' => "'Car'", 'i18n_4.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_4.field' => "'model'"), array('i18n_4.content.model'));
			$type_arr['example'] = $CarTypeModel->get(array('type_id' => $_GET['type_id']));
			$this->tpl['type_arr'] = $type_arr;
			
			$sql = sprintf("SELECT 1,
				(SELECT `content` FROM `%1\$s` WHERE `foreign_id` = '%2\$u' AND `model` = 'Location' AND `locale` = '%3\$u' AND `field` = 'name' LIMIT 1) AS `pickup_location`{RETURN}
				",
				$I18nModel->getTable(), @$_SESSION[$this->default_product][$this->default_order]['pickup_id'], $this->getLanguage());
			if (!isset($_SESSION[$this->default_product][$this->default_order]['same_location']))
			{
				$sql = str_replace('{RETURN}', ", (SELECT `content` FROM `%1\$s` WHERE `foreign_id` = '%2\$u' AND `model` = 'Location' AND `locale` = '%3\$u' AND `field` = 'name' LIMIT 1) AS `return_location`", $sql);
				$sql = sprintf($sql, $I18nModel->getTable(), @$_SESSION[$this->default_product][$this->default_order]['return_id'], $this->getLanguage());
			} else {
				$sql = str_replace('{RETURN}', "", $sql);
			}
			$this->tpl['location_arr'] = $LocationModel->execute($sql);
			
			$price = 'price';
			if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_1'){
				$price = 'price';
			}
			else if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_2'){
				$price = 'price_1_2';
			}
			else if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_3'){
				$price = 'price_1_3';
			}
                        else if($_SESSION[$this->default_product][$this->default_order]['duration'] == '1_E'){
				$price = 'price_1_E';
			}
			
			$this->tpl['price'] = $price;
			$p_opts = array();
			$p_opts['t1.type_id'] = $_GET['type_id'];
			$p_opts["t1.id > 0 AND ('".Util::formatDate($_SESSION[$this->default_product][$this->default_order]['date_from'], $this->option_arr['date_format'])."'"] = array('t1.date_from AND t1.date_to     )', 'BETWEEN', 'null');
			$this->tpl['price_arr'] = $PriceModel->getAll(array_merge($p_opts, array('offset' => 0, 'row_count' => 1)));
		}
	}
	
	function loadCheckout()
	{
		$this->isAjax = true;

		if ($this->isXHR())
		{
                    
			Object::import('Model', array('Country','Price'));
			$CountryModel = new CountryModel();
			$PriceModel = new PriceModel();
                        
                        
                        $opts = AppController::getCartTotal($this->default_product, $this->default_order, $this->option_arr);
			$data = array();
			if ($this->option_arr['payment_disable'] == 'Yes')
			{
				$data['status'] = $this->option_arr['booking_status'];
			} else {
				$data['status'] = $this->option_arr['booking_status']; //$this->option_arr['payment_status']
			}
                        $data['total']   = $opts['total'];
			$data['deposit'] = $opts['deposit'];
			$data['tax']     = $opts['tax'];
                        $this->tpl['price_info'] = $data;
			$this->tpl['country_arr'] = $CountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));
		}
	}
        
	function checkExperience()
        {
            $this->isAjax = true;
            $total_correct = 0;
            
            $q1 = $_GET["q1"];
            $q2 = $_GET["q2"];
            $q3 = $_GET["q3"];
            $q4 = $_GET["q4"];
            $q5 = $_GET["q5"];
            $this->tpl['answers'] = $_GET;
            
            if($q1 == 'c'){$total_correct++;}
            if($q2 == 'a'){$total_correct++;}
            if($q3 == 'a'){$total_correct++;}
            if($q4 == 'a'){$total_correct++;}
            if($q5 == 'b'){$total_correct++;}
            
            $this->tpl['exp'] = "nope";
            if($total_correct >= 4)
            {
                $this->tpl['exp'] = "ok";
                Object::import('Model', 'Country');
                $CountryModel = new CountryModel();

                $this->tpl['country_arr'] = $CountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));   
            }
            
        }
        
	function loadPayment()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('Booking', 'BookingExtra', 'I18n'));
			$BookingModel = new BookingModel();
			$BookingExtraModel = new BookingExtraModel();
			$I18nModel = new I18nModel();
			
			$arr = $BookingModel->get($_POST['id']);
			
			$BookingExtraModel->addJoin($BookingExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
			$arr['extra_arr'] = $BookingExtraModel->getAll(array('t1.booking_id' => $_POST['id']));
			$s_arr = array();
			foreach ($arr['extra_arr'] as $item)
			{
				$s_arr[] = $item['name'];
			}
			$arr['extra_str'] = "";
			if (count($s_arr) > 0)
			{
				$arr['extra_str'] = join(", ", array_map("stripslashes", $s_arr));
			}
                        
                        $arr['payment_mod'] = $_POST['payment_mod'];
			$this->tpl['arr'] = $arr;
		}
	}
/**
 * Save booking via AJAX
 *
 * @access public
 * @return json
 */
	function bookingSave()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$opts = AppController::getCartTotal($this->default_product, $this->default_order, $this->option_arr);
			$data = array();
			if ($this->option_arr['payment_disable'] == 'Yes')
			{
				$data['status'] = $this->option_arr['booking_status'];
			} else {
				$data['status'] = $this->option_arr['booking_status']; //$this->option_arr['payment_status']
			}
			$data['total']   = $opts['total'];
			$data['deposit'] = $opts['deposit'];
			$data['tax']     = $opts['tax'];
			$data['from'] = Util::formatDate($_SESSION[$this->default_product][$this->default_order]['date_from'], $this->option_arr['date_format']) . " " . $_SESSION[$this->default_product][$this->default_order]['hour_from']. ":" .$_SESSION[$this->default_product][$this->default_order]['minutes_from'].":00";
			$data['to'] = Util::formatDate($_SESSION[$this->default_product][$this->default_order]['date_to'], $this->option_arr['date_format']) . " " . $_SESSION[$this->default_product][$this->default_order]['hour_to']. ":" .$_SESSION[$this->default_product][$this->default_order]['minutes_to'].":00";
			$data['uuid'] = time();
			$data['locale_id'] = $this->getLanguage();
			if (isset($_SESSION[$this->default_product][$this->default_order]['same_location']))
			{
				$data['return_id'] = $_SESSION[$this->default_product][$this->default_order]['pickup_id'];
			}
			if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'creditcard')
			{
				$data['cc_exp'] = $_POST['cc_exp_year'] . '-' . $_POST['cc_exp_month'];
			}
			$payment = 'none';
			if (isset($_POST['payment_method']))
			{
				$payment = $_POST['payment_method'];
			}
                        if (isset($_POST['payment_mod']))
			{
				$payment_mod = $_POST['payment_mod'];
			}
			
			Object::import('Model', array('Booking', 'CarType'));
			$BookingModel = new BookingModel();
			$CarTypeModel = new CarTypeModel();
			
			$sql = sprintf("SELECT `car_id` FROM `%1\$s`
				WHERE `type_id` = '%5\$u' AND `car_id` NOT IN (SELECT `car_id` FROM `%2\$s` WHERE `status` = 'confirmed'
					AND ((`from` BETWEEN '%3\$s' AND '%4\$s') OR (`to` BETWEEN '%3\$s' AND '%4\$s'))) LIMIT 1",
				$CarTypeModel->getTable(),
				$BookingModel->getTable(),
				$data['from'],
				$data['to'],
				$_SESSION[$this->default_product][$this->default_order]['type_id']
			);
			$ct_arr = $CarTypeModel->execute($sql);
			if (count($ct_arr) > 0 && isset($ct_arr['car_id']))
			{
				$data['car_id'] = $ct_arr['car_id'];
			}
			
			$booking_id = $BookingModel->save(array_merge($_POST, $_SESSION[$this->default_product][$this->default_order], $data));
			if ($booking_id !== false && (int) $booking_id > 0)
			{
				Object::import('Model', array('BookingExtra', 'Extra', 'Type', 'I18n'));
				$BookingExtraModel = new BookingExtraModel();
				$ExtraModel = new ExtraModel();
				$TypeModel = new TypeModel();
				$I18nModel = new I18nModel();
				if (isset($_SESSION[$this->default_product][$this->default_order]) && isset($_SESSION[$this->default_product][$this->default_order]['extras']))
				{
					$be = array();
					$be['booking_id'] = $booking_id;
					foreach ($_SESSION[$this->default_product][$this->default_order]['extras'] as $extra_id => $price)
					{
						$be['extra_id'] = $extra_id;
						$be['price'] = $price;
						$BookingExtraModel->save($be);
					}
				}
				
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.pickup_id', 'i18n_1.model' => "'Location'", 'i18n_1.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_1.field' => "'name'"), array('i18n_1.content.pickup_location'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.return_id', 'i18n_2.model' => "'Location'", 'i18n_2.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_2.field' => "'name'"), array('i18n_2.content.return_location'));
				$BookingModel->addJoin($BookingModel->joins, $TypeModel->getTable(), 'TT', array('TT.id' => 't1.type_id'), array('TT.size'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.type_id', 'i18n.model' => "'Type'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.type'));
				$booking_arr = $BookingModel->get($booking_id);
				if (count($booking_arr) > 0)
				{
					$BookingExtraModel->addJoin($BookingExtraModel->joins, $ExtraModel->getTable(), 'TE', array('TE.id' => 't1.extra_id'), array('TE.price'));
					$BookingExtraModel->addJoin($BookingExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
					$booking_arr['extra_arr'] = $BookingExtraModel->getAll(array('t1.booking_id' => $booking_arr['id']));
				}
				Front::confirmSend($this->option_arr, $booking_arr, $this->salt, 2);
				
				$_SESSION[$this->default_product][$this->default_order] = array();
				$json = array('code' => 200, 'text' => '', 'booking_id' => $booking_id, 'payment' => $payment, 'payment_mod' => $payment_mod);
			} else {
				$json = array('code' => 100, 'text' => '');
			}
			Object::import('Component', 'Services_JSON');
			$Services_JSON = new Services_JSON();
			header("Content-type: text/json");
			echo $Services_JSON->encode($json);
			exit;
		}
	}
/**
 * Cancel booking
 *
 * @access public
 * @return void
 */
	function cancel()
	{
		$this->layout = 'empty';
		
		Object::import('Model', 'Booking');
		$BookingModel = new BookingModel();
				
		if (isset($_POST['booking_cancel']))
		{
			$arr = $BookingModel->get($_POST['id']);
			if (count($arr) > 0)
			{
				$BookingModel->update(array('status' => 'cancelled'), array("SHA1(CONCAT(`id`, `created`, '".$this->salt."'))" => array("'" . $_POST['hash'] . "'", '=', 'null'), 'limit' => 1));
				Util::redirect($_SERVER['PHP_SELF'] . '?controller=Front&action=cancel&err=200');
			}
		} else {
			if (isset($_GET['hash']) && isset($_GET['id']))
			{
				Object::import('Model', array('Country', 'Type', 'I18n'));
				$CountryModel = new CountryModel();
				$TypeModel = new TypeModel();
				$I18nModel = new I18nModel();
				
				$BookingModel->addJoin($BookingModel->joins, $CountryModel->getTable(), 'TC', array('TC.id' => 't1.c_country'), array('TC.country_title'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.pickup_id', 'i18n_1.model' => "'Location'", 'i18n_1.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_1.field' => "'name'"), array('i18n_1.content.pickup'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.return_id', 'i18n_2.model' => "'Location'", 'i18n_2.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_2.field' => "'name'"), array('i18n_2.content.return'));
				$BookingModel->addJoin($BookingModel->joins, $TypeModel->getTable(), 'TT', array('TT.id' => 't1.type_id'), array('TT.size'));
				$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.type_id', 'i18n.model' => "'Type'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.type'));
				$arr = $BookingModel->get($_GET['id']);
				if (count($arr) == 0)
				{
					$this->tpl['status'] = 2;
				} else {
					if ($arr['status'] == 'cancelled')
					{
						$this->tpl['status'] = 4;
					} else {
						$hash = sha1($arr['id'] . $arr['created'] . $this->salt);
						if ($_GET['hash'] != $hash)
						{
							$this->tpl['status'] = 3;
						} else {
							
							Object::import('Model', array('BookingExtra', 'Extra'));
							$BookingExtraModel = new BookingExtraModel();
							$ExtraModel = new ExtraModel();
							$BookingExtraModel->addJoin($BookingExtraModel->joins, $ExtraModel->getTable(), 'TE', array('TE.id' => 't1.extra_id'), array('TE.price'));
							$BookingExtraModel->addJoin($BookingExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
							$arr['extra_arr'] = $BookingExtraModel->getAll(array('t1.booking_id' => $arr['id']));
							
							$this->tpl['arr'] = $arr;
						}
					}
				}
			} elseif (!isset($_GET['err'])) {
				$this->tpl['status'] = 1;
			}
			$this->css[] = array('file' => 'install.css', 'path' => CSS_PATH);
		}
	}
/**
 * Display captcha
 *
 * @param mixed $renew
 * @access public
 * @return binary
 */
	function captcha($renew=null)
	{
		$this->isAjax = true;
		
		Object::import('Component', 'Captcha');
		$Captcha = new Captcha(WEB_PATH . 'obj/Anorexia.ttf', $this->default_captcha, 6);
		$Captcha->setImage(IMG_PATH . 'button.png');
		$Captcha->init($renew);
	}
/**
 * Check captcha via AJAX
 *
 * @access public
 * @return json
 */
	function checkCaptcha()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->default_product][$this->default_captcha]) && strtoupper($_GET['captcha']) == $_SESSION[$this->default_product][$this->default_captcha])
			{
				$json = "{'code':200,'text':''}";
			} else {
				$json = "{'code':100,'text':''}";
			}
			header("Content-type: text/json");
			echo $json;
		}
	}
	
	function getLocations()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			Object::import('Model', array('Location', 'I18n'));
			$LocationModel = new LocationModel();
			$I18nModel = new I18nModel();
			
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Location'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.name'));
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.id', 'i18n_2.model' => "'Location'", 'i18n_2.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_2.field' => "'city'"), array('i18n_2.content.city'));
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n_3', array('i18n_3.foreign_id' => 't1.id', 'i18n_3.model' => "'Location'", 'i18n_3.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_3.field' => "'state'"), array('i18n_3.content.state'));
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n_4', array('i18n_4.foreign_id' => 't1.id', 'i18n_4.model' => "'Location'", 'i18n_4.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_4.field' => "'address_1'"), array('i18n_4.content.address_1'));
			$LocationModel->addJoin($LocationModel->joins, $I18nModel->getTable(), 'i18n_5', array('i18n_5.foreign_id' => 't1.id', 'i18n_5.model' => "'Location'", 'i18n_5.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_5.field' => "'opening_time'"), array('i18n_5.content.opening_time'));
			$arr = $LocationModel->getAll();
			
			Object::import('Component', 'Services_JSON');
			$Services_JSON = new Services_JSON();
			header("Content-type: application/json");
			echo $Services_JSON->encode($arr);
			exit;
		}
	}
	
	function getTerms()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			Object::import('Model', 'I18n');
			$I18nModel = new I18nModel();
			
			$this->tpl['i18n_arr'] = $I18nModel->getAll(array(
				't1.model' => 'Option',
				't1.locale' => $this->getLanguage(),
				't1.field' => 'terms',
				'offset' => 0,
				'row_count' => 1
			));
		}
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::index()
 */
	function index()
	{
		
	}
/**
 * Init calendar
 *
 * @access public
 * @return void
 */
	function load()
	{
		ob_start();
		header("Content-type: text/javascript");
	}
/**
 * Load payment form
 *
 * @access public
 * @return void
 */
	function paymentForm()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('Booking'));
			$BookingModel = new BookingModel();
			
			$arr = $BookingModel->get($_POST['id']);
			
			$this->tpl['arr'] = $arr;
		}
	}
/**
 * Authorize.NET confirmation: Send email and redirect to "Thank you" page
 *
 * @access public
 * @return void
 */
	function confirmAuthorize()
	{
		$this->isAjax = true;
		
		Object::import('Model', array('Booking', 'BookingExtra', 'Type', 'I18n'));
		$BookingModel = new BookingModel();
		$BookingExtraModel = new BookingExtraModel();
		$TypeModel = new TypeModel();
		$I18nModel = new I18nModel();
		
		$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.pickup_id', 'i18n_1.model' => "'Location'", 'i18n_1.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_1.field' => "'name'"), array('i18n_1.content.pickup_location'));
		$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.return_id', 'i18n_2.model' => "'Location'", 'i18n_2.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_2.field' => "'name'"), array('i18n_2.content.return_location'));
		$BookingModel->addJoin($BookingModel->joins, $TypeModel->getTable(), 'TT', array('TT.id' => 't1.type_id'), array('TT.size'));
		$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.type_id', 'i18n.model' => "'Type'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.type'));
		$booking_arr = $BookingModel->get($_POST['x_invoice_num']);
		if (count($booking_arr) > 0)
		{
			$BookingExtraModel->addJoin($BookingExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
			$booking_arr['extra_arr'] = $BookingExtraModel->getAll(array('t1.booking_id' => $booking_arr['id']));
		}
		if (count($booking_arr) == 0)
		{
			Util::redirect($this->option_arr['thank_you_page']);
		}
		
		if (intval($_POST['x_response_code']) == 1)
		{
			$BookingModel->update(array('id' => $_POST['x_invoice_num'], 'status' => $this->option_arr['payment_status']));
			Front::confirmSend($this->option_arr, $booking_arr, $this->salt, 3);
		}
		Util::redirect($this->option_arr['thank_you_page']);
	}
/**
 * PayPal confirmation: Send email and redirect to "Thank you" page
 * Use as IPN too
 *
 * @access public
 * @return void
 */
	function confirmPaypal()
	{
		$this->isAjax = true;
		
		$url = TEST_MODE ? 'ssl://sandbox.paypal.com' : 'ssl://www.paypal.com';
		$log = '';
		Front::log("\nPayPal - " . date("Y-m-d"));
		
		Object::import('Model', array('Booking', 'Type', 'I18n'));
		$BookingModel = new BookingModel();
		$TypeModel = new TypeModel();
		$I18nModel = new I18nModel();
		
		$invoice = explode("_", $_POST['invoice']);
		$invoice = $invoice[1];
		
		$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_1', array('i18n_1.foreign_id' => 't1.pickup_id', 'i18n_1.model' => "'Location'", 'i18n_1.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_1.field' => "'name'"), array('i18n_1.content.pickup_location'));
		$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n_2', array('i18n_2.foreign_id' => 't1.return_id', 'i18n_2.model' => "'Location'", 'i18n_2.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n_2.field' => "'name'"), array('i18n_2.content.return_location'));
		$BookingModel->addJoin($BookingModel->joins, $TypeModel->getTable(), 'TT', array('TT.id' => 't1.type_id'), array('TT.size'));
		$BookingModel->addJoin($BookingModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.type_id', 'i18n.model' => "'Type'", 'i18n.locale' => sprintf("'%u'", $this->getLanguage()), 'i18n.field' => "'name'"), array('i18n.content.type'));
		$booking_arr = $BookingModel->get($invoice);
		if (count($booking_arr) == 0)
		{
			Front::log("No such booking");
			Util::redirect($this->option_arr['thank_you_page']);
		}
		
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value)
		{
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		Front::log($req);
		
		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Host: www.paypal.com\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen($url, 443, $errno, $errstr, 30);		
		// assign posted variables to local variables
		$invoice = explode("_", $_POST['invoice']);
		$invoice = $invoice[1];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];

		if (!$fp)
		{
			// HTTP ERROR
			Front::log("HTTP error");
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp))
			{
				$res = fgets ($fp, 1024);
				Front::log($res);
				if (strcmp (trim($res), "VERIFIED") == 0)
				{
					Front::log("VERIFIED");
					if ($payment_status == "Completed")
					{
						Front::log("Completed");
						if ($booking_arr['txn_id'] != $txn_id)
						{
							Front::log("TXN_ID is OK");
							if ($receiver_email == $this->option_arr['paypal_address'])
							{
								Front::log("EMAIL address is OK");
								
								// There are no such fields as 'booking_deposit' and 'booking_total'
								//$booking_amount = $this->option_arr['payment_option'] == 'deposit' ? $booking_arr['booking_deposit'] : $booking_arr['booking_total'];
								
								Front::log($booking_arr['deposit'].' '.$booking_arr['total'].' '.$payment_amount.' * '.$payment_currency.' '.$this->option_arr['currency']);
								
								// There is no such field 'payment_option' in this script.
								//$booking_amount = $booking_arr['payment_option'] == 'deposit' ? $booking_arr['booking_deposit'] : $booking_arr['booking_total'];
								//if ($payment_amount == $booking_amount && $payment_currency == $this->option_arr['currency'])
								if ((($payment_amount == $booking_arr['deposit']) || ($payment_amount == $booking_arr['total'])) && ($payment_currency == $this->option_arr['currency']))
								{
									Front::log("AMOUNT is OK, proceed with booking update");
									$BookingModel->update(array('id' => $invoice, 'status' => $this->option_arr['payment_status'], 'txn_id' => $txn_id, 'processed_on' => array('NOW()')));
									
									if (count($booking_arr) > 0)
									{
										Object::import('Model', 'BookingExtra');
										$BookingExtraModel = new BookingExtraModel();
										$BookingExtraModel->addJoin($BookingExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.extra_id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
										$booking_arr['extra_arr'] = $BookingExtraModel->getAll(array('t1.booking_id' => $booking_arr['id']));
									}
									Front::confirmSend($this->option_arr, $booking_arr, $this->salt, 3);
									// No need forredirect, sicne thisis done from Paypal using the URL set for return parameter
									//Util::redirect($this->option_arr['thank_you_page']);
								}
							}
						}
					}
					//Util::redirect($this->option_arr['thank_you_page']);
			    } elseif (strcmp ($res, "INVALID") == 0) {
			    	Front::log("INVALID");
			  		//Util::redirect($this->option_arr['thank_you_page']);
			  	}
			}
			fclose($fp);
		}
	}
/**
 * Write given $content to file
 *
 * @param string $content
 * @param string $filename If omitted use 'payment.log'
 * @access public
 * @return void
 * @static
 */
	function log($content, $filename=null)
	{
		if (TEST_MODE)
		{
			$filename = is_null($filename) ? 'payment.log' : $filename;
			@file_put_contents($filename, $content . "\n", FILE_APPEND|FILE_TEXT);
		}
	}
/**
 * Send email
 *
 * @param array $option_arr
 * @param array $booking_arr
 * @param string $salt
 * @param int $opt
 * @access public
 * @return void
 * @static
 */
	function confirmSend($option_arr, $booking_arr, $salt, $opt)
	{
		if (!in_array((int) $opt, array(2, 3)))
		{
			return false;
		}
		Object::import('Component', 'Email');
		$Email = new Email();

		$country = NULL;
		if (!empty($booking_arr['c_country']))
		{
			Object::import('Model', 'Country');
			$CountryModel = new CountryModel();
			$country_arr = $CountryModel->get($booking_arr['c_country']);
			if (count($country_arr) > 0)
			{
				$country = $country_arr['country_title'];
			}
		}
		
		$row = array();
		foreach ($booking_arr['extra_arr'] as $v)
		{
			$row[] = stripslashes($v['name']);
		}
		$booking_data = count($row) > 0 ? join("\n", $row) : NULL;
		
		$cancelURL = INSTALL_URL . 'index.php?controller=Front&action=cancel&id='.$booking_arr['id'].'&hash='.sha1($booking_arr['id'].$booking_arr['created'].$salt);
		$search = array(
			'{Title}', '{FirstName}', '{LastName}', '{Email}', '{Phone}', '{Country}',
			'{City}', '{State}', '{Zip}', '{Address1}', '{Address2}', '{Address3}',
			'{Company}', '{CCType}', '{CCNum}', '{CCExp}',
			'{CCSec}', '{PaymentMethod}', '{PickupLocation}', '{ReturnLocation}', '{UniqueID}',
			'{DtFrom}', '{DtTo}', '{Type}',
			'{Deposit}', '{Total}', '{Tax}', '{BookingID}', '{Extras}', '{CancelURL}');
		$replace = array(
			$booking_arr['c_title'], $booking_arr['c_fname'], $booking_arr['c_lname'], $booking_arr['c_email'], $booking_arr['c_phone'], $country,
			$booking_arr['c_city'], $booking_arr['c_state'], $booking_arr['c_zip'], $booking_arr['c_address_1'], $booking_arr['c_address_2'], $booking_arr['c_address_3'],
			$booking_arr['c_company'], $booking_arr['cc_type'], $booking_arr['cc_num'], ($booking_arr['payment_method'] == 'creditcard' ? $booking_arr['cc_exp'] : NULL),
			$booking_arr['cc_code'], $booking_arr['payment_method'], $booking_arr['pickup_location'], $booking_arr['return_location'], $booking_arr['uuid'],
			date($option_arr['datetime_format'], strtotime($booking_arr['from'])), date($option_arr['datetime_format'], strtotime($booking_arr['to'])), $booking_arr['type'] ." ". $booking_arr['size'],
			$booking_arr['deposit'] . " " . $option_arr['currency'], $booking_arr['total'] . " " . $option_arr['currency'], $booking_arr['tax'] . " " . $option_arr['currency'], $booking_arr['id'], $booking_data, $cancelURL);
				
			
		Object::import('Model', 'I18n');
		$I18nModel = new I18nModel();
		
		$locale_id = isset($booking_arr['locale_id']) && (int) $booking_arr['locale_id'] > 0 ? (int) $booking_arr['locale_id'] : 1;
		
		# Payment email
		if ($option_arr['email_payment'] == $opt)
		{
			$i18n_m = $I18nModel->getAll(array(
				't1.model' => 'Option', 't1.locale' => $locale_id,
				't1.field' => 'email_payment_message', 'offset' => 0, 'row_count' => 1
			));
			$i18n_s = $I18nModel->getAll(array(
				't1.model' => 'Option', 't1.locale' => $locale_id,
				't1.field' => 'email_payment_subject', 'offset' => 0, 'row_count' => 1
			));
			if (count($i18n_m) === 1 && count($i18n_s) === 1)
			{
				$message = str_replace($search, $replace, $i18n_m[0]['content']);
				# Send to ADMIN
				$Email->send($option_arr['email_address'], $i18n_s[0]['content'], $message, $option_arr['email_address']);
				# Send to CLIENT
				$Email->send($booking_arr['c_email'], $i18n_s[0]['content'], $message, $option_arr['email_address']);
			}
		}
		
		# Confirmation email
		if ($option_arr['email_confirmation'] == $opt)
		{
			$i18n_m = $I18nModel->getAll(array(
				't1.model' => 'Option', 't1.locale' => $locale_id,
				't1.field' => 'email_confirmation_message', 'offset' => 0, 'row_count' => 1
			));
			$i18n_s = $I18nModel->getAll(array(
				't1.model' => 'Option', 't1.locale' => $locale_id,
				't1.field' => 'email_confirmation_subject', 'offset' => 0, 'row_count' => 1
			));
			if (count($i18n_m) === 1 && count($i18n_s) === 1)
			{
				$message = str_replace($search, $replace, $i18n_m[0]['content']);
				# Send to ADMIN
				$Email->send($option_arr['email_address'], $i18n_s[0]['content'], $message, $option_arr['email_address']);
				# Send to CLIENT
				$Email->send($booking_arr['c_email'], $i18n_s[0]['content'], $message, $option_arr['email_address']);
			}
		}
	}
	
	function loadCss()
	{
		$arr = array(
			array('file' => 'calendar.css', 'path' => LIBS_PATH . 'calendarJS/themes/light/'),
			array('file' => 'overlay.css', 'path' => LIBS_PATH . 'overlayJS/themes/light/'),
			array('file' => 'app.css', 'path' => CSS_PATH)
		);
		header("Content-type: text/css");
		foreach ($arr as $item)
		{
			echo str_replace(
				array('../img/', 'url(overlay-'),
				array(IMG_PATH, 'url('.LIBS_PATH.'overlayJS/overlay-'),
				@file_get_contents($item['path'] . $item['file'])) . "\n";
		}
		exit;
	}
	
	function loadJs()
	{
		$arr = array(
			array('file' => 'jabb-0.3.js', 'path' => JS_PATH),
			array('file' => 'calendar.min.js', 'path' => LIBS_PATH . 'calendarJS/'),
			array('file' => 'overlay.min.js', 'path' => LIBS_PATH . 'overlayJS/'),
			array('file' => 'date.js', 'path' => LIBS_PATH . 'datejs/'),
			array('file' => 'js?sensor=false', 'path' => 'http://maps.googleapis.com/maps/api/'),
			array('file' => 'app.js', 'path' => JS_PATH)
		);
		header("Content-type: text/javascript");
		foreach ($arr as $item)
		{
			echo @file_get_contents($item['path'] . $item['file']) . "\n";
		}
		exit;
	}
/**
 * Change current locale
 *
 * @param string $iso
 * @access public
 * @return void
 */
	function setLocale($index)
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (in_array((int) $index, array(1, 2, 3)))
			{
				$_SESSION[$this->default_product][$this->default_language] = $index;
				
				include APP_PATH . 'locale/' . $index . '.php';
				$arr = array(
					'folder' => INSTALL_FOLDER,
					'validation' => array(
						'error_dates' => $CR_LANG['front']['1_v_err_dates'],
						'error_title' => $CR_LANG['front']['4_v_err_title'],
						'error_email' => $CR_LANG['front']['4_v_err_email']
					),
					'message_1' => $CR_LANG['front']['msg_1'],
					'message_2' => $CR_LANG['front']['msg_2'],
					'message_3' => $CR_LANG['front']['msg_3'],
					'message_4' => $CR_LANG['front']['msg_4'],
					'dateFormat' => $this->option_arr['date_format'],
					'dayNames' => $CR_LANG['day_name'],
					'monthNamesFull' => array_values($CR_LANG['month_name']),
					'closeButton' => $CR_LANG['front']['1_close']
				);
				
				Object::import('Component', 'Services_JSON');
				$Services_JSON = new Services_JSON();
				header("Content-Type: application/json; charset=utf-8");
				echo $Services_JSON->encode($arr);
				exit;
			}
		}
	}
	
	function getWorkingTime()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			$this->tpl['duration'] = $_REQUEST['duration'];
			$this->tpl['arr'] = AppController::getWorkingTime(Util::formatDate($_POST['date_from'], $this->option_arr['date_format']));
		}
	}
	
	function getHourTo(){
		$this->isAjax = true;
		if ($this->isXHR())
		{
			$this->tpl['duration'] = $_REQUEST['duration'];
			$arr = AppController::getWorkingTime(Util::formatDate($_POST['date_from'], $this->option_arr['date_format']));
			
			$start = 0;
			$end = 23;
			$length = 8;
			if(count($arr)){
				$time_from = explode(":",$arr[0]['time_from']);
				$start = $time_from[0];
				
				$time_to = explode(":",$arr[0]['time_to']);
				$end = $time_to[0];
				
                                if($_REQUEST['duration'] == '1_1'){
					$length = 8;
				}
				if($_REQUEST['duration'] == '1_2'){
					$length = $arr[0]['length_1_2'];
                                        $length = 4;
				}
				else if($_REQUEST['duration'] == '1_3'){
					$length = $arr[0]['length_1_3'];
                                        $length = 6;
				}
                                else if($_REQUEST['duration'] == '1_E'){
					$length = $arr[0]['length_1_E'];
                                        $length = 12;
				}
				
			}
			
			$hour_to = $length + $_REQUEST['hour_from'];
//			if($_REQUEST['duration'] == '1_1'){
//				$hf = $start;
//				$mt = null;
//				$hour_to = $end;
//			}

			echo  $hour_to;
		}
		exit();
	}
}