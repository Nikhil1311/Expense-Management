<?php



	move_uploaded_file($_FILES["image"]["tmp_name"],$_FILES["image"]["name"]);
	echo $_FILES["image"]["name"];



?>