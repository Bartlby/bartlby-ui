<?
//Get Max Value "-c"

preg_match("/-c ([0-9]+) /", $plcs[service][plugin_arguments], $m);
$max_value=$m[1];

//Get current Value

preg_match("/, ([0-9\.]+) ([0-9\.]+) ([0-9\.]+)/", $plcs[service][new_server_text], $m);

$sum = $m[1]+$m[2]+$m[3];

$cur_val = round($sum/3);




?>
<script>
	
	
	$(document).ready(function() {
		
		var opts = {
  lines: 12, // The number of lines to draw
  angle: 0.15, // The length of each line
  lineWidth: 0.44, // The line thickness
  pointer: {
    length: 0.9, // The radius of the inner circle
    strokeWidth: 0.035, // The rotation offset
    color: '#000000' // Fill color
  },
  limitMax: 'false',   // If true, the pointer will not go past the end of the gauge

  colorStart: '#6FADCF',   // Colors
  colorStop: '#8FC0DA',    // just experiment with them
  strokeColor: '#E0E0E0',   // to see which ones work best for you
  generateGradient: true
};
var target = document.getElementById('bartlby_load_canvas'); // your canvas element
var textfield = document.getElementById('bartlby_load_textfield'); // your canvas element
var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
gauge.setTextField(textfield);
gauge.maxValue = <?=$max_value?>; // set max gauge value
gauge.animationSpeed = 128; // set animation speed (32 is default value)
gauge.set(<?=$cur_val?>); // set actual value



		window.gauges.push(gauge);
		
		
		
	});
	</script>      

	<div class="gauglet_div">
  	<canvas width="220" height="70" class="gauglet_canvas" id=bartlby_load_canvas></canvas>
  	<div class="gauglet_textfield" style="font-size: 41px;" id=bartlby_load_textfield></div>
  	<div class="gauglet_textfield_bottom" style="font-size: 41px;" >Load</div>
  </div>
  
<?
$gauge_idx=count($layout->gauges);

$layout->gauges[$gauge_idx]->current_val=$cur_val;
$layout->gauges[$gauge_idx]->max_val=$max_value;
?>
