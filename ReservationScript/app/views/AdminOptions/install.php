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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">

		<p><span class="bold block b10"><?php echo $CR_LANG['o_install']['js'][1]; ?></span>
		<textarea class="textarea textarea-install w700 h150 overflow">
&lt;link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=loadCss" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=loadJs"&gt;&lt;/script&gt;
</textarea></p>

		<p><span class="bold block b10"><?php echo $CR_LANG['o_install']['js'][2]; ?></span>
		<textarea class="textarea textarea-install w700 h80 overflow">
&lt;script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=load"&gt;&lt;/script&gt;</textarea>
	</form>
	<?php
}
?>