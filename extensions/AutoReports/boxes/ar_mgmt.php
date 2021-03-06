
<p>
<button  class="ar_add_new_btn btn  btn-success"   >Add New Report</button>
</p>

<table class="table table-bordered datatable" id=sm_list>
							  <thead>
								  <tr>
									  <th>Alias</th>
									  <th>Receipient</th>
									  <th>Service(s)</th>
									  <th>Interval</th>
									   <th>Last Send</th>
									   <th>Actions</th>
								  </tr>
							  </thead>

<tbody>

<?
/*
connect to DB an render list
*/
$r = $ar->db->query("select * from autoreports");


foreach($r as $row) {
	//Resolve Service
	$svc_str="<ul>";
	$ar->btl->service_list_loop(function($svc, $shm) use(&$svc_str, &$row) {

		if(strstr($row[service_var], "|" . $svc[service_id] . "=")) {
			$svc_str .= "<li><b><a href='service_detail.php?service_id=" . $svc[service_id] . "'>" . $svc[server_name] . "/" . $svc[service_name] . "</a></b></li>";
		}
	});
	$svc_str .="</ul>";

	$interval_str="<ul>";
	
	if($row[daily] == 1) {
		$interval_str .= "<li>Daily</li>";
	}
	if($row[monthly] == 1) {
		$interval_str .= "<li>Monthly</li>";
	}
	if($row[weekly] == 1) {
		$interval_str .= "<li>Weekly</li>";
	}

	$interval_str .="</ul>";


	$expl = explode(";", $row[receipient]);
	
	$rcpt_str="<ul>";
	for($x=0; $x<count($expl);  $x++) {
		$rcpt_str .= "<li>"  . $expl[$x] . "</li>";		
	}
	$rcpt_str .= "</ul>";



?>
	<tr>
		<td><?=$row[alias]?></td>
		<td><?=$rcpt_str?></td>
		<td><?=$svc_str?></td>
		<td><?=$interval_str?></td>
		<td><?=$row[last_send]?></td>
		<td>
			<button  class="ar_modify_btn btn btn-mini btn-default  fa fa-edit"  data-node-id="<?=$row[id]?>" > Edit</button>
			<button  class="ar_copy_btn btn btn-mini btn-default  fa fa-copy"  data-node-id="<?=$row[id]?>" > Copy</button>
			<button  class="ar_delete_btn btn btn-mini btn-danger  fa fa-trash"  data-node-id="<?=$row[id]?>" > Delete</button>
		</td>
		
	</tr>

<?	
}

?>


</tbody></table>