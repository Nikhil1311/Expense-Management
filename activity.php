<?php

	include('init.php');
	
	$data = array();

	$result = get_activity_expense();

	while($row = $result->fetch_assoc()) 
	{
		$row['type'] = 1;
		$data[$row['timestamp']] = $row;
	}

	$result = get_activity_group_add();

	while($row = $result->fetch_assoc()) 
	{
		$row['type'] = 2;
		$data[$row['timestamp']] = $row;
	}

	$result = get_activity_payments();

	while($row = $result->fetch_assoc()) 
	{
		$row['type'] = 3;
		$data[$row['timestamp']] = $row;
	}

	krsort($data);
	
	$answer = array();
	foreach($data as $x=>$x_value)
	{
		   if($x_value['type'] == 1)
		   {
			   if($x_value['groupId'] == 0)
			   	$answer[] = "You spent <i> $x_value[amount] </i> for <i> $x_value[description] </i> on $x ";
			   else
			   	$answer[] = "You spent <i> $x_value[amount] </i> for <i> $x_value[description] </i> on $x in group";

			 }
		   else if($x_value['type'] == 2)
		   {
			   if($x_value['CreatedBy'] == 'user1@gmail.com')
					$answer[] = "You created group <i> $x_value[groupName] </i> on $x";
				else
					$answer[] = "You were added to group <i> $x_value[groupName] </i> by <i> $x_value[firstName] </i> on $x";
		   }
		   else
		   {
			   if($x_value['emailIdPaid'] == 'user1@gmail.com')
					$answer[] = "You paid <i> $x_value[amount] </i> to <i> $x_value[emailIdPayee] </i> on $x";
				else
					$answer[] = "You got payemnt of <i>$x_value[amount]</i> from <i> $x_value[emailIdPayee] </i> on $x";
		   }
	}

	echo json_encode($answer);
?>