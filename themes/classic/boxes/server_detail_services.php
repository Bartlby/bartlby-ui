<table  width='100%'  class="no-strip borderless">
	<tr>
		<td width=150 class='font2'>Number of services:</td>
		<td><?=$plcs[server_service_count]?></td> 
	</tr>
	<tr>
		<td width=150 class='font2' valign=top>Services: </td>
		<td> <a href='services.php?server_id=<?=$_GET[server_id]?>'>All</A><br><?=$plcs[services_assigned]?></td> 
	</tr>
	
	
</table>