<!--
<?=$this->disp_box("MAIN")?>
-->
<?=$this->disp_box("mass_actions")?>



<script>
	$(document).ready(function() {
		window.server_list_timer = setInterval('window.oTable.fnDraw(false)', 20000);
	});
	</script>
<table class="table table-striped table-bordered " id='servers_table'>
						  <thead>
							  <tr>
							  	<th><input type=checkbox id=server_checkbox_select_all></th>
							  		<th>Name</th>
							  	  <th>IP</th>
								  <th>Port</th>
								  <th>Options</th>
								  
							  </tr>
						  </thead>
						    <tbody id=server_boxes_ajax>




</tbody>
</table>


<?=$this->disp_box("legend")?>
<?=$this->disp_box("UNPLACED")?>



