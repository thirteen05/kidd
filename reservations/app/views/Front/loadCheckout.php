<?php include_once VIEWS_PATH . 'Front/elements/menu.php'; ?>
<div class="crBox">
	<div class="crBoxTop">
		<div class="crBoxTopLeft"></div>
		<div class="crBoxTopRight"></div>
	</div>
	<div class="crBoxMiddle">
		<form action="" method="post" class="crForm">
			<div class="crLegend">
				<span class="crLegendLeft">&nbsp;</span>
				<span class="crLegendText"><?php echo $CR_LANG['front']['4_personal']; ?></span>
				<span class="crLegendRight">&nbsp;</span>
			</div>
			<?php if (in_array($tpl['option_arr']['bf_include_title'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_title']; ?> <?php if ($tpl['option_arr']['bf_include_title'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<select name="c_title" class="crSelect<?php echo ($tpl['option_arr']['bf_include_title'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_title'])); ?>">
					<option value=""><?php echo $CR_LANG['front']['4_select_title']; ?></option>
					<?php 
					foreach ($CR_LANG['_titles'] as $k => $v)
					{
						?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
					}
					?>
				</select>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_fname'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_fname']; ?> <?php if ($tpl['option_arr']['bf_include_fname'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_fname" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_fname'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_fname'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_lname'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_lname']; ?> <?php if ($tpl['option_arr']['bf_include_lname'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_lname" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_lname'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_lname'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_phone'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_phone']; ?> <?php if ($tpl['option_arr']['bf_include_phone'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_phone" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_phone'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_phone'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_email'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_email']; ?> <?php if ($tpl['option_arr']['bf_include_email'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_email" class="crText crW320 crEmail<?php echo ($tpl['option_arr']['bf_include_email'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_email'])); ?>" />
			</p>
			<?php endif; ?>
			<div class="crLegend">
				<span class="crLegendLeft">&nbsp;</span>
				<span class="crLegendText"><?php echo $CR_LANG['front']['4_billing']; ?></span>
				<span class="crLegendRight">&nbsp;</span>
			</div>
			<?php if (in_array($tpl['option_arr']['bf_include_company'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_company']; ?> <?php if ($tpl['option_arr']['bf_include_company'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_company" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_company'] == 3) ? ' crRequired' : NULL; ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_address_1'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_address_1']; ?> <?php if ($tpl['option_arr']['bf_include_address_1'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_address_1" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_address_1'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_address_1'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_address_2'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_address_2']; ?> <?php if ($tpl['option_arr']['bf_include_address_2'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_address_2" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_address_2'] == 3) ? ' crRequired' : NULL; ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_address_3'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_address_3']; ?> <?php if ($tpl['option_arr']['bf_include_address_3'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_address_3" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_address_3'] == 3) ? ' crRequired' : NULL; ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_city'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_city']; ?> <?php if ($tpl['option_arr']['bf_include_city'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_city" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_city'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_city'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_state'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_state']; ?> <?php if ($tpl['option_arr']['bf_include_state'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_state" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_state'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_state'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_zip'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_zip']; ?> <?php if ($tpl['option_arr']['bf_include_zip'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<input type="text" name="c_zip" class="crText crW320<?php echo ($tpl['option_arr']['bf_include_zip'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_zip'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_country'], array(2, 3))) : ?>
			<p>
				<label class="crLabel"><?php echo $CR_LANG['front']['4_country']; ?> <?php if ($tpl['option_arr']['bf_include_country'] == 3) : ?><span class="crRed">*</span><?php endif; ?></label>
				<select name="c_country" class="crSelect crW328<?php echo ($tpl['option_arr']['bf_include_country'] == 3) ? ' crRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_country'])); ?>">
					<option value=""><?php echo $CR_LANG['front']['4_select_country']; ?></option>
					<?php 
					foreach ($tpl['country_arr'] as $country)
					{
						?><option value="<?php echo $country['id']; ?>"><?php echo stripslashes($country['country_title']); ?></option><?php
					}
					?>
				</select>
			</p>
			<?php endif; ?>
			<?php
			if ($tpl['option_arr']['payment_disable'] == 'No')
			{
				?>
				<div class="crLegend">
					<span class="crLegendLeft">&nbsp;</span>
					<span class="crLegendText"><?php echo $CR_LANG['front']['4_payment']; ?></span>
					<span class="crLegendRight">&nbsp;</span>
				</div>
				<p>
                                       <p>
                                        <?php
                                        
                                            $total = $tpl['price_info']['total']; // + $tpl['price_info']['deposit'];
                                            $deposit = $tpl['price_info']['deposit'];
                                            $tax = $tpl['price_info']['tax'];
                                        ?>
                                        <label class="crLabel">Pay Deposit Only: $<?php echo number_format($deposit, 2, '.', ''); ?><input class='payment_select' type='radio' name='payment_mod' value='<?php echo $deposit; ?>' checked></label>
                                        <label class="crLabel">Pay Entire Amount: $<?php echo number_format($total, 2, '.', ''); ?><input  class='payment_select' type='radio' name='payment_mod' value='<?php echo $total; ?>'></label>
                                        </p>
                                        
					<label class="crLabel"><?php echo $CR_LANG['front']['4_payment']; ?> <span class="crRed">*</span></label>
					<select name="payment_method" class="crSelect crW328 crRequired" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_payment'])); ?>">
						<option value=""><?php echo $CR_LANG['front']['4_select_payment']; ?></option>
						<?php 
						foreach ($CR_LANG['_payments'] as $k => $v)
						{
							if (@$tpl['option_arr']['payment_enable_' . $k] == 'Yes')
							{
								?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
							}
						}
						?>
					</select>
				</p>
				<div id="crCCData" style="display: none">
					<p>
						<label class="crLabel"><?php echo $CR_LANG['front']['4_cc_type']; ?> <span class="crRed">*</span></label>
						<select name="cc_type" class="crSelect" rev="<?php echo htmlspecialchars($CR_LANG['front']['4_v_cc_type']); ?>">
							<option value="">---</option>
							<?php
							foreach ($CR_LANG['front']['4_cc_types'] as $k => $v)
							{
								if (isset($_POST['cc_type']) && $_POST['cc_type'] == $k)
								{
									?><option value="<?php echo $k; ?>" selected="selected"><?php echo $v; ?></option><?php
								} else {
									?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
								}
							}
							?>
						</select>
					</p>
					<p>
						<label class="crLabel"><?php echo $CR_LANG['front']['4_cc_num']; ?> <span class="crRed">*</span></label>
						<input type="text" name="cc_num" class="crText" rev="<?php echo htmlspecialchars($CR_LANG['front']['4_v_cc_num']); ?>" />
					</p>
					<p>
						<label class="crLabel"><?php echo $CR_LANG['front']['4_cc_exp']; ?> <span class="crRed">*</span></label>
						<select name="cc_exp_month" class="crSelect" rev="<?php echo htmlspecialchars($CR_LANG['front']['4_v_cc_exp_month']); ?>">
							<option value="">---</option>
							<?php
							foreach ($CR_LANG['month_name'] as $key => $val)
							{
								?><option value="<?php echo $key;?>"><?php echo $val;?></option><?php
							}
							?>
						</select>
						<select name="cc_exp_year" class="crSelect" rev="<?php echo htmlspecialchars($CR_LANG['front']['4_v_cc_exp_year']); ?>">
							<option value="">---</option>
							<?php
							$y = (int) date('Y');
							for ($i = $y; $i <= $y + 10; $i++)
							{
								?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
							}
							?>
						</select>
					</p>
					<p>
						<label class="crLabel"><?php echo $CR_LANG['front']['4_cc_code']; ?> <span class="crRed">*</span></label>
						<input type="text" name="cc_code" class="crText " rev="<?php echo htmlspecialchars($CR_LANG['front']['4_v_cc_code']); ?>" />
					</p>
				</div>
				<?php
			}
			?>
			<div class="crLegend">
				<span class="crLegendLeft">&nbsp;</span>
				<span class="crLegendText"><?php echo $CR_LANG['front']['4_terms']; ?></span>
				<span class="crLegendRight">&nbsp;</span>
			</div>
			<p>
				<label class="crLabel">&nbsp;</label>
				<label><input type="checkbox" name="c_agree" checked="checked" value="1" class="crRequired" rev="<?php echo htmlspecialchars(stripslashes($CR_LANG['front']['4_v_agree'])); ?>" /> <?php echo $CR_LANG['front']['4_agree']; ?></label>
			</p>
			<p>
				<label class="crLabel">&nbsp;</label>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" id="crBtnTerms"><?php echo $CR_LANG['front']['4_click']; ?></a>
			</p>
			<p>
				<input type="button" value="<?php echo $CR_LANG['front']['btn_back']; ?>" id="crBtnBack" class="crBtn crBtnBack crFloatLeft" />				
				<input type="button" value="<?php echo $CR_LANG['front']['btn_confirm']; ?>" id="crBtnConfirm" class="crBtn crBtnConfirm crFloatRight" />
			</p>
			<p class="crError" style="display: none"></p>
		</form>
	</div>
	<div class="crBoxBottom">
		<div class="crBoxBottomLeft"></div>
		<div class="crBoxBottomRight"></div>
	</div>
</div>