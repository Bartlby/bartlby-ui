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
$instance_id_fix="";

?>

<table class='nopad' width='100%'>
		
		<tr>
			<td class='font1'>

				

				<? if($plcs[services_ok]>0) {?><a href='services.php?&expect_state=0<?=$instance_id_fix?>'><span class='label label-success'><?=$plcs[services_ok]?> OK's</A><? } ?>
				<? if($plcs[services_warning]>0) {?><a href='services.php?&expect_state=1<?=$instance_id_fix?>'><span class='label label-warning'><?=$plcs[services_warning]?> Warnings</A><? } ?>
				<? if($plcs[services_critical]>0) {?><a href='services.php?&expect_state=2<?=$instance_id_fix?>'><span class='label label-important'><?=$plcs[services_critical]?> Criticals</A><? } ?>
				<? if($plcs[services_unkown]>0) {?><a href='services.php?&expect_state=3<?=$instance_id_fix?>'><span class='label label-default'><?=$plcs[services_unkown]?>  Unkown</A><? } ?>
				<? if($plcs[services_info]>0) {?><a href='services.php?&expect_state=4<?=$instance_id_fix?>'><span class='label label-default'><?=$plcs[services_info]?> Info</A><? } ?>
				<? if($plcs[services_downtime]>0) {?><a href='services.php?&downtime=true<?=$instance_id_fix?>'><span class='label label-default'><?=$plcs[services_downtime]?> Downtime</A><? } ?>
				<? if($plcs[acks_outstanding]>0) {?><a href='services.php?&expect_state=2&acks=yes<?=$instance_id_fix?>'><span class='label label-default'><?=$plcs[acks_outstanding]?> Ack Wait</A><? } ?>
				<? if($plcs[services_handled]>0) {?><a href='services.php?handled=yes<?=$instance_id_fix?>'><span class='label label-default'><?=$plcs[services_handled]?> Handled</A><? } ?>
				<a href='services.php?expect_state=0&invert=true<?=$instance_id_fix?>'>show all failures</A>





			</td>
			
		</tr>
		
	</table>
