
<script>
	$(document).ready(function() {
		
	});
</script>
Perform Action on Selected Triggers<br>

<button class="btn btn-default btn-xs " id="triggers_bulk_refresh_table" onClick='window.triggers_table.fnDraw(false)'><i class=" icon-refresh"></i>Refresh Trigger Table</button>&nbsp;
<button class="btn btn-default btn-xs " id="triggers_bulk_edit"><i class=" icon-edit"></i>Bulk Edit</button>&nbsp;



<?
//$editable_service_fields[] = array("orch_id", "Orchestra ID");
$editable_service_fields[] = array("trigger_name", "Name");
$editable_service_fields[] = array("trigger_data", "Data");
$editable_service_fields[] = array("trigger_execplan", "Execplan");
$editable_service_fields[] = array("trigger_enabled", "Enabled");
$editable_service_fields[] = array("trigger_type", "Type");
$editable_service_fields[] = array("orch_id", "Orchestra ID");



?>	


<div class="modal fade" id=triggertester>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Trigger Simulator</h4>
      </div>
      <div class="modal-body">
        	
        	<form class="form-horizontal">
  <fieldset>
    
  <div class="form-group">
      <label for="triggerdata" class="col-lg-2 control-label">Data</label>
      <div class="col-lg-10">
        <textarea class="form-control" rows="3" id="triggerdata"></textarea>
        <span class="help-block">Input Data (max 4kb)</span>
      </div>
    </div>


    
  </fieldset>
</form>

<div id=triggertest_output>

</div>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id='triggertestrun'><i class="fa fa-fire"></i>Run</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade " id="myModal">
  <div class="modal-dialog full-width">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Bulk Trigger Modify</h4>
      </div>
      <div class="modal-body" style='overflow-y:auto;height:600px;'>
        	


        	<div class="alert alert-warning" style='margin-bottom: 0px;'>WARNING beware what you are doing!!<br>
<p class=xwell>
	Regex Format: 
	<code>
	#(.+)disk#\1load#
	</code>
	replaces bartlby_disk with bartlby_load
</p>
				</div>
				<form id="triggers_bulk_form">
				<div id=triggers_bulk_output></div>
				
				<?
					for($x=0; $x<count($editable_service_fields); $x++) {


				?>
					<div class=row>
						<div class=col-sm-12>

					<div class="form-group">
		                <label class="col-sm-3 control-label"><?=$editable_service_fields[$x][1]?></label>
		                <div class="col-sm-6">
		                  
		                  <select class="form-control chosen-select" data-rel="chosen" name=<?=$editable_service_fields[$x][0]?>_typefield>
							<option value="unused">unused</option>
							<option value="set">=</option>
							<option value="regex">~</option>
							<option value="add">+=</option>
							<option value="sub">-=</option>

							<option value="addrand">+=rand(x,y)</option>
							<option value="subrand">-=rand(x,y)</option>
							<option value="toggle">toggle</option>
							
						</select>
						<input  class=form-control type=text name=<?=$editable_service_fields[$x][0]?>>
		                </div>
		            </div>
		           </div>
		           </div>
				<?
					}
				?>
				
			</form>

			</div><div class="modal-footer">
       
      	<button data-dismiss="modal"class="btn btn-primary"><i class="fa fa-close"></i> Close</button>
				
				<button id=triggers_bulk_edit_dry_run class="btn btn-success"><i class="fa fa-eye"></i> Dry Run</button>
				<button id=triggers_bulk_edit_run class="btn btn-warning"><i class="fa fa-fire"></i> Run</button>
				<button id=triggers_bulk_edit_delete class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>



      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
