
<div class=form-horizontal>


	<?
		echo $layout->FormBox(array(
				0=>"External Name",
				1=>$layout->Field("orch_ext_name", "text") . "to send uptstreams, and connect to DB"
			), true);
		echo $layout->FormBox(array(
				0=>"DB User",
				1=>$layout->Field("orch_db_user", "text") . "eg.: orch"
			), true);
		echo $layout->FormBox(array(
				0=>"DB Password",
				1=>$layout->Field("orch_db_pw", "text")
			), true);
		echo $layout->FormBox(array(
				0=>"DB Name",
				1=>$layout->Field("orch_db_name", "text") 
			), true);
		echo $layout->FormBox(array(
				0=>"ORCH ext PORT",
				1=>$layout->Field("orch_ext_port", "text") . " e.g.: 9031"
			), true);
		echo $layout->FormBox(array(
				0=>"Orch Master-PW",
				1=>$layout->Field("orch_master_pw", "text") . "to send uptstreams, and connect to DB"
			), true);

	?>
	
</div>

