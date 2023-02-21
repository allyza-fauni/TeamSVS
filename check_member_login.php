<?php

//check_member_login.php

include('database_connection.php');

session_start();

$member_emailid = '';
$member_password = '';
$error_member_emailid = '';
$error_member_password = '';
$error = 0;

if(empty($_POST["member_emailid"]))
{
	$error_member_emailid = 'Email Address is required';
	$error++;
}
else
{
	$member_emailid = $_POST["member_emailid"];
}

if(empty($_POST["member_password"]))
{	
	$error_member_password = 'Password is required';
	$error++;
}
else
{
	$member_password = $_POST["member_password"];
}

if($error == 0)
{
	$query = "
	SELECT * FROM tbl_member 
	WHERE member_emailid = '".$member_emailid."'
	";

	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$total_row = $statement->rowCount();
		if($total_row > 0)
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				if(password_verify($member_password, $row["member_password"]))
				{
					$_SESSION["member_id"] = $row["member_id"];
				}
				else
				{
					$error_member_password = "Wrong Password";
					$error++;
				}
			}
		}
		else
		{
			$error_member_emailid = "Wrong Email Address";
			$error++;
		}
	}
}

if($error > 0)
{
	$output = array(
		'error'			=>	true,
		'error_member_emailid'	=>	$error_member_emailid,
		'error_member_password'	=>	$error_member_password
	);
}
else
{
	$output = array(
		'success'		=>	true
	);
}
echo json_encode($output);
?>