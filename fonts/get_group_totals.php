<?php
include('init.php');

$expenses = array();

$personal_totals = get_personal_expense();

if(is_null($personal_totals[0]))
{
	$expenses[] = 0;
}
else
{
	$expenses[] = $personal_totals;
}

$personal_group_totals = get_personal_group_totals();

// print_r($personal_group_totals);

if(empty($personal_group_totals))
{
	$expenses[] = 0;
}
else
{
	$expenses[] = $personal_group_totals;
}

$group_totals = get_group_expense();

// echo "<br>";
// print_r($group_totals);

if(empty($group_totals))
{
	$expenses[] = 0;
}
else
{
	$expenses[] = $group_totals;
}

if($expenses[1] != 0 && $expenses[2] != 0)
{
	// echo "<br>";
	if(count($personal_group_totals) != count($group_totals))
	{
		$updated_array = $personal_group_totals;
		$dict_personal_group_totals = array();

		// print_r($updated_array);
		// echo "<br>";

		foreach($personal_group_totals as $element)
		{	
			$temp = array($element[1], $element[2]);
			
			$dict_personal_group_totals[$element[0]] = $temp;
		}

		// print_r($dict_personal_group_totals);
		// echo "<br>";
		// echo count($group_totals);
		// echo "<br>";

		foreach($group_totals as $element)
		{
			$result = array_key_exists($element[0], $dict_personal_group_totals);
			if($result === false)
			{
				$updated_array[] = array($element[0], $element[1], "0");
			}
		}

		$expenses[1] = $updated_array;
	}
}

// print_r($expenses);
echo json_encode($expenses);

?>