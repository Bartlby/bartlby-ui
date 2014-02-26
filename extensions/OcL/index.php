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
	$btl->hasRight("ocl_view");
	$ocl = new OcL();
	
	
	
	$layout= new Layout();
	$layout->setTitle("OcL: Logbook");
	
	$layout->set_menu("OcL");
	
	$layout->Table("100%");
	
	$edate=time();
	if($_GET[edate]) $edate=$_GET[edate];
	
	
	$nday=$edate+(86400*30);
	$pday=$edate-(86400*30);
	
	
	$nlink="<a href='extensions_wrap.php?script=OcL/index.php&edate=" . $nday . "'>" . date("m.Y", $nday)  . "</A>";
	$plink="<a href='extensions_wrap.php?script=OcL/index.php&edate=" . $pday . "'>" . date("m.Y", $pday)  . "</A>";
	$csv="<a href='extensions_wrap.php?script=OcL/csv.php&edate=" . $edate . "'>CSV</A>";
	
	

	$layout->Tr(
		$layout->Td(
				Array(
					array("colspan" => 2, "show" => "<b>Entrys for:</b>  " . $plink . " &lt;&lt;" .  date("m.Y", $edate) . "&gt;&gt;" . $nlink . " $csv ") 
				)
			)
	
	);	
	
	
	//get all entrys via identifier
	$identifier = date(".m.Y",$edate);
	$sql = "select * from logbook where ocl_date like '%" . $identifier . " %' order by ocl_date desc";
	$r = $ocl->db_logbook->query($sql);
	$cur_box_content  = '<button onClick="document.location.href=\'extensions_wrap.php?script=OcL/add.php\'" class="sm_add_new_btn btn  btn-success">Add New Entry</button>';
	$cur_box_content .= ' <ol class="discussion">';
	foreach($r as $row) {


		//images/diabled.gif
		$del_icon="<a href='#' onClick='xajax_ExtensionAjax(\"OcL\", \"xajax_ocl_del_entry\",\"" . $identifier . "\",\""  . $row[id] .  "\" )'><img border=0 alt='delete this entry' src='themes/classic/images/diabled.gif'></A>";
		$mod_icon="<a href='extensions_wrap.php?script=OcL/modify.php&identifier=" . $identifier . "&id=" . $row[id] ."'><img border=0 alt='modify this entry' src='themes/classic/images/modify.gif'></A>";
		$grp_str=$ocl->resolveGroupString($row[ocl_service_var]);
		$gv="";
	
		$btl->worker_list_loop(function($wrk, $shm) use (&$gv, &$layout, &$row){
				
				if($wrk[name] == $row[ocl_poster]) {
					$gv =  $layout->get_gravatar($wrk[mail]);
				}
		});

		$cur_box_content .= '<li class="other">
      <div class="avatar1">
      	<div class=avatar style="width: 40px; height:40px;">
        	<img src="' . $gv . '">
    	</div>
	' . $row[ocl_date] . '
      </div>

      <div class="messages">
      <b><h2>' . $row[ocl_subject] . '</b></h2>
      <span >' . $grp_str . '</span>
      <hr noshade>
       <p>
       	' . nl2br($row[ocl_error_long]) . '
       </p>
       <hr noshade>
       ' . $del_icon . '&nbsp;' .  $mod_icon . '
        <span class="pull-right"><xsmall>Duration: '.  $row[ocl_duration] . ' Caller: ' . $row[ocl_caller] . ' Type: ' . $row[ocl_type] . '</sxmall></span>
        
      </div>
    </li>	';		
		


	}
	$cur_box_content .= "</ol>";
	$layout->push_outside($layout->create_box("Entrys for " . $identifier, $cur_box_content));
	
	
	$layout->TableEnd();
	$layout->display();
	
	
	
?>
