<div class="row">

	<div class="col-lg-4">
		<div id=MAIN_ajaxs  class='fifty_float_left'>
		<?=$this->disp_box("MAIN")?>
		</div>
		<div id=server_detail_timing_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_timing")?>
			
		</div>
		<div id=server_detail_notifications_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_notifications")?>
			
		</div>
		<div id=service_detail_ssh_info_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_ssh_info")?>
		</div>
		<div id=server_detail_services_ajax class='fifty_float_left'>
			<?=$this->disp_box("server_detail_services")?>
		</div>
		<div id=service_detail_downtime_notice_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_downtime_notice")?>
		</div>
			
		<div id=server_detail_server_group_info_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_server_group_info")?>
			
		</div>

		<div id=server_gauglets_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_gauglets")?>
			
		</div>	
		

		<div id=server_detail_orch_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_orch")?>
			
		</div>
		<div id=server_detail_orch_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_web_hooks")?>
			
		</div>
	
	</div>
	<div class=col-lg-8>
				
					<div id=server_detail_server_info_ajax>
					<?=$this->disp_box("server_detail_server_info")?>
					</div>
			
				<?=$this->disp_box("mass_actions")?>

				<div class=col-lg-12>
						<div class="panel panel-default">
  							<div class="panel-body">
								<table class="table  table-bordered " id='services_table'>
														  <thead>
															  <tr>
															  								   <th><input type=checkbox id="service_checkbox_select_all" class=icheck></th>
															  	<th>Server</th>
																  <th>State</th>
																  <th>Timinig</th>
																  
																  <th>Service</th>
																  <th>Output</th>
																  <th>Options</th>
																  	

															  </tr>
														  </thead>
														    <tbody>

								</tbody>
								</table>

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
<div class="row">


</div>