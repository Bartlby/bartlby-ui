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