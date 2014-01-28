<p>
<button  class="sm_add_new_btn btn  btn-success"   >Add New Node</button>
</p>

<table class="table table-bordered table-striped table-condensed" id=sm_list>
							  <thead>
								  <tr>
									  <th>Alias</th>
									  <th>Mode</th>
									  <th>Last Sync</th>
									  <th>Last Output</th>
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
?>
	<tr>
		<td><?=$row[remote_alias]?></td>
		<td><?=$row[mode]?></td>
		<td><?=$row[last_sync]?></td>
		<td><?=$row[last_output]?></td>
		<td>
			<button  class="sm_modify_btn btn btn-mini btn-default"  data-node-id="<?=$row[id]?>" >Edit</button>
			<button  class="sm_delete_btn btn btn-mini btn-danger"  data-node-id="<?=$row[id]?>" >Delete</button>
		</td>

	</tr>

<?	
}

?>


</tbody></table>