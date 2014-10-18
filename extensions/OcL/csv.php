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
	$btl->hasRight("ocl_csv");
	$ocl = new OcL();
	Header("Content-Type: text/plain");
	
	
	
	
	$edate=time();
	if($_GET[edate]) $edate=$_GET[edate];
	
	
	$nday=$edate+(86400*30);
	$pday=$edate-(86400*30);
	
	
		
	//get all entrys via identifier
	$identifier = date("m/%/Y",$edate);
	$sql = "select * from logbook where ocl_date like '%" . $identifier . " %'";
	$r = $ocl->db_logbook->query($sql);
	foreach($r as $row) {
		echo $row[ocl_date] . ";" . $row[ocl_type] . ";" . $row[ocl_duration] . ";" . $row[ocl_caller] . ";" . $row[ocl_poster] . ";" . $row[ocl_subject] . ";" . str_replace("\n", " ", str_replace("\r\n", " ", $row[ocl_error_long])) . ";\n";
				
	}
	
	
	
	
?>