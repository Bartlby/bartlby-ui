<?
//Get Max Value "-c"

preg_match("/-c ([0-9]+) /", $plcs[service][plugin_arguments], $m);
$max_value=$m[1];
if(!$max_value) $max_value=10;

//Get current Value

preg_match("/, ([0-9\.]+) ([0-9\.]+) ([0-9\.]+)/", $plcs[service][new_server_text], $m);



$cur_val[0]=$m[1];
$cur_val[1]=$m[2];
$cur_val[2]=$m[3];




?>
<script>
	
	
	$(document).ready(function() {
	
			 window.g1 = new JustGage({
		    id: "g1",
		    value : <?=$cur_val[0]?>,
		    min: 0,
		    max: <?=$max_value*2?>,
		    decimals: 2,
		    gaugeWidthScale: 0.6,
		    label: "1m",
		    title: "Load"
		  });
		   window.g2 = new JustGage({
		    id: "g2",
		    value : <?=$cur_val[1]?>,
		    min: 0,
		    max: <?=$max_value*1.5?>,
		    decimals: 2,
		    gaugeWidthScale: 0.6,
		    label: "5m",
		    title: "Load"
		  });
		 window.g3 = new JustGage({
		    id: "g3",
		    value : <?=$cur_val[2]?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 2,
		    gaugeWidthScale: 0.6,
		    label: "15m",
		    title: "Load"
		  });
		  
		  btl_add_refreshable_object(
		 	function(data) {
		 			cur = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_1_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_1_max");
		 			window.g1.refresh(parseFloat(cur), parseFloat(max));
		 	});
		 	 btl_add_refreshable_object(
		 	function(data) {
		 			cur = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_2_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_2_max");
		 			window.g2.refresh(parseFloat(cur), parseFloat(max));
		 	});
		 	 btl_add_refreshable_object(
		 	function(data) {
		 			cur = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_3_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_3_max");
		 			window.g3.refresh(parseFloat(cur), parseFloat(max));
		 	});
		 	
			
	});
	</script>      

	<div id="g1" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g2" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g3" class="gauge" style='width:100px;height:100px;float:left'></div>
	
	
  
<?
$layout->refreshable_objects["bartlby_load.sh_" . $plcs[service][service_id] . "_1_cur"]=$cur_val[0];
$layout->refreshable_objects["bartlby_load.sh_" . $plcs[service][service_id] . "_1_max"]=$max_value*2;

$layout->refreshable_objects["bartlby_load.sh_" . $plcs[service][service_id] . "_2_cur"]=$cur_val[1];
$layout->refreshable_objects["bartlby_load.sh_" . $plcs[service][service_id] . "_2_max"]=$max_value*1.5;

$layout->refreshable_objects["bartlby_load.sh_" . $plcs[service][service_id] . "_3_cur"]=$cur_val[2];
$layout->refreshable_objects["bartlby_load.sh_" . $plcs[service][service_id] . "_3_max"]=$max_value*2;





?>
