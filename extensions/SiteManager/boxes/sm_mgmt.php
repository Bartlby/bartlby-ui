<table class="table table-bordered table-striped table-condensed" id=sm_list>
							  <thead>
								  <tr>
									  <th>Alias</th>
									  <th>Mode</th>
									  <th>Last Sync</th>
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
		<td>
			<button  class="sm_modify_btn btn btn-mini btn-success"  data-node-id="<?=$row[id]?>" >Edit</button>
			<button  class="sm_delete_btn btn btn-mini btn-danger"  data-node-id="<?=$row[id]?>" >Delete</button>
		</td>

	</tr>

<?	
}

?>


</tbody></table>