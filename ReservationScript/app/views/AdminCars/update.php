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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCars&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>" method="post" id="frmUpdateCar" class="form">
		<input type="hidden" name="car_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<div class="p"><label class="title"><?php echo $CR_LANG['car_make']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][make]" class="text w300 <?php echo $i > 1 ? NULL : 'required'; ?> float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$i]['make'])); ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php //  echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<div class="p"><label class="title"><?php echo $CR_LANG['car_model']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][model]" class="text w300 <?php echo $i > 1 ? NULL : 'required'; ?> float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$i]['model'])); ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php //  echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<p><label class="title"><?php echo $CR_LANG['car_reg']; ?></label><input type="text" name="registration_number" id="registration_number" class="text w300 required" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['registration_number'])); ?>" /></p>
		<p><label class="title"><?php echo $CR_LANG['car_location']; ?></label>
			<select name="location_id" id="location_id" class="select w300 required">
				<option value=""><?php echo $CR_LANG['car_choose']; ?></option>
			<?php
			foreach ($tpl['location_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['location_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['name']); ?></option><?php
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['car_type']; ?></label>
			<span class="block" style="margin-left: 160px">
			<?php 
			$i = 1;
			foreach ($tpl['type_arr'] as $type)
			{
				$is_open = true;
				?><span class="float_left block w200"><input type="checkbox" name="type_id[]" id="type_<?php echo $type['id']; ?>" value="<?php echo $type['id']; ?>"<?php echo in_array($type['id'], $tpl['linked_types']) ? ' checked="checked"' : NULL; ?> /> <label for="type_<?php echo $type['id']; ?>"><?php echo stripslashes(@$CR_LANG['type_sizes'][$type['size']] ." - ". $type['name']); ?></label></span><?php
				if ($i % 3 === 0)
				{
					$is_open = false;
					?><span class="clear_left block"></span><?php
				}
				$i++;
			}
			if ($is_open) {
				?><span class="clear_left block"></span><?php
			}
			?>
			</span>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>
	</form>
	<?php
}
?>