<?
//Get Max Value "-c"

preg_match("/-c ([0-9]+) /", $plcs[service][plugin_arguments], $m);
$max_value=$m[1];
if(!$max_value) $max_value=10;

//Get current Value

preg_match("/, ([0-9\.]+) ([0-9\.]+) ([0-9\.]+)/", $plcs[service][new_server_text], $m);



$cur_val[0]=$m[1];
$cur_val[1]=$m[2];
$cur_val[2]=$m[2];




?>
<script>
	
	
	$(document).ready(function() {
	
			 g1 = new JustGage({
		    id: "g1",
		    value : <?=$cur_val[0]?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 0,
		    gaugeWidthScale: 0.6,
		    label: "1m",
		    title: "Load"
		  });
		   g2 = new JustGage({
		    id: "g2",
		    value : <?=$cur_val[1]?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 0,
		    gaugeWidthScale: 0.6,
		    label: "5m",
		    title: "Load"
		  });
		  g3 = new JustGage({
		    id: "g3",
		    value : <?=$cur_val[2]?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 0,
		    gaugeWidthScale: 0.6,
		    label: "15m",
		    title: "Load"
		  });
		  
		  window.gauges.push(g1);
		  window.gauges.push(g2);
		  window.gauges.push(g3);
			
	});
	</script>      

	<div id="g1" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g2" class="gauge" style='width:100px;height:100px;float:left'></div>
	<div id="g3" class="gauge" style='width:100px;height:100px;float:left'></div>
	
	
  
<?
$gauge_idx=count($layout->gauges);

$layout->gauges[$gauge_idx]->current_val=$cur_val[0];
$layout->gauges[$gauge_idx]->max_val=$max_value;
$gauge_idx++;
$layout->gauges[$gauge_idx]->current_val=$cur_val[1];
$layout->gauges[$gauge_idx]->max_val=$max_value;
$gauge_idx++;
$layout->gauges[$gauge_idx]->current_val=$cur_val[2];
$layout->gauges[$gauge_idx]->max_val=$max_value;




?>
