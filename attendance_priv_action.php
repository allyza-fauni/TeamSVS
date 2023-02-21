<?php

//attendance_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_attendancepriv 
		INNER JOIN tbl_dupmemberpriv 
		ON tbl_dupmemberpriv.dupmemberpriv_id = tbl_attendancepriv.dupmemberpriv_id 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
		WHERE tbl_attendancepriv.memberpriv_id = '".$_SESSION["memberpriv_id"]."' AND (
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_dupmemberpriv.dupmemberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendancepriv.attendancepriv_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendancepriv.attendancepriv_date LIKE "%'.$_POST["search"]["value"].'%") 
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
			ORDER BY tbl_attendancepriv.attendancepriv_id DESC 
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
			if($row["attendancepriv_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}

			if($row["attendancepriv_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}

			$sub_array[] = $row["dupmemberpriv_name"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendancepriv_date"];
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendancepriv'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "Add")
	{
		$attendancepriv_date = '';
		$error_attendancepriv_date = '';
		$error = 0;
		if(empty($_POST["attendancepriv_date"]))
		{
			$error_attendancepriv_date = 'Attendance Date is required';
			$error++;
		}
		else
		{
			$attendancepriv_date = $_POST["attendancepriv_date"];
		}

		if($error > 0)
		{
			$output = array(
				'error'								=>	true,
				'error_attendancepriv_date'			=>	$error_attendancepriv_date
			);
		}
		else
		{
			$dupmemberpriv_id = $_POST["dupmemberpriv_id"];
			$query = '
			SELECT attendancepriv_date FROM tbl_attendancepriv 
			WHERE memberpriv_id = "'.$_SESSION["memberpriv_id"].'" 
			AND attendancepriv_date = "'.$attendancepriv_date.'"
			';
			$statement = $connect->prepare($query);
			$statement->execute();
			if($statement->rowCount() > 0)
			{
				$output = array(
					'error'						=>	true,
					'error_attendancepriv_date'	=>	'Attendance Data Already Exists on this date'
				);
			}
			else
			{
				for($count = 0; $count < count($dupmemberpriv_id); $count++)
				{
					$data = array(
						':dupmemberpriv_id'			=>	$dupmemberpriv_id[$count],
						':attendancepriv_status'	=>	$_POST["attendancepriv_status".$dupmemberpriv_id[$count].""],
						':attendancepriv_date'		=>	$attendancepriv_date,
						':memberpriv_id'			=>	$_SESSION["memberpriv_id"]
					);

					$query = "
					INSERT INTO tbl_attendancepriv 
					(dupmemberpriv_id, attendancepriv_status, attendancepriv_date, memberpriv_id) 
					VALUES (:dupmemberpriv_id, :attendancepriv_status, :attendancepriv_date, :memberpriv_id)
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
		SELECT * FROM tbl_attendancepriv 
		INNER JOIN tbl_dupmemberpriv 
		ON tbl_dupmemberpriv.dupmemberpriv_id = tbl_attendancepriv.dupmemberpriv_id 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
		WHERE tbl_attendancepriv.memberpriv_id = '".$_SESSION["memberpriv_id"]."' AND (
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_dupmemberpriv.dupmemberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_svs.svs_name LIKE "%'.$_POST["search"]["value"].'%" )
			';
		}
		$query .= 'GROUP BY tbl_dupmemberpriv.dupmemberpriv_id ';
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
			$sub_array[] = $row["dupmemberpriv_name"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = get_attendancepriv_percentage($connect, $row["dupmemberpriv_id"]);
			$sub_array[] = '<button type="button" name="report_button" id="'.$row["dupmemberpriv_id"].'" class="btn btn-info btn-sm report_button">Report</button>';
			$data[] = $sub_array;
		}
		$output = array(
			'draw'					=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_dupmemberpriv'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}
}
?>