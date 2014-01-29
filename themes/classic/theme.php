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
	<link id="bs-css" href="themes/classic/css/bootstrap-simplex.css" rel="stylesheet">
	
	<?=$this->XAJAX?>
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
	<link href="themes/classic/btl.css" rel="stylesheet">
	<link href="themes/classic/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="themes/classic/css/charisma-app.css" rel="stylesheet">
	<link href="themes/classic/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
	<link href='themes/classic/css/fullcalendar.css' rel='stylesheet'>
	<link href='themes/classic/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	<link href='themes/classic/css/chosen.css' rel='stylesheet'>
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


	<!-- jQuery -->
	<script src="themes/classic/js/jquery-1.7.2.min.js"></script>
	<!-- jQuery UI -->
	<script src="themes/classic/js/jquery-ui-1.8.21.custom.min.js"></script>
	<!-- transition / effect library -->
	<script src="themes/classic/js/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="themes/classic/js/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="themes/classic/js/bootstrap-modal.js"></script>
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
		
</head>

<body>

	
		<!-- topbar starts -->
	<div class="navbar">
	
		<div class="navbar-inner">
				
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a  href="overview.php"> <img src="themes/classic/images/btl-logo.gif" /> </a>
				
				<div class="pull-right">
					<button class="btn btn-small" onClick="document.location.href='bartlby_action.php?action=reload';"><i class="icon-refresh"></i> Reload</button>
					<button class="btn btn-small" onClick="document.location.href='logout.php';"><i class="icon-remove"></i> Logout</button>
					<?=$this->BTL_INSTANCES?>
				</div>
				<div class="pull-right" style="padding-top: 8px;">
					<div  id="quick_look" style="z-index:100"><font size=1>Auto Refresh<input type='checkbox' id=toggle_reload checked  style='height:10px'>   Quick look<input onkeyup="buffer_suggest.modified('qlook', 'xajax_QuickLook');" id=qlook autocomplete='off' type=text name="qlook" style="border:solid black 1px;font-size:10px; height:17px"><div id='quick_suggest' style='z-index: 1000; background-color: white;position:absolute;width:550px'></div></div>
				</div>
				
			
				
				
				
				<!-- user dropdown starts -->
				
				<!-- user dropdown ends -->
				
				<div class="top-nav nav-collapse">
					<ul class="nav">
						
						
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- left menu starts -->
			<div class="span2 main-menu-span" style='width: 218px;'>
				
				<div class="well nav-collapse sidebar-nav" style='width: 218px; padding-bottom:10px;'>
					
				<?=$this->BTLEXTMENU?>
				
				</div><!--/.well -->
				<div id='bartlby_basket'></div>
			</div><!--/span-->
			<!-- left menu ends -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10" style='margin-left: 5px !important;'>
			<!-- content starts -->
			
			
			
			<div class="ui_performance" id="reload"><font size=1>UI-Version: <font size=1><?=$this->UIVERSION?></font> Page Render:<?=$this->BTUITIME?> secs &nbsp; Memory Used: <?=$this->BTMEMUSAGE?>MB &nbsp;&nbsp;&nbsp;&nbsp;<?=$this->SERVERTIME?> &nbsp; &nbsp;&nbsp;&nbsp;</div>
			<?=$this->BTTABBAR?>
			<?=$this->BTUIOUTSIDE?>
			    
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		<hr>

		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">�</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>

		<footer>
				<center>
										<table>
											<tr>
												<td>
													
													<font style='font-size:8pt; color:#000000'><a href='http://www.bartlby.org/' target='_blank'>bartlby</A> is a GPLv2 product of <a href='http://www.januschka.com/' target='_blank'>januschka.com</A><?=$this->RELNOT?></font>
												</td>
											</tr>
										</table>
										</center>		
		</footer>
		
	</div><!--/.fluid-container-->

	<!-- external javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->


		<script>
		
			</script>
</body>
</html>


