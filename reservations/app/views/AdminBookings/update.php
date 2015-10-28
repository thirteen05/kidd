<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			Util::printNotice($CR_LANG['status'][1]);
			break;
		case 2:
			Util::printNotice($CR_LANG['status'][2]);
			break;
		case 9:
			Util::printNotice($CR_LANG['status'][9]);
			break;
	}
} else {
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>" method="post" id="frmUpdateBooking" class="form">
		<input type="hidden" name="booking_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		
		<p><label class="title"><?php echo $CR_LANG['booking_from']; ?></label><input type="text" name="from" id="from" class="text w150 required pointer timepicker" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['from'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_to']; ?></label><input type="text" name="to" id="to" class="text w150 required pointer timepicker" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['to'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_uuid']; ?></label><input type="text" name="uuid" id="uuid" class="text w150 required" value="<?php echo (int) $tpl['arr']['uuid']; ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_type']; ?></label>
			<select name="type_id" id="type_id" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['type_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['type_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['name']); ?></option><?php
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_car']; ?></label>
			<span id="boxCars">
				<select name="car_id" id="car_id" class="select w250 required">
					<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
					<?php
					foreach ($tpl['car_arr'] as $v)
					{
						?><option value="<?php echo $v['car_id']; ?>"<?php echo $tpl['arr']['car_id'] == $v['car_id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['make'] . " " . $v['model'] . " - " . $v['registration_number']); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_extras']; ?></label>
			<span id="boxExtras">
			<?php
			foreach ($tpl['extra_arr'] as $v)
			{
				?><input type="checkbox" name="extra_id[<?php echo $v['extra_id']; ?>]" id="extra_<?php echo $v['extra_id']; ?>" value="<?php echo $v['price']; ?>"<?php echo in_array($v['extra_id'], $tpl['be_arr'])? ' checked="checked"' : NULL; ?> /> 
				<label for="extra_<?php echo $v['extra_id']; ?>"><?php echo stripslashes($v['name']); ?> (<?php echo Util::formatCurrencySign(number_format(floatval($v['price']), 2), $tpl['option_arr']['currency'], " "); ?>)</label>
				<?php
			}
			?>
			</span>
		</p>
		<!--<p><label class="title"><?php echo $CR_LANG['booking_pickup']; ?></label>
			<select name="pickup_id" id="pickup_id" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['location_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['pickup_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['name']); ?></option><?php
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_return']; ?></label>
			<select name="return_id" id="return_id" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['location_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['return_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['name']); ?></option><?php
			}
			?>
			</select>
		</p>-->
		<p><label class="title"><?php echo $CR_LANG['booking_payment_method']; ?></label>
			<select name="payment_method" id="payment_method" class="select w150 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($CR_LANG['_payments'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['payment_method'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
			}
			?>
			</select>
		</p>
		<p class="boxCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>"><label class="title"><?php echo $CR_LANG['front']['4_cc_type']; ?></label>
			<select name="cc_type" class="select w250">
				<option value="">---</option>
				<?php
				foreach ($CR_LANG['front']['4_cc_types'] as $k => $v)
				{
					if (isset($tpl['arr']['cc_type']) && $tpl['arr']['cc_type'] == $k)
					{
						?><option value="<?php echo $k; ?>" selected="selected"><?php echo $v; ?></option><?php
					} else {
						?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
					}
				}
				?>
			</select>
		</p>
		<p class="boxCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>"><label class="title"><?php echo $CR_LANG['front']['4_cc_num']; ?></label><input type="text" name="cc_num" id="cc_num" value="<?php echo htmlspecialchars($tpl['arr']['cc_num']); ?>" class="text w250" /></p>
		<p class="boxCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>"><label class="title"><?php echo $CR_LANG['front']['4_cc_exp']; ?></label>
			<select name="cc_exp_month" class="select">
				<option value="">---</option>
				<?php
				list($year, $month) = explode("-", $tpl['arr']['cc_exp']);
				foreach ($CR_LANG['month_name'] as $key => $val)
				{
					?><option value="<?php echo $key;?>"<?php echo (int) $month == $key ? ' selected="selected"' : NULL; ?>><?php echo $val;?></option><?php
				}
				?>
			</select>
			<select name="cc_exp_year" class="select">
				<option value="">---</option>
				<?php
				$y = (int) date('Y');
				for ($i = $y; $i <= $y + 10; $i++)
				{
					?><option value="<?php echo $i; ?>"<?php echo $year == $i ? ' selected="selected"' : NULL; ?>><?php echo $i; ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="boxCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>"><label class="title"><?php echo $CR_LANG['front']['4_cc_code']; ?></label><input type="text" name="cc_code" id="cc_code" value="<?php echo htmlspecialchars($tpl['arr']['cc_code']); ?>" class="text w100" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_total']; ?></label><input type="text" name="total" id="total" value="<?php echo floatval($tpl['arr']['total']); ?>" class="text w100 number align_right" /> <span class="l10"><?php echo $tpl['option_arr']['currency']; ?></span></p>
		<p><label class="title"><?php echo $CR_LANG['booking_tax']; ?></label><input type="text" name="tax" id="tax" value="<?php echo floatval($tpl['arr']['tax']); ?>" class="text w100 number align_right" /> <span class="l10"><?php echo $tpl['option_arr']['currency']; ?></span></p>
		<p><label class="title"><?php echo $CR_LANG['booking_deposit']; ?></label><input type="text" name="deposit" id="deposit" value="<?php echo floatval($tpl['arr']['deposit']); ?>" class="text w100 number align_right" /> <span class="l10"><?php echo $tpl['option_arr']['currency']; ?></span></p>
		<p><label class="title"><?php echo $CR_LANG['booking_status']; ?></label>
			<select name="status" id="status" class="select w150 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($CR_LANG['booking_statuses'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['status'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
			}
			?>
			</select>
		</p>
		
		<p><label class="title"><?php echo $CR_LANG['booking_title']; ?></label>
			<select name="c_title" id="c_title" class="select w150">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($CR_LANG['_titles'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['c_title'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_fname']; ?></label><input type="text" name="c_fname" id="c_fname" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_fname'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_lname']; ?></label><input type="text" name="c_lname" id="c_lname" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_lname'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_phone']; ?></label><input type="text" name="c_phone" id="c_phone" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_phone'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_email']; ?></label><input type="text" name="c_email" id="c_email" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_email'])); ?>" /></p>
		
		<p><label class="title"><?php echo $CR_LANG['booking_company']; ?></label><input type="text" name="c_company" id="c_company" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_company'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_address_1']; ?></label><input type="text" name="c_address_1" id="c_address_1" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_address_1'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_address_2']; ?></label><input type="text" name="c_address_2" id="c_address_2" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_address_2'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_address_3']; ?></label><input type="text" name="c_address_3" id="c_address_3" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_address_3'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_city']; ?></label><input type="text" name="c_city" id="c_city" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_city'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_state']; ?></label><input type="text" name="c_state" id="c_state" class="text w250" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_state'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_zip']; ?></label><input type="text" name="c_zip" id="c_zip" class="text w100" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_zip'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_country']; ?></label>
			<select name="c_country" id="c_country" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['country_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['c_country'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['country_title']); ?></option><?php
			}
			?>
			</select>
		</p>
		
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>
	</form>
	<?php
}
?>