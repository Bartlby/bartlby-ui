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
<form id=sm_form class=form-horizontal>
<div id=sm_edit_mode></div>
<input type="hidden" name="sm_edit_node_id" id=sm_edit_node_id value="">

<?
		echo $layout->FormBox(array(
				0=>"Remote-Alias",
				1=>$layout->Field("remote_alias", "text") . "to send uptstreams, and connect to DB"
			), true);

			echo $layout->FormBox(array(
				0=>"Remote Core Path",
				1=>$layout->Field("remote_core_path", "text") . "to send uptstreams, and connect to DB"
			), true);

			echo $layout->FormBox(array(
				0=>"Remote UI Path",
				1=>$layout->Field("remote_ui_path", "text") . "to send uptstreams, and connect to DB"
			), true);

			echo $layout->FormBox(array(
				0=>"SSH Keyfile Path",
				1=>$layout->Field("ssh_key", "text") . "to send uptstreams, and connect to DB"
			), true);
			echo $layout->FormBox(array(
				0=>"SSH ip",
				1=>$layout->Field("ssh_ip", "text") . "to send uptstreams, and connect to DB"
			), true);
			echo $layout->FormBox(array(
				0=>"SSH Username",
				1=>$layout->Field("ssh_username", "text") . "to send uptstreams, and connect to DB"
			), true);


			echo $layout->FormBox(array(
				0=>"SSH Username",
				1=>$layout->Dropdown("mode", array(
						0=>array("k" => "(read only) - PULL", "v" => "pull"),
						1=>array("k" => "(read write) - PUSH", "v" => "push"),
						2=>array("k" => "(read only) - ARCH independant pull (EXPERIMENTIAL)", "v" => "arch-ind-pull"),
						3=>array("k" => "Orchestra Node", "v" => "orch-node")
						

					))
			), true);

			echo $layout->FormBox(array(
				0=>"Reload Before DB Transfer",
				1=>'<select name=reload_before_db_sync id=reload_before_db_sync>
							<option value="0" selected>Off</option>
							<option value="1">On</option>
						</select>'
			), true);



?>


