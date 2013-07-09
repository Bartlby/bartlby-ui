<script>
	function reload_overview_json() {
		window.clearTimeout();
		//alert(1);
		$.getJSON('overview.php?json=1', function(data) {
 			$("#core_info_ajax").empty();
 			$("#tactical_overview_ajax").empty();
 			$("#system_health_ajax").empty();
 			$("#server_groups_ajax").empty();
 			$("#service_groups_ajax").empty();
 			
 			
 			$("#core_info_ajax").html(data.boxes.core_info);
 			$("#tactical_overview_ajax").html(data.boxes.tactical_overview);
 			$("#system_health_ajax").html(data.boxes.system_health);
 			$("#server_groups_ajax").html(data.boxes.server_groups);
 			$("#service_groups_ajax").html(data.boxes.service_groups);
 			$("#UNPLACED_ajax").html(data.boxes.UNPLACED);
		});
		window.setTimeout("reload_overview_json()", 2000);
	}
	window.setTimeout("reload_overview_json()", 2000);
	
</script>


<div id="system_health_ajax" style='width: 50%; float:right'>
<?=$this->disp_box("system_health")?>
</div>
<div id="core_info_ajax" style='width: 50%; float:left'>
<?=$this->disp_box("core_info")?>
</div>
<div id="tactical_overview_ajax" style='width: 50%; float:right'>
<?=$this->disp_box("tactical_overview")?>
</div>






<div style='clear:both' />

<div id="server_groups_ajax">
<?=$this->disp_box("server_groups")?>
</div>
<div id="service_groups_ajax">
<?=$this->disp_box("service_groups")?>
</div>

<div id="UNPLACED_ajax">
<?=$this->disp_box("UNPLACED")?>
</div>


