<?
include "config.php";

class OcL {
	function ocl_get_worker_list() {
		global $btl, $xajax;
		$search = $_GET[xajaxargs][2];
		$re = new XajaxResponse();
		$re->AddScript('$("#ocl_external-events").html("");');
		$ocl=$this;
		$btl->worker_list_loop(function($wrk, $shm) use(&$re, &$search, &$ocl) {
			if(!in_array($wrk[worker_id], $ocl->managed_users)) return LOOP_CONTINUE;
			if($search != "" && !preg_match("/" . $search . "/i", $wrk[name])) return LOOP_CONTINUE;
			$re->AddScript('$("#ocl_external-events").append("<div class=\'external-event\' data-worker_id=' . $wrk[worker_id] . ' data-activity_level=1>' . $wrk[name] . ' (ACTIVE)</div>");');
			$re->AddScript('$("#ocl_external-events").append("<div class=\'external-event1\' data-worker_id=' . $wrk[worker_id] . ' data-activity_level=2>' . $wrk[name] . ' (STANDBY)</div>");');
		});
		$re->AddScript("ocl_make_draggable();");
		return $re;
	}

	function fire_trigger($wrk, $msg) {
		global $btl;
			$exi=-1;

			$trigger_dir=bartlby_config($btl->CFG, "trigger_dir");
			if(!$trigger_dir) {
				$cmd_out="Trigger_dir not set";	
			} else {
				$base_dir=bartlby_config($btl->CFG, "basedir");
				
				$trs=explode("|", $wrk[enabled_triggers]);
				
				for($x=0; $x<count($trs); $x++) {
					if($trs[$x] == "") {
						continue;	
					}	
					if(!is_executable($trigger_dir . "/" . $trs[$x])) {
						continue;
					}

					$estr="export BARTLBY_HOME=\"$base_dir\";" .  $trigger_dir . "/" . $trs[$x] . " \"" . $wrk[mail] . "\" \"" . $wrk[icq] . "\" \"" . $wrk[name] . "\" \"" . $msg . "\"";

					$cmd_out .= "<hr><b>" . $trs[$x] . "</b><br>";
					$p=popen($estr . "2>&1", "r");
					while(!feof($p)) {
						$cmd_out .= fgets($p, 1024);
					}
					$exi=(fclose($p)>>8)&0xFF;
					
					
				}
			}		
	}
	function ocl_save_managed() {
		global $btl, $xajax;
		$values = $xajax->_xmlToArray("xjxobj", $_GET[xajaxargs][2]);
		
		$re = new XajaxResponse();
		$this->storage->save_key("managed_users", serialize($values));
		$re->AddScript('noty({"text":"[OcL] Managed Users saved","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})'); //Notify User
		return $re;


	}
	function OcL() {
		$this->layout = new Layout();
		$this->storage = new BartlbyStorage("OcL");
		$this->DBSTR = "CREATE TABLE logbook (id INTEGER PRIMARY  KEY AUTOINCREMENT, 
				ocl_date DATETIME,
				ocl_subject TEXT,
				ocl_duration TEXT, 
				ocl_caller TEXT,
				ocl_error_long TEXT,
				ocl_type TEXT,
				ocl_service_var TEXT,
				ocl_poster TEXT				
				);";
		$this->db_logbook = $this->storage->SQLDB($this->DBSTR, "ocl_logbook.db");
		
