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
<form id=fm1 name=fm1 class=form-horizontal>
<div id=ar_edit_mode class=h4></div>
<input type="hidden" name="ar_edit_node_id" id=ar_edit_node_id value="">

<?
		echo $layout->FormBox(
			array(0=>"Alias",
				  1=>$layout->Field("alias", "text")

			)
		,true);
	echo $layout->FormBox(
			array(0=>"Receipient(s)",
				  1=>$layout->Field("receipient", "text","", "","")

			)
		,true);


		echo $layout->FormBox(
			array(0=>"Services",
				  1=>$layout->Field("service_var", "hidden","") . '<a href="javascript:ar_GrpChk();" class="btn btn-primary btn-sm">Open Service selector</a>'

			)
		,true);

		echo $layout->FormBox(
			array(0=>"Intervall",
				  1=>"<label class='checkbox-inline' style='padding: 5px; font-size:14px;'><input value=1  class=icheck  type=checkbox name=daily id=daily> Daily</label>" .
				  "<label class='checkbox-inline' style='padding: 5px; font-size:14px;'><input value=1  class=icheck  type=checkbox name=weekly id=weekly> Weekly</label>" .
				  "<label class='checkbox-inline' style='padding: 5px; font-size:14px;'><input value=1  class=icheck  type=checkbox name=monthly id=monthly> Monthly</label>" .
				  '<br><input type=hidden name=ar_new_mod id=ar_new_mod value="new"><input type=button value="Save" id=ar_save_node class="btn btn-success">'
				  
			)
		,true);

?>




				
</table>
</form>