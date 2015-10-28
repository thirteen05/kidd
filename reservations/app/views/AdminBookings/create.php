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
	}
} else {
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=create" method="post" id="frmCreateBooking" class="form">
		<input type="hidden" name="booking_create" value="1" />
		
		<p><label class="title"><?php echo $CR_LANG['booking_from']; ?></label><input type="text" name="from" id="from" class="text w150 required pointer timepicker" readonly="readonly" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_to']; ?></label><input type="text" name="to" id="to" class="text w150 required pointer timepicker" readonly="readonly" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_type']; ?></label>
			<select name="type_id" id="type_id" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['type_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_car']; ?></label>
			<span id="boxCars">
				<select name="car_id" id="car_id" class="select w250 required">
					<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
				</select>
			</span>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_extras']; ?></label>
			<span id="boxExtras"><?php echo $CR_LANG['booking_extras_note']; ?></span>
		</p>
		<!--<p><label class="title"><?php echo $CR_LANG['booking_pickup']; ?></label>
			<select name="pickup_id" id="pickup_id" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['location_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
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
				?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
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
				?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
			}
			?>
			</select>
		</p>
		<p class="boxCC" style="display: none"><label class="title"><?php echo $CR_LANG['front']['4_cc_type']; ?></label>
			<select name="cc_type" class="select w250">
				<option value="">---</option>
				<?php
				foreach ($CR_LANG['front']['4_cc_types'] as $k => $v)
				{
					?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="boxCC" style="display: none"><label class="title"><?php echo $CR_LANG['front']['4_cc_num']; ?></label><input type="text" name="cc_num" id="cc_num" class="text w250" /></p>
		<p class="boxCC" style="display: none"><label class="title"><?php echo $CR_LANG['front']['4_cc_exp']; ?></label>
			<select name="cc_exp_month" class="select">
				<option value="">---</option>
				<?php
				foreach ($CR_LANG['month_name'] as $key => $val)
				{
					?><option value="<?php echo $key;?>"><?php echo $val;?></option><?php
				}
				?>
			</select>
			<select name="cc_exp_year" class="select">
				<option value="">---</option>
				<?php
				$y = (int) date('Y');
				for ($i = $y; $i <= $y + 10; $i++)
				{
					?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="boxCC" style="display: none"><label class="title"><?php echo $CR_LANG['front']['4_cc_code']; ?></label><input type="text" name="cc_code" id="cc_code" class="text w100" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_total']; ?></label><input type="text" name="total" id="total" class="text w100 number align_right" /> <span class="l10"><?php echo $tpl['option_arr']['currency']; ?></span></p>
		<p><label class="title"><?php echo $CR_LANG['booking_tax']; ?></label><input type="text" name="tax" id="tax" class="text w100 number align_right" /> <span class="l10"><?php echo $tpl['option_arr']['currency']; ?></span></p>
		<p><label class="title"><?php echo $CR_LANG['booking_deposit']; ?></label><input type="text" name="deposit" id="deposit" class="text w100 number align_right" /> <span class="l10"><?php echo $tpl['option_arr']['currency']; ?></span></p>
		<p><label class="title"><?php echo $CR_LANG['booking_status']; ?></label>
			<select name="status" id="status" class="select w150 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($CR_LANG['booking_statuses'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
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
				?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['booking_fname']; ?></label><input type="text" name="c_fname" id="c_fname" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_lname']; ?></label><input type="text" name="c_lname" id="c_lname" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_phone']; ?></label><input type="text" name="c_phone" id="c_phone" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_email']; ?></label><input type="text" name="c_email" id="c_email" class="text w250" /></p>
		
		<p><label class="title"><?php echo $CR_LANG['booking_company']; ?></label><input type="text" name="c_company" id="c_company" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_address_1']; ?></label><input type="text" name="c_address_1" id="c_address_1" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_address_2']; ?></label><input type="text" name="c_address_2" id="c_address_2" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_address_3']; ?></label><input type="text" name="c_address_3" id="c_address_3" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_city']; ?></label><input type="text" name="c_city" id="c_city" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_state']; ?></label><input type="text" name="c_state" id="c_state" class="text w250" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_zip']; ?></label><input type="text" name="c_zip" id="c_zip" class="text w100" /></p>
		<p><label class="title"><?php echo $CR_LANG['booking_country']; ?></label>
			<select name="c_country" id="c_country" class="select w250 required">
				<option value=""><?php echo $CR_LANG['booking_choose']; ?></option>
			<?php
			foreach ($tpl['country_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['country_title']); ?></option><?php
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