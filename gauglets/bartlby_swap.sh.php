<?
//Get Max Value "-c"

preg_match("/-c ([0-9]+) /", $plcs[service][plugin_arguments], $m);
$max_value=$m[1];
if(!$max_value) $max_value=10;
$max_value=100-$max_value;

//Get current Value

preg_match("/Free Swap: ([0-9]+)%/", $plcs[service][new_server_text], $m);


$cur_val = 100-$m[1];





?>
<script>
	
	
	$(document).ready(function() {
	
			 window.g<?=$plcs[service][service_id]?>AA1 = new JustGage({
		    id: "g<?=$plcs[service][service_id]?>AA1",
		    value : <?=$cur_val?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 0,
		    gaugeWidthScale: 0.6,
		    label: "used %",
		    title: "Swap"
		  });
		  
		  
		  btl_add_refreshable_object(
		 	function(data) {
		 		
		 			cur = btl_get_refreshable_value(data,"bartlby_swap.sh_<?=$plcs[service][service_id]?>_1_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_swap.sh_<?=$plcs[service][service_id]?>_1_max");
		 			window.g<?=$plcs[service][service_id]?>AA1.refresh(parseInt(cur), parseInt(max));
		 	});

			
	});
	</script>      

	<div id="g<?=$plcs[service][service_id]?>AA1" class="gauge" style='width:100px;height:100px;float:left'></div>
	
	
	
  
<?

	$layout->setRefreshableVariable("bartlby_swap.sh_" . $plcs[service][service_id] . "_1_cur", $cur_val);
	$layout->setRefreshableVariable("bartlby_swap.sh_" . $plcs[service][service_id] . "_1_max", $max_value);



?>
