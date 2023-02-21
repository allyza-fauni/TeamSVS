<?php

//dupmember_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_dupmember 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_dupmember.dupmember_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_dupmember.dupmember_dob LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_svs.svs_name LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_dupmember.dupmember_id DESC 
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
			$sub_array[] = $row["dupmember_dob"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = '<button type="button" name="edit_dupmember" class="btn btn-primary btn-sm edit_dupmember" id="'.$row["dupmember_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_dupmember" class="btn btn-danger btn-sm delete_dupmember" id="'.$row["dupmember_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_dupmember'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$dupmember_name = '';
		$dupmember_dob = '';
		$dupmember_svs_id = '';
		$error_dupmember_name = '';
		$error_dupmember_dob = '';
		$error_dupmember_svs_id = '';
		$error = 0;
		if(empty($_POST["dupmember_name"]))
		{
			$error_dupmember_name = 'Student Name is required';
			$error++;
		}
		else
		{
			$dupmember_name = $_POST["dupmember_name"];
		}
		if(empty($_POST["dupmember_dob"]))
		{
			$error_dupmember_dob = 'Student Date of Birth is required';
			$error++;
		}
		else
		{
			$dupmember_dob = $_POST["dupmember_dob"];
		}
		if(empty($_POST["dupmember_svs_id"]))
		{
			$error_dupmember_svs_id = "Grade is required";
			$error++;
		}
		else
		{
			$dupmember_svs_id = $_POST["dupmember_svs_id"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_dupmember_name'			=>	$error_dupmember_name,
				'error_dupmember_dob'				=>	$error_dupmember_dob,
				'error_dupmember_svs_id'		=>	$error_dupmember_svs_id
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':dupmember_name'		=>	$dupmember_name,
					':dupmember_dob'		=>	$dupmember_dob,
					':dupmember_svs_id'	=>	$dupmember_svs_id
				);
				$query = "
				INSERT INTO tbl_dupmember 
				(dupmember_name, dupmember_dob, dupmember_svs_id) 
				VALUES (:dupmember_name, :dupmember_dob, :dupmember_svs_id)
				";

				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'Data Added Successfully',
					);
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':dupmember_name'			=>	$dupmember_name,	
					':dupmember_dob'			=>	$dupmember_dob,
					':dupmember_svs_id'		=>	$dupmember_svs_id,
					':dupmember_id'			=>	$_POST["dupmember_id"]
				);
				$query = "
				UPDATE tbl_dupmember 
				SET dupmember_name = :dupmember_name, 
				dupmember_dob = :dupmember_dob, 
				dupmember_svs_id = :dupmember_svs_id 
				WHERE dupmember_id = :dupmember_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'Data Edited Successfully',
					);
				}
			}
		}
		echo json_encode($output);
	}

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_dupmember 
		WHERE dupmember_id = '".$_POST["dupmember_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["dupmember_name"] = $row["dupmember_name"];
				$output["dupmember_dob"] = $row["dupmember_dob"];
				$output["dupmember_svs_id"] = $row["dupmember_svs_id"];
				$output["dupmember_id"] = $row["dupmember_id"];
			}
			echo json_encode($output);
		}
	}
	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_dupmember 
		WHERE dupmember_id = '".$_POST["dupmember_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Delete Successfully';
		}
	}
}

?>