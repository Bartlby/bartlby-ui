<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();
$layout->setTemplate("nonav.html");

$layout->OUT .= "
	<script>
			function group_str_remove(f) {
				ar=f.target.id.split(\"_\");
				
				$('#grp_service_' + ar[2]).remove();	
			}
			function appl() {
			grp_str='';
				$('div[id^=\"grp_service_\"]').each(function() {
					svc_id=this.id;
					ar=svc_id.split(\"_\");
					if(parseInt(ar[2])==ar[2]) {
						svc_id=ar[2];
						svc_state=$('#sel_service_state_'+svc_id).val();
						if(svc_state >= 0)  {
							grp_str += '|' + svc_id + '=' + svc_state;
						}
									
					} 
					
				});
				grp_str +='|';
				window.opener.document.forms[\"fm1\"].service_var.value=grp_str;
				window.close();
			}
			function group_str_selected(f) {
			 new_svc_id=$(\"#grp_service_id\")[0].selectize.getValue();
			 new_svc_text=$(\"#grp_service_id\")[0].selectize.getItem($(\"#grp_service_id\")[0].selectize.getValue())[0].innerHTML;
			 drop=\"<select name='sel_service_state_\"+new_svc_id+\"' id='sel_service_state_\"+new_svc_id+\"'   data-rel='chosen'><option style='background-color: ' value='-1' >unused<option style='background-color: green' value='0' >OK<option style='background-color: orange' value='1' >Warning<option style='background-color: red' value='2' selected>Critical</select>\";
			 $(\"#sel_services\").append(\"<div id='grp_service_\" + new_svc_id + \"' name='\" + new_svc_id + \"'><table  border=0 width='100%'><tr><td>\"+ new_svc_text +\"</td><td width=100>\"+drop+\"</td><td width=10><button id='remove_service_\" + new_svc_id + \"' class='btn btn-small btn-danger'>remove</button></td></tr></table></div>\");
			 
			 $('[data-rel=\"chosen\"],[rel=\"chosen\"]').selectize({  });
			 		$('button[id^=\"remove_service_\"]').unbind();
			 	$('button[id^=\"remove_service_\"]').click(function(f) {
				group_str_remove(f);
			 });
	
			}
			//window.opener.document.forms[\"fm1\"].service_var.value='|' + fstr.substring(0, fstr.length-1) + '|';
		
		</script>
";


$layout->Table("100%");





$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 3,
					'class'=>'header',
					'show'=>'Group Definition'
					)
			)
		)

);

$x=0;
$ges=0;
$optind=0;
$servers=array();
$ibox=array();
$already_sel="";
$btl->service_list_loop(function($svc, $shm) use(&$optind, &$servers, &$btl, &$ibox, &$already_sel, &$layout) {
		global $_GET;

			//$v1=bartlby_get_service_by_id($btl->CFG, $servs[$x][service_id]);
			
			if($x == 0) {
				//$isup=$btl->isServerUp($v1[server_id]);
				//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
				$servers[$optind][c]="";
				$servers[$optind][v]="s" . $svc[server_id];	
				$servers[$optind][k]="" . $svc[server_name] . "";
				$servers[$optind][is_group]=1;
				$optind++;
			} else {
				
			}
			if($servs[$x][is_gone] != 0) {
			 continue;
			}
			$state=$btl->getState($svc[current_state]);
			
			
			
			$ibox[0][v]=-1;	
			$ibox[0][k]="unused";

			$ibox[1][c]="green";
			$ibox[1][v]=0;	
			$ibox[1][k]="OK";
			$ibox[2][c]="orange";        
			$ibox[2][v]=1;	  
			$ibox[2][k]="Warning";
			$ibox[3][c]="red";        
			$ibox[3][v]=2;	  
			$ibox[3][k]="Critical";

		
			$ibox[0][s]=1;
			$ibox[1][s]=0;
			$ibox[2][s]=0;
			$ibox[3][s]=0;
			
			
			if(preg_match("/\|" . $svc[service_id] . "=(0|1|2)\|/", $_GET[str], $m)) {

				$ibox[0][s]=0;
				$ibox[(int)$m[1]+1][s]=1;
				$st_drop = $layout->DropDown("sel_service_state_" . $svc[service_id], $ibox);
				$already_sel .= "<div id='grp_service_" . $svc[service_id] . "' name='" . $svc[service_id] . "'><table width='100%'><tr><td>" . $svc[server_name] . "/" . $svc[service_name] . "</td><td width=100>" . $st_drop . "</td><td width=10><button id='remove_service_" . $svc[service_id] . "' class=\"btn btn-small btn-danger\">remove</button></td></tr></table></div>";
				
			}
			if($_GET[dropdown_term] && @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" . $svc[service_name])) {
				$servers[$optind][c]="";
				$servers[$optind][v]=$svc[service_id];	
				$servers[$optind][k]=$svc[server_name] . "/" .  $svc[service_name];
				$optind++;
			}
			
		});



//function DropDown($name,$options=array(), $type='', $style='', $addserver=true, $custom_name='chosen') {
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Select Service",
				1=>array(
					"show"=>$layout->DropDown("grp_service_id", $servers,"","",false,  "ajax_grp_service_id"),
					'colspan'=> 2
					
					)
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>array(
					"show"=>"<b>Selected Services</b>",
					'colspan'=> 3
					)
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>array(
					"show"=>"<div>$already_sel</div><div id=sel_services></div>",
					'colspan'=> 3
					)
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>array(
					"show"=>"<input type=button class='btn btn-primary' value='Apply' onClick=appl();>",
					'colspan'=> 3,
					'class'=>'header'
					)
			)
		)

);


$layout->TableEnd();

$layout->display();