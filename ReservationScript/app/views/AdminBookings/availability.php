<?php 
$jqDateFormat = Util::jqDateFormat($tpl['option_arr']['date_format']);
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="form">
	<input type="hidden" name="controller" value="AdminBookings" />
	<input type="hidden" name="action" value="availability" />
	<input type="hidden" name="hidden_availability" value="1" />
	<p>
		<span class="r10"><?php echo $CR_LANG['booking_select_date']; ?>:</span>
		<input type="text" name="date" id="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : date($tpl['option_arr']['date_format']); ?>" class="text w80 datepick pointer" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" />
	</p>
</form>