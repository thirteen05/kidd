<div id="crContainer" class="crContainer"></div>
<div id="crDialogMap" title="<?php echo $CR_LANG['front']['1_map_title']; ?>" style="display: none"></div>
<div id="crDialogTerms" title="<?php echo $CR_LANG['front']['4_terms_title']; ?>" style="display: none"></div>
<script type="text/javascript">
var myCR = CR({
	folder: "<?php echo INSTALL_FOLDER; ?>",
	validation: {
		error_dates: "<?php echo $CR_LANG['front']['1_v_err_dates']; ?>",
		error_title: "<?php echo $CR_LANG['front']['4_v_err_title']; ?>",
		error_email: "<?php echo $CR_LANG['front']['4_v_err_email']; ?>"		
	},
	message_1: "<?php echo $CR_LANG['front']['msg_1']; ?>",
	message_2: "<?php echo $CR_LANG['front']['msg_2']; ?>",
	message_3: "<?php echo $CR_LANG['front']['msg_3']; ?>",
	message_4: "<?php echo $CR_LANG['front']['msg_4']; ?>",	
	dateFormat: "<?php echo $tpl['option_arr']['date_format']; ?>",
	dayNames: ["<?php echo join('","', $CR_LANG['day_name']); ?>"],
	monthNamesFull: ["<?php echo join('","', $CR_LANG['month_name']); ?>"],
	closeButton: "<?php echo $CR_LANG['front']['1_close']; ?>"
});
</script>