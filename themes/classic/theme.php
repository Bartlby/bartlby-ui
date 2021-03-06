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
?>

<?
global $Bartlby_CONF_DisplayName;
global $Bartlby_CONF_IDX;
if(!$_SESSION["service_display_prio"]) $_SESSION["service_display_prio"]=50;
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<title>(<?=$Bartlby_CONF_DisplayName?>) - Bartlby</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Bartlby">
	<meta name="author" content="Helmut Januschka">

	<!-- The styles -->
	
	
	<?=$this->XAJAX?>
	<style>
		input.vertical { width: 50px;}
	</style>
	<script>
		js_theme_name='classic';
		</script>
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
	

	<link href="themes/classic/css/jquery-ui-1.10.0.custom.css" rel="stylesheet"/>
	
	<link href='themes/classic/css/fullcalendar.css' rel='stylesheet'>
	<link href='themes/classic/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	
	<link href="themes/classic/css/bootstrap.css" rel="stylesheet">
	
	
	
	<link href='themes/classic/css/slider.css' rel='stylesheet'>
	
	<link href="themes/classic/btl.css" rel="stylesheet">

	<link href="themes/classic/css/selectize.bootstrap3.css" rel="stylesheet">


	
	
	<link href='themes/classic/css/jquery.terminal.css' rel='stylesheet'>
	<link href='themes/classic/css/sweet-alert.css' rel='stylesheet'>
	<link href='themes/classic/css/dataTables.tableTools.css' rel='stylesheet'>

	
	



	<!-- jQuery -->
	<script src="themes/classic/js/jquery-1.11.0.min.js"></script>


	<script src="themes/classic/js/bootstrap.js"></script>
	<script src="themes/classic/js/selectize.js"></script>

	<!-- jQuery UI -->
	<script src="themes/classic/js/jquery-ui.js"></script>
	<!-- transition / effect library -->
	<script src="themes/classic/js/jquery.cookie.js"></script>
	<script src="themes/classic/js/jquery-ui-timepicker-addon.js"></script>
	<!-- calander plugin -->
	<script src='themes/classic/js/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='themes/classic/js/jquery.dataTables.min.js'></script>
	<script src='themes/classic/js/dataTables.tableTools.js'></script>
	<script src='themes/classic/js/dataTables.bootstrap.js'></script>

	<!-- chart libraries start -->
	
	<script src="themes/classic/js/jquery.flot.min.js"></script>
	<script src="themes/classic/js/jquery.flot.pie.min.js"></script>
	<script src="themes/classic/js/jquery.flot.stack.js"></script>
	<script src="themes/classic/js/jquery.flot.resize.min.js"></script>
	<script src="themes/classic/js/jquery.flot.threshold.js"></script>
	<script src="themes/classic/js/jquery.flot.selection.js"></script>
	<script src="themes/classic/js/jquery.flot.tooltip.js"></script>
	<!-- chart libraries end -->
  


	<!--GAUGE-->
	<script src="themes/classic/js/raphael-min.js"></script>
	<script src="themes/classic/js/justgauge.js"></script>
	
	<!-- notification plugin -->
	<script src="themes/classic/js/jquery.noty.js"></script>


	<!-- application script for Charisma demo -->
	

	<script src="themes/classic/js/jquery.terminal-0.8.8.min.js"></script>	
	
	


    <script type="text/javascript" src="./jsrrd/binaryXHR.js"></script>
    <script type="text/javascript" src="./jsrrd/rrdFile.js"></script>

    <!-- rrdFlot class needs the following four include files !-->
    <script type="text/javascript" src="./jsrrd/rrdFlotSupport.js"></script>
    <script type="text/javascript" src="./jsrrd/rrdFlot.js"></script>


    <script type="text/javascript" src="themes/classic/js/jquery.icheck.js"></script>

    <script type="text/javascript" src="themes/classic/js/bootstrap-slider.js"></script>
    <script type="text/javascript" src="themes/classic/js/bootstrap-switch.js"></script>

	<script type="text/javascript" src="js/btl.js"></script>


	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="themes/classic/img/favicon.ico">
		

	<link href="themes/classic/css/font-awesome.min.css" rel="stylesheet">
	
	<?
		echo $this->BTUI_SCRIPTS;
	?>
</head>

<body>


	





