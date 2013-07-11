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
	
			 g1 = new JustGage({
		    id: "g1",
		    value : <?=$cur_val?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 0,
		    gaugeWidthScale: 0.6,
		    label: "used MB",
		    title: "Swap"
		  });
		  
		  
		  window.gauges.push(g1);

			
	});
	</script>      

	<div id="g1" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g2" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g3" class="gauge" style='width:100px;height:100px;float:left'></div>
	
	
  
<?
$gauge_idx=count($layout->gauges);

$layout->gauges[$gauge_idx]->current_val=$cur_val;
$layout->gauges[$gauge_idx]->max_val=$max_value;





?>
