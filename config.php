<?php
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
session_start();



	$confs[0][file] = "/opt/bartlby/etc/bartlby.cfg";
	$confs[0][remote] = false;
	$confs[0][db_sync] = true;
	$confs[0][display_name] = "Primary";
	

	
	
if(file_exists("nodes/uinodes.php")) {
	include_once "nodes/uinodes.php";
}



if(!$_SESSION[instance_id]) {
	$Bartlby_CONF=$confs[0][file];
	$Bartlby_CONF_Remote=$confs[0][remote];
	$Bartlby_CONF_DBSYNC=$confs[0][db_sync];
	$Bartlby_CONF_IDX=$confs[0][uniq_id];
} else {
	$Bartlby_CONF=$confs[$_SESSION[instance_id]][file];
	$Bartlby_CONF_Remote=$confs[$_SESSION[instance_id]][remote];
	$Bartlby_CONF_Remote=$confs[$_SESSION[instance_id]][remote];
	$Bartlby_CONF_DBSYNC=$confs[$_SESSION[instance_id]][db_sync];
	$Bartlby_CONF_IDX=$confs[$_SESSION[instance_id]][uniq_id];
}
if($_SESSION[instance_id] > count($confs)) {
	$Bartlby_CONF=$confs[0][file];
	$Bartlby_CONF_Remote=$confs[0][remote];
	$Bartlby_CONF_IDX=$confs[0][uniq_id];
	$Bartlby_CONF_DBSYNC=true;
	
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
/*

	$Bartlby_CONF="/opt/bartlby/etc/bartlby.cfg";
	


$Bartlby_CONF="/storage/SF.NET/BARTLBY/GIT/bartlby-core/BARTLBY.local";
*/
?>
