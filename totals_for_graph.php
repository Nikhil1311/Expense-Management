<?php

// SELECT xyz.`category`, SUM(xyz.amount) FROM (SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'food' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'utilities' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'rent' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'groceries' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'entertainment' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'others') AS xyz WHERE xyz.`groupId` = 1 OR xyz.`groupId` = 2 GROUP BY xyz.`category`


// SELECT xyz.`groupId`, xyz.`category`, SUM(xyz.amount) FROM (SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'food' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'utilities' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'rent' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'groceries' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'entertainment' UNION SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = 'user1@gmail.com' AND `category` = 'others') AS xyz GROUP BY xyz.groupId, xyz.`category`


include('init.php');

$email_id = $_SESSION["email_id"];
extract($_POST);

// $email_id = "user1@gmail.com";
// $food = "0";
// $rent = "1";
// $others = "0";
// $utilities = "0";
// $groceries = "0";
// $entertainment = "0";
// $personal = "0";
// $group_list = "1";


$selected_groups = "";
if(isset($group_list))
{
	if($personal == 1)
	{
		$selected_groups = $group_list . ",0";
	}
	else
	{
		$selected_groups = $group_list;
	}
}

else
{
	if($personal == 1)
	{
		$selected_groups = "0";
	}
}

// echo($selected_groups);

$query = "";

$q1 = "";
$q2 = "";
$q3 = "";
$q4 = "";
$q5 = "";
$q6 = "";

if($food == '1')
{
	$q1 = "SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = '$email_id' AND `category` = 'food'";

	if($query == "")
		$query = $q1;
	else
		$query = $query . " UNION " . $q1;
}

if($utilities == '1')
{
	$q2 = "SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = '$email_id' AND `category` = 'utilities'";

	if($query == "")
		$query = $q2;
	else
		$query = $query . " UNION " . $q2;
}

if($rent == '1')
{
	$q3 = "SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = '$email_id' AND `category` = 'rent'";
	if($query == "")
		$query = $q3;
	else
		$query = $query . " UNION " . $q3;
}

if($groceries == '1')
{
	$q4 = "SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = '$email_id' AND `category` = 'groceries'";
	if($query == "")
		$query = $q4;
	else
		$query = $query . " UNION " . $q4;
}

if($entertainment == '1')
{
	$q5 = "SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = '$email_id' AND `category` = 'entertainment'";
	if($query == "")
		$query = $q5;
	else
		$query = $query . " UNION " . $q5;
}

if($others == '1')
{
	$q6 = "SELECT `expenseId`, `emailId`, `amount`, `category`, `groupId` FROM `personalexpense` WHERE `emailId` = '$email_id' AND `category` = 'others'";
	if($query == "")
		$query = $q6;
	else
		$query = $query . " UNION " . $q6;
}

// echo "<br>";
// echo $query;
// echo "<br>";

if($query != "")
{	
	$q = "";
	if($selected_groups == "")
		$q = "SELECT xyz.`groupId`, xyz.`category`, SUM(xyz.`amount`) AS total FROM (" . $query . ") AS xyz GROUP BY xyz.groupId, xyz.`category` ORDER BY xyz.groupId";
	
	else
		$q = "SELECT xyz.`groupId`, xyz.`category`, SUM(xyz.`amount`) AS total FROM (" . $query . ") AS xyz WHERE xyz.`groupId` IN (" . $selected_groups . ") GROUP BY xyz.groupId, xyz.`category` ORDER BY xyz.groupId";

	// echo "<br>";
	// echo $q;
	// echo "<br>";
	$result = run_big_query($q);
	echo json_encode($result);
}

?>