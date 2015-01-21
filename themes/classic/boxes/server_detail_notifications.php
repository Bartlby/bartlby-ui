<table  width='100%' class="no-strip borderless">
	
	<tr>
		<td width=150 class='font2'>Notifications?:</td>
		<td align=left ><?=$plcs[notify_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>

	<tr>
		<td width=150 class='font2'>Last notify sent:</td>
		<td align=left ><?=date("d.m.Y H:i:s", $plcs[service][last_notify_send])?></font></td>  
		<td>&nbsp;</td>         
	</tr>

	<tr>
		<td width=150 class='font2'>Triggers:</td>
		<td align=left ><?= $plcs[triggers]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	
	
</table>