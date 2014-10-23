
<table  width='100%' class="table no-strip borderless">
	<tbody class=" ">
	<tr>
		<td width=150 class='font2'>Server:</td>
		<td align=left ><a href='server_detail.php?server_id=<?=$plcs[service][server_id] ?>'><?=$plcs[service][server_name]?></A> ( IP: <?=gethostbyname($plcs[service][client_ip])?> Port: <?=$plcs[service][client_port]?> )</font> <?=$plcs[server_enabled]?> </td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Name:</td>
		<td align=left ><?=$plcs[service][service_name]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>ID:</td>
		<td align=left ><?=$plcs[service][service_id]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Type:</td>
		<td align=left ><?=$plcs[service_type]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Current State:</td>
		<td align=left id='service_current_state' name='service_current_state'><?=$plcs[state]?></td> 
		<td>&nbsp;</td>          
	</tr>
	<?
	if($plcs[dead_marker] != "") {
	?>
	<tr>
		<td width=150 class='font2' Valign=top>Server Life Indicator:</td>
		<td align=left><?=$plcs[dead_marker]?></td> 
		<td>&nbsp;</td>          
	</tr>
	<?
	}
	?>
	
	
	
	<tr>
		<td width=150 class='font2'>Check Enabled:</td>
		<td align=left ><?=$plcs[service_enabled]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Fires Events:</td>
		<td align=left ><?=$plcs[fires_events]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	
	
	<tr>
		<td width=150 class='font2'>Flap count:</td>
		<td align=left ><?=$plcs[service][flap_count]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Flap seconds:</td>
		<td align=left ><?=$plcs[service][flap_seconds]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Ack settings:</td>
		<td align=left ><?=$plcs[needs_ack]?></font></td>
		<td>&nbsp;</td>           
	</tr>

	<tr>
		<td width=150 class='font2'>Status:</td>
		<td align=left id='service_status' name='service_status'><?=$plcs[service][service_retain_current]?> / <?=$plcs[service][service_retain]?> </font></td>
		<td>&nbsp;</td>           
	</tr>	
	<tr>
		<td width=150 class='font2'>Last State Change:</td>
		<td align=left ><?=date("d.m.Y H:i:s",$plcs[service][last_state_change])?> (since:  <?=$btl->intervall(time()-$plcs[service][last_state_change])?>)</font></td>
		<td>&nbsp;</td>           
	</tr>	
	



	<tr>
		<td width=150 class='font2'>Problem Handled:</td>
		<td align=left ><?= $plcs[handled]?></font></td>
		<td>&nbsp;</td>           
	</tr>







	</tbody>
</table>
