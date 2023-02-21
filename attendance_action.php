<?php

//attendance_action.php

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
		WHERE tbl_attendance.member_id = '".$_SESSION["member_id"]."' AND (
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_dupmember.dupmember_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_date LIKE "%'.$_POST["search"]["value"].'%") 
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
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendance'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "Add")
	{
		$attendance_date = '';
		$error_attendance_date = '';
		$error = 0;
		if(empty($_POST["attendance_date"]))
		{
			$error_attendance_date = 'Attendance Date is required';
			$error++;
		}
		else
		{
			$attendance_date = $_POST["attendance_date"];
		}

		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_attendance_date'			=>	$error_attendance_date
			);
		}
		else
		{
			$dupmember_id = $_POST["dupmember_id"];
			$query = '
			SELECT attendance_date FROM tbl_attendance 
			WHERE member_id = "'.$_SESSION["member_id"].'" 
			AND attendance_date = "'.$attendance_date.'"
			';
			$statement = $connect->prepare($query);
			$statement->execute();
			if($statement->rowCount() > 0)
			{
				$output = array(
					'error'					=>	true,
					'error_attendance_date'	=>	'Attendance Data Already Exists on this date'
				);
			}
			else
			{
				for($count = 0; $count < count($dupmember_id); $count++)
				{
					$data = array(
						':dupmember_id'			=>	$dupmember_id[$count],
						':attendance_status'	=>	$_POST["attendance_status".$dupmember_id[$count].""],
						':attendance_date'		=>	$attendance_date,
						':member_id'			=>	$_SESSION["member_id"]
					);

					$query = "
					INSERT INTO tbl_attendance 
					(dupmember_id, attendance_status, attendance_date, member_id) 
					VALUES (:dupmember_id, :attendance_status, :attendance_date, :member_id)
					";
					$statement = $connect->prepare($query);
					$statement->execute($data);
				}
				$output = array(
					'success'		=>	'Data Added Successfully',
				);
			}
		}
		echo json_encode($output);
	}

	if($_POST["action"] == "index_fetch")
	{
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_dupmember 
		ON tbl_dupmember.dupmember_id = tbl_attendance.dupmember_id 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
		WHERE tbl_attendance.member_id = '".$_SESSION["member_id"]."' AND (
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_dupmember.dupmember_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_svs.svs_name LIKE "%'.$_POST["search"]["value"].'%" )
			';
		}
		$query .= 'GROUP BY tbl_dupmember.dupmember_id ';
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
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
			$sub_array[] = $row["dupmember_name"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = get_attendance_percentage($connect, $row["dupmember_id"]);
			$data[] = $sub_array;
		}
		$output = array(
			'draw'					=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_dupmember'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}
}
?>