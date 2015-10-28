<?php include_once VIEWS_PATH . 'Helpers/time.widget.php'; ?>
<?php 
$jqDateFormat = Util::jqDateFormat($tpl['option_arr']['date_format']);
?>
<table class="table" cellpadding="0" cellspacing="0" id="tblPrices">
	<thead>
		<tr>
			<th><?php echo $CR_LANG['price_type']; ?></th>
			<th><?php echo $CR_LANG['date_from']; ?></th>
			<th><?php echo $CR_LANG['date_to']; ?></th>
<!--			<th><?php //echo $CR_LANG['hour_from']; ?></th>
			<th><?php //echo $CR_LANG['hour_to']; ?></th>-->
			
<!--                        <th><?php //echo $CR_LANG['length_1_3']; ?></th>
			<th><?php //echo $CR_LANG['length_1_2']; ?></th>-->
                        
                        <th><?php echo $CR_LANG['price_1_4_day']; ?></th>
                        <th><?php echo $CR_LANG['price_1_2_day']; ?></th>
                        <th><?php echo $CR_LANG['price_1_3_day']; ?></th>

			<th><?php echo $CR_LANG['price_per_day']; ?></th>       
                        <th><?php echo $CR_LANG['price_1_E_day']; ?></th>
			<th style="width: 2%">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($tpl['price_arr'] as $price)
	{
		?>
		<tr>
			<td>
				<select name="type_id[]" class="select w200">
				<?php
				foreach ($tpl['type_arr'] as $type)
				{
					?><option value="<?php echo $type['id']; ?>"<?php echo $price['type_id'] == $type['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes(@$CR_LANG['type_sizes'][$type['size']] . " - " . $type['name']); ?></option><?php
				}
				?>
				</select>
			</td>
			<td><input type="text" name="date_from[]" class="text w80 pointer datepick" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" value="<?php echo date($tpl['option_arr']['date_format'], strtotime($price['date_from'])); ?>"/></td>
			<td><input type="text" name="date_to[]" class="text w80 pointer datepick" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" value="<?php echo date($tpl['option_arr']['date_format'], strtotime($price['date_to'])); ?>"/></td>
			
			<?php 
			list($sh, $sm,) = explode(":", $price['time_from']);
			list($eh, $em,) = explode(":", $price['time_to']);
			?>
				
<!--			<td >
				<?php // TimeWidget::hour((int) $sh, 'time_from[]', 'time_from', 'select w60'); ?>
			</td>
			<td >
				<?php // TimeWidget::hour((int) $eh, 'time_to[]', 'time_to', 'select w60'); ?>
			</td>-->
			
<!--			<td><input type="text" name="length_1_2[]" class="text w20 align_right" value="<?php echo floatval($price['length_1_2']); ?>" /></td>
			<td><input type="text" name="length_1_3[]" class="text w20 align_right" value="<?php echo floatval($price['length_1_3']); ?>" /></td>
			-->
			<td><input type="text" name="price_1_4[]" class="text w40 align_right" value="<?php echo floatval($price['price_1_4']); ?>" /></td>
                        <td><input type="text" name="price_1_2[]" class="text w40 align_right" value="<?php echo floatval($price['price_1_2']); ?>" /></td>
			<td><input type="text" name="price_1_3[]" class="text w40 align_right" value="<?php echo floatval($price['price_1_3']); ?>" /></td>
                        
                        <td><input type="text" name="price[]" class="text w40 align_right" value="<?php echo floatval($price['price']); ?>" /></td>
                        <td><input type="text" name="price_1_E[]" class="text w40 align_right" value="<?php echo floatval($price['price_1_E']); ?>" /></td>
			<td><a class="icon icon-delete" href="">&nbsp;</a></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>

<p class="t10">
	<input type="button" value="" class="button button_add_price" id="btnAddPrice" />
	<input type="submit" value="" class="button button_save" />
	<span class="bxStatus bxStatusStart" style="display: none"><?php echo $CR_LANG['price_status_start']; ?></span>
	<span class="bxStatus bxStatusEnd" style="display: none"><?php echo $CR_LANG['price_status_end']; ?></span>
</p>

<table style="display: none" id="tblClone">
	<tr>
		<td >
			<select name="type_id[]" class="select w200">
			<?php
			foreach ($tpl['type_arr'] as $type)
			{
				?><option value="<?php echo $type['id']; ?>"><?php echo stripslashes(@$CR_LANG['type_sizes'][$type['size']] . " - " . $type['name']); ?></option><?php
			}
			?>
			</select>
		</td>
		<td ><input type="text" name="date_from[]" class="text w80 pointer datepick" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" /></td>
		<td ><input type="text" name="date_to[]" class="text w80 pointer datepick" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" /></td>
<!--		<td >
			<?php// TimeWidget::hour('', 'time_from[]', 'time_from', 'select w60'); ?>
		</td>
		<td >
			<?php //TimeWidget::hour('', 'time_to[]', 'time_to', 'select w60'); ?>
		</td>-->
<!--		<td><input type="text" name="length_1_3[]" class="text w20 align_right"  /></td>
		<td><input type="text" name="length_1_2[]" class="text w20 align_right" /></td>-->
                
                <td ><input type="text" name="price_1_4[]" class="text w40 align_right" /></td>
		<td ><input type="text" name="price_1_2[]" class="text w40 align_right" /></td>
                <td ><input type="text" name="price_1_3[]" class="text w40 align_right" /></td>
		<td ><input type="text" name="price[]" class="text w40 align_right" /></td>
                <td ><input type="text" name="price_1_E[]" class="text w40 align_right" /></td>
		<td ><a class="icon icon-delete" href="">&nbsp;</a></td>
	</tr>
</table>