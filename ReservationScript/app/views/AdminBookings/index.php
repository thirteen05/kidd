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
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 0:
				Util::printNotice($CR_LANG['booking_err'][0]);
				break;
			case 1:
				Util::printNotice($CR_LANG['booking_err'][1]);
				break;
			case 2:
				Util::printNotice($CR_LANG['booking_err'][2]);
				break;
			case 3:
				Util::printNotice($CR_LANG['booking_err'][3]);
				break;
			case 4:
				Util::printNotice($CR_LANG['booking_err'][4]);
				break;
			case 5:
				Util::printNotice($CR_LANG['booking_err'][5]);
				break;
			case 7:
				Util::printNotice($CR_LANG['status'][7]);
				break;
			case 8:
				Util::printNotice($CR_LANG['booking_err'][8]);
				break;
		}
	}
	$jqDateFormat = Util::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="form">
		<p>
			<span class="r10"><?php echo $CR_LANG['booking_select_pdate']; ?>:</span>
			<input type="text" name="p_date" id="p_date" value="<?php echo isset($_GET['p_date']) ? $_GET['p_date'] : NULL; ?>" class="text w80 datepick pointer" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" />
		</p>
	</form>
	<?php	
	if (isset($tpl['arr']))
	{
		if (is_array($tpl['arr']))
		{
			$count = count($tpl['arr']);
			if ($count > 0)
			{
				?>
				<table class="table">
					<thead>
						<tr>
							<th class="sub"><?php echo $CR_LANG['booking_from']; ?></th>
							<th class="sub"><?php echo $CR_LANG['booking_to']; ?></th>
                                                        <th class="sub">Pickup Location</th>
							<th class="sub"><?php echo $CR_LANG['booking_type']; ?></th>
							<th class="sub"><?php echo $CR_LANG['booking_car']; ?></th>
							<th class="sub"><?php echo $CR_LANG['booking_total']; ?></th>
							<th class="sub"><?php echo $CR_LANG['booking_status']; ?></th>
							<th class="sub" style="width: 7%"></th>
							<th class="sub last" style="width: 8%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++)
				{
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'even' : 'odd'; ?>">
						<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr'][$i]['from'])); ?></td>
						<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr'][$i]['to'])); ?></td>
						
                                                <td><?php echo $tpl['locations'][$tpl['arr'][$i]['pickup_id']]; ?></td>
                                                    
                                                <td><?php echo @$CR_LANG['type_sizes'][$tpl['arr'][$i]['size']] . " " . $tpl['arr'][$i]['type_title']; ?></td>
						<td><a class="light-blue no-decor" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCars&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['car_id']; ?>"><?php echo stripslashes($tpl['arr'][$i]['make'] . " ".$tpl['arr'][$i]['registration_number']); ?></a></td>
						<td><?php echo Util::formatCurrencySign(number_format(floatval($tpl['arr'][$i]['total']), 2), $tpl['option_arr']['currency'], " "); ?></td>
						<td><span class="booking-status booking-status-<?php echo $tpl['arr'][$i]['status']; ?>"><?php echo $tpl['arr'][$i]['status']; ?></span></td>
						<td><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_edit']; ?></a></td>
						<td><a class="icon icon-delete" rel="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_delete']; ?></a></td>
					</tr>
					<?php
				}
				?>
					</tbody>
				</table>
				<?php
				if (isset($tpl['paginator']))
				{
					include_once VIEWS_PATH . 'Helpers/paginator.widget.php';
					Paginator::display($controller->isAjax(), $tpl['paginator']['pages']);
				}
				
				if (!$controller->isAjax())
				{
					?>
					<div id="dialogDelete" title="<?php echo htmlspecialchars($CR_LANG['booking_del_title']); ?>" style="display:none">
						<p><?php echo $CR_LANG['booking_del_body']; ?></p>
					</div>
					<?php
				}
			} else {
				Util::printNotice($CR_LANG['booking_empty']);
			}
		}
	}
}
?>