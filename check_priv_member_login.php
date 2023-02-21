<?php

//check_member_priv_login.php

include('database_connection.php');

session_start();

$memberpriv_emailid = '';
$memberpriv_password = '';
$error_memberpriv_emailid = '';
$error_memberpriv_password = '';
$error = 0;

if(empty($_POST["memberpriv_emailid"]))
{
	$error_memberpriv_emailid = 'Email Address is required';
	$error++;
}
else
{
	$memberpriv_emailid = $_POST["memberpriv_emailid"];
}

if(empty($_POST["memberpriv_password"]))
{	
	$error_memberpriv_password = 'Password is required';
	$error++;
}
else
{
	$memberpriv_password = $_POST["memberpriv_password"];
}

if($error == 0)
{
	$query = "
	SELECT * FROM tbl_memberpriv 
	WHERE memberpriv_emailid = '".$memberpriv_emailid."'
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
				if(password_verify($memberpriv_password, $row["memberpriv_password"]))
				{
					$_SESSION["memberpriv_id"] = $row["memberpriv_id"];
				}
				else
				{
					$error_memberpriv_password = "Wrong Password";
					$error++;
				}
			}
		}
		else
		{
			$error_memberpriv_emailid = "Wrong Email Address";
			$error++;
		}
	}
}

if($error > 0)
{
	$output = array(
		'error'			=>	true,
		'error_memberpriv_emailid'	=>	$error_memberpriv_emailid,
		'error_memberpriv_password'	=>	$error_memberpriv_password
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