<div id="cl-wrapper">

  <div class="cl-sidebar">
    <div class="cl-toggle"><i class="fa fa-bars"></i></div>
    <div class="cl-navblock" style='background-color: #333; xwidth:241px'>
      <div class="menu-space">
        <div class="content">
          <div class="sidebar-logo">
            <div class="logo">BARTLBY
                <a href="index2.html"></a>
            </div>
          </div>
          <!--<div class="side-user">
           
          </div>-->
         
          <ul class="cl-vnavigation">
          
           	<?=$this->BTLEXTMENU?>

          </ul>
        </div>
        
      </div>
      
      <div class=" collapse-button" style="padding:7px 9px;">
      	<div class=search style='color: black;'> <?=$this->BTL_INSTANCES?></div>
      	<div class=search style='background-color:transparent; margin-bottom: 20px;'>
      		
      		<div class="form-group">
                <label style='padding-left: 5px;padding-bottom:10px;' class="col-sm-3 control-label">Service Density</label>
                <div class="col-sm-6">
                 <input  class="form-control service_deepnes" style='width:70%;' type="text"  data-slider-value="<?=$_SESSION["service_display_prio"]?>" data-slider-step="1" data-slider-max="100" data-slider-min="0" value="">
                </div>
              </div>
      	</div>

      	 

      	<div class=pull-right>
        <button id="sidebar-collapse" class="btn btn-default" style=""><i style="color:#fff;" class="fa fa-angle-left"></i></button>
        </div>
      </div>
    </div>
  </div>
	<div class="container-fluid" id="pcont">
   <!-- TOP NAVBAR -->
  <div id="head-nav" class="navbar navbar-default">
    <div class="container-fluid">
    <?
					if($Bartlby_CONF_IDX > 0 ) {
				?>
				<div class="alert alert-warning" style='margin-bottom: 0px;'>
										<button type="button" class="close" data-dismiss="alert">×</button>
										You are on a remote Node (<?=$Bartlby_CONF_DisplayName?>)!!
									</div>
				<?
					}
				?>
				
				
      <div class="navbar-collapse">
        <ul class="nav navbar-nav navbar-right user-nav">
         <li>
        	<a href="bartlby_action.php?action=reload"><i class="fa fa-refresh"></i></a>
        </li>
          <li class="dropdown profile_menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><div class=avatar style="height: 30px; width:30px;"><img alt="Avatar" src="<?=$this->get_gravatar($_SESSION[worker][mail],30)?>" /></div><span style='padding-left:10px;'><?=$_SESSION[worker][name]?></span> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="modify_worker.php?worker_id=<?=$_SESSION[worker][worker_id]?>">My Account</a></li>
              <li><a href="worker_detail.php?worker_id=<?=$_SESSION[worker][worker_id]?>">Profile</a></li>
              
              <li class="divider"></li>
              <li><a href="bartlby_action.php?action=logout">Sign Out</a></li>
            </ul>
          </li>
        </ul>			
        <ul class="nav navbar-nav not-nav">
        <li class=button style='margin-top: 10px;'>
        	Auto Refresh: <input type='checkbox' id=toggle_reload checked  style='height:10px' class=icheck>
        </li>
      
         <li class="button dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bookmark"></i></a>
            <ul class="dropdown-menu" style='max-width: 400px;'>
            	<li>
            			<ul class="foot"><li><a href="#">Favorites </a></li></ul>           
            	</li>
              <li style='width: 320px;'>
                <div class="nano nscroller">
                  <div class="content" style='xwidth: 300px;'>
                    <ul style='list-style-type: none;' id=bartlby_basket>
                      
                    </ul>
                  </div>
                </div>
                     
              </li>
            </ul>
          </li>
          
          
  
        </ul>
        
                
                
        <div class="form-group" style="display:inline;">
          <div class="input-group" style='padding: 11px 0;'>
            <span class="input-group-addon"><span class="fa fa-search"></span></span>
            
            

            <div  id="quick_look" style="z-index:100"><input onkeyup="buffer_suggest.modified('qlook', 'xajax_QuickLook');" id=qlook autocomplete='off' type=text name="qlook" class="form-control">
            
            <div id='quick_suggest' style='z-index: 1000; position:absolute; background-color:white; width: 100%;margin-top: 50px;' class=container-fluid></div>
            
            </div>
            
            
          </div>
          
        </div>


      </div><!--/.nav-collapse animate-collapse -->
    </div>
  </div>
  
    
	<div class="cl-mcont">

<!-- HEADER + BREAD
				<div class="page-head">
			<h2><?=$this->BoxTitle?></h2>
			<ol class="breadcrumb">
			  <li><a href="#">Home</a></li>
			  <li><a href="#">Category</a></li>
			  <li class="active">Sub Category</li>
			</ol>
		</div>		
-->
			<!-- <div id='bartlby_basket'></div> -->
			
	
		 	<div class=row-fluid>
			
			<?=$this->BTTABBAR?>
			<?=$this->BTUIOUTSIDE?>

			
			</div>
			    
		

		
	</div>
	
	</div> 
	
</div>
<!-- Right Chat-->




	
		

	<!-- external javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->







</body>
<script>xajax_ExtensionAjax('Basket', 'updateBasket');</script>
</html>



<!--
THINGS TO RECOVER:
			
			<div class="span2 main-menu-span" style='width: 218px;'>
				
				<div class="well nav-collapse sidebar-nav" style='width: 218px; padding-bottom:10px;'>
					
				<?=$this->BTLEXTMENU?>
				
				</div>
				<div id='bartlby_basket'></div>
			</div>




	<div class="navbar">
	
		<div class="navbar-inner">
				
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a  href="overview.php"> <img src="themes/classic/images/btl-logo.gif" /> </a>
				
				
			
				
				
				
				
				<div class="top-nav nav-collapse">
					<ul class="nav">
						
						
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	-->
	

