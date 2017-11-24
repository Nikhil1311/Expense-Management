<?php

include('init.php');

extract($_POST);

if(sanitize($password_new) === sanitize($password_new_confirm))
{
	$log_in = logged_in();
	if(log_in !== false)
	{
		$correct = login($log_in, sanitize($password_old));
		if($correct !== false)
		{
			$result = update_password($correct, sanitize($password_new));
			if($result)
			{
				echo "<script>alert('Passwords Successfully changed');
				window.location.href='settings.php';
				</script>"; 
			}

			else
			{
				echo "<script>alert('Failed to change password, Please try again!!!');
				window.location.href='settings.php';
				</script>";
			}
		}
	}
}

else
{
	echo "<script>alert('New Passwords Do not Match, Please try Again');
		window.location.href='settings.php';
		</script>"; 
}
?>