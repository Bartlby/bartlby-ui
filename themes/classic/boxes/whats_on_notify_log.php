<script>
$(document).ready(function() {
	$('.datatable_whats_on_notify').dataTable({
			"bSort": true,		
			"bFilter": false,	
			"bInfo" : false,
			"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
			}

		} );
	$('.datatable_whats_on_notify1').dataTable({
			"bSort": true,		
			"bFilter": false,
			"bInfo" : false,
			"aaSorting": [[ 4, "desc" ]],	
			"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
			}
		} );

	
	
});
</script>
<div class=row>
	<div class="col-sm-3">

<table class="table table-bordered datatable_whats_on_notify"><thead>
							  <tr>
								  <th>Worker</th>
								  <th>Notifications</th>
								  <th>Notifications/Real</th>
							  </tr>
		
				  </thead>


<?
global $btl;
while(list($k, $v) = @each($plcs[whats_on][notifications][worker])) {
	
?>

<tr>
<td><?=$k?></td>
<td><?=$v[0]?></td>
<td><?=$v[1]?></td>
</tr>
<?
}
?>
</table>	


</div><!--COL-->

<div class="col-sm-3">
<div id=service_detail_service_info_ajax >
<table class="table table-bordered datatable_whats_on_notify"><thead>
							  <tr>
								  <th>Trigger</th>
								  <th>Notifications</th>
								
							  </tr>
		
				  </thead>


<?
global $btl;
while(list($k, $v) = @each($plcs[whats_on][notifications][trigger])) {
	
?>

<tr>
<td><?=$k?></td>
<td><?=$v[0]?></td>

</tr>
<?
}
?>
</table>	



</div>

</div><!--COL-->


<div class=col-sm-6>

<div>
<table class="table  table-bordered datatable_whats_on_notify1" id=tb1>
	<thead>
							  <tr>

								  <th>Date</th>
								  <th>Worker</th>
								  <th>State</th>
								  <th>Service</th>
								  <th>Actions</th>
								
							  </tr>
		
				  </thead>
				  <tbody>

<?
global $btl;

for($x=0; $x<count($plcs[whats_on][notifications][msgs]); $x++) {
	$v=$plcs[whats_on][notifications][msgs][$x];

	$tsvc=bartlby_get_service_by_id($btl->RES, $v[service_id]);
	if(!$tsvc) continue;
?>

<tr>
<td><?=date("d.m.Y H:i:s", $v[date])?></td>	
<td><?=$v[to]?></td>
<td><?=$btl->getColorSpan($v[state])?></td>
<td><a href='service_detail.php?service_id=<?=$tsvc[service_id]?>'><?=$tsvc[server_name]?>/<?=$tsvc[service_name]?></a> </td>
<td><?=$btl->getserviceoptions($tsvc, $layout)?></td>


</tr>
<?
}
?>
</tbody>
</table>
</div>

</div></div>
