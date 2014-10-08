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
	
	<link href="themes/classic/css/bootstrap.css" rel="stylesheet">
	
	<xlink id="bs-css" href="themes/classic/css/bootstrap-theme.css" rel="stylesheet">
	<xlink id="bs-css" href="themes/classic/css/todc-bootstrap.css" rel="stylesheet">
	<link id="bs-css" href="themes/classic/css/docs.css" rel="stylesheet">

	<link href="themes/classic/btl.css" rel="stylesheet">

	<link href="themes/classic/css/selectize.bootstrap3.css" rel="stylesheet">


	<xlink href="themes/classic/css/charisma-app.css" rel="stylesheet">
	<link href="themes/classic/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
	<link href='themes/classic/css/fullcalendar.css' rel='stylesheet'>
	<link href='themes/classic/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	<link href='themes/classic/css/chosen.css' rel='stylesheet'>
	<link href='themes/classic/css/chosen-bootstrap.css' rel='stylesheet'>
	<link href='themes/classic/css/uniform.default.css' rel='stylesheet'>
	<link href='themes/classic/css/colorbox.css' rel='stylesheet'>
	<link href='themes/classic/css/jquery.cleditor.css' rel='stylesheet'>
	<link href='themes/classic/css/jquery.noty.css' rel='stylesheet'>
	<link href='themes/classic/css/noty_theme_default.css' rel='stylesheet'>
	<link href='themes/classic/css/elfinder.min.css' rel='stylesheet'>
	<link href='themes/classic/css/elfinder.theme.css' rel='stylesheet'>
	<link href='themes/classic/css/jquery.iphone.toggle.css' rel='stylesheet'>
	<link href='themes/classic/css/opa-icons.css' rel='stylesheet'>
	<link href='themes/classic/css/uploadify.css' rel='stylesheet'>
	<link href='themes/classic/css/jquery.terminal.css' rel='stylesheet'>
	<link href='themes/classic/css/dataTables.tableTools.css' rel='stylesheet'>
	

	<!-- jQuery -->
	<script src="themes/classic/js/jquery-1.7.2.min.js"></script>

	<script src="themes/classic/js/bootstrap.js"></script>

	<script src="themes/classic/js/selectize.js"></script>

	<!-- jQuery UI -->
	<script src="themes/classic/js/jquery-ui-1.8.21.custom.min.js"></script>
	<!-- transition / effect library -->
	<script src="themes/classic/js/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="themes/classic/js/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<xscript src="themes/classic/js/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="themes/classic/js/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="themes/classic/js/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="themes/classic/js/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="themes/classic/js/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="themes/classic/js/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="themes/classic/js/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="themes/classic/js/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="themes/classic/js/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="themes/classic/js/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<script src="themes/classic/js/bootstrap-tour.js"></script>
	<!-- library for cookie management -->
	<script src="themes/classic/js/jquery.cookie.js"></script>
	<script src="themes/classic/js/jquery-ui-timepicker-addon.js"></script>
	<!-- calander plugin -->
	<script src='themes/classic/js/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='themes/classic/js/jquery.dataTables.min.js'></script>
	<script src='themes/classic/js/dataTables.tableTools.js'></script>
	<script src='themes/classic/js/dataTables.bootstrap.js'></script>

	<!-- chart libraries start -->
	<script src="themes/classic/js/excanvas.js"></script>
	<script src="themes/classic/js/jquery.flot.min.js"></script>
	<script src="themes/classic/js/jquery.flot.pie.min.js"></script>
	<script src="themes/classic/js/jquery.flot.stack.js"></script>
	<script src="themes/classic/js/jquery.flot.resize.min.js"></script>
	<script src="themes/classic/js/jquery.flot.threshold.js"></script>
	<script src="themes/classic/js/jquery.flot.selection.js"></script>
	<script src="themes/classic/js/jquery.flot.tooltip.js"></script>
	<!-- chart libraries end -->
  
	<!-- select or dropdown enhancer -->
	<script src="themes/classic/js/jquery.chosen.min.js"></script>
	<script src="themes/classic/js/ajaxchosen.js"></script>
	
	<!--GAUGE-->
	<script src="themes/classic/js/raphael-min.js"></script>
	<script src="themes/classic/js/justgauge.js"></script>
	
	<!-- checkbox, radio, and file input styler -->
	<script src="themes/classic/js/jquery.uniform.min.js"></script>
	<!-- plugin for gallery image view -->
	<script src="themes/classic/js/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<script src="themes/classic/js/jquery.cleditor.min.js"></script>
	<!-- notification plugin -->
	<script src="themes/classic/js/jquery.noty.js"></script>
	<!-- file manager library -->
	<script src="themes/classic/js/jquery.elfinder.min.js"></script>
	<!-- star rating plugin -->
	<script src="themes/classic/js/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="themes/classic/js/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="themes/classic/js/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="themes/classic/js/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="themes/classic/js/jquery.history.js"></script>
	<!-- application script for Charisma demo -->
	<script src="themes/classic/js/charisma.js"></script>
	<script src="themes/classic/js/jquery.terminal-0.8.8.min.js"></script>	
	
	


    <script type="text/javascript" src="./jsrrd/binaryXHR.js"></script>
    <script type="text/javascript" src="./jsrrd/rrdFile.js"></script>

    <!-- rrdFlot class needs the following four include files !-->
    <script type="text/javascript" src="./jsrrd/rrdFlotSupport.js"></script>
    <script type="text/javascript" src="./jsrrd/rrdFlot.js"></script>

	<script type="text/javascript" src="js/btl.js"></script>


	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="themes/classic/img/favicon.ico">
		

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylsheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	
	<?
		echo $this->BTUI_SCRIPTS;
	?>
