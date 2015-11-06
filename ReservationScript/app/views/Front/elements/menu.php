<!--<ul class="crLocale">
	<li><a href="<?php // echo INSTALL_URL; ?>" class="crLocaleEl" rel="1"><img src="<?php // echo INSTALL_URL . LIBS_PATH; ?>jquery/plugins/i18n/img/1.png" alt="" /></a></li>
	<li><a href="<?php // echo INSTALL_URL; ?>" class="crLocaleEl" rel="2"><img src="<?php // echo INSTALL_URL . LIBS_PATH; ?>jquery/plugins/i18n/img/2.png" alt="" /></a></li>
	<li><a href="<?php // echo INSTALL_URL; ?>" class="crLocaleEl" rel="3"><img src="<?php // echo INSTALL_URL . LIBS_PATH; ?>jquery/plugins/i18n/img/3.png" alt="" /></a></li>
</ul>-->
<ul class="crBreadcrumbs">
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>" rel="1" class="crBreadcrumbsEl<?php echo $_GET['action'] == 'loadSearch' ? ' focus' : NULL; ?>"><span>1</span><?php echo $CR_LANG['front']['menu_1']; ?></a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>" rel="2" class="crBreadcrumbsEl<?php echo $_GET['action'] == 'loadCars' ? ' focus' : NULL; ?>"><span>2</span><?php echo $CR_LANG['front']['menu_2']; ?></a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>" rel="3" class="crBreadcrumbsEl<?php echo $_GET['action'] == 'loadExtras' ? ' focus' : NULL; ?>"><span>3</span><?php echo $CR_LANG['front']['menu_3']; ?></a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>" rel="4" class="crBreadcrumbsEl<?php echo $_GET['action'] == 'loadCheckout' ? ' focus' : NULL; ?>"><span>4</span><?php echo $CR_LANG['front']['menu_4']; ?></a></li>
</ul>