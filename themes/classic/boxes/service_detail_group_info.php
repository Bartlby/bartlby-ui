<table  width='100%'>
<tr>
<td>
Member of following ServiceGroups:
</td>
</tr>
	
<?
echo "<tr><td>";
for($x=0; $x<count($plcs[service_groups]); $x++) {

	echo "<b><a href='servicegroup_detail.php?servicegroup_id=" . $plcs[service_groups][$x][servicegroup_id] . "'>" . $plcs[service_groups][$x][servicegroup_name] . "</A>,</b>";
	
}
echo "</td></tr>";
echo "</table>";
?>