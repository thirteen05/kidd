<?php
require_once FRAMEWORK_PATH . 'Controller.class.php';
class AppController extends Controller
{
/**
 * Model's cache
 *
 * @var array
 * @access protected
 */
	var $models = array();
/**
 * Check loged user against 'owner' role
 *
 * @access public
 * @return bool
 */
	function isOwner()
    {
   		return $this->getRoleId() == 2;
    }
/**
 * Set timezone
 *
 * @param int $timezone
 * @access public
 * @return void
 * @static
 */
    function setTimezone($timezone="UTC")
    {
    	if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
		{
			date_default_timezone_set($timezone);
		} else {
			$safe_mode = ini_get('safe_mode');
			if ($safe_mode)
			{
				putenv("TZ=".$timezone);
			}
		}
    }
/**
 * Set MySQL server time
 *
 * @param int $offset
 * @access public
 * @return void
 * @static
 */
    function setMySQLServerTime($offset="-0:00")
    {
		mysql_query("SET SESSION time_zone = '$offset';");
    }
    
	function getCartPrices($productName, $cartName)
    {
    	Object::import('Model', 'Extra');
		$ExtraModel = new ExtraModel();
		
		$_arr = array();
		if (isset($_SESSION[$productName][$cartName]) && isset($_SESSION[$productName][$cartName]['extras']))
		{
			$_arr = array_keys($_SESSION[$productName][$cartName]['extras']);
		}
		$_arr = array_unique($_arr);
		$e_arr = $ExtraModel->getAll(array('t1.id' => array("('" . join("','", $_arr) . "')", 'IN', 'null')));
		$extra_arr = array();
		foreach ($e_arr as $extra)
		{
			$extra_arr[$extra['id']] = $extra;
		}
		return $extra_arr;
    }
    
	function getCartTotal($productName, $cartName, $option_arr)
    {
    	$arr = AppController::getCartPrices($productName, $cartName);
    	$price = 0;
    	if (isset($_SESSION[$productName][$cartName]) && isset($_SESSION[$productName][$cartName]['extras']))
    	{
    		foreach ($_SESSION[$productName][$cartName]['extras'] as $extra_id => $v)
    		{
				if (isset($arr[$extra_id]) && isset($arr[$extra_id]['price']))
				{
					switch ($arr[$extra_id]['per'])
					{
						case 'day':
							$price += (float) $arr[$extra_id]['price'] * $_SESSION[$productName][$cartName]['rental_days'];
							break;
						case 'booking':
							$price += (float) $arr[$extra_id]['price'];
							break;
					}
				}
    		}
    	}
    	
    	Object::import('Model', 'Price');
    	$PriceModel = new PriceModel();
    	$price_arr = $PriceModel->getAll(array(
    		't1.type_id' => $_SESSION[$productName][$cartName]['type_id'],
    		"t1.id > 0 AND ('".Util::formatDate($_SESSION[$productName][$cartName]['date_from'], $option_arr['date_format'])."'" => array('t1.date_from AND t1.date_to)', 'BETWEEN', 'null'),
    		'offset' => 0, 'row_count' => 1));
    	if (count($price_arr) == 1)
    	{
    		$_price = 'price';
			if($_SESSION[$productName][$cartName]['duration'] == '1_1'){
				$_price = 'price';
			}
			else if($_SESSION[$productName][$cartName]['duration'] == '1_3'){
				$_price = 'price_1_3';
			}
			else if($_SESSION[$productName][$cartName]['duration'] == '1_2'){
				$_price = 'price_1_2';
			}
                        else if($_SESSION[$productName][$cartName]['duration'] == '1_E'){
				$_price = 'price_1_E';
			}
			
			
    		$price += $price_arr[0][$_price];
    	}
    	
    	$tax = ($price * $option_arr['tax']) / 100;
		$total = $price + $tax;
		$deposit = $option_arr['deposit_percent'];

		return array('price' => round($price, 2), 'total' => round($total, 2), 'deposit' => round($deposit, 2), 'tax' => round($tax, 2));
    }
    
    function getWorkingTime($date){
		Object::import('Model', 'Price');
		$PriceModel = new PriceModel();
		
		$opts = array();
		$opts['t1.id > 0  AND ("'.$date.'"'] =  array("t1.date_from AND t1.date_to)", 'BETWEEN', 'null');
		$arr = $PriceModel->getAll(array_merge($opts, array('offset' => 0, 'row_count' => 1)));
		
		return $arr;
		
	}
}
	
?>