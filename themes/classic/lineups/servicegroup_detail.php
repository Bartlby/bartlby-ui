
<div id=servicegroup_detail_servicegroup_info_ajax class='fifty_float_left'>

<?=$this->disp_box("servicegroup_detail_servicegroup_info")?>
</div>
<div id=MAIN_ajaxs class='fifty_float_left' >
<?=$this->disp_box("MAIN")?>
</div>

<div id=servicegroup_detail_members_ajax class='fifty_float_left' >

<?=$this->disp_box("servicegroup_detail_members")?>
</div>


<div id=service_detail_downtime_notice_ajax class='fifty_float_left'>

<?=$this->disp_box("service_detail_downtime_notice")?>
</div>


<div style='clear:both;'/>

<?=$this->disp_box("mass_actions")?>
<table class="table table-striped table-bordered " id='services_table'>
						  <thead>
							  <tr>
							  	<th><input type=checkbox id=service_checkbox_select_all></th>
							  	<th>Server</th>
								  <th>State</th>
								  <th>LastCheck</th>
								  <th>NextCheck</th>
								  <th>Service</th>
								  <th>Output</th>
								  <th>Options</th>
								   
							  </tr>
						  </thead>
						    <tbody>
<?=$this->disp_box("server_box_*")?>

</tbody>
</table>

<?=$this->disp_box("UNPLACED")?>
