
Perform Action on Selected Servers<br>

<button class="btn btn-default btn-xs " id="servers_bulk_refresh_table" onClick='window.servers_table.fnDraw(false)'><i class=" icon-refresh"></i>Refresh Server Table</button>&nbsp;
<button class="btn btn-default btn-xs " id="servers_bulk_edit"><i class=" icon-edit"></i>Bulk Edit</button>&nbsp;


<?
//$editable_service_fields[] = array("orch_id", "Orchestra ID");
$editable_service_fields[] = array("server_name", "Name");
$editable_service_fields[] = array("server_ip", "Server IP");
$editable_service_fields[] = array("server_ssh_keyfile", "SSH Keyfile");
$editable_service_fields[] = array("server_ssh_passphrase", "SSH Key Passphrase");
$editable_service_fields[] = array("server_ssh_username", "SSH Key Username");
$editable_service_fields[] = array("enabled_triggers", "Enabled Triggers");
$editable_service_fields[] = array("default_service_type", "Default Service Type");
$editable_service_fields[] = array("server_icon", "Server Icon");
$editable_service_fields[] = array("server_port", "Server Port");
$editable_service_fields[] = array("server_enabled", "Enabled");
$editable_service_fields[] = array("server_notify", "Notifications Enabled");
$editable_service_fields[] = array("server_flap_seconds", "Flap Seconds");
$editable_service_fields[] = array("exec_plan", "Execution Plan");
$editable_service_fields[] = array("orch_id", "Orchestra ID");
$editable_service_fields[] = array("json_endpoint", "JSON Endpoint");
$editable_service_fields[] = array("web_hooks", "Web hooks");
$editable_service_fields[] = array("web_hooks_level", "Web Hooks Level");



?>	

<div class="modal fade " id="myModal">
  <div class="modal-dialog full-width">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Bulk Service Modify</h4>
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
				<form id="servers_bulk_form">
				<div id=servers_bulk_output></div>
				
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
				
				<button id=servers_bulk_edit_dry_run class="btn btn-success"><i class="fa fa-eye"></i> Dry Run</button>
				<button id=servers_bulk_edit_run class="btn btn-warning"><i class="fa fa-fire"></i> Run</button>
				<button id=servers_bulk_edit_delete class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>



      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
