<?
	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/OcL/OcL.class.php";
	
	
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("ocl_edit");
	$ocl = new OcL();
	
	
	$layout= new Layout();
	$layout->setTitle("OcL: Add Entry");
	
	$layout->OUT .= "<script>function GrpChk() {
			window.open('grpstr.php?str='+document.fm1.service_var.value, 'grp', 'width=600, height=600, scrollbars=yes');
		}
	</script>";
	$layout->set_menu("OcL");
	$layout->Form("fm1", "extensions_wrap.php");
	$layout->Table("100%");
	
	
	
	
	//load defaults
	$sql = "select * from logbook where id=" . $_GET[id];
	$r = $ocl->db_logbook->query($sql);
	foreach($r as $row) {
		$default=$row;
	}


	$typos[0][s]=0;
	$typos[0][k]="phone";
	$typos[0][v]="phone";
	if ($defaults[ocl_type] == "phone") $typos[0][s]=1; 
	
	$typos[1][s]=0;
	$typos[1][k]="sms";
	$typos[1][v]="sms";
	if ($defaults[ocl_type] == "sms") $typos[1][s]=1;
	
	$typos[2][s]=0;
	$typos[2][k]="email";
	$typos[2][v]="email";
	if ($defaults[ocl_type] == "sms") $typos[2][s]=1;
	
	$typos[3][s]=0;
	$typos[3][k]="various";
	$typos[3][v]="various";
	if ($defaults[ocl_type] == "various") $typos[3][s]=1;
	

$layout->Tr(
	$layout->Td(
			Array(
				array("colspan" => 2, "show" => "<b>Modify OnCall-Log-Entry</b>")
			)
		)

);	


$layout->Tr(
	$layout->Td(
			Array(
				0=>"Date:",
				1=>$layout->Field("ocl_date", "text", $default[ocl_date])
			)
		)

);	
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Subject:",
				1=>$layout->Field("ocl_subject", "text",  $default[ocl_subject])
			)
		)

);	
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Duration:",
				1=>$layout->Field("ocl_duration", "text",  $default[ocl_duration])
			)
		)

);	
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Type-of-Activation:",
				1=>$layout->DropDown("ocl_type", $typos)
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Caller:",
				1=>$layout->Field("ocl_caller", "text", $default[ocl_caller])
			)
		)

);		
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Worker:",
				 $default[ocl_user]
			)
		)

);	
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Detailed description:",
				$layout->TextArea("ocl_error_long",  $default[ocl_error_long] ,10,70) . $layout->Field("script", "hidden", "OcL/save.php") . $layout->Field("identifier", "hidden", $_GET[identifier]) . $layout->Field("id", "hidden", $_GET[id])
			)
		)

);

$layout->Tr(
	$layout->Td(
		array(
			0=>"Group definition",
			
			1=>$layout->Field("service_var", "hidden", $default[ocl_service_var]) . "<a href='javascript:GrpChk();'>Open Service selector</A>"
			
		)
	)
);





$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "" ," onClick='xajax_ExtensionAjax(\"OcL\", \"xajax_ocl_add_form\", xajax.getFormValues(\"fm1\"))'")
					)
			)
		)

);
	$layout->FormEnd();
	
	$layout->TableEnd();
	$layout->display();