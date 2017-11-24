<?php

include('init.php');

if(empty($_GET) === false)
{
	$emailPaidTo = $_GET['paidto_user_email'];
	echo "$emailPaidTo";
	$amount = $_GET['amount'];
	echo "$amount";
	$emailPaidBy = $_SESSION['email_id'];
	echo "$emailPaidBy";
	$ans = get_due($emailPaidBy, $emailPaidTo);

	if($ans <= $amount)
	{
		settle_amount($emailPaidBy, $emailPaidTo, $amount - $ans);

		add_to_payment($emailPaidBy, $emailPaidTo, $amount);

		echo "<script>alert('Payment Registered'); window.location.href='user_home.php';</script>";
	}

	else
	{
		settle_amount($emailPaidTo, $emailPaidBy, $ans - $amount);
		settle_amount($emailPaidBy, $emailPaidTo, 0);

		add_to_payment($emailPaidBy, $emailPaidTo, $amount);

		echo "<script>alert('Payment Registered'); window.location.href='user_home.php';</script>";
	}
}

else
{
	echo "<script>alert('Failed to Add Payment'); window.location.href='user_home.php';</script>";
}

?>