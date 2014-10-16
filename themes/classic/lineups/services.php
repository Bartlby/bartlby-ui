<!--
<?=$this->disp_box("MAIN")?>
-->
<?=$this->disp_box("mass_actions")?>



<script>
	$(document).ready(function() {
		window.service_list_timer = setInterval('window.oTable.fnDraw(false)', 20000);
	});
	</script>

<div class="table-responsive">	
	<table class="table  table-bordered " id='services_table'>
							  <thead >
								  <tr>
								  	<th><input type=checkbox class="icheck" id=service_checkbox_select_all></th>
								  	<th>Server</th>
									  <th>State</th>
									  <th>CheckTime</th>
									  
									  <th>Service</th>
									  <th>Output</th>
									  <th>Options</th>
									  
								  </tr>
							  </thead>
							    <tbody id=server_boxes_ajax>


	<?=$this->disp_box("server_box_.*")?>

	</tbody>
	</table>

</div>

<?=$this->disp_box("legend")?>
<?=$this->disp_box("UNPLACED")?>



