<?
	include "layout.class.php";
	include "config.php";
	include "bartlby-ui.class.php";


	$btl=new BartlbyUi($Bartlby_CONF);
	$layout= new Layout();
	$layout->MetaRefresh(240);
	$layout->Table("100%");
	
	
	$btl->hasRight("main.servers");

	$search_result=array();
	
	$layout->set_menu("main");
	$layout->setTitle("Servers");
	
	$ajax_search = array();
	$ajax_total_records=0;
	$ajax_displayed_records=0;
	$xc = 0;
	$btl->trap_list_loop(function($svc, $shm_place)  {
		global $_GET, $ajax_search, $btl, $ajax_total_records, $xc, $ajax_displayed_records;
		
		if($_GET[datatables_output]) {
					
					
					
					
					$ajax_total_records++;
					

					if($btl->bartlby_service_matches_string($svc, $_GET[sSearch])) {
						$ajax_displayed_records++;
						if($xc >= $_GET[iDisplayStart] && $xc <= $_GET[iDisplayStart]+$_GET[iDisplayLength]) {

							$ajax_checkbox='<div><input type=checkbox class="trap_checkbox icheck" data-trap_id="' . $svc[trap_id] .  '"></div>';
							$ajax_server_options=$btl->getTrapOptions($svc, $layout) . "<button type='button' class='btn btn-sm btn-primary' onClick='xajax_showTrapData(" . $svc[trap_id] . ")'>Last Data</button>";

							$svc_out="NONE";
							if($svc[trap_service_id] > 0) {
								$trap_svc = bartlby_get_service($btl->RES, $svc[service_shm_place]);
								$svc_out = "<a href='service_detail.php?service_id=" . $trap_svc[service_id] . "'>" . $trap_svc[server_name] . "/" . $trap_svc[service_name] . "</a>";
							}

							$ajax_search["aaData"][] = array($ajax_checkbox,"<a href='trap_detail.php?trap_id=" . $svc[trap_id] . "'>" . $svc[trap_name] . "</A>",$svc[trap_prio],$svc_out,$svc[matched],$svc[trap_last_match] == 0 ? "not" : date("d.m.Y H:i:s", $svc[trap_last_match]),  $ajax_server_options);		//FIXME
							$ajax_search["rawService"][] = $svc;
						}
					
						$xc++;

					}

					
		}



	});
		
	$legend_content="";
	
	$layout->create_box("Bulk Trap Edit", "", "mass_actions",
											array("a"=>"b")				
				,"trap_list_mass_actions", false);
	
	
	
	$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 6,
					'class'=>'header1',
					'show'=>"Matching Traps: $displayed_servers Matching Services: $displayed_services" 
					
					)
			)
		)

	);	

	

	$r=$btl->getExtensionsReturn("_servers", $layout);
	
	if($_GET[datatables_output]) {
			$json_ret["iTotalRecords"] = $ajax_total_records;
			$json_ret["iTotalDisplayRecords"] = $ajax_displayed_records;
			$json_ret["sEcho"] = (int)$_GET[sEcho];
			
			//$json_ret["iTotalDisplayRecords"]=0;
			$json_ret["aaData"] = $ajax_search["aaData"];
			if($_GET[rawService]) {
				$json_ret["rawService"] = $ajax_search["rawService"];
				
			}
			
			


			
			if(!is_array($json_ret["aaData"])) {
				$json_ret["aaData"]=array();
			}
			echo json_encode(utf8_encode_all($json_ret));
			exit;
	}
	

	
	
	$layout->boxes_placed[MAIN]=true;
	$layout->TableEnd();
	$layout->display("traps");
	
	
function isInServiceGroup($svc, $group) {

	for($x=0; $x<count($svc[servicegroups]); $x++) {
				if($svc[servicegroups][$x][servicegroup_id] == $group) {
					return true;
				}

	}
	return false;
}
function isInServerGroup($svc, $group) {

	for($x=0; $x<count($svc[servergroups]); $x++) {

				if($svc[servergroups][$x][servergroup_id] == $group) {
					return true;
				}

	}
	return false;
}
?>
