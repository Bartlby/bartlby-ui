
Perform Action on Selected Servers<br>

<button class="btn btn-mini " id="servers_bulk_refresh_table" onClick='window.servers_table.fnDraw(false)'><i class=" icon-refresh"></i>Refresh Server Table</button>&nbsp;
<button class="btn btn-mini " id="servers_bulk_edit"><i class=" icon-edit"></i>Bulk Edit</button>&nbsp;


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



?>	

<div class="modal hide fade" id="myModal" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h3>Bulk Edit Servers</h3>
			</div>
			<div class="modal-body" style='overflow-y:auto;'>
				<div class="alert alert-warning" style='margin-bottom: 0px;'>WARNING beware what you are doing!!<br>
<p class=well>
	Regex Format: 
	<code>
	#(.+)disk#\1load#
	</code>
	replaces bartlby_disk with bartlby_load
</p>
				</div>
				<div id=servers_bulk_output></div>
				<table width=100% border=0>
					<form id="servers_bulk_form">
					<?
						for($x=0; $x<count($editable_service_fields); $x++) {
					?>
					<tr>
					<td><?=$editable_service_fields[$x][1]?>:</td>
					<td>
						<input  type=text name=<?=$editable_service_fields[$x][0]?>	
					</td>
					<td>
						<select name=<?=$editable_service_fields[$x][0]?>_typefield>
							<option value="unused">unused</option>
							<option value="set">set</option>
							<option value="regex">Regex (#SEARCH#REPLACE#)</option>
							<option value="add">addition (only on int values)</option>
							<option value="sub">subtraction (only on int values)</option>

							<option value="addrand">addition random (X,Y) is from, to (only on int values)</option>
							<option value="subrand">subtraction random (X,Y) is from, to (only on int values)</option>
							<option value="toggle">toggle (only for boleans)</option>
							
						</select>
					</td>
					</tr>
					<?
						}
					?>
					</form>
				</table>
			</div>
			<div class="modal-footer">
				
				<button data-dismiss="modal"class="btn btn-success1"><i class="icon-ok"></i> Close</button>
				
				<button id=servers_bulk_edit_dry_run class="btn btn-success"><i class="icon-eye-open"></i> Dry Run</button>
				<button id=servers_bulk_edit_run class="btn btn-warning"><i class="icon-fire"></i> Run</button>
				<button id=servers_bulk_edit_delete class="btn btn-danger"><i class="icon-trash"></i> Delete</button>
			</div>
		</div>