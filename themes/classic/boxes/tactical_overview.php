<?

/*
	$layout->create_box($tac_title, $tac_content, "tactical_overview",array(
		'host_sum' => $hosts_sum,
		'hosts_up' => $hosts_up,
		'hosts_down' => $hosts_down,
		'services_ok' => $services_ok,
		'services_warning' => $services_warning,
		'services_critical' => $services_critical,
		'services_downtime' => $services_downtime,
		'acks_outstanding' => $acks_outstanding
	
	), "tactical_overview");
*/

?>

<table class='nopad' width='100%'>
		<tr>
			<td colspan=2 class='font1'>Hosts:<font class='font2'><?=$plcs[host_sum]?></font></td>
			<td colspan=6 align=left class='font1'>Services:<font Fav. Services class='font2'><?=$plcs[services_sum]?></font></td>
		</tr>
		<tr>
			<td class='font1'>Up:<font class='font2'><?=$plcs[hosts_up]?></font></td>
			<td class='font1'>Down:<font class='font2'><?=$plcs[hosts_down]?></font></td>
			<td class='font1'><a href='services.php?expect_state=0'><font color=green><span class="label label-success">OK</span></font></A>:<font class='font2'><?=$plcs[services_ok]?></font></td>
			<td class='font1'><a href='services.php?expect_state=1'><font color=orange><span class="label label-warning">Warning</span></font></A>:<font class='font2'><?=$plcs[services_warning]?></font></td>
			<td class='font1'><a href='services.php?expect_state=2'><font color=red><span class="label label-important">Critical</span></font></A>:<font class='font2'><?=$plcs[services_critical]?></font></td>
			<td class='font1'><a href='services.php?expect_state=4'><font color=grey><span class="label">Info</span></font></A>:<font class='font2'><?=$plcs[services_info]?></font></td>
			<td class='font1'><a href='services.php?downtime=true'>Downtime</A>:<font class='font2'><?=$plcs[services_downtime]?></font></td>
			<td class='font1'><a href='services.php?acks=yes'>Acks</A> outstanding:<font class='font2'><?=$plcs[acks_outstanding]?></font></td>
			<td class='font1'><a href='services.php?expect_state=0&invert=true'>all failures</A></td>
		</tr>
		
	</table>