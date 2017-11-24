<?php
include('init.php');

if(empty($_POST) === false) // ensure form not empty
{
	$email = $_POST['email'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$password = $_POST['password'];
	$password1 = $_POST['password1'];

	if(!(empty($email) || empty($fname) || empty($lname) || empty($password) || empty($password1))){
	
		if(user_exists($email) === true) // if user has already created an account with this emailId
		{
			echo "<script>alert('You have already registered with this username');
			window.location.href='login.html';
			</script>";
		}
		
		else // create a new user, add user to the system
		{
			$ans = insert_user($email, $fname, $lname, $password);
			if($ans === true)
			{
				$_SESSION['emailId'] = $email;
				header('Location: user_home.php');
				exit();
			}
			else{
				echo 'Error in insertion';
			}
		}
	}

	else
	{
		echo "<script>alert('Enter All Details');
			window.location.href='signup.html';
			</script>";	
	}
}

?>