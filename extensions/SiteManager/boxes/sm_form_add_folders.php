<div class=form-horizontal>
<?
	echo $layout->FormBox(array(
				0=>"Additional-Folders - PULL",
				1=>$layout->TextArea("additional_folders_pull") . "REMOTEPATH:LOCALPATH (on per Line)"
			), true);

		echo $layout->FormBox(array(
				0=>"Additional-Folders - PUSH",
				1=>$layout->TextArea("additional_folders_push") . "LOCALPATH:REMOTEPATH (on per Line)"
			), true);


?>

</div>