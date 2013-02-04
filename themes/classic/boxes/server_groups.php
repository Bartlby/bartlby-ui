<table border=0>
<tr>



<?
$c=0;
	for($x=0; $x<count($plcs[groups]); $x++) {
		$grp = $plcs[groups][$x];
		if($grp[service_sum] == 0) {
				continue;
		}
			echo  "<td align=left valign=top width=300>";					
			echo  "<table width='100%'>";
			echo "<tr><td>";
			echo "<b><a href='servergroup_detail.php?servergroup_id=" . $grp[servergroup_id] . "'>" . $grp[servergroup_name] . "</b>";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td >";
			echo "<span class='label " . $grp[lbl] . "'>" . $grp[prozent_float] . " % OK</span><br>";
			if($grp[0] > 0) {
			echo "<span class='label label-success'><a href='services.php?servergroup_id=" . $grp[servergroup_id] . "&expect_state=0'>" . $grp[0] . "  OK</A></span>";
			}
			if($grp[1] > 0) {
			echo "&nbsp;<span class='label label-warning'><a href='services.php?servergroup_id=" . $grp[servergroup_id] . "&expect_state=1'>" . $grp[1] . "  Warning</A></span>";
			}
			if($grp[2] > 0) {
			echo "&nbsp;<span class='label label-important'><a href='services.php?servergroup_id=" . $grp[servergroup_id] . "&expect_state=2'>" . $grp[2] . "  Critical</A></span>";
			}
			echo "</td>";
			echo "</tr>";
			echo "</table>";

			$c++;
			
			
			
			echo  "</td>";
			if($c == 4) {
				echo "</tr><tr>";
				$c=0;	
			}
		
	}
	
		while($c < 4) {
			echo "<td>&nbsp;</td>";
			$c++;	
		}
		
?>		
</tr>
<tr>
<td colspan=4></td>
</tr>
</table>