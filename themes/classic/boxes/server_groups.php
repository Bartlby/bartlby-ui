
<div class=container-fluid>
<div class="row">
<?
$c=0;
	for($x=0; $x<count($plcs[groups]); $x++) {
		$grp = $plcs[groups][$x];

		if($grp[service_sum] == 0) {
				continue;
		}
			$bar = "<div  style='width:100%'><div class='progress'>
  <div class='progress-bar progress-bar-success' style='width: " . $grp[prozent_float][ok] . "%'>
    <span class='sr-only1'>" . $grp[prozent_float][ok] . "%</span>
  </div>
  <div class='progress-bar progress-bar-warning' style='width: " . $grp[prozent_float][warning] . "%'>
    <span class='sr-only1'>" . $grp[prozent_float][warning] . "%</span>
  </div>
  <div class='progress-bar progress-bar-danger' style='width: " . $grp[prozent_float][criticals] . "%'>
    <span class='sr-only1'>" . $grp[prozent_float][criticals] . "%</span>
  </div>
    <div class='progress-bar progress-bar-danger' style='width: " . $grp[prozent_float][downtimes_and_infos] . "%'>
    <span class='sr-only1'>" . $grp[prozent_float][downtimes_and_infos] . "%</span>
  </div>
</div>
</div>
";
			//echo  "<td align=left valign=top width=300>";					
			//echo  "<table width='100%'><tr><td><b><a href='servergroup_detail.php?servergroup_id=" . $grp[servergroup_id] . "'>" . $grp[servergroup_name] . "</b></td><tr><tr><td >" . $bar . "</td></tr></table>";
			echo  "<div class=col-lg-3><b><a href='servergroup_detail.php?servergroup_id=" . $grp[servergroup_id] . "'><span style='text-overflow: ellipsis;'>" . $grp[servergroup_name] . "</span></b></A><br>" . $bar . "</div>";

			$c++;
			
			
			
			//echo  "</td>";
			if($c == 5) {
			//	echo "</tr><tr>";
				$c=0;	
			}
		
	}
	
		while($c < 5) {
			//echo "<td>&nbsp;</td>";
			$c++;	
		}
		
?>		

</div>
</div>
