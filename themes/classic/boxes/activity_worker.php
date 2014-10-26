<script>
		function updateWorkerState(id, el) {
			xajax_setWorkerState(id, el.options[el.selectedIndex].value);
			
		}
</script>

<div class=col-sm-12>
<div class=form-horizontal>

			<?
				$a=$plcs[workers];
				for($x=0; $x<count($a); $x++) {
						
						echo '<div class="form-group">
                <label class="col-sm-3 control-label">' . $a[$x][k] . '</label>
                <div class="col-sm-6">
                 
			   	 <div class="col-sm-12">
			     	' . $a[$x][opts] . '
			     </div>

			    </div>
			  </div>
                ';



                
				}
				
			
			?>
	
</div>
</div>



			