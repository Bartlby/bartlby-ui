
Perform Action on Selected services<br>
<button class="btn btn-default btn-xs" id="services_bulk_force"><i class="fa fa-refresh"></i>&nbsp;Force</button>&nbsp;


<button class="btn btn-default btn-xs" id="services_bulk_enable_checks" data-rel="tooltip"><i class="fa fa-check-circle"></i>&nbsp;Enable Checks</button>&nbsp;
<button class="btn btn-default btn-xs" id="services_bulk_disable_checks"><i class="fa fa-ban"></i>&nbsp;Disable Checks</button>&nbsp;
<button class="btn btn-default btn-xs" id="services_bulk_enable_notifys"><i class="fa fa-check-circle"></i>&nbsp;Enable Notifications</button>&nbsp;
<button class="btn btn-default btn-xs" id="services_bulk_disable_notifys"><i class=" fa fa-ban"></i>&nbsp;Disable Notifications</button>&nbsp;
<button class="btn btn-default btn-xs" id="services_bulk_refresh_table" onClick='window.oTable.fnDraw(false)'><i class=" fa fa-refresh"></i>&nbsp;Refresh Service Table</button>&nbsp;
<button class="btn btn-default btn-xs" id="services_bulk_edit"><i class="fa fa-edit"></i>&nbsp;Bulk Edit</button>&nbsp;


<?
//$editable_service_fields[] = array("orch_id", "Orchestra ID");
$editable_service_fields[] = array("plugin", "Plugin");
$editable_service_fields[] = array("service_name", "Service Name");
$editable_service_fields[] = array("plugin_arguments", "Plugin Arguments");
$editable_service_fields[] = array("notify_enabled", "Notifications Enabled");
$editable_service_fields[] = array("check_interval", "Interval");
$editable_service_fields[] = array("service_type", "Service Type");
$editable_service_fields[] = array("service_passive_timeout", "Passive Timeout");
$editable_service_fields[] = array("server_id", "Server");
$editable_service_fields[] = array("service_check_timeout", "Check Timeout");
$editable_service_fields[] = array("service_var", "Group Str.");
$editable_service_fields[] = array("exec_plan", "Execution Plan");
$editable_service_fields[] = array("service_ack_enabled", "Ack Enabled");
$editable_service_fields[] = array("service_retain", "Retain");
$editable_service_fields[] = array("service_active", "Check Enabled");
$editable_service_fields[] = array("flap_seconds", "Flap Seconds");
$editable_service_fields[] = array("escalate_divisor", "Escalate Divisor");
$editable_service_fields[] = array("renotify_interval", "Renotification Interval");
$editable_service_fields[] = array("fires_events", "Fires Events");
$editable_service_fields[] = array("enabled_triggers", "Enabled Triggers");
$editable_service_fields[] = array("snmp_community", "SNMP Community");
$editable_service_fields[] = array("snmp_textmatch", "SNMP Textmatch");
$editable_service_fields[] = array("snmp_objid", "SNMP OBJID");
$editable_service_fields[] = array("snmp_version", "SNMP Version");
$editable_service_fields[] = array("snmp_warning", "SNMP Warning");
$editable_service_fields[] = array("snmp_critical", "SNMP Critical");

$editable_service_fields[] = array("prio", "Priority");
$editable_service_fields[] = array("usid", "Uniq Service Identfier");
$editable_service_fields[] = array("notify_super_users", "Notify Super Users");

$editable_service_fields[] = array("snmp_type", "SNMP Type");



?>	


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Bulk Service Modify</h4>
      </div>
      <div class="modal-body" style='overflow-y:auto;height:300px;'>
        	


        	<div class="alert alert-warning" style='margin-bottom: 0px;'>WARNING beware what you are doing!!<br>
<p class=well>
	Regex Format: 
	<code>
	#(.+)disk#\1load#
	</code>
	replaces bartlby_disk with bartlby_load
</p>
				</div>
				<div id=services_bulk_output></div>
				<table width=100% border=0>
					<form id="services_bulk_form">
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
       
      	<button data-dismiss="modal"class="btn btn-primary"><i class="fa fa-close"></i> Close</button>
				
				<button id=services_bulk_edit_dry_run class="btn btn-success"><i class="fa fa-eye"></i> Dry Run</button>
				<button id=services_bulk_edit_run class="btn btn-warning"><i class="fa fa-fire"></i> Run</button>
				<button id=services_bulk_edit_delete class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>



      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




