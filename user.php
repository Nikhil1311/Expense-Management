<?php

function sanitize($data)
{
	include('connect.php');
	return mysqli_real_escape_string($con, $data); // escape special characters in a string - used to prevent SQL Injection to some degree
}


function check_group_name($array)
{ // function returns an array of all group names that every group member being added to the new grouo is part of
  // intends to check if any user being added to a new group is already part of a group with the same name	

	include('connect.php');
	$email_id = $_SESSION['email_id'];
	if(!in_array($email_id, $array))
	{$array[] = $email_id;}
	$x = "('" . implode("','", $array) . "')"; // form a comma separated list of all email IDs
	$query = "SELECT * FROM `usergroups` WHERE `emailId` IN $x";
	$result = mysqli_query($con, $query);
	$ids = array();
	while($row = mysqli_fetch_array($result))
	{
		$ids[] = $row['groupId'];
	}

	if(count($ids)) // if databse if non empty
	{
		$x = "(" . implode(',',$ids) .")"; // form a comma separated list of group IDs
		$query_group_name = "SELECT `groupName` FROM `grouptable` WHERE `groupId` IN $x";
		$result_group_name = mysqli_query($con, $query_group_name);
		
		$groupnames = array();
		while($row = mysqli_fetch_array($result_group_name))
		{
			$groupnames[] = $row['groupName'];
		}
		return $groupnames;
	}

	else // if database is empty
	{
		return array();
	}
}

function create_group($groupname)
{
	// create a new group, with owner as the logged in user and return the AUTO_INCREMENT(ed) value of groupId for the new group
	include('connect.php');
	$email_id = $_SESSION['email_id'];
	$sql = "INSERT INTO grouptable(groupName, emailId) VALUES ('$groupname', '$email_id')";
	$query = mysqli_query($con, $sql);
	$sql = "SELECT `groupId` FROM `grouptable` ORDER BY `timestamp` DESC LIMIT 1";
	$query = mysqli_query($con, $sql);
	$new_id = mysqli_fetch_array($query)['groupId'];
	return $new_id;
}

function adduser_togroup($groupid, $emails, $exist)
{
	// add an array of users to the speified group ID
	include('connect.php');
	if($exist == 1) // if user exists in the database and has already signed up
	{
		$email_id = $_SESSION['email_id'];
		if(!in_array($email_id, $emails)) // checking if the user creating the group has included themselves in the list of group members
		{$emails[] = $email_id;} // add the logged in user to the group themselves

		foreach($emails as $email)
		{
			$sql = "INSERT INTO usergroups(emailId, groupId) VALUES ('$email', '$groupid')";
			$query = mysqli_query($con, $sql);
		}
	}

	else // if user does not have an account in the system
	{
		foreach($emails as $email)
		{
			$sql = "INSERT INTO pendingusers(emailId, groupId) VALUES ('$email', '$groupid')";
			$query = mysqli_query($con, $sql);
		}
	}
}


function add_expense($description, $amount, $date, $category)
{
	// add an expense to the database for the specified details
	include('connect.php');
	$email_id = $_SESSION['email_id'];
	$description = sanitize($description);
	$amount = sanitize($amount);
	$date = sanitize($date);
	$category = sanitize($category);
	
	$sql = "INSERT INTO personalexpense(emailId, description, amount, date, category) VALUES ('$email_id', '$description', '$amount', '$date', '$category')";
	
	$query = mysqli_query($con, $sql);
	if($query) // if succesfully inserted expense in the database
		return true;
	else // failed to insert into database
		return false;
}

function insert_user($email, $fname, $lname, $password){
	// add user to database to create an account for the user with the system
	include('connect.php');
	$email = sanitize($email); // sanitize the inputs before using them in an SQL Query
	$fname = sanitize($fname);
	$lname = sanitize($lname);
	$password = md5($password); // md5 hash is used for storing the password of the user

	$sql = "INSERT INTO user (emailId, firstName, lastName, password) VALUES ('$email', '$fname', '$lname', '$password')";
	
	$query = mysqli_query($con, $sql);
	if($query) // return true if user successfully inserted in the databse else false
	{
		return true;
	}
	
	else
	{
		return false;
	}
}

function logged_in()
{
	// check if user has logged in by checking the session variable
	if(isset($_SESSION['email_id'])) return $_SESSION['email_id'];
	else return false;
}


