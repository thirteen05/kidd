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
	include VIEWS_PATH . 'AdminUsers/index.php';
}
?>