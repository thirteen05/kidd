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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTypes&amp;action=create" method="post" id="frmCreateType" class="form" enctype="multipart/form-data">
		<input type="hidden" name="type_create" value="1" />
		<p><label class="title"><?php echo $CR_LANG['type_size']; ?></label>
			<select name="size" id="size" class="select w120 required">
				<option value=""><?php echo $CR_LANG['type_choose']; ?></option>
			<?php
			foreach ($CR_LANG['type_sizes'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php
			}
			?>
			</select>
		</p>
		<div class="p"><label class="title"><?php echo $CR_LANG['type_name']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][name]" class="text w300 <?php echo $i > 1 ? NULL : 'required'; ?> float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" />
				<?php
			}
			?>
			<br class="clear_left" />
		</div>
		<div class="p"><label class="title"><?php echo $CR_LANG['type_description']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<textarea name="i18n[<?php echo $i; ?>][description]" class="text w400 h100 float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>"></textarea>
				<?php
			}
			?>
			<br class="clear_left" />
		</div>
		<p><label class="title"><?php echo $CR_LANG['type_image']; ?></label><input type="file" name="image" id="image" class="text w300" /></p>
		<p><label class="title"><?php echo $CR_LANG['type_passengers']; ?></label><input type="text" name="passengers" id="passengers" class="text w100 align_right digits required" /></p>
<!--		<p><label class="title"><?php // echo $CR_LANG['type_luggages']; ?></label><input type="text" name="luggages" id="luggages" class="text w100 align_right digits required" /></p>-->
<!--		<p><label class="title"><?php // echo $CR_LANG['type_doors']; ?></label><input type="text" name="doors" id="doors" class="text w100 align_right digits required" /></p>-->
<!--		<p><label class="title"><?php echo $CR_LANG['type_transmission']; ?></label>
			<select name="transmission" id="transmission" class="select w300 required">
				<option value=""><?php //echo $CR_LANG['type_choose']; ?></option>
			<?php
			//foreach ($CR_LANG['type_transmissions'] as $k => $v)
			//{
				?><option value="<?php //echo $k; ?>"><?php //echo stripslashes($v); ?></option><?php
			//}
			?>
			</select>
		</p>-->
		<p><label class="title"><?php echo $CR_LANG['type_extras']; ?></label>
			<span class="block" style="margin-left: 160px">
			<?php 
			$i = 1;
			$is_open = false;
			foreach ($tpl['extra_arr'] as $extra)
			{
				$is_open = true;
				?><span class="float_left block w200"><input type="checkbox" name="extra_id[]" id="extra_<?php echo $extra['id']; ?>" value="<?php echo $extra['id']; ?>" /> <label for="extra_<?php echo $extra['id']; ?>"><?php echo stripslashes($extra['name']); ?></label></span><?php
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