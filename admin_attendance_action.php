<?php

//dupmember_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_dupmember 
		ON tbl_dupmember.dupmember_id = tbl_attendance.dupmember_id 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
		INNER JOIN tbl_member 
		ON tbl_member.member_id = tbl_attendance.member_id 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
				WHERE tbl_dupmember.dupmember_name LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_attendance.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_attendance.attendance_date LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_member.member_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
			';
		}
		else
		{	
			$query .= '
			ORDER BY tbl_attendance.attendance_id DESC 
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach($result as $row)
		{
			$sub_array = array();
			$status = '';
			if($row["attendance_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}
			if($row["attendance_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}
			$sub_array[] = $row["dupmember_name"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			$sub_array[] = $row["member_name"];
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendance'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "index_fetch")
	{
		$query = "
		SELECT * FROM tbl_dupmember 
		LEFT JOIN tbl_attendance 
		ON tbl_attendance.dupmember_id = tbl_dupmember.dupmember_id 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
		INNER JOIN tbl_member 
		ON tbl_member.member_svs_id = tbl_svs.svs_id  
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_dupmember.dupmember_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_svs.svs_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_member.member_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}
		$query .= 'GROUP BY tbl_dupmember.dupmember_id ';
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY tbl_dupmember.dupmember_name ASC ';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["dupmember_name"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = $row["member_name"];
			$sub_array[] = get_attendance_percentage($connect, $row["dupmember_id"]);
			$sub_array[] = '<button type="button" name="report_button" data-dupmember_id="'.$row["dupmember_id"].'" class="btn btn-info btn-sm report_button">Report</button>&nbsp;&nbsp;&nbsp;<button type="button" name="chart_button" data-dupmember_id="'.$row["dupmember_id"].'" class="btn btn-danger btn-sm report_button">Chart</button>
			';
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_dupmember'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}
}
?>