<?php 
if (isset($tpl['i18n_arr']) && count($tpl['i18n_arr']) === 1)
{
	echo stripslashes(nl2br($tpl['i18n_arr'][0]['content']));
}
?>