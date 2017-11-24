<?php
include('init.php');

if(empty($_POST) === false){ // form not empty
	$description = $_POST['description'];
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$finalCategory = $_POST['finalCategory'];
	
	if(!(empty($description) || empty($amount) || empty($date) || empty($finalCategory))) // ensure all details entered
	{
		$ans = add_expense($description, $amount, $date, $finalCategory); // add expense to the database and display status messages accordingly
		if($ans)
		{
			echo "<script>alert('Expense Successfully Updated');
			window.location.href='user_home.php';
			</script>"; 
		}
		else
		{
			echo "<script>alert('Failed to Add Expense');
			window.location.href='user_home.php';
			</script>"; 
		}
	}
	else
	{
		echo "<script>alert('Enter All Details');
		window.location.href='user_home.php';
		</script>"; 
	}
	$_POST = array();
}
print_r($_POST);
?>