<table  width='100%' class="table no-strip borderless">

<?
	if($plcs[trap][trap_service_id] > 0) {
		$trap_svc = bartlby_get_service($btl->RES, $svc[service_shm_place]);
		$svc_out = "<a href='service_detail.php?service_id=" . $trap_svc[service_id] . "'>" . $trap_svc[server_name] . "/" . $trap_svc[service_name] . "</a>";
?>

	<tr>
		<td width=150 class='font2'>Service</td>
		<td align=left ><?=$svc_out?></font></td>
		<td>&nbsp;</td>           
	</tr>

<?
	}
?>

	<tr>
		<td width=150 class='font2'>Prio:</td>
		<td align=left ><?=$plcs[trap][trap_prio]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
	<tr>
		<td width=150 class='font2'>Is Final?:</td>
		<td align=left ><?=$plcs[is_final]?></font></td>  
		<td>&nbsp;</td>         
	</tr>

	<tr>
		<td width=150 class='font2'>Catcher:</td>
		<td align=left ><kbd><?=htmlentities($plcs[trap][trap_catcher])?></kbd></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Status:</td>
		<td align=left ><kbd><?=htmlentities($plcs[trap][trap_status_text])?></kbd></font></td>
		<td>&nbsp;</td>           
	</tr>	
	<tr>
		<td width=150 class='font2'><span class='label label-success'>OK</span></td>
		<td align=left ><kbd><?=htmlentities($plcs[trap][trap_status_ok])?></kbd></font></td>
		<td>&nbsp;</td>           
	</tr>	
	<tr>
		<td width=150 class='font2'><span class='label label-warning'>Warning</span></td>
		<td align=left ><kbd><?=htmlentities($plcs[trap][trap_status_warning])?></kbd></font></td>
		<td>&nbsp;</td>           
	</tr>	
<?
	if($plcs[trap][trap_fixed_status] >= 0) {

?>
	<tr>
		<td width=150 class='font2'>Fixed Status</td>
		<td align=left ><?=$btl->getColorSpan($plcs[trap][trap_fixed_status])?></font></td>
		<td>&nbsp;</td>           
	</tr>					
<?
	}
?>
	<tr>
		<td width=150 class='font2'><span class='label label-danger'>Critical</span></td>
		<td align=left ><kbd><?=htmlentities($plcs[trap][trap_status_critical])?></kbd></font></td>
		<td>&nbsp;</td>           
	</tr>	



</table>

