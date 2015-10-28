<?php include_once VIEWS_PATH . 'Front/elements/menu.php'; ?>

<div class="crBox crExtraLeft">
	<div class="crBoxWTop"><?php echo $CR_LANG['front']['3_booking']; ?></div>
	<div class="crBoxMiddle">
		<div class="crType">
			<div class="crStep"><span>1</span> <?php echo $CR_LANG['front']['3_when']; ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>" id="crBtnWhen"><?php echo $CR_LANG['front']['3_change']; ?></a></div>
			<table class="crTableXtra" style="width: 100%">
				<tbody>
					<tr>
						<td class="crGray crARight" style="width: 80px"><?php echo $CR_LANG['front']['3_pickup']; ?>:</td>
						<td><?php echo stripslashes($tpl['location_arr']['pickup_location']); ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime(Util::formatDate($_SESSION[$controller->default_product][$controller->default_order]['date_from'], $tpl['option_arr']['date_format']) . " " . $_SESSION[$controller->default_product][$controller->default_order]['hour_from'] . ":" . $_SESSION[$controller->default_product][$controller->default_order]['minutes_from'] . ":00")); ?></td>
					</tr>
					<tr>
						<td class="crGray crARight"><?php echo $CR_LANG['front']['3_return']; ?>:</td>
						<td><?php echo isset($tpl['location_arr']['return_location']) ? stripslashes($tpl['location_arr']['return_location']) : stripslashes($tpl['location_arr']['pickup_location']); ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime(Util::formatDate($_SESSION[$controller->default_product][$controller->default_order]['date_to'], $tpl['option_arr']['date_format']) . " " . $_SESSION[$controller->default_product][$controller->default_order]['hour_to'] . ":" . $_SESSION[$controller->default_product][$controller->default_order]['minutes_to'] . ":00")); ?></td>
					</tr>
					<!--<tr>
						<td class="crGray crARight"><?php echo $CR_LANG['front']['3_rental']; ?>:</td>
						<td><?php echo $_SESSION[$controller->default_product][$controller->default_order]['rental_days']; ?></td>
					</tr>-->
				</tbody>
			</table>
		</div>
		<div class="crType">
			<div class="crStep"><span>2</span> <?php echo $CR_LANG['front']['3_choise']; ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>" id="crBtnChoise"><?php echo $CR_LANG['front']['3_change']; ?></a></div>
			<div class="cr3Items">
				<p class="crBold"><?php echo @$CR_LANG['type_sizes'][$tpl['type_arr']['size']]; ?>: <?php echo stripslashes(@$tpl['type_arr']['name']); ?></p>
				<p class="crGray">(<?php echo $CR_LANG['front']['3_example']; ?>: <?php echo stripslashes(@$tpl['type_arr']['example']['make'] . " " . @$tpl['type_arr']['example']['model']); ?>)</p>
				<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>" id="crBtnConditions"><?php echo $CR_LANG['front']['3_conditions']; ?></a></p>
			</div>
			<img src="<?php echo is_file(@$tpl['type_arr']['thumb_path']) ? INSTALL_URL . $tpl['type_arr']['thumb_path'] : INSTALL_URL . IMG_PATH . 'frontend/dummy_1.png'; ?>" alt="" class="cr3Img" />
		</div>
	</div>
	<div class="crBoxBottom">
		<div class="crBoxBottomLeft"></div>
		<div class="crBoxBottomRight"></div>
	</div>
</div>

<div class="crBox crExtraRight">
	<div class="crBoxWTop"><?php echo $CR_LANG['front']['3_extras']; ?></div>
	<div class="crBoxMiddle">
	<?php
	$extraCost = 0;
	foreach ($tpl['arr'] as $extra)
	{
		?>
		<div class="crExtra">
			<abbr><?php echo stripslashes($extra['name']); ?></abbr>
			<?php
			if (isset($_SESSION[$controller->default_product][$controller->default_order]) && isset($_SESSION[$controller->default_product][$controller->default_order]['extras']) && array_key_exists($extra['extra_id'], $_SESSION[$controller->default_product][$controller->default_order]['extras']))
			{
				?><button type="button" value="<?php echo $extra['extra_id']; ?>" class="crBtn crBtnRemove"><?php echo $CR_LANG['front']['btn_remove']; ?></button><?php
				switch ($extra['per'])
				{
					case 'day':
						$extraCost += $extra['price'] * $_SESSION[$controller->default_product][$controller->default_order]['rental_days'];
						break;
					case 'booking':
						$extraCost += $extra['price'];
						break;
				}
			} else {
				?><button type="button" value="<?php echo $extra['extra_id']; ?>" class="crBtn crBtnAdd"><?php echo $CR_LANG['front']['btn_add']; ?></button><?php
			}
			?>
			<p>
				<strong><?php echo Util::formatCurrencySign(number_format(floatval($extra['price']), 2), $tpl['option_arr']['currency'], " "); ?></strong>
				<span><?php echo $CR_LANG['front']['3_per']; ?> <?php echo @$CR_LANG['extra_types'][$extra['per']]; ?></span>
			</p>
		</div>
		<?php
	}
	?>
	
	<table class="crExtraTable" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td class="crKey"><?php echo $CR_LANG['front']['3_price']; ?></td>
				<td class="crValue"><?php
				$rentalPrice = @$tpl['price_arr'][0][$tpl['price']] * $_SESSION[$controller->default_product][$controller->default_order]['rental_days'];
				echo Util::formatCurrencySign(number_format(floatval($rentalPrice), 2), $tpl['option_arr']['currency'], " "); ?></td>
			</tr>
			<?php
			$total = $extraCost + $rentalPrice;
			$tax = 0;
			if ($tpl['option_arr']['tax'] > 0)
			{
				?>
				<tr>
					<td class="crKey"><?php echo $CR_LANG['front']['3_tax']; ?></td>
					<td class="crValue"><?php
					$tax = ($total * $tpl['option_arr']['tax']) / 100;
					echo Util::formatCurrencySign(number_format($tax, 2), $tpl['option_arr']['currency'], " ");
					?></td>
				</tr>
				<?php
				$total += $tax;
			}
			?>
			<tr>
				<td class="crKey"><?php echo $CR_LANG['front']['3_total']; ?></td>
				<td class="crValue"><?php
				echo Util::formatCurrencySign(number_format($total, 2), $tpl['option_arr']['currency'], " ");
				?></td>
			</tr>
			<tr>
				<td class="crKey"><?php echo $CR_LANG['front']['3_deposit']; ?></td>
				<td class="crValue"><?php
				$deposit = $tpl['option_arr']['deposit_percent'];
				echo Util::formatCurrencySign(number_format($deposit, 2), $tpl['option_arr']['currency'], " ");
				?></td>
			</tr>
		</tbody>
	</table>
	<input type="button" value="<?php echo $CR_LANG['front']['btn_checkout']; ?>" id="crBtnCheckout" class="crBtn crBtnCheckout" />
	
	</div>
	<div class="crBoxBottom">
		<div class="crBoxBottomLeft"></div>
		<div class="crBoxBottomRight"></div>
	</div>
</div>