<?php
if (isset($tpl['arr']))
{
	if (is_array($tpl['arr']))
	{
		$count = count($tpl['arr']);
		if ($count > 0)
		{
			foreach ($tpl['arr'] as $group => $arr)
			{
				if (count($arr) == 0) continue;
				ob_start();
				if (!empty($group))
				{
					?><h3><?php echo $group; ?></h3><?php
				}
				?>
				<table cellpadding="2" cellspacing="1" class="table">
					<thead>
						<tr>
							<th class="sub" style="width: 50%"><?php echo $CR_LANG['option_description']; ?></th>
							<th class="sub"><?php echo $CR_LANG['option_value']; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if ($tab_id == 1)
					{
						?>
						<tr>
							<td class="align_top"><?php echo $CR_LANG['option_username']; ?></td>
							<td class="align_top"><input type="text" name="username" value="<?php echo htmlspecialchars(stripslashes($_SESSION[$controller->default_user]['username'])); ?>" class="text w200" /></td>
						</tr>
						<tr>
							<td class="align_top"><?php echo $CR_LANG['option_password']; ?></td>
							<td class="align_top"><input type="text" name="password" value="<?php echo htmlspecialchars(stripslashes($_SESSION[$controller->default_user]['password'])); ?>" class="text w200" /></td>
						</tr>
						<?php
					}
				
				$j = 0;
				for ($i = 0; $i < count($arr); $i++)
				{
					if ($arr[$i]['tab_id'] == $tab_id)
					{
						if ($tab_id == 3)
						{
							switch ($arr[$i]['key'])
							{
								case 'payment_enable_paypal':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_paypal = $val == "No" ? " none" : NULL;
									break;
								case 'payment_enable_authorize':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_authorize = $val == "No" ? " none" : NULL;
									break;
							}
						}
						if (in_array($arr[$i]['key'], array('paypal_address')))
						{
							?><tr class="<?php echo $class_paypal; ?>"><?php
						} elseif (in_array($arr[$i]['key'], array('payment_authorize_key', 'payment_authorize_mid'))) {
							?><tr class="<?php echo $class_authorize; ?>"><?php
						} else {
							?><tr class=""><?php
						}
						?>
							<td class="align_top"><?php echo html_entity_decode(stripslashes($arr[$i]['description'])); ?></td>
							<td class="align_top">
								<?php
								switch ($arr[$i]['type'])
								{
									case 'string':
										if (!in_array($arr[$i]['key'], array('email_confirmation_subject', 'email_payment_subject')))
										{
											?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w300" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" /><?php
										} else {
											?>
											<div>
												<?php 
												foreach (range(1, $CR_LANG['i18n_locales']) as $ii)
												{
													?>
													<input type="text" name="i18n[<?php echo $ii; ?>][<?php echo $arr[$i]['key']; ?>]" class="text w300 float_left" style="display: <?php echo $ii > 1 ? 'none' : NULL; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['i18n_'.$arr[$i]['id']][$ii][$arr[$i]['key']])); ?>" />
													<?php
												}
												?>
<!--												<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
												<br class="clear_left" />
											</div>
											<?php											
										}										
										break;
									case 'text':
										if (!in_array($arr[$i]['key'], array('terms', 'email_confirmation_message', 'email_payment_message')))
										{
											?><textarea name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="textarea w400 h230"><?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?></textarea><?php
										} else {
											?>
											<div>
												<?php 
												foreach (range(1, $CR_LANG['i18n_locales']) as $ii)
												{
													?>
													<textarea name="i18n[<?php echo $ii; ?>][<?php echo $arr[$i]['key']; ?>]" class="textarea w400 h230 float_left" style="display: <?php echo $ii > 1 ? 'none' : NULL; ?>"><?php echo stripslashes(@$tpl['i18n_'.$arr[$i]['id']][$ii][$arr[$i]['key']]); ?></textarea>
													<?php
												}
												?>
<!--												<span class="i18n_selector" title="<?php // echo $CR_LANG['i18n_tooltip']; ?>"></span>-->
												<br class="clear_left" />
											</div>
											<?php
										}
										break;
									case 'int':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w50 align_right digits" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" />
										<?php
										break;
									case 'float':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w50 align_right number" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" /><?php
										break;
									case 'enum':
										?><select name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="select">
										<?php
										$default = explode("::", $arr[$i]['value']);
										$enum = explode("|", $default[0]);
										
										$enumLabels = array();
										if (!empty($arr[$i]['label']) && strpos($arr[$i]['label'], "|") !== false)
										{
											$enumLabels = explode("|", $arr[$i]['label']);
										}
										
										foreach ($enum as $k => $el)
										{
											if ($default[1] == $el)
											{
												?><option value="<?php echo $default[0].'::'.$el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
											} else {
												?><option value="<?php echo $default[0].'::'.$el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
											}
										}
										?>
										</select>
										<?php
										break;
									case 'color':
										?>
										<div id="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="colorSelector"><div style="background-color: #<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>"></div></div>
										<input type="hidden" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" class="hex" />
										<?php
										break;
								}
								?>
							</td>
						</tr>
						<?php
						$j++;
					}
				}
				?>
					</tbody>
				</table>
				<?php
				if ($j > 0)
				{
					ob_end_flush();
				} else {
					ob_end_clean();
				}
			}
			?>
			<p>&nbsp;</p>
			<p><input type="submit" value="" class="button button_save" /></p>
			<?php
		}
	}
}
?>