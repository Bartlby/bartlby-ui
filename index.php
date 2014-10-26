
<?
include "config.php";
if(count($confs) > 0) {
			$drpd .= "<select name='btl_instance_id' onChange='btl_change(this)' data-rel='chosen'>";
			for($x=0; $x<count($confs); $x++) {
				$sel = " ";
				if($_SESSION[instance_id] == $x) {
					$sel = "selected";
				}
				
				
				$r = "(LOCAL)";
				$read_only = "";
				$rw="green";
				if($confs[$x][remote]) {
					 $r = "(REMOTE)";
					if($confs[$x][db_sync] == false) {
						 $rw = "grey";
					}
				}
				$drpd .= "<option style='background-color: $rw' value=" . $x ." $sel>" . $confs[$x][display_name] . " $r</option>";
			}
			$drpd .= "</select>";
		}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bartlby</title>
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
	
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>

	<link href="themes/classic/css/jquery-ui-1.10.0.custom.css" rel="stylesheet"/>
	
	<link href='themes/classic/css/fullcalendar.css' rel='stylesheet'>
	<link href='themes/classic/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	
	<link href="themes/classic/css/bootstrap.css" rel="stylesheet">
	
	
	
	<link href='themes/classic/css/slider.css' rel='stylesheet'>
	
	<link href="themes/classic/btl.css" rel="stylesheet">

	<link href="themes/classic/css/selectize.bootstrap3.css" rel="stylesheet">


	
	
	<link href='themes/classic/css/jquery.terminal.css' rel='stylesheet'>
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

	<link href='themes/classic/css/sweet-alert.css' rel='stylesheet'>

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="themes/classic/img/favicon.ico">
		

	<link href="themes/classic/css/font-awesome.min.css" rel="stylesheet">	


	<script>
	$(document).ready(function() {
		$("#login_btn").click(function() {
			var hash=CryptoJS.SHA1($("#password").val());
			console.log(hash.toString());
		
			//$("#password").val();
			$.post( "login.php", {
					"login_username": $("#login_username").val(),
					"password": hash.toString(),
					"btl_instance_id": $('[name=btl_instance_id]').val()
			})
			.done(function() {
				swal("OK", "Login Successfull", "success");
				delay(function() {
					document.location.href='overview.php';
				}, 500);
			})
			.fail(function() {
				swal("Error", "Login Failed", "error");	
			});
			 
		});
	});

	</script>

</head>

<body class="texture">

<div id="cl-wrapper" class="login-container">

	<div class="middle-login">
		<div class="block-flat">
			<div class="header">							
				<h3 class="text-center">Bartlby</h3>
			</div>
			<div>
				<form style="margin-bottom: 0px !important;" class="form-horizontal" action="login.php" id=lform method=POST>
					<div class="content">
						<h4 class="title">Login Access</h4>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" name="login_username" id="login_username" placeholder="Username" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" name="password" id="password" placeholder="Password" id="password" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
										<?
											echo $drpd;
										?>	
									</div>
								</div>
							</div>
							
					</div>
					<div class="foot">
						<button class="btn btn-primary" data-dismiss="modal" type="button" id=login_btn>Log me in</button>
					</div>
					
				</form>
			</div>
		</div>
		<div class="text-center out-links"><a href="#">bartlby.org</a></div>
	</div> 
	
</div>


</body>
</html>


