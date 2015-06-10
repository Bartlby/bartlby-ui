<script>
<?
global $defaults;
?>
$(document).ready(function() {
	var selectize_tags=plugin_search[0].selectize;
	selectize_tags.addOption({
        text:'<?=$defaults[plugin]?>',
        value: '<?=$defaults[plugin]?>'
    });
    selectize_tags.addItem('<?=$defaults[plugin]?>');

});
</script>
<?
echo $this->Form("fm1", "bartlby_action.php", "POST", true);
?>

<div class="row">
	<div class="col-sm-6">
		<?=$this->disp_box("basic")?>
		<?=$this->disp_box("timing")?>
		<?=$this->disp_box("baseline")?>

	</div>
	
	<div class="col-sm-6">
		<?=$this->disp_box("snmp")?>
		<?=$this->disp_box("passive")?>
		<?=$this->disp_box("group")?>

		<?=$this->disp_box("toggles")?>
		<?=$this->disp_box("active")?>
		<?=$this->disp_box("script")?>
		
		<?=$this->disp_box("orch")?>

		
		
	</div>
	

</div>

<div class="row">
	<div class=col-sm-12>
		
		
		<div id=UNPLACED_ajax>
		<?=$this->disp_box("UNPLACED")?>
		</div>
	</div>
</div>

</form>