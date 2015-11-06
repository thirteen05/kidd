<select name="car_id" id="car_id" class="select w250 required">
	<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
	<?php
	foreach ($tpl['car_arr'] as $v)
	{
		?><option value="<?php echo $v['car_id']; ?>"><?php echo stripslashes($v['make'] . " " . $v['model'] . " - " . $v['registration_number']); ?></option><?php
	}
	?>
</select>