<?php

//member_priv_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_memberpriv 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_memberpriv.memberpriv_svs_id 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_memberpriv.memberpriv_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_memberpriv.memberpriv_emailid LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_memberpriv.memberpriv_id DESC 
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
			$sub_array[] = '<img src="member_priv_image/'.$row["memberpriv_image"].'" class="img-thumbnail" width="75" />';
			$sub_array[] = $row["memberpriv_name"];
			$sub_array[] = $row["memberpriv_emailid"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = '<button type="button" name="view_memberpriv" class="btn btn-info btn-sm view_memberpriv" id="'.$row["memberpriv_id"].'">View</button>';
			$sub_array[] = '<button type="button" name="edit_memberpriv" class="btn btn-primary btn-sm edit_memberpriv" id="'.$row["memberpriv_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_memberpriv" class="btn btn-danger btn-sm delete_memberpriv" id="'.$row["memberpriv_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_memberpriv'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$memberpriv_name = '';
		$memberpriv_emailid = '';
		$memberpriv_password = '';
		$memberpriv_svs_id = '';
		$memberpriv_doj = '';
		$memberpriv_image = '';
		$error_memberpriv_name = '';
		$error_memberpriv_emailid = '';
		$error_memberpriv_password = '';
		$error_memberpriv_svs_id = '';
		$error_memberpriv_doj = '';
		$error_memberpriv_image = '';
		$error = 0;

		$memberpriv_image = $_POST["hidden_memberpriv_image"];
		if($_FILES["memberpriv_image"]["name"] != '')
		{
			$file_name = $_FILES["memberpriv_image"]["name"];
			$tmp_name = $_FILES["memberpriv_image"]["tmp_name"];
			$extension_array = explode(".", $file_name);
			$extension = strtolower($extension_array[1]);
			$allowed_extension = array('jpg','png');
			if(!in_array($extension, $allowed_extension))
			{
				$error_memberpriv_image = 'Invalid Image Format';
				$error++;
			}
			else
			{
				$memberpriv_image = uniqid() . '.' . $extension;
				$upload_path = 'member_priv_image/' . $memberpriv_image;
				move_uploaded_file($tmp_name, $upload_path);
			}
		}
		else
		{
			if($memberpriv_image == '')
			{
				$error_memberpriv_image = 'Image is required';
				$error++;
			}
		}
		if(empty($_POST["memberpriv_name"]))
		{
			$error_memberpriv_name = 'Member Name is required';
			$error++;
		}
		else
		{
			$memberpriv_name = $_POST["memberpriv_name"];
		}
		if($_POST["action"] == "Add")
		{
			if(empty($_POST["memberpriv_emailid"]))
			{
				$error_memberpriv_emailid = 'Email Address is required';
				$error++;
			}
			else
			{
				if(!filter_var($_POST["memberpriv_emailid"], FILTER_VALIDATE_EMAIL))
				{
					$error_memberpriv_emailid = 'Invalid email format';
					$error++;
				}
				else
				{
					$memberpriv_emailid = $_POST["memberpriv_emailid"];
				}
			}
			if(empty($_POST["memberpriv_password"]))
			{
				$error_memberpriv_password = "Password is required";
				$error++;
			}
			else
			{
				$memberpriv_password = $_POST["memberpriv_password"];
			}
		}
		if(empty($_POST["memberpriv_svs_id"]))
		{
			$error_memberpriv_svs_id = "SVS Code is required";
			$error++;
		}
		else
		{
			$memberpriv_svs_id = $_POST["memberpriv_svs_id"];
		}
		if(empty($_POST["memberpriv_doj"]))
		{
			$error_memberpriv_doj = 'Date Joined Field is required';
			$error++;
		}
		else
		{
			$memberpriv_doj = $_POST["memberpriv_doj"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_memberpriv_name'			=>	$error_memberpriv_name,
				'error_memberpriv_emailid'			=>	$error_memberpriv_emailid,
				'error_memberpriv_password'		=>	$error_memberpriv_password,
				'error_memberpriv_svs_id'		=>	$error_memberpriv_svs_id,
				'error_memberpriv_doj'				=>	$error_memberpriv_doj,
				'error_memberpriv_image'			=>	$error_memberpriv_image
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':memberpriv_name'			=>	$memberpriv_name,
					':memberpriv_emailid'		=>	$memberpriv_emailid,
					':memberpriv_password'		=>	password_hash($memberpriv_password, PASSWORD_DEFAULT),
					':memberpriv_doj'			=>	$memberpriv_doj,
					':memberpriv_image'		=>	$memberpriv_image,
					':memberpriv_svs_id'		=>	$memberpriv_svs_id
				);
				$query = "
				INSERT INTO tbl_memberpriv 
				(memberpriv_name, memberpriv_emailid, memberpriv_password, memberpriv_doj, memberpriv_image, memberpriv_svs_id) 
				SELECT * FROM (SELECT :memberpriv_name, :memberpriv_emailid, :memberpriv_password, :memberpriv_doj, :memberpriv_image, :memberpriv_svs_id) as temp 
				WHERE NOT EXISTS (
					SELECT memberpriv_emailid FROM tbl_memberpriv WHERE memberpriv_emailid = :memberpriv_emailid
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
							'error_memberpriv_emailid'	=>	'Email Already Exists'
						);
					}
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':memberpriv_name'		=>	$memberpriv_name,
					':memberpriv_doj'		=>	$memberpriv_doj,
					':memberpriv_image'		=>	$memberpriv_image,
					':memberpriv_svs_id'	=>	$memberpriv_svs_id,
					':memberpriv_id'		=>	$_POST["memberpriv_id"]
				);
				$query = "
				UPDATE tbl_memberpriv 
				SET memberpriv_name = :memberpriv_name, 
				memberpriv_svs_id = :memberpriv_svs_id, 
				memberpriv_doj = :memberpriv_doj, 
				memberpriv_image = :memberpriv_image
				WHERE memberpriv_id = :memberpriv_id
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

	if($_POST["action"] == "single_fetch")
	{
		$query = "
		SELECT * FROM tbl_memberpriv 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_memberpriv.memberpriv_svs_id 
		WHERE tbl_memberpriv.memberpriv_id = '".$_POST["memberpriv_id"]."'";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			$output = '
			<div class="row">
			';
			foreach($result as $row)
			{
				$output .= '
				<div class="col-md-3">
					<img src="member_priv_image/'.$row["memberpriv_image"].'" class="img-thumbnail" />
				</div>
				<div class="col-md-9">
					<table class="table">
						<tr>
							<th>Name</th>
							<td>'.$row["memberpriv_name"].'</td>
						</tr>
						<tr>
							<th>Email Address</th>
							<td>'.$row["memberpriv_emailid"].'</td>
						</tr>
						<tr>
							<th>Date Joined</th>
							<td>'.$row["memberpriv_doj"].'</td>
						</tr>
						<tr>
							<th>SVS Code</th>
							<td>'.$row["svs_name"].'</td>
						</tr>
					</table>
				</div>
				';
			}
			$output .= '</div>';
			echo $output;
		}
	}

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_memberpriv WHERE memberpriv_id = '".$_POST["memberpriv_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["memberpriv_name"] = $row["memberpriv_name"];
				$output["memberpriv_doj"] = $row["memberpriv_doj"];
				$output["memberpriv_image"] = $row["memberpriv_image"];
				$output["memberpriv_svs_id"] = $row["memberpriv_svs_id"];
				$output["memberpriv_id"] = $row["memberpriv_id"];
			}
			echo json_encode($output);
		}
	}

	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_memberpriv 
		WHERE memberpriv_id = '".$_POST["memberpriv_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}
}
?>