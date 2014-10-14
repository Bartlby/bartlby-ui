
<div class="row">
  

	<div class="col-md-6">
		<div id=server_detail_server_info_ajax>
		<?=$this->disp_box("server_detail_server_info")?>
		</div>
	</div>
	<div class="col-md-6">
		<div id=MAIN_ajaxs  class='fifty_float_left'>
		<?=$this->disp_box("MAIN")?>
		</div>
		<div id=service_detail_ssh_info_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_ssh_info")?>
		</div>

		<div id=service_detail_downtime_notice_ajax class='fifty_float_left'>
		<?=$this->disp_box("service_detail_downtime_notice")?>
		</div>

		<div id=server_detail_services_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_services")?>
		</div>
		<div id=server_detail_server_group_info_ajax class='fifty_float_left'>
		<?=$this->disp_box("server_detail_server_group_info")?>
		</div>


	</div>
</div>

<div style='clear: both;'/>

<?=$this->disp_box("mass_actions")?>

<table class="table  table-bordered " id='services_table'>
						  <thead>
							  <tr>
							  								   <th><input type=checkbox id=service_checkbox_select_all></th>
							  	<th>Server</th>
								  <th>State</th>
								  <th>Timinig</th>
								  
								  <th>Service</th>
								  <th>Output</th>
								  <th>Options</th>
								  	

							  </tr>
						  </thead>
						    <tbody>
<?=$this->disp_box("server_box_*")?>

</tbody>
</table>



<?=$this->disp_box("UNPLACED")?>
