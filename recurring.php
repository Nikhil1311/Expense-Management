<?php
		session_start();
		include('connect.php');
 		/*$user = 'root' ;
	    $pass = '' ;
        $db = 'pockets';
        $db = new mysqli('localhost' , $user ,$pass , $db) or die("unable to connect !!");*/
		
		# echo json_encode($_POST);
		
		$email_id = $_SESSION['email_id'];

		$description = $_POST['description'];
		$amount = $_POST['amount'];
		$date = $_POST['date'];
		$category = $_POST['finalCategory'];
		# echo gettype($description);
		
		$dat = new DateTime();
		$tStmp = (string) $dat->getTimestamp();
		$evName = $description.$tStmp;
		
	if($category == 'DAY')
		$sql = "CREATE  EVENT `$evName` ON SCHEDULE EVERY 1 DAY STARTS '$date' DO INSERT INTO `personalexpense`(`emailId`, `description`, `amount`, `groupId`, `date`, `category`) VALUES ('user1@gmail.com','$description',$amount,0,'$date','$description')";
	
	else if($category == 'WEEK')
		$sql = "CREATE  EVENT `$evName` ON SCHEDULE EVERY 1 WEEK STARTS '$date' DO INSERT INTO `personalexpense`(`emailId`, `description`, `amount`, `groupId`, `date`, `category`) VALUES ('user1@gmail.com','$description',$amount,0,'$date','$description')";

	if($category == 'MONTH')
		$sql = "CREATE  EVENT `$evName` ON SCHEDULE EVERY 1 MONTH STARTS '$date' DO INSERT INTO `personalexpense`(`emailId`, `description`, `amount`, `groupId`, `date`, `category`) VALUES ('user1@gmail.com','$description',$amount,0,'$date','$description')";

	$query = mysqli_query($con , $sql);
	
	if($query) // if succesfully inserted expense in the database
	{
		echo "<script>alert('Recurring expense added succesfully');
		window.location.href='user_home.php';
		</script>"; 
	}

	else 
	{
		echo "<script>alert('Unsuccesfull');
		window.location.href='user_home.php';
		</script>"; 
	}
?>
