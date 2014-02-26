<table  width='100%'>
	<tr>
		<td width=150 class='font2'>Name:</td>
		<td align=left ><?=$plcs[service][server_name]?></font></td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Status:</td>
		<td align=left ><?=$plcs[isup]?></font></td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Ip:</td>
		<td align=left ><?=$plcs[service][server_ip]?>/<?=gethostbyname($plcs[service][server_ip])?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Port:</td>
		<td align=left ><?=$plcs[service][server_port]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Notifications?:</td>
		<td align=left ><?=$plcs[notify_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Enabled ?:</td>
		<td align=left ><?=$plcs[server_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Last notify sent:</td>
		<td align=left ><?=date("d.m.Y H:i:s", $plcs[service][last_notify_send])?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Flap Seconds:</td>
		<td align=left ><?=$plcs[service][server_flap_seconds]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Icon:</td>
		<td align=left ><img src='server_icons/<?=$plcs[service][server_icon]?>'></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Location:</td>
		<td align=left ><?=getGeoip(gethostbyname($plcs[service][server_ip]))?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	
	<tr>
		<td width=150 class='font2' valign=top>Server Life indicator:</td>
		<td align=left ><?=$btl->resolveDeadMarker($plcs[service][server_dead], $plcs[map])?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Triggers:</td>
		<td align=left ><?= $plcs[triggers]?></font></td>
		<td>&nbsp;</td>           
	</tr>
		<tr>
		<td width=150 class='font2'>Default Service Type:</td>
		<td align=left ><?= $plcs[default_service_type]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	
	
	
</table>