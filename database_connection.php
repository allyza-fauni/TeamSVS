<?php

//database_connection.php

$connect = new PDO("mysql:host=localhost;dbname=attendance","root","allyzA6180");

$base_url = "http://localhost/svs/admin_index.php";

function get_total_records($connect, $table_name)
{
	$query = "SELECT * FROM $table_name";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function load_svs_list($connect)
{
	$query = "
	SELECT * FROM tbl_svs ORDER BY svs_name ASC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["svs_id"].'">'.$row["svs_name"].'</option>';
	}
	return $output;
}

function load_all_svs_list($connect)
{
	$query = "
	SELECT * FROM tbl_svs
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["svs_id"].'">'.$row["svs_name"].'</option>';
	}
	return $output;
}


function get_attendance_percentage($connect, $dupmember_id)
{
	$query = "
	SELECT 
		ROUND((SELECT COUNT(*) FROM tbl_attendance 
		WHERE attendance_status = 'Present' 
		AND dupmember_id =  '".$dupmember_id."') 
	* 100 / COUNT(*)) AS percentage FROM tbl_attendance 
	WHERE dupmember_id = '".$dupmember_id."'
	";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		if($row["percentage"] > 0)
		{
			return $row["percentage"] . '%';
		}
		else
		{
			return 'NA';
		}
	}
}

function get_attendancepriv_percentage($connect, $dupmemberpriv_id)
{
	$query = "
	SELECT 
		ROUND((SELECT COUNT(*) FROM tbl_attendancepriv 
		WHERE attendancepriv_status = 'Present' 
		AND dupmemberpriv_id =  '".$dupmemberpriv_id."') 
	* 100 / COUNT(*)) AS percentage FROM tbl_attendancepriv 
	WHERE dupmemberpriv_id = '".$dupmemberpriv_id."'
	";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		if($row["percentage"] > 0)
		{
			return $row["percentage"] . '%';
		}
		else
		{
			return 'NA';
		}
	}
}

function Get_dupmember_name($connect, $dupmember_id)
{
	$query = "
	SELECT dupmember_name FROM tbl_dupmember 
	WHERE dupmember_id = '".$dupmember_id."'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach($result as $row)
	{
		return $row["dupmember_name"];
	}
}

function Get_dupmember_svs_name($connect, $dupmember_id)
{
	$query = "
	SELECT tbl_svs.svs_name FROM tbl_dupmember 
	INNER JOIN tbl_svs 
	ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
	WHERE tbl_dupmember.dupmember_id = '".$dupmember_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['svs_name'];
	}
}

function Get_dupmember_member_name($connect, $dupmember_id)
{
	$query = "
	SELECT tbl_member.member_name 
	FROM tbl_dupmember 
	INNER JOIN tbl_svs 
	ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
	INNER JOIN tbl_member 
	ON tbl_member.member_svs_id = tbl_svs.svs_id 
	WHERE tbl_dupmember.dupmember_id = '".$dupmember_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row["member_name"];
	}
}

function Get_dupmemberpriv_name($connect, $dupmemberpriv_id)
{
	$query = "
	SELECT dupmemberpriv_name FROM tbl_dupmemberpriv 
	WHERE dupmemberpriv_id = '".$dupmemberpriv_id."'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach($result as $row)
	{
		return $row["dupmemberpriv_name"];
	}
}

function Get_dupmemberpriv_svs_name($connect, $dupmemberpriv_id)
{
	$query = "
	SELECT tbl_svs.svs_name FROM tbl_dupmemberpriv 
	INNER JOIN tbl_svs 
	ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
	WHERE tbl_dupmemberpriv.dupmemberpriv_id = '".$dupmemberpriv_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['svs_name'];
	}
}

function Get_dupmemberpriv_memberpriv_name($connect, $dupmemberpriv_id)
{
	$query = "
	SELECT tbl_memberpriv.memberpriv_name 
	FROM tbl_dupmemberpriv 
	INNER JOIN tbl_svs 
	ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
	INNER JOIN tbl_memberpriv 
	ON tbl_memberpriv.memberpriv_svs_id = tbl_svs.svs_id 
	WHERE tbl_dupmemberpriv.dupmemberpriv_id = '".$dupmemberpriv_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row["memberpriv_name"];
	}
}

function Get_svs_name($connect, $svs_id)
{
	$query = "
	SELECT svs_name FROM tbl_svs 
	WHERE svs_id = '".$svs_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row["svs_name"];
	}
}

?>