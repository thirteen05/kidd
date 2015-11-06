<?php
foreach ($tpl['extra_arr'] as $v)
{
	?><input type="checkbox" name="extra_id[<?php echo $v['extra_id']; ?>]" id="extra_<?php echo $v['extra_id']; ?>" value="<?php echo $v['price']; ?>" /> 
	<label for="extra_<?php echo $v['extra_id']; ?>"><?php echo stripslashes($v['name']); ?> (<?php echo Util::formatCurrencySign(number_format(floatval($v['price']), 2), $tpl['option_arr']['currency'], " "); ?>)</label>
	<?php
}
?>