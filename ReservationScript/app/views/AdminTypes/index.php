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
				Util::printNotice($CR_LANG['type_err'][0]);
				break;
			case 1:
				Util::printNotice($CR_LANG['type_err'][1]);
				break;
			case 2:
				Util::printNotice($CR_LANG['type_err'][2]);
				break;
			case 3:
				Util::printNotice($CR_LANG['type_err'][3]);
				break;
			case 4:
				Util::printNotice($CR_LANG['type_err'][4]);
				break;
			case 5:
				Util::printNotice($CR_LANG['type_err'][5]);
				break;
			case 7:
				Util::printNotice($CR_LANG['status'][7]);
				break;
			case 8:
				Util::printNotice($CR_LANG['type_err'][8]);
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
							<th class="sub"><?php echo $CR_LANG['type_image']; ?></th>
							<th class="sub"><?php echo $CR_LANG['type_size_type']; ?></th>
							<th class="sub">Boat Details</th>
							<th class="sub">Number of Boats</th>
							<th class="sub" style="width: 10%"></th>
							<th class="sub" style="width: 10%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++)
				{
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'even' : 'odd'; ?>">
						<td class="align_top align_center type_img"><img src="<?php echo is_file($tpl['arr'][$i]['thumb_path']) ? $tpl['arr'][$i]['thumb_path'] : IMG_PATH . 'backend/noimg.png'; ?>" alt="" /></td>
						<td class="align_top"><?php echo stripslashes(@$CR_LANG['type_sizes'][$tpl['arr'][$i]['size']] . " / " . $tpl['arr'][$i]['name']); ?></td>
						<td class="align_top">
							<span class=" float_left"><?php echo $tpl['arr'][$i]['passengers']; ?> Passenger(s)</span><br>
<!--							<span class="attribute attribute-luggages float_left"><?php //echo $tpl['arr'][$i]['luggages']; ?></span>-->
<!--							<span class="attribute attribute-doors float_left"><?php // echo $tpl['arr'][$i]['doors']; ?></span>-->
<!--							<span class="attribute attribute-transmission float_left"><?php // echo strtoupper($tpl['arr'][$i]['transmission']{0}); ?></span>-->
						</td>
						<td class="align_top"><?php 
						if ((int) $tpl['arr'][$i]['cnt'] > 0)
						{
							?><a class="light-blue no-decor" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCars&amp;action=index&amp;type_id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo intval($tpl['arr'][$i]['cnt']); ?></a><?php 
						} else {
							echo intval($tpl['arr'][$i]['cnt']);							
						}
						?></td>
						<td class="align_top"><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTypes&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_edit']; ?></a></td>
						<td class="align_top"><a class="icon icon-delete" rel="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTypes&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $CR_LANG['_delete']; ?></a></td>
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
					<div id="dialogDelete" title="<?php echo htmlspecialchars($CR_LANG['type_del_title']); ?>" style="display:none">
						<p><?php echo $CR_LANG['type_del_body']; ?></p>
					</div>
					<?php
				}
			} else {
				Util::printNotice($CR_LANG['type_empty']);
			}
		}
	}
}
?>