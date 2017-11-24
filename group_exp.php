<?php
function check_group_name()
{ // function returns an array of all group names that every group member being added to the new grouo is part of
  // intends to check if any user being added to a new group is already part of a group with the same name	
	session_start();
	include('connect.php');
	$email_id = $_SESSION['email_id'];
	//echo $email_id;
	// form a comma separated list of all email IDs
	$query = "SELECT * FROM `usergroups` WHERE `emailId` = '$email_id'";
	$result = mysqli_query($con, $query);
	$ids = array();
	while($row = mysqli_fetch_array($result))
	{
		$ids[] = $row['groupId'];
	}
	$id_name=array();
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
		$id_name["id"]=$ids;
		$id_name["name"]=$groupnames;
		echo json_encode($id_name);
	}

	else // if database is empty
	{
		echo json_encode(array());
	}
}
check_group_name();
?>