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
				Remote DB Name:
			</td>
			<td>
				<input type=text name=remote_db_name id=remote_db_name>
			</td>
	</tr>
	<tr>
			<td>
				Remote DB User:
			</td>
			<td>
				<input type=text name=remote_db_user id=remote_db_user>
			</td>
	</tr>		

	<tr>
			<td>
				Remote DB Password:
			</td>
			<td>
				<input type=text name=remote_db_pass id=remote_db_pass>
			</td>
	</tr>	
	<tr>
			<td>
				Remote DB Host:
			</td>
			<td>
				<input type=text name=remote_db_host id=remote_db_host>
			</td>
	</tr>	
	<tr>
			<td>
				Local DB User:
			</td>
			<td>
				<input type=text name=local_db_user id=local_db_user>
			</td>
	</tr>	
		<tr>
			<td>
				Local DB Password:
			</td>
			<td>
				<input type=text name=local_db_pass id=local_db_pass>
			</td>
	</tr>	
		<tr>
			<td>
				Local DB Name:
			</td>
			<td>
				<input type=text name=local_db_name id=local_db_name>
			</td>
	</tr>
		<tr>
			<td>
				Local DB host:
			</td>
			<td>
				<input type=text name=local_db_host id=local_db_host>
			</td>
	</tr>
			<tr>
			<td>
				Additional-Folders - PULL:
			</td>
			<td>
				<input type=text name=additional_folders_pull id=additional_folders_pull>
			</td>
	</tr>
	<tr>
			<td>
				Additional-Folders - PUSH:
			</td>
			<td>
				<input type=text name=additional_folders_push id=additional_folders_push>
			</td>
	</tr>
				<tr>
			<td>
				Mode:
			</td>
			<td>
				<select name=mode id=mode>
					<option value='pull'>(read only) - PULL</option>
					<option value='push'>(read write) - PUSH</option>
					<option value='receiv-pull'>(read only) - RECEIVE-PULL (remote pushed)</option>
					<option value='receiv-push'>(read write) - RECEIVE-PUSH (remote pushed)</option>
				</select>
			</td>
	</tr>
	<tr>
		<td colspan=2><input type=hidden name=sm_new_mod id=sm_new_mod value="new"><input type=button value="Save" id=sm_save_node></td>
	</tr>
				
</table>
</form>