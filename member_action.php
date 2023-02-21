<?php

//member_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_member 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_member.member_svs_id 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_member.member_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_member.member_emailid LIKE "%'.$_POST["search"]["value"].'%" 
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
			ORDER BY tbl_member.member_id DESC 
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
			$sub_array[] = '<img src="member_image/'.$row["member_image"].'" class="img-thumbnail" width="75" />';
			$sub_array[] = $row["member_name"];
			$sub_array[] = $row["member_emailid"];
			$sub_array[] = $row["svs_name"];
			$sub_array[] = '<button type="button" name="view_member" class="btn btn-info btn-sm view_member" id="'.$row["member_id"].'">View</button>';
			$sub_array[] = '<button type="button" name="edit_member" class="btn btn-primary btn-sm edit_member" id="'.$row["member_id"].'">Edit</button>';
			$sub_array[] = '<button type="button" name="delete_member" class="btn btn-danger btn-sm delete_member" id="'.$row["member_id"].'">Delete</button>';
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_member'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$member_name = '';
		$member_emailid = '';
		$member_password = '';
		$member_svs_id = '';
		$member_doj = '';
		$member_image = '';
		$error_member_name = '';
		$error_member_emailid = '';
		$error_member_password = '';
		$error_member_svs_id = '';
		$error_member_doj = '';
		$error_member_image = '';
		$error = 0;

		$member_image = $_POST["hidden_member_image"];
		if($_FILES["member_image"]["name"] != '')
		{
			$file_name = $_FILES["member_image"]["name"];
			$tmp_name = $_FILES["member_image"]["tmp_name"];
			$extension_array = explode(".", $file_name);
			$extension = strtolower($extension_array[1]);
			$allowed_extension = array('jpg','png');
			if(!in_array($extension, $allowed_extension))
			{
				$error_member_image = 'Invalid Image Format';
				$error++;
			}
			else
			{
				$member_image = uniqid() . '.' . $extension;
				$upload_path = 'member_image/' . $member_image;
				move_uploaded_file($tmp_name, $upload_path);
			}
		}
		else
		{
			if($member_image == '')
			{
				$error_member_image = 'Image is required';
				$error++;
			}
		}
		if(empty($_POST["member_name"]))
		{
			$error_member_name = 'Member Name is required';
			$error++;
		}
		else
		{
			$member_name = $_POST["member_name"];
		}
		if($_POST["action"] == "Add")
		{
			if(empty($_POST["member_emailid"]))
			{
				$error_member_emailid = 'Email Address is required';
				$error++;
			}
			else
			{
				if(!filter_var($_POST["member_emailid"], FILTER_VALIDATE_EMAIL))
				{
					$error_member_emailid = 'Invalid email format';
					$error++;
				}
				else
				{
					$member_emailid = $_POST["member_emailid"];
				}
			}
			if(empty($_POST["member_password"]))
			{
				$error_member_password = "Password is required";
				$error++;
			}
			else
			{
				$member_password = $_POST["member_password"];
			}
		}
		if(empty($_POST["member_svs_id"]))
		{
			$error_member_svs_id = "SVS Code is required";
			$error++;
		}
		else
		{
			$member_svs_id = $_POST["member_svs_id"];
		}
		if(empty($_POST["member_doj"]))
		{
			$error_member_doj = 'Date Joined Field is required';
			$error++;
		}
		else
		{
			$member_doj = $_POST["member_doj"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_member_name'			=>	$error_member_name,
				'error_member_emailid'			=>	$error_member_emailid,
				'error_member_password'		=>	$error_member_password,
				'error_member_svs_id'		=>	$error_member_svs_id,
				'error_member_doj'				=>	$error_member_doj,
				'error_member_image'			=>	$error_member_image
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':member_name'			=>	$member_name,
					':member_emailid'		=>	$member_emailid,
					':member_password'		=>	password_hash($member_password, PASSWORD_DEFAULT),
					':member_doj'			=>	$member_doj,
					':member_image'		=>	$member_image,
					':member_svs_id'		=>	$member_svs_id
				);
				$query = "
				INSERT INTO tbl_member 
				(member_name, member_emailid, member_password, member_doj, member_image, member_svs_id) 
				SELECT * FROM (SELECT :member_name, :member_emailid, :member_password, :member_doj, :member_image, :member_svs_id) as temp 
				WHERE NOT EXISTS (
					SELECT member_emailid FROM tbl_member WHERE member_emailid = :member_emailid
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
							'error_member_emailid'	=>	'Email Already Exists'
						);
					}
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':member_name'		=>	$member_name,
					':member_doj'		=>	$member_doj,
					':member_image'	=>	$member_image,
					':member_svs_id'	=>	$member_svs_id,
					':member_id'		=>	$_POST["member_id"]
				);
				$query = "
				UPDATE tbl_member 
				SET member_name = :member_name, 
				member_svs_id = :member_svs_id, 
				member_doj = :member_doj, 
				member_image = :member_image
				WHERE member_id = :member_id
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
		SELECT * FROM tbl_member 
		INNER JOIN tbl_svs 
		ON tbl_svs.svs_id = tbl_member.member_svs_id 
		WHERE tbl_member.member_id = '".$_POST["member_id"]."'";
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
					<img src="member_image/'.$row["member_image"].'" class="img-thumbnail" />
				</div>
				<div class="col-md-9">
					<table class="table">
						<tr>
							<th>Name</th>
							<td>'.$row["member_name"].'</td>
						</tr>
						<tr>
							<th>Email Address</th>
							<td>'.$row["member_emailid"].'</td>
						</tr>
						<tr>
							<th>Date Joined</th>
							<td>'.$row["member_doj"].'</td>
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
		SELECT * FROM tbl_member WHERE member_id = '".$_POST["member_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["member_name"] = $row["member_name"];
				$output["member_doj"] = $row["member_doj"];
				$output["member_image"] = $row["member_image"];
				$output["member_svs_id"] = $row["member_svs_id"];
				$output["member_id"] = $row["member_id"];
			}
			echo json_encode($output);
		}
	}

	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_member 
		WHERE member_id = '".$_POST["member_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}
}
?>