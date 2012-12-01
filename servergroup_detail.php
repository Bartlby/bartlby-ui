<?php
function dnl($i) {
	return sprintf("%02d", $i);
}

include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("main.servergroup_detail");
$layout= new Layout();
$layout->set_menu("main");
$layout->setTitle("ServerGroup");

$servergroups=$btl->GetServerGroups();
for($x=0; $x<count($servergroups); $x++) {
	if($servergroups[$x][servergroup_id] == $_GET[servergroup_id]) {
		$defaults=$servergroups[$x];
		break;	
	}
}

if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$servers=$btl->getSVCMap($btl->CFG, NULL, NULL);


if($defaults["servergroup_notify"]==1) {
	$noti_en="true";
} else {
	$noti_en="false";
}
if($defaults["servergroup_active"]==1) {
	$server_en="true";
} else {
	$server_en="false";
}


while(list($k,$v)=@each($servers)) {
		$x=$k;
		
		
		for($y=0; $y<count($v); $y++) {
			
			if(strstr($defaults[servergroup_members], "|" . $v[$y][server_id] . "|")) {
				
				$qck[$v[$y][server_id]][$v[$y][current_state]]++;	
				$qck[$v[$y][server_id]][10]=$v[$y][server_id];
				$qck[$v[$y][server_id]][server_icon]=$v[$y][server_icon];
				$qck[$v[$y][server_id]][server_name]=$v[$y][server_name];
				if($v[$y][is_downtime] == 1) {
					$qck[$v[$y][server_id]][$v[$y][current_state]]--;
					$qck[$v[$y][server_id]][downtime]++;
					
				}
			
			}
		
			
			
		
		
		}
		
		
	}


$info_box_title='ServerGroup Info';  

$layout->create_box($info_box_title, $core_content, "servergroup_detail_servergroup_info", array(
										"servergroup" => $defaults,
										"" => $isup,
										"notify_enabled" => $noti_en,
										"servergroup_enabled" => $server_en
										
										),
			"servergroup_detail_servergroup_info");




			
			$qv_title='Members';  
			$layout->create_box($qv_title, $core_content,"quick_view", array(
				'quick_view' => $qck
			), "quick_view");
	


$r=$btl->getExtensionsReturn("_servergroupDetails", $layout);

$layout->OUT .= $btl->getServerGroupOptions($defaults, $layout);





$layout->display("servergroup_detail");