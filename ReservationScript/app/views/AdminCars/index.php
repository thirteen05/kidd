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
				Util::printNotice($CR_LANG['car_err'][0]);
				break;
			case 1:
				Util::printNotice($CR_LANG['car_err'][1]);
				break;
			case 2:
				Util::printNotice($CR_LANG['car_err'][2]);
				break;
			case 3:
				Util::printNotice($CR_LANG['car_err'][3]);
				break;
			case 4:
				Util::printNotice($CR_LANG['car_err'][4]);
				break;
			case 5:
				Util::printNotice($CR_LANG['car_err'][5]);
				break;
			case 7:
				Util::printNotice($CR_LANG['status'][7]);
				break;
			case 8:
				Util::printNotice($CR_LANG['car_err'][8]);
				break;
		}
	}
	
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
							<th class="sub"><?php echo $CR_LANG['car_reg']; ?></th>
							<th class="sub"><?php echo $CR_LANG['car_make_model']; ?></th>
							<th class="sub"><?php echo $CR_LANG['car_type']; ?></th>
							<th class="sub" style="width: 9%"></th>
							<th class="sub" style="width: 7%"></th>
							<th class="sub" style="width: 8%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++)
				{
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'even' : 'odd'; ?>">
						<td><?php echo stripslashes($tpl['arr'][$i]['registration_number']); ?></td>
						<td><?php echo stripslashes($tpl['arr'][$i]['make'] . " " . $tpl['arr'][$i]['model']); ?></td>
						<td>
						<?php
						$ct_arr = array();
						foreach ($tpl['arr'][$i]['CarType'] as $ct)
						{
							$ct_arr[] = @$CR_LANG['type_sizes'][$ct['size']] . " " . $ct['name'];
						}
						echo join(", ", array_map('stripslashes', $ct_arr));
						?>
						</td>
						<td><a class="icon icon-booking" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=index&amp;car_id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_bookings']; ?></a></td>
						<td><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCars&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_edit']; ?></a></td>
						<td><a class="icon icon-delete" rel="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCars&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_delete']; ?></a></td>
					</tr>
					<?php
				}
				?>
					</tbody>
				</table>
				<?php
				if (isset($tpl['paginator']))
				{
					?>
					<ul class="paginator">
					<?php
					for ($i = 1; $i <= $tpl['paginator']['pages']; $i++)
					{
						if ((isset($_GET['page']) && (int) $_GET['page'] == $i) || (!isset($_GET['page']) && $i == 1))
						{
							?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>" class="focus"><?php echo $i; ?></a></li><?php
						} else {
							?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
						}
					}
					?>
					</ul>
					<?php
				}
				
				if (!$controller->isAjax())
				{
					?>
					<div id="dialogDelete" title="<?php echo htmlspecialchars($CR_LANG['car_del_title']); ?>" style="display:none">
						<p><?php echo $CR_LANG['car_del_body']; ?></p>
					</div>
					<?php
				}
			} else {
				Util::printNotice($CR_LANG['car_empty']);
			}
		}
	}
}
?>