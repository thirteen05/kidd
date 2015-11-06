<?php include_once VIEWS_PATH . 'Front/elements/menu.php'; ?>
<div class="crBox">
	<div class="crBoxTop">
		<div class="crBoxTopLeft"></div>
		<div class="crBoxTopRight"></div>
	</div>
	<div class="crBoxMiddle">
		<ul class="crTabs">
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crTabsLink<?php echo !isset($_GET['size']) || (isset($_GET['size']) && $_GET['size'] == 'all') ? ' crFocus' : NULL; ?>" rel="all"><?php echo $CR_LANG['front']['2_all']; ?></a></li>
			<?php
			foreach ($CR_LANG['type_sizes'] as $k => $size)
			{
				?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crTabsLink<?php echo isset($_GET['size']) && $_GET['size'] == $k ? ' crFocus' : NULL; ?>" rel="<?php echo $k; ?>"><?php echo $size; ?></a></li><?php
			}
			?>
		</ul>
		
		<div class="crSortHolder">
			<label><?php echo $CR_LANG['front']['2_price']; ?></label>
			<span>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crSort crUp" rel="total_price|asc"></a>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crSort crDown" rel="total_price|desc"></a>
			</span>
			
			<label><?php echo $CR_LANG['front']['2_size']; ?></label>
			<span>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crSort crUp" rel="size|asc"></a>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crSort crDown" rel="size|desc"></a>
			</span>
			
<!--			<label><?php //echo $CR_LANG['front']['2_luggage']; ?></label>
			<span>
				<a href="<?php //echo $_SERVER['PHP_SELF']; ?>" class="crSort crUp" rel="luggages|asc"></a>
				<a href="<?php //echo $_SERVER['PHP_SELF']; ?>" class="crSort crDown" rel="luggages|desc"></a>
			</span>-->
			
<!--			<label><?php //echo $CR_LANG['front']['2_transmission']; ?></label>
			<select name="transmission" id="crTransmission" class="crSelect" style="padding: 1px">
				<option value=""><?php //echo $CR_LANG['front']['2_any']; ?></option>
				<?php
				//foreach ($CR_LANG['type_transmissions'] as $k => $v)
				//{
					?><option value="<?php //echo $k; ?>"<?php //echo isset($_GET['transmission']) && $_GET['transmission'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
				//}
				?>
			</select>-->
		</div>
		<?php
		if (count($tpl['arr']) > 0)
		{
			foreach ($tpl['arr'] as $type)
			{
				?>
				<div class="crType">
					<img src="<?php echo (!empty($type['thumb_path'])) ? INSTALL_URL . $type['thumb_path'] : INSTALL_URL . IMG_PATH . "frontend/dummy_2.png"; ?>" alt="" class="crTypeImg" />
					<div class="crTypeArea">
						<div class="crTypeTitle"><strong><?php echo $CR_LANG['type_sizes'][$type['size']] ;?>: <?php echo stripslashes($type['name']); ?></strong> (<?php echo $CR_LANG['front']['2_example']; ?>: <?php echo stripslashes(@$type['example']['make'] . " " . @$type['example']['model']); ?>)</div>
						<div class="crAttributeBar">
							<span class="" title="<?php echo $CR_LANG['front']['2_passengers']; ?>"><?php echo $type['passengers']; ?> Passengers</span>
<!--							<span class="crAttribute crAttribute-luggages" title="<?php // echo $CR_LANG['front']['2_luggage']; ?>"><?php // echo $type['luggages']; ?></span>-->
<!--							<span class="crAttribute crAttribute-doors" title="<?php // echo $CR_LANG['front']['2_doors']; ?>"><?php // echo $type['doors']; ?></span>-->
<!--							<span class="crAttribute crAttribute-transmission" title="<?php // echo $CR_LANG['front']['2_transmission']; ?>"><?php // echo strtoupper($type['transmission']{0}); ?></span>-->
						</div>
						<div class="crTypeDesc"><?php echo stripslashes(nl2br($type['description'])); ?></div>
						<div class="crTypePrice">
							<p><?php echo $CR_LANG['front']['2_best']; ?>:</p> <span class="crPrice"><abbr></abbr><strong><?php echo Util::formatCurrencySign(number_format(floatval($type['total_price']), 2), $tpl['option_arr']['currency'], " "); ?></strong><?php /*echo $CR_LANG['front']['2_per_day'];*/ ?></span>
						</div>
						<div class="crTypeBootom">
							<?php
							if ($type['cnt_available'] > 0 && $type['total_price'])
							{
								echo $CR_LANG['front']['2_available'];
								?><button type="button" value="<?php echo $type['id']; ?>" class="crBtn crBtnContinue"><?php echo $CR_LANG['front']['btn_continue']; ?></button><?php
							} else {
								echo $CR_LANG['front']['2_not'];
							}
							?>
						</div>
					</div>
				</div>
				<?php
			}
		} else {
			?><p style="padding: 10px"><?php echo $CR_LANG['front']['2_empty']; ?></p><?php
		}
		?>
	</div>
	<div class="crBoxBottom">
		<div class="crBoxBottomLeft"></div>
		<div class="crBoxBottomRight"></div>
	</div>
</div>