<?php include_once VIEWS_PATH . 'Helpers/time.widget.php'; ?>
<label class="crLabel"><?php echo $CR_LANG['front']['1_time']; ?></label>

<?php 
$start = 0;
$end = 23;
$length = 8;
if(count($tpl['arr'])){
	$time_from = explode(":",$tpl['arr'][0]['time_from']);
	$start = $time_from[0];
	
	$time_to = explode(":",$tpl['arr'][0]['time_to']);
	$end = $time_to[0];
	
        if($tpl['duration'] == '1_1'){
		$length = 8;
	}
	if($tpl['duration'] == '1_2'){
		$length = $tpl['arr'][0]['length_1_2'];
	}
        else if($tpl['duration'] == '1_4'){
		$length = $tpl['arr'][0]['length_1_4'];
	}
	else if($tpl['duration'] == '1_3'){
		$length = $tpl['arr'][0]['length_1_3'];
	}
        else if($tpl['duration'] == '1_E'){
		$length = $tpl['arr'][0]['length_1_E'];
	}
//	else $length = ($end - $start);
        else $length = 8;
}

$hf = isset($_SESSION[$controller->default_product][$controller->default_order]['hour_from']) ? $_SESSION[$controller->default_product][$controller->default_order]['hour_from'] : $start;
$mt = isset($_SESSION[$controller->default_product][$controller->default_order]['minutes_to']) ? $_SESSION[$controller->default_product][$controller->default_order]['minutes_to'] : null;

$hour_to = $length + $hf;
//if(isset($_SESSION[$controller->default_product][$controller->default_order]['duration']) &&  $_SESSION[$controller->default_product][$controller->default_order]['duration']== '1_1'){
//	$hf = $start;
//	$mt = null;
//	$hour_to = $end;
//	$length = 0;
//}

//TimeWidget::hour(null, 'hour_from', 'cr_hour_from', 'crSelect'  ); 
//TimeWidget::minute($mt, 'minutes_from', 'cr_minutes_from', 'crSelect'); 

    $hrs = range(1, 12);
    $mins = array(00, 15, 30, 45);
?>

<select id="cr_hour_from" class="crSelect" name="hour_from">
    
    <?php foreach($hrs as $hour): ?>
        <option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>
    <?php endforeach; ?>
        
</select>
<select id="cr_minutes_from" class="crSelect" name="minutes_from">
    
    <?php foreach($mins as $min): ?>
        <option value="<?php echo $min; ?>"><?php echo  str_pad($min, 2, "0", STR_PAD_LEFT); ?></option>
    <?php endforeach; ?>
    
</select>
<select id="cr_minutes_ampm" class="crSelect" name="ampm_from">
    <option value="AM">AM</option>
    <option value="PM">PM</option>
</select>
    
<input type="hidden" id="cr_hour_to" name="hour_to" value="<?php echo $hour_to ; ?>"  />