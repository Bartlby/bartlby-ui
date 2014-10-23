<table  width='100%' class="no-strip borderless">
	<tr>
			<td colspan=2 align=center>
				<img src='server_icons/<?=$plcs[service][server_icon]?>' height=30>
			</td>
	</tr>
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
		<td width=150 class='font2'>Enabled ?:</td>
		<td align=left ><?=$plcs[server_enabled]?></font></td>  
		<td>&nbsp;</td>         
	</tr>

	<tr>
		<td width=150 class='font2'>Flap Seconds:</td>
		<td align=left ><?=$plcs[service][server_flap_seconds]?></font></td>  
		<td>&nbsp;</td>         
	</tr>

	<tr>
		<td width=150 class='font2'>Location:</td>
		<td align=left ><?=getGeoip(gethostbyname($plcs[service][server_ip]))?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	
	<?
	if($btl->resolveDeadMarker($plcs[service][server_dead], $plcs[map]) != "") {
	?>
		<tr>
			<td width=150 class='font2' valign=top>Server Life indicator:</td>
			<td align=left ><?=$btl->resolveDeadMarker($plcs[service][server_dead], $plcs[map])?></font></td>  
			<td>&nbsp;</td>         
		</tr>
	<?
	}
	?>

		<tr>
		<td width=150 class='font2'>Default Service Type:</td>
		<td align=left ><?= $plcs[default_service_type]?></font></td>
		<td>&nbsp;</td>           
	</tr>


	
	
	
</table>