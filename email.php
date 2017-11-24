<?php

	// localhost/SE/email.php?name=Aakash Sangwan&amt=50&email_to=aakashsangwan99@gmail.com
	session_start();
	require_once "Mail.php";

	extract($_GET);

	$username = 'pockets1418@gmail.com';
	$password = 'seproject';
	$smtpHost = 'ssl://smtp.gmail.com';
	$smtpPort = '465';
	$to = $email_to;
	$from =  'pockets1418@gmail.com';

	$subject = 'Payment Reminder';
	$successMessage = 'Message successfully sent!';


	$replyTo = '';

	// echo $to;
	// echo $amt;
	// echo $name;

	$body = 'Hi '. $name .",\r\n\n    This is a reminder that you owe " . $_SESSION['email_id'] . " money, amounting to " . $amt . ", which is requested to be settled.";


	$headers = array(
	    'From' => $name . " <" . $from . ">",
	    'To' => $to,
	    'Subject' => $subject
	);
	$smtp = Mail::factory('smtp', array(
	            'host' => $smtpHost,
	            'port' => $smtpPort,
	            'auth' => true,
	            'username' => $username,
	            'password' => $password
	        ));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
	    echo($mail->getMessage());
	} else {
	    echo($successMessage);
	}
?>