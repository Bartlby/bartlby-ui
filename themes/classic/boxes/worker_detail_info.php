<table  width='100%'>
	<tr>
		<td width=150 class='font2' colspan=3>
			<div class="avatar">
				<img src='<?=$this->get_gravatar($plcs[worker][mail]);?>'>
			</div>
		</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>Name:</td>
		<td align=left ><?=$plcs[worker][name]?></font></td> 
		<td>&nbsp;</td>     
	</tr>
	<tr>
		<td width=150 class='font2'>E-Mail:</td>
		<td align=left ><?=$plcs[worker][mail]?></font></td>  
		<td>&nbsp;</td>         
	</tr>
		<tr>
		<td width=150 class='font2'>Notify Plan:</td>
		<td align=left ><?=$plcs[plan_box]?></font></td>  
		<td>&nbsp;</td>         
	</tr>

	</tr>
		<tr>
		<td width=150 class='font2'>Notify Limitation:</td>
		<td align=left ><?=$plcs[worker][escalation_limit]?> per <?=$plcs[worker][escalation_minutes]?> minutes</font></td>  
		<td>&nbsp;</td>         
	</tr>	
	<tr>
		<td width=150 class='font2'>Trigger:</td>
		<td align=left ><?=$plcs[triggers]?></font></td>  
		<td>&nbsp;</td>         
	</tr>	
	<tr>
		<td width=150 class='font2'>Levels:</td>
		<td align=left ><?=$plcs[levels]?></font></td>  
		<td>&nbsp;</td>         
	</tr>	
	
	
</table>