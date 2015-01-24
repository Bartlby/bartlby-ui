<?
//Get Max Value "-c"

preg_match("/-c ([0-9]+) /", $plcs[service][plugin_arguments], $m);
$max_value=$m[1];
if(!$max_value) $max_value=10;

//Get current Value


if(preg_match("/, ([0-9\.]+) ([0-9\.]+) ([0-9\.]+)/", $plcs[service][current_output], $m)) {
	$cur_val[0]=$m[1];
	$cur_val[1]=$m[2];
	$cur_val[2]=$m[3];
} else {
	$cur_val[0]=0;
	$cur_val[1]=0;
	$cur_val[2]=0;
}





?>
<script>
	
	
	$(document).ready(function() {
	
			 window.g<?=$plcs[service][service_id]?>AA1 = new JustGage({
		    id: "g<?=$plcs[service][service_id]?>AA1",
		    value : <?=$cur_val[0]?>,
		    min: 0,
		    max: <?=$max_value*2?>, 
		    decimals: 2,
		    gaugeWidthScale: 0.6,
		    label: "1m",
		    title: "Load"
		  });
		   window.g<?=$plcs[service][service_id]?>AA2 = new JustGage({
		    id: "g<?=$plcs[service][service_id]?>AA2",
		    value : <?=$cur_val[1]?>,
		    min: 0,
		    max: <?=$max_value*1.5?>,
		    decimals: 2,
		    gaugeWidthScale: 0.6,
		    label: "5m",
		    title: "Load"
		  });
		 window.g<?=$plcs[service][service_id]?>AA3 = new JustGage({
		    id: "g<?=$plcs[service][service_id]?>AA3",
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
		 			window.g<?=$plcs[service][service_id]?>AA1.refresh(parseFloat(cur), parseFloat(max));
		 	});
		 	 btl_add_refreshable_object(
		 	function(data) {
		 			cur = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_2_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_2_max");
		 			window.g<?=$plcs[service][service_id]?>AA2.refresh(parseFloat(cur), parseFloat(max));
		 	});
		 	 btl_add_refreshable_object(
		 	function(data) {
		 			cur = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_3_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_load.sh_<?=$plcs[service][service_id]?>_3_max");
		 			window.g<?=$plcs[service][service_id]?>AA3.refresh(parseFloat(cur), parseFloat(max));
		 	});
		 	
			
	});
	</script>      

	<div id="g<?=$plcs[service][service_id]?>AA1" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g<?=$plcs[service][service_id]?>AA2" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g<?=$plcs[service][service_id]?>AA3" class="gauge" style='width:100px;height:100px;float:left'></div>
	
	<style>
		tspan {
				font-family:Roboto, 'Helvetica Neue', Helvetica, Arial, sans-serif;
		}
	</style>
  
<?

	$layout->setRefreshableVariable("bartlby_load.sh_" . $plcs[service][service_id] . "_1_cur", $cur_val[0]);
	$layout->setRefreshableVariable("bartlby_load.sh_" . $plcs[service][service_id] . "_1_max", $max_value*2);
	
	$layout->setRefreshableVariable("bartlby_load.sh_" . $plcs[service][service_id] . "_2_cur", $cur_val[1]);
	$layout->setRefreshableVariable("bartlby_load.sh_" . $plcs[service][service_id] . "_2_cur_max", $max_value*1.5);
	
	$layout->setRefreshableVariable("bartlby_load.sh_" . $plcs[service][service_id] . "_3_cur", $cur_val[2]);
	$layout->setRefreshableVariable("bartlby_load.sh_" . $plcs[service][service_id] . "_3_max", $max_value);
	






?>
