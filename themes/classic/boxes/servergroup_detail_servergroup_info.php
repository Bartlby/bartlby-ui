<table  width='100%' class="table no-strip borderless">
	<tr>
		<td width=150 class='font2'>Name:</td>
		<td align=left ><?=$plcs[servergroup][servergroup_name]?></font></td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Notifications?:</td>
		<td align=left ><?=$plcs[notify_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Enabled ?:</td>
		<td align=left ><?=$plcs[servergroup_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<?
		if($btl->resolveDeadMarker($plcs[servergroup_dead], $plcs[map]) != "") {

	?>
	<tr>
		<td width=150 class='font2' valign=top> Life indicator:</td>
		
		<td align=left ><?=$btl->resolveDeadMarker($plcs[servergroup_dead], $plcs[map])?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<?
		}
	?>
	<tr>
		<td width=150 class='font2'>Triggers:</td>
		<td align=left ><?= $plcs[triggers]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Orchestra ID:</td>
		<td align=left ><?= $layout->orchLable($plcs[servergroup][orch_id]) ?></font></td>
		<td>&nbsp;</td>           
	</tr>
	
</table>