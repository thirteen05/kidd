<?php include_once VIEWS_PATH . 'Helpers/time.widget.php'; ?>
<?php include_once VIEWS_PATH . 'Front/elements/menu.php'; ?>

<div class="crBox">
	<div class="crBoxTop">
		<div class="crBoxTopLeft"></div>
		<div class="crBoxTopRight"></div>
	</div>
	<div class="crBoxMiddle">
		<form action="" method="get" id="crFormSearch" class="crForm">
			<div class="crType">
				<p>
					<label class="crLabel"><?php echo $CR_LANG['front']['1_start']; ?></label>
					<input type="text" id="cr_date_from" name="date_from" class="crText crPointer crDate" value="<?php echo isset($_SESSION[$controller->default_product][$controller->default_order]['date_from']) ? htmlspecialchars($_SESSION[$controller->default_product][$controller->default_order]['date_from']) : date($tpl['option_arr']['date_format'], strtotime("+1 day")); ?>" readonly="readonly" />
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="crDatepickerIcon" id="crDateFrom"></a>
				</p>
				<p style="display:none">
					<label class="crLabel"><?php echo $CR_LANG['front']['1_end']; ?></label>
					<input type="text" id="cr_date_to" name="date_to" class="crText crPointer crDate" value="<?php echo isset($_SESSION[$controller->default_product][$controller->default_order]['date_to']) ? htmlspecialchars($_SESSION[$controller->default_product][$controller->default_order]['date_to']) : date($tpl['option_arr']['date_format'], strtotime("+1 day")); ?>" readonly="readonly" />
				</p>
				
				
				<p>
					<label class="crLabel"><?php echo $CR_LANG['front']['1_duration']; ?></label>
					<select name="duration" id="duration" class="crSelect">
					<?php
					foreach ($CR_LANG['front']['1_durationes'] as $key => $val)
					{
						?>
						<option value="<?php echo $key; ?>"<?php echo isset($_SESSION[$controller->default_product][$controller->default_order]['duration']) && $_SESSION[$controller->default_product][$controller->default_order]['duration'] == $key ? ' selected="selected"' : NULL; ?>><?php echo$val ; ?></option><?php
					}
					?>
					</select>
				</p>
                                <p>
                                    <label class="crLabel">Pickup Location</label>
                                    <select name='pickup_location' id='pickup_location' class="crSelect">
                                    <?php foreach($tpl['location_arr'] as $location): ?>
                                        <option value="<?php echo $location['id']; ?>"><?php echo $location['name']; ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </p>
                                
				<p id="timeBox" style="display: block;<?php // echo isset($_SESSION[$controller->default_product][$controller->default_order]['duration']) && ( $_SESSION[$controller->default_product][$controller->default_order]['duration'] == '1_4' || $_SESSION[$controller->default_product][$controller->default_order]['duration'] == '1_2') ? 'block' : 'none'; ?>">
				</p>
			</div>
			<div class="crLocation">
				<div class="crNote" style="background:none">
					<input type="button" id="crBtnQuote" value="<?php echo $CR_LANG['front']['btn_quote']; ?>" class="crBtn crBtnQuote" />
				</div>
				
			</div>
			<p class="crError" style="display: none"></p>
		</form>
	</div>
	<div class="crBoxBottom">
		<div class="crBoxBottomLeft"></div>
		<div class="crBoxBottomRight"></div>
	</div>
</div>