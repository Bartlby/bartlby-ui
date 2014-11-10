<?php
error_reporting(0);	


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


	$Bartlby_SOFT_selected_instance=-1;
	$Bartlby_CONF_single_sign_on=1; //Auth agains instance 0


		$confs[0][file] = "/opt/bartlby/etc/bartlby.cfg";
		$confs[0][remote] = false;
		$confs[0][db_sync] = true;
		$confs[0][display_name] = "Primary";
		$confs[0][is_master]=true;
		$confs[0][uniq_id]=0;
		

		
		
	if(file_exists("nodes/uinodes.php")) {
		include_once "nodes/uinodes.php";
	}

	$Bartlby_CONF_used_instance=$_SESSION[instance_id];
	if($_GET[instance_id]) {
		//user supplied one
		$Bartlby_SOFT_selected_instance=$Bartlby_CONF_used_instance;
		$Bartlby_CONF_used_instance = $_GET[instance_id];

	}


	if(!$Bartlby_CONF_used_instance) {
		$Bartlby_CONF=$confs[0][file];
		$Bartlby_CONF_Remote=$confs[0][remote];
		$Bartlby_CONF_DBSYNC=$confs[0][db_sync];
		$Bartlby_CONF_IDX=$confs[0][uniq_id];
		$Bartlby_CONF_DisplayName=$confs[0][display_name];
		$Bartlby_CONF_isMaster=$confs[0][is_master];

	} else {
		$Bartlby_CONF=$confs[$Bartlby_CONF_used_instance][file];
		$Bartlby_CONF_Remote=$confs[$Bartlby_CONF_used_instance][remote];
		$Bartlby_CONF_Remote=$confs[$Bartlby_CONF_used_instance][remote];
		$Bartlby_CONF_DBSYNC=$confs[$Bartlby_CONF_used_instance][db_sync];
		$Bartlby_CONF_IDX=$confs[$Bartlby_CONF_used_instance][uniq_id];
		$Bartlby_CONF_DisplayName=$confs[$Bartlby_CONF_used_instance][display_name];
		$Bartlby_CONF_isMaster=$confs[$Bartlby_CONF_used_instance][is_master];
	}
	if($Bartlby_CONF_used_instance > count($confs)) {
		$Bartlby_CONF=$confs[0][file];
		$Bartlby_CONF_Remote=$confs[0][remote];
		$Bartlby_CONF_IDX=$confs[0][uniq_id];
		$Bartlby_CONF_DBSYNC=true;
		$Bartlby_CONF_DisplayName=$confs[0][display_name];
		$Bartlby_CONF_isMaster=$confs[0][is_master];
		
	}



		

if($do_not_merge_post_get != true) {
			$_GET=array_merge($_GET, $_POST);
}
		if($_SERVER[SERVER_NAME] != "www.bartlby.org") {
			if(file_exists("setup.php")) {
				include("setup.php");
				exit(1);	
			}
		}



define("API_PORTIER_HOST", "localhost");
define("API_PORTIER_PORT", "9031");


if(!function_exists("bartlby_audit")) {
	function btl_audit_get_last($r, $id, $type) {

		$re="";
		switch($type) {
			case BARTLBY_AUDIT_TYPE_SERVICE:
			$re = bartlby_get_service_by_id($r, $id);
			
			break;
			case BARTLBY_AUDIT_TYPE_SERVER:
			$re = bartlby_get_server_by_id($r, $id);
			
			break;
			case BARTLBY_AUDIT_TYPE_WORKER:
			$re = bartlby_get_worker_by_id($r, $id);
			
			break;
			case BARTLBY_AUDIT_TYPE_DOWNTIME:
			$re = bartlby_get_downtime_by_id($r, $id);
			
			break;
			case BARTLBY_AUDIT_TYPE_SERVERGROUP:
			$re = bartlby_get_servergroup_by_id($r, $id);
			
			break;
			case BARTLBY_AUDIT_TYPE_SERVICEGROUP:
			$re = bartlby_get_servicegroup_by_id($r, $id);
			break;
		
		}
		
		return json_encode($re, true);		
	}
	function bartlby_audit($res, $type, $id, $action) {

		if((int)$_SESSION[worker][worker_id] < 0)  {
			
			return false;
		}
		$prev_object="";
		//readable
		switch($action) {
			case BARTLBY_AUDIT_ACTION_ADD:
			$readable_action="ADD";
			break;
			case BARTLBY_AUDIT_ACTION_DELETE:
			$readable_action="DELETE";
			$prev_object=btl_audit_get_last($res, $id, $type);
			break;
			case BARTLBY_AUDIT_ACTION_MODIFY:
			$readable_action="MODIFY";
			$prev_object=btl_audit_get_last($res, $id, $type);
			break;
		}

		
		switch($type) {
			case BARTLBY_AUDIT_TYPE_SERVICE:
			$readable_type="SERVICE";
			break;
			case BARTLBY_AUDIT_TYPE_SERVER:
			$readable_type="SERVER";
			break;
			case BARTLBY_AUDIT_TYPE_WORKER:
			$readable_type="WORKER";
			break;
			case BARTLBY_AUDIT_TYPE_DOWNTIME:
			$readable_type="DOWNTIME";
			break;
			case BARTLBY_AUDIT_TYPE_SERVERGROUP:
			$readable_type="SERVERGROUP";
			break;
			case BARTLBY_AUDIT_TYPE_SERVICEGROUP:
			$readable_type="SERVICEGROUP";
			break;
		
		}
		if(!class_exists("Audit")) {
			include_once "extensions/Audit/Audit.class.php";
		}
		$ad = new Audit();
		
		

		$sql = "insert into bartlby_object_audit (type, action, utime, worker_id, object_id, prev_object) values(" . $type . "," . $action . ", " . time() . ", " . $_SESSION[worker][worker_id] . ", " . $id . ", '" .  SQLite3::escapeString($prev_object) . "')";
		$r = $ad->db->query($sql);
		
		
		return true;
		/*
		
		//" Type=>" . $type . " ID=>" . $id . " action=>" . $action . " folder: " . $folder . "\n<br>";
		
		*/
		/*
		$fp = fopen("/var/log/bartlby_audit.log", "a");
		fwrite($fp, "AUDIT: Type=>" . $type . " ID=>" . $id . " action=>" . $action . " folder: " . $folder . "\n");
		fclose($fp);
		*/
        return true;
	}




}

?>