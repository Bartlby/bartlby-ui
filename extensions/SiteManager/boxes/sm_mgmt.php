<p>
<button  class="sm_add_new_btn btn  btn-success"   >Add New Node</button>
</p>

				
			
                    



<table class="table table-bordered table-condensed " id=sm_list>
							  <thead>
								  <tr>
									  <th>Alias</th>
									  <th>Mode</th>
									  <th>Last Sync</th>
									  <th>Last Output</th>
									 
									   <th>Sync/Orch Active</th>
									   <th>Flags</th>
									     <th>Action</th>
								  </tr>
							  </thead>

<tbody>

<?
/*
connect to DB an render list
*/
$r = $sm->db->query("select * from sm_remotes");

foreach($r as $row) {
$accheck="";
if($row[sync_active] == 1) {
	$accheck="checked";
}
$flags="";
if($row[node_restart_outstanding] == 1) {
	$flags .= "<li>Restart Pending";
}
if($row[node_dead] == 0){
	$flags .= "<li>ALIVE";
} else {
	$flags .= "<li>DEAD";
}
$init_file="nodes/" . $row[id] . "/node.deployed";
if(!file_exists($init_file)) {
	$flags .= "<li>Init outstanding";
}
?>
	<tr>
		<td><?=$row[remote_alias]?></td>
		<td><?=$row[mode]?></td>
		<td><?=$row[last_sync]?></td>
		<td><?=$row[last_output]?></td>
		
		<td>
			<input type=checkbox name="sm_active_sync"  class="sm_toggle_sync_btn btn btn-mini btn-danger icheck"  data-node-id="<?=$row[id]?>" <?=$accheck?> >
		</td>
		<td><?=$flags?></td>
		<td>
			<button  class="sm_modify_btn btn btn-xs btn-default"  data-node-id="<?=$row[id]?>" >Edit</button>
			<button  class="sm_copy_btn btn btn-xs btn-default"  data-node-id="<?=$row[id]?>" >Copy</button>
			<button  class="sm_restart_btn btn btn-xs btn-default"  data-node-id="<?=$row[id]?>" >Restart</button>
			<button  class="sm_delete_btn btn btn-xs btn-danger"  data-node-id="<?=$row[id]?>" >Delete</button>

		</td>
	</tr>

<?	
}

?>


</tbody></table>