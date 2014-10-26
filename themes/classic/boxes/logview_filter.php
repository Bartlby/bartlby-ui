<span class=form-horizontal>
	<?
		$handles = array(
					"MAIN",
					"CHECK",
					"NOTIFYLOG",
					"SCHED",
					"TRIGGER",
					"SHM"
					

			);
		$handle_drop = array();
		for($x=0; $x<count($handles); $x++) {
			$is_selected=0;
			if($plcs[FILTER][handle_filter] == $handles[$x]) {
				$is_selected=1;
			}

			array_push($handle_drop, array(
					k=>$handles[$x],
					v=>$handles[$x],
					s=>$is_selected
				));			
		}
		$ch_time=time();
		if($plcs["FILTER"][date_filter]) {
			$tt=explode("/",$_GET[date_filter]);
			//var_dump($tt);
			$ch_time=mktime(0,0,0,$tt[0],$tt[1],$tt[2]);	
		}
		 echo $layout->Form("fm1", "logview.php", "GET", true);
		 echo $layout->FormBox(
                                array(
                                        0=>"Text",
                                        1=>$layout->Field("text_filter", "text", $plcs["FILTER"][text_filter])
                                        )
                        ,true);
		  echo $layout->FormBox(
							array(
								0=>"Date:",
								1=>$layout->Field("date_filter", "text", date("m/d/Y",$ch_time), "", "class='datepicker'") 
								)
			, true);
		  
		  echo $layout->FormBox(
                                array(
                                        0=>"Handle",
                                        1=>$layout->DropDown("handle_filter", $handle_drop) . $layout->Field("subm", "submit", "Filter")
                                        )
                        ,true);
		  
		 
		
		  
		$svcM="";
		if($plcs[FILTER][service_id] != "") {
			$def=bartlby_get_service_by_id($btl->RES, $plcs[FILTER][service_id]);
			$svcM .="<h5>Service Filter:</h5>" . $def[server_name] . "/" . $def[service_name] . "<br>" . $btl->getServiceOptions($def, $layout) . "<a href='service_detail.php?service_id=" . $def[service_id] . "'>Detail</A>";
		}
		if($plcs[FILTER][server_id] != "") {
			$def=bartlby_get_server_by_id($btl->RES, $plcs[FILTER][server_id]);
			$svcM .="<h5>Server Filter:</h5>" . $def[server_name]  . "<br>" . $btl->getServerOptions($def, $layout) . "<a href='server_detail.php?server_id=" . $def[server_id] . "'>Detail</A>";
		}
		if($plcs[FILTER][servergroup_id] != "") {
			$def = array();
			$btl->servergroup_list_loop(function($grp, $shm) use(&$def, $plcs) {
				global $_GET;
				
				if($grp[servergroup_id] == $plcs[FILTER][servergroup_id]) {

					$def=$grp;

					return LOOP_BREAK;	
				}
			});

			
			$svcM .="<h5>Servergroup Filter:</h5>" . $def[servergroup_name]  . "<br>" . $btl->getServerGroupOptions($def, $layout) . "<a href='servergroup_detail.php?servergroup_id=" . $def[servergroup_id] . "'>Detail</A>";
		}
		if($plcs[FILTER][servicegroup_id] != "") {
			$def = array();
			$btl->servicegroup_list_loop(function($grp, $shm) use(&$def,$plcs) {
				global $_GET;
				
				if($grp[servicegroup_id] == $plcs[FILTER][servicegroup_id]) {

					$def=$grp;

					return LOOP_BREAK;	
				}
			});

			
			$svcM .="<h5>Servicegroup Filter:</h5>" . $def[servicegroup_name]  . "<br>" . $btl->getServiceGroupOptions($def, $layout) . "<a href='servicegroup_detail.php?servicegroup_id=" . $def[servicegroup_id] . "'>Detail</A>";
		}




		  if($_GET[text_filter] || $svcM != "") {
			$svcM .= "<br><br>&nbsp; <a href='logview.php' class='btn btn-danger fa fa-trash'> reset filter</A>";
		  }

		  echo $svcM;

		  echo  "<script>log_filter_query='" . http_build_query($plcs[FILTER]) . "';</script>"; 
		  echo $layout->FormEnd(true);
	?>
</span>