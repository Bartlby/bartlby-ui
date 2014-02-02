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
/*
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
*/
	</script>
		

<div id='sys_health_base1' class='progress progress-success'>
							<div data-rel="tooltip" title="OK: <?=$plcs[prozent_float][ok]?>%" id='sys_health_progress1' class='bar' style='width: <?=$plcs[prozent_float][ok]?>%;float:left;'></div>
							<div data-rel="tooltip" title="Infos/Downtimes:<?=$plcs[prozent_float][downtimes_and_infos]?>%" id='sys_health_progress3' class='bar' style='float:left; width: <?=$plcs[prozent_float][downtimes_and_infos]?>%;background-image: -webkit-linear-gradient(top, #A8A8A8, #CFCFCF);'></div>
							<div data-rel="tooltip" title="Warnings: <?=$plcs[prozent_float][warning]?>%" id='sys_health_progress2' class='bar' style='float:left; width: <?=$plcs[prozent_float][warning]?>%;background-image: -webkit-linear-gradient(top, #ff944d, #ff6600);'></div>
							<div data-rel="tooltip" title="Critical: <?=$plcs[prozent_float][criticals]?>%"  id='sys_health_progress3' class='bar' style='float:left; width: <?=$plcs[prozent_float][criticals]?>%;background-image: -webkit-linear-gradient(top, #ee5f5b, #c43c35);'></div>
</div>
<div style='clear:both;'></div>
