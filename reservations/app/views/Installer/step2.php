<?php
var_dump($tpl);

if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 'ok':
			?>
			<h3>Step 2: Back-end data</h3>
			<form action="index.php?controller=Installer&amp;action=step3&amp;install=1" method="post" id="frmStep2" class="form_install">
				<input type="hidden" name="step2" value="1" />
				<input type="hidden" name="hostname" value="<?php echo isset($_SESSION[$controller->default_product]['Install']['hostname']) ? $_SESSION[$controller->default_product]['Install']['hostname'] : NULL; ?>" />
				<input type="hidden" name="username" value="<?php echo isset($_SESSION[$controller->default_product]['Install']['username']) ? $_SESSION[$controller->default_product]['Install']['username'] : NULL; ?>" />
				<input type="hidden" name="password" value="<?php echo isset($_SESSION[$controller->default_product]['Install']['password']) ? $_SESSION[$controller->default_product]['Install']['password'] : NULL; ?>" />
				<input type="hidden" name="database" value="<?php echo isset($_SESSION[$controller->default_product]['Install']['database']) ? $_SESSION[$controller->default_product]['Install']['database'] : NULL; ?>" />
				<input type="hidden" name="prefix" value="<?php echo isset($_SESSION[$controller->default_product]['Install']['prefix']) ? $_SESSION[$controller->default_product]['Install']['prefix'] : NULL; ?>" />
			
				<p><label class="title"><span class="red">*</span> Username</label> <input type="text" name="admin_username" class="required" /></p>
				<p><label class="title"><span class="red">*</span> Password</label> <input type="text" name="admin_password" class="required" /></p>
				<p>
					<input type="submit" value="Finish" />
					<input type="button" value="Back" onclick="window.location='index.php?controller=Installer&action=step1'" />
				</p>
			</form>
			<?php
			if (isset($tpl['warning']))
			{
				switch ($tpl['warning'])
				{
					case 4:
						?>
						<h3>Warning</h3>
						<p class="form_install">
							If you proceed with the installation your current database and all the data will be deleted.
						</p>
						<?php
						break;
				}
			}
			break;
		case 2:
			?>
			<h3>Error 2</h3>
			<p class="form_install">
				Can't connect to MySQL server. Please check you data again.
				<br /><br />
				<input type="button" value="Back" onclick="window.location='index.php?controller=Installer&action=step1'" />
			</p>
			<?php
			break;
		case 3:
			?>
			<h3>Error 3</h3>
			<p class="form_install">
				Can't select database. Please check you data again.
				<br /><br />
				<input type="button" value="Back" onclick="window.location='index.php?controller=Installer&action=step1'" />
			</p>
			<?php
			break;
		case 7:
			?><p class="form_install"><?php echo $CR_LANG['status'][7]; ?></p><?php
			break;
	}
}
?>