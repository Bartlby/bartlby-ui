<?
/*
$layout->create_box($health_title, $health_content,"system_health", array(
			'prozent_float' => $prozent_float,
			'color' => $color
		), "system_health");
*/
?>
<?
if($plcs[color] == "green") {
	
	$progress_css = "progress-success";
}
if($plcs[color] == "red") {
	
	$progress_css = "progress-danger";
}

if($plcs[color] == "yellow") {
	
	$progress_css = "progress-warning";
}

$layout->setRefreshableVariable("system_health_perc", $plcs[prozent_float]);
$layout->setRefreshableVariable("system_health_progress_css", $progress_css);

?>
	
<script>

btl_add_refreshable_object(function(data) {
	perc = btl_get_refreshable_value(data, "system_health_perc");
	cl = btl_get_refreshable_value(data, "system_health_progress_css");
	$("#sys_health_progress").css("width", parseFloat(perc) + "%");
	$("#sys_health_progress").html(parseFloat(perc) + "%");
	$("#sys_health_base").removeClass("progress-success");
	$("#sys_health_base").removeClass("progress-danger");
	$("#sys_health_base").removeClass("progress-warning");
	$("#sys_health_base").addClass(cl);
	
});
	</script>
		
		<div id="sys_health_base"  class="progress <?=$progress_css?>" >
							<div id=sys_health_progress class="bar" style="width: <?=$plcs[prozent_float]?>%;"><?=$plcs[prozent_float]?>%</div>
						</div>
