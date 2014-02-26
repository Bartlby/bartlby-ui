<script>
$(document).ready(function() {
		var state_changes_whats_on = [];
		var i=0;
		<?
			while(list($k, $v) = @each($plcs[whats_on][services][hours])) {
				if($k == "") continue;
				date_default_timezone_set('UTC');
				list($d1, $m1,$y1, $h1) = sscanf($k, "%d.%d.%d %d");
				$ts=mktime($h1,0,0,$m1,$d1,$y1);



		?>
			i++;
			state_changes_whats_on.push([<?=$ts?>*1000, <?=$v?>]);
		<?
			}
		?>
		
		
		
		$.plot($("#state_change_flotchart"), [
			{ label: "State Changes",  data: state_changes_whats_on}			
		], {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			 xaxis: { mode: "time",
			  timeformat: "%d/%m/%y %h:%M"

			},
			tooltip: true,
    		tooltipOpts: { content: "<h4>%s</h4> Value: %y.3" },

			yaxis: {
				
			},
			timezone: "browser",
			grid: {
				xbackgroundColor: { colors: ["#fff", "#eee"] },
				hoverable: true, clickable: true
			}
		});
		

});
</script>
<table width=100%>
<tr>
<td class=font1 width=150>State Changes:</td>
<td class=font2 align=left>
<?
echo $plcs[whats_on][state_changes];
?>
</td>
</tr>
</table>

<div id="state_change_flotchart" class="center" style="height:200px; width: 90%;"></div>

