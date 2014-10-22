<?php

	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/Packery/Packery.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("packery");
	$ar = new Packery();

	ini_set('display_errors', '1');
	error_reporting(E_ERROR);

	$layout= new Layout();
	$layout->setTitle("Packery");
	$layout->set_menu("Packery");
	$layout->setMainTabName("Packery");
	$layout->do_auto_reload=false;

	$layout->addScript('<script src="extensions/Packery/js/packery.pkgd.min.js" type="text/javascript"></script>');
	$layout->addScript('<script src="extensions/Packery/js/draggabilly.pkgd.min.js" type="text/javascript"></script>');
	$layout->addScript('<script src="extensions/Packery/js/packery_main.js" type="text/javascript"></script>');

	$layout->boxes_placed[MAIN]=true;
	$layout->display("packery_main.php", "extensions/Packery/lineups/");

