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

						

<div >
						
						<table class="no-border no-strip borderless-thin">
						<tbody class="">
		<tr class="">
			<td >
		<?
			if($Bartlby_CONF_isMaster) {
		?>

				(Logged in as: <b><?=$plcs[user]?></b>)
		<?
			} else {
		?>			
			Remote Node
		<?
			}
		?>	
			</td>
			<td align=right >Uptime:<b><?=$plcs[uptime]?></b></td>
		</tr>
		<tr>
			<td >Services: <b><?=$plcs[services]?></b>&nbsp;Workers: <b><?=$plcs[workers]?></b>Servers:<b><?=$plcs[servers]?></b></td>
			<td align=right >Datalib:<b><?=$plcs[datalib]?>-<?=$plcs[datalib_version]?></b></td>
		</tr>
		<?
			if($Bartlby_CONF_isMaster) {
		?>
		<tr>
			<td  colspan=1>
			Running: <b><?=$plcs[running]?></b>
			</td>
			<td align=right >Avg Round Time:<b><?=$plcs[round_ms_time]?></b> ms / avg service delay:<b> <?=$plcs[average_delay]?> sec.</b></td>
		</tr>
		<?
			}
		?>
		<tr>
			<td>Last-Sync: <b><?=$plcs[last_sync]?></b></td>
			<td align=right>Checks Performed:<b><?=$plcs[checks_performed]?> </b> Checks/s:<b><?=$plcs[checks_performed_per_sec]?></b>
			<br>
			Notifications Waiting: <b><?=$plcs[notification_aggregation_queue]?></b>
			</td>
		</tr>
		<tr>
			<td colspan=2 >Version: <b><?=$plcs[release_name]?></b></td>
			
		</tr>
		<tr>
			<td colspan=2 >Reload: <b><?=$plcs[reload_state]?></b></td>
			
		</tr>
		</tbody>
	</table>
	
</div>				

