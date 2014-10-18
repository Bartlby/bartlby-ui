<div class=form-horizontal>


<?
	echo $layout->FormBox(array(
				0=>"Local DB User",
				1=>$layout->Field("local_db_user", "text")
			), true);
	echo $layout->FormBox(array(
				0=>"Local DB Password",
				1=>$layout->Field("local_db_pass", "text")
			), true);
	echo $layout->FormBox(array(
				0=>"Local DB Name",
				1=>$layout->Field("local_db_name", "text")
			), true);
	echo $layout->FormBox(array(
				0=>"Local DB Host",
				1=>$layout->Field("local_db_host", "text")
			), true);	
?>
</div>