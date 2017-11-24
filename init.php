<?php
session_start();
// error_reporting(0); // to prevent giving away the directory structure in case of errors

include('connect.php');
include('user.php');

$errors = array(); // hold errors if any in the files validating user inputs
?>