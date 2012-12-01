<table  width='100%'>
<tr>
<td>
Member of following ServiceGroups:
</td>
</tr>
	
<?
echo "<tr><td>";
for($x=0; $x<count($plcs[service_groups]); $x++) {

	echo "<b>" . $plcs[service_groups][$x][servicegroup_name] . ",</b>";
	
}
echo "</td></tr>";
echo "</table>";
?>