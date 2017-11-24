<?php
function check_group_members()
{ 
	session_start();
	//echo $_GET['group_name'];
	include('connect.php');
	$grp_names = $_GET['group_name'];
	$grp_id = $_GET['group_id'];
	$query_group_mem = "SELECT `emailId` FROM `usergroups` WHERE `groupId` =$grp_id";
	$result= mysqli_query($con, $query_group_mem);
	$ids = array();
	while($row = mysqli_fetch_array($result))
	{
		$ids[] = $row['emailId'];
	}
	//echo json_encode($ids);
	/*$query_group_mem1 = "SELECT `groupId` FROM `grouptable` WHERE `groupName` ='$grp_names'";
	$result1= mysqli_query($con, $query_group_mem1);
	$ids1 = array();
	while($row1 = mysqli_fetch_array($result1))
	{
		$ids1[] = $row1['groupId'];
		break;
	}*/
	$ass_arr=array();
	$ass_arr["mail"]=$ids;
	$ass_arr["id"]=$grp_id;
	if(count($result)) // if databse if non empty
	{
		echo json_encode($ass_arr);
		//echo "hi";
	}

	else // if database is empty
	{	
		$arr=array();
		echo json_encode($arr);
		//echo "hi";
	}
	//$car=array("co","hjnk");
	//echo json_encode($car);
}
check_group_members()
?>