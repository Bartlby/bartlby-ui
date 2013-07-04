
<?=$this->disp_box("MAIN")?>
<?=$this->disp_box("mass_actions")?>
<?=$this->disp_box("legend")?>

<table class="table table-striped table-bordered " id='services_table'>
						  <thead>
							  <tr>
							  	<th>Server</th>
								  <th>State</th>
								  <th>LastCheck</th>
								  <th>NextCheck</th>
								  <th>Service</th>
								  <th>Output</th>
								  <th>Options</th>
								  <th><input type=checkbox id=service_checkbox_select_all></th>
							  </tr>
						  </thead>
						    <tbody id=server_boxes_ajax>


<?=$this->disp_box("server_box_.*")?>

</tbody>
</table>


<?=$this->disp_box("UNPLACED")?>



