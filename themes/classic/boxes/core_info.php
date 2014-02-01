<?
/*
	$layout->create_box($info_box_title, $core_content, "core_info", array(
		'user' => $btl->user,
		'time' =>  date("d.m.Y H:i:s"),
		'uptime' =>  $btl->intervall(time()-$btl->info[startup_time]),
		'services' => $info[services],
		'workers' => $info[workers],
		'downtimes' => $info[downtimes],
		'datalib' => $lib[Name],
		'datalib_version' => $lib[Version],
		'running' => $load_bar,
		'round_ms_time' => $rndMS,
		'average_delay' => $avgDEL,
		'release_name' => $btl->getRelease(),
		'reload_state' => $reload_status,
		'sirene'  => $sir

*/
		global $Bartlby_CONF_isMaster;
?>


<div style='xmin-height:150px'>
						
						<table class='nopad' width='100%'  border=0>
		<tr>
			<td class='font1'>
		<?
			if($Bartlby_CONF_isMaster) {
		?>

				(Logged in as: <font class='font2'><?=$plcs[user]?></font>)
		<?
			} else {
		?>			
			Remote Node
		<?
			}
		?>	
			</td>
			<td align=right class='font1'>Uptime:<font class='font2'><?=$plcs[uptime]?></font></td>
		</tr>
		<tr>
			<td class='font1'>Services: <font class='font2'><?=$plcs[services]?>&nbsp;<font class='font1'>Workers: <font class='font2'><?=$plcs[workers]?><font class='font1'>&nbsp;Servers: <font class='font2'><?=$plcs[servers]?></td>
			<td align=right class='font1'>Datalib:<font class='font2'><?=$plcs[datalib]?>-<?=$plcs[datalib_version]?></font></td>
		</tr>
		<?
			if($Bartlby_CONF_isMaster) {
		?>
		<tr>
			<td class='font1' colspan=1>
			Running: <?=$plcs[running]?>
			</td>
			<td align=right class='font1'>Avg Round Time:<font class='font2'><?=$plcs[round_ms_time]?> ms / <font class=font1>avg service delay:<font class=font2> <?=$plcs[average_delay]?> sec.</font></td>
		</tr>
		<?
			}
		?>
		<tr>
			<td><font class='font1'>Last-Sync: <font class='font2'><?=$plcs[last_sync]?></td>
			<td align=right class='font1' colspan=1 rxowspan=1>Checks Performed:<font class='font2'><?=$plcs[checks_performed]?> <font class=font1> Checks/s: <font class=font2><?=$plcs[checks_performed_per_sec]?></font></td>
		</tr>
		<tr>
			<td colspan=2 class='font1'>Version: <font class='font2'><?=$plcs[release_name]?></font></td>
			
		</tr>
		<tr>
			<td colspan=2 class='font1'>Reload: <font class='font2'><?=$plcs[reload_state]?></font></td>
			
		</tr>
		
	</table>
	
</div>				

