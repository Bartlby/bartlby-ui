<div class=form-horizontal>


<?
	echo $layout->FormBox(array(
				0=>"Remote DB User",
				1=>$layout->Field("remote_db_user", "text")
			), true);
	echo $layout->FormBox(array(
				0=>"Remote DB Password",
				1=>$layout->Field("remote_db_pass", "text")
			), true);
	echo $layout->FormBox(array(
				0=>"Remote DB Name",
				1=>$layout->Field("remote_db_name", "text")
			), true);
	echo $layout->FormBox(array(
				0=>"Remote DB Host",
				1=>$layout->Field("remote_db_host", "text")
			), true);	
?>
</div>