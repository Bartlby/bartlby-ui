
<table  width='100%' class="table no-strip borderless">
	<tbody class=" ">
	
	<tr>
		<td width=150 class='font2'>Last/Next:</td>
		<td align=left id='service_last_check' name='service_last_check'><?=date("d.m.Y H:i:s", $plcs[service][last_check])?> / <?=date("d.m.Y H:i:s", $plcs[service][last_check]+$plcs[service][check_interval])?></font></td>
		<td>&nbsp;</td>           
	</tr>

	
	<tr>
		<td width=150 class='font2'>intervall:</td>
		<td align=left ><?=$plcs[service][check_interval_original]?>ms / <?=$plcs[service][check_interval]?> s</font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Is Running?:</td>
		<td align=left id='service_currently_running' name='service_currently_running'><?= $plcs[currently_running]?></font></td>
		<td>&nbsp;</td>           
	</tr>	
	<tr>
		<td width=150 class='font2'>avg. Check Time:</td>
		<td align=left ><?= $plcs[service_ms]?> ms</font>   <b>avg. delay:</b> <?= $plcs[service_delay]?> ms</td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2' valign=top>Check Plan:</td>
		<td align=left ><?=$plcs[check_plan]?></td>
		<td>&nbsp;</td>           
	</tr>
	

	</tbody>
</table>