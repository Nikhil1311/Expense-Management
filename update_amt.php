<?php

function update()
{
	// include('init.php');
	include('settle.php');

	$amt = $_GET['amt_array'];
	$arr = json_decode($amt);
	
	$description=$arr->description;
	$timestamp_str=$arr->dates;
	$timestamp = strtotime($timestamp_str);
	$emailpaid=$arr->emailpaid;
	$date = date('Y-m-d', $timestamp);
	$timestamp1=date('Y-m-d H:i:s', $timestamp);
	$groupid=$arr->groupid;
	$category=$arr->category;
	$amount=$arr->amnt;
	$str="";
	$len=count($amt);
	$count=0;
	foreach($arr as $x => $x_value) 
	{
			$len++;
	}
	
	foreach($arr as $x => $x_value) 
	{
		if($len-$count==7)
			break;
		else
			$count++;
		$str=$str.$x.":".$x_value.",";
	
	}

	$dues=substr($str,0,strlen($str)-1);

	// add group expense
	$query = add_group_expense($groupid, $description, $amount, $emailpaid, $category, $date, $dues);

	// fire personal on group expense
	add_to_personal_on_group($groupid, $description, $category, $date, $dues);

	echo $groupid;
	echo "<br>";
	echo $dues;
	echo "<br>";
	echo $emailpaid;

	// settling algorithm
	$r = settle($groupid, $dues, $emailpaid); // $groupId, $dues, $emailpaid

	if($query && $r)
	{
		echo "Success";
	}
	else
	{
		echo "Error";
	}
}
update();
?>