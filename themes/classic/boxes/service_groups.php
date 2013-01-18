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
			echo  "<table width='100%'><tr><td><b><a href='servicegroup_detail.php?servicegroup_id=" . $grp[servicegroup_id] . "'>" . $grp[servicegroup_name] . "</b></td><tr><tr><td ><span class='label " . $grp[lbl] . "'>" . $grp[prozent_float] . " % OK</span></td></tr></table>";

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