function user_exists($email)
{
	// function returns true if the user has signed up for an account with the system
	include('connect.php');
	$email = sanitize($email);
	$query = mysqli_query($con, "SELECT `emailId` FROM `user` WHERE `emailId` = '$email'");
	$num = mysqli_num_rows($query);
	return ($num == 1) ? true : false;
}

/* function user_active($email){
	include('connect.php');
	$email = sanitize($email);
	$query = mysqli_query($con, "SELECT COUNT(`emailId`) FROM `user` WHERE `emailId` = '$email' AND `active` = 1");
	return (mysqli_num_rows($query) == 1) ? true : false;
} */

function login($email, $password){
	// return the users email ID if login in successful else return false
	include('connect.php');
	$email = sanitize($email);
	$password = md5($password);
	$query = mysqli_query($con, "SELECT `emailId` FROM `user` WHERE `emailId` = '$email' AND `password` = '$password'");
	// $row=mysqli_fetch_array($query,MYSQLI_ASSOC);
	return (mysqli_num_rows($query) == 1) ? $email : false;
}

function getDues()
{
	include('connect.php');
	$email_id = $_SESSION['email_id'];

	$query = "SELECT `emailIdPaid`, `firstName`, `lastName`, `amount` FROM `dues` JOIN `user` ON `emailId` = `emailIdPaid` WHERE `emailIdPayee` = '$email_id' AND `amount` != 0";
	$result = mysqli_query($con, $query);
	$final_dues = array();
	while($row = mysqli_fetch_array($result))
	{
		$r = array();
		$r[] = $row['emailIdPaid'];
		$r[] = $row['firstName'] . " " . $row['lastName'];
		$r[] = $row['amount'] * -1.0;
		$final_dues[] = $r;
	}

	$query = "SELECT `emailIdPayee`, `firstName`, `lastName`, `amount` FROM `dues` JOIN `user` ON `emailId` = `emailIdPayee` WHERE `emailIdPaid` = '$email_id' AND `amount` != 0";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_array($result))
	{
		$r = array();
		$r[] = $row['emailIdPayee'];
		$r[] = $row['firstName'] . " " . $row['lastName'];
		$r[] = $row['amount'] * 1.0;
		$final_dues[] = $r;
	}
	return $final_dues;
}


function get_group_totals()
{
	include('connect.php');
	$email_id = $_SESSION['email_id'];
}

function get_groups()
{
	include('connect.php');
	$email_id = $_SESSION['email_id'];
	$query = "SELECT usergroups.groupId, grouptable.groupName FROM usergroups JOIN grouptable ON usergroups.groupId = grouptable.groupId WHERE usergroups.emailId = '$email_id'";
	
	$groups = array();
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_array($result))
	{
		$group = array();
		$group[] = $row['groupId'];
		$group[] = $row['groupName'];

		$groups[] = $group;
	}

	return $groups;
}

function run_big_query($query)
{
	include('connect.php');

	$result = mysqli_query($con, $query);

	$answer = array();
	while($row = mysqli_fetch_array($result))
	{
		$result_row = array();
		$result_row[] = $row['groupId'];
		$result_row[] = $row['total'];
		$result_row[] = $row['category'];
		$answer[] = $result_row;
	}

	return $answer;
}

function upload_image($name, $image)
{
	include('connect.php');
	$email_id = $_SESSION["email_id"];
    $qry="INSERT INTO `images` (`name`, `emailId`, `image`) VALUES ('$name', '$email_id', '$image')";
    $result=mysqli_query($con, $qry);
    return $result;
}

function update_password($email_Id, $password)
{
	include('connect.php');
	$password = md5($password);
	$query = "UPDATE `user` SET `password` = '$password' WHERE `emailId` = '$email_Id'";
	$result = mysqli_query($con, $query);

	return $result;
}


function get_personal_group_totals()
{
	include('connect.php');
	$email_id = $_SESSION['email_id'];

	$sql = "SELECT personalexpense.`groupId`, grouptable.`groupName`, SUM(amount) AS 'total' FROM grouptable,personalexpense WHERE grouptable.`groupId` = personalexpense.`groupId` AND personalexpense.`emailId` = '$email_id' GROUP BY personalexpense.`groupId` ORDER BY personalexpense.`groupId`";

	$result = mysqli_query($con, $sql);

	$personIngroup = array();
	while($row = mysqli_fetch_array($result))
	{
		$arr = array();
		$arr[] = $row["groupId"];
		$arr[] = $row["groupName"];
		$arr[] = $row["total"];
		$personIngroup[] = $arr;
	}
	return $personIngroup;
}

