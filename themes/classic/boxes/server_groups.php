<table border=0>
<tr>



<?
$c=0;
	for($x=0; $x<count($plcs[groups]); $x++) {
		$grp = $plcs[groups][$x];

		if($grp[service_sum] == 0) {
				continue;
		}
			$bar = "<div id='sys_health_base1' class='progress progress-success' style='width: 80px;'>
							<div data-rel='tooltip' title='OK: " . $grp[prozent_float][ok] . "%' id='sys_health_progress1' class='bar' style='width: " . $grp[prozent_float][ok] . "%;float:left;'></div>
							<div data-rel='tooltip' title='Infos/Downtime: " . $grp[prozent_float][downtimes_and_infos] . "%' id='sys_health_progress3' class='bar' style='float:left; width: " . $grp[prozent_float][downtimes_and_infos] . "%;background-image: -webkit-linear-gradient(top, #A8A8A8, #CFCFCF);'></div>
							<div data-rel='tooltip' title='Warning: " . $grp[prozent_float][warning] . "%' id='sys_health_progress2' class='bar' style='float:left; width: " . $grp[prozent_float][warning] . "%;background-image: -webkit-linear-gradient(top, #ff944d, #ff6600);'></div>
							<div data-rel='tooltip' title='Critical: " . $grp[prozent_float][criticals] . "%'  id='sys_health_progress3' class='bar' style='float:left; width: " . $grp[prozent_float][criticals] . "%;background-image: -webkit-linear-gradient(top, #ee5f5b, #c43c35);'></div>
</div>
<div style='clear:both;'></div>
			";
			echo  "<td align=left valign=top width=300>";					
			echo  "<table width='100%'><tr><td><b><a href='servergroup_detail.php?servergroup_id=" . $grp[servergroup_id] . "'>" . $grp[servergroup_name] . "</b></td><tr><tr><td >" . $bar . "</td></tr></table>";

			$c++;
			
			
			
			echo  "</td>";
			if($c == 5) {
				echo "</tr><tr>";
				$c=0;	
			}
		
	}
	
		while($c < 5) {
			echo "<td>&nbsp;</td>";
			$c++;	
		}
		
?>		
</tr>
<tr>
<td colspan=4></td>
</tr>
</table>