<?
/* $Id: ack.c 16 2008-04-07 19:20:34Z hjanuschka $ */
/* ----------------------------------------------------------------------- *
 *
 *   Copyright 2005-2008 Helmut Januschka - All Rights Reserved
 *   Contact: <helmut@januschka.com>, <contact@bartlby.org>
 *
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, Inc., 675 Mass Ave, Cambridge MA 02139,
 *   USA; either version 2 of the License, or (at your option) any later
 *   version; incorporated herein by reference.
 *
 *   visit: www.bartlby.org for support
 * ----------------------------------------------------------------------- */
/*
$Revision: 16 $
$HeadURL: http://bartlby.svn.sourceforge.net/svnroot/bartlby/trunk/bartlby-core/src/ack.c $
$Date: 2008-04-07 21:20:34 +0200 (Mo, 07 Apr 2008) $
$Author: hjanuschka $ 
*/


require_once ("xajax/xajax.inc.php");
include("xajax.common.php");

class Layout {
	var $OUT;
	var $template_file;
	var $box_count;



function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}


	function stackTrace() {
    $stack = debug_backtrace();
    $output = '';

    $stackLen = count($stack);
    for ($i = 1; $i < $stackLen; $i++) {
        $entry = $stack[$i];

        $func = $entry['function'] . '(';
        $argsLen = count($entry['args']);
        for ($j = 0; $j < $argsLen; $j++) {
            $func .= $entry['args'][$j];
            if ($j < $argsLen - 1) $fxunc .= ', ';
        }
        $func .= ')';

        $output .= $entry['file'] . ':' . $entry['line'] . ' - ' . $func . PHP_EOL;
    }
    return $output;
	}

	function deprecated($str) {
		$this->deprecated[] = $str . "<pre>" . $this->stackTrace() . "</pre>";		
	}
	function setTheme($name="classic") {
		if($name=="") $name="classic";
		$this->theme=$name;	
	}
	function setRefreshableVariable($k, $v) {
		$this->refreshable_objects[$k] = $v;
	}
	function set_menu($men) {
		$this->OUT .= "<script>doToggle('" . $men . "');</script>";	
		$this->menu_set=true;
	}
	function microtime_float()
	{
   		list($usec, $sec) = explode(" ", microtime());
   		return ((float)$usec + (float)$sec);
	}
	function setTemplate($file) {
		$this->template_file=$file;
	}
	function setJSONOutput($json_variant = 1) {
		$this->OUTPUT_JSON=$json_variant;
	}
	function setMainTabName($n) {
		$this->mainTabName=$n;
	}
	function Layout($scr='') {
		global $_GET;
		global $_SESSION;
		global $Bartlby_CONF_used_instance;
		$this->box_count=1;
		if(bartlby_config(getcwd() . "/ui-extra.conf", "theme") != "") {
			$this->theme=bartlby_config(getcwd() . "/ui-extra.conf", "theme");
		} else {
			$this->theme="classic";
		}
		
		$this->instance_id=$Bartlby_CONF_used_instance;
		$this->do_auto_reload=false;
		$this->OUTPUT_JSON=false;
		$this->template_file="template.html";
		$this->start_time=$this->microtime_float();
		$this->menu_set=false;
			
		$this->tab_count=0;
		$this->tabs=array();


		if($_GET[json]) {
			$this->setJSONOutput($_GET[json]);
		} 

		//



		
	}
	function addScript($sc) {
		$this->BTUI_SCRIPTS .= $sc;
	}
	function Tab($name, $cnt, $tab_name="") {
		$this->tab_count++;
		
		$this->tabs[]=array(name=>$name, cnt=>$cnt, tab_name=>$tab_name);
	}
	function Table($proz="100%", $border=0) {
		$this->deprecated("NO MORE TABLE");

		$this->OUT .= "<table border=$border width='$proz' cellpadding=0 cellspacing=0 border=0 style='width: 100%;' class=' no-border'><tbody class='no-border-y'>";
	}
	function MetaRefresh($time=20) {
		$this->OUT .= "<script>function ReloadME() {
			if(global_reload == 1) {
				document.location.reload();
			}
			window.setTimeout('ReloadME()', " . $time . "000);
		}
		window.setTimeout('ReloadME()', " . $time . "000);</script>";	
	}
	function TableEnd() {
		$this->deprecated("NO MORE TABLE");
		$this->OUT .= "</tbody></table>";	
	}
	function DisplayHelp($msg=array()) {
		for($x=0; $x<=count($msg);$x++) {
			$fin .= "msg[]=" . $msg[$x] . "&";
		}
		//$this->OUT .= "<script>parent.unten.location.href='help.php?" . $fin . "';</script>";
	}

	function Td($data=array()) {
		$this->deprecated("NO MORE TD();");
		for($x=0;$x<count($data);$x++) {
			$width="";
			$height="";
			$class="";
			$colspan="";
			$disp=$data[$x];
			$align="align=left";
			if (is_array($data[$x])) {
				if ($data[$x]["width"]) $width="width='" . $data[$x]["width"] .  "'";
				if ($data[$x]["height"]) $height="height='" . $data[$x]["width"] .  "'";
				if ($data[$x]["class"]) $class="class='" . $data[$x]["class"] .  "'";
				if ($data[$x]["colspan"]) $colspan="colspan='" . $data[$x]["colspan"] .  "'";
				if ($data[$x]["align"]) $align="align='" . $data[$x]["align"] .  "'";
			}
			
			if (is_array($data[$x])) $disp=$data[$x]["show"];
			
			
			//$disp=htmlspecialchars($disp);			
			
			$r .= "<td $colspan  $align  valign=top $width $height $class>\n" . $disp . "\n</td>\n";
			//$r .= "<div >\n" . $disp . "\n</div>\n";	
		}
		return $r;
	}
	function FormBox($el = array(), $return=false) {

		$data = '<div class="form-group">
                <label class="col-sm-3 control-label">' . $el[0] . '</label>
                <div class="col-sm-6">
                 ' . $el[1] . '
                </div>
              </div>';
             	if($return == true) {
					return  $data;
				} else {
					$this->OUT .= $data;
				}

	}
	function Tr($td, $return = false) {
		$data="<tr>\n$td\n</tr>\n";
		//$data='' . $td; // NO MORE TR
		$this->deprecated("NO MORE TR();");
		
		if($return == true) {
			return  $data;
		} else {
			$this->OUT .= $data;
		}
		
	}
	function Form($name,$action, $method='GET', $r = false) {
		
		$rr = "<form id='$name' name='$name' class='form-horizontal' action='$action' method='POST'>\n";	
		if($r) {
			return $rr;	
		} else {
			$this->OUT .= $rr;	
		}
		
	}
	function FormEnd($r=false) {
		$rr .= "</form>\n";	
		if($r) {
			return $rr;	
		} else {
			$this->OUT .= $rr;	
		}
	}
	 
	function TextArea($name, $def, $height=7, $width=100) {
		$r = "<textarea class=form-control name='$name' cols=$width rows=$height style='width:100%'>$def</textarea>\n";
		$r = '<div class="form-group" id="fg_' . $name . '">
				    
				   	 <div class="col-sm-10">
				      ' . $r . '
				      </div>
				    
				  </div>';
		return $r;
	}
	
	function Field($name, $type='text', $value='',$L='', $chkBox='', $help = array()) {
		$n="name='$name' id='$name'";
		$value=htmlspecialchars($value);
		if($help) {
			$hIcon="<a href='help.php?msg[0]=$help&msg[1]=NULL' target='unten'><img src='layout/themes/classic/info.gif' border=0></A>";
		}
		$cl = "form-control";
		if($type == "button" || $type == "submit") {
			$cl = "btn btn-primary pull-right";
		}
		if(preg_match("/class=/", $chkBox)) {
			preg_match("/class=['\"](.*?)['\"]/", $chkBox, $m);
			if($m[1] == "switch") {
				$cl = $m[1];
			} else {
				$cl .= " " . $m[1];
			}
		}
		
		$r="<input type='$type' class='$cl' value='$value' $n $chkBox>$hIcon<div style='color:#ff0000' id='error_" . $name . "'></div>\n";

		if($type != "hidden" && $type != "button" && $type != "submit") {
			$r = '<div class="form-group" id="fg_' . $name . '">
				    
				   	 <div class="col-sm-10">
				      ' . $r . '
				      </div>
				    
				  </div>';
		}
		if ($L) {
			$this->OUT .= $r;
		} else {
			return $r;
		}
		
	}
	function orchLable($orch_id) {
		global $_BARTLBY;
		$_BARTLBY[orch_nodes][]=array("orch_id"=>0, "orch_alias"=>"LOCAL");
		for($x=0; $x<count($_BARTLBY[orch_nodes]); $x++) {
			$f=true;
			$sel="";
			if($_BARTLBY[orch_nodes][$x][orch_id] == $orch_id) {
				return $_BARTLBY[orch_nodes][$x][orch_alias];
			}
		}
		return "UNKNOWN";


	}
	function orchDropdown($choosable=true, $selected) {
		global $_BARTLBY;
		$_BARTLBY[orch_nodes][]=array("orch_id"=>0, "orch_alias"=>"LOCAL");
		if($choosable) {
			$f=false;
			for($x=0; $x<count($_BARTLBY[orch_nodes]); $x++) {
				$f=true;
				$sel="";
				if($_BARTLBY[orch_nodes][$x][orch_id] == $selected) {
					$sel = "selected";
				}
				$rdrop .= "<option value='" . $_BARTLBY[orch_nodes][$x][orch_id] . "' " . $sel . ">" . $_BARTLBY[orch_nodes][$x][orch_alias] . "</option>";
			}

			if($f == true) {
				return "<select name=orch_id id=orch_id data-rel='chosen'>" . $rdrop . "</select>";				
			} else {
				return "<input type=hidden name=orch_id value=0>No Orchestra Node Configured defaulting to 0";
			}




		} else {
			return "<select id=orch_id name=orch_id disabled><option value=-1>Orch id is inerhited</option></select>";
		}

	}
	function DropDown($name,$options=array(), $type='', $style='', $addserver=true, $custom_name='chosen', $default_preset = false) {
		global $_GET;
		
		
		
		if(strstr($custom_name, "ajax")) {
			if($_GET[dropdown_search] == 1 && $_GET[dropdown_name] == $name) {
				//Return JSON Objects :)
				$obj_idx=-1;
				
				for($x=0; $x<count($options); $x++) {
					if($options[$x][is_group]==1) {
						//if(!@preg_match("/" . $_GET[dropdown_term] . "/i", $options[$x+1][k])) continue;
						
						$obj_idx++;
						$obj[$obj_idx]->group=true;
						$obj[$obj_idx]->text=$options[$x][k];
						$options[$x][k]="Server: " . $options[$x][k];
						if(!$addserver) continue;
						
						
					}
					if(!is_array($obj[$obj_idx]->items)) $obj[$obj_idx]->items=array();
					
					if($obj_idx < 0) $obj_idx=0;
					if(!@preg_match("/" . $_GET[dropdown_term] . "/i", $options[$x][k])) continue;
					
					$obj[$obj_idx]->items[]=array("value"=> "" . $options[$x][v], "text" => "" . $options[$x][k]);
					
					
					
				}
		
				for($x=0; $x<count($obj); $x++) {
					if(count($obj[$x]->items) > 0) {
						$obj_cleaned[]=$obj[$x];
					}
				}
				echo json_encode(utf8_encode_all($obj_cleaned));
				exit;
				
				
				
			}
		}
		
		$r = "<select name='$name' id='$name' $type $style data-rel='" . $custom_name . "' class='form-control chosen-select'>\n";
		for ($x=0;$x<count($options); $x++) {
			$sel="";
			if ($options[$x][s] == 1) $sel="selected";
			if($options[$x][is_group] == 1) {
					if(strstr($custom_name, "ajax") && $options[$x][s] != 1) continue; 
					$r .= '</optgroup><optgroup label="' .  $options[$x][k] . '">';
					if($addserver)  {
							if(strstr($custom_name, "ajax") && $options[$x][s] != 1) continue; 
							$r .= "<option style='background-color: " .  $options[$x][c] . "' value='" . $options[$x][v] . "' $sel>Server: " . $options[$x][k] . "\n";	
					}
			} else{
							if(strstr($custom_name, "ajax") && $options[$x][s] != 1) continue; 
							$r .= "<option style='background-color: " .  $options[$x][c] . "' value='" . $options[$x][v] . "' $sel>" . $options[$x][k] . "\n";	
			}
		}		
		$r .= "</select><div style='color:#ff0000' id='error_" . $name . "'></div>\n";


		$r = '<div class="form-group" id="fg_' . $name . '">
			    
			   	 <div class="col-sm-10">
			      ' . $r . '
			    </div>
			  </div>';

		return $r;
	}
	function setTitle($str) {
		$this->BoxTitle=$str;
	}
	
					 
					
					
				
	
	function beginMenu() {
		return '<li class="">';
		//return '<ul class="nav navbar-nav">';
		
		
	}

	/*
	<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li class="dropdown-header">Dropdown header</li>
          <li><a href="#">Separated link</a></li>
          <li><a href="#">One more separated link</a></li>
        </ul>
      </li>
	*/

	function addRoot($name) {
		
		return ' <a href="#"><i class="fa fa-desktop"></i><span>' . $name . '</span></a> <ul class="sub-menu">';
	
		
	}
	function addSub($root, $name, $link) {
		//return '<li><a tabindex="-1" href="#">Second level</a></li>'
		return '<li> <a href="' . $link . '"> ' . $name . '</a></li>';
		
	}
	function endMenu() {
		return '</ul></li>';
	
 
	
	}
	
	function display($lineup_file="") {
	global $xajax;
	global $confs;
		if($lineup_file=="no") $lineup_file="";
		
		
		if($this->menu_set == false) {
			$this->set_menu("core");	
			$this->set_menu("main");
			$this->set_menu("report");
			$this->set_menu("services");
			$this->set_menu("session");
			$this->set_menu("client");
			$this->set_menu("worker");
			$this->set_menu("downtimes");
			
		}
		$this->template_file="themes/" . $this->theme . "/theme.php";
			
		$this->end_time=$this->microtime_float();
		$diff=$this->end_time-$this->start_time;
			
		if(count($confs) > 0) {
			$this->BTL_INSTANCES .= "<select name='btl_instance_id' onChange='btl_change(this)' data-rel='chosen'>";
			for($x=0; $x<count($confs); $x++) {
				$sel = " ";
				if($_SESSION[instance_id] == $x) {
					$sel = "selected";
				}
				
				
				$r = "(LOCAL)";
				$read_only = "";
				$rw="grey";
				if($x == 0) $rw="#AEC6CF";
				if($confs[$x][remote]) {
					 $r = "(REMOTE)";
					if($confs[$x][db_sync] == false) {
						 $rw = "lightgrey";
					}
				}
				$this->BTL_INSTANCES .= "<option style='background-color: $rw' value=" . $x ." $sel>" . $confs[$x][display_name] . " $r</option>";
			}
			$this->BTL_INSTANCES .= "</select>";
		}
			

		//Create Menu.
		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Monitoring");
                $this->ext_menu .= $this->addSub("Monitoring", "Overview","overview.php");
                $this->ext_menu .= $this->addSub("Monitoring", "Services","services.php");
                $this->ext_menu .= $this->addSub("Monitoring", "Servers","servers.php");
		$this->ext_menu .= $this->endMenu();


		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Reporting");
                $this->ext_menu .= $this->addSub("Reporting", "Report/s","create_report.php");
                $this->ext_menu .= $this->addSub("Reporting", "Logfile","logview.php");
                $this->ext_menu .= $this->addSub("Reporting", "Notifications","logview.php?bartlby_filter=@NOT@");
                $this->ext_menu .= $this->addSub("Reporting", "Whats On","whats_on.php");
		$this->ext_menu .= $this->endMenu();

		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Server/s");
                $this->ext_menu .= $this->addSub("Server/s", "Add","add_server.php");
                $this->ext_menu .= $this->addSub("Server/s", "Modify","server_list.php?script=modify_server.php");
                $this->ext_menu .= $this->addSub("Server/s", "Delete","server_list.php?script=delete_server.php");
		$this->ext_menu .= $this->EndMenu();

		

		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Service/s");
                $this->ext_menu .= $this->addSub("Service/s", "Add","add_service.php");
                $this->ext_menu .= $this->addSub("Service/s", "Modify","service_list.php?script=modify_service.php");
                $this->ext_menu .= $this->addSub("Service/s", "Delete","service_list.php?script=delete_service.php");
                
		$this->ext_menu .= $this->endMenu();


		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Packages");
                $this->ext_menu .= $this->addSub("Packages", "Install","server_list.php?script=install_pkg.php");
                $this->ext_menu .= $this->addSub("Packages", "Uninstall","server_list.php?script=uninstall_pkg.php");
                $this->ext_menu .= $this->addSub("Packages", "Create","package_create.php");
                $this->ext_menu .= $this->addSub("Packages", "Delete","package_delete.php");
		$this->ext_menu .= $this->endMenu();


//ServerGroups
		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("ServerGroups/s");
                $this->ext_menu .= $this->addSub("ServerGroups/s", "Add","add_servergroup.php");
                $this->ext_menu .= $this->addSub("ServerGroups/s", "Modify","servergroup_list.php?script=modify_servergroup.php");
                $this->ext_menu .= $this->addSub("ServerGroups/s", "Delete","servergroup_list.php?script=delete_servergroup.php");
		$this->ext_menu .= $this->endMenu();
		
		
		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("ServiceGroup/s");
                $this->ext_menu .= $this->addSub("ServiceGroup/s", "Add","add_servicegroup.php");
                $this->ext_menu .= $this->addSub("ServiceGroup/s", "Modify","servicegroup_list.php?script=modify_servicegroup.php");
                $this->ext_menu .= $this->addSub("ServiceGroup/s", "Delete","servicegroup_list.php?script=delete_servicegroup.php");
		$this->ext_menu .= $this->endMenu();
		

		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Downtime/s");
                $this->ext_menu .= $this->addSub("Downtime/s", "Add","downtime_type_list.php");
                $this->ext_menu .= $this->addSub("Downtime/s", "Modify","downtime_list.php?script=modify_downtime.php");
                $this->ext_menu .= $this->addSub("Downtime/s", "Delete","downtime_list.php?script=delete_downtime.php");
		$this->ext_menu .= $this->endMenu();


		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Worker/s");
                $this->ext_menu .= $this->addSub("Worker/s", "Add","add_worker.php");
                $this->ext_menu .= $this->addSub("Worker/s", "Modify","user_list.php?script=modify_worker.php");
                $this->ext_menu .= $this->addSub("Worker/s", "Delete","user_list.php?script=delete_worker.php");
								$this->ext_menu .= $this->addSub("Worker/s", "Permissions","user_list.php?script=permission_worker.php");
								$this->ext_menu .= $this->addSub("Worker/s", "Activity","activity_worker.php");
		$this->ext_menu .= $this->endMenu();


		



		$dhl = opendir("extensions");
		while($file = readdir($dhl)) {
			if($file != "." && $file != "..") {
				if(!file_exists("extensions/" .  $file . ".disabled")) {
					@include_once("extensions/" . $file . "/" . $file . ".class.php");
					if (class_exists($file)) {
						eval("\$clh = new " . $file . "();");
						if(method_exists($clh, "_menu")) {
							$this->ext_menu .= $clh->_menu();
						}
					}
				}
			}
		}
		closedir();
		

		$this->ext_menu .= $this->beginMenu();
		$this->ext_menu .= $this->addRoot("Core");
                $this->ext_menu .= $this->addSub("Core", "Reload","bartlby_action.php?action=reload");
                $this->ext_menu .= $this->addSub("Core", "Config","choose_config.php");
                $this->ext_menu .= $this->addSub("Core", "Statistic","statistic.php");
                
		$this->ext_menu .= $this->addSub("Core", "Event Queue","event_queue.php");
		$this->ext_menu .= $this->addSub("Core", "Extensions","extensions.php");
		$this->ext_menu .= $this->addSub("Core", "About","version.php");
		$this->ext_menu .= $this->endMenu();

		
		$this->BTUICONTENT=$this->OUT;
		$this->BTUIOUTSIDE=$this->OUTSIDE;
		$this->BTUIBOXTITLE=$this->BoxTitle;
		$this->BTUITIME=round($diff,2);
		$this->BTMEMUSAGE=round(memory_get_peak_usage(true)/1024/1024,2);
		$this->BTLEXTMENU=$this->ext_menu;
		$this->SERVERTIME=date("d.m.Y H:i:s");
		$this->XAJAX=$xajax->getJavascript("xajax");			
		$this->UIVERSION=BARTLBY_UI_VERSION;
		$this->RELNOT=BARTLBY_RELNOT;
		
		$this->create_box($this->BoxTitle, $this->OUT, "MAIN", "", "", false, true);

		
	
		if(1==2) {
		
			for($z=0; $z<count($this->deprecated); $z++) {
				$depre .= '<div class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">×</button>
								Deprecated INFO: <strong>' .  $this->deprecated[$z] . '</strong>
							</div>';
			}
		}

		//Default LineUp
		if($lineup_file == "") {
			$lineup_file="default";
		}

		$lineup_path="themes/" . $this->theme . "/lineups/" . $lineup_file . ".php";
		if(!file_exists($lineup_path)) {
			$lineup_path="themes/classic/lineups/default.php";
		}
		ob_start();
		include($lineup_path);
		
		$this->BTUIOUTSIDE = $depre . ob_get_contents();		
		ob_end_clean();
		

		if($this->tab_count > 0) {
			if($this->mainTabName == "") $this->mainTabName="use setMainTabName";
			$this->tabs[-1][name]=$this->mainTabName;
			$this->tabs[-1][cnt]=$this->BTUIOUTSIDE;
			$this->tabs[-1][tab_name]="ROOT";

			$this->BTUIOUTSIDE='<div id="myTabContent" class="tab-content">';
			$this->BTTABBAR='<ul class="nav nav-tabs nav-tabs-google" id="coreTabs">';
			for($x=-1; $x<$this->tab_count; $x++) {
				if($this->tabs[$x][tab_name] != "") {
					$ttname=$this->tabs[$x][tab_name];
				} else {
					$ttname="coretab" . $x;
				}
				$this->BTTABBAR .='<li><a href="#' . $ttname . '">' . $this->tabs[$x][name] . '</a></li>';
				$this->BTUIOUTSIDE .= '<div class="tab-pane" id="' . $ttname . '">' . $this->tabs[$x][cnt] . '</div>';
			}
			$this->BTUIOUTSIDE .= "</div>";
			$this->BTTABBAR .="</ul>";
			//If we have tabs do em :)


		}
		


		ob_start();
		include($this->template_file);
		
		$o = ob_get_contents();
		ob_end_clean();

		if($this->OUTPUT_JSON) {
			echo json_encode(utf8_encode_all($this));
			exit;
		}
		
		echo $o;

		if($this->do_auto_reload) {
			echo "<script>btl_start_auto_reload()</script>";
		}


			
		
		
	}

	function disp_box($name) {
		if($name != "UNPLACED") {
			$regexp=0;
			if(preg_match("/\*/", $name)) $regexp=1;
			
			if($regexp == 0) {
				$this->boxes_placed[$name]=true;
				return $this->boxes[$name];
			} else {
				//Find Matching boxes;
				$r = "";
				while(list($k, $v) = @each($this->boxes)) {
					if(preg_match("/" . $name . "/i", $k)) {
					
						$this->boxes_placed[$k]=true;
						$r .= $this->boxes[$k];
					}
				}
				return $r;
			}
		} else {
				
			@krsort($this->boxes);
			while(list($k, $v) = @each($this->boxes)) {
				if($this->boxes_placed[$k] != true) {
					$r .= $v;
				}			
			}
			return $r;
		}
	}
	function create_box($title, $content, $id="", $plcs="", $box_file="", $collapsed=false, $auto_reload=false) {
		global $btl;
		
		$layout=$this;
		$put_a_standard_box_around_me=true;
		if($id != "") {
			$oid = $id;
		} else {
			//$oid = rand(100,2);	
			$oid=$this->box_count;
			$this->box_count++;
		}
		if(!is_array($plcs)) {
			$box_file="default_box.php";
		} else {
			$box_file .= ".php";
			if($this->OUTPUT_JSON == 2) {
				$this->boxes_values[$oid]=$plcs;
			}
		}
		$boxes_path="themes/" . $this->theme . "/boxes/" . $box_file;
		if(!file_exists($boxes_path)) {
			
			$boxes_path="themes/classic/boxes/" . $box_file;
		}
		$hidden = "block";
		$updown = "up";
		if($collapsed == true) {
		
			$hidden = "none";
			$updown = "down";
		}
		
		
		ob_start();
			include($boxes_path);
		
		$o = ob_get_contents();	
			
		ob_end_clean();	
	
		if($this->OUTPUT_JSON != 2) {
			$this->boxes[$oid]=$o;
			$this->boxes_content[$oid]=$content;
		}
		
		
		if($box_file != "default_box.php" && $put_a_standard_box_around_me == true) { //pack into a standard box
		
			$this->create_box($title, $o, $oid, "","", $collapsed, false);
		}
		
		//$this->boxes_wo_reload[$oid] = $this->boxes[$oid];
		if($auto_reload) {
		
		if($this->OUTPUT_JSON != 2) {
			$this->boxes[$oid] .= "<script>
			btl_add_refreshable_object(function(data) {
					
					$('#content_" . $oid . "').html(data.boxes_content." . $oid . ");
			});
			</script>";
		}
		
		
		}
		return $oid;
	}
	function push_outside($content) {
		//echo "HELLP!!!";
	}

}