function get_personal_expense()
{
	include('connect.php');
	$email_id = $_SESSION['email_id'];
	$sql = "SELECT SUM(amount) AS 'total' From personalexpense WHERE groupId = 0 AND emailId = '$email_id'";

	$result = mysqli_query($con, $sql);
	$expense = array();
	while($row = mysqli_fetch_array($result))
	{
		$arr = array();
		$arr[] = $row['total'];
		$expense = $arr;
	}
	return $expense;
}

function get_group_expense()
{
	include('connect.php');
	$email_id = $_SESSION['email_id'];

	// $sql = "SELECT grouptable.groupId,grouptable.groupName,sum(amount) AS 'Total' from grouptable LEFT JOIN personalexpense ON grouptable.groupId = personalexpense.groupid GROUP BY groupId HAVING groupid <> 0";

	$sql = "SELECT personalexpense.`groupId`, grouptable.`groupName`, SUM(`amount`) AS 'total' FROM `personalexpense`, `grouptable` WHERE personalexpense.`groupId` = grouptable.`groupId` AND personalexpense.`groupId` IN (SELECT groupId FROM usergroups WHERE emailId = '$email_id') GROUP BY `groupId` ORDER BY personalexpense.`groupId`";

	$result = mysqli_query($con, $sql);
	$expense = array();

	while($row = mysqli_fetch_array($result))
	{
		$arr = array();
		$arr[] = $row["groupId"];
		$arr[] = $row["groupName"];
		$arr[] = $row["total"];
		$expense[] = $arr;
	}
	return $expense;
}

function add_todues($final_list_email)
{
	if(! in_array($_SESSION["email_id"], $final_list_email))
	{
		$final_list_email[] = $_SESSION["email_id"];
	}

	include('connect.php');

	foreach($final_list_email as $email_id_1)
	{
		foreach($final_list_email as $email_id_2)
		{
			if($email_id_1 != $email_id_2)
			{
				$sql_1 = "INSERT INTO `dues`(`emailIdPayee`, `emailIdPaid`) SELECT * FROM (SELECT '$email_id_1', '$email_id_2') AS xyz WHERE NOT EXISTS (SELECT `emailIdPayee`, `emailIdPaid` FROM `dues` WHERE `emailIdPayee` = '$email_id_1' AND `emailIdPaid` = '$email_id_2') LIMIT 1";

				$sql_2 = "INSERT INTO `dues`(`emailIdPayee`, `emailIdPaid`) SELECT * FROM (SELECT '$email_id_2', '$email_id_1') AS xyz WHERE NOT EXISTS (SELECT `emailIdPayee`, `emailIdPaid` FROM `dues` WHERE `emailIdPayee` = '$email_id_2' AND `emailIdPaid` = '$email_id_1') LIMIT 1";

				$result1 = mysqli_query($con, $sql_1);
				$result2 = mysqli_query($con, $sql_2);
			}	
		}
	}
}


function get_members_group($groupId)
{
	include('connect.php');

	$sql = "SELECT emailId FROM usergroups WHERE groupId = '$groupId' ORDER BY emailId";

	$query = mysqli_query($con, $sql);

	$result = array();

	while($row = mysqli_fetch_array($query))
	{
		$result[] = $row['emailId'];
	}

	return $result;
}

function get_all_dues($name_list)
{
	include('connect.php');
	$result = array();
	foreach($name_list as $email_1)
	{
		$for_payee = array();
		foreach($name_list as $email_2)
		{
			if($email_1 != $email_2)
			{
				$sql = "SELECT * FROM dues WHERE emailIdPayee = '$email_1' AND emailIdPaid = '$email_2'";
				$query = mysqli_query($con, $sql);
				while($row = mysqli_fetch_array($query))
				{
					$for_payee[$email_2] = (float)$row['amount'];
				}
			}
			else
			{
				$for_payee[$email_2] = 0;
			}
		}

		$result[$email_1] = $for_payee;
	}

	return $result;
}


