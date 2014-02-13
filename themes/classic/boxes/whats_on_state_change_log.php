<script>
$(document).ready(function() {
	$('.datatable_whats_on').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
			"sPaginationType": "bootstrap",
			"aaSorting": [[ 6, "desc" ]],
			"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
			}
		} );
});
</script>
<table class="table table-striped table-bordered datatable_whats_on"><thead>
							  <tr>
								  <th>Service</th>
								  <th>Actions</th>
								  <th>State Changes</th>
								  <th>Notifications</th>
								  <th><span class="label label-success">OK Time</span></th>
								  <th><span class="label label-warning">Warning Time</span></th>
								  <th><span class="label label-important">Critical Time</span></th>
							  </tr>
		
				  </thead>


<?
global $btl, $layout;
while(list($k, $v) = @each($plcs[whats_on][services])) {
	//if($v[notifications][notifications_sent] <= 0) continue;
	$tsvc=bartlby_get_service_by_id($btl->RES, $k);
	if(!$tsvc) continue;
	if($v[notifications][notifications_sent] == "") {
		$v[notifications][notifications_sent]=0;
	}
?>

<tr>
<td><a href='service_detail.php?service_id=<?=$tsvc[service_id]?>'><?=$tsvc[server_name]?>/<?=$tsvc[service_name]?></a> </td>
<td><?=$btl->getserviceoptions($tsvc, $layout)?></td>
<td><?=$v[state_changes]?></td>
<td><?=$v[notifications][notifications_sent]?></td>
<td><?=$btl->intervall($v[0])?></td>
<td><?=$btl->intervall($v[1])?></td>
<td><?=$btl->intervall($v[2])?></td>
</tr>
<?
}
?>
</table>						 