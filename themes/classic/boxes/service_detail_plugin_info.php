<table  width='100%' class="table no-strip borderless">
		<tr>
			<td width=150 class='font2'>Plugin:</td>
			<td align=left ><kbd><?=htmlspecialchars($plcs[service][plugin])?> <?=htmlspecialchars($plcs[service][plugin_arguments])?></kbd></td>
			<td>&nbsp;</td>           
		</tr>
		
		<tr>
			<td width=150 class='font2'>Plugin Timeout:</td>
			<td align=left ><?=$plcs[service][service_check_timeout]?> Seconds</font></td>
			<td>&nbsp;</td>           
		</tr>
		
	</table>