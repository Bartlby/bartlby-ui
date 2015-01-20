<table  width='100%' class="table no-strip borderless">
	<tr>
		<td width=150 class='font2'>Name:</td>
		<td align=left ><?=$plcs[servicegroup][servicegroup_name]?></font></td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Notifications?:</td>
		<td align=left ><?=$plcs[notify_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Enabled ?:</td>
		<td align=left ><?=$plcs[servicegroup_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<?
	if($btl->resolveDeadMarker($plcs[servicegroup_dead], $plcs[map]) != "") {
	?>
	<tr>
		<td width=150 class='font2' valign=top>Servicegroup Life indicator:</td>
		
		<td align=left ><?=$btl->resolveDeadMarker($plcs[servicegroup_dead], $plcs[map])?></font></td>  
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
		<td align=left ><?= $layout->orchLable($plcs[servicegroup][orch_id])?></font></td>
		<td>&nbsp;</td>           
	</tr>
	
</table>