<?
//Get Max Value "-c"

$max_value=100;



$ar = explode("\dbr",   $plcs[service][new_server_text]);

for($x=0; $x<count($ar); $x++) {
				preg_match("/disk: (.*?) reached (.*?)%/", $ar[$x], $m);
        //if(!$m[2])continue;

				if( $part_done[$m[1]] == 1) continue;
        if($m[1] == "/dev") continue;
				if($m[1] == "/lib/init/rw") continue;

        if(count($m)<1) continue;
        $cur_val[]=$m[2];
        $cur_label[]=$m[1];
        $part_done[$m[1]]=1;
}

?>
<script>
	
	
	$(document).ready(function() {
	
			<?
			for($x=0; $x<count($cur_val); $x++) {			
			?>
			
			window.g<?=$x?> = new JustGage({
		    id: "g<?=$x?>",
		    value : <?=$cur_val[$x]?>,
		    min: 0,
		    max: <?=$max_value?>,
		    decimals: 0,
		    gaugeWidthScale: 0.6,
		    label: "%used",
		    title: "<?=$cur_label[$x]?>"
		  });
		  
		 btl_add_refreshable_object(
		 	function(data) {
		 			cur = btl_get_refreshable_value(data,"bartlby_disk.sh_<?=$plcs[service][service_id]?>_<?=$x?>_cur");
		 			max = btl_get_refreshable_value(data,"bartlby_disk.sh_<?=$plcs[service][service_id]?>_<?=$x?>_max");
		 			window.g<?=$x?>.refresh(parseInt(cur), parseInt(max));
		 	});
		 
			
			<?
			}
			?>
			
	});
	</script>      

	<?
		for($x=0; $x<count($cur_val); $x++) {
	?>
	<div id="g<?=$x?>" class="gauge" style='width:100px;height:100px;float:left'></div>
	<?
	}
	?>
	
	
  
<?


$gauge_idx=count($layout->gauges);

for($x=0; $x<count($cur_val); $x++) {
	$layout->setRefreshableVariable("bartlby_disk.sh_" . $plcs[service][service_id] . "_" . $x . "_cur", $cur_val[$x]);
	$layout->setRefreshableVariable("bartlby_disk.sh_" . $plcs[service][service_id] . "_" . $x . "_max", $max_value);
	

}



?>
