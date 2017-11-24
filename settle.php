<?php

include('init.php');

// $settle = array();

function getMin($arr)
{
	$min_index = 0;
	for($i = 0;$i<count($arr);$i++)
	{
		if($arr[$i] < $arr[$min_index])
		{
			$min_index = $i;
		}
	}

	return $min_index;
}

function getMax($arr)
{
	$max_index = 0;
	for($i = 0;$i<count($arr);$i++)
	{
		if($arr[$i] > $arr[$max_index])
		{
			$max_index = $i;
		}
	}

	return $max_index;
}

function minOf2($x, $y)
{
	return ($x<$y)?$x:$y;
}


function minCashFlowRec($amount, $name_list)
{
	global $settle;
	$mxCredit = getMax($amount);
	$mxDebit = getMin($amount);

	if($amount[$mxCredit] == 0 && $amount[$mxDebit] == 0)
	{
		return;
	}

	$min = minOf2(-$amount[$mxDebit], $amount[$mxCredit]);
	$amount[$mxCredit] -= $min;
	$amount[$mxDebit] += $min;

	// array_push($GLOBALS["settle"], array($name_list[$mxDebit], $name_list[$mxCredit], $min));

	$GLOBALS["settle"][] = array($name_list[$mxDebit], $name_list[$mxCredit], $min);

	minCashFlowRec($amount, $name_list);
}

function minCashFlow($graph, $name_list)
{
	$amount = array();
	for($i = 0;$i<count($graph);$i++)
	{
		$amount[] = 0;
	}

	for($p = 0;$p<count($graph);$p++)
	{
		for($i = 0;$i<count($graph);$i++)
		{
			$x = $name_list[$i];
			$y = $name_list[$p];
			$amount[$p] += ($graph[$x][$y] - $graph[$y][$x]);
		}
	}
	minCashFlowRec($amount, $name_list);
}

function settle($groupId, $dues, $paidBy)
{
	global $settle;
	// $dues = "pratul.ramkumar@gmail.com:250,user1@gmail.com:250,user2@gmail.com:250,user3@gmail.com:250";
	// $groupId = 27;
	// $paidBy = "pratul.ramkumar@gmail.com";

	$name_list = get_members_group($groupId);

	$dues = explode(",", $dues);
	$owe = array();

	foreach($dues as $d)
	{
		$split = explode(":", $d);
		$owe[$split[0]] = (float)$split[1];
	}

	$current_dues = get_all_dues($name_list);

	foreach($owe as $email => $x)
	{
		if($email != $paidBy)
		{
			$current_dues[$email][$paidBy] += $owe[$email];
		}
	}

	minCashFlow($current_dues, array_keys($current_dues));

	print_r($settle);

	foreach($current_dues as $key => $value)
	{
		foreach($current_dues[$key] as $i => $j)
		{
			$l = 0;
			if($j != 0) // earlier pay
			{
				foreach ((array)$settle as $s) 
				{
					if($s[0] == $key && $s[1] == $i)
					{
						// earlier pay, now also pay
						break;
					}
					$l++;
				}

				if(count($settle) == $l)
				{
					// now don't have to pay
					// update the existing record in database to zero
					$settle[] = array($key, $i, 0);
				}
			}
		}
	}

	echo "<br>";
	print_r($settle);

	$r = insert_database($settle);
	return $r;
}


settle("", "", "")
?>