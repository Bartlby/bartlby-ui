<!--
<?=$this->disp_box("MAIN")?>
-->
<?=$this->disp_box("mass_actions")?>

<script>
	$(document).ready(function() {
		var newtimer = setInterval('window.oTable.fnDraw(false)', 20000);
	});
	</script>
<table class="table table-striped table-bordered " id='services_table'>
						  <thead>
							  <tr>
							  	<th><input type=checkbox id=service_checkbox_select_all></th>
							  	<th>Server</th>
								  <th>State</th>
								  <th>LastCheck</th>
								  <th>NextCheck</th>
								  <th>Service</th>
								  <th>Output</th>
								  <th>Options</th>
								  
							  </tr>
						  </thead>
						    <tbody id=server_boxes_ajax>


<?=$this->disp_box("server_box_.*")?>

</tbody>
</table>

<?=$this->disp_box("legend")?>
<?=$this->disp_box("UNPLACED")?>


