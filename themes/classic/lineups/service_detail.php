<style>
#mygraph > table tbody tr td, .borderless thead tr th {
    border: none;
  
   
}
#mygraph > table {
	width: 80%;
}
</style>
<div class="row">

	<div class="col-lg-4">
<div id=MAIN_ajaxs  class='fifty_float_left'>
		<?=$this->disp_box("MAIN")?>
		</div>
		

		
		<div id=service_detail_gauglets class='fifty_float_left'>
		<?=$this->disp_box("service_detail_gauglets")?>
		</div>
		
		<div id=service_detail_passive_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_passive")?>
		</div>
		<div id=service_detail_snmp_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_snmp")?>
		</div>
		
		
		<div id=service_detail_notifications class='fifty_float_left'>
			<?=$this->disp_box("service_detail_notifications")?>
		</div>
		
		
		
		<div id=service_detail_passive_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_passive")?>
		</div>

		
		
	
			
		
		
		<div id=service_detail_timing class='fifty_float_left'>
			<?=$this->disp_box("service_detail_timing")?>
		</div>
		<div id=service_detail_downtime_notice_ajax class='fifty_float_left'>
			<?=$this->disp_box("service_detail_downtime_notice")?>
		</div>
		<div id=service_detail_group_check_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_group_check")?>
		</div>
		
		
	</div>

	<div class="col-lg-8">
		<div class=row>
			<div class=col-lg-6>
				<div id=service_detail_service_info_ajax class='fifty_float_left'>
				<?=$this->disp_box("service_detail_service_info")?>
				</div>
			
				<div id=service_detail_group_info_ajax class='fifty_float_left'>
				<?=$this->disp_box("service_detail_group_info")?>

				</div>
				<div id=extension_Basket class='fifty_float_left'>
				<?=$this->disp_box("extension_Basket")?>
				</div>
			</div>
			<div class=col-lg-6>
				<div id=service_detail_status_text_ajax class='fifty_float_left'>
					<?=$this->disp_box("service_detail_status_text")?>
				</div>
				<div id=service_detail_plugin_info_ajax class='fifty_float_left'>
				<?=$this->disp_box("service_detail_plugin_info")?>
				</div>
				<div id=service_detail_manual_ajax class='fifty_float_left'>
				<?=$this->disp_box("service_detail_manual")?>
				</div>
				<div id=service_detail_orch class='fifty_float_left'>
					<?=$this->disp_box("service_detail_orch")?>
				</div>
				
			</div>		

		</div>
		
		<div class=row>
			<div class=col-lg-12>
			
			<?=$this->disp_box("UNPLACED")?>
			</div>
		</div>	
	</div>
	
</div>




	


















