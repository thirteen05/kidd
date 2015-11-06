<?php include_once VIEWS_PATH . "AdminBookings/availability.php"; ?>

<table class="table" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th><?php echo $CR_LANG['booking_locations']; ?></th>
			<th><?php echo $CR_LANG['booking_available']; ?></th>
			<th><?php echo $CR_LANG['booking_reservations']; ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($tpl['location_arr'] as $k => $location)
	{
		$_available = $_booked = $_dates = array();
		foreach ($location['available_arr'] as $car)
		{
			$_available[] = stripslashes($car['make'] . " " . $car['model'] . " " . $car['registration_number']);
		}
		foreach ($location['booking_arr'] as $booking)
		{
			$_booked[] = '<a class="light-blue no-decor" href="'.$_SERVER['PHP_SELF'].'?controller=AdminBookings&amp;action=index&amp;car_id='.$booking['car_id'].'" title="'.$CR_LANG['_bookings'].'">'.stripslashes($booking['make'] . " " . $booking['model'] . " " . $booking['registration_number']).'</a>';
			$_dates[] = '<a class="light-blue no-decor" href="'.$_SERVER['PHP_SELF'].'?controller=AdminBookings&amp;action=update&amp;id='.$booking['id'].'" title="'.$CR_LANG['_edit'].'">'.date($tpl['option_arr']['datetime_format'], strtotime($booking['from'])) . ' - ' . date($tpl['option_arr']['datetime_format'], strtotime($booking['to'])).'</a>';
		}
		$cnt_a = count($_available);
		$cnt_b = count($_booked);
		$rows = max(compact('cnt_a', 'cnt_b'));
		$rows = $rows === 0 ? 1 : $rows;
		foreach (range(0, $rows - 1) as $i)
		{
			?>
			<tr>
				<td><?php echo ($i > 0) ? NULL : stripslashes($location['name']); ?></td>
				<td><?php echo isset($_available[$i]) ? $_available[$i] : NULL; ?></td>
				<td><?php echo isset($_booked[$i]) ? $_booked[$i] : NULL; ?></td>
				<td><?php echo isset($_dates[$i]) ? $_dates[$i] : NULL; ?></td>
			</tr>
			<?php
		}
	}
	?>
	</tbody>
</table>