<script>
$(document).ready(function() {
		var noti_send = [];
		var i=0;
		<?
			while(list($k, $v) = @each($plcs[whats_on][notifications][hours])) {
				if($k == "") continue;
				date_default_timezone_set('UTC');
				list($d1, $m1,$y1, $h1) = sscanf($k, "%d.%d.%d %d");
				$ts=mktime($h1,0,0,$m1,$d1,$y1);



		?>
			i++;
			noti_send.push([<?=$ts?>*1000, <?=$v?>]);
		<?
			}
		?>
		
		
		
		$.plot($("#notification_flotchart"), [
			{ label: "Notifications",  data: noti_send}			
		], {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			 xaxis: { mode: "time",
			  timeformat: "%d/%m/%y %h:%M"

			},
			colors: ['#2196f3'],
			yaxis: {
				
			},
			tooltip: true,
    		tooltipOpts: { content: "<h4>%s</h4> Value: %y.3" },
			timezone: "browser",
			
			grid: {
				xbackgroundColor: { colors: ["#fff", "#eee"] },
				hoverable: true, clickable: true
			}
		});
	

});
</script>
<table width=100% class="no-border">
	<tbody class="no-border-y no-border-x">
<tr>
<td class=font1 width=150>Notifications Sent:</td>
<td class=font2 align=left>
<?
echo $plcs[whats_on][notifications_sent];
?>
</td>
</tr>
</tbody>
</table>

<div id="notification_flotchart" class="center" style="height:200px; width: 90%;"></div>

