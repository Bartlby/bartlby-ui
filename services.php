<?
	include "layout.class.php";
	include "config.php";
	include "bartlby-ui.class.php";


	$btl=new BartlbyUi($Bartlby_CONF);
	$layout= new Layout();
	$layout->MetaRefresh(240);
	$layout->Table("100%");
	
	
	$btl->hasRight("main.services");

	$search_result=array();
	
	$layout->set_menu("main");
	$layout->setTitle("Services");
	
	$ajax_search = array();
	$ajax_total_records=0;
	$ajax_displayed_records=0;
	$xc = 0;
	$btl->service_list_loop(function($svc, $shm_place)  {
		global $_GET, $ajax_search, $btl, $ajax_total_records, $xc, $ajax_displayed_records;
		$display_serv=$_GET[server_id];
		if($display_serv && $display_serv != $svc[server_id]) {
				return LOOP_CONTINUE;	
		}


		if($_GET[servergroup_id] && !isInServerGroup($svc, $_GET[servergroup_id])) {
			return LOOP_CONTINUE;	
		}
		if($_GET[servicegroup_id] && !isInServiceGroup($svc, $_GET[servicegroup_id])) {
			return LOOP_CONTINUE;	
		}
		if($_GET[service_id] != "" && $svc[service_id] != $_GET[service_id]) {
					
			return LOOP_CONTINUE;	
		}
				
				
		if($_GET[downtime] == "" && $_GET[invert] == "" && $_GET[expect_state] != "" && $svc[current_state] != $_GET[expect_state]) {
			
			return LOOP_CONTINUE;	
		}
		if($_GET[downtime] == "" &&  $_GET[invert] && $_GET[expect_state] != "" && $svc[current_state] == $_GET[expect_state] ) {
		
			return LOOP_CONTINUE;	
		}
		if($_GET[invert] && $_GET[expect_state] != "" && $svc[handled] == 1) {
			return LOOP_CONTINUE;	
		}		
		if($_GET[invert] && $_GET[expect_state] != "" && $svc[current_state] == 4) {
			return LOOP_CONTINUE;	
		}
		
		if($_GET[downtime] && $svc[is_downtime] != 1) {
			return LOOP_CONTINUE;				
		}
		if($_GET[expect_state] != "" && $svc[is_downtime] == 1) {
			return LOOP_CONTINUE;	
		}
		if($_GET[expect_state] != "" && $svc[handled] == 1) {
			return LOOP_CONTINUE;	
		}
		if(($_GET[handled] == "yes"||$_GET[handled] == true) && $svc[handled] != 1) {
			return LOOP_CONTINUE;
		}
		if($_GET[acks] == "yes" && $svc[service_ack_current] != 2) {
			return LOOP_CONTINUE;	
		}
		
		if($_GET[datatables_output]) {
					
					
					
					$svc_color=$btl->getColor($svc[current_state]);
                    $svc_state=$btl->getState($svc[current_state]);
                   

                    if($svc[service_ack_current] == 2) {
                    	$svc_state .= "<br>(ACKW)";        
                    }
                                                                
                    if($svc[is_downtime] == 1) {
                       $svc_state="Downtime";
                       $svc_color="silver";        
                    }
                    $svc[color]=$svc_color;
                    $svc[state_readable]=$svc_state;
					$ajax_lbl = "label-default";
					if($svc[color] == "green") {
							$ajax_lbl = "label-success";
					}
				
					if($svc[color] == "orange") {
							$ajax_lbl = "label-warning";
					}
					if($svc[color] == "red") {
							$ajax_lbl = "label-danger";
					}

					$ajax_total_records++;
					

					if($btl->bartlby_service_matches_string($svc, $_GET[sSearch])) {
						$ajax_displayed_records++;
						if($xc >= $_GET[iDisplayStart] && $xc <= $_GET[iDisplayStart]+$_GET[iDisplayLength]) {



							$server_ajax="<a href='server_detail.php?server_id=" . $svc[server_id] . "'><b>" . $svc[server_name]  . "</A> " . $btl->getServerOPtions($svc, $layout);
							$ajax_checkbox='<div><input type=checkbox class="service_checkbox" data-service_id="' . $svc[service_id] .  '"></div>';
							$ajax_state='<a href="services.php?expect_state=' . $svc[current_state] . '"><span class="label ' . $ajax_lbl . '">' . $svc[state_readable] . '</span></A>';
							$ajax_last_check=date("d.m.y H:i:s", $svc[last_check]);
							$ajax_next_check=date("d.m.y H:i:s", $svc[last_check]+$svc[check_interval]);
							$ajax_service_name='<a href="service_detail.php?service_place=' . $shm_place . '"><b>' . $svc[service_name] . '</A>';
							$ajax_service_output=str_replace( "\\dbr","<br>", nl2br($svc[new_server_text]));												
							$ajax_service_options=$btl->getserviceOptions($svc, $layout);
							$ajax_search["aaData"][] = array($ajax_checkbox, $server_ajax,$ajax_state , $ajax_last_check, $ajax_next_check, $ajax_service_name, $ajax_service_output, $ajax_service_options);		//FIXME
							$ajax_search["rawService"][] = $svc;
						}
					
						$xc++;

					}

					
		}



	});
		
	$legend_content="";
	
	$layout->create_box("Mass Actions", "", "mass_actions",
											array("a"=>"b")				
				,"service_list_mass_actions", false);
	
	$layout->create_box("Legend", $legend_content, "legend", array("a"=>"b"), "legend", true);
	
	
	$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 6,
					'class'=>'header1',
					'show'=>"Matching Servers: $displayed_servers Matching Services: $displayed_services" 
					
					)
			)
		)

	);	

	

	$r=$btl->getExtensionsReturn("_services", $layout);
	
	if($_GET[datatables_output]) {
			$json_ret["iTotalRecords"] = $ajax_total_records;
			$json_ret["iTotalDisplayRecords"] = $ajax_displayed_records;
			$json_ret["sEcho"] = (int)$_GET[sEcho];
			
			//$json_ret["iTotalDisplayRecords"]=0;
			$json_ret["aaData"] = $ajax_search["aaData"];
			if($_GET[rawService]) {
				$json_ret["rawService"] = $ajax_search["rawService"];
				@usort($json_ret[rawService], function($a, $b) {
					
					$a=$a[server_id];
					$b=$b[server_id];

					if ($a == $b) {
	        				return 0;
	    			}
	    			return ($a < $b) ? -1 : 1;

				});
			}
			
			@usort($json_ret[aaData], function($a, $b) {
				
				$a=$a[1];
				$b=$b[1];

				if ($a == $b) {
        				return 0;
    			}
    			return ($a < $b) ? -1 : 1;

			});



			
			if(!is_array($json_ret["aaData"])) {
				$json_ret["aaData"]=array();
			}
			echo json_encode(utf8_encode_all($json_ret));
			exit;
	}
	

	
	
	$layout->boxes_placed[MAIN]=true;
	$layout->TableEnd();
	$layout->display("services");
	
	
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
