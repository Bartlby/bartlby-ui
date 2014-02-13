<script>
$(document).ready(function() {
	$('.datatable_whats_on_notify').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
			"sPaginationType": "bootstrap",
			"aaSorting": [[ 1, "desc" ]],
			"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
			}
		} );
});
</script>
<div id=service_detail_service_info_ajax class='fifty_float_left'>
<table class="table table-striped table-bordered datatable_whats_on_notify"><thead>
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
</div>
<div id=service_detail_service_info_ajax class='fifty_float_left'>
<table class="table table-striped table-bordered datatable_whats_on_notify"><thead>
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
<div style='clear:both;'></div>
<table class="table table-striped table-bordered datatable_whats_on_notify"><thead>
							  <tr>

								  <th>Date</th>
								  <th>Worker</th>
								  <th>State</th>
								  <th>Service</th>
								  <th>Actions</th>
								
							  </tr>
		
				  </thead>


<?
global $btl;
while(list($k, $v) = @each($plcs[whats_on][notifications][msgs])) {
	$tsvc=bartlby_get_service_by_id($btl->RES, $k);
	//if(!$tsvc) continue;
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
</table>
