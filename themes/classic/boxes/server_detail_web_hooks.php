<table  width='100%' class="no-strip borderless">

	<tr>
		<td width=150 class='font2'>Web Hooks:</td>
		<td align=left ><?

			$web_hooks_a = explode("\n", $plcs[server][web_hooks]);
			echo "<ul class='list-group'>";
			
			for($web_hooks_l = 0; $web_hooks_l < count($web_hooks_a); $web_hooks_l++) {
				$web_hook = $web_hooks_a[$web_hooks_l];
				echo "<a  class='list-group-item'>" . $web_hook . "</a>";

			}
			echo "</ul>";
		?></font></td> 
		<td>&nbsp;</td>     

		
	</tr>
	
	
</table>