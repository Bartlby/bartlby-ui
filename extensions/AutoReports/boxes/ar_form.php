<?
/*
	remote_core_path TEXT,
				ssh_key TEXT,
				ssh_ip TEXT, 
				ssh_username TEXT,
				remote_ui_path TEXT,
				remote_db_name TEXT,
				remote_db_user TEXT,
				remote_db_pass TEXT, 
				remote_db_host TEXT,
				local_db_name TEXT,
				local_db_user TEXT,
				local_db_pass TEXT, 
				local_db_host TEXT,
				last_sync DATE,
				additional_folders TEXT,
				mode INTEGER,
				remote_alias TEXT
				*/
?>
<form id=fm1 name=fm1>
<div id=ar_edit_mode></div>
<input type="hidden" name="ar_edit_node_id" id=ar_edit_node_id value="">
<table>
		<tr>
			<td>
				Receipient:
			</td>
			<td>
				<input type=text name=receipient id=receipient>
			</td>
	</tr>
		<tr>
			<td>
				Service(s):
			</td>
			<td>
				<input type="hidden" value="" name="service_var" id="service_var">
				<a href="javascript:ar_GrpChk();">Open Service selector</a>
			</td>
	</tr>
	<tr>
			<td>
				Interval:
			</td>
			<td>
				<input value="1" type="checkbox" name="daily" id="daily"> Daily
				<input value="1" type="checkbox" name="weekly" id="weekly"> Weekly
				<input value="1" type="checkbox" name="monthly" id="monthly"> Monthly
				
			</td>
	</tr>

	<tr>
		<td colspan=2><input type=hidden name=ar_new_mod id=ar_new_mod value="new"><input type=button value="Save" id=ar_save_node></td>
	</tr>
				
</table>
</form>