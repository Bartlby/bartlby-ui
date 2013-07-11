<script>
function reload_service_detail_json(id) {
		window.clearTimeout();
		//alert(1);
		
		$.getJSON('service_detail.php?json=1&service_place=' + id, function(data) {
 			console.log(data);
 			$("#MAIN_ajax").html(data.boxes.UNPLACED);
 			$("#service_detail_service_info_ajax").html(data.boxes.service_detail_service_info);
 			$("#service_detail_downtime_notice_ajax").html(data.boxes.service_detail_downtime_notice);
 			$("#service_detail_group_info_ajax").html(data.boxes.service_detail_group_info);
 			$("#service_detail_status_text_ajax").html(data.boxes.service_detail_status_text);
 			$("#service_detail_plugin_info_ajax").html(data.boxes.service_detail_plugin_info);
 			$("#service_detail_snmp_ajax").html(data.boxes.service_detail_snmp);
 			$("#service_detail_passive_ajax").html(data.boxes.service_detail_passive);
 			//$("#service_detail_manual_ajax").html(data.boxes.service_detail_manual);
 			$("#service_detail_group_check_ajax").html(data.boxes.service_detail_group_check);
 			$("#UNPLACED_ajax").html(data.boxes.UNPLACED);
 			
 			for(x=0; x<data.gauges.length; x++) {
 				window.gauges[x].maxValue=parseInt(data.gauges[x].max_val);
 				window.gauges[x].set(data.gauges[x].current_val); 				
 			}
 			
		});
		window.setTimeout("reload_service_detail_json(<?=$_GET[service_place]?>)", 2000);
	}
	window.setTimeout("reload_service_detail_json(<?=$_GET[service_place]?>)", 2000);
	
</script>



<div id=service_detail_service_info_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_service_info")?>
</div>
<div id=MAIN_ajaxs  class='fifty_float_left'>
<?=$this->disp_box("MAIN")?>
</div>
<div id=service_detail_downtime_notice_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_downtime_notice")?>
</div>
<div id=service_detail_group_info_ajax style='width:25%; float:left;'>
<?=$this->disp_box("service_detail_group_info")?>
</div>
<div id=service_detail_gauglets style='width:25%; float:left;'>
<?=$this->disp_box("service_detail_gauglets")?>
</div>
<div id=service_detail_plugin_info_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_plugin_info")?>
</div>

<div id=service_detail_manual_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_manual")?>
</div>

<div id=service_detail_passive_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_passive")?>
</div>

<div id=service_detail_group_check_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_group_check")?>
</div>

<div id=service_detail_snmp_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_snmp")?>
</div>
<div id=service_detail_status_text_ajax class='fifty_float_left'>
<?=$this->disp_box("service_detail_status_text")?>
</div>


<div style='clear: both;'/>



<div id=UNPLACED_ajax>
<?=$this->disp_box("UNPLACED")?>
</div>

