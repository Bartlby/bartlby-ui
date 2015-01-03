<!--
<?=$this->disp_box("MAIN")?>
-->
<?=$this->disp_box("mass_actions")?>



<script>
	$(document).ready(function() {
		window.trap_list_timer = setInterval('window.traps_table.fnDraw(false)', 20000);
	});
	</script>

<div class="panel panel-default">
  <div class="panel-body">	
<table class="table table-bordered " id='traps_table'>
						  <thead>
							  <tr>
							  	<th><input type=checkbox id=trap_checkbox_select_all class=icheck></th>
							  		<th>Name</th>
							  	  
								  <th>Prio</th>
								  <th>Service</th>
								  <th>Matched</th>
								  <th>Last Match</th>
								  <th>Options</th>
								  
							  </tr>
						  </thead>
						    <tbody id=trap_boxes_ajax>




</tbody>
</table>
</div>
</div>

<?=$this->disp_box("legend")?>
<?=$this->disp_box("UNPLACED")?>



