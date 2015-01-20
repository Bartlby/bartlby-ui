<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("action.create_package");
$layout= new Layout();

$layout= new Layout();
$layout->Form("fm1", "bartlby_action.php");
$layout->set_menu("packages");
$layout->setTitle("Package Name");

$optind=0;
//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);
$servers=array();
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out) {
	global $_GET;
	if($svc[is_gone] != 0) {
	 return LOOP_CONTINUE;
	}

	if(($_GET[dropdown_term] && @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name])) || $svc[service_id] == $_GET[service_id]) {

		


		$state=$btl->getState($svc[current_state]);
		$servers[$optind][c]="";
		$servers[$optind][v]=$svc[service_id];	
		$servers[$optind][k]=$svc[server_name] . "/" .  $svc[service_name];
		$servers[$optind][s]=1;
		$optind++;
	}
});		


$layout->FormBox(

			Array(
				0=>"Name:",
				1=>$layout->Field("package_name", "text", "") . $layout->Field("action", "hidden", "create_package")
			)
		
);
$layout->FormBox(

		array(
			0=>"Services:",
			1=>$layout->DropDown("services[]", $servers, "multiple","",false,"ajax_package_services")
		)
);

$layout->FormBox(

			Array(
				0=>"Include Plugins:",
				1=>$layout->Field("package_with_plugins", "checkbox", "checked", "", "class='icheck'")
			)
);
$layout->FormBox(

			Array(
				0=>"Include Perf Handlers:",
				1=>$layout->Field("package_with_perf", "checkbox", "checked", "", "class='icheck'")
			)
);
$layout->FormBox(

			Array(
				0=>"Overwrite existing package:",
				1=>$layout->Field("package_overwrite", "checkbox", "checked", "", "class='icheck'") . $layout->Field("Subm", "button", "next->", "" ," onClick='xajax_CreatePackage(xajax.getFormValues(\"fm1\"))'") . $layout->Field("server_id", "hidden", $_GET[server_id])
			)
);



$layout->FormEnd();
$layout->display();