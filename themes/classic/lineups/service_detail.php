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
 			
 			
 			
		});
		window.setTimeout("reload_service_detail_json(<?=$_GET[service_place]?>)", 2000);
	}
	window.setTimeout("reload_service_detail_json(<?=$_GET[service_place]?>)", 2000);
	
</script>


<div id=MAIN_ajaxs>
<?=$this->disp_box("MAIN")?>
</div>
<div id=service_detail_service_info_ajax>
<?=$this->disp_box("service_detail_service_info")?>
</div>
<div id=service_detail_downtime_notice_ajax>
<?=$this->disp_box("service_detail_downtime_notice")?>
</div>
<div id=service_detail_group_info_ajax>
<?=$this->disp_box("service_detail_group_info")?>
</div>
<div id=service_detail_status_text_ajax>
<?=$this->disp_box("service_detail_status_text")?>
</div>
<div id=service_detail_plugin_info_ajax>
<?=$this->disp_box("service_detail_plugin_info")?>
</div>
<div id=service_detail_snmp_ajax>
<?=$this->disp_box("service_detail_snmp")?>
</div>
<div id=service_detail_passive_ajax>
<?=$this->disp_box("service_detail_passive")?>
</div>
<div id=service_detail_manual_ajax>
<?=$this->disp_box("service_detail_manual")?>
</div>
<div id=service_detail_group_check_ajax>
<?=$this->disp_box("service_detail_group_check")?>
</div>
<div id=UNPLACED_ajax>
<?=$this->disp_box("UNPLACED")?>
</div>

