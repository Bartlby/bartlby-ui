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
	$btl->trigger_list_loop(function($svc, $shm_place)  {
		global $_GET, $ajax_search, $btl, $ajax_total_records, $xc, $ajax_displayed_records;
		
		if($_GET[datatables_output]) {
					
					
					
					
					$ajax_total_records++;
					

					if($btl->bartlby_service_matches_string($svc, $_GET[sSearch])) {
						$ajax_displayed_records++;
						if($xc >= $_GET[iDisplayStart] && $xc <= $_GET[iDisplayStart]+$_GET[iDisplayLength]) {

							$ajax_checkbox='<div><input type=checkbox class="trigger_checkbox icheck" data-trigger_id="' . $svc[trigger_id] .  '"></div>';
							$ajax_server_options=$btl->getTriggerOptions($svc, $layout);

							
							$ajax_search["aaData"][] = array($ajax_checkbox,"<a href='trigger_detail.php?trigger_id=" . $svc[trigger_id] . "'>" . $svc[trigger_name] . "</A>",  $ajax_server_options);		//FIXME
							$ajax_search["rawService"][] = $svc;
						}
					
						$xc++;

					}

					
		}



	});
		
	$legend_content="";
	
	$layout->create_box("Bulk Trigger Edit", "", "mass_actions",
											array("a"=>"b")				
				,"trigger_list_mass_actions", false);
	
	
	
	$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 6,
					'class'=>'header1',
					'show'=>"Matching Triggers: $displayed_servers Matching Services: $displayed_services" 
					
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
	$layout->display("triggers");
	
	
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
