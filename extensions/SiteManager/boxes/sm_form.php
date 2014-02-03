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
<form id=sm_form>
<div id=sm_edit_mode></div>
<input type="hidden" name="sm_edit_node_id" id=sm_edit_node_id value="">
<table>
		<tr>
			<td>
				Remote-Alias:
			</td>
			<td>
				<input type=text name=remote_alias id=remote_alias>
			</td>
	</tr>
		<tr>
			<td>
				Remote Core Path:
			</td>
			<td>
				<input type=text name=remote_core_path id=remote_core_path>
			</td>
	</tr>
		<tr>
			<td>
				Remote UI Path:
			</td>
			<td>
				<input type=text name=remote_ui_path id=remote_ui_path>
			</td>
	</tr>
	<tr>
			<td>
				SSH Keyfile Path:
			</td>
			<td>
				<input type=text name=ssh_key id=ssh_key>
			</td>
	</tr>

	<tr>
			<td>
				SSH ip:
			</td>
			<td>
				<input type=text name=ssh_ip id=ssh_ip>
			</td>
	</tr>
	<tr>
			<td>
				SSH Username:
			</td>
			<td>
				<input type=text name=ssh_username id=ssh_username>
			</td>
	</tr>	
</table>

<table>
				<tr>
			<td>
				Mode:
			</td>
			<td>
				<select name=mode id=mode>
					<option value='pull'>(read only) - PULL</option>
					<option value='push'>(read write) - PUSH</option>
					<option value='arch-ind-pull'>(read only) - ARCH indend (EXPERIMENTIAL)</option>
					
				</select>
			</td>
	</tr>
	<tr>
		<td>Reload Before DB Transfer</td>
		<td>
			<select name=reload_before_db_sync id=reload_before_db_sync>
				<option value="0">Off</option>
				<option value="1">On</option>
			</select>
		</td>
	</tr>
</table>
