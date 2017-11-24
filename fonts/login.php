<?php
include('init.php');

if(empty($_POST) === false) // input not empty - both fields not empty
{
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	if(empty($email) === true || empty($password) === true)
	{
		$errors[] = 'Enter a username and password';
	}
	
	else if(user_exists($email) === false) // check if the emailId is present in the database
	{
		$errors[] = 'Cannot Find the Username';
	}

	/* else if(user_active($email) === false)
	{
		$errors[] = 'You have not activated your account';
	} */

	else{
		$login = login($email, $password); // function to authenticate Id and password
		if($login == false)
		{
			$errors[] = 'Username and Password combination is incorrect';
		}
		else
		{
			$_SESSION['email_id'] = $login; // set $_SESSION variable if the login is successful
			header('Location: user_home.php'); // redirect to the user home page
			exit();
		}
	}
	
	if(count($errors)){ // if failed to log the user in, throw an error
		// header('Location: login.html');
		echo "<script>alert('$errors[0]');
		window.location.href='login.html';
		</script>"; 
	}
}
?>