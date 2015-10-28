<?php
/**
 * Util class
 *
 * @package erp
 * @subpackage erp.app.config
 */
class Util
{
/**
 * Redirect browser
 *
 * @param string $url
 * @param int $http_response_code
 * @param bool $exit
 * @return void
 * @access public
 * @static
 */
	function redirect($url, $http_response_code = null, $exit = true)
	{
		//if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		if (strstr($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS'))
		{
			echo '<html><head><title></title><script type="text/javascript">window.location.href="'.$url.'";</script></head><body></body></html>';
		} else {
			$http_response_code = !is_null($http_response_code) && (int) $http_response_code > 0 ? $http_response_code : 303;
			header("Location: $url", true, $http_response_code);
		}
		if ($exit)
		{
	    	exit();
		}
	}
	
	function getFileExtension($str)
    {
    	$arrSegments = explode('.', $str); // may contain multiple dots
        $strExtension = $arrSegments[count($arrSegments) - 1];
        $strExtension = strtolower($strExtension);
        return $strExtension;
    }
/**
 * Print notice
 *
 * @param string $value
 * @return string
 * @access public
 * @static
 */
	function printNotice($value, $escape = true)
	{
		?>
		<div class="notice-box"><?php echo $escape === true ? htmlspecialchars(stripslashes($value)) : $value; ?></div>
		<?php
	}
/**
 * Format currency string
 *
 * @param decimal $price
 * @param string $currency
 * @param string $separator
 * @return string
 * @access public
 * @static
 */
	function formatCurrencySign($price, $currency, $separator = "")
	{
		if (!in_array($currency, array('USD', 'GBP', 'EUR', 'YEN')))
		{
			return $price . $separator . $currency;
		}
		switch ($currency)
		{
			case 'USD':
				$format = "$" . $separator . $price;
				break;
			case 'GBP':
				$format = "&pound;" . $separator . $price;
				break;
			case 'EUR':
				$format = "&euro;" . $separator . $price;
				break;
			case 'YEN':
				$format = "&yen;" . $separator . $price;
				break;
		}
		return $format;
	}
	
	function formatDate($date, $inputFormat, $outputFormat = "Y-m-d")
	{
		$limiters = array('.', '-', '/');
		foreach ($limiters as $limiter)
		{
			if (strpos($inputFormat, $limiter) !== false)
			{
				$_date = explode($limiter, $date);
				$_iFormat = explode($limiter, $inputFormat);
				$_iFormat = array_flip($_iFormat);
				break;
			}
		}
		return date($outputFormat, mktime(0, 0, 0,
			$_date[isset($_iFormat['m']) ? $_iFormat['m'] : $_iFormat['n']],
			$_date[isset($_iFormat['d']) ? $_iFormat['d'] : $_iFormat['j']],
			$_date[$_iFormat['Y']]));
	}
	
	function jqDateFormat($phpFormat)
	{
		$jQuery = array('d', 'dd', 'm', 'mm', 'yy');
		$php = array('j', 'd', 'n', 'm', 'Y');
		$limiters = array('.', '-', '/');
		foreach ($limiters as $limiter)
		{
			if (strpos($phpFormat, $limiter) !== false)
			{
				$_iFormat = explode($limiter, $phpFormat);
				return join($limiter, array(
					$jQuery[array_search($_iFormat[0], $php)],
					$jQuery[array_search($_iFormat[1], $php)],
					$jQuery[array_search($_iFormat[2], $php)]
				));
			}
		}
		return $phpFormat;
	}
	
	function readDir(&$data, $dir)
	{
		$stop = array('.', '..', '.buildpath', '.project', '.svn', 'Thumbs.db');
		if ($handle = opendir($dir))
		{
			$sep = $dir{strlen($dir)-1} != '/' ? '/' : NULL;
			while (false !== ($file = readdir($handle)))
			{
				if (in_array($file, $stop)) continue;
				if (!is_dir($dir . $sep . $file))
				{
					$data[] = $dir . $sep . $file;
				} else {
					Util::readDir($data, $dir . $sep . $file);
				}
			}
			closedir($handle);
		}
	}
	
	function decrypt($str)
	{
		$txt = '';
		$arr = explode("%", $str);
		foreach ($arr as $val)
		{
			if (strlen($val) > 0)
			{
				$txt .= chr(hexdec($val));
			}
		}
		return $txt;
	}
}
?>