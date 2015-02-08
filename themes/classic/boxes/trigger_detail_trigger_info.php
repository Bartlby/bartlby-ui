<table  width='100%' class="table no-strip borderless">
	<tr>
		<td width=150 class='font2'>Name:</td>
		<td align=left ><?=$plcs[trigger][trigger_name]?></font></td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Type:</td>
		<td align=left ><?=$plcs[trigger_type]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Enabled:</td>
		<td align=left ><?=$plcs[trigger_enabled]?></font></td>
		<td>&nbsp;</td>           
	</tr>
	<tr>
		<td width=150 class='font2'>Orchestra ID:</td>
		<td align=left ><?= $layout->orchLable($plcs[trap][orch_id]) ?></font></td>
		<td>&nbsp;</td>           
	</tr>
	
	<tr>
		<td width=150 class='font2'>Data:</td>
		<td align=left >
			<div class="code inline" style='height: 300px; overflow: auto;font-size: 16px;'><?echo htmlspecialchars($plcs[trigger][trigger_data]);				
			?>
			</div>

		</td>
		<td>&nbsp;</td>           
	</tr>


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