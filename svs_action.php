<?php

//svs_action.php

include('database_connection.php');

session_start();

$output = '';

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "SELECT * FROM tbl_svs ";
		if(isset($_POST["search"]["value"]))
		{
			$query .= 'WHERE svs_name LIKE "%'.$_POST["search"]["value"].'%" ';
		}
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY svs_id DESC ';
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
			$sub_array[] = $row["svs_name"];
			$sub_array[] = '<button type="button" name="edit_svs" class="btn btn-primary btn-sm edit_svs" id="'.$row["svs_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_svs" class="btn btn-danger btn-sm delete_svs" id="'.$row["svs_id"].'">Delete</button>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"			=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_svs'),
			"data"				=>	$data
		);

		
	}
	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$svs_name = '';
		$error_svs_name = '';
		$error = 0;
		if(empty($_POST["svs_name"]))
		{
			$error_svs_name = 'Grade Name is required';
			$error++;
		}
		else
		{
			$svs_name = $_POST["svs_name"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_svs_name'				=>	$error_svs_name
			);
		}
		else
		{
			if($_POST["action"] == "Add")
			{
				$data = array(
					':svs_name'				=>	$svs_name
				);
				$query = "
				INSERT INTO tbl_svs 
				(svs_name) 
				SELECT * FROM (SELECT :svs_name) as temp 
				WHERE NOT EXISTS (
					SELECT svs_name FROM tbl_svs WHERE svs_name = :svs_name
				) LIMIT 1
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					if($statement->rowCount() > 0)
					{
						$output = array(
							'success'		=>	'Data Added Successfully',
						);
					}
					else
					{
						$output = array(
							'error'					=>	true,
							'error_svs_name'		=>	'SVS Code Already Exists'
						);
					}
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':svs_name'			=>	$svs_name,
					':svs_id'				=>	$_POST["svs_id"]
				);

				$query = "
				UPDATE tbl_svs 
				SET svs_name = :svs_name 
				WHERE svs_id = :svs_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'Data Updated Successfully',
					);
				}
			}
		}
	}

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_svs WHERE svs_id = '".$_POST["svs_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["svs_name"] = $row["svs_name"];
				$output["svs_id"] = $row["svs_id"];
			}
		}
	}

	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_svs 
		WHERE svs_id = '".$_POST["svs_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}

	echo json_encode($output);
}

?>