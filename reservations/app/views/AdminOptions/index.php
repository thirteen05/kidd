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
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 5:
				Util::printNotice($CR_LANG['option_err'][5]);
				break;
			case 7:
				Util::printNotice($CR_LANG['status'][7]);
				break;
		}
	}
	$tab_id = isset($_GET['tab']) && !empty($_GET['tab']) ? $_GET['tab'] : 1;
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminOptions&amp;action=update" method="post" class="form">
		<input type="hidden" name="options_update" value="1" />
		<input type="hidden" name="tab" value="<?php echo $tab_id; ?>" />
		<?php
		if ($tab_id != 2)
		{
			include VIEWS_PATH . 'AdminOptions/elements/tab.php';
		} else {
			include VIEWS_PATH . 'AdminOptions/elements/prices.php';
		}
		?>
	</form>
	<?php
}
?>