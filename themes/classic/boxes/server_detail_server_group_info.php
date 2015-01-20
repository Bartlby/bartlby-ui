<table  width='100%'  class="no-strip borderless">
<tr>
<td>
Member of following ServerGroups:
</td>
</tr>
	
<?

echo "<tr><td>";
for($x=0; $x<count($plcs[server_groups]); $x++) {

	echo "<b><a href='servergroup_detail.php?servergroup_id=" . $plcs[server_groups][$x][servergroup_id] . "'>" . $plcs[server_groups][$x][servergroup_name] . "</A>,</b>";
	
}
echo "</td></tr>";
echo "</table>";
?>