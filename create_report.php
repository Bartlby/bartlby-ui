<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasright("log.report");

$ibox[0][c]="green";
$ibox[0][v]=0;
$ibox[0][s]=1;	
$ibox[0][k]="OK";
$ibox[1][c]="orange";        
$ibox[1][v]=1;	  
$ibox[1][k]="Warning";
$ibox[2][c]="red";        
$ibox[2][v]=2;	  
$ibox[2][k]="Critical";

//if(!$_GET[dropdown_term]) $_GET[dropdown_term] = "YOUNOWFIN";
//$map = $btl->GetSVCMap();
$optind=0;
//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);
$servers=array();
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out) {
	global $_GET;
	if($svc[is_gone] != 0) {
	 continue;
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


$layout= new Layout();

$layout->set_menu("report");
$layout->setTitle("Report");
$layout->Form("fm1", "report.php");
$layout->Table("100%");






$layout->Tr(
	$layout->Td(
			Array(
				0=>"Start Date:",
				1=>$layout->Field("report_start", "text", date("m/d/Y",time()-86800), "", "class='datepicker'") 
			)
		)

);


$layout->Tr(
	$layout->Td(
			Array(
				0=>"End Date:",
				1=>$layout->Field("report_end", "text", date("m/d/Y",time()), "", "class='datepicker'") 
			)
		)

);

$layout->Tr(
	$layout->Td(
			Array(
				0=>"Assume Initial State:",
				1=>$layout->DropDown("report_init", $ibox)
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Service:",
				1=>$layout->DropDown("report_service", $servers, "","",false, "ajax_report_service")
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Email RCPT:",
				1=>$layout->Field("report_rcpt", "text", "") 
			)
		)

);

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "" ," onClick='xajax_CreateReport(xajax.getFormValues(\"fm1\"))'") . $layout->Field("server_id", "hidden", $_GET[server_id])
					)
			)
		)

);


$layout->TableEnd();

$layout->FormEnd();
$layout->display();
