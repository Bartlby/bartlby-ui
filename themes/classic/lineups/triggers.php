<!--
<?=$this->disp_box("MAIN")?>
-->
<?=$this->disp_box("mass_actions")?>





<script>
	$(document).ready(function() {
		window.trigger_list_timer = setInterval('window.triggers_table.fnDraw(false)', 20000);
	});
	</script>

<div class="panel panel-default">
  <div class="panel-body">	
<table class="table table-bordered " id='triggers_table'>
						  <thead>
							  <tr>
							  	<th><input type=checkbox id=trigger_checkbox_select_all class=icheck></th>
							  		<th>Name</th>
							  	    <th>Options</th>
								  
							  </tr>
						  </thead>
						    <tbody id=trigger_boxes_ajax>




</tbody>
</table>
</div>
</div>

<?=$this->disp_box("legend")?>
<?=$this->disp_box("UNPLACED")?>



