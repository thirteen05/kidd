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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminLocations&amp;action=create" method="post" id="frmCreateLocation" class="form">
		<input type="hidden" name="location_create" value="1" />
		<div class="p"><label class="title"><?php echo $CR_LANG['location_name']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][name]" class="text w250 <?php echo $i > 1 ? NULL : 'required'; ?> float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<p><label class="title"><?php echo $CR_LANG['location_country']; ?></label>
			<select name="country_id" id="country_id" class="select w250 required">
				<option value=""><?php echo $CR_LANG['location_choose']; ?></option>
			<?php
			foreach ($tpl['country_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['country_title']); ?></option><?php
			}
			?>
			</select>
		</p>
		<div class="p"><label class="title"><?php echo $CR_LANG['location_state']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][state]" class="text w250 float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<div class="p"><label class="title"><?php echo $CR_LANG['location_city']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][city]" class="text w250 float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<div class="p"><label class="title"><?php echo $CR_LANG['location_address_1']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][address_1]" class="text w250 float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<div class="p"><label class="title"><?php echo $CR_LANG['location_address_2']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][address_2]" class="text w250 float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<p><label class="title"><?php echo $CR_LANG['location_zip']; ?></label><input type="text" name="zip" id="zip" class="text w100" /></p>
		<p><label class="title"><?php echo $CR_LANG['location_email']; ?></label><input type="text" name="email" id="email" class="text w250 email" /></p>
		<p><label class="title"><?php echo $CR_LANG['location_phone']; ?></label><input type="text" name="phone" id="phone" class="text w250" /></p>
		<div class="p"><label class="title"><?php echo $CR_LANG['location_opening_time']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<textarea name="i18n[<?php echo $i; ?>][opening_time]" class="textarea w450 h100 float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>"></textarea>
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>
	</form>
	<?php
}
?>