</head>

<body>


	
	<?
		if($Bartlby_CONF_IDX > 0) {
	?>
	<div class="alert alert-warning" style='margin-bottom: 0px;'>
							<button type="button" class="close" data-dismiss="alert">×</button>
							You are on a remote Node (<?=$Bartlby_CONF_DisplayName?>)!!
						</div>
	<?
		}
	?>




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
          <div class="side-user">
            <div class="avatar"><img src="https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg" alt="Avatar" /></div>
            <br>
            <div class="info">
              <p>40 <b>GB</b> / 100 <b>GB</b><span><a href="#"><i class="fa fa-plus"></i></a></span></p>
              <div class="progress progress-user">
                <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                  <span class="sr-only">50% Complete (success)</span>
                </div>
              </div>
            </div>
          </div>
          <ul class="cl-vnavigation">
            <li><a href="#"><i class="fa fa-home"></i><span>Dashboard</span></a>
              <ul class="sub-menu">
                <li  ><a href="index.html">Version 1</a></li>
                <li  ><a href="dashboard2.html"><span class="label label-primary pull-right">New</span>Version 2</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-desktop"></i><span>Layouts</span></a>
              <ul class="sub-menu">
                <li><a href="layout-boxed.html"><span class="label label-primary pull-right">New</span>Boxed Layout</a></li>
                <li><a href="layout-topbar.html"><span class="label label-primary pull-right">New</span>Top Menu</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-smile-o"></i><span>UI Elements</span></a>
              <ul class="sub-menu">
                <li  ><a href="ui-elements.html">General</a></li>
                <li  ><a href="ui-alerts.html">Alerts</a></li>
                <li  ><a href="ui-porlets.html"><span class="label label-primary pull-right">New</span>Porlets</a></li>
                <li  ><a href="ui-buttons.html">Buttons</a></li>
                <li  ><a href="ui-modals.html">Modals</a></li>
                <li  ><a href="ui-notifications.html">Notifications</a></li>
                <li  ><a href="ui-tiles.html"><span class="label label-primary pull-right">New</span>Tiles</a></li>
                <li  ><a href="ui-progress.html">Progress Bars</a></li>
                <li  ><a href="ui-icons.html">Icons</a></li>
                <li  ><a href="ui-grid.html">Grid</a></li>
                <li  ><a href="ui-tabs-accordions.html">Tabs & Accordions</a></li>
                <li  ><a href="ui-nestable-lists.html">Nestable Lists</a></li>
                <li  ><a href="ui-treeview.html">Tree View</a></li>
                <li  ><a href="ui-calendar.html"><span class="label label-primary pull-right">New</span>Calendar</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-list-alt"></i><span>Forms</span></a>
              <ul class="sub-menu">
                <li  ><a href="form-elements.html">Components</a></li>
                <li  ><a href="form-multiselect.html"><span class="label label-primary pull-right">New</span>Multiselect</a></li>
                <li  ><a href="form-validation.html">Validation</a></li>
                <li  ><a href="form-wizard.html">Wizard</a></li>
                <li  ><a href="form-masks.html">Input Masks</a></li>
                <li  ><a href="form-wysiwyg.html">WYSIWYG Editor</a></li>
                <li  ><a href="form-upload.html">Multi Upload</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-table"></i><span>Tables</span></a>
              <ul class="sub-menu">
                <li  ><a href="tables-general.html">General</a></li>
                <li  ><a href="tables-datatables.html"><span class="label label-primary pull-right">New</span>Data Tables</a></li>
                <li  ><a href="tables-xeditable.html"><span class="label label-primary pull-right">New</span>X-Editable</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-map-marker nav-icon"></i><span>Maps</span></a>
              <ul class="sub-menu">
                <li  ><a href="maps.html">Maps</a></li>
                <li  ><a href="vector-maps.html">Vector Maps</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-envelope nav-icon"></i><span>Email</span></a>
              <ul class="sub-menu">
                <li  ><a href="email-inbox.html">Inbox</a></li>
                <li  ><a href="email-read.html">Email Detail</a></li>
                <li  ><a href="email-compose.html"><span class="label label-primary pull-right">New</span>Email Compose</a></li>
              </ul>
            </li>
            <li  ><a href="typography.html"><i class="fa fa-text-height"></i><span>Typography</span></a></li>
            <li  ><a href="charts.html"><i class="fa fa-bar-chart-o"></i><span>Charts</span></a></li>
            <li><a href="#"><i class="fa fa-file"></i><span>Pages</span></a>
              <ul class="sub-menu">
                <li class="active" ><a href="pages-blank.html">Blank Page</a></li>
                <li  ><a href="pages-blank-header.html">Blank Page Header</a></li>
                <li  ><a href="pages-blank-aside.html">Blank Page Aside</a></li>
                <li  ><a href="pages-blank-aside-header.html"><span class="label label-primary pull-right">New</span>Blank Page Aside Header</a></li>
                <li  ><a href="pages-profile.html"><span class="label label-primary pull-right">New</span>Profile</a></li>
                <li><a href="pages-login.html">Login</a></li>
                <li><a href="pages-sign-up.html"><span class="label label-primary pull-right">New</span>Sign Up</a></li>
                <li><a href="pages-forgot.html"><span class="label label-primary pull-right">New</span>Forgot Password</a></li>
                <li><a href="pages-404.html">404 Page</a></li>
                <li><a href="pages-500.html">500 Page</a></li>
                <li  ><a href="pages-tour.html"><span class="label label-primary pull-right">New</span>Tour Guide</a></li>
                <li  ><a href="pages-gallery.html">Gallery</a></li>
                <li  ><a href="pages-search.html"><span class="label label-primary pull-right">New</span>Search</a></li>
                <li  ><a href="pages-timeline.html">Timeline</a></li>
                <li  ><a href="pages-code-editor.html">Code Editor</a></li>
              </ul>
            </li>

          </ul>
        </div>
      </div>
      <div class="text-right collapse-button" style="padding:7px 9px;">
        <input type="text" class="form-control search" placeholder="Search..." />
        <button id="sidebar-collapse" class="btn btn-default" style=""><i style="color:#fff;" class="fa fa-angle-left"></i></button>
      </div>
    </div>
  </div>
	<div class="container-fluid" id="pcont">
   <!-- TOP NAVBAR -->
  <div id="head-nav" class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-collapse">
        <ul class="nav navbar-nav navbar-right user-nav">
          <li class="dropdown profile_menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img alt="Avatar" src="images/avatar6-2.jpg" /><span>Jane Smith</span> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">My Account</a></li>
              <li><a href="#">Profile</a></li>
              <li><a href="#">Messages</a></li>
              <li class="divider"></li>
              <li><a href="#">Sign Out</a></li>
            </ul>
          </li>
        </ul>			
        <ul class="nav navbar-nav not-nav">
          <li class="button dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-inbox"></i></a>
            <ul class="dropdown-menu messages">
              <li>
                <div class="nano nscroller">
                  <div class="content">
                    <ul>
                      <li>
                        <a href="#">
                          <img src="images/avatar2.jpg" alt="avatar" /><span class="date pull-right">13 Sept.</span> <span class="name">Daniel</span> Hey! How are you?
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <img src="images/avatar_50.jpg" alt="avatar" /><span class="date pull-right">20 Oct.</span><span class="name">Adam</span> Hi! Can you fix my phone?
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <img src="images/avatar4_50.jpg" alt="avatar" /><span class="date pull-right">2 Nov.</span><span class="name">Michael</span> Regards!
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <img src="images/avatar3_50.jpg" alt="avatar" /><span class="date pull-right">2 Nov.</span><span class="name">Lucy</span> Hello, my name is Lucy
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <ul class="foot"><li><a href="#">View all messages </a></li></ul>           
              </li>
            </ul>
          </li>
          <li class="button dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i><span class="bubble">2</span></a>
            <ul class="dropdown-menu">
              <li>
                <div class="nano nscroller">
                  <div class="content">
                    <ul>
                      <li><a href="#"><i class="fa fa-cloud-upload info"></i><b>Daniel</b> is now following you <span class="date">2 minutes ago.</span></a></li>
                      <li><a href="#"><i class="fa fa-male success"></i> <b>Michael</b> commented on your link <span class="date">15 minutes ago.</span></a></li>
                      <li><a href="#"><i class="fa fa-bug warning"></i> <b>Mia</b> commented on post <span class="date">30 minutes ago.</span></a></li>
                      <li><a href="#"><i class="fa fa-credit-card danger"></i> <b>Andrew</b> sent you a request <span class="date">1 hour ago.</span></a></li>
                    </ul>
                  </div>
                </div>
                <ul class="foot"><li><a href="#">View all activity </a></li></ul>           
              </li>
            </ul>
          </li>
          <li class="button"><a class="toggle-menu menu-right push-body" href="javascript:;"><i class="fa fa-comments"></i></a></li>				
        </ul>

      </div><!--/.nav-collapse animate-collapse -->
    </div>
  </div>
  
    
	<div class="cl-mcont">

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

