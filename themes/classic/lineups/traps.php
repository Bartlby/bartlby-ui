<!--
<?=$this->disp_box("MAIN")?>
-->
<?=$this->disp_box("mass_actions")?>



<!-- Modal -->
<div class="modal fade" id="trapdataModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Last Trap Data</h4>
      </div>
      <div class="modal-body" id=audit_modal_body style='padding:0px;'>
           <div class="col-lg-12">
              
               <div id=trap_data>
              </div>
            </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default fa fa-close" data-dismiss="modal"> Close</button>
        
      </div>
    </div>
  </div>
</div>


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



