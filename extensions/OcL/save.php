<?
/*
storage layout

array 
	0 => array(element_info)
	2 => array(element_info)


*/
	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/OcL/OcL.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("ocl_add");
	$ocl = new OcL();
	
	
if (!$_GET[id]) {
	//add new
	$cur_entry[ocl_date]		=	$_GET[ocl_date];
	$cur_entry[ocl_subject]		=	$_GET[ocl_subject];
	$cur_entry[ocl_duration]	=	$_GET[ocl_duration];
	$cur_entry[ocl_type]		=	$_GET[ocl_type];
	$cur_entry[ocl_caller]		=	$_GET[ocl_caller];
	$cur_entry[ocl_error_long]	=	$_GET[ocl_error_long];
	$cur_entry[ocl_poster]		=	$btl->user;
	$cur_entry[ocl_service_var]	= 	$_GET[service_var];
	
	$sql = "insert into logbook (ocl_date, ocl_subject, ocl_duration, ocl_type, ocl_caller, ocl_error_long, ocl_poster, ocl_service_var) values(
			'". SQLite3::escapeString($cur_entry[ocl_date]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_subject]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_duration]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_type]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_caller]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_error_long]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_poster]) . "',
			'". SQLite3::escapeString($cur_entry[ocl_service_var]) . "'


		)";	


	$ocl->db_logbook->exec($sql);
	
	
	$layout= new Layout();
	$layout->setTitle("OcL: Add entry");
} else {
	$layout= new Layout();
	$layout->setTitle("OcL: Edit entry");


	$cur_entry[ocl_date]		=	$_GET[ocl_date];
	$cur_entry[ocl_subject]		=	$_GET[ocl_subject];
	$cur_entry[ocl_duration]	=	$_GET[ocl_duration];
	$cur_entry[ocl_type]		=	$_GET[ocl_type];
	$cur_entry[ocl_caller]		=	$_GET[ocl_caller];
	$cur_entry[ocl_error_long]	=	$_GET[ocl_error_long];
	$cur_entry[ocl_poster]		=	$btl->user;
	$cur_entry[ocl_service_var]	= 	$_GET[service_var];

	$sql  = "update logbook set ";
	$sql .= "ocl_date='" . SQLite3::escapeString($cur_entry[ocl_date]) . "', ";
	$sql .= "ocl_subject='" . SQLite3::escapeString($cur_entry[ocl_subject]) . "', ";
	$sql .= "ocl_duration='" . SQLite3::escapeString($cur_entry[ocl_duration]) . "', ";
	$sql .= "ocl_type='" . SQLite3::escapeString($cur_entry[ocl_type]) . "', ";
	$sql .= "ocl_caller='" . SQLite3::escapeString($cur_entry[ocl_caller]) . "', ";
	$sql .= "ocl_error_long='" . SQLite3::escapeString($cur_entry[ocl_error_long]) . "', ";
	$sql .= "ocl_poster='" . SQLite3::escapeString($cur_entry[ocl_poster]) . "', ";
	$sql .= "ocl_service_var='" . SQLite3::escapeString($cur_entry[ocl_service_var]) . "'";
	$sql .= " where id=" . $_GET[id];

	$ocl->db_logbook->exec($sql);
	
}
	$layout->set_menu("OcL");
	$layout->Table("100%");
	

	$layout->Tr(
		$layout->Td(
				Array(
					array("colspan" => 2, "show" => "<b>Done!</b>")
				)
			)
	
	);	
	
	$layout->TableEnd();
	$layout->display();
	
	
	
?>