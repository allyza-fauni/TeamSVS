<?php

//dupmember_priv_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_dupmemberpriv 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_dupmemberpriv.dupmemberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_dupmemberpriv.dupmemberpriv_dob LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_dupmemberpriv.dupmemberpriv_id DESC 
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
			$sub_array[] = $row["dupmemberpriv_dob"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = '<button type="button" name="edit_dupmemberpriv" class="btn btn-primary btn-sm edit_dupmemberpriv" id="'.$row["dupmemberpriv_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_dupmemberpriv" class="btn btn-danger btn-sm delete_dupmemberpriv" id="'.$row["dupmemberpriv_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_dupmemberpriv'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$dupmemberpriv_name = '';
		$dupmemberpriv_dob = '';
		$dupmemberpriv_svs_id = '';
		$error_dupmemberpriv_name = '';
		$error_dupmemberpriv_dob = '';
		$error_dupmemberpriv_svs_id = '';
		$error = 0;
		if(empty($_POST["dupmemberpriv_name"]))
		{
			$error_dupmemberpriv_name = 'Member Name is required';
			$error++;
		}
		else
		{
			$dupmemberpriv_name = $_POST["dupmemberpriv_name"];
		}
		if(empty($_POST["dupmemberpriv_dob"]))
		{
			$error_dupmemberpriv_dob = 'Member Date Joined is required';
			$error++;
		}
		else
		{
			$dupmemberpriv_dob = $_POST["dupmemberpriv_dob"];
		}
		if(empty($_POST["dupmemberpriv_svs_id"]))
		{
			$error_dupmemberpriv_svs_id = "SVS Code is required";
			$error++;
		}
		else
		{
			$dupmemberpriv_svs_id = $_POST["dupmemberpriv_svs_id"];
		}
		if($error > 0)
		{
			$output = array(
				'error'								=>	true,
				'error_dupmemberpriv_name'			=>	$error_dupmemberpriv_name,
				'error_dupmemberpriv_dob'			=>	$error_dupmemberpriv_dob,
				'error_dupmemberpriv_svs_id'		=>	$error_dupmemberpriv_svs_id
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':dupmemberpriv_name'		=>	$dupmemberpriv_name,
					':dupmemberpriv_dob'		=>	$dupmemberpriv_dob,
					':dupmemberpriv_svs_id'	=>	$dupmemberpriv_svs_id
				);
				$query = "
				INSERT INTO tbl_dupmemberpriv 
				(dupmemberpriv_name, dupmemberpriv_dob, dupmemberpriv_svs_id) 
				VALUES (:dupmemberpriv_name, :dupmemberpriv_dob, :dupmemberpriv_svs_id)
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
					':dupmemberpriv_name'			=>	$dupmemberpriv_name,	
					':dupmemberpriv_dob'			=>	$dupmemberpriv_dob,
					':dupmemberpriv_svs_id'		=>	$dupmemberpriv_svs_id,
					':dupmemberpriv_id'			=>	$_POST["dupmemberpriv_id"]
				);
				$query = "
				UPDATE tbl_dupmemberpriv 
				SET dupmemberpriv_name = :dupmemberpriv_name, 
				dupmemberpriv_dob = :dupmemberpriv_dob, 
				dupmemberpriv_svs_id = :dupmemberpriv_svs_id 
				WHERE dupmemberpriv_id = :dupmemberpriv_id
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
		SELECT * FROM tbl_dupmemberpriv 
		WHERE dupmemberpriv_id = '".$_POST["dupmemberpriv_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["dupmemberpriv_name"] = $row["dupmemberpriv_name"];
				$output["dupmemberpriv_dob"] = $row["dupmemberprivpriv_dob"];
				$output["dupmemberpriv_svs_id"] = $row["dupmemberpriv_svs_id"];
				$output["dupmemberpriv_id"] = $row["dupmemberpriv_id"];
			}
			echo json_encode($output);
		}
	}
	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_dupmemberpriv 
		WHERE dupmemberpriv_id = '".$_POST["dupmemberpriv_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Delete Successfully';
		}
	}
}

?>