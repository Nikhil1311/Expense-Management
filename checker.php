<?php
	include 'init.php';
	$x = logged_in();
	if($x){echo 'logged in as ' . $x;}
	else echo 'not logged in';
?>