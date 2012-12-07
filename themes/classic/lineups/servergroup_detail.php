<?=$this->disp_box("MAIN")?>
<?=$this->disp_box("servergroup_detail_servergroup_info")?>
<?=$this->disp_box("service_detail_downtime_notice")?>
<?=$this->disp_box("servergroup_detail_members")?>


<table class="table table-striped table-bordered " id='services_table'>
						  <thead>
							  <tr>
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
