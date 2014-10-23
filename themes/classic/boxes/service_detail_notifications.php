
<table  width='100%' class="table no-strip borderless">
	<tbody class=" ">

	<tr>
		<td width=150 class='font2'>Last Notify Send:</td>
		
		<td align=left id='service_last_notify_send' name='service_last_notify_send'><?=date("d.m.Y H:i:s", $plcs[service][last_notify_send])?></font></td>
		<td>&nbsp;</td>           
	</tr>
		<tr>
		<td width=150 class='font2'>Re-Notification Interval</td>
		
		<td align=left ><?=$plcs[renotify]?></font></td>
		<td>&nbsp;</td>           
	</tr>
		<tr>
		<td width=150 class='font2'>Escalate after:</td>
		
		<td align=left ><?=$plcs[escalate]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Notify Enabled:</td>
		<td align=left ><?=$plcs[notify_enabled]?> <?=$plcs[server_notifications]?></font></td>
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
		<td width=150 class='font2'>Triggers:</td>
		<td align=left ><?= $plcs[triggers]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Notify Super Users?:</td>
		<td align=left ><?=($plcs[service][notify_super_users]==1) ? "<input type=checkbox class='switch'  disabled checked>" : "<input type=checkbox class='switch'  disabled>" ?></font></td>
		<td>&nbsp;</td>           
	</tr>


	</tbody>
</table>
