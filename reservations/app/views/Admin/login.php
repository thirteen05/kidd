<h3>Reservation Manager Login</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=Admin&amp;action=login" method="post" id="frmLoginAdmin" class="login-form">
	<input type="hidden" name="login_user" value="1" />
	<table class="login-table" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $CR_LANG['login_username']; ?>:</th>
			<th colspan="2"><?php echo $CR_LANG['login_password']; ?>:</th>
		</tr>
		<tr>
			<td><input name="login_username" type="text" class="text-login w250" id="login_username" /></td>
			<td><input name="login_password" type="password" class="text-login w250" id="login_password" /></td>
			<td><input type="submit" value="" class="button button_login" /></td>
		</tr>
	</table>
	<ul id="login-errors">
		<li><label for="login_username" class="error">Username is required</label></li>
		<li><label for="login_password" class="error">Password is required</label></li>
	</ul>
	<?php
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 1:
				?><p><?php echo $CR_LANG['login_err'][1]; ?></p><?php
				break;
			case 2:
				?><p><?php echo $CR_LANG['login_err'][2]; ?></p><?php
				break;
			case 3:
				?><p><?php echo $CR_LANG['login_err'][3]; ?></p><?php
				break;
		}
	}
	?>
</form>