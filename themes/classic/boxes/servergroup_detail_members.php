<table  width='100%'>
<tr>
<td>
Member of following ServerGroups:
</td>
</tr>
	
<?
echo "<tr><td>";
for($x=0; $x<count($plcs[server_groups]); $x++) {

	echo "<b>" . $plcs[server_groups][$x][servergroup_name] . ",</b>";
	
}
echo "</td></tr>";
echo "</table>";
?>