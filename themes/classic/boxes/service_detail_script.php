
<table  width='100%' class="table no-strip borderless">
	<tbody class=" ">

	<tr>
		<td width=150 class='font2'>Script:</td>
		<td align=left >
			<div class="code inline" style='height: 300px; overflow: auto;font-size: 16px;'><?echo htmlspecialchars($plcs[service][script]);				
			?>
			</div>

		</td>
		<td>&nbsp;</td>           
	</tr>
		<tr>
		<td width=150 class='font2'>Enabled:</td>
		<td align=left ><?=$plcs[script_enabled]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	





	</tbody>
</table>


<script>
$(document).ready(function() {


	$('.code').each(function() {

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