		$this->DBSTR = "CREATE TABLE schedule (id INTEGER PRIMARY  KEY AUTOINCREMENT, 
				date_from DATETIME,
				date_to DATETIME,
				worker_id INTEGER, 
				activity_level INTEGER,
				all_day INTEGER DEFAULT 1,
				color TEXT				
				);";
		$this->db_schedule = $this->storage->SQLDB($this->DBSTR, "ocl_schedule.db");
		$this->managed_users=unserialize($this->storage->load_key("managed_users"));

	}
	
	function resolveGroupString($str) {
		global $btl;
		$aa=explode("|", $str);
		for($aax=0; $aax<count($aa); $aax++) {
			$bb = explode("=", $aa[$aax]);
			if($aa[$aax]) {
				//$svc = @bartlby_get_service_by_id($this->CFG, $aa[$aax]);
				$idx=$btl->findSHMPlace($aa[$aax]);
				$svc=bartlby_get_service($btl->RES, $idx);
				$dtemp="";
				if($svc[is_downtime] == 1) {
					$dtemp="<i>DOWNTIME</i>";
				}
				$r .= "Service: <a href='service_detail.php?service_place=" . $idx . "'>$svc[server_name]:$svc[client_port]/$svc[service_name]</A> (Current: <font color=" . $btl->getColor($svc[current_state]) . ">" . $btl->getState($svc[current_state]) . "</font>) $dtemp<br>";
			}
		}	
		return $r;
	}
	function _About() {
		$snotice="Manage OnCall";
		return $snotice;
			
	}
	
	function _Menu() {
		$r =  $this->layout->beginMenu();
		$r .= $this->layout->addRoot("On-Call");
		$r .= $this->layout->addSub("On-Call", "Logs","extensions_wrap.php?script=OcL/index.php");
		$r .= $this->layout->addSub("On-Call", "Add","extensions_wrap.php?script=OcL/add.php");
		$r .= $this->layout->addSub("On-Call", "Schedule","extensions_wrap.php?script=OcL/schedule.php");


		$r .= $this->layout->endMenu();
		return $r;
	}
	
	
	function xajax_ocl_del_entry() {
		global $xajax, $btl;
		$res = new xajaxResponse();
		$btl->hasRight("ocl_delete");
		$identifier=$_GET[xajaxargs][2];
		$ocl_id=$_GET[xajaxargs][3];
		
		$sql = "delete from logbook where id=" . $ocl_id;
		$this->db_logbook->exec($sql);

		$res->AddScript("document.location.reload()");
		return $res;
	}
	function xajax_ocl_add_form() {
		global $xajax;
		$res = new xajaxResponse();
		$values = $xajax->_xmlToArray("xjxquery", $_GET[xajaxargs][2]);
		$e=0;
		$res->addAssign("error_ocl_date", "innerHTML", "");
		$res->addAssign("error_ocl_subject", "innerHTML", "");
		$res->addAssign("error_ocl_duration", "innerHTML", "");
		$res->addAssign("error_ocl_caller", "innerHTML", "");
		$res->addAssign("error_ocl_error_long", "innerHTML", "");
		
		if($values[ocl_date] == "") {
			$res->addAssign("error_ocl_date", "innerHTML", "required field");			
			$e++;
		} 
		if($values[ocl_subject] == "") {
			$res->addAssign("error_ocl_subject", "innerHTML", "required field");			
			$e++;
		} 
		
		if($values[ocl_duration] == "") {
			$res->addAssign("error_ocl_duration", "innerHTML", "required field");			
			$e++;
		} 
		if($values[ocl_caller] == "") {
			$res->addAssign("error_ocl_caller", "innerHTML", "required field");			
			$e++;
		} 
		if($values[ocl_error_long] == "") {
			$res->addAssign("error_ocl_error_long", "innerHTML", "required field");			
			$e++;
		} 
		if($e == 0) {
			$res->AddScript("document.fm1.submit()");	
		}
		return $res;
	}
	
	function _permissions() {
		global $worker_rights;
		
		$ky["ocl_add"]="allowed to add ocl-entrys";	
		$ky["ocl_csv"]="can view csv reports";
		$ky["ocl_view"]="can view logbook";
		$ky["ocl_edit"]="can edit entrys";
		$ky["ocl_delete"]="can delete";
		$ky["bsp_manage"]="allowed to manage/update BsP's";
		
		while(list($k, $v) = each($ky)) {
			$kc="";
			if($worker_rights[$k][0] && $worker_rights[$k][0] != "false") {
				$kc="checked";	
			}
			$r .= "<input type=checkbox name='$k' $kc> " . $ky[$k] . "<br>";
				
		}
		return $r;
	}
	
	
	
	
	function _overview() {
		global $btl;
		global $layout;
		
		
	$sql = "select * from logbook order by ocl_date desc limit 30";
	$r = $this->db_logbook->query($sql);
	$cur_box_content  = '<button onClick="document.location.href=\'extensions_wrap.php?script=OcL/add.php\'" class="sm_add_new_btn btn  btn-success">Add New Entry</button>';
	$cur_box_content .= ' <ol class="discussion">';
	$ocnt=0;
foreach($r as $row) {


		//images/diabled.gif
		$del_icon="<a href='#' onClick='xajax_ExtensionAjax(\"OcL\", \"xajax_ocl_del_entry\",\"" . $identifier . "\",\""  . $row[id] .  "\" )'><img border=0 alt='delete this entry' src='themes/classic/images/diabled.gif'></A>";
		$mod_icon="<a href='extensions_wrap.php?script=OcL/modify.php&identifier=" . $identifier . "&id=" . $row[id] ."'><img border=0 alt='modify this entry' src='themes/classic/images/modify.gif'></A>";
		$grp_str=$this->resolveGroupString($row[ocl_service_var]);
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
		

    $ocnt++;
	}
	$cur_box_content .= "</ol>";
	$layout->Tab("Recent On-Call Log <span class='notification blue' style='display:inline-block; font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif; position:relative;top: 0px;'>" . $ocnt . "</span>", $cur_box_content, "ocl_overview");

	



		
		return "";
		
		
	}
	
	
	
	
	
}

?>
