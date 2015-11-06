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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminExtras&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>" method="post" id="frmUpdateExtra" class="form">
		<input type="hidden" name="extra_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
	
		<div class="p"><label class="title"><?php echo $CR_LANG['extra_title']; ?></label>
			<?php 
			foreach (range(1, $CR_LANG['i18n_locales']) as $i)
			{
				?>
				<input type="text" name="i18n[<?php echo $i; ?>][name]" class="text w300 <?php echo $i > 1 ? NULL : 'required'; ?> float_left" style="display: <?php echo $i > 1 ? 'none' : NULL; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$i]['name'])); ?>" />
				<?php
			}
			?>
<!--			<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
			<br class="clear_left" />
		</div>
		<p><label class="title"><?php echo $CR_LANG['extra_price']; ?></label>
			<input type="text" name="price" id="price" class="text w100 align_right number" value="<?php echo floatval($tpl['arr']['price']); ?>" />
			<select name="per" id="per" class="select w150 l20">
			<?php
			foreach ($CR_LANG['extra_per'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['per'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php 
			}
			?>
			</select>
		</p>
		<p><label class="title"><?php echo $CR_LANG['extra_count']; ?></label><input type="text" name="count" id="count" class="text w100 align_right digits" value="<?php echo intval($tpl['arr']['count']); ?>" /></p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>	
	</form>
	<?php
}
?>