function get_group_members_totals($param) 
{
	include('connect.php');
	$sql = "SELECT SUM(amount) AS amount, user.firstName FROM user, personalexpense where personalexpense.groupId = '$param' AND personalexpense.emailId = user.emailId GROUP BY user.firstName";
    $result = mysqli_query($con, $sql);
    return $result;
}


function add_group_expense($groupid, $description, $amount, $emailpaid, $category, $date, $dues)
{
	include('connect.php');

	$email=$_SESSION['email_id'];
	$sql = "INSERT INTO groupexpense(`groupId`,`emailIdAddedBy`, `description`, `amount`,`emailIdPaidBy`,`category`,`date`,`dues`) VALUES ('$groupid', '$email', '$description', '$amount', '$emailpaid', '$category', '$date', '$dues')";
	$query = mysqli_query($con, $sql);

	return $query;
}

function insert_database($results)
{
	include('connect.php');
	foreach((array)$results as $r)
	{
		$payee = $r[0];
		$paid = $r[1];
		$amount = $r[2];

		$sql = "UPDATE `dues` SET `amount` = '$amount' WHERE `emailIdPayee` = '$payee' AND `emailIdPaid` = '$paid'";
		
		$result = mysqli_query($con, $sql);

		if(! $result)
			return False;
	}
	return True;
}


function get_activity_expense()
{
	include('connect.php');

	#$sql = "SELECT `description`, SUM(`amount`) AS `amount`,`timestamp` FROM `user`, `personalexpense` WHERE personalexpense.emailId = user.emailId GROUP BY groupId ORDER BY timestamp DESC";
	$sql = "SELECT `description`,amount,`timestamp`,groupId FROM `user`, `personalexpense` WHERE personalexpense.emailId = '$_SESSION[email_id]' ORDER BY timestamp DESC";
	
    $result = mysqli_query($con, $sql);

    return $result;
}

function get_activity_group_add()
{
	include('connect.php');

	$email_id = $_SESSION['email_id'];

	$sql = "SELECT grouptable.emailId AS 'CreatedBy',user.firstName, grouptable.groupName,usergroups.emailId,usergroups.groupId, grouptable.timestamp FROM usergroups LEFT JOIN grouptable ON usergroups.groupId = grouptable.groupId, user WHERE usergroups.emailId = '$email_id' AND user.emailId = grouptable.emailId ORDER BY timestamp DESC";

	$result = mysqli_query($con, $sql);

	return $result;
}


function get_activity_payments()
{
	include('connect.php');

	$email_id = $_SESSION['email_id'];

	$sql = "SELECT *  FROM payment where emailIdPaid = '$email_id' OR emailIdPayee = '$email_id' ORDER BY timestamp DESC";
	
	$result = mysqli_query($con, $sql);

	return $result;
}

function get_due($emailPayee, $emailPaid)
{
	include('connect.php');

	$sql = "SELECT amount FROM dues WHERE emailIdPayee = '$emailPayee' AND emailIdPaid = '$emailPaid'";

	$result = mysqli_query($con, $sql);

	$r = mysqli_fetch_array($result)["amount"];

	return $r;
}

function settle_amount($emailPayee, $emailPaid, $amount)
{
	include('connect.php');

	$sql = "UPDATE dues SET `amount` = $amount WHERE `emailIdPayee` = '$emailPayee' AND `emailIdPaid` = '$emailPaid'";

	mysqli_query($con, $sql);
}

function add_to_payment($emailPaidBy, $emailPaidTo, $amount)
{
	include ('connect.php');

	$sql = "INSERT INTO payments (`emailIdPayee`, `emailIdPaid`, `amount`) VALUES ('$emailPaidBy', '$emailPaidTo', '$amount')";

	mysqli_query($con, $sql);
}

function add_to_personal_on_group($groupId, $description, $category, $date, $dues)
{
	include('connect.php');

	$dues = explode(",", $dues);

	foreach($dues as $d)
	{
		$x = explode(":", $d);
		$email = $x[0];
		$amt = (float)$x[1];

		$sql = "INSERT INTO personalexpense (`emailId`, `description`, `amount`, `groupId`, `date`, `category`) VALUES ('$email', '$description', '$amt', '$groupId', '$date', '$category')";

		mysqli_query($con, $sql);
	}
}
?>