
<table  width='100%' class="table no-strip borderless">
	<tbody class=" ">

	<tr>
		<td width=150 class='font2'>Baseline:</td>
		<td align=left >
			<div class="code_baseline inline" style='height: 300px; overflow: auto;font-size: 16px;'><?echo htmlspecialchars($plcs[service][baseline]);				
			?>
			</div>

		</td>
		<td>&nbsp;</td>           
	</tr>
		<tr>
		<td width=150 class='font2'>Enabled:</td>
		<td align=left ><?=$plcs[baseline_enabled]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	





	</tbody>
</table>


<script>
$(document).ready(function() {


	$('.code_baseline').each(function() {

	    var $this = $(this),
	        $code = $this.html(),
	        $unescaped = $('<div/>').html($code).text();;

	    $this.empty();

	    var myCodeMirror = CodeMirror(this, {
	        value: $unescaped,
	        mode: 'lua',
	        lineNumbers: !$this.is('.inline'),
	        readOnly: true,
	        theme: "monokai"
	    });

	});
});
</script>