<?php

//dupmember_priv_action.php

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
		INNER JOIN tbl_memberpriv 
		ON tbl_memberpriv.memberpriv_id = tbl_attendancepriv.memberpriv_id 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
				WHERE tbl_dupmemberpriv.dupmemberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_attendancepriv.attendancepriv_status LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_attendancepriv.attendancepriv_date LIKE "%'.$_POST["search"]["value"].'%" 
				OR tbl_memberpriv.memberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
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
			$sub_array[] = $row["memberpriv_name"];
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendancepriv'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "index_fetch")
	{
		$query = "
		SELECT * FROM tbl_dupmemberpriv 
		LEFT JOIN tbl_attendancepriv 
		ON tbl_attendancepriv.dupmemberpriv_id = tbl_dupmemberpriv.dupmemberpriv_id 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
		INNER JOIN tbl_memberpriv 
		ON tbl_memberpriv.memberpriv_svs_id = tbl_svs.svs_id  
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_dupmemberpriv.dupmemberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_svs.svs_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_memberpriv.memberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}
		$query .= 'GROUP BY tbl_dupmemberpriv.dupmemberpriv_id ';
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY tbl_dupmemberpriv.dupmemberpriv_name ASC ';
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
			$sub_array[] = $row["memberpriv_name"];
			$sub_array[] = get_attendancepriv_percentage($connect, $row["dupmemberpriv_id"]);
			$sub_array[] = '<button type="button" name="report_button" data-dupmemberpriv_id="'.$row["dupmemberpriv_id"].'" class="btn btn-info btn-sm report_button">Report</button>&nbsp;&nbsp;&nbsp;<button type="button" name="chart_button" data-dupmemberpriv_id="'.$row["dupmemberpriv_id"].'" class="btn btn-danger btn-sm report_button">Chart</button>
			';
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_dupmemberpriv'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}
}
?>